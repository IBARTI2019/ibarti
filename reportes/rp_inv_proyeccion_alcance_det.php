<?php
define("SPECIALCONSTANT", true);
$Nmenu   = 576;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

if (($_POST['fecha_desde'] == "")) {
	exit;
}
$reporte      = $_POST['reporte'];
$fecha_desde  = $_POST['fecha_desde'];
$fecha_D   = conversion($_POST['fecha_desde']);
$archivo      = "rp_inv_proyeccion_alcance" . $fecha_D . "";
$titulo       = "  PROYECCION DE DOTACION DE ALCANCES \n";
$region          = $_POST['region'];
$d_proyeccion = $_POST['d_proyeccion'];
$estado       = $_POST['estado'];
$linea        = $_POST['linea'];
$sub_linea    = $_POST['sub_linea'];
$cliente    = $_POST['cliente'];
$ubicacion    = $_POST['ubicacion'];
$producto    = $_POST['producto'];

$where = " ";

if($linea != "TODOS"){
	$where .= " AND prod_lineas.codigo = '$linea' ";
}

if($sub_linea != "TODOS"){
	$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
}

if($producto != NULL){
	$where  .= " AND productos.item = '$producto' ";
}

if($region != "TODOS"){
	$where .= " AND clientes.cod_region = '$region' ";
}

if($estado != "TODOS"){
	$where .= " AND clientes_ubicacion.cod_estado = '$estado' ";
}

if($cliente != "TODOS"){
	$where  .= " AND clientes_ubicacion.cod_cliente = '$cliente' ";
}

if($ubicacion != "TODOS"){
	$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' AND clientes_ub_alcance.cod_cl_ubicacion = '$ubicacion'";
}


$sql = "SELECT
max( ajuste_alcance.fecha ) fecha,
regiones.descripcion region,
estados.descripcion estado,
clientes.codigo cod_cliente,
clientes.nombre cliente,
clientes.abrev abrev_cliente,
clientes_ub_alcance.cod_cl_ubicacion cod_ubicacion,
clientes_ubicacion.descripcion ubicacion,
prod_lineas.codigo cod_linea,
prod_lineas.descripcion AS linea,
clientes_ub_alcance.cod_sub_linea,
prod_sub_lineas.descripcion AS sub_linea,
IFNULL( productos.item, prod_sub_lineas.codigo ) cod_producto,
CONCAT( productos.descripcion, ' ', IFNULL( tallas.descripcion, '' ) ) producto,
SUM(
	IFNULL(
		(
		SELECT
			sum( `pdd`.`cantidad` ) 
		FROM
			ajuste_alcance_reng pdd,
			productos sub_producto 
		WHERE
			pdd.cod_ajuste = ajuste_alcance.codigo 
			AND sub_producto.item = pdd.cod_producto 
			AND sub_producto.cod_sub_linea = clientes_ub_alcance.cod_sub_linea 
		HAVING
		IF
			(
				clientes_ub_alcance.vencimiento = 'T',
				DATE_ADD( DATE_FORMAT( IFNULL( ajuste_alcance.fecha, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL clientes_ub_alcance.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
				DATE_ADD( DATE_FORMAT( IFNULL( ajuste_alcance.fecha, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
			) = 1
		),
		0 
	) 
) cantidad,
clientes_ub_alcance.cantidad alcance,
IF
(
	clientes_ub_alcance.vencimiento = 'T',
	DATE_ADD( DATE_FORMAT( max( ajuste_alcance.fecha ), '%Y-%m-%d' ), INTERVAL clientes_ub_alcance.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
	DATE_ADD( DATE_FORMAT( max( ajuste_alcance.fecha ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
) vencido,
ajuste_alcance_reng.cantidad cantidad_dot 
FROM
clientes_ub_alcance
INNER JOIN control ON control.oesvica = control.oesvica
INNER JOIN prod_sub_lineas ON clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
INNER JOIN `ajuste_alcance` ON `ajuste_alcance`.`anulado` = 'F' 
AND ajuste_alcance.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion
LEFT JOIN `ajuste_alcance_reng` ON `ajuste_alcance`.`codigo` = ajuste_alcance_reng.cod_ajuste
INNER JOIN `productos` ON `productos`.`item` = `ajuste_alcance_reng`.`cod_producto` 
AND productos.cod_sub_linea = clientes_ub_alcance.cod_sub_linea
LEFT JOIN `tallas` ON `productos`.`cod_talla` = `tallas`.`codigo`
INNER JOIN clientes_ubicacion ON clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo 
AND clientes_ubicacion.status = 'T'
INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente AND clientes.status = 'T'
INNER JOIN regiones ON clientes.cod_region = regiones.codigo
WHERE
control.oesvica = control.oesvica 
".$where."
GROUP BY
cod_ubicacion,
cod_linea,
cod_sub_linea 
HAVING
( vencido = 1 ) 
OR ( vencido = 0 AND cantidad < alcance )
UNION
SELECT
	'SIN DOTAR' fecha,
	regiones.descripcion region,
	estados.descripcion estado,
	clientes.codigo cod_cliente,
	clientes.nombre cliente,
	clientes.abrev abrev_cliente,
	clientes_ub_alcance.cod_cl_ubicacion cod_ubicacion,
	clientes_ubicacion.descripcion ubicacion,
	prod_lineas.codigo cod_linea,
	prod_lineas.descripcion AS linea,
	clientes_ub_alcance.cod_sub_linea,
	prod_sub_lineas.descripcion AS sub_linea,
	prod_sub_lineas.codigo cod_producto,
	prod_sub_lineas.descripcion producto,
	0 cantidad,
	clientes_ub_alcance.cantidad alcance,
	1 vencido,
	0 cantidad_dot 
FROM
	clientes_ub_alcance
	INNER JOIN control ON control.oesvica = control.oesvica
	INNER JOIN prod_sub_lineas ON clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
	INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
	INNER JOIN clientes_ubicacion ON clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo 
	AND clientes_ubicacion.status = 'T'
	INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
	INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente AND clientes.status = 'T'
	INNER JOIN regiones ON clientes.cod_region = regiones.codigo
WHERE
	clientes_ub_alcance.cod_sub_linea NOT IN (
	SELECT
		sub_producto.cod_sub_linea 
	FROM
		ajuste_alcance,
		ajuste_alcance_reng,
		productos sub_producto
	WHERE
		ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste 
		AND ajuste_alcance.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion 
		AND sub_producto.item = ajuste_alcance_reng.cod_producto
		AND sub_producto.cod_sub_linea = clientes_ub_alcance.cod_sub_linea 
	) 
	".$where."
ORDER BY
fecha ASC, ubicacion ASC, producto ASC
";

if ($reporte == 'excel') {
	echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

	$query01  = $bd->consultar($sql);
	echo "<table border=1>";

	echo "<tr><th> Fecha </th><th>" . $leng['region'] . "</th><th>" . $leng['estado'] . " </th><th> " . $leng['cliente'] . " </th>
	<th>Abrev. Cliente</th><th> " . $leng['ubicacion'] . " </th>
		<th> Linea </th><th> Sub Linea </th><th> Cod. Producto</th> <th> Producto </th>
		<th> Cantidad </th><th> Alcance </th><th> Cant. A Dotar </th><th> Vencido </th></tr>";

	while ($row01 = $bd->obtener_num($query01)) {
		$vencido = "NO";
		if($row01[16] == 1){
			$vencido = "SI";
		}
		echo "<tr><td>" . $row01[0] . " </td><td>" . $row01[1] . "</td><td>" . $row01[2] . "</td><td>" . $row01[4] . "</td>
			<td>" . $row01[5] . "</td><td>" . $row01[7] . "</td><td>" . $row01[9] . "</td><td>" . $row01[11] . "</td><td>" . $row01[13] . "</td><td>" . $row01[15] . "</td>
			<td>" . $row01[14] . "</td><td>" . $row01[15] . "</td><td>" . ($row01[15] - $row01[14]) . "</td><td>" . $vencido . "</td></tr>";
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
		<th width='12%'>Fecha</th>
		<th width='20%'>" . $leng['cliente'] . "</th>
		<th width='20%'>" . $leng['ubicacion'] . "</th>
		<th width='18%'>Producto</th>
		<th width='5%''>Cant.</th>
		<th width='5%'>Alc.</th>
		<th width='5%'>Dotar</th>
		</tr>";

	$f = 0;
	while ($row = $bd->obtener_num($query)) {
		if ($f % 2 == 0) {
			echo "<tr>";
		} else {
			echo "<tr class='class= odd_row'>";
		}
		echo   " <td width='12%'>" . $row[0] . "</td>
			<td width='20%'>" . $row[5] . "</td>
			<td width='10%'>" . $row[7] . "</td>
			<td width='20%'>" . $row[13] . "</td>
			<td width='18%'>" . $row[14] . "</td>
			<td width='5%'>" . $row[15] . "</td>
			<td width='5%'>" . ($row[15] - $row[14]). "</td></tr>";

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
