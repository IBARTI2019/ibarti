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
	if(isset($_POST['proced'])){
		try {
      $sql    = "$SELECT $proced('$metodo', '$codigo', '$cliente', '$estado',
   	                             '$ciudad', '$region', '$zona', '$calendario',
                                 '$nombre', '$contacto', '$cargo', '$telefono',
                                 '$email', '$direccion', '$observ', '$latitud', '$longitud',
   								               '$campo01', '$campo02', '$campo03', '$campo04', '$usuario',  '$status')";
   	 $query = $bd->consultar($sql);
     $result['sql'] = $sql;

 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;
       $bd->log_error("Aplicacion", "sc_ubicacion.php",  "$usuario", "$error", "$sql");
   }
	}
	print_r(json_encode($result));
	return json_encode($result);

?>
