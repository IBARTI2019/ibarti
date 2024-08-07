<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Documento sin t&iacute;tulo</title>
</head>
<?php
define("SPECIALCONSTANT", true);
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bdI);
$bd = new DataBase();
$tabla    = $_POST['tabla'];
$tabla_id = 'codigo';

$codigo       = $_POST["codigo"];
$nacionalidad = htmlentities($_POST["nacionalidad"]);
$estado_civil = htmlentities($_POST["estado_civil"]);
$nombre       = htmlentities($_POST["nombre"]);
$apellido     = htmlentities($_POST["apellido"]);
$fecha_nac    = conversion($_POST["fecha_nac"]);
$lugar_nac    = htmlentities($_POST["lugar_nac"]);
$experiencia  = htmlentities($_POST["experiencia"]);
$sexo         = $_POST["sexo"];
$correo      = htmlentities($_POST["correo"]);
$telefono     = htmlentities($_POST["telefono"]);
$celular      = htmlentities($_POST["celular"]);
$direccion    = htmlentities($_POST["direccion"]);
$estado       = $_POST["estado"];
$ciudad       = $_POST["ciudad"];
$cargo        = $_POST["cargo"];
$nivel_academico = $_POST["nivel_academico"];

$fec_preingreso = conversion($_POST["fec_preingreso"]);
$fec_psi     = conversion($_POST["fec_psi"]);
$psi_apto    = $_POST["psi_apto"];
$psic_observacion = htmlentities($_POST["psic_observacion"]);
$fec_pol     = conversion($_POST["fec_pol"]);
$pol_apto    = $_POST["pol_apto"];
$pol_observacion = htmlentities($_POST["pol_observacion"]);

$fec_pre_emp     = conversion($_POST["fec_pre_emp"]);

$pre_emp_apto    = $_POST["pre_emp_apto"];
$pre_emp_observacion = htmlentities($_POST["pre_emp_observacion"]);
$observacion     = htmlentities($_POST["observacion"]);


$status           = $_POST["status"];
$refp01_nombre    = htmlentities($_POST["refp01_nombre"]);
$refp01_ocupacion = htmlentities($_POST["refp01_ocupacion"]);
$refp01_telf      = htmlentities($_POST["refp01_telf"]);
$refp01_parentezco = htmlentities($_POST["refp01_parentezco"]);
$refp01_apto   = $_POST["refp01_apto"];
$refp01_direccion   = htmlentities($_POST["refp01_direccion"]);
$refp01_observacion = htmlentities($_POST["refp01_observacion"]);
$refp02_nombre = htmlentities($_POST["refp02_nombre"]);
$refp02_ocupacion = htmlentities($_POST["refp02_ocupacion"]);
$refp02_telf   = $_POST["refp02_telf"];
$refp02_parentezco = htmlentities($_POST["refp02_parentezco"]);
$refp02_apto   = $_POST["refp02_apto"];
$refp02_direccion   = htmlentities($_POST["refp02_direccion"]);
$refp02_observacion = htmlentities($_POST["refp02_observacion"]);
$refp03_nombre = htmlentities($_POST["refp03_nombre"]);
$refp03_ocupacion = htmlentities($_POST["refp03_ocupacion"]);
$refp03_telf   = htmlentities($_POST["refp03_telf"]);
$refp03_parentezco = htmlentities($_POST["refp03_parentezco"]);
$refp03_apto   = $_POST["refp03_apto"];
$refp03_direccion   = htmlentities($_POST["refp03_direccion"]);
$refp03_observacion = htmlentities($_POST["refp03_observacion"]);

$refl01_empresa  = htmlentities($_POST["refl01_empresa"]);
$refl01_telf     = htmlentities($_POST["refl01_telf"]);
$refl01_contacto = htmlentities($_POST["refl01_contacto"]);
$refl01_cargo    = htmlentities($_POST["refl01_cargo"]);
$refl01_sueldo_inic = $_POST["refl01_sueldo_inic"] != "" ? htmlentities($_POST["refl01_sueldo_inic"]) : 0;
$refl01_sueldo_fin  = $_POST["refl01_sueldo_fin"] != "" ? htmlentities($_POST["refl01_sueldo_fin"]) : 0;
$refl01_fec_ingreso = conversion($_POST["refl01_fec_ingreso"]);
$refl01_fec_egreso  = conversion($_POST["refl01_fec_egreso"]);
$refl01_retiro    = htmlentities($_POST["refl01_retiro"]);
$refl01_apto     = $_POST["refl01_apto"];
$refl01_direccion   = htmlentities($_POST["refl01_direccion"]);
$refl01_observacion = htmlentities($_POST["refl01_observacion"]);
$refl02_empresa  = htmlentities($_POST["refl02_empresa"]);
$refl02_telf     = htmlentities($_POST["refl02_telf"]);
$refl02_contacto = htmlentities($_POST["refl02_contacto"]);
$refl02_cargo    = htmlentities($_POST["refl02_cargo"]);
$refl02_sueldo_inic =  $_POST["refl02_sueldo_inic"] != "" ? htmlentities($_POST["refl02_sueldo_inic"]) : 0;
$refl02_sueldo_fin  = $_POST["refl02_sueldo_fin"] != "" ? htmlentities($_POST["refl02_sueldo_fin"]) : 0;
$refl02_fec_ingreso = conversion($_POST["refl02_fec_ingreso"]);
$refl02_fec_egreso  = conversion($_POST["refl02_fec_egreso"]);
$refl02_retiro    = htmlentities($_POST["refl02_retiro"]);
$refl02_apto     = $_POST["refl02_apto"];
$refl02_direccion   = htmlentities($_POST["refl02_direccion"]);
$refl02_observacion = htmlentities($_POST["refl02_observacion"]);

$t_pantalon     = $_POST['t_pantalon'];
$t_camisa       = $_POST['t_camisa'];
$n_zapato       = $_POST['n_zapato'];

$campo01 = htmlentities($_POST["campo01"]);
$campo02 = htmlentities($_POST["campo02"]);
$campo03 = htmlentities($_POST["campo03"]);
$campo04 = htmlentities($_POST["campo04"]);

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

$sql    = "SELECT control.preingreso_nuevo, control.preingreso_apto, 
	                   control.preingreso_aprobado, control.preingreso_rechazado
                  FROM control";
$query     = $bd->consultar($sql);
$result    = $bd->obtener_fila($query, 0);
$nuevo     = $result['preingreso_nuevo'];
$aprobado  = $result['preingreso_aprobado'];
$apto      = $result['preingreso_apto'];
$rechazado = $result['preingreso_rechazado'];
$rech      = 0;
$apt       = 0;

if ($status == $apto) {
	$status =  $aprobado;
}

if (($status == $nuevo) or ($status == $rechazado)) {

	if ($refp01_apto == 'S') {
		$apt++;
	} elseif ($refp01_apto == 'N') {
		$rech++;
	}

	if ($refp02_apto == 'S') {
		$apt++;
	} elseif ($refp02_apto == 'N') {
		$rech++;
	}

	if ($refp03_apto == 'S') {
		$apt++;
	} elseif ($refp03_apto == 'N') {
		$rech++;
	}

	if ($refl01_apto == 'S') {
		$apt++;
	} elseif ($refl01_apto == 'N') {
		$rech++;
	}

	/* 	
	if ($refl02_apto == 'S') {
		$apt++;
	} elseif ($refl02_apto == 'N') {
		$rech++;
	} 
	*/

	if ($psi_apto == 'A') {
		$apt++;
	} elseif ($psi_apto == 'R') {
		$rech++;
	}

	if ($pol_apto == 'A') {
		$apt++;
	} elseif ($pol_apto == 'R') {
		$rech++;
	}
	// VALIDO
	if ($rech > 0) {
		$status =  $rechazado;
	} elseif ($apt >= 6) {
		$status = $apto;
	} else {
		$status =  $status;
	}
}


if (isset($_POST['proced'])) {

	$sql    = "$SELECT $proced('$metodo', '$codigo', '$nacionalidad',  '$estado_civil',
	                            '$apellido', '$nombre', '$fecha_nac', '$lugar_nac',
							    '$sexo', '$telefono', '$celular', '$correo',
								'$experiencia', '$direccion',
								'$estado', '$ciudad', '$nivel_academico', '$cargo', 
								'$fec_preingreso', '$fec_psi', '$psi_apto', '$psic_observacion', 
								'$fec_pol', '$pol_apto', '$pol_observacion',
								'$fec_pre_emp', '$pre_emp_apto', '$pre_emp_observacion', '$observacion',
								'$refp01_nombre', '$refp01_ocupacion', '$refp01_telf', '$refp01_parentezco', 
								'$refp01_direccion', '$refp01_observacion', '$refp01_apto', '$refp02_nombre', 
								'$refp02_ocupacion', '$refp02_telf', '$refp02_parentezco', '$refp02_direccion', 
								'$refp02_observacion', '$refp02_apto', '$refp03_nombre', '$refp03_ocupacion', 
								'$refp03_telf', '$refp03_parentezco', '$refp03_direccion', '$refp03_observacion', 
								'$refp03_apto', '$refl01_empresa', '$refl01_telf', '$refl01_contacto',
								'$refl01_cargo', '$refl01_sueldo_inic', '$refl01_sueldo_fin', '$refl01_fec_ingreso',                                
								'$refl01_fec_egreso', '$refl01_direccion', '$refl01_observacion', '$refl01_retiro',
								'$refl01_apto', '$refl02_empresa', '$refl02_telf', '$refl02_contacto',
								'$refl02_cargo', '$refl02_sueldo_inic', '$refl02_sueldo_fin', '$refl02_fec_ingreso',
								'$refl02_fec_egreso', '$refl02_direccion', '$refl02_observacion', '$refl02_retiro',
								'$refl02_apto', '$t_camisa', '$t_pantalon', '$n_zapato',
								'$campo01', '$campo02', '$campo03', '$campo04', '$usuario',  '$status')";
	try {
		$query = $bd->consultar($sql);
	} catch (Exception $e) {
		echo $e;
	}

	//echo $sql;
}

if ($metodo == "agregar") {

	echo '<script languaje="JavaScript" type="text/javascript">
	if(confirm("¿Desea Agregar Fotos")) {
		  location.href="../inicio.php?area=formularios/add_imagenes&ci=' . $codigo . '&tipo=01";
	}
	</script>';
}
require_once('../funciones/sc_direccionar.php');
?>

<body>
</body>

</html>