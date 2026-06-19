<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

$codigo = $_POST['codigo'];
$almacen = $_POST['almacen'];
$tipo = $_POST['tipo'];
$ubicacion = $_POST['ubicacion'];
$ficha = isset($_POST['ficha']) ? $_POST['ficha'] : '';

$eans = array();

if ($tipo == 'ASIGNACION') {
    // Buscar EANs en el almacén con inStock = 'T'
    // Y que no estén actualmente asignados a NADIE.
    $sql = "SELECT p.cod_ean 
            FROM prod_ean p
            WHERE p.cod_producto = '$codigo'
              AND p.cod_almacen = '$almacen'
              AND p.inStock = 'T'
              AND p.cod_ean NOT IN (SELECT cod_ean FROM v_stock_asignado_eans)
            ORDER BY 1 DESC";
} else {
    // DEVOLUCION
    // Buscar EANs que están actualmente asignados a esa Ubicación (y Ficha si se envió)
    $ficha_cond = "";
    if ($ficha != "") {
        $ficha_cond = " AND pa.cod_ficha = '$ficha' ";
    } else {
        $ficha_cond = " AND (pa.cod_ficha = '' OR pa.cod_ficha IS NULL) ";
    }
    
    $sql = "SELECT sub.cod_ean FROM (
                  SELECT pae.cod_ean, 
                         SUM(CASE WHEN pa.tipo = 'ASIGNACION' THEN 1 ELSE -1 END) as balance
                  FROM prod_asignacion_eans pae
                  JOIN prod_asignacion pa ON pae.cod_asignacion = pa.codigo
                  WHERE pae.cod_producto = '$codigo'
                    AND pa.cod_ubicacion = '$ubicacion'
                    $ficha_cond
                  GROUP BY pae.cod_ean
              ) as sub WHERE sub.balance > 0
            ORDER BY 1 DESC";
}

$query = $bd->consultar($sql);
if (!$query) {
    echo json_encode(array("error" => mysql_error()));
    exit;
}
while ($datos = $bd->obtener_fila($query, 0)) {
    $eans[] = array('cod_ean' => $datos['cod_ean']);
}

if ($tipo == 'DEVOLUCION' && count($eans) == 0) {
    $dev_ficha_cond = "";
    if ($ficha != "") {
        $dev_ficha_cond = " AND pa_dev.cod_ficha = '$ficha' ";
    } else {
        $dev_ficha_cond = " AND (pa_dev.cod_ficha = '' OR pa_dev.cod_ficha IS NULL) ";
    }

    $sql_no_ean = "SELECT 1
        FROM prod_asignacion pa
        INNER JOIN prod_asignacion_det pad ON pad.cod_asignacion = pa.codigo
        LEFT JOIN prod_asignacion_eans pae ON pae.cod_asignacion = pa.codigo AND pae.cod_producto = pad.cod_producto
        WHERE pa.tipo = 'ASIGNACION'
          AND pa.cod_ubicacion = '$ubicacion'
          $ficha_cond
          AND pad.cod_producto = '$codigo'
          AND pad.cod_almacen = '$almacen'
          AND pae.cod_ean IS NULL
          AND (
            pad.cantidad - IFNULL((
              SELECT SUM(pad_dev.cantidad)
              FROM prod_asignacion pa_dev
              INNER JOIN prod_asignacion_det pad_dev ON pad_dev.cod_asignacion = pa_dev.codigo
              WHERE pa_dev.tipo = 'DEVOLUCION'
                AND pa_dev.cod_ubicacion = pa.cod_ubicacion
                $dev_ficha_cond
                AND pad_dev.cod_producto = pad.cod_producto
                AND pad_dev.cod_almacen = pad.cod_almacen
            ), 0)
          ) > 0
        LIMIT 1";

    $query_no_ean = $bd->consultar($sql_no_ean);
    if ($query_no_ean && $bd->obtener_fila($query_no_ean, 0)) {
        echo json_encode(array("allow_without_ean" => true));
        mysql_free_result($query);
        exit;
    }
}

echo json_encode($eans);
mysql_free_result($query);
?>
