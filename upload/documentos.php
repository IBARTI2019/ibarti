<?php 
require("../autentificacion/aut_config.inc.php");
require_once("../bd/class_mysqli.php");
$bd = new DataBase();

$link     = $_POST["link"]; 
$ficha    = $_POST["ficha"]; 
$doc      = $_POST["doc"];

if(isset($_SESSION['usuario_cod'])){
	$usuario = $_SESSION['usuario_cod'];
}else{
	$usuario = $_POST['usuario'];
}

$check = "S";

if(isset($_POST["borrar"])){
	$check = "N";
	
}

if ($_POST["borrar"]==true) {

	$sql= "DELETE from ficha_documentos where cod_documento='$doc' and cod_ficha='$ficha' and link='$link';";

} else {

	$sql = "INSERT INTO ficha_documentos (cod_documento, cod_ficha, link, checks, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod )
	VALUES( '$doc', '$ficha', '$link', 'S', '$usuario', CURRENT_DATE, '$usuario', CURRENT_DATE ) 
	ON DUPLICATE KEY UPDATE link = '$link',
	checks = '$check',
	cod_us_mod = '$usuario',
	fec_us_mod = CURRENT_DATE;";
	
}

$query = $bd->consultar($sql);

?>