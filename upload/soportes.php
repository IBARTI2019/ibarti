<?php 
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$link     = $_POST["link"]; 
$ficha    = $_POST["ficha"]; 
$folder    = $_POST["folder"];

if(isset($_SESSION['usuario_cod'])){
	$usuario = $_SESSION['usuario_cod'];
}else{
	$usuario = $_POST['usuario'];
}

if($folder == 'soportes_de_liquidacion'){
	$sql = "UPDATE ficha_egreso SET       
		soporte_pago = '$link',
		cod_us_soporte_pago = '$usuario',       
		fec_us_soporte_pago = CURRENT_TIMESTAMP
	WHERE cod_ficha   = '$ficha'; ";
}else{
	$sql = "UPDATE ficha_egreso SET       
		calculo_pago = '$link',
		cod_us_calculo_pago = '$usuario',       
		fec_us_calculo_pago = CURRENT_TIMESTAMP
	WHERE cod_ficha   = '$ficha'; ";
}


$query = $bd->consultar($sql);
echo $sql;	
?>