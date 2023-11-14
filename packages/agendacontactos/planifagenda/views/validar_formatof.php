<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$plan   = new Planificacion;

$cliente     = $_POST['cliente'];
$ubicacion    = $_POST['ubicacion'];
$result  =  $plan->validar_formato($cliente, $ubicacion);

print_r(json_encode($result));
return json_encode($result);
