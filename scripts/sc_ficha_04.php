<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla    = 'ficha';

$codigo    = $_POST['codigo'];

$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

$href     = $_POST['href'];

if(isset($_POST['proced'])){

	$sql = "SELECT documentos.codigo AS cod_doc FROM documentos
			 WHERE documentos.`status` = 'T'
			 ORDER BY 1 ASC ";
	$query = $bd->consultar($sql);
		while($datos=$bd->obtener_fila($query,0)){
		extract($datos);
		$doc             = $_POST['documento'.$cod_doc.''];
		$doc_old         = $_POST['documento_old'.$cod_doc.''];
		$observ          = $_POST['observ_doc'.$cod_doc.''];
	  $vencimiento     = $_POST['vencimiento'.$cod_doc.''];
  	$fecha_venc      = $_POST['fecha_venc'.$cod_doc.''] != "" ? $_POST['fecha_venc'.$cod_doc.''] : '0000-00-00';

		$fecha_venc_old  = $_POST['fecha_venc_old'.$cod_doc.''] != "" ? $_POST['fecha_venc_old'.$cod_doc.''] : '0000-00-00';


		 $sql02    = "$SELECT $proced('$metodo', '$codigo', '$cod_doc', '$doc_old',
									                '$doc', '$observ', '$vencimiento', '$fecha_venc',
																	'$fecha_venc_old',   '$usuario')";
			echo $sql02 + "\n";
		 $query02  = $bd->consultar($sql02);
		}
}
// require_once('../funciones/sc_direccionar.php');
?>
