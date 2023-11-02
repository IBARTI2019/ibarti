<?php
require "../modelo/planificacion_modelo.php";
require "../../planif_modelo/modelo/planif_modelo.php";
$rotacion    = $_POST['codigo'];

$modelo   = new Planif_modelo;
$rotacion_det = $modelo->get_rotacion_det($rotacion);
$i = 0;
echo '<option value="">Seleccione...</option>';
foreach ($rotacion_det as $datosX){
  echo '<option value="'.$i.'">'.($i+1).' - ('.$datosX["abrev"].')</option>';
  $i++;
  }?>
