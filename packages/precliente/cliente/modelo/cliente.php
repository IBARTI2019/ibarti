<?php
define("SPECIALCONSTANT", true);
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);
$bd = new DataBase();
$result = array();

$data = array();

foreach ($_POST as $nombre_campo => $valor) {
	$variables = "\$" . $nombre_campo . "='" . $valor . "';";
	eval($variables);
}

$codigo  = htmlentities($codigo);

if (isset($_POST['proced'])) {

	try {
		if (!$limite_cred) $limite_cred = 0;
		if (!$plazo_pago) $plazo_pago = 0;
		if (!$desc_global) $desc_global = 0;
		if (!$desc_p_pago) $desc_p_pago = 0;

		$sql    = "$SELECT $proced('$metodo', '$codigo', '$cl_tipo', '$vendedor',
									'$region', '$abrev', '$rif', '$nit',
									'$nombre', '$telefono', '$fax', '$direccion',
									'$dir_entrega', '$email', '$website', '$contacto', '$observ',
									'$juridico', '$contrib', '$lunes', '$martes',
									'$miercoles', '$jueves', '$viernes', '$sabado',
									'$domingo', '$limite_cred', '$plazo_pago', '$desc_global',
									'$desc_p_pago', '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', 
									'$responsable', '$empresa_actual', '$cantidad_hombres', '$problema_identificado', '$activo')";
		$query = $bd->consultar($sql);

		$result['sql'] = $sql;
	} catch (Exception $e) {
		$error =  $e->getMessage();
		$result['error'] = true;
		$result['mensaje'] = $error;
		$bd->log_error("Aplicacion", "sc_precliente.php",  "$usuario", "$error", "$sql");
	}
}
print_r(json_encode($result));
return json_encode($result);
