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

$descripcion      = htmlentities($descripcion);
if($metodo == 'agregar'){
  $codigo = 0;
}
	if(isset($_POST['proced'])){

		try {
 	 $sql    = "$SELECT $proced('$metodo', $codigo, '$cliente', '$descripcion',
	                            '$fecha_inicio', '$usuario', '$status')";
	 $query = $bd->consultar($sql);


 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;

       $bd->log_error("Aplicacion", "sc_contratacion.php",  "$usuario", "$error", "$sql");
   }

	}

   $result['sql'] = $sql;
	print_r(json_encode($result));
	return json_encode($result);
?>
