<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$bd2 = new DataBase();

$tabla    = 'nov_perfiles';

$href          = $_POST['href'];
$cliente       =  $_POST['cliente'];	
$ubicacion     =  $_POST['ubicacion'];	
$nov_clasif    =  $_POST['nov_clasif'];	
$nov_tipo      =  $_POST['nov_tipo'];	
$novedad       =  $_POST['novedad'];	

$usuario  = $_POST['usuario']; 

$where = " WHERE novedades.codigo = novedades.codigo ";

if($nov_clasif != "TODOS"){		
	$where .= " AND novedades.cod_nov_clasif = '$nov_clasif' ";
}		

if($nov_tipo != "TODOS"){		
	$where .= " AND novedades.cod_nov_tipo = '$nov_tipo' ";  // cambie AND asistencia.co_cont = '$contracto'
}	

$sql   = "SELECT novedades.codigo FROM novedades $where ;";

$metodo   = $_POST['metodo'];

if (isset($_POST['metodo'])) {
	$i=$_POST['metodo'];
	switch ($i) {  	
		case 'actualizar':
			if($ubicacion == 'TODOS' || $ubicacion == ''){
				$sql_ubic = "SELECT codigo FROM clientes_ubicacion WHERE cod_cliente = '$cliente' AND status = 'T';";
				$query_ubics = $bd2->consultar($sql_ubic);
				while($rowUbic=$bd2->obtener_fila($query_ubics,0)){
					$query = $bd->consultar($sql);
					while($row03=$bd->obtener_fila($query,0)){
						$codigo = $row03[0];
		
						$sql02 = " DELETE FROM nov_cl_ubicacion WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$rowUbic[0]'
																AND nov_cl_ubicacion.cod_novedad = '$codigo';";
						$query02 = $bd->consultar($sql02);	
					}

					foreach($novedad as $valorX){
				
						$sqlX = "INSERT INTO nov_cl_ubicacion (cod_cl_ubicacion, cod_novedad, fecha, usuario )			
													VALUES ('$rowUbic[0]', '$valorX', '$date_time', '$usuario');";

						$queryX = $bd->consultar($sqlX);			 
					}
				}	
				break;	
			}else{
				while($row03=$bd->obtener_fila($query,0)){
					$codigo = $row03[0];
	
					$sql02 = " DELETE FROM nov_cl_ubicacion WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion'
															AND nov_cl_ubicacion.cod_novedad = '$codigo';";
					$query02 = $bd->consultar($sql02);
				}
	
				foreach($novedad as $valorX){
			
					$sqlX = "INSERT INTO nov_cl_ubicacion (cod_cl_ubicacion, cod_novedad, fecha, usuario )			
												VALUES ('$ubicacion', '$valorX', '$date_time', '$usuario');";
							
					$queryX = $bd->consultar($sqlX);			 
				}			
				break;	
			}		
	}        
}
require_once('../funciones/sc_direccionar.php');  
?>