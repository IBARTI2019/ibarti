<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();


$producto   = $_POST['producto'];
$almacen    = $_POST['almacen'];
$cod_ficha    = $_POST['cod_ficha'];
 $sql = "SELECT IF(FORMAT(GREATEST(0, stock.stock_actual - stock.stock_reservado),0) < FORMAT(ficha_dotacion.cantidad,0) ,FORMAT(GREATEST(0, stock.stock_actual - stock.stock_reservado),0) ,FORMAT(ficha_dotacion.cantidad,0) ) stock_actual
    FROM stock,ficha_dotacion,productos 
WHERE stock.cod_producto = '$producto' 
AND stock.cod_almacen = '$almacen'
AND productos.item = stock.cod_producto
AND productos.cod_sub_linea = ficha_dotacion.cod_sub_linea
AND ficha_dotacion.cod_ficha = '$cod_ficha'";
 $query = $bd->consultar($sql);
 $result = $bd->obtener_fila($query,0);
echo json_encode($result);
mysql_free_result($query);
?>
