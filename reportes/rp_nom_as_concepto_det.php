<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 546;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

// TXT bajar concepto ficha cantidad  26-05 2017  

if(($_POST['fecha_desde'] == "" or $_POST['fecha_hasta'] == "")){
	exit;
}

$fecha_D         = conversion($_POST['fecha_desde']);
$fecha_H         = conversion($_POST['fecha_hasta']);
$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$contrato       = $_POST['contrato'];
$concepto        = $_POST['concepto'];

$trabajador     = $_POST['trabajador'];
$reporte         = $_POST['reporte'];

$archivo         = "rp_nom_as_concepto".$_POST['fecha_desde']."";
$titulo          = " REPORTE ASISTENCIA CONCEPTOS FECHA: ".$fecha_D." HASTA: ".$fecha_H."\n";

if(isset($reporte)){

	$where = " WHERE asistencia_apertura.fec_diaria BETWEEN \"$fecha_D\" AND \"$fecha_H\"
	AND asistencia_apertura.codigo = v_asistencia.cod_as_apertura
	AND v_asistencia.cod_ficha = ficha.cod_ficha
	AND ficha.cod_ficha = trab_roles.cod_ficha
	AND ficha.cod_contracto = contractos.codigo
	AND trab_roles.cod_rol = roles.codigo
	AND ficha.cod_estado = estados.codigo ";

	if($rol != "TODOS"){
		$where  .= " AND roles.codigo = '$rol' ";
	}
	if($region != "TODOS"){
		$where .= " AND v_asistencia.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND estados.codigo = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($contrato != "TODOS"){
		$where  .= " AND ficha.cod_contracto = '$contrato' ";
	}

	if($concepto != "TODOS"){
		$where  .= " AND v_asistencia.cod_concepto = '$concepto' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_asistencia.cod_ficha = '$trabajador' ";
	}

// QUERY A MOSTRAR //
	$sql = "  SELECT asistencia_apertura.fec_diaria, roles.descripcion AS rol,
	v_asistencia.region, estados.descripcion AS estado,
	contractos.descripcion AS contrato, v_asistencia.cod_ficha,
	ficha.cedula, CONCAT(ficha.apellidos,' ', ficha.nombres) AS trabajador,
	v_asistencia.cod_concepto, v_asistencia.abrev,v_asistencia.concepto,
	Count(v_asistencia.concepto) AS cantidad
	FROM asistencia_apertura , v_asistencia, ficha, contractos,
	trab_roles, roles, estados
	$where
	GROUP BY
	roles.descripcion, v_asistencia.region, estados.descripcion, contractos.descripcion,
	v_asistencia.cod_ficha, ficha.cedula, v_asistencia.cod_concepto
	ORDER BY 1 ASC ";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");

		$query01  = $bd->consultar($sql);

		echo "<table border=1>";

		echo "<tr><th>Fecha</th><th> ".$leng['rol']."</th><th> ".$leng['region']." </th><th> ".$leng['estado']." </th> <th> ".$leng['contrato']." </th>
		<th> ".$leng['ficha']." </th> <th> ".$leng['ci']." </th> <th> Nombre </th><th> Cod. ".$leng['concepto']." </th>
		<th> Abreviatura </th> <th> ".$leng['concepto']." </th><th> Cantidad </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td></tr>";

		}
		echo "</table>";
	}

	if($reporte == 'pdf'){

		$titulo          = " REPORTE ASISTENCIA CONCEPTOS <br> FECHA: ".$fecha_D." HASTA: ".$fecha_H."";
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
		<th width='8%'>Fecha</th>
		<th width='10%'>".$leng['rol']."</th>
		<th width='8%'>".$leng['estado']."</th>
		<th width='10%'>".$leng['contrato']."</th>
		<th width='8%'>".$leng['ficha']."</th>
		<th width='26%'>".$leng['trabajador']."</th>
		<th width='10%' style='text-align:center;'>Abreviatura</th>
		<th width='10%' style='text-align:center;'>Concepto</th>
		<th width='10%' style='text-align:center;'>Cantidad</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='8%'>".$row[0]."</td>
			<td width='10%'>".$row[1]."</td>
			<td width='8%'>".$row[3]."</td>
			<td width='10%'>".$row[4]."</td>
			<td width='8%'>".$row[5]."</td>
			<td width='22%'>".$row[7]."</td>
			<td width='10%'>".$row[9]."</td>
			<td width='14%' style='text-align:center;'>".$row[10]."</td>
			<td width='10%' style='text-align:center;'>".$row[11]."</td></tr>";

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