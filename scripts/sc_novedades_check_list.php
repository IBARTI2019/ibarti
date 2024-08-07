<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla    = 'ficha';

$codigo         = $_POST['codigo']; 
$descripcion    = htmlentities($_POST['descripcion']);
$trabajador     = $_POST['trabajador'];
$clasif         = $_POST['clasif'];
$tipo           = $_POST['tipo'];
$cliente        = $_POST['cliente'];
$ubicacion      = $_POST['ubicacion'];
$observacion    = $_POST['observacion'];
$repuesta       = htmlentities($_POST['repuesta']); 
$activo         = $_POST['status'];
$check_list     = $_POST['check_list'];
$campo01        = "";
$campo02        = "";
$campo03        = "";
$campo04        = "";

$href           = $_POST['href'];
$usuario        = $_POST['usuario']; 
$proced         = $_POST['proced'];
$proced2        = $_POST['proced2'];
$proced3        = $_POST['proced3'];
$metodo         = $_POST['metodo'];

	if(isset($_POST['proced'])){
		
	if($metodo == "agregar"){		
	    $sql    = " SELECT MAX(nov_check_list.codigo)
						  FROM nov_check_list
						 WHERE nov_check_list.cod_nov_clasif = '$clasif'
						   AND nov_check_list.cod_nov_tipo   = '$tipo' 
						   AND nov_check_list.cod_cliente    = '$cliente' 
						   AND nov_check_list.cod_ubicacion  = '$ubicacion' 
						   AND nov_check_list.cod_ficha      = '$trabajador'";							  						  
		 $query  = $bd->consultar($sql);	 			 
 	     $datos  = $bd->obtener_fila($query,0);	 
		 $codigo_ant = $datos[0];
	
				 $sql    = "$SELECT $proced('$metodo', '$codigo',  '$clasif', '$tipo', 
		                            '$cliente', '$ubicacion', '$trabajador', '$observacion',
									'$repuesta',
									'$campo01', '$campo02', '$campo03', '$campo04',  
								    '$usuario',  '$activo')";							  
			 $query = $bd->consultar($sql);	

	    $sql    = " SELECT MAX(nov_check_list.codigo)
						  FROM nov_check_list
						 WHERE nov_check_list.cod_nov_clasif = '$clasif'
						   AND nov_check_list.cod_nov_tipo   = '$tipo' 
						   AND nov_check_list.cod_cliente    = '$cliente' 
						   AND nov_check_list.cod_ubicacion  = '$ubicacion' 
						   AND nov_check_list.cod_ficha      = '$trabajador'";							  						  
		 $query  = $bd->consultar($sql);	 			 
 	     $datos  = $bd->obtener_fila($query,0);	 
		 $codigo = $datos[0];
		if($codigo > $codigo_ant){	
			 foreach($check_list as $valorX){
				if(isset($_POST["check_list_valor_".$valorX.""])){	 			
	
				$cod_valor    = $_POST["cod_valor_".$valorX.""]; 
				$valor        = $_POST["check_list_valor_".$valorX.""];
				$observacion  = htmlentities($_POST["observacion_".$valorX.""]); 
	
 				 $sql    = "$SELECT $proced2('$metodo', '$codigo','$valorX',  '$valor',
											 '$observacion')";				  
				 $query = $bd->consultar($sql);
				 
				}
			}
		}
		}elseif($metodo == "modificar"){
			 $sql    = "$SELECT $proced('$metodo', '$codigo',  '$clasif', '$tipo', 
								'$cliente', '$ubicacion', '$trabajador', '$observacion',
								'$repuesta',
								'$campo01', '$campo02', '$campo03', '$campo04',  
								'$usuario',  '$activo')";		
					  
			 $query = $bd->consultar($sql);	 	

			 foreach($check_list as $valorX){
				if(isset($_POST["check_list_valor_".$valorX.""])){	 			
	
				$cod_valor    = $_POST["cod_valor_".$valorX.""]; 
				$valor        = $_POST["check_list_valor_".$valorX.""];
				$observacion  = htmlentities($_POST["observacion_".$valorX.""]); 
	
 				 $sql    = "$SELECT $proced2('$metodo', '$codigo','$valorX',  '$valor',
											 '$observacion')";							  
				 $query = $bd->consultar($sql);
				 
				}
			
			}	
		}
	}
require_once('../funciones/sc_direccionar.php');
