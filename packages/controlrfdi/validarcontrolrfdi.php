<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();
$sub_linea     = $_POST['sub_linea'];


$sql = "SELECT control_rfid.codigo        
FROM control_rfid
WHERE control_rfid.codigo = '$sub_linea'";
$query = $bd->consultar($sql);
while ($datos=$bd->obtener_fila($query,0)){
    array_push($result, $datos);
}
print_r(json_encode($result));
return json_encode($result);
?>