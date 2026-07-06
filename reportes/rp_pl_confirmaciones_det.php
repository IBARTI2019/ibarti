<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 5301;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

if(($_POST['fecha_desde'] == "" or $_POST['fecha_hasta'] == "")){
	exit;
}
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$trabajador = $_POST['trabajador'];

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);

$reporte         = $_POST['reporte'];
$archivo         = "rp_pl_trab_real_".$fecha."";
$titulo          = "HISTORIAL DE CONFIRMACIONES \n";

if(isset($reporte)){
	$where = " WHERE (planif_clientes_trab_det.asistencia = 'T' OR planif_clientes_trab_det.confirm = 'T' OR planif_clientes_trab_det.in_transport = 'T')
AND planif_clientes_trab_det.fecha BETWEEN \"$fecha_D\" AND \"$fecha_H\"
	AND planif_clientes_trab_det.cod_cliente = clientes.codigo
	AND planif_clientes_trab_det.cod_ubicacion = clientes_ubicacion.codigo
	AND planif_clientes_trab_det.cod_ficha = v_ficha.cod_ficha";


	if($trabajador != NULL){
		$where   .= " AND  v_ficha.cod_ficha = '$trabajador' ";
	}

	if($cliente  != "TODOS"){
		$where   .= " AND planif_clientes_trab_det.cod_cliente = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where   .= " AND planif_clientes_trab_det.cod_ubicacion = '$ubicacion' ";
	}

	$sql = "SELECT planif_clientes_trab_det.fecha,
		clientes.nombre AS cliente, 
		clientes_ubicacion.descripcion AS ubicacion,
		planif_clientes_trab_det.cod_ficha,
		v_ficha.ap_nombre, turno.abrev turno, 
		horarios.nombre  AS horario,
		planif_clientes_trab_det.servicio_confirm,
		TIME(planif_clientes_trab_det.fec_confirm) hora_confirm,
		planif_clientes_trab_det.servicio_in_transport,
		TIME(planif_clientes_trab_det.fec_in_transport) hora_transport,
		TIME(planif_clientes_trab_det.fec_asistencia) hora_asistencia
	FROM
		planif_clientes_trab_det
		INNER JOIN turno ON planif_clientes_trab_det.cod_turno = turno.codigo
		INNER JOIN horarios ON turno.cod_horario = horarios.codigo ,
		clientes ,
		clientes_ubicacion ,
		v_ficha
		$where
		ORDER BY 1, 2 ASC ";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "<tr><th> Fecha </th><th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th>
		<th> ".$leng['ficha']." </th><th> ".$leng['trabajador']." </th><th>".$leng['turno']."</th> <th> ".$leng['horario']." </th>
		<th> Servicio Asisto </th><th> Hora Asisto </th><th> Servicio En Transporte </th><th> Hora En Transporte </th><th> Hora Asistencia </th>
		</tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td></tr>";
		}
		echo "</table>";
	}

	if($reporte == 'pdf'){
		require_once('../'.ConfigDomPdf);

		$dompdf= new DOMPDF();

		$query  = $bd->consultar($sql);

		ob_start();

		require('../'.PlantillaDOM.'/header_ibarti_2.php');
		include('../'.pagDomPdf.'/paginacion_ibarti.php');

		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='10%'>Fecha</th>
		<th width='10%'>".$leng['cliente']."</th>
		<th width='15%'>".$leng['ubicacion']."</th>
		<th width='5%'>".$leng['ficha']."</th>
		<th width='15%'>".$leng['trabajador']."</th>
		<th width='5%'>".$leng['turno']."</th>
		<th width='10%'>".$leng['horario']."</th>
		<th width='10%'>Asisto</th>
		<th width='10%'>En Transporte</th>
		<th width='10%'>Asistencia</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='10%'>".$row[0]."</td>
			<td width='10%'>".$row[1]."</td>
			<td width='15%'>".$row[2]."</td>
			<td width='5%'>".$row[3]."</td>
			<td width='15%'>".$row[4]."</td>
			<td width='5%'>".$row[5]."</td>
			<td width='10%'>".$row[6]."</td>
			<td width='10%'>".$row[8]."</td>
			<td width='10%'>".$row[10]."</td>
			<td width='10%'>".$row[11]."</td></tr>";

			$f++;
		}

		echo "</tbody>
		</table>
		</div>
		</body>
		</html>";

		$dompdf->load_html(ob_get_clean(),'UTF-8');
		$dompdf->set_paper ('letter','landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}
}