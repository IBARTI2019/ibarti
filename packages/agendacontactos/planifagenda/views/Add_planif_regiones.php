<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$cliente     = $_POST['cliente'];
$planif      = new Planificacion;
$regiones = $planif->get_regiones($cliente);

echo '<option value="">Seleccione..</option>';
foreach ($regiones as  $datos) {
  echo '<option value="' . $datos["codigo"] . '">' . $datos["descripcion"] . '</option>
    ';
}
