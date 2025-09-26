<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Reporte de Ficha de Trabajador</title>
</head>
<?php

require("../autentificacion/aut_config.inc.php");
require("../libs/PHPMailer/enviar.php");
require_once("../".class_bd);
$bd = new DataBase();

$reporte = $_POST["reporte"];
$ficha   = $_POST["trabajador"];

if(isset($_POST['trabajador'])){

	$sql_smtp = "SELECT control.host_smtp,  control.puerto_smtp, control.protocolo_smtp,
	control.cuenta_smtp,control.password_smtp, men_reportes_html.descripcion reporte
	FROM control,men_reportes_html WHERE  men_reportes_html.codigo = '$reporte' ";

	$query = $bd->consultar($sql_smtp);
	$result =$bd->obtener_fila($query,0);
	$host =$result['host_smtp'];
	$puerto =$result['puerto_smtp'];
	$protocolo =$result['protocolo_smtp'];
	$cuenta=$result['cuenta_smtp'];
	$password =$result['password_smtp'];
	$rep = $result['reporte'];

	$sql = "SELECT * FROM v_rp_ficha WHERE v_rp_ficha.cod_ficha = '$ficha' ";
	$query = $bd->consultar($sql);
	$result =$bd->obtener_fila($query,0);

	extract($result);


	if($_POST['ubicacion'] != "TODOS"){
		$sql02 = "SELECT clientes.abrev AS cl_abrev, clientes.nombre AS cliente,
		clientes.observacion AS cliente_observ, clientes_ubicacion.descripcion AS ubicacion,
		clientes_ubicacion.contacto AS cliente_contacto,  clientes_ubicacion.cargo AS cliente_cargo,
		clientes_ubicacion.observacion AS ubicacion_observ,IF(clientes_vetados.cod_ficha,'VETADO','') vetado
		FROM
		clientes ,
		clientes_ubicacion
		LEFT JOIN clientes_vetados ON clientes_vetados.cod_ubicacion = clientes_ubicacion.codigo AND clientes_vetados.cod_ficha = '$ficha'
		WHERE clientes_ubicacion.codigo = ".$_POST['ubicacion']."
		AND clientes_ubicacion.cod_cliente = clientes.codigo ";
		$query02 = $bd->consultar($sql02);
		$result02 =$bd->obtener_fila($query02,0);
		extract($result02);
	}

	if($_POST['puesto'] != "TODOS"){
		$sql02 = "SELECT clientes_ub_puesto.nombre puesto,clientes_ub_puesto.actividades,clientes_ub_puesto.observ puesto_observ FROM clientes_ub_puesto WHERE clientes_ub_puesto.codigo = '".$_POST['puesto'] ."' ";
		$query02 = $bd->consultar($sql02);
		$result02 =$bd->obtener_fila($query02,0);
		extract($result02);
	}

	$sql02 = "SELECT REPLACE(men_reportes_html.html, '&quot;', '\'') AS html
	FROM men_reportes_html
	WHERE men_reportes_html.codigo = $reporte ";
	$query02  = $bd->consultar($sql02);
	$result02 = $bd->obtener_fila($query02,0);

	$html =  html_entity_decode($result02['html']);
	eval("\$html = \"$html\";");
	
	echo $html. "\n";

	if(isset($_POST['cliente']) AND (isset($_POST['enviar_cliente']) OR isset($_POST['enviar_ubicacion']))){
		
		if(isset($_POST['enviar_cliente'])){
			$sql = "SELECT email FROM clientes WHERE codigo = '".$_POST['cliente']."' ";
			$query = $bd->consultar($sql);
			$result =$bd->obtener_fila($query,0);
			$email =$result['email'];
		//Formato de propiedades de la funcion enviar_mail_html($host,$puerto,$smtpSecure,$cuentaDeEnvio,$passwordCuentaDeEnvio,$nombre,$tema,$cuerpo,$cuerpoHtml,$cuentaDestino) 
			enviar_mail_html($host,$puerto,$protocolo,$cuenta,$password,'IBARTI',$rep.' ('.$trabajador.')','LEER',$html,$email);
		}

		if(isset($_POST['enviar_ubicacion'])){
			$sql = "SELECT email FROM clientes_ubicacion WHERE codigo = ".$_POST['ubicacion'];
			$query = $bd->consultar($sql);
			$result =$bd->obtener_fila($query,0);
			$email =$result['email'];
		//Formato de propiedades de la funcion enviar_mail_html($host,$puerto,$smtpSecure,$cuentaDeEnvio,$passwordCuentaDeEnvio,$nombre,$tema,$cuerpo,$cuerpoHtml,$cuentaDestino) 
			enviar_mail_html($host,$puerto,$protocolo,$cuenta,$password,'IBARTI',$rep.' ('.$trabajador.')','LEER',$html,$email);
		}
	}
}

?>
<body>
</body>
</html>
