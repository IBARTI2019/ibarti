<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla    = 'nov_procesos';
$tabla_id = 'codigo';

$codigo         = $_POST['codigo']; 
$trabajador     = $_POST['trabajador'];
$novedad        = $_POST['novedad'];
$cliente        = $_POST['cliente'];
$ubicacion      = $_POST['ubicacion'];
$observacion    = $_POST['observacion']; 
$repuesta       = $_POST['repuesta']; 
$activo         = $_POST['status'];

$campo01        = "";
$campo02        = "";
$campo03        = "";
$campo04        = "";

$href     = $_POST['href'];
$usuario  = $_POST['usuario']; 
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

	if(isset($_POST['proced'])){
		
	$sql    = "$SELECT $proced('$metodo', '$codigo', '$novedad', '$cliente', 
								'$ubicacion', '$trabajador', '$observacion', '$repuesta',
                                '$campo01', '$campo02', '$campo03', '$campo04', 
								'$usuario',  '$activo')";						  
	 $query = $bd->consultar($sql);	  			   		

	}
 require_once('../funciones/sc_direccionar.php');  
?>