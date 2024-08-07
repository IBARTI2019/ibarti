<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$apertura   = $_POST['codigo'];
$ubicacion  = $_POST['ubicacion'];
$planif     = new Planificacion;

$pl = $planif->generar_planif($apertura, $ubicacion);

?>
