<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$cliente     = $_POST['cliente'];
$plan   = new Planificacion;
$ubicaciones  =  $plan->get_planif_ap_ubic($cliente);
//echo '<option value="">Seleccione..</option>';
foreach ($ubicaciones as  $datos) {
	echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
}
