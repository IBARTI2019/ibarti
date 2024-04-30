<?php
define("SPECIALCONSTANT", true);

include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();
$productos = array();

foreach($_POST as $nombre_campo => $valor){
	$variables = "\$".$nombre_campo."='".$valor."';";
	eval($variables);
}


if(isset($_POST['proced'])){
	try {
		// $sql = "SELECT descripcion FROM productos WHERE cod_sub_linea = '$codigo'";
		// $query = $bd->consultar($sql);
		// while ($datos= $bd->obtener_fila($query)) {
		// 	$productos[] = $datos;
		// }
		// if(count($productos) == 0){
		$sql    = "$SELECT $proced('$metodo', '$codigo','$linea', '$descripcion', '$usuario', '$activo','$color','$talla','$peso','$piecubico')";
		// }else{
		// 	$result['error'] = true;
		// 	$result['mensaje'] = "No es posible actualizar esta Sub Linea, debido a que ya existen productos en la misma";
		// }
		$query   = $bd->consultar($sql);
		$result['sql'] = $sql;

	}catch (Exception $e) {
		$error =  $e->getMessage();
		$result['error'] = true;
		$result['mensaje'] = $error;

		$bd->log_error("Aplicacion", "sc_prod_sub_linea.php",  "$usuario", "$error", "$sql");
	}
}
$result['sql'] = $sql;
print_r(json_encode($result));
return json_encode($result);
?>
