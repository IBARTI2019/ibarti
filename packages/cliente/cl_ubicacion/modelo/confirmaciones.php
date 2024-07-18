<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  $variables = "\$".$nombre_campo."='".$valor."';";
  eval($variables);
}
$codigo  = htmlentities($codigo);

try {
  $sql = "UPDATE clientes_ubicacion 
  SET min_confirm = $min_confirm, max_confirm = $max_confirm, 
  min_in_transport = $min_in_transport, max_in_transport = $max_in_transport 
  WHERE codigo = '$codigo';";
  $query = $bd->consultar($sql);
  $result['sql'] = $sql;
}catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_ubic_confirmaciones.php",  "$usuario", "$error", "$sql");
}

	print_r(json_encode($result));
	return json_encode($result);

?>
