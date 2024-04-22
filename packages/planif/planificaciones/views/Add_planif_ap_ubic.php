<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$contratacion    = $_POST['codigo'];
$cliente     = $_POST['cliente'];
$usuario     = $_POST['usuario'];
$plan   = new Planificacion;
$ubicaciones  =  $plan->get_planif_ap_ubic($cliente, $contratacion, $usuario);
	echo '<option value="">Seleccione...</option>';
foreach ($ubicaciones as  $datos)
{
	echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
}?>
