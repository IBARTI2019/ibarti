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
$porcentaje_asistenia = round((($data["confirm"] * 100) / $data["total"]), 2);
$porcentaje_transporte = round((($data["in_transport"] * 100) / $data["total"]), 2);
echo '<b> Asistencia: '.$data["confirm"].'/'.$data["total"].' '. $porcentaje_asistenia .'% </b><br> 
<b> Transporte: '.$data["in_transport"].'/'.$data["total"].' '. $porcentaje_transporte .'% </b>';
