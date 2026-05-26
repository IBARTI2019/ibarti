<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

$codigo = $_POST['codigo'];

$sql = "SELECT ean FROM productos WHERE item = '$codigo'";
$query = $bd->consultar($sql);
$datos = $bd->obtener_fila($query, 0);

echo json_encode(array($datos['ean']));
?>
