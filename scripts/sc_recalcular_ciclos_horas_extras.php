<?php
/**
 * Script de Recalculo Historico de Ciclos de Asistencia y Horas Extras
 * Puede ejecutarse por CLI o navegador web.
 *
 * Uso CLI:
 * php.exe scripts/sc_recalcular_ciclos_horas_extras.php [fecha_desde] [cod_ficha]
 */
define("SPECIALCONSTANT", true);
if (php_sapi_name() !== 'cli') {
    // Si corre por navegador, verificar sesion
    @session_start();
}

$base_dir = dirname(__FILE__) . '/..';
require_once($base_dir . '/funciones/funciones.php');
require_once($base_dir . '/autentificacion/aut_config.inc.php');
require_once($base_dir . '/' . class_bdI);

$bd = new DataBase();

$is_cli = (php_sapi_name() === 'cli');

function out($msg) {
    global $is_cli;
    if ($is_cli) {
        echo $msg . "\n";
    } else {
        echo nl2br(htmlspecialchars($msg)) . "<br/>";
        @flush();
    }
}

out("=================================================================");
out("INICIANDO RECALCULO HISTORICO DE CICLOS Y HORAS EXTRAS");
out("=================================================================");

// 1. Obtener configuracion de control
$sql_ctrl = "SELECT IFNULL(calc_he_auto, 'S') AS calc_auto,
                    IFNULL(dias_ciclo_he, 56) AS dias_ciclo,
                    IFNULL(max_horas_ciclo, 320.00) AS max_horas
               FROM control LIMIT 1";
$query_ctrl = $bd->consultar($sql_ctrl);
$ctrl = $bd->obtener_fila($query_ctrl, 0);

$dias_ciclo = (int)$ctrl['dias_ciclo'];
$max_horas  = (float)$ctrl['max_horas'];

out("Configuracion: Ciclo de {$dias_ciclo} dias | Limite de horas normales: {$max_horas} hrs.");

// Filtro opcional por linea de comando
$filtro_fecha = "";
$filtro_ficha = "";
if ($is_cli && isset($argv[1]) && !empty($argv[1]) && strtoupper($argv[1]) !== 'ALL') {
    $filtro_fecha = $argv[1];
    out("Filtro fecha desde: {$filtro_fecha}");
}
if ($is_cli && isset($argv[2]) && !empty($argv[2])) {
    $filtro_ficha = $argv[2];
    out("Filtro ficha: {$filtro_ficha}");
}

// 2. Cargar catalogo de conceptos y saber cuales computan
$conceptos_map = array();
$sql_c = "SELECT c.codigo, c.cod_horario, IFNULL(h.horas_trabajo, 0.00) AS horas_trabajo,
                 IFNULL(h.tipo_hora_extra, 'DIURNO') AS tipo_hora_extra,
                 IF(cc.cod_concepto IS NOT NULL, 1, 0) AS computa
            FROM conceptos c
            LEFT JOIN horarios h ON c.cod_horario = h.codigo
            LEFT JOIN control_conceptos_he cc ON c.codigo = cc.cod_concepto";
try {
    $query_c = $bd->consultar($sql_c);
    while ($c = $bd->obtener_fila($query_c)) {
        $conceptos_map[$c['codigo']] = array(
            'horas'   => (float)$c['horas_trabajo'],
            'tipo_he' => $c['tipo_hora_extra'],
            'computa' => ($c['computa'] == 1 && (float)$c['horas_trabajo'] > 0)
        );
    }
} catch (Exception $e) {
    out("Error cargando conceptos: " . $e->getMessage());
    exit(1);
}

// 3. Obtener lista de trabajadores que tienen asistencia registrada
$sql_fichas = "SELECT DISTINCT a.cod_ficha, f.fec_ingreso, f.fec_inicio_ciclo,
                      CONCAT(f.apellidos, ' ', f.nombres) AS trabajador
                 FROM asistencia a
                 INNER JOIN ficha f ON a.cod_ficha = f.cod_ficha
                WHERE 1 = 1 ";
if (!empty($filtro_ficha)) {
    $sql_fichas .= " AND a.cod_ficha = '{$filtro_ficha}' ";
}
$sql_fichas .= " ORDER BY a.cod_ficha ASC";

$query_fichas = $bd->consultar($sql_fichas);
$total_fichas = 0;
$total_asistencias_recalculadas = 0;
$total_he_generadas = 0;

while ($f = $bd->obtener_fila($query_fichas, 0)) {
    $cod_ficha = $f['cod_ficha'];
    $nombre    = $f['trabajador'];
    $fec_inicio_ciclo = $f['fec_inicio_ciclo'];
    $total_fichas++;

    // Si no tiene fec_inicio_ciclo, determinarla por su primera asistencia laborada
    if (empty($fec_inicio_ciclo) || $fec_inicio_ciclo == '0000-00-00') {
        $sql_primera = "SELECT MIN(ap.fec_diaria) AS primera_fecha
                          FROM asistencia a
                          INNER JOIN asistencia_apertura ap ON a.cod_as_apertura = ap.codigo
                          INNER JOIN control_conceptos_he cc ON a.cod_concepto = cc.cod_concepto
                         WHERE a.cod_ficha = '{$cod_ficha}'";
        $q_primera = $bd->consultar($sql_primera);
        $r_primera = $bd->obtener_fila($q_primera, 0);
        $fec_inicio_ciclo = !empty($r_primera['primera_fecha']) ? $r_primera['primera_fecha'] : $f['fec_ingreso'];

        if (empty($fec_inicio_ciclo) || $fec_inicio_ciclo == '0000-00-00') {
            continue; // No hay fecha base para este trabajador
        }

        // Actualizar ficha con fec_inicio_ciclo
        $bd->consultar("UPDATE ficha SET fec_inicio_ciclo = '{$fec_inicio_ciclo}' WHERE cod_ficha = '{$cod_ficha}'");
    }

    // 4. Obtener cronologicamente todas las asistencias del trabajador
    $sql_asist = "SELECT a.cod_as_apertura, a.cod_ficha, a.cod_ubicacion, a.cod_concepto,
                         a.hora_extra, a.hora_extra_n, ap.fec_diaria, c.cod_horario
                    FROM asistencia a
                    INNER JOIN asistencia_apertura ap ON a.cod_as_apertura = ap.codigo
                    INNER JOIN conceptos c ON a.cod_concepto = c.codigo
                   WHERE a.cod_ficha = '{$cod_ficha}' ";
    if (!empty($filtro_fecha)) {
        $sql_asist .= " AND ap.fec_diaria >= '{$filtro_fecha}' ";
    }
    $sql_asist .= " ORDER BY ap.fec_diaria ASC, a.cod_as_apertura ASC";

    $q_asist = $bd->consultar($sql_asist);

    $ciclo_actual = -1;
    $acumulado_normal = 0.00;
    $he_trabajador = 0.00;
    $he_ciclo_actual = 0.00;
    $asist_ciclo_actual = 0;
    $count_asist = 0;

    while ($as = $bd->obtener_fila($q_asist, 0)) {
        $count_asist++;
        $fec_diaria = $as['fec_diaria'];

        // Calcular numero de ciclo
        $dias_dif = (int)((strtotime($fec_diaria) - strtotime($fec_inicio_ciclo)) / 86400);
        if ($dias_dif < 0) {
            $num_ciclo = 0;
        } else {
            $num_ciclo = (int)floor($dias_dif / $dias_ciclo);
        }

        // Si cambia de ciclo, el contador vuelve a cero
        if ($num_ciclo !== $ciclo_actual) {
            $ciclo_actual = $num_ciclo;
            $acumulado_normal = 0.00;
            $he_ciclo_actual = 0.00;
            $asist_ciclo_actual = 0;
        }
        $asist_ciclo_actual++;

        $cod_conc = $as['cod_concepto'];
        $computa = isset($conceptos_map[$cod_conc]) && $conceptos_map[$cod_conc]['computa'];
        $horas_jornada = $computa ? $conceptos_map[$cod_conc]['horas'] : 0.00;
        $tipo_he = isset($conceptos_map[$cod_conc]) ? $conceptos_map[$cod_conc]['tipo_he'] : 'DIURNO';

        $he_nueva = 0.00;
        if ($computa && $horas_jornada > 0) {
            if ($acumulado_normal >= $max_horas) {
                // Ya supero las 320 horas: toda la jornada pasa a horas extras
                $he_nueva = $horas_jornada;
            } elseif (($acumulado_normal + $horas_jornada) > $max_horas) {
                // Cruce del umbral: completa a 320 normales y el exceso a extras
                $he_nueva = ($acumulado_normal + $horas_jornada) - $max_horas;
                $acumulado_normal = $max_horas;
            } else {
                // Dentro del rango normal
                $acumulado_normal += $horas_jornada;
                $he_nueva = 0.00;
            }
        }

        $he_d_nueva = ($tipo_he === 'NOCTURNO') ? 0.00 : $he_nueva;
        $he_n_nueva = ($tipo_he === 'NOCTURNO') ? $he_nueva : 0.00;

        // Si los valores calculados difieren de los que estan guardados, actualizar BD
        if ((float)$as['hora_extra'] != $he_d_nueva || (float)$as['hora_extra_n'] != $he_n_nueva) {
            $sql_upd = "UPDATE asistencia
                           SET hora_extra = '{$he_d_nueva}',
                               hora_extra_n = '{$he_n_nueva}'
                         WHERE cod_as_apertura = '{$as['cod_as_apertura']}'
                           AND cod_ficha = '{$as['cod_ficha']}'
                           AND cod_ubicacion = '{$as['cod_ubicacion']}'
                           AND cod_concepto = '{$as['cod_concepto']}'";
            $bd->consultar($sql_upd);
            $total_asistencias_recalculadas++;
        }

        $he_trabajador += $he_nueva;
        $he_ciclo_actual += $he_nueva;
    }

    $total_he_generadas += $he_trabajador;

    // Fechas del ciclo actual
    if ($ciclo_actual >= 0) {
        $ciclo_dias_ini = $ciclo_actual * $dias_ciclo;
        $ciclo_dias_fin = ($ciclo_actual + 1) * $dias_ciclo - 1;
        $fec_ciclo_actual_ini = date('Y-m-d', strtotime("{$fec_inicio_ciclo} + {$ciclo_dias_ini} days"));
        $fec_ciclo_actual_fin = date('Y-m-d', strtotime("{$fec_inicio_ciclo} + {$ciclo_dias_fin} days"));
        $horas_faltantes = max(0, $max_horas - $acumulado_normal);
        $total_ciclos = $ciclo_actual + 1;

        out("-----------------------------------------------------------------");
        out("Trabajador: {$cod_ficha} - {$nombre}");
        out("  [Historico General]");
        out("    - Fecha Base Inicio: {$fec_inicio_ciclo} | Asistencias: {$count_asist} | Ciclos Totales: {$total_ciclos}");
        out("    - Sumatoria Total HE Historicas (3+ anos): {$he_trabajador} hrs.");
        out("  [Estado del Ciclo Actual (Ciclo #{$ciclo_actual})]");
        out("    - Periodo del Ciclo: {$fec_ciclo_actual_ini} al {$fec_ciclo_actual_fin} ({$dias_ciclo} dias)");
        out("    - Asistencias registradas en ciclo: {$asist_ciclo_actual}");
        out("    - Horas Normales Acumuladas: {$acumulado_normal} / {$max_horas} hrs");
        out("    - Horas Extras Generadas en este Ciclo: {$he_ciclo_actual} hrs");
        out("    - Horas que le Faltan para Superar Umbral: {$horas_faltantes} hrs");
    } else {
        out("Ficha: {$cod_ficha} | {$nombre} | Sin asistencias en el periodo.");
    }
}

out("=================================================================");
out("RESUMEN DE PROCESAMIENTO:");
out("Total Trabajadores Procesados: {$total_fichas}");
out("Total Asistencias Actualizadas: {$total_asistencias_recalculadas}");
out("Total Horas Extras Calculadas: {$total_he_generadas} hrs.");
out("=================================================================");
