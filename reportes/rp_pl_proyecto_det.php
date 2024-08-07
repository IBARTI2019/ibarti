<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 5309;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bd);
require_once("../" . Leng);
$bd = new DataBase();

$reporte         = $_POST['reporte'];
$archivo         = "rp_pl_proyectos_" . $fecha . "";
$titulo          = "PROYECTOS \n";

if (isset($reporte)) {

	$area    = $_POST['area'];
	$proyecto    = $_POST['proyecto'];
	$status    = $_POST['status'];
	$actividad  = $_POST['actividad'];
	$cargo  = $_POST['cargo'];

	$where = " WHERE planif_proyecto.codigo = planif_proyecto.codigo ";

	if ($area != "TODOS") {
		$where  .= " AND area_proyecto.codigo = '$area' ";
	}

	if ($proyecto != "TODOS") {
		$where  .= " AND planif_proyecto.codigo = '$proyecto' ";
	}

	if ($status != "TODOS") {
		$where  .= " AND planif_proyecto.status = '$status' ";
	}

	if ($cargo != "TODOS") {
		$where  .= " AND planif_proyecto_cargos.cod_cargo = '$cargo' ";
	}

	if ($actividad != "TODOS") {
		$where   .= " AND planif_actividad.codigo = '$actividad' ";
	}

	$sql = "SELECT DISTINCT
	area_proyecto.descripcion area,
	planif_proyecto.codigo,
	planif_proyecto.descripcion,
	planif_proyecto.abrev,
	planif_actividad.codigo cod_actividad,
	planif_actividad.descripcion actividad,
	Valores(planif_proyecto.`status`) AS `status`
	FROM
	planif_proyecto
	LEFT JOIN planif_actividad ON planif_actividad.cod_proyecto = planif_proyecto.codigo AND planif_actividad.status = 'T'
	LEFT JOIN planif_proyecto_cargos ON planif_proyecto_cargos.cod_proyecto = planif_proyecto.codigo
	LEFT JOIN area_proyecto ON planif_proyecto.cod_area = area_proyecto.codigo
	$where
	ORDER BY planif_proyecto.codigo";


	if ($reporte == 'excel') {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "		<tr >
			<th >Código</th>
			<th >Area</th>
			<th >Proyecto</th>
			<th >Abreviatura</th>
			<th >Actividades</th>
			<th >Cargos</th>
			<th >Minutos</th>
			<th >Activo</th>
		</tr>";

		while ($datos = $bd->obtener_fila($query01, 0)) {
			$sql_actividades = "SELECT
				planif_actividad.descripcion
			FROM
				planif_actividad
			WHERE
				cod_proyecto = " . $datos["codigo"] . " AND `status` = 'T'";
			$nro_actividades = 0;
			$query_actividades = $bd->consultar($sql_actividades);
			while ($actividad = $bd->obtener_fila($query_actividades, 0)) {
				$nro_actividades += 1;
			}
			echo '<tr>
			<td class="texto">' . $datos["codigo"] . '</td>
			<td class="texto">' . $datos["area"] . '</td>
			<td class="texto">' . $datos["descripcion"] . '</td>
			<td class="texto">' . $datos["abrev"] . '</td>
			<td class="texto">' . $datos["actividad"] . '</td>
			<td class="texto">';
			$sql_cargos = "SELECT
				planif_proyecto_cargos.cod_cargo,
				cargos.descripcion
			FROM
				planif_proyecto_cargos, cargos
			WHERE planif_proyecto_cargos.cod_cargo = cargos.codigo  
			AND cod_proyecto = " . $datos["codigo"];
			$query_cargos = $bd->consultar($sql_cargos);
			while ($cargo = $bd->obtener_fila($query_cargos, 0)) {
				echo $cargo["descripcion"] . ",";
			}
			echo '</td><td class="texto">';
			if ($datos["cod_actividad"]) {
				$sql_minutos = "SELECT
					SUM(minutos) minutos
				FROM
					planif_actividad	
				WHERE cod_proyecto = " . $datos["codigo"] . " AND codigo = " . $datos["cod_actividad"] . " AND `status` = 'T'";
				$query_minutos = $bd->consultar($sql_minutos);
				$minutos = $bd->obtener_fila($query_minutos, 0);
				echo $minutos["minutos"];
			} else {
				echo 0;
			}
			echo "</td>";
			echo '<td class="texto">' . $datos["status"] . '</td></tr>';
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
		<th class='etiqueta'>Código</th>
		<th class='etiqueta'>Area</th>
		<th class='etiqueta'>Proyecto</th>
		<th class='etiqueta'>Abreviatura</th>
		<th class='etiqueta'>Actividad</th>
		<th class='etiqueta'>Cargos</th>
		<th class='etiqueta'>Minutos</th>
		<th class='etiqueta'>Activo</th>
		</tr>";

		$f = 0;
		while ($datos = $bd->obtener_fila($query, 0)) {
			$sql_actividades = "SELECT
				planif_actividad.descripcion
			FROM
				planif_actividad
			WHERE
				cod_proyecto = " . $datos["codigo"] . " AND `status` = 'T'";
			$nro_actividades = 0;
			$query_actividades = $bd->consultar($sql_actividades);
			while ($actividad = $bd->obtener_fila($query_actividades, 0)) {
				$nro_actividades += 1;
			}
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			echo '
			<td class="texto">' . $datos["codigo"] . '</td>
			<td class="texto">' . $datos["area"] . '</td>
			<td class="texto">' . $datos["descripcion"] . '</td>
			<td class="texto">' . $datos["abrev"] . '</td>
			<td class="texto">' . $datos["actividad"] . '</td>
			<td class="texto">';
			$sql_cargos = "SELECT
				planif_proyecto_cargos.cod_cargo,
				cargos.descripcion
			FROM
				planif_proyecto_cargos, cargos
			WHERE planif_proyecto_cargos.cod_cargo = cargos.codigo  
			AND cod_proyecto = " . $datos["codigo"];
			$query_cargos = $bd->consultar($sql_cargos);
			while ($cargo = $bd->obtener_fila($query_cargos, 0)) {
				echo $cargo["descripcion"] . "</br>,";
			}
			echo '</td><td class="texto">';
			if ($datos["cod_actividad"]) {
				$sql_minutos = "SELECT
					SUM(minutos) minutos
				FROM
					planif_actividad	
				WHERE cod_proyecto = " . $datos["codigo"] . " AND codigo = " . $datos["cod_actividad"] . " AND `status` = 'T'";
				$query_minutos = $bd->consultar($sql_minutos);
				$minutos = $bd->obtener_fila($query_minutos, 0);
				echo $minutos["minutos"];
			} else {
				echo 0;
			}
			echo "</td>";
			echo '<td class="texto">' . $datos["status"] . '</td></tr>';
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
