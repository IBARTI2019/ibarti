<?php
define("SPECIALCONSTANT", true);

include_once "../../../funciones/funciones.php";
require "../../../autentificacion/aut_config.inc.php";
require_once "../../../" . class_bdI;
$bd = new DataBase();
$result = array();
if (isset($_POST['cedula'])) {
    try {
        $cedula = $_POST['cedula'];
        $sql = "SELECT v_preingreso.ap_nombre 
            FROM v_preingreso WHERE cedula = $cedula";

        $sql_evaluaciones = "SELECT
            nov_check_list_trab.fec_us_ing fecha,
            nov_check_list_trab.hora hora,
            nov_check_list_trab.cedula documento,
            nov_tipo.descripcion tipo,
            CONCAT(
                preingreso.nombres,
                ' ',
                preingreso.apellidos
            ) nombres,
            CONCAT(
                SUM(
                    nov_check_list_trab_det.valor
                ),
                ' ',
                'puntos'
            ) porcentaje,
            control.porc_min_aprob_encuesta_preing
        FROM
            nov_check_list_trab,
            nov_check_list_trab_det,
            nov_tipo,
            nov_clasif,
            preingreso,
            novedades,
            control
        WHERE
            nov_check_list_trab.codigo = nov_check_list_trab_det.cod_check_list
        AND nov_clasif.codigo = nov_check_list_trab.cod_nov_clasif
        AND nov_tipo.codigo = nov_check_list_trab.cod_nov_tipo
        AND preingreso.cedula = nov_check_list_trab.cedula
        AND novedades.codigo = nov_check_list_trab_det.cod_novedades
        AND novedades.status = 'T'
        AND nov_check_list_trab.cedula = '$cedula'
        GROUP BY nov_check_list_trab.codigo
        HAVING SUM(nov_check_list_trab_det.valor) >= control.porc_min_aprob_encuesta_preing";

        $query = $bd->consultar($sql_evaluaciones);
        $result['tests'] = array();
        while ($datos = $bd->obtener_fila($query)) {
            $result['tests'][] = $datos;
        }

        $query = $bd->consultar($sql);
        $datos = $bd->obtener_fila($query);
        $url_foto = "../../../imagenes/fotos/" . $cedula . ".jpg";
        if (!file_exists($url_foto)) {
            $result['error'] = true;
            $result['mensaje'] = "La foto de " . $datos['ap_nombre'] . ", no esta cargada.";
        } elseif ($datos['existe'] == 1) {
            $result['error'] = false;
        }
        if (count($result['tests'])) {
        }
    } catch (Exception $e) {
        $error =  $e->getMessage();
        $result['error'] = true;
        $result['mensaje'] = $error;
        $bd->log_error("Aplicacion", "sc_login.php",  "$usuario", "$error", "$sql");
    }
} else {
    $result['error'] = true;
    $result['mensaje'] = "Error en sistema de autentificacion";
    $result['sql'] = $sql;
}

print_r(json_encode($result));
return json_encode($result);
