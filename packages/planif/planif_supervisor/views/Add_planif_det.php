<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;
require_once "../../../../funciones/funciones.php";
$result = array();

$plan  = new Planificacion;
if (isset($_POST['fecha_desde'])) {
	$ubicacion  = $_POST['ubicacion'];
	$cliente  = $_POST['cliente'];
	$fecha_desde  = conversion($_POST['fecha_desde']);
	$fecha_hasta  = conversion($_POST['fecha_hasta']);
	$ficha  = $_POST['ficha'];
	$result["fechas"] = array();
	$result["fechas"]["fecha_inicio"] = $fecha_desde;
	$result["fechas"]["fecha_fin"] = $fecha_hasta;
	$result["data"]  = $plan->get_planif_det_rp($fecha_desde, $fecha_hasta, $cliente, $ubicacion, $ficha);
} elseif (isset($_POST['codigo'])) {
	$apertura  = $_POST['codigo'];
	$region  = $_POST['region'];
	$cargo  = $_POST['cargo'];
	$ubicacion  = $_POST['ubic'];
	$usuario  = $_POST['usuario'];

	$trab  = $plan->get_planif_det($apertura, $ubicacion, $cargo);

	$mod  = $plan->get_ultima_mod($apertura);

	$fechas = $plan->get_fechas_apertura($apertura, $ubicacion, $cargo);
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

	$supervisores = $plan->get_supervisores($ubicacion, null, $usuario, $cargo);
	$result['html'] = '</br></br><div align="center" class="etiqueta_title">Planificación Detalle</div>
	<div align="right"><span class="etiqueta">Ultima Modificación: </span> ' . $mod["fecha"] . ' (' . $mod["us_mod"] . ')</div>
	<div align="right"><span class="etiqueta">Nro. de personas sin planificar: <h6 id="cantidad_sin_planif"></h6></div>
	<div id="wrap">

	<div id="supervisor-wrap" class="scroll">
	<div id="external-events">
	<input type="text"id="filtro" value="" placeholder="Filtro" style="width:200px"/>
	<h4>Supervisores:</h4>

	<div id="external-events-list">';

	foreach ($supervisores as  $datos) {
		$result['html'] .= '<div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" cod_ficha="' . $datos[0] . '" cedula="' . $datos[5] . '">
		<div class="fc-event-main">' . $datos[0] . ' - ' . $datos[5] . '<br>' . $datos[1] . '<br>' . $datos[6] . '</div>
		</div>';
	}


	$result['html'] .= '</div></div></div><div id="calendar-wrap"> <div id="calendar"></div></div></div>';

	$result['html'] .= '<script language="JavaScript" type="text/javascript"> new Autocomplete("filtro", function() { filtrar_supervisores(this.value); }); </script>';

	$result["fechas"] = $fechas;

	$result["data"] = $trab;
}
print_r(json_encode($result));
return json_encode($result);
