<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$plan   = new Planificacion;

$cliente     = $_POST['cliente'];
$fecha     = $_POST['fecha'];
$apertura     = $_POST['apertura'];
$cod_ficha     = $_POST['cod_ficha'];
$result  =  $plan->validar_fecha($fecha, $cliente, $apertura, $cod_ficha);

print_r(json_encode($result));
return json_encode($result);
?>
