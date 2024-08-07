<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();
$sub_linea     = $_POST['sub_linea'];
$ficha     = $_POST['ficha'];

$sql = "SELECT clientes_ub_uniforme.cod_cl_ubicacion        
FROM clientes_ub_uniforme, clientes_ubicacion
WHERE clientes_ubicacion.codigo  = '$ficha' AND clientes_ub_uniforme.cod_cl_ubicacion = clientes_ubicacion.codigo
AND clientes_ub_uniforme.cod_sub_linea = '$sub_linea'";
$query = $bd->consultar($sql);
while ($datos=$bd->obtener_fila($query,0)){
    array_push($result, $datos);
}
print_r(json_encode($result));
return json_encode($result);
?>