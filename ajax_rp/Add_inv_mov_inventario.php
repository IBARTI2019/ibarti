<?php
define("SPECIALCONSTANT", true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../" . class_bdI;
require "../" . Leng;
$bd = new DataBase();

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$almacen   = $_POST['almacen'];
$producto  = $_POST['producto'];
$referencia  = $_POST['referencia'];
$tipo      = $_POST['tipo'];
$result = array();

$where = " WHERE ajuste_reng.cod_almacen = almacenes.codigo AND ajuste_reng.cod_producto = productos.item
AND ajuste.codigo = ajuste_reng.cod_ajuste AND ajuste.cod_tipo = prod_mov_tipo.codigo 
AND prod_sub_lineas.codigo = productos.cod_sub_linea AND tallas.codigo = productos.cod_talla
AND ajuste.fec_us_ing BETWEEN '$fecha_D' AND '$fecha_H' ";

$where_alcance = " WHERE ajuste_alcance.codigo =ajuste_alcance_reng.cod_ajuste
AND ajuste_alcance_reng.cod_almacen = almacenes.codigo
AND ajuste_alcance_reng.cod_producto = productos.item
AND ajuste_alcance.fec_us_ing BETWEEN '$fecha_D' AND '$fecha_H' ";

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

$sql = " SELECT ajuste.codigo,ajuste.referencia,ajuste.fecha,prod_mov_tipo.descripcion ajuste,prod_mov_tipo.tipo_movimiento,almacenes.codigo cod_almacen, almacenes.descripcion almacen,productos.item cod_producto, IF(prod_sub_lineas.talla = 'T', CONCAT(productos.descripcion,' ',tallas.descripcion), productos.descripcion ) producto ,ajuste_reng.cantidad,ajuste_reng.costo,ajuste_reng.neto,
ajuste_reng.cant_acum,ajuste_reng.importe importe_acum,ajuste_reng.cos_promedio ,ajuste_reng.aplicar
FROM ajuste,ajuste_reng,prod_mov_tipo,almacenes,productos,prod_sub_lineas,tallas
$where ";

if ($tipo == "TODOS" || $tipo == 'DOT') {
	$sql .= " UNION
		SELECT
			ajuste_alcance.codigo,
			ajuste_alcance.referencia,
			ajuste_alcance.fecha,
			'DOTACION ALCANCE',
			'OUT',
			ajuste_alcance_reng.cod_almacen,
			almacenes.descripcion,
			ajuste_alcance_reng.cod_producto,
			productos.descripcion producto, 
			ajuste_alcance_reng.cantidad,
		0 costo,
		0 neto,
		0 cant_acum,
		0 importe_acum,
		0 cos_promedio,
		'OUT' aplicar
		FROM ajuste_alcance, ajuste_alcance_reng, almacenes, productos
		$where_alcance ";
}

$sql .= " ORDER BY fecha ASC ";
//echo $sql;
$query = $bd->consultar($sql);
while ($rows = $bd->obtener_name($query)) {
	$result[] = $rows;
}

// print_r(json_encode($result));
// return json_encode($result);

?>
<table class="tabla_sistema" width="100%" border="0" align="center">
	<tr>
		<th width="8%" class="etiqueta">Codigo</th>
		<th width="8%" class="etiqueta">Referencia</th>
		<th width="8%" class="etiqueta">Fecha Hora</th>
		<th width="13%" class="etiqueta">Tipo Movimiento</th>
		<th width="13%" class="etiqueta">Almacen</th>
		<th width="18%" class="etiqueta"><?php echo $leng['producto'] ?></th>
		<th width="8%" class="etiqueta">Cantidad</th>
		<th width="8%" class="etiqueta">Costo</th>
		<th width="8%" class="etiqueta">Neto</th>
		<th width="8%" class="etiqueta">Cant. Acum.</th>
		<th width="8%" class="etiqueta">Importe</th>
		<th width="8%" class="etiqueta">Costo Prom.</th>
	</tr>
	<?php
	$valor = 0;
	$query = $bd->consultar($sql);
	while ($datos = $bd->obtener_fila($query, 0)) {
		$signo = "";
		if ($datos["aplicar"] == "IN") {
			$signo = "+";
		} elseif ($datos["aplicar"] == "OUT") {
			$signo = "-";
		}
		echo '<tr>
		<td class="texto">' . $datos["codigo"] . '</td>
		<td class="texto">' . $datos["referencia"] . '</td>
		<td class="texto">' . $datos["fecha"] . '</td>
		<td class="texto">' . longitud($datos["ajuste"]) . '</td>
		<td class="texto">' . longitud($datos["almacen"]) . '</td>
		<td class="texto">' . $datos["producto"] . '</td>
		<td class="texto">' . $signo . '' . $datos["cantidad"] . '</td>
		<td class="texto">' . $datos["costo"] . '</td>
		<td class="texto">' . $datos["neto"] . '</td>
		<td class="texto">' . $datos["cant_acum"] . '</td>
		<td class="texto">' . $datos["importe_acum"] . '</td>
		<td class="texto">' . $datos["cos_promedio"] . '</td>
		</tr>';
	}; ?>
</table>