<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;
$cliente   = $_POST['cliente'];
$apertura   = $_POST['apertura'];
$plan   = new Planificacion;
$datos_ap = $plan->get_dias_planif_apertura($cliente, $apertura);

echo json_encode($datos_ap);

?>
