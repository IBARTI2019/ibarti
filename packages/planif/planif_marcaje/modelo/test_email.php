<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";;
require("../../../../libs/PHPMailer/enviar.php");
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();
$result['error'] = false;

$sql_smtp = "SELECT control.host_smtp,  control.puerto_smtp, control.protocolo_smtp,
control.cuenta_smtp,control.password_smtp FROM control;";

$query = $bd->consultar($sql_smtp);
$result =$bd->obtener_fila($query,0);
$host =$result['host_smtp'];
$puerto =$result['puerto_smtp'];
$protocolo =$result['protocolo_smtp'];
$cuenta=$result['cuenta_smtp'];
$password =$result['password_smtp'];

foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}

if (isset($codigo)) {
  try {
    $sql_email_cliente = "SELECT clientes.email FROM planif_clientes_superv_trab_det pd, planif_clientes_superv_trab p, clientes 
    WHERE pd.cod_planif_cl_trab = p.codigo AND p.cod_cliente = clientes.codigo AND pd.codigo = '$codigo' ";
    $query_email_cliente  = $bd->consultar($sql_email_cliente);
    $result =$bd->obtener_fila($query_email_cliente, 0);
    $email =$result['email'];

    //Formato de propiedades de la funcion enviar_mail_html($host,$puerto,$smtpSecure,$cuentaDeEnvio,$passwordCuentaDeEnvio,$nombre,$tema,$cuerpo,$cuerpoHtml,$cuentaDestino) 
    echo $host.",   ".$puerto.",   ".$protocolo.",   ".$cuenta.",   ".$password.",   ".'Comprobante'.",   ".'LEER'.",   "."".",   ". "<h1>TEST</h1>".",   ".$email;
    enviar_mail_html($host,$puerto,$protocolo,$cuenta,$password,'Comprobante','LEER',"", "",$email, $link);    
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_marcaje_supervisor.php",  "$usuario", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
