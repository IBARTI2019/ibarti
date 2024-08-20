<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla    = 'ficha';

$cedula         = $_POST['cedula'];
$cod_ficha      = $_POST['cod_ficha'];
$nacionalidad   = $_POST['nacionalidad'];
$estado_civil   = $_POST['estado_civil'];

$nombre         = htmlspecialchars($_POST['nombre']);
$apellido       = htmlspecialchars($_POST['apellido']);
$fecha_nac      = conversion($_POST['fecha_nac']);
$lugar_nac      = htmlspecialchars($_POST["lugar_nac"]);
$correo         = htmlspecialchars($_POST["correo"]);
$experiencia    = htmlspecialchars($_POST["experiencia"]);
$carnet         = $_POST['carnet'];
$foto           = '';
$fec_venc_carnet   = conversion($_POST['fec_venc_carnet']);

if($fec_venc_carnet == ''){
	$fec_venc_carnet   = '0000-00-00';
}

$fec_ingreso    = conversion($_POST['fec_ingreso']);

$sexo           = $_POST['sexo'];
$telefono       = htmlspecialchars($_POST['telefono']);
$celular       = htmlspecialchars($_POST['celular']);
$direccion      = htmlspecialchars($_POST['direccion']);
$ocupacion      = $_POST['ocupacion'];
$estado         = $_POST['estado'];
$ciudad         = $_POST['ciudad'];
$cargo          = $_POST['cargo'];
$nivel_academico = $_POST['nivel_academico'];
$contracto      = $_POST['contracto'];
$turno      = $_POST['turno'];


$fec_venc_contracto  = $fec_ingreso;  // dias vencimiento contractro no guardo
$n_contracto    = $_POST['n_contracto'];

$rol            = $_POST['rol'];
$region         = $_POST['region'];
$cliente        = $_POST['cliente'];
$ubicacion      = $_POST['ubicacion'];
$banco          = $_POST['banco'];
$cta_banco      = $_POST['cta_banco'];

$observacion     = htmlspecialchars($_POST["observacion"]);
$fec_profit     = conversion($_POST['fec_profit']);
$campo01        = '';
$campo02        = '';
$campo03        = $_POST['latitud'];
$campo04        = $_POST['longitud'];

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];
$status   = $_POST['status'];
$href     = $_POST['href'];

if(isset($_POST['servicio_militar'])){
	$servicio_militar 		  = 'T';
	$status_militar_obs 	  = $_POST['militar_obs'];
	$cod_ficha_status_militar = $_POST['cod_militar'];
}else{
	$servicio_militar 		  = 'F';
	$status_militar_obs 	  = $_POST['militar_obs'];
	$cod_ficha_status_militar = $_POST['cod_militar'];
	if(!$status_militar_obs){
		$status_militar_obs="";
	}
}



if(isset($_POST['proced'])){

  	 $sql    = "$SELECT $proced('$metodo', '$cedula', '$cod_ficha', '$nacionalidad',
								'$estado_civil',  '$nombre',  '$apellido', '$estado',
								'$ciudad', '$fecha_nac', '$lugar_nac', '$sexo',
								'$telefono', '$celular', '$correo', '$experiencia',
								'$direccion', '$observacion', '$cargo', '$ocupacion',
								'$nivel_academico', '$carnet', '$fec_venc_carnet', '$fec_ingreso',
								'$contracto','$n_contracto', '$fec_venc_contracto','$rol',
								'$region', '$cliente', '$ubicacion','$banco',
                '$cta_banco', '$fec_profit',
								'$campo01', '$campo02', '$campo03', '$campo04', '$usuario',  '$status', '$cod_ficha_status_militar', '$servicio_militar', '$status_militar_obs', '$turno' )";
	//  echo $sql;							
	 $query = $bd->consultar($sql);
}
require_once('../funciones/sc_direccionar.php');
?>
