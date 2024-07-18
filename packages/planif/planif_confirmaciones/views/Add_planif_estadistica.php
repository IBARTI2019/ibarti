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

echo '<b> Confirmaciones de asistencia: '.$data["confirm"].'/'.$data["total"].'</b><br> 
<b> Confirmaciones de transporte: '.$data["in_transport"].'/'.$data["total"].'</b>';
