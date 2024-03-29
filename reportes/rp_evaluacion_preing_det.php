<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 561;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

if (($_POST['fecha_desde'] == "" or $_POST['fecha_hasta'] == "")) {
	exit;
}

$fecha_D         = conversion($_POST['fecha_desde']);
$fecha_H         = conversion($_POST['fecha_hasta']);

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$tipo       = $_POST['tipo'];
$clasif     = $_POST['clasif'];
$cedula    	= $_POST['stdID'];
$reporte         = $_POST['reporte'];
$detalle = $_POST['detalle'];
$archivo         = "rp_nov_check_list_" . $_POST['fecha_desde'] . "";
$titulo          = " REPORTE DE EVALUACION CHECKLIST FECHA: " . $fecha_D . " HASTA: " . $fecha_H . "\n";

if (isset($reporte)) {

	$where = "";

	if ($clasif != "TODOS" && $clasif != "") {
		$where .= " AND nov_clasif.codigo = '$clasif' ";
	}

	if ($cedula != "TODOS" && $cedula != "") {
		$where .= " AND nov_check_list_trab.cedula = '$cedula' ";
	}

	if ($tipo != "TODOS" && $tipo != "") {
		$where .= " AND nov_tipo.codigo  = '$tipo' ";
	}


	// QUERY A MOSTRAR //

	$sql = "
	SELECT
	nov_check_list_trab.codigo,
	nov_check_list_trab.fec_us_mod,
	nov_clasif.descripcion,
	nov_tipo.descripcion,
	preingreso.cedula doc,
	concat(
		preingreso.nombres,
		' ',
		preingreso.apellidos
	) trab,
	ficha.cod_ficha,
	concat(
		ficha.nombres,
		' ',
		ficha.apellidos
	) entrevistador";
	$sql .= ($detalle != "T" || $reporte=="pdf") ? "
	,SUM(
		nov_check_list_trab_det.valor
	),
	SUM(
		nov_check_list_trab_det.valor_max
	)" : ',
	nov_check_list_trab_det.observacion,
		nov_valores.abrev,
		nov_check_list_trab_det.valor
	,
		nov_check_list_trab_det.valor_max,
		nov_valores.descripcion,
		novedades.descripcion
	';
	$sql .= "
FROM
	preingreso,
	nov_check_list_trab,
	nov_check_list_trab_det,
	nov_tipo,
	nov_clasif,
	ficha,
	nov_valores,
	novedades
	WHERE nov_check_list_trab.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\" AND 
	nov_check_list_trab.cedula = preingreso.cedula
	AND nov_check_list_trab.cod_nov_clasif = nov_clasif.codigo
	AND nov_check_list_trab.cod_nov_tipo = nov_tipo.codigo
	AND nov_check_list_trab.codigo = nov_check_list_trab_det.cod_check_list 
	AND nov_check_list_trab.cod_ficha = ficha.cod_ficha
	AND nov_check_list_trab_det.cod_novedades = novedades.codigo
	AND nov_valores.codigo = nov_check_list_trab_det.cod_valor
$where";
	$sql .= ($detalle != "T" || $reporte=="pdf") ?
		"GROUP BY
	nov_check_list_trab.codigo
	" : '';


	if ($reporte == 'excel') {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");


		$mostrar = '
			<table width="100%" align="center">
				<tr class="fondo00">
					<th  class="etiqueta">Codigo</th>
					<th  class="etiqueta">Fecha</th>
					<th  class="etiqueta">Clasificacion</th>
					<th  class="etiqueta">Evaluacion</th>
					<th  class="etiqueta">Documento</th>
					<th  class="etiqueta">Trabajador</th>
					<th  class="etiqueta">Ficha Entrevistador</th>
					<th  class="etiqueta">Entrevistador</th>';
		$mostrar .= $detalle != "T" ? '
					<th  class="etiqueta">Valor</th>
					<th  class="etiqueta">Valor MAX</th>
			' : '
			<th  class="etiqueta">pregunta</th>
			<th  class="etiqueta">observacion</th>
			<th  class="etiqueta">abr</th>
			<th  class="etiqueta">respuesta</th>
			<th  class="etiqueta">Valor</th>
					<th  class="etiqueta">Valor MAX</th>';
		$mostrar .= "</tr>";
		$query01  = $bd->consultar($sql);

		//echo '<tr><td>'.json_encode($query01).'</td></tr>';
		while ($datos = $bd->obtener_fila($query01, 0)) {
			$mostrar .= '<tr>
					<td>' . $datos[0] . '</td>
					<td>' . $datos[1] . '</td>
					<td>' . $datos[2] . '</td>
					<td>' . $datos[3] . '</td>
					<td>' . $datos[4] . '</td>
					<td>' . strtoupper($datos[5]) . '</td>
					<td>' . $datos[6] . '</td>
					<td>' . strtoupper($datos[7]) . '</td>
					';
			$mostrar .= $detalle != "T" ? '
					<td>' . $datos[8] . '</td>
					<td>' . $datos[9] . '</td>
					</tr>' : '<td>' . $datos[13] . '</td>
					<td>' . $datos[8] . '</td>
					<td>' . $datos[9] . '</td>
					<td>' . $datos[12] . '</td>
					<td>' . $datos[10] . '</td>
					<td>' . $datos[11] . '</td>
					</tr>';
		};
		echo $mostrar;
	}

	if ($reporte == 'pdf') {

		require_once('../' . ConfigDomPdf);
		$dompdf = new DOMPDF();

		$query01  = $bd->consultar($sql);


		ob_start();

		require('../' . PlantillaDOM . '/header_ibarti_2.php');
		include('../' . pagDomPdf . '/paginacion_ibarti.php');

		$mostrar3 = '<br><div>
        <table>
		<tbody>
            <tr style="background-color: #4CAF50;">
					<th width="10%">Fecha</th>
					<th width="20%">Clasificacion</th>
					<th width="20%">Evaluacion</th>
					<th width="10%">Documento</th>
					<th width="20%">Trabajador</th>
		';
		$mostrar3 .= ($detalle != "T" || $reporte=="pdf") ? '
					<th width="10%">Valor</th>
					<th width="10%">Valor MAX</th>
			</tr>
			' : '
				<th width="7%">Valor</th>
					<th width="7%">Valor MAX</th>
					</tr>';

		$f = 0;
		while ($datos = $bd->obtener_num($query01)) {
			if ($f % 2 == 0) {
				$mostrar3 .= "<tr>";
			} else {
				$mostrar3 .= "<tr class='class= odd_row'>";
			}
			$mostrar3 .= "
				<td>$datos[1]</td>
				<td>$datos[2]</td>
				<td>$datos[3]</td>
				<td>$datos[4]</td>
				<td>" . strtoupper($datos[5]) . "</td>
				";
			$mostrar3 .= ($detalle != "T" || $reporte=="pdf") ? "
				<td>$datos[8]</td>
				<td>$datos[9]</td>
				</tr>" : "
				
				<td>$datos[10]</td>
				<td>$datos[11]</td>
				</tr>";

			$f++;
		}
		$mostrar3 .= "</tbody>
        </table>
		</div>
		</body>
		</html>";
		echo $mostrar3;
		$dompdf->load_html(ob_get_clean(), 'UTF-8');
		$dompdf->set_paper('letter', 'landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}
}
