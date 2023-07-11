<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../bd/class_mysqli.php");
$bd = new DataBase();
$codigo    = $_POST['codigo'];
$tb    = $_POST['tb'];

$sql   = "SELECT codigo AS cantidad
                 FROM $tb
                WHERE codigo = '$codigo'";

$query = $bd->consultar($sql);

if ($bd->num_fila($query) == 0) {
	//	echo $mensaje = "Valor Valido";	  
} else {
	echo $mensaje = "Ya existe este Valor: ($codigo)";
}
