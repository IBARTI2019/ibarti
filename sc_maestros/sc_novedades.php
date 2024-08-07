<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
//include_once('../funciones/mensaje_error.php');



$tabla_id = 'codigo';

$codigo      = htmlentities($_POST['codigo']);
$orden       = $_POST['orden'];
$clasif      = $_POST["clasif"];
$tipo        = $_POST["tipo"];
$descripcion = htmlentities($_POST["descripcion"]);			
$activo      = statusbd($_POST['activo']);
$valor       = $_POST["valor"];
$v_maximo     = $_POST["v_maximo"];
$href     = $_POST['href'];
$usuario  = $_POST['usuario']; 
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];
$dias_vencimiento  =$_POST['dias_v'];
$cantidad = $_POST['cantidad'];
	if(isset($_POST['proced'])){

	$sql    = "$SELECT $proced('$metodo', '$codigo', '$orden', '$clasif', 
	                           '$tipo', '$descripcion', '$usuario', '$activo',$dias_vencimiento, $v_maximo)";						 		


	$query = $bd->consultar($sql);	
	//if(count($valor)>0){
		$sql   = "DELETE FROM nov_valores_det WHERE cod_novedades = '$codigo'";
    $query = $bd->consultar($sql);	

		 foreach($valor as $clave=>$valorX){
	//	$cantidad     = $_POST['cantidad_'.$valorX.''];

		$sql = "INSERT INTO nov_valores_det
					 (cod_valores, cod_novedades, valor)			
			  VALUES ( '$valorX', '$codigo', '$cantidad[$clave]')";	
					  
		    $query = $bd->consultar($sql);			 
		 }		   	
	}
	//}
	
	
require_once('../funciones/sc_direccionar.php');  
?>