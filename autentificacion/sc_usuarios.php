<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

$tabla    = 'men_usuarios';
$tabla_id = 'codigo';

$codigo   = $_POST['codigo'];
$cedula	  = $_POST['cedula'];
$nombre   = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email    = $_POST['email'];


$login    = strtoupper($_POST['login']);

$password = md5($_POST['password1']);
$pass_ant = md5($_POST['passOLD']); // PASSWORDS ANTERIOR


$email     = $_POST['email'];
$perfil    = $_POST['perfil'];
$region    = $_POST['region'];
$estado    = $_POST['estado'];
$ciudad    = $_POST['ciudad'];
if (isset($_POST['check'])) {
	$check     =   'S';
} else {
	$check     =   'F';
}

$r_cliente = $_POST['r_cliente'];
$r_rol     = $_POST['r_rol'];

$admin_kanban = 'F';
if (isset($_POST['admin_kanban'])) {
	$admin_kanban = $_POST['admin_kanban'];
}

$admin_rrhh = 'F';
if (isset($_POST['admin_rrhh'])) {
	$admin_rrhh = $_POST['admin_rrhh'];
}

$as_orden  = $_POST['as_orden'];

if (isset($_POST['status'])) {
	$status    = statusbd($_POST['status']);
} else {
	$status    = 'F';
}

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

if (($cedula == 999999)) {
	$nombre   = "ADMINISTRADOR";
	$apellido = "ADMIN";
	$login = "ADMIN";
	$perfil = "01";
	$status = "T";
}

if (isset($_POST['proced'])) {



	if ($check == 'S') {

		$sql = " SELECT pass, pass_old FROM $tabla WHERE $tabla_id = '$codigo' ";
		$query = $bd->consultar($sql);
		$row02 = $bd->obtener_fila($query, 0);

		$pass_old1  = $row02[0];
		$pass_old2 = $row02[1];

		if ($metodo == "modificar") {
			$pass_ant = $pass_old1;
		}

		if ($metodo <> "agregar") {
			if ($pass_ant == $pass_old1) {

				if (($password == $pass_old1) or ($password == $pass_old2)) {

					echo '<script language="javascript"> 
							alert(" El Passwords No puede Ser Igual a los Anterior");
						  </script>';

					$href = "../autentificacion/aut_logout.php";
					$metodo = "error";
				}
			} else {
				echo '<script language="javascript"> 
							alert(" El Passwords Anterior No Coincide ");
						  </script>';

				$href = "../autentificacion/aut_logout.php";
				$metodo = "error";
			}
		}
	}
	$sql    = "$SELECT $proced('$metodo', '$codigo', '$cedula', '$perfil', '$region',  '$estado',  '$ciudad',  
									'$nombre', '$apellido','$login', '$check',
									'$password', '$pass_ant','$email', '$as_orden',
									 '$r_cliente', '$r_rol', '', '',
									'$usuario',  '$status', '$admin_kanban', '$admin_rrhh')";
	$query = $bd->consultar($sql);
}

require_once('../funciones/sc_direccionar.php');
