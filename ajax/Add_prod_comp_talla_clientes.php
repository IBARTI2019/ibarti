<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$result = array();
$ficha   = $_POST['ficha'];
$producto    = $_POST['producto'];
$sql = "SELECT clientes_ub_alcance.cod_producto
    FROM clientes_ub_alcance
WHERE clientes_ub_alcance.cod_producto= '$producto' 
AND clientes_ub_alcance.cod_cl_ubicacion = '$ficha'";
 $query = $bd->consultar($sql);
$result = $bd->obtener_fila($query,0);
echo json_encode($result);
mysql_free_result($query);
?>
