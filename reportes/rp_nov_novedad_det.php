<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 560;
require("../autentificacion/aut_config.inc.php");
require_once("../".Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();
$bd2 = new DataBase();

$novedad         = $_POST['novedad'];
$clasif          = $_POST['clasif'];
$status          = $_POST['status'];
$cliente         = $_POST['cliente'];
$ubicacion       = $_POST['ubicacion'];
$trabajador      = $_POST['trabajador'];
$detalle         = $_POST['detalle'];
$cod_nov 		= $_POST['stdNovedadID'];

$reporte         = $_POST['reporte'];
$titulo          = " REPORTE DE NOVEDADES";

$archivo         = "rp_nov_novedad";


if(isset($reporte)){
	if($cod_nov!=''){
		$where = " WHERE nov_procesos.codigo = '".$cod_nov."'
		AND nov_procesos.cod_novedad = novedades.codigo
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T'
		AND novedades.cod_nov_clasif = nov_clasif.codigo
		AND nov_clasif.campo04 = 'F'
		AND nov_procesos.cod_cliente = clientes.codigo
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
		AND nov_procesos.cod_ficha = ficha.cod_ficha
		AND nov_procesos.cod_nov_status = nov_status.codigo ";

		$where2 = " WHERE nov_procesos.codigo = '".$cod_nov."'
		AND nov_procesos.cod_novedad = novedades.codigo
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T'
		AND novedades.cod_nov_clasif = nov_clasif.codigo
		AND nov_clasif.campo04 = 'F'
		AND nov_procesos.cod_cliente = clientes.codigo
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
		AND nov_procesos.cod_ficha = ficha.cod_ficha
		AND nov_procesos.codigo = nov_procesos_det.cod_nov_proc
		AND nov_procesos_det.cod_nov_status = nov_status.codigo
		AND nov_procesos_det.cod_us_ing = men_usuarios.codigo ";
	}else{
		
		$fecha_D         = conversion($_POST['fecha_desde']);
		$fecha_H         = conversion($_POST['fecha_hasta']);
		$titulo          = " REPORTE DE NOVEDADES FECHA: ".$fecha_D." HASTA: ".$fecha_H."\n";
		$where = " WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
		AND nov_procesos.cod_novedad = novedades.codigo
		AND novedades.cod_nov_clasif = nov_clasif.codigo
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T'
		AND nov_clasif.campo04 = 'F'
		AND nov_procesos.cod_cliente = clientes.codigo
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
		AND nov_procesos.cod_ficha = ficha.cod_ficha
		AND nov_procesos.cod_nov_status = nov_status.codigo ";

		$where2 = " WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
		AND nov_procesos.cod_novedad = novedades.codigo
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T'
		AND novedades.cod_nov_clasif = nov_clasif.codigo
		AND nov_clasif.campo04 = 'F'
		AND nov_procesos.cod_cliente = clientes.codigo
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
		AND nov_procesos.cod_ficha = ficha.cod_ficha
		AND nov_procesos.codigo = nov_procesos_det.cod_nov_proc
		AND nov_procesos.cod_nov_status = nov_status.codigo
		AND nov_procesos_det.cod_us_ing = men_usuarios.codigo ";

		if($novedad != "TODOS"){
			$where  .= " AND novedades.codigo   = '$novedad' ";
			$where2 .= " AND novedades.codigo   = '$novedad' ";
		}

		if($clasif != "TODOS"){
			$where  .= " AND novedades.cod_nov_clasif   = '$clasif' ";
			$where2 .= " AND novedades.cod_nov_clasif   = '$clasif' ";
		}

		if($status != "TODOS"){
			$where  .= " AND nov_status.codigo = '$status' ";
			$where2 .= " AND nov_status.codigo = '$status' ";
		}

		if($cliente != "TODOS"){
			$where  .= " AND clientes.codigo = '$cliente' ";
			$where2 .= " AND clientes.codigo = '$cliente' ";
		}

		if($ubicacion != "TODOS"){
			$where  .= " AND clientes_ubicacion.codigo  = '$ubicacion' ";
			$where2 .= " AND clientes_ubicacion.codigo  = '$ubicacion' ";
		}

		if($trabajador != NULL){
			$where  .= " AND ficha.cod_ficha = '$trabajador' ";
			$where2 .= " AND ficha.cod_ficha = '$trabajador' ";
		}
	}
	// QUERY A MOSTRAR //
	$sql = "SELECT nov_procesos.codigo, nov_procesos.fec_us_ing,
	nov_clasif.descripcion AS clasif, novedades.descripcion AS novedad,
	clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
	nov_procesos.cod_ficha, ficha.cedula,
	CONCAT(ficha.apellidos ,' ',ficha.nombres) AS trabajador ,
	nov_procesos.observacion,
	nov_procesos.repuesta, nov_status.descripcion AS status
	FROM nov_procesos , novedades , nov_tipo,  nov_clasif,  clientes ,
	clientes_ubicacion , ficha , nov_status
	$where
	ORDER BY 1 ASC";

	$sql_det = "SELECT nov_procesos.codigo,  nov_procesos.fec_us_ing,
	nov_clasif.descripcion AS clasif, novedades.descripcion AS novedad,
	clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
	nov_procesos.cod_ficha,  ficha.cedula,
	CONCAT(ficha.apellidos ,' ',ficha.nombres) AS trabajador , nov_procesos.observacion AS observacion_g,
	nov_procesos.repuesta, nov_status.descripcion AS status
	FROM nov_procesos , novedades , nov_tipo, nov_clasif,  clientes ,
	clientes_ubicacion , ficha , nov_status,
	nov_procesos_det, men_usuarios
	$where2
	GROUP BY 1
	ORDER BY 1 ASC ";

	if($reporte == 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");
		echo "<table border=1>";

		if($detalle ==  "S"){
			$query01  = $bd->consultar(	$sql_det);
			echo "<tr><th>Código </th><th>Fecha Sistema </th><th> Clasificación </th><th> Novedad </th>
			<th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th>
			<th> ".$leng['trabajador']." </th><th> Observación General </th><th>Respuesta </th><th> Status </th>";
			$i =0;
			while ($row01 = $bd->obtener_num($query01)){
				$sql_pre = "SELECT nov_procesos_det.codigo,CONCAT(men_usuarios.apellido,' ',men_usuarios.nombre)AS usuario,  nov_procesos_det.fec_us_ing, nov_procesos_det.hora, nov_procesos_det.observacion, nov_status.descripcion AS status
				FROM nov_procesos,nov_procesos_det,novedades, nov_tipo, clientes,clientes_ubicacion,ficha  , nov_clasif , nov_status, men_usuarios
				WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
				AND nov_procesos.cod_novedad = novedades.codigo
				AND novedades.cod_nov_tipo = nov_tipo.codigo
				AND nov_tipo.kanban != 'T'
				AND novedades.cod_nov_clasif = nov_clasif.codigo
				AND nov_clasif.campo04 = 'F'
				AND nov_procesos.cod_cliente = clientes.codigo
				AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
				AND nov_procesos.cod_ficha = ficha.cod_ficha
				AND nov_procesos.codigo = nov_procesos_det.cod_nov_proc
				AND nov_procesos_det.cod_nov_status = nov_status.codigo
				AND nov_procesos_det.cod_us_ing = men_usuarios.codigo
				AND nov_procesos.codigo = '".$row01[0]."'
				ORDER BY 1 ASC";

				$queryPre  = $bd2->consultar($sql_pre);
				$ii = 1;
				while ($rowPre = $bd2->obtener_num($queryPre)){
					if($ii > $i){
						echo '<th >Código '.$ii.'</th><th >Usuario '.$ii.'</th><th >Fecha '.$ii.'</th><th >Hora '.$ii.'</th><th >Observación '.$ii.'</th><th >Status '.$ii.'</th>';
						$i = $ii;
						$codigo = $rowPre[0];
					}
					$ii++;
				}
			}
			echo '</tr>';
			$query01  = $bd->consultar(	$sql_det);
			while ($row01 = $bd->obtener_num($query01)){
				$sql_pre = "SELECT nov_procesos_det.codigo,CONCAT(men_usuarios.apellido,' ',men_usuarios.nombre)AS usuario,  nov_procesos_det.fec_us_ing, nov_procesos_det.hora, nov_procesos_det.observacion, nov_status.descripcion AS status
				FROM nov_procesos,nov_procesos_det,novedades, nov_tipo, clientes,clientes_ubicacion,ficha  , nov_clasif , nov_status, men_usuarios
				WHERE nov_procesos.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"
				AND nov_procesos.cod_novedad = novedades.codigo
				AND novedades.cod_nov_tipo = nov_tipo.codigo
				AND nov_tipo.kanban != 'T'
				AND novedades.cod_nov_clasif = nov_clasif.codigo
				AND nov_clasif.campo04 = 'F'
				AND nov_procesos.cod_cliente = clientes.codigo
				AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
				AND nov_procesos.cod_ficha = ficha.cod_ficha
				AND nov_procesos.codigo = nov_procesos_det.cod_nov_proc
				AND nov_procesos_det.cod_nov_status = nov_status.codigo
				AND nov_procesos_det.cod_us_ing = men_usuarios.codigo
				AND nov_procesos.codigo = '".$row01[0]."'
				ORDER BY 1 ASC";

				echo "<tr><td >".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
				<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
				<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td>";
				$queryPre  = $bd2->consultar($sql_pre);
				while ($rowPre = $bd2->obtener_num($queryPre)){
					echo "<td >".$rowPre[0]."</td><td>".$rowPre[1]."</td><td>".$rowPre[2]."</td><td>".$rowPre[3]."</td>
					<td>".$rowPre[4]."</td><td>".$rowPre[5]."</td>";
				}
				echo '</tr>';
			}


	}elseif($detalle == "N"){  //// REVISAR ////
		$query01  = $bd->consultar($sql);
		echo "<tr><th>Código </th><th>Fecha </th><th> Clasificación </th><th> Novedad </th>
		<th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th>
		<th> ".$leng['trabajador']." </th><th> Observación </th><th>Respuesta </th><th> Status </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td >".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td></tr>";
		}
	}
	echo "</table>";
}

if($reporte == 'pdf'){
	require_once('../'.ConfigDomPdf);
	$dompdf= new DOMPDF();

	if($detalle =='N'){
		$query = $bd->consultar($sql);
	}elseif($detalle =='S'){
		$query = $bd->consultar($sql_det);
	}
	ob_start();

	require('../'.PlantillaDOM.'/header_ibarti_2.php');
	include('../'.pagDomPdf.'/paginacion_ibarti.php');

	if($detalle =='N'){
		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='8%'>Código</th>
		<th width='12%' style='text-align:center;'>Fecha Sistema</th>
		<th width='14%'>Clasificación</th>
		<th width='25%'>Novedad</th>
		<th width='13%'>".$leng['cliente']."</th>
		<th width='8%'>".$leng['ficha']."</th>
		<th width='20%'>".$leng['trabajador']."</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='8%'>".$row[0]."</td>
			<td width='12%' style='text-align:center;'>".$row[1]."</td>
			<td width='14%'>".$row[2]."</td>
			<td width='25%'>".$row[3]."</td>
			<td width='13%'>".$row[4]."</td>
			<td width='8%'>".$row[6]."</td>
			<td width='20%'>".$row[8]."</td></tr>";

			$f++;
		}
	}elseif ($detalle == 'S') {
		echo "<br><div>
		<table style='padding:0; font-size: 11px;'>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='8%'>Código</th>
		<th width='10%' style='text-align:center;'>Fecha Sistema</th>
		<th width='24%'>Novedad</th>
		<th width='10%'>".$leng['cliente']."</th>
		<th width='8%'>".$leng['ficha']."</th>
		<th width='10%'>".$leng['trabajador']."</th>
		<th width='10%'>Respuesta</th>
		<th width='8%'>Hora</th>
		<th width='12%'>Status</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='8%'>".$row[0]."</td>
			<td width='10%' style='text-align:center;'>".$row[1]."</td>
			<td width='24%'>".$row[3]."</td>
			<td width='10%'>".$row[4]."</td>
			<td width='8%'>".$row[6]."</td>
			<td width='10%'>".$row[8]."</td>
			<td width='10%'>".$row[10]."</td>
			<td width='8%'>".$row[12]."</td>
			<td width='12%'>".$row[13]."</td></tr>";

			$f++;
		}
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
