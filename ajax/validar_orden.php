<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bdI);

$bd = new DataBase();
$codigo    = $_POST['codigo'];

$tb    = $_POST['tb'];

$sql   = "SELECT orden AS cantidad
                 FROM $tb
                WHERE orden = '$codigo'";
$query = $bd->consultar($sql);

        if ($bd->num_fila($query) == 0) {
	//	echo $mensaje = "Valor Valido";	  
        } else {
	        echo $mensaje = "Ya existe este Valor: ($codigo)";
            }
?>