<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$producto   = $_POST['producto'];
$almacen    = $_POST['almacen'];

// Solo validamos limite fisico de almacen (stock_actual - reservado)
$sql = "SELECT IFNULL(FORMAT(GREATEST(0, stock_actual - stock_reservado),0), 0) as stock_actual
        FROM stock
        WHERE cod_producto = '$producto' 
        AND cod_almacen = '$almacen'";
$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query,0);

if (!$result) {
    $result = array("stock_actual" => 0);
}

echo json_encode($result);
mysql_free_result($query);
?>
