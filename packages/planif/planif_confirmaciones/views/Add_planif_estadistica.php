<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$result = array();
$ficha     = $_POST['ficha'];
$cliente     = $_POST['cliente'];
$ubicacion     = $_POST['ubicacion'];
$horario     = $_POST['horario'];
$data  =  $confirmaciones->get_estadistica($ficha, $cliente, $ubicacion, $horario);
$porcentaje_asistenia = ($data["confirm"] * 100) / $data["total"];
$porcentaje_transporte = ($data["in_transport"] * 100) / $data["total"];
echo '<b> Confirmaciones de asistencia: '.$data["confirm"].'/'.$data["total"].' '. $porcentaje_asistenia .'% </b><br> 
<b> Confirmaciones de transporte: '.$data["in_transport"].'/'.$data["total"].' '. $porcentaje_transporte .'% </b>';
