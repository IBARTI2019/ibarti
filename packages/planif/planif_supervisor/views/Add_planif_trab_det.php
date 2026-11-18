<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;

$result = array();

$apertura  = $_POST['codigo'];
$ubic  = $_POST['ubic'];
$ficha  = $_POST['ficha'];
$cargo  = $_POST['cargo'];
$plan  = new Planificacion;
$fechas = $plan->get_fechas_apertura($apertura, $ubic, $cargo);
if ($fechas) {
	$hoy = date('Y-m-d');
	if ($hoy >= $fechas['fecha_inicio'] && $hoy <= $fechas['fecha_fin']) {
		$fechas['fecha_actual'] = $hoy;
	} elseif ($hoy > $fechas['fecha_fin']) {
		$fechas['fecha_actual'] = $fechas['fecha_fin'];
	} else {
		$fechas['fecha_actual'] = $fechas['fecha_inicio'];
	}
}
$trab  = $plan->get_planif_trab_det(null, null, $ficha, $fechas);
$result["data"] = $trab;
$result["fechas"] = $fechas;
print_r(json_encode($result));
return json_encode($result);
