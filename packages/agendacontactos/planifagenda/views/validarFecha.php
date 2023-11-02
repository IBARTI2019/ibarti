<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$plan   = new Planificacion;

$cliente     = $_POST['cliente'];
$fecha     = $_POST['fecha'];
$result  =  $plan->validar_fecha($fecha, $cliente);

print_r(json_encode($result));
return json_encode($result);
