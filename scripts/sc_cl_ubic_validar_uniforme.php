<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$codigo        = $_POST['codigo']; 
$cod_producto        = $_POST['cod_producto']; 

     $sql    = "SELECT COUNT(clientes_ub_uniforme.cod_producto) FROM clientes_ub_uniforme
                 WHERE clientes_ub_uniforme.cod_cl_ubicacion = '$codigo'
                 AND clientes_ub_uniforme.cod_producto = '$cod_producto'
                 ";						  
	 $query = $bd->consultar($sql);	 
	 $datos=$bd->obtener_fila($query,0);	
	echo $datos[0];
	 	 
?>