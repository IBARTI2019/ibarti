<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../".Leng;
$ubicacion   = $_POST['ubicacion'];
$horario     = $_POST['horario'];
$plan   = new Confirmaciones;
$datos = $plan->planif_sin_verify($ubicacion, $horario);
echo json_encode($datos)
?>