<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../bd/class_mysqli.php");
$bd = new DataBase();

$tabla       = 'men_perfil_menu';
$tabla_id    = 'cod_men_perfil';
$proced      = "p_men_perfil_det";

$codigo      = $_POST['codigo'];
$descripcion = strtoupper($_POST['descripcion']);
$href        = $_POST['href'];      
$usuario     = $_POST['usuario'];
$orden       = $_POST['orden'];
$status      = statusbd($_POST['status']);
$cod_criticidad= $_POST['cod_criticidad'];
// codcriticidad

	if (isset($_POST['metodo'])) {
	$i=$_POST['metodo'];
		switch ($i) {
		case 'agregar':
		
				$sql = "INSERT INTO men_perfiles
					           (codigo, descripcion, orden, status,idcriticidad )		
			            VALUES ('$codigo', '$descripcion', '$orden', '$status','$cod_criticidad')";						  
			    $query = $bd->consultar($sql);	

		break;
		case 'modificar':    	 

					$sql =" UPDATE men_perfiles SET   
								   descripcion  = '$descripcion',    orden   = '$orden',
								   status       = '$status'								    
						    WHERE  codigo       = '$codigo'";
			    $query = $bd->consultar($sql);	

		break;		 
		case 'modificar_perfil':    	
		$modulo      = $_POST['modulo'];
		$menu      	 = $_POST['menu'];
		$menu_cons   = $_POST['menu_cons'];
		$menu_add    = $_POST['menu_add'];
		$menu_mod    = $_POST['menu_mod'];
		$menu_eli    = $_POST['menu_eli'];
 
 
			$sql = "DELETE FROM $tabla WHERE cod_men_perfil = '$codigo' AND cod_menu_modulo ='$modulo' AND cod_men_principal = '$menu'";
			$query = $bd->consultar($sql);
				
		 foreach($menu_cons as $valorX){
				$sql    = "$SELECT $proced('consultar', '$codigo', '$valorX', '$menu', '$modulo', '$usuario')";
			$query  = $bd->consultar($sql);	
		 }	
		 
		 foreach($menu_add as $valorX){
			$sql    = "$SELECT $proced('agregar', '$codigo', '$valorX', '$menu', '$modulo', '$usuario')";
			$query  = $bd->consultar($sql);	
		 }	
		 
		 foreach($menu_mod as $valorX){
			$sql    = "$SELECT $proced('modificar', '$codigo', '$valorX', '$menu', '$modulo', '$usuario')";
			$query  = $bd->consultar($sql);	
		 }
		 	
		 foreach($menu_eli as $valorX){
			$sql    = "$SELECT $proced('eliminar', '$codigo', '$valorX', '$menu', '$modulo', '$usuario')";
			$query  = $bd->consultar($sql);	
		 }
			
		 break;
		}        
	}
require_once('../funciones/sc_direccionar.php');  
?>