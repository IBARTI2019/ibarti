<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 574;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();
$bd2 = new DataBase();

$cliente         = $_POST['cliente'];
$ubicacion         = $_POST['ubicacion'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ciudad          = $_POST['ciudad'];
$puesto			 = $_POST['puesto'];
$estatu          = $_POST['estatu'];
$reporte         = $_POST['reporte'];

$archivo         = "rp_cs_cliente_" . $fecha . "";
$titulo          = "  REPORTE CLIENTES \n";

if (isset($reporte)) {

	$where = "WHERE clientes_ubicacion.cod_cliente = clientes.codigo
	AND clientes.cod_cl_tipo = clientes_tipos.codigo
	AND clientes_ubicacion.cod_region = regiones.codigo
	AND clientes_ubicacion.cod_estado = estados.codigo
	AND clientes_ubicacion.cod_ciudad = ciudades.codigo";


	if ($cliente != "TODOS") {
		$where .= " AND clientes.codigo = '$cliente' ";
	}

	if ($ubicacion != "TODOS") {
		$where .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}
	if ($region != "TODOS") {
		$where .= " AND regiones.codigo = '$region' ";
	}

	if ($estado != "TODOS") {
		$where .= " AND estados.codigo = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if ($ciudad != "TODOS") {
		$where  .= " AND ciudades.codigo = '$ciudad' ";
	}

	if ($puesto != "TODOS") {
		$where .= " AND clientes_ub_puesto.codigo = '$puesto'";
	}

	if ($estatu != "TODOS"){
		$where .= " AND clientes.status = '$estatu'";
		if($estatu == 'T'){
			$where .= " AND clientes_ubicacion.status = 'T'";
		}
	}
	
	// QUERY A MOSTRAR //
	$sql = " SELECT regiones.descripcion AS region, estados.descripcion AS estado,
	ciudades.descripcion AS ciudad, clientes.nombre AS cliente,clientes.status,
	clientes_tipos.descripcion AS cliente_tipo,
	clientes.rif, clientes_ubicacion.contacto,
	IF(clientes.latitud, 'SI', 'NO') geolocalizacion_cliente,
	clientes.latitud latitud_cliente,
	clientes.longitud longitud_cliente,
	clientes_ubicacion.descripcion AS ubicacion,
	clientes_ubicacion.telefono, clientes_ubicacion.email,
	clientes_ubicacion.direccion, clientes_ubicacion.status,
	clientes_ub_puesto.nombre as cliente_puesto_nombre,
	clientes_ub_puesto.actividades as cliente_puesto_actividades,
	clientes_ub_puesto.observ as cliente_puesto_observacion,
	IF(clientes_ubicacion.latitud, 'SI', 'NO') geolocalizacion_ubicacion,
	clientes_ubicacion.latitud latitud_ubicacion,
	clientes_ubicacion.longitud longitud_ubicacion,
	clientes_ubicacion.status status_ubic,
	clientes.codigo cod_cliente
	FROM clientes_ubicacion 
	LEFT JOIN clientes_ub_puesto ON clientes_ub_puesto.cod_cl_ubicacion = clientes_ubicacion.codigo, 
	clientes, clientes_tipos, regiones , estados , ciudades
	$where
	ORDER BY 1 ASC";

	if ($reporte == 'excel' ) {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		$index = 0;
		while ($row01 = $bd->obtener_fila($query01)) {
			if ($index == 0){
			echo "<table border=1>";
			echo "<tr><th> " . $leng['region'] . " </th><th> " . $leng['estado'] . " </th><th> CIUDAD && MUNICIPIO </th><th> " . $leng['cliente'] . " </th>
			<th> Tipo </th><th> " . $leng['rif'] . " </th>";
	
			$sql_contacts = "SELECT documento, nombres, cargo, telefono, correo FROM clientes_contactos WHERE cod_cliente = '". $row01["cod_cliente"]  ."'";
			$queryContacts  = $bd2->consultar($sql_contacts);
			$contact_index = 1;
			while ($rowContact = $bd2->obtener_fila($queryContacts)) {
				echo "<th> Documento Contacto ". $contact_index ." </th><th> Nombres Contacto " . $contact_index . " </th><th> Cargo Contacto ". $contact_index . " </th><th> Telefono Contacto " . $contact_index . " </th><th> Correo Contacto " . $contact_index . " </th>";
				$contact_index += 1;
			}
			echo "<th> Contacto Ubicacion</th><th> Geolicalización Cliente </th><th> Latitud Cliente </th><th> Longitud Cliente </th><th> " . $leng['ubicacion'] . " </th>
			<th> Puesto </th><th> Actividades del puesto </th><th> Observacion del puesto </th><th> Teléfono </th><th> " . $leng['correo'] . " </th><th> Dirección</th>
			<th>Estatus</th><th> Geolicalización Ubicación </th><th> Latitud Ubicación </th><th> Longitud Ubicación </th><th> Estatus Ubicación </th></tr>";
			}
			echo "<tr><td > " . $row01["region"] . " </td>
			<td>" . $row01["estado"] . "</td>
			<td>" . $row01["ciudad"] . "</td>
			<td>" . $row01["cliente"] . "</td>
			<td>" . $row01["cliente_tipo"] . "</td>
			<td>" . $row01["rif"] . "</td>";
			$queryContacts  = $bd2->consultar($sql_contacts);
			while ($rowContact = $bd2->obtener_fila($queryContacts)) {
				echo "<td> ". $rowContact["documento"] ." </td><td> " . $rowContact["nombres"]  . " </td><td> " . $rowContact["cargo"]  . " </td><td> " . $rowContact["telefono"]  . " </td><td> " . $rowContact["correo"]  . " </td>";
			}
			echo "<td>" . $row01["contacto"] . "</td>
			<td>" . $row01["geolocalizacion_cliente"] . "</td>
			<td>" . floatval($row01["latitud_cliente"]) . "</td>
			<td>" . floatval($row01["longitud_cliente"]) . "</td>
			<td>" . $row01["ubicacion"] . "</td>
			<td>" . $row01["cliente_puesto_nombre"] . "</td>
			<td>" . $row01["cliente_puesto_actividades"] . "</td>
			<td>" . $row01["cliente_puesto_observacion"] . "</td>
			<td>" . $row01["telefono"] . "</td>
			<td>" . $row01["email"] . "</td>
			<td>" . $row01["direccion"] . "</td>
			<td>" . statuscal($row01["status"]) . "</td>
			<td>" . $row01["geolocalizacion_ubicacion"] . "</td>
			<td>" . floatval($row01["latitud_ubicacion"]) . "</td>
			<td>" . floatval($row01["longitud_ubicacion"]) . "</td>
			<td>" . statuscal($row01["status_ubic"]) . "</td>
			</tr>";
			$index += 1;
		}
		echo "</table>";
	}

	if ($reporte == 'pdf') {

		require_once('../' . ConfigDomPdf);
		$dompdf = new DOMPDF();

		$query  = $bd->consultar($sql);

		ob_start();

		require('../' . PlantillaDOM . '/header_ibarti_2.php');
		include('../' . pagDomPdf . '/paginacion_ibarti.php');

		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='15%'>" . $leng['estado'] . "</th>
		<th width='20%'>" . $leng['cliente'] . "</th>
		<th width='20%'>" . $leng['ubicacion'] . "</th>
		<th width='10%'>Puesto</th>
		<th width='10%'>Teléfono</th>
		<th width='35%'>Dirección</th>
			
		</tr>";

		$f = 0;
		while ($row = $bd->obtener_num($query)) {
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='15%'>" . $row[1] . "</td>
			<td width='20%'>" . $row[3] . "</td>
			<td width='20%'>" . $row[7] . "</td>
			<td width='10%'>" . $row[15] . "</td>
			<td width='10%'>" . $row[11] . "</td>
			<td width='35%'>" . $row[13] . "</td>
						
			</tr>";

			$f++;
		}

		echo "</tbody>
		</table>
		</div>
		</body>
		</html>";

		$dompdf->load_html(ob_get_clean(), 'UTF-8');
		$dompdf->set_paper('letter', 'landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}
}
