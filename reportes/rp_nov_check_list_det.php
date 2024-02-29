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

$clasif          = $_POST['clasif'];
$codigo          = $_POST['codigo'];
$tipo            = $_POST['tipo'];
$agrupacion      = $_POST['agrupacion'];
$cliente         = $_POST['cliente'];
$ubicacion       = $_POST['ubicacion'];
$status          = $_POST['status'];
$detalle         = $_POST['detalle'];

$trabajador     = $_POST['trabajador'];
$reporte         = $_POST['reporte'];

$archivo         = "rp_nov_check_list_" . $_POST['fecha_desde'] . "";
$titulo          = " REPORTE DE NOVEDADES CHECK LIST FECHA: " . $fecha_D . " HASTA: " . $fecha_H . "\n";

if (isset($reporte)) {

	$where = " WHERE nov_check_list.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
                     AND nov_check_list.codigo = nov_check_list_det.cod_check_list
                     AND nov_check_list_det.cod_novedades = novedades.codigo
                     AND nov_check_list.cod_nov_clasif = nov_clasif.codigo
                     AND nov_check_list.cod_nov_tipo = nov_tipo.codigo
                     AND nov_check_list_det.cod_valor = nov_valores.codigo
                    AND nov_check_list.cod_cliente = clientes.codigo
					 AND nov_check_list.cod_ubicacion = clientes_ubicacion.codigo
					 AND nov_check_list.cod_ficha = ficha.cod_ficha
					 AND nov_check_list.cod_nov_status = nov_status.codigo ";

	$where2 = " WHERE nov_check_list.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
	   	             AND nov_check_list.codigo = nov_check_list_det.cod_check_list
                     AND nov_check_list.cod_nov_clasif = nov_clasif.codigo
                     AND nov_check_list.cod_nov_tipo = nov_tipo.codigo
                     AND nov_check_list.cod_cliente = clientes.codigo
                     AND nov_check_list.cod_ubicacion = clientes_ubicacion.codigo
                     AND nov_check_list.cod_ficha = ficha.cod_ficha
                     AND nov_check_list.cod_nov_status = nov_status.codigo ";

	if ($codigo != "" && $codigo != null) {
		$where .= " AND nov_check_list.codigo = '$codigo' ";
		$where2 .= " AND nov_check_list.codigo = '$codigo' ";
	}

	if ($clasif != "TODOS") {
		$where .= " AND nov_clasif.codigo = '$clasif' ";
		$where2 .= " AND nov_clasif.codigo = '$clasif' ";
	}

	if ($tipo != "TODOS") {
		$where .= " AND nov_tipo.codigo = '$tipo' ";
		$where2 .= " AND nov_tipo.codigo = '$tipo' ";
	}

	if ($agrupacion != "TODOS") {
		$where .= " AND nov_agrupacion.codigo = '$agrupacion' ";
		$where2 .= " AND nov_agrupacion.codigo = '$agrupacion' ";
	}

	if ($status != "TODOS") {
		$where  .= " AND nov_status.codigo = '$status' ";
		$where2  .= " AND nov_status.codigo = '$status' ";
	}

	if ($cliente != "TODOS") {
		$where  .= " AND clientes.codigo = '$cliente' ";
		$where2  .= " AND clientes.codigo = '$cliente' ";
	}

	if ($ubicacion != "TODOS") {
		$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
		$where2  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}

	if ($trabajador != NULL) {
		$where  .= " AND ficha.cod_ficha = '$trabajador' ";
		$where2  .= " AND ficha.cod_ficha = '$trabajador' ";
	}

	// QUERY A MOSTRAR //
	$sql = "SELECT nov_check_list.codigo, nov_check_list.fec_us_ing,
                         CONCAT(ficha.apellidos, ' ', ficha.nombres) AS superv, clientes.nombre AS cliente,
						 clientes_ubicacion.descripcion AS ubicacion, nov_clasif.descripcion AS clasif,
                         nov_tipo.descripcion AS tipo, novedades.descripcion AS check_list ,
						 nov_valores.abrev, nov_check_list_det.valor,
						 nov_check_list_det.valor_max,  nov_valores.factor,
					     nov_check_list_det.observacion, nov_check_list.fec_us_mod,
						 nov_status.descripcion AS nov_status,
					   IF(ISNULL(nov_agrupacion.descripcion),'N/A',nov_agrupacion.descripcion) agrupacion
                    FROM nov_check_list , nov_check_list_det LEFT JOIN nov_agrupacion ON nov_check_list_det.cod_nov_agrupacion = nov_agrupacion.codigo, 
						 novedades, nov_clasif , nov_tipo, nov_valores, clientes ,
					 	 clientes_ubicacion , ficha , nov_status
                  $where
              ORDER BY 3 ASC  ";

	$sql2 = "SELECT nov_check_list.codigo, nov_check_list.fec_us_ing,
                      CONCAT(ficha.apellidos, ' ', ficha.nombres) AS superv, clientes.nombre AS cliente,
					  clientes_ubicacion.descripcion AS ubicacion, nov_clasif.descripcion AS clasif,
					  nov_tipo.descripcion AS tipo, nov_check_list.observacion,
					  nov_check_list.repuesta,
				Sum(nov_check_list_det.valor) AS check_list_valor, Sum(nov_check_list_det.valor_max) AS valor_max,
                nov_check_list.fec_us_mod, nov_status.descripcion AS nov_status
           FROM nov_check_list , nov_check_list_det LEFT JOIN nov_agrupacion ON nov_check_list_det.cod_nov_agrupacion = nov_agrupacion.codigo, 
		   		nov_clasif ,  nov_tipo, clientes , clientes_ubicacion , ficha , nov_status
        $where2
       GROUP BY nov_check_list.codigo
      ORDER BY 3 ASC ";

	if ($reporte == 'excel') {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");

		if ($detalle ==  "S") {
			echo $sql;
			$query01  = $bd->consultar($sql);

			echo "<table border=1>
	      <tr><th>".$sql." </th><th>Fecha </th><th> Supervisor </th><th> " . $leng['cliente'] . " </th>
	           <th> " . $leng['ubicacion'] . " </th><th> Clasificación </th><th> Tipo </th><th> Agrupacion </th><th> CHECK LIST </th>
			   <th> Abreviatura </th><th> Valor </th><th> Valor MAX</th><th> % Cumplimiento </th>
			   <th> Factor </th><th> Observación </th><th> Fec. Ult. Modificación </th><th> Status </th></tr>";

			while ($row01 = $bd->obtener_num($query01)) {
				echo "<tr><td>" . $row01[0] . "</td><td>" . $row01[1] . "</td><td>" . $row01[2] . "</td><td>" . $row01[3] . "</td>
		           <td>" . $row01[4] . "</td><td>" . $row01[5] . "</td><td>" . $row01[6] . "</td><td>" . $row01[7] . "</td>
		           <td>" . $row01[15] . "</td><td>" . $row01[8] . "</td><td>" . $row01[9] . "</td><td>" . $row01[10] . "</td>
				   <td>" . Redondear2d(($row01[9] * 100) / $row01[10]) . "</td>
				   <td>" . $row01[11] . "</td><td>" . $row01[12] . "</td><td>" . $row01[13] . "</td><td>" . $row01[14] . "</td></tr>";
			}
			echo "</table>";
		} elseif ($detalle == "N") {

			$query01  = $bd->consultar($sql2);

			echo "<table border=1>
	      <tr><th>Código </th><th>Fecha </th><th> Supervisor </th><th> " . $leng['cliente'] . " </th>
	          <th> " . $leng['ubicacion'] . " </th><th> Clasificación </th> <th> Tipo </th><th> Observación </th>
			  <th>Respuesta </th>
			  <th> Valor CHECK LIST</th><th> Valor MAX</th><th> % Cumplimiento </th><th> Fec. Última Modificación </th>
			  <th> Status </th></tr>";

			while ($row01 = $bd->obtener_num($query01)) {
				echo "<tr><td>" . $row01[0] . "</td><td>" . $row01[1] . "</td><td>" . $row01[2] . "</td><td>" . $row01[3] . "</td>
		           <td>" . $row01[4] . "</td><td>" . $row01[5] . "</td><td>" . $row01[6] . "</td><td>" . $row01[7] . "</td> <td>" . $row01[8] . "</td>
				   <td>" . $row01[9] . "</td><td>" . $row01[10] . "</td><td>" . Redondear2d(($row01[9] * 100) / $row01[10]) . "</td>
				   <td>" . $row01[11] . "</td><td>" . $row01[12] . "</td></tr>";
			}
			echo "</table>";
		}
	}

	if ($reporte == 'pdf') {

		require_once('../' . ConfigDomPdf);
		$dompdf = new DOMPDF();


		if ($detalle ==  "S") {

			$query  = $bd->consultar($sql);
		} elseif ($detalle == "N") {

			$query  = $bd->consultar($sql2);
		}


		ob_start();

		require('../' . PlantillaDOM . '/header_ibarti_2.php');
		include('../' . pagDomPdf . '/paginacion_ibarti.php');

		if ($detalle ==  "S") {
			echo "<br><div>
        <table>
		<tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>Código</th>
            <th width='10%'>Fecha</th>
            <th width='15%'>Clasificación</th>
            <th width='20%'>Check List</th>
            <th width='16%'>" . $leng['cliente'] . "</th>
            <th width='16%'>" . $leng['ubicacion'] . "</th>
            <th width='8%'>Abreviatura</th>
			<th width='10%'>% Cump.</th>
            </tr>";

			$f = 0;
			while ($row = $bd->obtener_num($query)) {
				if ($f % 2 == 0) {
					echo "<tr>";
				} else {
					echo "<tr class='class= odd_row'>";
				}
				echo   "<td width='10%'>" . $row[0] . "</td>
   		    <td width='16%'>" . $row[1] . "</td>
            <td width='14%'>" . $row[5] . "</td>
            <td width='15%'>" . $row[7] . "</td>
            <td width='15%'>" . $row[3] . "</td>
            <td width='20%'>" . $row[4] . "</td>
            <td width='10%'>" . $row[8] . "</td>
			<td width='10%'>" . Redondear2d(($row[9] * 100) / $row[10]) . "</td></tr>";
				$f++;
			}
		} elseif ($detalle == "N") {
			echo "<br><div>
        <table>
		<tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>Código</th>
            <th width='10%'>Fecha</th>
            <th width='20%'>Clasificación</th>
            <th width='20%'>" . $leng['cliente'] . "</th>
            <th width='20%'>" . $leng['ubicacion'] . "</th>
            <th width='5%'>Valor</th>
            <th width='5%'>Valor Máximo</th>
			<th width='10%'>% Cump.</th>
            </tr>";


			$f = 0;
			while ($row = $bd->obtener_num($query)) {
				if ($f % 2 == 0) {
					echo "<tr>";
				} else {
					echo "<tr class='class= odd_row'>";
				}
				echo   "<td width='9%'>" . $row[0] . "</td>
   		    <td width='14%'>" . $row[1] . "</td>
            <td width='19%'>" . $row[5] . "</td>
            <td width='19%'>" . $row[3] . "</td>
            <td width='19%'>" . $row[4] . "</td>
            <td width='10%'>" . $row[9] . "</td>
            <td width='10%'>" . $row[10] . "</td>
			<td width='5%'>" . Redondear2d(($row[9] * 100) / $row[10]) . "</td></tr>";
				$f++;
			}
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
