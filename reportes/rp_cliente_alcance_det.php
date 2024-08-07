<?php
define("SPECIALCONSTANT",true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;
$bd = new DataBase();
$reporte          = $_POST['reporte'];
$archivo          = $_POST['archivo'];
$titulo = "Reporte Ubicación - Alcance";

if(isset($reporte)){
	$region          = $_POST['region'];
	$estado          = $_POST['estado'];
	$ciudad          = $_POST['ciudad'];
	$cliente          = $_POST['cliente'];
	$ubicacion          = $_POST['ubicacion'];
	$vencimiento      = $_POST['vencimiento'];
	$sub_linea      = $_POST['sub_linea'];
	
	$where = " 	WHERE clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
	AND clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo
	AND clientes_ubicacion.cod_cliente = clientes.codigo
	AND clientes.status = 'T'
	AND clientes.cod_region = regiones.codigo
	AND clientes_ubicacion.cod_estado = estados.codigo
	AND clientes_ubicacion.cod_ciudad = ciudades.codigo ";
	
	
	if($region != "TODOS"){
		$where .= " AND regiones.codigo = '$region' ";
	}
	
	if($estado != "TODOS"){
			$where .= " AND estados.codigo = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
		}
	
		if($ciudad != "TODOS"){
			$where  .= " AND ciudades.codigo = '$ciudad' ";
		}
	
		if($cliente != "TODOS"){
			$where .= " AND clientes.codigo = '$cliente' ";
		}
	
		if($ubicacion != "TODOS"){
			$where .= " AND cliente_ubicacion.codigo = '$ubicacion' "; 
		}
	
		if($vencimiento != "TODOS"){
			$where .= " AND clientes_ub_alcance.vencimiento = '$vencimiento' "; 
		}
	
		if($sub_linea != "TODOS"){
			$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
		}
	
		// QUERY A MOSTRAR //
		$sql = "SELECT regiones.descripcion region,
		estados.descripcion estado,
		ciudades.descripcion ciudad,
		clientes.nombre cliente, 
		clientes_ubicacion.descripcion ubicacion,
		prod_sub_lineas.descripcion sub_linea,
		clientes_ub_alcance.cantidad,
		clientes_ub_alcance.dias,
		clientes_ub_alcance.vencimiento
		FROM clientes_ub_alcance, prod_sub_lineas, clientes_ubicacion, clientes, regiones, estados, ciudades
		$where
		ORDER BY 5,6 ASC;";

	if($reporte== 'excel'){

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";

		echo "<tr><th>".$leng['region']." </th><th> ".$leng['estado']."  </th> <th> ".$leng['ciudad']."  </th>
		<th> ".$leng['cliente']."  </th><th> ".$leng['ubicacion']."  </th> <th> Sub Linea </th> <th> Cantidad </th>
		<th> Nro. de días para reponer </th><th> Aplica Vencimiento</th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
			echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
			<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			<td>".valorF($row01[8])."</td></tr>";
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
		<th width='10%'>".$leng['region']."</th>
		<th width='10%'>".$leng['estado']."</th>
		<th width='10%'>".$leng['ciudad']."</th>
		<th width='12%'>".$leng['cliente']." </th>
		<th width='12%'>".$leng['ubicacion']." </th>
		<th width='28%'>Sub Linea </th>
		<th width='5%'>Cantidad</th>
		<th width='5%'>Días para Reponer</th>
		<th width='8%'>Aplica vencimiento</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td>".$row[0]."</td>
			<td>".$row[1]."</td>
			<td>".$row[2]."</td>
			<td>".$row[3]."</td>
			<td>".$row[4]."</td>
			<td>".$row[5]."</td>
			<td>".$row[6]."</td>
			<td>".$row[7]."</td>
			<td>".valorF($row[8])."</td></tr>";

			$f++;
		}

		echo "</tbody>
		</table>
		</div>
		</body>
		</html>";

		$dompdf->load_html(ob_get_clean(),'UTF-8');
		$dompdf->set_paper('letter','landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}
}
?>
