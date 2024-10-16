!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title></title>
</head>
<?php

require("../autentificacion/aut_config.inc.php");
require("../libs/PHPMailer/enviar.php");
require_once("../".class_bd);
$bd = new DataBase();

$link = $_POST["link"];
$ubicacion   = $_POST["ubicacion"];
$email= array();

if(isset($_POST['ubicacion'])){

	$sql_smtp = "SELECT control.host_smtp,  control.puerto_smtp, control.protocolo_smtp,
	control.cuenta_smtp,control.password_smtp, cliente_ubicacion.codigo
	FROM control,clientes_ubicacion WHERE  clientes_ubicacion.codigo = '$ubicacion' ";

	$query = $bd->consultar($sql_smtp);
	$result =$bd->obtener_fila($query,0);
	$host =$result['host_smtp'];
	$puerto =$result['puerto_smtp'];
	$protocolo =$result['protocolo_smtp'];
	$cuenta=$result['cuenta_smtp'];
	$password =$result['password_smtp'];
	$rep = $result['codigo'];

	
	if(isset($_POST['ubicacion'])){
		$sql = "SELECT email,email1,email2,email3 FROM clientes_ubicacion WHERE codigo = ".$_POST['ubicacion'];
		$query = $bd->consultar($sql);
		$result =$bd->obtener_fila($query,0);
		if($result['email']){
			array_push($email, $result['email']);
		}
		if($result['email1']){
			array_push($email, $result['email1']);
		}
		if($result['email2']){
			array_push($email, $result['email2']);
		}
		if($result['email3']){
			array_push($email, $result['email3']);
		}
		if(count($email) > 0){
			//Formato de propiedades de la funcion enviar_mail_html($host,$puerto,$smtpSecure,$cuentaDeEnvio,$passwordCuentaDeEnvio,$nombre,$tema,$cuerpo,$cuerpoHtml,$cuentaDestino) 
			enviar_mail_htmlmasivos($host,$puerto,$protocolo,$cuenta,$password,'IBARTI',$rep.' ('.$trabajador.')','LEER',$link,$email);
		}
		
	}
}

?>
<body>
</body>
</html>