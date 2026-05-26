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

echo json_encode($eans);
?>
