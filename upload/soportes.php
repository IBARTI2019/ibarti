<?php 
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$link     = $_POST["link"]; 
$ficha    = $_POST["ficha"]; 

if(isset($_SESSION['usuario_cod'])){
	$usuario = $_SESSION['usuario_cod'];
}else{
	$usuario = $_POST['usuario'];
}

$sql = "UPDATE ficha_egreso SET       
	soporte_pago = '$link',
	cod_us_soporte_pago = '$usuario',       
	fec_us_soporte_pago = CURRENT_DATE
WHERE cod_ficha   = '$ficha'; ";

$query = $bd->consultar($sql);
echo $sql;	
?>