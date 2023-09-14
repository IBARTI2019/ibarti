
<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 736;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

$cliente         = $_POST['cliente'];
$region          = $_POST['region'];
$estatu          = $_POST['estatu'];
$reporte         = $_POST['reporte'];

$archivo         = "rp_cs_precliente_" . $fecha . "";
$titulo          = "  REPORTE DE PRE-CLIENTES \n";

if (isset($reporte)) {
       

	$where = "";


	if ($cliente != "TODOS") {
		$where .= " preclientes.codigo = '$cliente' ";
	}

	if ($region != "TODOS") {
		$where .= " AND regiones.codigo = '$region' ";
	}

	if ($estatu != "TODOS"){
		$where .= " AND preclientes.status = '$estatu'";
		
	}
	
	// QUERY A MOSTRAR //
	$sql = " SELECT regiones.descripcion AS region,preclientes.nombre AS cliente,preclientes.telefono AS telefono,preclientes.fax AS fax,
	clientes_tipos.descripcion AS cliente_tipo,
	preclientes.rif, IF(preclientes.latitud, 'SI', 'NO') geolocalizacion_cliente,
	preclientes.latitud latitud_cliente,
	preclientes.longitud longitud_cliente,preclientes.direccion,preclientes.dir_entrega,preclientes.email,preclientes.status
	from preclientes
	inner JOIN regiones ON preclientes.cod_region = regiones.codigo
	inner join clientes_tipos on preclientes.cod_cl_tipo=clientes_tipos.codigo
	$where
	ORDER BY 1 ASC";
    
	if ($reporte == 'excel' ) {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "<tr><th> " . $leng['region'] . " </th>
		<th> Nombre </th>
		<th> Telefono</th>
		<th> Fax</th>
		<th> Tipo </th>
		<th> Rif </th><th> " . $leng['rif'] . " </th>
		<th> Latitud</th>
		<th> Longitud</th>
		<th> Direccion</th>
		<th> Direccion de Entrega</th>
		<th> Email</th>
		<th> Estatu</th>
		</tr>";
		while ($row01 = $bd->obtener_num($query01)) {
			echo "<tr><td > " . $row01[0] . " </td>
			<td>" . $row01[1] . "</td>
			<td>" . $row01[2] . "</td>
			<td>" . $row01[3] . "</td>
			<td>" . $row01[4] . "</td>
			<td>" . $row01[5] . "</td>
			<td>" . $row01[6] . "</td>
			<td>" . $row01[7] . "</td>
			<td>" . $row01[8] . "</td>
			<td>" . $row01[9] . "</td>
			<td>" . $row01[10] . "</td>
			<td>" . $row01[11] . "</td>
			<td>" . $row01[12] . "</td>
			</tr>";
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
		<th width='15%'>Region</th>
		<th width='15%'>Nombre</th>
		<th width='20%'>Telefono</th>
		<th width='20%'>Fax</th>
		<th width='10%'>Tipo</th>
		<th width='10%'>Rif</th>
		<th width='10%'>Nit</th>
		<th width='35%'>Latitud</th>
		<th width='20%'>Longitud</th>
		<th width='20%'>Direccion</th>
		<th width='20%'>Direccion de Entrega</th>
		<th width='20%'>Email</th>
		<th width='20%'>Estatu</th>
		</tr>";

		$f = 0;
		while ($row = $bd->obtener_num($query)) {
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='15%'>" . $row[0] . "</td>
			<td width='20%'>" . $row[1] . "</td>
			<td width='20%'>" . $row[2] . "</td>
			<td width='10%'>" . $row[3] . "</td>
			<td width='10%'>" . $row[4] . "</td>
			<td width='35%'>" . $row[5] . "</td>
			<td width='35%'>" . $row[6] . "</td>
			<td width='35%'>" . $row[7] . "</td>
			<td width='35%'>" . $row[8] . "</td>
			<td width='35%'>" . $row[9] . "</td>
			<td width='35%'>" . $row[10] . "</td>	
			<td width='35%'>" . $row[11] . "</td>
			<td width='35%'>" . $row[12] . "</td>
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
