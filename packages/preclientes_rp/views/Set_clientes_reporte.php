<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 574;
require("../../../autentificacion/aut_config.inc.php");
include_once('../../../'.Funcion);
require_once("../../../".class_bdI);
require_once("../../../".Leng);
$bd = new DataBase();

$cliente         = $_POST['cliente'];
$ubicacion         = $_POST['ubicacion'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ciudad          = $_POST['ciudad'];

$reporte         = $_POST['reporte'];

$archivo         = "rp_cs_cliente_".$fecha."";
$titulo          = "  REPORTE CLIENTES  \n";

if(isset($reporte)){

	$where = "WHERE clientes_ubicacion.cod_cliente = clientes.codigo
	AND clientes.cod_cl_tipo = clientes_tipos.codigo
	AND clientes_ubicacion.cod_region = regiones.codigo
	AND clientes_ubicacion.cod_estado = estados.codigo
	AND clientes_ubicacion.cod_ciudad = ciudades.codigo ";


	if($cliente != "TODOS"){
		$where .= " AND clientes.codigo = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}
	if($region != "TODOS"){
		$where .= " AND regiones.codigo = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND estados.codigo = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($ciudad != "TODOS"){
		$where  .= " AND ciudades.codigo = '$ciudad' ";
	}
	// QUERY A MOSTRAR //
	$sql = " SELECT regiones.descripcion AS region, estados.descripcion AS estado,
	ciudades.descripcion AS ciudad, clientes.nombre AS cliente,
	clientes_tipos.descripcion AS cliente_tipo,
	clientes.rif, clientes_ubicacion.contacto,
	clientes_ubicacion.descripcion AS ubicacion,
	clientes_ubicacion.telefono, clientes_ubicacion.email,
	clientes_ubicacion.direccion, clientes_ubicacion.`status`
	FROM clientes_ubicacion, clientes, clientes_tipos ,
	regiones , estados , ciudades
	$where
	ORDER BY 1 ASC";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";
		echo "<tr><th> ".$leng['region']." </th><th> ".$leng['estado']." </th><th> CIUDAD && MUNICIPIO </th><th> ".$leng['cliente']." </th>
		<th> Tipo </th><th> ".$leng['rif']." </th><th> Contacto </th><th> ".$leng['ubicacion']." </th>
		<th> Teléfono </th><th> ".$leng['correo']." </th><th> Dirección</th><th>Status </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td > ".$row01[0]." </td><td>".$row01[1]."</td>
			<td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td>
			<td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".$row01[8]."</td><td>".$row01[9]."</td>
			<td>".$row01[10]."</td><td>".statuscal($row01[11])."</td></tr>";
		}
		echo "</table>";
	}

	if($reporte == 'pdf'){
		
		require_once('../../../'.ConfigDomPdf);
		$dompdf= new DOMPDF();

		$query  = $bd->consultar($sql);

		ob_start();

		require('../../../'.PlantillaDOM.'/header_ibarti_2.php');
		include('../../../'.pagDomPdf.'/paginacion_ibarti.php');

		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='15%'>".$leng['estado']."</th>
		<th width='20%'>".$leng['cliente']."</th>
		<th width='25%'>".$leng['ubicacion']."</th>
		<th width='10%'>Teléfono</th>
		<th width='30%'>Dirección</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='15%'>".$row[1]."</td>
			<td width='20%'>".$row[3]."</td>
			<td width='25%'>".$row[7]."</td>
			<td width='10%'>".$row[8]."</td>
			<td width='30%'>".$row[10]."</td></tr>";

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