<?php
define("SPECIALCONSTANT", true);
require  "../autentificacion/aut_config.inc.php";
require_once "../".Funcion;
require_once  "../".class_bdI;
$bd = new Database;

$ficha = $_POST['cod_ficha'];
$result =array();
try {
	$sql = "SELECT
	clientes.codigo cod_cliente,
	clientes.abrev,
	clientes.nombre cliente,
	clientes_ubicacion.codigo cod_ubicaicon,
	clientes_ubicacion.descripcion ubicacion 
	FROM
	ficha,
	clientes,
	clientes_ubicacion 
	WHERE
	ficha.cod_ficha = '$ficha'
	AND ficha.cod_ubicacion = clientes_ubicacion.codigo
	ANd clientes_ubicacion.cod_cliente = clientes.codigo;";
	$query         = $bd->consultar($sql);
	$result = $bd->obtener_fila($query);
}catch (Exception $e) {
	$error =  $e->getMessage();
	$result['error'] = true;
	$result['mensaje'] = $error;
	$bd->log_error("Aplicacion", "dotacion_ficha.php",  "$us", "$error", "$sql");
}	
echo json_encode($result);
?>