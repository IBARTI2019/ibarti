<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Documento sin t&iacute;tulo</title>
</head>
<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../bd/class_mysqli.php");
$bd = new DataBase();
//include_once('../funciones/mensaje_error.php');
$tabla    = $_POST['tabla'];
$tabla_id = 'codigo';

$codigo      = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$tipo = null;
if (isset($_POST['tipo'])) {
	$tipo = $_POST['tipo'];
}
if (isset($_POST['orden'])) {
	$orden = $_POST['orden'];
}

if (isset($_POST['conceptos'])) {
	$conceptos = $_POST['conceptos'];
} else {
	$conceptos = [];
}

if (isset($_POST['motivo'])) {
	$motivo = $_POST['motivo'];
}
$campo01 = $_POST['campo01'];
$campo02 = $_POST['campo02'];
$campo03 = $_POST['campo03'];
$campo04 = $_POST['campo04'];
$activo = 'F';
if (isset($_POST['activo'])) {
	$activo      = statusbd($_POST['activo']);
}
$kanban = 'F';
if (isset($_POST['kanban'])) {
	$kanban      = statusbd($_POST['kanban']);
}
$planificable = null;
if (isset($_POST['planificable'])) {
	$planificable = statusbd($_POST['planificable']);
}

$color = '';
if (isset($_POST['color'])) {
	$color      = $_POST['color'];
}

$inicial = 'F';
if (isset($_POST['inicial'])) {
	$inicial = statusbd($_POST['inicial']);
}

$anula_vencimiento = 'F';
if (isset($_POST['anula_vencimiento'])) {
	$anula_vencimiento = statusbd($_POST['anula_vencimiento']);
}

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];

if (isset($_POST['metodo'])) {

	$i = $_POST['metodo'];
	switch ($i) {
        case 'vector':
            if ($tabla == 'vectoreans') {
				$sql = "INSERT INTO $tabla (codigo,vector) 
					VALUES ('$codigo', '$codigo')";
                $query = $bd->consultar($sql);
                
			}
            break;    
        case 'agregarean':
            if ($tabla == 'tabla1') {
				$sql = "INSERT INTO $tabla (codigo,vector) 
					VALUES ('$codigo', '$codigo')";
                $query = $bd->consultar($sql);
                
			}
            break;
		case 'agregar':
			if ($tabla == 'cargos') {
				$sql = "INSERT INTO $tabla (codigo, descripcion, cod_tipo, campo01, campo02, campo03, campo04,
					cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status, planificable) 
					VALUES ('$codigo', '$descripcion', '$tipo',
							'$campo01', '$campo02', '$campo03', '$campo04', 
							'$usuario', '$date', '$usuario','$date' , '$activo', '$planificable')";
			} else {
				if ($tabla == 'documentos' || $tabla == 'documentos_cl') {
					$sql = "INSERT INTO $tabla (codigo, descripcion, orden, campo01, campo02, campo03, campo04,
                                            cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status) 
                                    VALUES ('$codigo', '$descripcion', $orden,
									        '$campo01', '$campo02', '$campo03', '$campo04', 
											'$usuario', '$date', '$usuario','$date' , '$activo')";
				} else if ($tabla == 'ficha_egreso_motivo') {
					$sql = "INSERT INTO $tabla (codigo, descripcion, motivo, campo01, campo02, campo03, campo04,
					cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status) 
					VALUES ('$codigo', '$descripcion', '$motivo',
							'$campo01', '$campo02', '$campo03', '$campo04', 
							'$usuario', '$date', '$usuario','$date' , '$activo')";
				} else if ($tabla == 'nov_tipo') {
					$sql = "INSERT INTO $tabla (codigo, descripcion, kanban, campo01, campo02, campo03, campo04,
					cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status) 
					VALUES ('$codigo', '$descripcion', '$kanban',
							'$campo01', '$campo02', '$campo03', '$campo04', 
							'$usuario', '$date', '$usuario','$date' , '$activo')";
				} else if ($tabla == 'nov_status_kanban') {
					$sql = "INSERT INTO $tabla (codigo, descripcion, color, campo01, campo02, campo03, campo04,
					cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status, inicial, anula_vencimiento) 
					VALUES ('$codigo', '$descripcion', '$color',
							'$campo01', '$campo02', '$campo03', '$campo04', 
							'$usuario', '$date', '$usuario','$date' , '$activo', '$inicial', '$anula_vencimiento')";
				} else {
					$sql = "INSERT INTO $tabla (codigo, descripcion, campo01, campo02, campo03, campo04,
                                            cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, status) 
                                    VALUES ('$codigo', '$descripcion',
									        '$campo01', '$campo02', '$campo03', '$campo04', 
											'$usuario', '$date', '$usuario','$date' , '$activo')";
				}
			}
			$query = $bd->consultar($sql);
			if ($tabla == 'asistencia_clasif') {
				$sql = "DELETE FROM asistencia_clasif_concepto WHERE cod_asistencia_clasif = '$codigo'";
				$query = $bd->consultar($sql);

				foreach ($conceptos as $cod_concepto) {
					// $array[3] se actualizará con cada valor de $array...
					$sql_concepto = "INSERT INTO asistencia_clasif_concepto(cod_asistencia_clasif, cod_concepto, cod_us_ing, fec_us_ing) 
					VALUES('$codigo', '$cod_concepto', '$usuario', '$date')";
					$query = $bd->consultar($sql_concepto);
				}
			}
			echo $sql;
			break;
		case 'modificar':
			$sql = "UPDATE $tabla SET   
						          codigo          = '$codigo',     descripcion    = '$descripcion',
								  campo01     = '$campo01',    campo02        = '$campo02',
								  campo03     = '$campo03',    campo04        = '$campo04', 
						          cod_us_mod  = '$usuario',    fec_us_mod     = '$date',
								  status      = '$activo'";
			if ($tabla == 'cargos') {
				$sql .= " ,planificable = '$planificable', cod_tipo = $tipo ";
			}
			if ($tabla == 'documentos' || $tabla == 'documentos_cl') {
				$sql .= " ,orden = $orden ";
			}
			if ($tabla == 'ficha_egreso_motivo') {
				$sql .= " ,motivo = '$motivo' ";
			}
			if ($tabla == 'nov_tipo') {
				$sql .= " , kanban = '$kanban' ";
			}
			if ($tabla == 'nov_status_kanban') {
				$sql .= " , color = '$color', inicial = '$inicial', anula_vencimiento = '$anula_vencimiento' ";
			}

			$sql .= " WHERE codigo = '$codigo'";
			$query = $bd->consultar($sql);

			if ($tabla == 'nov_status_kanban' && $inicial = 'T') {
				$sql = " UPDATE nov_status_kanban SET inicial = 'F' WHERE codigo != '$codigo'";
				$query = $bd->consultar($sql);
			}

			if ($tabla == 'asistencia_clasif') {
				$sql = "DELETE FROM asistencia_clasif_concepto WHERE cod_asistencia_clasif = '$codigo'";
				$query = $bd->consultar($sql);
				foreach ($conceptos as $cod_concepto) {
					// $array[3] se actualizará con cada valor de $array...
					$sql_concepto = "INSERT INTO asistencia_clasif_concepto(cod_asistencia_clasif, cod_concepto, cod_us_ing, fec_us_ing) 
					VALUES('$codigo', '$cod_concepto', '$usuario', '$date')";
					$query = $bd->consultar($sql_concepto);
				}
			}
			break;
		case 'borrar':
			$sql = "DELETE FROM $tabla WHERE  $tabla_id = '$codigo'";
			$query = $bd->consultar($sql);
			break;
               
	}
}
require_once('../funciones/sc_direccionar.php');
?>

<body>
</body>

</html>