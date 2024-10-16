<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$ubic     = $_POST['ubic'];
$cargo     = $_POST['cargo'];
$planif      = new Planificacion;
$apertura = $planif-> get_fcactividades($ubic, $cargo);

echo '<option value="">Seleccione..</option>';
  foreach ($apertura as  $datos)
  {
    echo '<option value="'.$datos["codigo"].'">'.$datos["descripcion"].' - '.$datos["fec_us_ing"].'</option>
    ';
  }
