<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$result = array();
$usuario  = $_POST['usuario'];
$plan  = new Planificacion;
$trab  = $plan->get_planif_trab_det($usuario);
$result["data"] = $trab;
print_r(json_encode($result));
return json_encode($result);
