<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$region    = $_POST['region'];
$cargo    = $_POST['cargo'];
$cliente     = $_POST['cliente'];
$plan   = new Planificacion;
$ubicaciones  =  $plan->get_planif_ap_ubic($cliente, $region, $cargo);
echo '<option value="">Seleccione..</option>';
foreach ($ubicaciones as  $datos) {
	echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
}
