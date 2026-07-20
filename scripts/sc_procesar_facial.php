<?php
define("SPECIALCONSTANT", true);
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bdI);
$bd = new DataBase();

$apertura   = $_POST['apertura'];
$fec_diaria = $_POST['fec_diaria'];
$rol        = $_POST['rol'];
$contracto  = $_POST['contracto'];
$usuario    = $_POST['usuario'];

if (!isset($apertura) || !isset($fec_diaria) || !isset($rol) || !isset($contracto)) {
    echo "Faltan parámetros requeridos para procesar.";
    exit();
}

$apertura   = mysql_real_escape_string($apertura);
$fec_diaria = mysql_real_escape_string($fec_diaria);
$rol        = mysql_real_escape_string($rol);
$contracto  = mysql_real_escape_string($contracto);
$usuario    = mysql_real_escape_string($usuario);

$CONCEPTOS_NOVEDAD = array('V', 'RSS', 'PR', 'PNR', 'PPATER');
$CONCEPTOS_TURNO    = array('D', 'N', 'M', '24');

// ---------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------

// Compara una hora ('HH:MM:SS') contra una ventana de marcaje.
// Devuelve true/false, o null si la ventana no está configurada en horarios.
function enVentana($hora_str, $ini, $fin) {
    if (empty($ini) || empty($fin)) {
        return null; // ventana no configurada en horarios.inicio_marc_entrada/fin_marc_entrada
    }
    if ($ini == $fin) {
        return true; // jornada de 24h (ej. horario '09'): ventana abierta todo el día por diseño
    }
    if ($ini > $fin) {
        // la ventana cruza medianoche (ej. 17:50 - 06:10)
        return ($hora_str >= $ini || $hora_str <= $fin);
    }
    return ($hora_str >= $ini && $hora_str <= $fin);
}

// De un array de marcas ('HH:MM:SS' ascendente) del día, devuelve la primera
// que caiga dentro de la ventana; si ninguna cae dentro, devuelve la más
// temprana del día (para poder informar "marcó a las HH:MM fuera de ventana").
function resolverMarcaje($marcas, $ini, $fin) {
    if (empty($marcas)) {
        return array('hora' => null, 'en_ventana' => false);
    }
    foreach ($marcas as $h) {
        if (enVentana($h, $ini, $fin) === true) {
            return array('hora' => $h, 'en_ventana' => true);
        }
    }
    return array('hora' => $marcas[0], 'en_ventana' => false);
}

function esFeriado($bd, $rol, $fecha) {
    $sql = "SELECT COUNT(*) AS cnt
              FROM nom_calendario_det ncd
              INNER JOIN roles_calendario rc ON ncd.cod_calendario = rc.cod_calendario
             WHERE rc.cod_rol = '$rol' AND ncd.fecha = '$fecha'";
    $q = $bd->consultar($sql);
    $r = $bd->obtener_fila($q, 0);
    return ($r && $r['cnt'] > 0);
}

// Busca el ID de un concepto por su abreviatura. Devuelve null si no existe
// (el llamador debe abstenerse de insertar en ese caso, nunca inventar un
// concepto de reemplazo).
function buscarConceptoId($bd, $abrev) {
    $abrev_esc = mysql_real_escape_string($abrev);
    $sql = "SELECT codigo FROM conceptos WHERE abrev = '$abrev_esc' AND status = 'T' LIMIT 1;";
    $q = $bd->consultar($sql);
    if ($r = $bd->obtener_fila($q, 0)) {
        return $r['codigo'];
    }
    return null;
}

// Trae, para un conjunto de fichas, todas las marcas de un día puntual,
// agrupadas por ficha y ordenadas ascendentemente (evita N+1 por trabajador).
function marcasPorFicha($bd, $fichas, $fecha) {
    $marcas = array();
    if (empty($fichas)) {
        return $marcas;
    }
    $lista = array();
    foreach ($fichas as $f) {
        $lista[] = "'" . mysql_real_escape_string($f) . "'";
    }
    $sql = "SELECT cod_ficha, fecha_dispositivo
              FROM historial_asistencia_facial
             WHERE cod_ficha IN (" . implode(',', $lista) . ")
               AND DATE(fecha_dispositivo) = '$fecha'
          ORDER BY fecha_dispositivo ASC;";
    $q = $bd->consultar($sql);
    while ($row = $bd->obtener_fila($q, 0)) {
        $hora = substr($row['fecha_dispositivo'], 11, 8);
        if (!isset($marcas[$row['cod_ficha']])) {
            $marcas[$row['cod_ficha']] = array();
        }
        $marcas[$row['cod_ficha']][] = $hora;
    }
    return $marcas;
}

// Ventana de marcaje de entrada asociada a un concepto (ej. 'D' o 'N'),
// resuelta dinámicamente contra horarios en vez de hardcodear códigos.
// Incluye cod_horario para poder buscar overrides de horario_cl_ubicacion.
function ventanaPorConcepto($bd, $abrev) {
    $abrev_esc = mysql_real_escape_string($abrev);
    $sql = "SELECT h.codigo AS cod_horario, h.inicio_marc_entrada, h.fin_marc_entrada
              FROM horarios h
              INNER JOIN conceptos cc ON h.cod_concepto = cc.codigo
             WHERE cc.abrev = '$abrev_esc' AND h.status = 'T'
             LIMIT 1;";
    $q = $bd->consultar($sql);
    if ($r = $bd->obtener_fila($q, 0)) {
        return array('cod_horario' => $r['cod_horario'], 'ini' => $r['inicio_marc_entrada'], 'fin' => $r['fin_marc_entrada']);
    }
    return array('cod_horario' => null, 'ini' => null, 'fin' => null);
}

// Ventana de marcaje personalizada para una combinación (ubicación, cargo,
// horario) — tabla horario_cl_ubicacion, gestionada desde Cons_control.php.
// Devuelve null si no hay override (el llamador debe usar la ventana del
// horario estándar sin cambios). Si hay override pero sin rango configurado
// (caso de los registros históricos que solo tienen hora_entrada), devuelve
// una ventana "abierta" (ini == fin, misma regla que ya usa enVentana() para
// el horario 24h) marcada con 'abierta' => true, para que el llamador proponga
// REVISAR en vez de AUTO aunque el marcaje caiga dentro.
function buscarOverrideHorario($bd, $ubicacion, $cargo, $cod_horario) {
    if (empty($cod_horario)) {
        return null;
    }
    $ubicacion_esc = mysql_real_escape_string($ubicacion);
    $cargo_esc     = mysql_real_escape_string($cargo);
    $horario_esc   = mysql_real_escape_string($cod_horario);
    $sql = "SELECT hora_entrada, inicio_marc_entrada, fin_marc_entrada
              FROM horario_cl_ubicacion
             WHERE cod_cl_ubicacion = '$ubicacion_esc'
               AND cod_cargo = '$cargo_esc'
               AND cod_horario = '$horario_esc'
             LIMIT 1;";
    $q = $bd->consultar($sql);
    $r = $bd->obtener_fila($q, 0);
    if (!$r) {
        return null;
    }
    if (empty($r['inicio_marc_entrada']) || empty($r['fin_marc_entrada'])) {
        return array('ini' => $r['hora_entrada'], 'fin' => $r['hora_entrada'], 'abierta' => true);
    }
    return array('ini' => $r['inicio_marc_entrada'], 'fin' => $r['fin_marc_entrada'], 'abierta' => false);
}

// Versión en lote de buscarOverrideHorario() para PASE 1 (evita N+1 sobre el
// roster completo). Devuelve un mapa "ubicacion|cargo|horario" => override.
function overridesPorUbicCargoHorario($bd, $filas_planif) {
    $mapa = array();
    if (empty($filas_planif)) {
        return $mapa;
    }
    $condiciones = array();
    $claves_vistas = array();
    foreach ($filas_planif as $row) {
        $clave = $row['cod_ubicacion'] . '|' . $row['cod_cargo'] . '|' . $row['cod_horario'];
        if (isset($claves_vistas[$clave])) {
            continue;
        }
        $claves_vistas[$clave] = true;
        $ubicacion_esc = mysql_real_escape_string($row['cod_ubicacion']);
        $cargo_esc     = mysql_real_escape_string($row['cod_cargo']);
        $horario_esc   = mysql_real_escape_string($row['cod_horario']);
        $condiciones[] = "(cod_cl_ubicacion = '$ubicacion_esc' AND cod_cargo = '$cargo_esc' AND cod_horario = '$horario_esc')";
    }
    if (empty($condiciones)) {
        return $mapa;
    }
    $sql = "SELECT cod_cl_ubicacion, cod_cargo, cod_horario, hora_entrada, inicio_marc_entrada, fin_marc_entrada
              FROM horario_cl_ubicacion
             WHERE " . implode(' OR ', $condiciones) . ";";
    $q = $bd->consultar($sql);
    while ($r = $bd->obtener_fila($q, 0)) {
        $clave = $r['cod_cl_ubicacion'] . '|' . $r['cod_cargo'] . '|' . $r['cod_horario'];
        if (empty($r['inicio_marc_entrada']) || empty($r['fin_marc_entrada'])) {
            $mapa[$clave] = array('ini' => $r['hora_entrada'], 'fin' => $r['hora_entrada'], 'abierta' => true);
        } else {
            $mapa[$clave] = array('ini' => $r['inicio_marc_entrada'], 'fin' => $r['fin_marc_entrada'], 'abierta' => false);
        }
    }
    return $mapa;
}

// Inserta una propuesta de asistencia (turno base, redoble o huérfana) y
// marca sus columnas de flag. No inserta si el concepto destino no existe,
// ni si ya existe una fila con esa misma clave (ficha, ubicación, concepto).
function proponerAsistencia($bd, $apertura, $ficha, $cliente, $ubicacion, $cargo, $usuario, $concepto_abrev, $feriado, $modo, $obs) {
    $concepto_id = buscarConceptoId($bd, $concepto_abrev);
    if ($concepto_id === null) {
        error_log("sc_procesar_facial: concepto '$concepto_abrev' no existe en catálogo; se omite ficha $ficha apertura $apertura");
        return false;
    }

    $sql_existe = "SELECT 1 FROM asistencia
                     WHERE cod_as_apertura = '$apertura'
                       AND cod_ficha = '$ficha'
                       AND cod_ubicacion = '$ubicacion'
                       AND cod_concepto = '$concepto_id'
                     LIMIT 1;";
    $q_existe = $bd->consultar($sql_existe);
    if ($bd->obtener_fila($q_existe, 0)) {
        return false; // ya fue propuesto/cargado antes, no duplicar
    }

    $feriado_param = $feriado ? '1' : '0';
    $sql_call = "CALL p_asistencia('agregar', '$apertura', '$ficha', '$cliente',
                                   '$ubicacion', '', '$concepto_id', '',
                                   NULL, '0.00', '0.00', '0', '$feriado_param',
                                   '0', '$usuario', '$cargo');";
    $bd->consultar($sql_call);

    $obs_esc = mysql_real_escape_string($obs);
    $sql_update_props = "UPDATE asistencia
                             SET prop_modo = '$modo',
                                 prop_observacion = '$obs_esc'
                           WHERE cod_as_apertura = '$apertura'
                             AND cod_ficha = '$ficha'
                             AND cod_ubicacion = '$ubicacion'
                             AND cod_concepto = '$concepto_id';";
    $bd->consultar($sql_update_props);

    return true;
}

// ---------------------------------------------------------------------
// Precómputo válido para toda la ejecución (mismo rol/fecha para todas las filas)
// ---------------------------------------------------------------------
$es_feriado_dia = esFeriado($bd, $rol, $fec_diaria);

$fecha_siguiente     = date('Y-m-d', strtotime($fec_diaria . ' +1 day'));
$es_feriado_siguiente = esFeriado($bd, $rol, $fecha_siguiente);

$vent_diurno   = ventanaPorConcepto($bd, 'D');
$vent_nocturno = ventanaPorConcepto($bd, 'N');

$hoy   = date('Y-m-d');
$ahora = date('H:i:s');

$registros_procesados = 0;

// ---------------------------------------------------------------------
// PASE 1: turnos planificados
// ---------------------------------------------------------------------
$sql_planificados = "SELECT
                        pctd.codigo AS cod_planif,
                        pctd.cod_ficha,
                        pctd.cod_cliente,
                        pctd.cod_ubicacion,
                        f.cod_cargo,
                        cc.codigo AS cod_concepto_original,
                        cc.abrev AS concepto_abrev,
                        h.codigo AS cod_horario,
                        h.inicio_marc_entrada,
                        h.fin_marc_entrada
                     FROM planif_clientes_trab_det pctd
                     INNER JOIN ficha f ON pctd.cod_ficha = f.cod_ficha
                     INNER JOIN trab_roles tr ON f.cod_ficha = tr.cod_ficha AND tr.cod_rol = '$rol'
                     INNER JOIN turno t ON pctd.cod_turno = t.codigo
                     INNER JOIN horarios h ON t.cod_horario = h.codigo
                     INNER JOIN conceptos cc ON h.cod_concepto = cc.codigo
                     INNER JOIN clientes_ubicacion cu ON pctd.cod_ubicacion = cu.codigo AND cu.reconocimiento_facial = 'T'
                     WHERE pctd.fecha = '$fec_diaria'
                       AND f.cod_contracto = '$contracto'
                       AND NOT EXISTS (
                           SELECT 1 FROM asistencia ast
                            WHERE ast.cod_as_apertura = '$apertura'
                              AND ast.cod_ficha = pctd.cod_ficha
                              AND ast.cod_ubicacion = pctd.cod_ubicacion
                       );";

$query_planif = $bd->consultar($sql_planificados);
$filas_planif = array();
$fichas_dia   = array();
while ($row = $bd->obtener_fila($query_planif, 0)) {
    $filas_planif[] = $row;
    $fichas_dia[$row['cod_ficha']] = true;
}
$marcas_por_ficha  = marcasPorFicha($bd, array_keys($fichas_dia), $fec_diaria);
$overrides_horario = overridesPorUbicCargoHorario($bd, $filas_planif);

foreach ($filas_planif as $row) {
    $ficha             = $row['cod_ficha'];
    $cliente           = $row['cod_cliente'];
    $ubicacion         = $row['cod_ubicacion'];
    $cargo             = $row['cod_cargo'];
    $concepto_original = $row['concepto_abrev'];
    $cod_horario       = $row['cod_horario'];
    $ini_entrada       = $row['inicio_marc_entrada'];
    $fin_entrada       = $row['fin_marc_entrada'];

    $override_abierta = false;
    $clave_override    = $ubicacion . '|' . $cargo . '|' . $cod_horario;
    if (isset($overrides_horario[$clave_override])) {
        $ini_entrada       = $overrides_horario[$clave_override]['ini'];
        $fin_entrada       = $overrides_horario[$clave_override]['fin'];
        $override_abierta  = $overrides_horario[$clave_override]['abierta'];
    }

    $marcas_ficha = isset($marcas_por_ficha[$ficha]) ? $marcas_por_ficha[$ficha] : array();
    $hay_marcas   = !empty($marcas_ficha);

    $modo = 'AUTO';
    $obs  = '';
    $concepto_final_abrev = $concepto_original;

    if (in_array($concepto_original, $CONCEPTOS_NOVEDAD)) {
        // Novedad cargada (vacaciones, reposo, permiso...): marcar mientras está en novedad es una alerta
        if ($hay_marcas) {
            $modo = 'ALERTA';
            $obs  = "Marcó estando en novedad: $concepto_original";
        }
        // sin marcas: AUTO, se mantiene el concepto de la novedad tal cual
    } elseif ($concepto_original == 'DL') {
        if (!$hay_marcas) {
            $concepto_final_abrev = 'DL';
        } else {
            $resuelto_d = resolverMarcaje($marcas_ficha, $vent_diurno['ini'], $vent_diurno['fin']);
            $resuelto_n = resolverMarcaje($marcas_ficha, $vent_nocturno['ini'], $vent_nocturno['fin']);

            if ($resuelto_d['en_ventana']) {
                $concepto_final_abrev = $es_feriado_dia ? 'FDLT' : 'DLT';
            } elseif ($resuelto_n['en_ventana']) {
                $concepto_final_abrev = $es_feriado_dia ? 'FNLT' : 'NLT';
            } else {
                $hora_str = $marcas_ficha[0];
                $concepto_final_abrev = ($hora_str < '12:00:00')
                    ? ($es_feriado_dia ? 'FDLT' : 'DLT')
                    : ($es_feriado_dia ? 'FNLT' : 'NLT');
                $modo = 'REVISAR';
                $obs  = "Marcaje " . substr($hora_str, 0, 5) . " fuera de ventana estándar de día libre.";
            }
        }
    } else {
        // Turnos de trabajo (D/N/M/24) y cualquier otro concepto no clasificado arriba
        if (!$hay_marcas) {
            if (in_array($concepto_original, $CONCEPTOS_TURNO)) {
                // Corte temporal: si el día en curso es hoy y la ventana de entrada
                // del turno aún no cerró, no se propone FI todavía en esta ejecución.
                $ventana_cerro = true;
                if ($fec_diaria == $hoy && !empty($ini_entrada) && !empty($fin_entrada)) {
                    $cruza_medianoche = ($ini_entrada > $fin_entrada);
                    $ventana_cerro = $cruza_medianoche ? false : ($ahora > $fin_entrada);
                }
                if (!$ventana_cerro) {
                    continue; // se evalúa en una próxima ejecución del botón
                }
                $concepto_final_abrev = 'FI';
            }
            // conceptos fuera de la tabla de decisión (ni turno ni DL ni novedad): se dejan sin tocar
        } else {
            if ($concepto_original == 'D') {
                $concepto_final_abrev = $es_feriado_dia ? 'FDT' : 'D';
            } elseif ($concepto_original == 'N') {
                $concepto_final_abrev = $es_feriado_dia ? 'FNT' : 'N';
            } elseif ($concepto_original == '24') {
                $concepto_final_abrev = $es_feriado_dia ? '24FT' : '24';
            }

            $ventana_configurada = !(empty($ini_entrada) || empty($fin_entrada));
            if (!$ventana_configurada) {
                $modo = 'REVISAR';
                $obs  = "Horario $cod_horario sin ventana de marcaje configurada (horarios.inicio_marc_entrada/fin_marc_entrada) — revisar catálogo de horarios.";
            } else {
                $resuelto = resolverMarcaje($marcas_ficha, $ini_entrada, $fin_entrada);
                if (!$resuelto['en_ventana']) {
                    $modo = 'REVISAR';
                    $obs  = "Marcaje " . substr($resuelto['hora'], 0, 5) . " fuera de ventana estándar.";
                } elseif ($override_abierta) {
                    $modo = 'REVISAR';
                    $obs  = "Horario personalizado sin rango de marcaje configurado — verificar manualmente.";
                }
            }
        }
    }

    if (proponerAsistencia($bd, $apertura, $ficha, $cliente, $ubicacion, $cargo, $usuario, $concepto_final_abrev, $es_feriado_dia, $modo, $obs)) {
        $registros_procesados++;
    }
}

// ---------------------------------------------------------------------
// PASE 2: redobles (segunda entrada del mismo trabajador el mismo día,
// en la ventana del turno contrario)
// ---------------------------------------------------------------------
$sql_candidatos_redoble = "SELECT ast.cod_ficha, ast.cod_cliente, ast.cod_ubicacion, f.cod_cargo, cc.abrev AS abrev_base
                              FROM asistencia ast
                              INNER JOIN conceptos cc ON ast.cod_concepto = cc.codigo
                              INNER JOIN ficha f ON f.cod_ficha = ast.cod_ficha
                             WHERE ast.cod_as_apertura = '$apertura'
                               AND cc.abrev IN ('D', 'FDT', 'N', 'FNT');";
$query_redoble = $bd->consultar($sql_candidatos_redoble);

while ($row = $bd->obtener_fila($query_redoble, 0)) {
    $ficha      = $row['cod_ficha'];
    $cliente    = $row['cod_cliente'];
    $ubicacion  = $row['cod_ubicacion'];
    $cargo      = $row['cod_cargo'];
    $abrev_base = $row['abrev_base'];

    if ($abrev_base == 'D' || $abrev_base == 'FDT') {
        // ¿Marcó también en la ventana nocturna del mismo día?
        if (isset($marcas_por_ficha[$ficha])) {
            $marcas_ficha = $marcas_por_ficha[$ficha];
        } else {
            $marcas_extra = marcasPorFicha($bd, array($ficha), $fec_diaria);
            $marcas_ficha = isset($marcas_extra[$ficha]) ? $marcas_extra[$ficha] : array();
        }
        $ventana_n = buscarOverrideHorario($bd, $ubicacion, $cargo, $vent_nocturno['cod_horario']);
        if ($ventana_n === null) {
            $ventana_n = array('ini' => $vent_nocturno['ini'], 'fin' => $vent_nocturno['fin']);
        }
        $resuelto_n = resolverMarcaje($marcas_ficha, $ventana_n['ini'], $ventana_n['fin']);
        if ($resuelto_n['en_ventana']) {
            $concepto_redoble = $es_feriado_dia ? 'RFNT' : 'RN';
            $obs = "Redoble: turno diurno cumplido + marcaje nocturno a las " . substr($resuelto_n['hora'], 0, 5) . ".";
            if (proponerAsistencia($bd, $apertura, $ficha, $cliente, $ubicacion, $cargo, $usuario, $concepto_redoble, $es_feriado_dia, 'REVISAR', $obs)) {
                $registros_procesados++;
            }
        }
    } elseif ($abrev_base == 'N' || $abrev_base == 'FNT') {
        // ¿Marcó al día siguiente en ventana diurna, con permanencia posterior a las 08:00
        // (para no confundir con la simple salida del turno nocturno)?
        $marcas_siguiente = marcasPorFicha($bd, array($ficha), $fecha_siguiente);
        $marcas_siguiente = isset($marcas_siguiente[$ficha]) ? $marcas_siguiente[$ficha] : array();
        $ventana_d = buscarOverrideHorario($bd, $ubicacion, $cargo, $vent_diurno['cod_horario']);
        if ($ventana_d === null) {
            $ventana_d = array('ini' => $vent_diurno['ini'], 'fin' => $vent_diurno['fin']);
        }
        $resuelto_d = resolverMarcaje($marcas_siguiente, $ventana_d['ini'], $ventana_d['fin']);

        if ($resuelto_d['en_ventana']) {
            $marcas_tardias = 0;
            foreach ($marcas_siguiente as $h) {
                if ($h > '08:00:00') { $marcas_tardias++; }
            }
            if ($marcas_tardias > 0) {
                $concepto_redoble = $es_feriado_siguiente ? 'RFDT' : 'RD';
                $obs = "Redoble: turno nocturno cumplido + marcaje diurno al día siguiente a las " . substr($resuelto_d['hora'], 0, 5) . " con permanencia posterior.";
                if (proponerAsistencia($bd, $apertura, $ficha, $cliente, $ubicacion, $cargo, $usuario, $concepto_redoble, $es_feriado_siguiente, 'REVISAR', $obs)) {
                    $registros_procesados++;
                }
            }
        }
    }
}

// ---------------------------------------------------------------------
// PASE 3: marcas sin planificación (huérfanas)
// ---------------------------------------------------------------------
$sql_huerfanas = "SELECT h.cod_ficha, MIN(h.fecha_dispositivo) AS primera_marca,
                          f.cod_cliente, f.cod_ubicacion, f.cod_cargo
                     FROM historial_asistencia_facial h
                     INNER JOIN ficha f ON f.cod_ficha = h.cod_ficha
                     INNER JOIN trab_roles tr ON tr.cod_ficha = f.cod_ficha AND tr.cod_rol = '$rol'
                     INNER JOIN clientes_ubicacion cu ON f.cod_ubicacion = cu.codigo AND cu.reconocimiento_facial = 'T'
                     LEFT JOIN planif_clientes_trab_det pctd ON pctd.cod_ficha = h.cod_ficha AND pctd.fecha = '$fec_diaria'
                    WHERE DATE(h.fecha_dispositivo) = '$fec_diaria'
                      AND f.cod_contracto = '$contracto'
                      AND pctd.codigo IS NULL
                      AND NOT EXISTS (
                          SELECT 1 FROM asistencia ast
                           WHERE ast.cod_as_apertura = '$apertura' AND ast.cod_ficha = h.cod_ficha
                      )
                 GROUP BY h.cod_ficha, f.cod_cliente, f.cod_ubicacion, f.cod_cargo;";
$query_huerfanas = $bd->consultar($sql_huerfanas);

while ($row = $bd->obtener_fila($query_huerfanas, 0)) {
    $ficha      = $row['cod_ficha'];
    $cliente    = $row['cod_cliente'];
    $ubicacion  = $row['cod_ubicacion'];
    $cargo      = $row['cod_cargo'];
    $hora_str   = substr($row['primera_marca'], 11, 8);

    $concepto_final_abrev = ($hora_str < '12:00:00')
        ? ($es_feriado_dia ? 'FDLT' : 'DLT')
        : ($es_feriado_dia ? 'FNLT' : 'NLT');
    $obs = "Sin planificación: posible redoble/traslado no planificado. Marcó a las " . substr($hora_str, 0, 5) . ".";

    if (proponerAsistencia($bd, $apertura, $ficha, $cliente, $ubicacion, $cargo, $usuario, $concepto_final_abrev, $es_feriado_dia, 'REVISAR', $obs)) {
        $registros_procesados++;
    }
}

// ---------------------------------------------------------------------
// Mensaje de retorno
// ---------------------------------------------------------------------
if ($registros_procesados > 0) {
    echo '<div class="mensaje_exito" style="color:#1a7f37; font-weight:bold; padding:10px; background:#dcfce7; border-radius:5px;">
            Se procesaron y cargaron (' . $registros_procesados . ') propuestas faciales con estados asignados.
          </div>';
} else {
    echo '<div class="mensaje_alerta" style="color:#b45309; font-weight:bold; padding:10px; background:#fef3c7; border-radius:5px;">
            No hay nuevos marcajes faciales para cargar.
          </div>';
}
?>
