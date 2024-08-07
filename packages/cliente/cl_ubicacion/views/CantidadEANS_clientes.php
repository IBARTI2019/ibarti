<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();
$codigo    = $_POST['codigo'];


$sql = "SELECT * from vectoreans where codigo='$codigo'";
$query = $bd->consultar($sql);
while ($datos=$bd->obtener_fila($query,0)){
    array_push($result, $datos);
}
print_r(json_encode($result));
return json_encode($result);