<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

//include_once('../funciones/mensaje_error.php');
$pais          = $_POST['pais'];
$administrador = $_POST['administrador'];
$cl_principal  = $_POST['cl_principal'];
$rif           = $_POST['rif'];
$p_nuevo       = $_POST['p_nuevo'];
$p_aprobado    = $_POST['p_aprobado'];
$p_rechazado   = $_POST['p_rechazado'];

$ficha_preingreso = $_POST['ficha_preingreso'];
$ficha_activo  = $_POST['ficha_activo'];
$rol           = $_POST['rol'];
$ing_status_sistema = $_POST['ing_status_sistema'];
$expedientes_dias  = $_POST['expedientes_dias'];
$vale_concepto = $_POST['vale_concepto'];
$vale_monto    = $_POST['vale_monto'];
$c_cestaticket = $_POST['c_cestaticket'];
$c_hora_extras_d = $_POST['c_hora_extras_d'];
$c_hora_extras_n = $_POST['c_hora_extras_n'];
$c_retirado    = $_POST['c_retirado'];
$c_replica     = $_POST['c_replica'];
$d_proyeccion  = $_POST['d_proyeccion'];
$lim_plantilla = $_POST['lim_plantilla'];
$nota_unif     = $_POST['nota_unif'];
$nota_doc       = $_POST['nota_doc'];
$s_cargo        = $_POST['s_cargo'];
$cl_campo_04    = $_POST['cl_campo_04'];
$cl_campo_04_d  = $_POST['cl_campo_04_d'];
$turno_dl       = $_POST['turno_dl'];
$ar_linea       = $_POST['ar_linea'];
$uniforme_linea = $_POST['uniforme_linea'];
$nov_clasif_sms = $_POST['nov_clasif_sms'];
$url_doc        = $_POST['url_doc'];
$rop_meses      = $_POST['rop_meses'];

$mensajeria     = $_POST['mensajeria'];
$clasif_mensajeria     = $_POST['clasif_mensajeria'];

$host_smtp      = $_POST['host_smtp'];
$puerto_smtp      = $_POST['puerto_smtp'];
$protocolo_smtp      = $_POST['protocolo_smtp'];
$cuenta_smtp      = $_POST['cuenta_smtp'];
$password_smtp      = $_POST['password_smtp'];

$notificar      = $_POST['notificaciones'];
$notificar_resp = $_POST['notificaciones_resp'];
$colores_notif = $_POST['colores'];
$dias_nov_notif = $_POST['dias_nov'];
$min_nov_notif = $_POST['min_nov'];
$porc_min_aprob_encuesta_preing = $_POST['porc_min_aprob_encuesta_preing'];

$min_confirm       = $_POST['min_confirm'];
$max_confirm       = $_POST['max_confirm'];
$min_in_transport       = $_POST['min_in_transport'];
$max_in_transport       = $_POST['max_in_transport'];

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

if (isset($_POST['proced'])) {

	$sql    = "$SELECT $proced('$metodo', '$pais', '$cl_principal', '$rif', 
								'$p_nuevo', '$p_aprobado', '$p_rechazado', '$ing_status_sistema',
								'$ficha_activo', '$rol', '$expedientes_dias', '$vale_concepto', 
								'$vale_monto', '$c_cestaticket', '$c_hora_extras_d', '$c_hora_extras_n',
								'$c_replica', '$c_retirado', '$d_proyeccion', '$lim_plantilla', 
								'$nota_unif', '$nota_doc', '$s_cargo', '$cl_campo_04', 
								'$cl_campo_04_d', '$turno_dl', '$ar_linea', '$uniforme_linea', '$ficha_preingreso',
								'$nov_clasif_sms', '$url_doc', '$rop_meses','$host_smtp',
								'$puerto_smtp', '$protocolo_smtp', '$cuenta_smtp','$password_smtp',$dias_nov_notif,
								$min_nov_notif, $porc_min_aprob_encuesta_preing, 
								$min_confirm, $max_confirm, $min_in_transport, $max_in_transport)";

	$query = $bd->consultar($sql);

	$sql = "UPDATE nov_clasif SET control_mensajeria  = 'F'";
	$query = $bd->consultar($sql);

	foreach ($clasif_mensajeria as $valorX) {
		$sql = "UPDATE nov_clasif SET    		
                           control_mensajeria     = 'T'
					 WHERE nov_clasif.codigo = '$valorX'";
		$query = $bd->consultar($sql);
	}

	$sql = "UPDATE nov_status SET control_mensajeria  = 'F'";
	$query = $bd->consultar($sql);

	foreach ($mensajeria as $valorX) {
		$sql = "UPDATE nov_status SET    		
                           control_mensajeria     = 'T'
					 WHERE nov_status.codigo = '$valorX'";
		$query = $bd->consultar($sql);
	}


	$sql = "UPDATE nov_status SET control_notificaciones  = 'F' , control_notificaciones_res  = 'F' ";
	$query = $bd->consultar($sql);


	foreach ($notificar as $h => $valorY) {
		$sql = "UPDATE nov_status SET    		
                           control_notificaciones = 'T',
						   color_notificaciones = '" . $colores_notif[$h] . "'
					 WHERE nov_status.codigo = '$valorY'";

		$query = $bd->consultar($sql);
	}

	$sql = "SELECT codigo from nov_status";
	$consulta = $bd->consultar($sql);

	if(isset($notificar_resp)){
		foreach ($notificar_resp as $h => $valorz) {
			$sql = "UPDATE nov_status SET nov_status.control_notificaciones_res = 'T' WHERE nov_status.codigo = '$valorz'";


			$query = $bd->consultar($sql);
		}
	}
	
	$sql = "SELECT codigo from nov_status";
	$consulta = $bd->consultar($sql);



	while ($cod_novedad = $bd->fetch_assoc($consulta)) {

		$sql = "UPDATE nov_status set control_notif_orden = " . $_POST[$cod_novedad['codigo']] . " WHERE codigo='" . $cod_novedad['codigo'] . "'";

		$query = $bd->consultar($sql);
	}
}
// require_once('../funciones/sc_direccionar.php');
