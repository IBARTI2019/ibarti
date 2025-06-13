<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();

$result = array();

foreach ($_POST as $nombre_campo => $valor) {
    $variables = "\$" . $nombre_campo . "='" . $valor . "';";
    eval ($variables);
}

if (isset($_POST['metodo']) && $metodo == "insertar_asignaciones") {
    try {
        // Inicializar estructura de resultados
        $resultados = array(
            'base' => array(
                'insertadas' => 0,
                'actualizadas' => 0,
                'errores' => array(),
                'data' => json_decode($base_data, true)
            ),
            'modificaciones' => array(
                'insertadas' => 0,
                'actualizadas' => 0,
                'eliminadas' => 0,
                'errores' => array(),
                'data' => json_decode($modificaciones_data, true)
            ),
            'error' => false
        );

        // Iniciar transacción
        $bd->start();

        // 1. Procesar asignaciones base
        foreach ($resultados['base']['data'] as $idx => $asig) {
            try {
                // Verificar si ya existe la asignación
                $sql_check = "SELECT codigo 
                             FROM planif_clientes_trab 
                             WHERE cod_planif_cl = '" . $bd->escape($asig['cod_planif_cl']) . "'
                             AND cod_ficha = '" . $bd->escape($asig['cod_ficha']) . "'
                             AND fecha_inicio = '" . $bd->escape($asig['fecha_inicio']) . "'
                             AND fecha_fin = '" . $bd->escape($asig['fecha_fin']) . "'";

                $query_check = $bd->consultar($sql_check);
                $existe = $bd->obtener_fila($query_check) !== false;

                // Insertar o actualizar
                $sql_upsert = "INSERT INTO planif_clientes_trab (
                                cod_planif_cl, cod_cliente, cod_ubicacion, cod_puesto_trabajo,
                                cod_ficha, cod_rotacion, posicion_inicio, posicion_fin,
                                fecha_inicio, fecha_fin, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod
                              ) VALUES (
                                '" . $bd->escape($asig['cod_planif_cl']) . "', 
                                '" . $bd->escape($cod_cliente) . "', 
                                '" . $bd->escape($cod_ubic) . "', 
                                '" . $bd->escape($asig['cod_puesto_trabajo']) . "',
                                '" . $bd->escape($asig['cod_ficha']) . "', 
                                '" . $bd->escape($asig['cod_rotacion']) . "', 
                                '" . $bd->escape($asig['posicion_inicio']) . "', 
                                '" . $bd->escape($asig['posicion_fin']) . "',
                                '" . $bd->escape($asig['fecha_inicio']) . "', 
                                '" . $bd->escape($asig['fecha_fin']) . "', 
                                '" . $bd->escape($cod_usuario) . "', 
                                NOW(),
                                '" . $bd->escape($cod_usuario) . "', 
                                NOW()
                              )
                              ON DUPLICATE KEY UPDATE
                                cod_puesto_trabajo = VALUES(cod_puesto_trabajo),
                                cod_rotacion = VALUES(cod_rotacion),
                                posicion_inicio = VALUES(posicion_inicio),
                                posicion_fin = VALUES(posicion_fin),
                                cod_us_mod = VALUES(cod_us_mod),
                                fec_us_mod = NOW()";

                $bd->consultar($sql_upsert);

                if ($existe) {
                    $resultados['base']['actualizadas']++;
                } else {
                    $resultados['base']['insertadas']++;
                }

            } catch (Exception $e) {
                $error = $e->getMessage();
                $resultados['base']['errores'][] = array(
                    'indice' => $idx,
                    'error' => $error,
                    'asignacion' => $asig
                );
                $bd->rollback();
                $bd->start(); // Reiniciar transacción después del rollback
            }
        }

        // 2. Procesar modificaciones en detalles
        foreach ($resultados['modificaciones']['data'] as $idx => $mod) {
            try {
                // Validar existencia previa en base
                $ficha_valida = false;
                foreach ($resultados['base']['data'] as $asig) {
                    if ($asig['cod_ficha'] == $mod['ficha']) {
                        $ficha_valida = true;
                        break;
                    }
                }

                if (!$ficha_valida) {
                    throw new Exception("Ficha " . $mod['ficha'] . " no tiene asignación base");
                }

                // Obtener ID de la asignación base
                $sql_base = "SELECT pct.codigo
                            FROM planif_clientes_trab pct
                            WHERE pct.cod_planif_cl = '" . $bd->escape($resultados['base']['data'][0]['cod_planif_cl']) . "'
                            AND pct.cod_ficha = '" . $bd->escape($mod['ficha']) . "'
                            AND pct.fecha_inicio <= '" . $bd->escape($mod['fecha']) . "'
                            AND pct.fecha_fin >= '" . $bd->escape($mod['fecha']) . "'";

                $query_base = $bd->consultar($sql_base);
                $base = $bd->obtener_fila($query_base);

                if (!$base) {
                    continue;
                }

                // Verificar si ya existe un detalle
                $sql_check_detalle = "SELECT codigo 
                                    FROM planif_clientes_trab_det
                                    WHERE cod_planif_cl = '" . $bd->escape($resultados['base']['data'][0]['cod_planif_cl']) . "'
                                    AND cod_planif_cl_trab = '" . $bd->escape($base['codigo']) . "'
                                    AND cod_ficha = '" . $bd->escape($mod['ficha']) . "'
                                    AND cod_turno = '" . $bd->escape($mod['cod_turno']) . "'
                                    AND fecha = '" . $bd->escape($mod['fecha']) . "'";

                $query_check_detalle = $bd->consultar($sql_check_detalle);
                $detalle_existente = $bd->obtener_fila($query_check_detalle) !== false;

                // Ejecutar acción según tipo
                if ($mod['accion'] == 'insertar' && !$detalle_existente) {
                    $cod_turno = isset($mod['cod_turno_nuevo']) ? $mod['cod_turno_nuevo'] : $mod['cod_turno'];

                    $sql_insert = "INSERT INTO planif_clientes_trab_det (
                                    cod_planif_cl, cod_planif_cl_trab, cod_turno,
                                    cod_cliente, cod_ubicacion, cod_puesto_trabajo, cod_ficha,
                                    fecha, cod_us_ing, fec_us_ing
                                  ) VALUES (
                                    '" . $bd->escape($resultados['base']['data'][0]['cod_planif_cl']) . "',
                                    '" . $bd->escape($base['codigo']) . "',
                                    '" . $bd->escape($cod_turno) . "',
                                    '" . $bd->escape($cod_cliente) . "',
                                    '" . $bd->escape($cod_ubic) . "',
                                    '" . $bd->escape($mod['cod_puesto_trabajo']) . "',
                                    '" . $bd->escape($mod['ficha']) . "',
                                    '" . $bd->escape($mod['fecha']) . "',
                                    '" . $bd->escape($cod_usuario) . "',
                                    NOW()
                                  )";

                    $bd->consultar($sql_insert);
                    $resultados['modificaciones']['insertadas']++;

                } elseif ($mod['accion'] == 'actualizar' || ($mod['accion'] == 'insertar' && $detalle_existente)) {
                    $sql_update = "INSERT INTO planif_clientes_trab_det (
                                    cod_planif_cl, cod_planif_cl_trab, cod_turno,
                                    cod_cliente, cod_ubicacion, cod_puesto_trabajo, cod_ficha,
                                    fecha, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod
                                  ) VALUES (
                                    '" . $bd->escape($resultados['base']['data'][0]['cod_planif_cl']) . "',
                                    '" . $bd->escape($base['codigo']) . "',
                                    '" . $bd->escape($mod['cod_turno_nuevo']) . "',
                                    '" . $bd->escape($cod_cliente) . "',
                                    '" . $bd->escape($cod_ubic) . "',
                                    '" . $bd->escape($mod['cod_puesto_trabajo']) . "',
                                    '" . $bd->escape($mod['ficha']) . "',
                                    '" . $bd->escape($mod['fecha']) . "',
                                    '" . $bd->escape($cod_usuario) . "',
                                    NOW(),
                                    '" . $bd->escape($cod_usuario) . "',
                                    NOW()
                                  )
                                  ON DUPLICATE KEY UPDATE
                                    cod_turno = VALUES(cod_turno),
                                    cod_puesto_trabajo = VALUES(cod_puesto_trabajo),
                                    cod_us_mod = VALUES(cod_us_mod),
                                    fec_us_mod = NOW()";

                    $bd->consultar($sql_update);
                    $resultados['modificaciones']['actualizadas']++;
                }

            } catch (Exception $e) {
                $error = $e->getMessage();
                $resultados['modificaciones']['errores'][] = array(
                    'indice' => $idx,
                    'error' => $error,
                    'modificacion' => $mod
                );
                $bd->rollback();
                $bd->start(); // Reiniciar transacción después del rollback
            }
        }

        // Confirmar transacción si todo fue bien
        $bd->commit();
        $result = $resultados;

    } catch (Exception $e) {
        $error = $e->getMessage();
        $result['error'] = true;
        $result['mensaje'] = $error;
        $bd->log_error("Aplicacion", "sc_planificacion_trab.php", "$usuario", "$error", "$sql");
    }
}

print_r(json_encode($result));
return json_encode($result);
?>