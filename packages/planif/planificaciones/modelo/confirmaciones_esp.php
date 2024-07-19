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
    if($codigo == ""){
      $sql = "INSERT INTO turno_cl_ubicacion (cod_cl_ubicacion, cod_turno, hora_entrada, usuario) VALUES ('$ubicacion','$turno', '$hora_entrada', '$usuario')";
      $query = $bd->consultar($sql);
    }else{
      $sql = "UPDATE turno_cl_ubicacion SET hora_entrada = '$hora_entrada', usuario = '$usuario'
              WHERE cod_cl_ubicacion = '$ubicacion', cod_turno = '$turno';";
     $query = $bd->consultar($sql);
    }
  $result['sql'] = $sql;
  }catch (Exception $e) {
     $error =  $e->getMessage();
     $result['error'] = true;
     $result['mensaje'] = $error;
     $bd->log_error("Aplicacion", "sc_planificacion.php",  "$usuario", "$error", "$sql");
 }

	print_r(json_encode($result));
	return json_encode($result);

?>
