<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();
foreach($_POST as $nombre_campo => $valor){
  $variables = "\$".$nombre_campo."='".$valor."';";
  eval($variables);
}

try {
  $sql = "INSERT INTO cargos_excl_confirm (cod_cargo, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod) 
  VALUES ('$cargo', '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP)";
  $query = $bd->consultar($sql);
  $result['sql'] = $sql;
}catch (Exception $e) {
  $error =  $e->getMessage();
  $result['error'] = true;
  $result['mensaje'] = $error;
  $bd->log_error("Aplicacion", "cargos_excl_.php",  "$usuario", "$error", "$sql");
}

print_r(json_encode($result));
return json_encode($result);
?>
