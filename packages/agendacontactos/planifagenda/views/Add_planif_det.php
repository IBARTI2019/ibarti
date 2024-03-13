<?php
require "../modelo/planificacion_modelo.php";
require "../../../../" . Leng;
require_once "../../../../funciones/funciones.php";
$result = array();

$plan  = new Planificacion;

if (isset($_POST['usuario'])) {
	
	$usuario  = $_POST['usuario'];
    
	$trab  = $plan->get_planif_det();

	// $mod  = $plan->get_ultima_mod($cliente);

	// $fechas = $plan->get_fechas_apertura($apertura, $ubicacion, $cargo);
    
	$preclientes = $plan->get_preclientes($usuario);
	// $result['html'] = '</br></br><div align="center" class="etiqueta_title">Detalle Agenda</div>
	// <div align="right"><span class="etiqueta">Ultima Modificación: </span> ' . $mod["fecha"] . ' (' . $mod["us_mod"] . ')</div>
	// <div align="right"><span class="etiqueta">Nro. de clientes sin Agenda: <h6 id="cantidad_sin_planif"></h6></div>
	// <div id="wrap">

	$result['html'] = '<div id="wrap">
	<div id="supervisor-wrap" class="scroll">
	<div id="external-events">
	<input type="text"id="filtro" value="" placeholder="Filtro" style="width:200px"/>
	<h4>'.$leng['precliente'].':</h4>

	<div id="external-events-list">';

	foreach ($preclientes as  $datos) {
		$result['html'] .= '<div class="fc-event fc-h-event fc-daygrid-event " codigo="' . $datos[0] . '" cedula="' . $datos[1] . '">
		<div class="fc-event-main">' . $datos[0] . '<br>' . $datos[1] . '<br>' . $datos[2] . '</div>
		</div>';
	}


	$result['html'] .= '</div></div></div><div id="calendar-wrap"> <div id="calendar"></div></div></div>';

	$result['html'] .= '<script language="JavaScript" type="text/javascript"> new Autocomplete("filtro", function() { filtrar_supervisores(this.value); }); </script>';

	// $result["fechas"] = $fechas;
    $result["data"] = $trab;
	
}

print_r(json_encode($result));
return json_encode($result);