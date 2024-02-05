<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
$variables = "\$".$nombre_campo."='".$valor."';";
eval($variables);
}

$check_list     = $_POST['check_list'];

if(isset($_POST['proced'])){
	try {
		$proced = $_POST['proced'];
		$sql    = " SELECT MAX(nov_check_list.codigo)
					FROM nov_check_list
					 WHERE nov_check_list.cod_nov_clasif = '$clasif'
					AND nov_check_list.cod_nov_tipo   = '$tipo' 
					AND nov_check_list.cod_cliente    = '$cliente' 
					AND nov_check_list.cod_ubicacion  = '$ubicacion' 
					AND nov_check_list.cod_ficha      = '$trabajador'";							  						  
		$result['sql'] = $sql;
		$query  = $bd->consultar($sql);	 			 
		$datos  = $bd->obtener_fila($query,0);	 
		$codigo_ant = $datos[0];
		$sql2 = "$SELECT $proced('agregar', '',  '$clasif', '$tipo', 
		'$cliente', '$ubicacion', '$trabajador', '$observacion',
		'$repuesta', '$campo01', '$campo02', '$campo03', '$campo04', '$usuario',  '$status')";	
		$result['sql2'] = $sql2;						  
		$query = $bd->consultar($sql2);	

		$sql3 = " SELECT MAX(nov_check_list.codigo)
		FROM nov_check_list
		WHERE nov_check_list.cod_nov_clasif = '$clasif'
		AND nov_check_list.cod_nov_tipo   = '$tipo' 
		AND nov_check_list.cod_cliente    = '$cliente' 
		AND nov_check_list.cod_ubicacion  = '$ubicacion' 
		AND nov_check_list.cod_ficha      = '$trabajador'";		
		$result['sql3'] = $sql3;						  						  
		$query  = $bd->consultar($sql3);	 			 
		$datos  = $bd->obtener_fila($query,0);	 
		$codigo = $datos[0];
		if($codigo > $codigo_ant){	
			foreach($check_list as $valorX){
				if(isset($_POST["check_list_valor_".$valorX.""])){	 			
					$cod_valor    = $_POST["cod_valor_".$valorX.""]; 
					$cod_agrupacion    = $_POST["cod_agrupacion_".$valorX.""]; 
					$valor        = $_POST["check_list_valor_".$valorX.""];
					$observacion  = htmlentities($_POST["observacion_".$valorX.""]); 

					$sql    = "$SELECT $proced2('agregar', '$codigo','$valorX', '$cod_agrupacion', '$valor',
					'$observacion')";			
					$result[$cod_valor] = $sql;				  
					$query = $bd->consultar($sql);
				}
			}
		}

		$sql4 = " UPDATE planif_clientes_superv_trab_det_participantes SET evaluado = 'T' WHERE codigo = $cod_participante_det";	
		$result['sql4'] = $sql4;						  						  
		$query  = $bd->consultar($sql4);
	}catch (Exception $e) {
		$error =  $e->getMessage();
		$result['error'] = true;
		$result['mensaje'] = $error;
		$bd->log_error("Aplicacion", "sc_hora.php",  "$usuario", "$error", "$sql");
	}
}

print_r(json_encode($result));
return json_encode($result);