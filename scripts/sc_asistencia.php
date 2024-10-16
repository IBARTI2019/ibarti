<?php
define("SPECIALCONSTANT", true);
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bdI);
$bd = new DataBase();

$tabla    = 'asistencia';

$apertura       = $_POST['apertura'];
$contracto      = $_POST['contracto'];
$trabajador     = $_POST['trabajador'];
$cliente        = $_POST['cliente'];
$ubicacion      = $_POST['ubicacion'];
$ubicacion_old  = $_POST['ubicacion_old'];
$concepto       = $_POST['concepto'];
$clasif_asistencia       = $_POST['clasif_asistencia'];
$concepto_old   = $_POST['concepto_old'];
$horaD          = $_POST['horaD'];
$horaN          = $_POST['horaN'];
$vale           = $_POST['vale'];
$feriado        = $_POST['feriado'];
$NL             = $_POST['NL'];
if ($feriado == "") {
	$feriado = 0;
}
if ($NL == "") {
	$NL = 0;
}
if ($vale == "") {
	$vale = 0;
}
if ($horaD == "") {
	$horaD = 0.00;
}

if ($horaN == "") {
	$horaN = 0.00;
}

if ($clasif_asistencia == "" || $clasif_asistencia == "9999") {
	$clasif_asistencia = null;
}

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

$sql_cargo = "SELECT cod_cargo FROM ficha WHERE cod_ficha = '$trabajador';";
$query_cargo = $bd->consultar($sql_cargo);
$cargo = $bd->obtener_fila($query_cargo, 0);
$cod_cargo = $cargo[0];

$i = $_POST['metodo'];

//  PROBLEMAS CON LA FECHA EN APERTURA DE FECHA

if (isset($_POST['metodo'])) {
	switch ($i) {
		case 'agregar':
		case 'modificar':
		case 'borrar':
			//	 	 begin();


			$sql    = "$SELECT $proced('$metodo', '$apertura', '$trabajador', '$cliente',
                               '$ubicacion', '$ubicacion_old', '$concepto', '$concepto_old',
							   '$clasif_asistencia', '$horaD', '$horaN', '$vale', '$feriado',
							   '$NL', '$usuario', '$cod_cargo')";

			$query = $bd->consultar($sql);

			$sql = "SELECT v_ficha.nombres, conceptos.descripcion
               FROM v_ficha, conceptos
              WHERE v_ficha.cod_ficha = '$trabajador'
                AND conceptos.codigo = '$concepto'";

			$query = $bd->consultar($sql);
			$row = $bd->obtener_fila($query, 0);
			echo	$mensaje = 'Se Actualizo: &nbsp; ' . $row[0] . ', (' . $trabajador . '), Concepto:  ' . $row[1] . '';

			break;
	}
}
require_once('../funciones/sc_direccionar.php');
