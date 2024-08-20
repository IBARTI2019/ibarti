<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 5307;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

if (($_POST['fecha_desde'] == "" or $_POST['fecha_hasta'] == "")) {
	exit;
}

$reporte         = $_POST['reporte'];
$archivo         = "rp_pl_personal_" . $fecha . "";
$titulo          = "PLANIFICACION DE PERSONAL \n";

if(isset($reporte)){
$region     = $_POST['region'];
$estado     = $_POST['estado'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$proyecto    = $_POST['proyecto'];
$area_proyecto    = $_POST['area_proyecto'];
$actividad  = $_POST['actividad'];
$trabajador = $_POST['trabajador'];

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$where = " WHERE p.fecha_inicio BETWEEN \"$fecha_D\" AND ADDDATE(\"$fecha_H\", 1)
AND p.codigo = pd.cod_planif_cl_trab
AND p.cod_ficha = f.cod_ficha
AND p.cod_cliente = cl.codigo
AND p.cod_ubicacion = cu.codigo
AND pd.cod_proyecto = pp.codigo 
AND pd.cod_actividad = pa.codigo ";


if($region != "TODOS"){
	$where  .= " AND f.cod_region = '$region' ";
}

	if ($estado != "TODOS") {
		$where  .= " AND f.cod_estado = '$estado' ";
	}

	if ($trabajador != NULL) {
		$where   .= " AND  f.cod_ficha = '$trabajador' ";
	}

	if ($cliente  != "TODOS") {
		$where   .= " AND p.cod_cliente = '$cliente' ";
	}

	if ($ubicacion != "TODOS") {
		$where   .= " AND p.cod_ubicacion = '$ubicacion' ";
	}

	if ($area_proyecto != "TODOS" && $area_proyecto != NULL) {
		$where   .= " AND pp.cod_area = '$area_proyecto' ";
	}

	if ($proyecto != "TODOS" && $proyecto != NULL) {
		$where   .= " AND pd.cod_proyecto = '$proyecto' ";
	}

	if ($actividad != "TODOS" && $actividad != NULL) {
		$where   .= " AND pd.cod_actividad = '$actividad' ";
	}

$sql = "SELECT pd.codigo, DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') fecha, p.cod_ficha, CONCAT(f.apellidos, ' ', f.nombres) ap_nombre, 
p.cod_cliente, cl.nombre cliente, p.cod_ubicacion, cu.descripcion ubicacion, 
pd.cod_proyecto, pp.descripcion proyecto, pd.cod_actividad, pa.descripcion actividad,
TIME(pd.fecha_inicio) hora_inicio, TIME(pd.fecha_fin) hora_fin,
pa.minutos, IF(pd.realizado='T','SI', 'NO') realizado
FROM planif_clientes_superv_trab p, planif_clientes_superv_trab_det pd, clientes cl, clientes_ubicacion cu, ficha f,
planif_proyecto pp, planif_actividad pa
$where
ORDER BY 1,2,3,13,5,7,11 ASC";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "<tr><th> Código </th><th> Fecha </th><th> ".$leng['ficha']." </th><th> ".$leng['trabajador']." </th>
		<th> Cod. Cliente </th><th> ".$leng['cliente']." </th><th> Cod. Ubicación </th><th> ".$leng['ubicacion']." </th>
		<th> Cod. Proyecto </th><th> Proyecto </th><th> Cod. Actividad </th><th> Actividad </th><th> Hora Inicio </th>
		<th> Hora Fin </th><th> Minutos dedicados </th><th> Realizado </th>
		</tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td>
			<td>".$row01[12]."</td><td>".$row01[13]."</td><td>".$row01[14]."</td><td>".$row01[15]."</td></tr>";
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
		<th class='etiqueta'>Fecha</th>
		<th  class='etiqueta'>" . $leng['ficha'] . "</th>
		<th  class='etiqueta'>" . $leng['trabajador'] . "</th>
		<th  class='etiqueta'>" . $leng['cliente'] . "</th>
		<th  class='etiqueta'>" . $leng['ubicacion'] . "</th>
		<th  class='etiqueta'>Proyecto </th>
		<th  class='etiqueta'>Actividad </th>
		<th  class='etiqueta'>Hora Inicio </th>
		<th  class='etiqueta'>Hora Fin </th>
		<th  class='etiqueta'>Minutos</th>
		<th  class='etiqueta'>Realizado </th>
		</tr>";

		$f = 0;
		while ($datos = $bd->obtener_fila($query)) {
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			echo   "		<td  >" . $datos["fecha"] . "</td>
			<td  >" . $datos["cod_ficha"] . "</td>
			<td  >" . $datos["ap_nombre"] . "</td>
			<td  >" . $datos["cliente"] . "</td>
			<td  >" . $datos["ubicacion"] . "</td>
			<td  >" . $datos["proyecto"] . "</td>
			<td  >" . $datos["actividad"] . "</td>
			<td  >" . $datos["hora_inicio"] . "</td>
			<td  >" . $datos["hora_fin"] . "</td>
			<td  >" . $datos["minutos"] . "</td>
			<td  >" . $datos["realizado"] . "</td></tr>";

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
