<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$ubic     = $_POST['ubic'];
$cliente     = $_POST['cliente'];
$usuario     = $_POST['usuario'];
$planif      = new Planificacion;
$cargos = $planif->get_cargos($cliente, $ubic, $usuario);

echo '<option value="">Seleccione..</option>';
foreach ($cargos as  $datos) {
  echo '<option value="' . $datos["codigo"] . '">' . $datos["descripcion"] . '</option>
    ';
}
