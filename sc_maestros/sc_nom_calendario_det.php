<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$codigo     = $_POST['codigo'];
$fechas     = $_POST['fechas'];
$tipo       = $_POST['tipo'];
$calendario_Fijo = $_POST['calendario_Fijo'];


$usuario    = $_POST['usuario'];
$proced     = $_POST['proced'];
$proced2     = $_POST['proced2'];
$metodo     = $_POST['metodo'];

$i = $_POST['metodo'];
//  PROBLEMAS CON LA FECHA EN APERTURA DE FECHA

function conversionCal($fecha){
$fecha_N1 = explode("/", $fecha);
$m   = $fecha_N1[0];
$d   = $fecha_N1[1];
$a   = $fecha_N1[2];

	if(($a=='0000') or ($m=="") or ($d=="")){
	$fecha='';
	}else{
	$fecha=$a."-".$m."-".$d;
	}
	return $fecha;
	}

	function conversionCal02($fecha){
	$fecha_N1 = explode("/", $fecha);
	$d   = $fecha_N1[0];
	$m   = $fecha_N1[1];
	$a   = $fecha_N1[2];

		if(($a=='0000') or ($m=="") or ($d=="")){
		$fecha='';
		}else{
		$fecha=$a."-".$m."-".$d;
		}
		return $fecha;
		}

if (isset($_POST['metodo'])) {
	$calendario_Fijo_param = ($calendario_Fijo === '') ? 'NULL' : "'$calendario_Fijo'";

	$sql   = "$SELECT $proced('borrar', '$codigo', '', 0, '', '$usuario')";
	$query = $bd->consultar($sql);


	$sql    = "$SELECT $proced2('mod_calend_nl', '$codigo', $calendario_Fijo_param, '',
	                           '', '$usuario', '')";
	$query = $bd->consultar($sql);


	$fechaX = explode(",", $fechas);
	$fechaN = " ";

	foreach ($fechaX as $k => $valorX) {
		$fechaN = conversionCal02($valorX);

		$sql    = "$SELECT $proced('$metodo', '$codigo', '$tipo', $calendario_Fijo_param, '$fechaN', '$usuario')";
	// echo $sql, "<br />";
		$query = $bd->consultar($sql);
	}
	$mensaje = "Registro Guardado Con Exitos ";
}

	 echo '<input type="hidden" id="mensaje_aj" value="'.$mensaje.'"/>';

// require_once('../funciones/sc_direccionar.php');
?>
