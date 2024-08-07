<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
//include_once('../funciones/mensaje_error.php');
$tabla        = $_POST['tabla'];
$tabla_id     = 'codigo';
$codigo       = $_POST["codigo"];
$nacionalidad = htmlspecialchars($_POST["nacionalidad"]);
$estado_civil = htmlspecialchars($_POST["estado_civil"]);
$nombre       = htmlspecialchars($_POST["nombre"]);
$apellido     = htmlspecialchars($_POST["apellido"]);
$fecha_nac    = conversion($_POST["fecha_nac"]);
$lugar_nac    = htmlspecialchars($_POST["lugar_nac"]);
$experiencia  = htmlspecialchars($_POST["experiencia"]);
$sexo         = $_POST["sexo"];
$correo       = htmlspecialchars($_POST["correo"]);
$telefono     = htmlspecialchars($_POST["telefono"]);
$celular      = htmlspecialchars($_POST["celular"]);
$direccion    = htmlspecialchars($_POST["direccion"]);
$estado       = $_POST["estado"];
$ciudad       = $_POST["ciudad"];
$cargo        = $_POST["cargo"];
$nivel_academico = $_POST["nivel_academico"];

$fec_preingreso = conversion($_POST["fec_preingreso"]);
$fec_psi     = conversion($_POST["fec_psi"]);
$psi_apto    = $_POST["psi_apto"];
$psic_observacion = htmlspecialchars($_POST["psic_observacion"]);
$fec_pol     = conversion($_POST["fec_pol"]);
$pol_apto    = $_POST["pol_apto"];
$pol_observacion = htmlspecialchars($_POST["pol_observacion"]);
$fec_pre_emp     = conversion($_POST["fec_pre_emp"]);
$pre_emp_apto    = $_POST["pre_emp_apto"];
$pre_emp_observacion = htmlspecialchars($_POST["pre_emp_observacion"]);
$observacion     = htmlspecialchars($_POST["observacion"]);

$status           = $_POST["status"];
$refp01_nombre    = htmlspecialchars($_POST["refp01_nombre"]);
$refp01_ocupacion = htmlspecialchars($_POST["refp01_ocupacion"]);
$refp01_telf      = htmlspecialchars($_POST["refp01_telf"]);
$refp01_parentezco = htmlspecialchars($_POST["refp01_parentezco"]);
$refp01_apto      = $_POST["refp01_apto"];
$refp01_direccion   = htmlspecialchars($_POST["refp01_direccion"]);
$refp01_observacion = htmlspecialchars($_POST["refp01_observacion"]);
$refp02_nombre  = htmlspecialchars($_POST["refp02_nombre"]);
$refp02_ocupacion = htmlspecialchars($_POST["refp02_ocupacion"]);
$refp02_telf    = $_POST["refp02_telf"];
$refp02_parentezco = htmlspecialchars($_POST["refp02_parentezco"]);
$refp02_apto    = $_POST["refp02_apto"];
$refp02_direccion   = htmlspecialchars($_POST["refp02_direccion"]);
$refp02_observacion = htmlspecialchars($_POST["refp02_observacion"]);
$refp03_nombre  = htmlspecialchars($_POST["refp03_nombre"]);
$refp03_ocupacion = htmlspecialchars($_POST["refp03_ocupacion"]);
$refp03_telf    = htmlspecialchars($_POST["refp03_telf"]);
$refp03_parentezco = htmlspecialchars($_POST["refp03_parentezco"]);
$refp03_apto   = $_POST["refp03_apto"];
$refp03_direccion   = htmlspecialchars($_POST["refp03_direccion"]);
$refp03_observacion = htmlspecialchars($_POST["refp03_observacion"]);

$refl01_empresa  = htmlspecialchars($_POST["refl01_empresa"]);
$refl01_telf     = htmlspecialchars($_POST["refl01_telf"]);
$refl01_contacto = htmlspecialchars($_POST["refl01_contacto"]);
$refl01_cargo    = htmlspecialchars($_POST["refl01_cargo"]);
$refl01_sueldo_inic = $refl01_sueldo_inic != "" ? htmlspecialchars($_POST["refl01_sueldo_inic"]) : 0;
$refl01_sueldo_fin  = $refl01_sueldo_fin != "" ? htmlspecialchars($_POST["refl01_sueldo_fin"]) : 0;

$refl01_fec_ingreso = conversion($_POST["refl01_fec_ingreso"]);
$refl01_fec_egreso  = conversion($_POST["refl01_fec_egreso"]);
$refl01_retiro    = htmlspecialchars($_POST["refl01_retiro"]);
$refl01_apto     = $_POST["refl01_apto"];
$refl01_direccion   = htmlspecialchars($_POST["refl01_direccion"]);
$refl01_observacion = htmlspecialchars($_POST["refl01_observacion"]);
$refl02_empresa  = htmlspecialchars($_POST["refl02_empresa"]);
$refl02_telf     = htmlspecialchars($_POST["refl02_telf"]);
$refl02_contacto = htmlspecialchars($_POST["refl02_contacto"]);
$refl02_cargo    = htmlspecialchars($_POST["refl02_cargo"]);
$refl02_sueldo_inic = $refl02_sueldo_inic != "" ? htmlspecialchars($_POST["refl02_sueldo_inic"]) : 0;
$refl02_sueldo_fin  = $refl02_sueldo_fin != "" ? htmlspecialchars($_POST["refl02_sueldo_fin"]) : 0;
$refl02_fec_ingreso = conversion($_POST["refl02_fec_ingreso"]);
$refl02_fec_egreso  = conversion($_POST["refl02_fec_egreso"]);
$refl02_retiro    = htmlspecialchars($_POST["refl02_retiro"]);
$refl02_apto     = $_POST["refl02_apto"];
$refl02_direccion   = htmlspecialchars($_POST["refl02_direccion"]);
$refl02_observacion = htmlspecialchars($_POST["refl02_observacion"]);

$t_pantalon     = $_POST['t_pantalon'];
$t_camisa       = $_POST['t_camisa'];
$n_zapato       = $_POST['n_zapato'];

$campo01 = htmlspecialchars($_POST["campo01"]);
$campo02 = htmlspecialchars($_POST["campo02"]);
$campo03 = htmlspecialchars($_POST["campo03"]);
$campo04 = htmlspecialchars($_POST["campo04"]);

$href     = $_POST['href'];
$usuario  = $_POST['usuario']; 
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];


  	 $sql    = "SELECT control.preingreso_nuevo, control.preingreso_apto, 
	                   control.preingreso_aprobado, control.preingreso_rechazado
                  FROM control";						  
	$query     = $bd->consultar($sql);	
	$result    = $bd->obtener_fila($query,0); 
	$nuevo     = $result['preingreso_nuevo']; 
	$aprobado  = $result['preingreso_aprobado']; 
	$apto      = $result['preingreso_apto']; 
	$rechazado = $result['preingreso_rechazado'];
	
	$rech      = 0;
	$apt       = 0;

	if($status == $apto){
	   $status =  $aprobado;
	}
		
	if(($status == $nuevo) or ($status == $rechazado)){
		
		if($refp01_apto == 'S'){
		$apt++;
		}elseif($refp01_apto == 'N'){
		$rech++;	
		}
		
		if($refp02_apto == 'S'){
		$apt++;
		}elseif($refp02_apto == 'N'){
		$rech++;	
		}		
		
		if($refp03_apto == 'S'){
		$apt++;
		}elseif($refp03_apto == 'N'){
		$rech++;	
		}		
		
		if($refl01_apto == 'S'){
		$apt++;
		}elseif($refl01_apto == 'N'){
		$rech++;	
		}	
		
		/* if($refl02_apto == 'S'){
		$apt++;
		}elseif($refl02_apto == 'N'){
		$rech++;	
		} */
						
		if($psi_apto == 'A' OR $psi_apto== 'C'){
		$apt++;
		}elseif($psi_apto == 'R' ){
		$rech++;	
		}
		
		if($pol_apto == 'A'){
		$apt++;
		}elseif($pol_apto == 'R'){
		$rech++;	
		}			
		// VALIDO
		if($rech > 0){
		$status =  $rechazado;	
		}elseif($apt >= 6){
	   $status = $apto;			
		}else{
	   $status =  $status;		
		}		
	}		
	

	if(isset($_POST['proced'])){

	 $sql    = "$SELECT $proced('$metodo', '$codigo', '$nacionalidad',  '$estado_civil',
	                            '$apellido', '$nombre', '$fecha_nac', '$lugar_nac',
							    '$sexo', '$telefono', '$celular', '$correo',
								'$experiencia', '$direccion',
								'$estado', '$ciudad', '$nivel_academico', '$cargo', 
								'$fec_preingreso', '$fec_psi', '$psi_apto', '$psic_observacion', 
								'$fec_pol', '$pol_apto', '$pol_observacion', 
								'$fec_pre_emp', '$pre_emp_apto', '$pre_emp_observacion','$observacion',
								'$refp01_nombre', '$refp01_ocupacion', '$refp01_telf', '$refp01_parentezco', 
								'$refp01_direccion', '$refp01_observacion', '$refp01_apto', '$refp02_nombre', 
								'$refp02_ocupacion', '$refp02_telf', '$refp02_parentezco', '$refp02_direccion', 
								'$refp02_observacion', '$refp02_apto', '$refp03_nombre', '$refp03_ocupacion', 
								'$refp03_telf', '$refp03_parentezco', '$refp03_direccion', '$refp03_observacion', 
								'$refp03_apto', '$refl01_empresa', '$refl01_telf', '$refl01_contacto',
								'$refl01_cargo', '0', '$refl01_sueldo_fin', '$refl01_fec_ingreso',                                '$refl01_fec_egreso', '$refl01_direccion', '$refl01_observacion', '$refl01_retiro',
								'$refl01_apto', '$refl02_empresa', '$refl02_telf', '$refl02_contacto',
								'$refl02_cargo', '$refl02_sueldo_inic', '$refl02_sueldo_fin', '$refl02_fec_ingreso',
								'$refl02_fec_egreso', '$refl02_direccion', '$refl02_observacion', '$refl02_retiro',
								'$refl02_apto', '$t_camisa', '$t_pantalon', '$n_zapato',
								'$campo01', '$campo02', '$campo03', '$campo04', '$usuario',  '$status')";

	 $query = $bd->consultar($sql);	  			   		
	}

	 if($metodo == "agregar"){

	echo '<script languaje="JavaScript" type="text/javascript">
	if(confirm("¿Desea Agregar Fotos")) {
		  location.href="../inicio.php?area=formularios/add_imagenes&ci='.$codigo.'&tipo=01";
	}
	</script>';	
	}	

 require_once('../funciones/sc_direccionar.php'); 
 	
?>

<body>
</body>
</html>