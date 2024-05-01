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

if(isset($_POST["metodo"])=='borrar'){
	$check = "N";
	
}
echo $_POST["metodo"];
if ($_POST["metodo"]=='borrar') {

	$sql= "DELETE from ficha_documentos where cod_documento='$doc' and cod_ficha='$ficha' and link='$link';";

} else {
	if ($_POST["metodo"]=='agregar') {
		$sql = "INSERT INTO ficha_documentos (cod_documento, cod_ficha, link, checks, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod )
		VALUES( '$doc', '$ficha', '$link', 'S', '$usuario', CURRENT_DATE, '$usuario', CURRENT_DATE ) 
		ON DUPLICATE KEY UPDATE link = '$link',
		checks = '$check',
		cod_us_mod = '$usuario',
		fec_us_mod = CURRENT_DATE;";
	} else {
		if ($_POST["metodo"]=='modificar') {
	      $observacion=	$_POST["observacion"];	
		  $fecha_vence= $_POST["fecha_vencimiento"];
		  $vence=$_POST["vencimiento"];
          $sql ="UPDATE ficha_documentos SET cod_documento='$doc',cod_ficha='$ficha',checks='S',observacion='$observacion',vencimiento='$vence',venc_fecha='$fecha_vence',cod_us_ing='$usuario',fec_us_ing=CURRENT_DATE,cod_us_mod= '$usuario',fec_us_mod=CURRENT_DATE where cod_documento='$doc' and cod_ficha='$ficha' and link='$link';";
		 	
		}

	}
}

$query = $bd->consultar($sql);

?>