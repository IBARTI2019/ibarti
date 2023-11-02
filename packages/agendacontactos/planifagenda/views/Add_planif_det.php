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
	$result["data"]  = $plan->get_cliente();
} elseif (isset($_POST['codigo'])) {
	
	$cliente  = $_POST['cliente'];
	$apertura  = $_POST['codigo'];
	$region  = $_POST['region'];
	$cargo  = $_POST['cargo'];
	$ubicacion  = $_POST['ubic'];
	$usuario  = $_POST['usuario'];
    
	$trab  = $plan->get_planif_det();

	$mod  = $plan->get_ultima_mod($apertura);

	$fechas = $plan->get_fechas_apertura($apertura, $ubicacion, $cargo);
    
	$supervisores = $plan->get_cliente_c($cliente);
	$result['html'] = '</br></br><div align="center" class="etiqueta_title">Detalle Agenda</div>
	<div align="right"><span class="etiqueta">Ultima Modificación: </span> ' . $mod["fecha"] . ' (' . $mod["us_mod"] . ')</div>
	<div align="right"><span class="etiqueta">Nro. de clientes sin Agenda: <h6 id="cantidad_sin_planif"></h6></div>
	<div id="wrap">

	<div id="supervisor-wrap" class="scroll">
	<div id="external-events">
	<input type="text"id="filtro" value="" placeholder="Filtro" style="width:200px"/>
	<h4>Clientes:</h4>

	<div id="external-events-list">';

	foreach ($supervisores as  $datos) {
		$result['html'] .= '<div class="fc-event fc-h-event fc-daygrid-event " cod_ficha="' . $datos[0] . '" cedula="' . $datos[0] . '">
		<div class="fc-event-main">' . $datos[0] . ' - ' . $datos[1] . '<br>' . $datos[2] . '<br>' . $datos[3] . '</div>
		</div>';
	}


	$result['html'] .= '</div></div></div><div id="calendar-wrap"> <div id="calendar"></div></div></div>';

	$result['html'] .= '<script language="JavaScript" type="text/javascript"> new Autocomplete("filtro", function() { filtrar_supervisores(this.value); }); </script>';

	$result["fechas"] = $fechas;
    $result["data"] = $trab;
	
}

print_r(json_encode($result));
return json_encode($result);
