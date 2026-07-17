<?php
define("SPECIALCONSTANT", true);
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$almacen   = $_POST['almacen'];
$producto  = $_POST['producto'];
$tipo      = $_POST['tipo'];
$referencia  = $_POST['referencia'];
$reporte   = $_POST['reporte'];
$archivo         = "rp_inv_mov_inventario_" . $date . "";
$titulo          = " REPORTE MOVIMIENTO DE INVENTARIO \n";

if (isset($reporte)) {

	$where = " WHERE ajuste_reng.cod_almacen = almacenes.codigo AND ajuste_reng.cod_producto = productos.item
AND ajuste.codigo = ajuste_reng.cod_ajuste AND ajuste.cod_tipo = prod_mov_tipo.codigo 
AND prod_sub_lineas.codigo = productos.cod_sub_linea AND tallas.codigo = productos.cod_talla
AND ajuste.fecha BETWEEN '$fecha_D' AND '$fecha_H' ";

	$where_alcance = " WHERE ajuste_alcance.codigo =ajuste_alcance_reng.cod_ajuste
AND ajuste_alcance_reng.cod_almacen = almacenes.codigo
ANd ajuste_alcance_reng.cod_producto = productos.item
AND ajuste_alcance.fecha BETWEEN '$fecha_D' AND '$fecha_H' ";

	if ($almacen != "TODOS") {
		$where .= " AND ajuste_reng.cod_almacen = '$almacen' ";
		$where_alcance .= " AND ajuste_alcance_reng.cod_almacen = '$almacen' ";
	}

	if ($producto != "TODOS") {
		$where .= " AND ajuste_reng.cod_producto = '$producto' ";
		$where_alcance .= " AND ajuste_alcance_reng.cod_producto = '$producto' ";
	}

	if ($tipo != "TODOS") {
		$where .= " AND ajuste.cod_tipo= '$tipo' ";
	}

	if ($referencia != "" && $referencia != null) {
		$where .= " AND ajuste.referencia= '$referencia' ";
	}

	$sql = " SELECT ajuste.codigo,ajuste.referencia,ajuste.fecha,prod_mov_tipo.descripcion ajuste, almacenes.descripcion almacen,
	IF(prod_sub_lineas.talla = 'T', CONCAT(productos.descripcion,' ',tallas.descripcion), productos.descripcion ) producto ,
	ajuste_reng.cantidad,ajuste_reng.costo,ajuste_reng.neto,
ajuste_reng.cant_acum,ajuste_reng.importe importe_acum,ajuste_reng.cos_promedio ,ajuste_reng.aplicar,
(SELECT GROUP_CONCAT(cod_ean SEPARATOR ', ') FROM ajuste_reng_eans WHERE cod_ajuste = ajuste.codigo AND reng_num = ajuste_reng.reng_num) AS eans
FROM ajuste,ajuste_reng,prod_mov_tipo,almacenes,productos,prod_sub_lineas,tallas
$where ";

	if ($tipo == "TODOS" || $tipo == 'DOT') {
		$sql .= " UNION
		SELECT
			ajuste_alcance.codigo,
			ajuste_alcance.referencia,
			ajuste_alcance.fecha,
			'DOTACION ALCANCE',
			almacenes.descripcion,
			productos.descripcion producto, 
			ajuste_alcance_reng.cantidad,
		0 costo,
		0 neto,
		0 cant_acum,
		0 importe_acum,
		0 cos_promedio,
		'OUT' aplicar,
		'' eans
		FROM ajuste_alcance, ajuste_alcance_reng, almacenes, productos
		$where_alcance ";
	}

	$sql .= " ORDER BY fecha ASC ";

	if ($reporte == 'excel') {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");

		$query01  = $bd->consultar($sql);

		echo "<table border=1>";
		echo "<th> CODIGO</th><th> REFERENCIA</th><th> FECHA</th><th> AJUSTE </th><th> ALMACEN </th><th> PRODUCTO </th> <th> EANs </th> <th> CANTIDAD </th>
		<th> COSTO </th> <th> IMPORTE </th> <th> CANTIDAD ACUMULADA </th> <th> IMPORTE ACUMULADO </th>
		<th> COSTO PROMEDIO </th>
		</tr>";

		while ($row01 = $bd->obtener_num($query01)) {
			echo "<tr><td>" . $row01[0] . "</td><td>" . $row01[1] . "</td><td>" . $row01[2] . "</td><td>" . $row01[3] . "</td><td>" . $row01[4] . "</td><td>" . $row01[5] . "</td><td>" . $row01[13] . "</td><td>" . $row01[6] . "</td><td>" . $row01[7] . "</td><td>" . $row01[8] . "</td><td>" . $row01[9] . "</td><td>" . $row01[10] . "</td><td>" . $row01[11] . "</td></tr>";
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
		<th>Codigo</th>
		<th>Fecha</th>
		<th>Ajuste</th>
		<th>Almacen</th>
		<th>Producto</th>
		<th>EANs</th>
		<th>Cantidad</th>
		<th>Costo</th>
		</tr>";

		$f = 0;
		while ($row = $bd->obtener_num($query)) {
			if ($f % 2 == 0) {
				echo "<tr>";
			} else {
				echo "<tr class='class= odd_row'>";
			}
			echo   "<td width='10%'>" . $row[0] . "</td>
			<td width='10%'>" . $row[2] . "</td>
			<td width='10%'>" . $row[3] . "</td>
			<td width='15%'>" . $row[4] . "</td>
			<td width='20%'>" . $row[5] . "</td>
			<td width='15%'>" . $row[13] . "</td>
			<td width='10%'>" . $row[6] . "</td>
			<td width='10%'>" . $row[7] . "</td></tr>";

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
} ?>
</table>