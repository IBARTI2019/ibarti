<?php 
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$link     = $_POST["link"]; 
$ci       = $_POST["ci"]; 
$ficha    = $_POST["ficha"]; 
$doc      = $_POST["doc"];
$usuario  = $_SESSION['usuario_cod'];

$sql = " INSERT INTO ficha_documentos (cod_documento, cod_ficha, link, checks, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod )
	VALUES( '$doc', '$ficha', '$link', 'S', '$usuario', CURRENT_DATE, '$usuario', CURRENT_DATE ) 
	ON DUPLICATE KEY UPDATE link = '$link',
	checks = 'S',
	cod_us_mod = '$usuario',
	fec_us_mod = CURRENT_DATE;";

$query = $bd->consultar($sql);
	
?>