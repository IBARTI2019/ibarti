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

    $sql = "CALL p_planif_delete('$cliente','$ubicacion','$ficha','$apertura')";
    $query = $bd->consultar($sql);
    $result['sql'] = $sql;

  }catch (Exception $e) {
   $error =  $e->getMessage();
   $result['error'] = true;
   $result['mensaje'] = $error;
   $bd->log_error("Aplicacion", "sc_delete_planif_trab_det.php",  "$usuario", "$error", "$sql");
 }

print_r(json_encode($result));
return json_encode($result);

?>
