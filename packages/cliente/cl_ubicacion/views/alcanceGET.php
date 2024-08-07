<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();

$typing     = $_GET['q'];

$sql = "SELECT  prod_sub_lineas.codigo, prod_sub_lineas.descripcion, 
CONCAT(prod_sub_lineas.codigo,' (',prod_sub_lineas.descripcion,') ') descripcionFull, prod_sub_lineas.codigo             
FROM prod_sub_lineas 
WHERE (LOCATE('$typing',prod_sub_lineas.descripcion) OR LOCATE('$typing', prod_sub_lineas.codigo))         
ORDER BY 2 DESC";
$query = $bd->consultar($sql);
while ($datos=$bd->obtener_fila($query,0)){
    array_push($result, $datos);
}
print_r(json_encode($result));
return json_encode($result);
?>