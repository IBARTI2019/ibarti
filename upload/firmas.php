<?php 
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$link     = $_POST["link"]; 
$ficha    = $_POST["ficha"]; 
$reporte    = $_POST["reporte"];

if(isset($_SESSION['usuario_cod'])){
	$usuario = $_SESSION['usuario_cod'];
}else{
	$usuario = $_POST['usuario'];
}

$sql = "INSERT INTO ficha_documentos_firmas (cod_documento, link, cod_ficha, cod_us_mod, fec_us_mod) 
		VALUES($reporte, '$link', '$ficha', '$usuario', CURRENT_TIMESTAMP) 
		ON DUPLICATE KEY UPDATE link = '$link', cod_us_mod = '$usuario', fec_us_mod = CURRENT_TIMESTAMP;";

$query = $bd->consultar($sql);
echo $sql;	
?>