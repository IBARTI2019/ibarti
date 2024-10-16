<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$ubic     = $_POST['ubic'];
$cliente     = $_POST['cliente'];
$planif      = new Planificacion;
$cargos = $planif->get_fcontactos($cliente, $ubic);

echo '<option value="">Seleccione..</option>';
foreach ($cargos as  $datos) {
  echo '<option value="' . $datos["codigo"] . '">' . $datos["descripcion"] . '</option>
    ';
}
