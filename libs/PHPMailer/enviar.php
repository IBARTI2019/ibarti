<?php
require("class.phpmailer.php");
require("class.smtp.php");

$result = array();
function enviar_mail_html($host,$puerto,$smtpSecure,$cuentaDeEnvio,$passwordCuentaDeEnvio,$nombre,$tema,$cuerpo,$cuerpoHtml,$cuentaDestino) {
	$mail = new PHPMailer() ;

	$mail->IsSMTP(); 

//Sustituye (ServidorDeCorreoSMTP)  por el host de tu servidor de correo SMTP
	$mail->Host = $host;		
	$mail->Port       = $puerto;  
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = $smtpSecure; 
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only

	$mail->From     = $cuentaDeEnvio;
	$mail->FromName = $nombre;
	$mail->Subject  = $tema;

	$mail->AltBody  = $cuerpo; 
	$mail->MsgHTML($cuerpoHtml);
	$mail->AddAddress($cuentaDestino,'');
	$mail->SMTPAuth = true;

	$mail->Username = $cuentaDeEnvio;
	$mail->Password = $passwordCuentaDeEnvio; 

	if($mail->Send()){
		$result['mensaje'] = "Enviado a ".$cuentaDestino;	
		echo json_encode($result);
	}else{
		$result['error'] =  true;
		$result['mensaje'] = "Error: ".$mail->ErrorInfo;	
		echo json_encode($result);
	}
}