<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$plan   = new Planificacion;
$result = array();
$result  =  $plan->get_actividades();

print_r(json_encode($result));
return json_encode($result);
