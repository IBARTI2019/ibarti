<?php
define("SPECIALCONSTANT",true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require_once "../".class_bdI;
require_once "../".Leng;
$bd = new DataBase();

$ubicacion   = $_GET['cod_ubicacion'];
$sub_linea   = $_GET['cod_sub_linea'];
$allFechas	= $_GET['allFechas'];

if($allFechas == false){
	$fecha_D    = conversion($_POST['fecha_desde']);
	$fecha_H    = conversion($_POST['fecha_hasta']);
}

$where = " WHERE ajuste_alcance.anulado = 'F'
		AND ajuste_alcance.cod_ubicacion = $ubicacion
		AND ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste 
		AND ajuste_alcance.anulado = 'F'
		AND ajuste_alcance_reng.cod_producto = productos.item 
		AND productos.cod_linea = prod_lineas.codigo 
		AND productos.cod_sub_linea = \"$sub_linea\"
		AND productos.cod_sub_linea = prod_sub_lineas.codigo 
		AND ajuste_alcance_reng.cod_anulado = 0
		";

if($allFechas == false){
	$where .= " AND DATE_FORMAT(ajuste_alcance.fecha, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\" ";
}
	
// QUERY A MOSTRAR //
$sql = " SELECT
			ajuste_alcance.fecha,
			ajuste_alcance.referencia,
			prod_lineas.descripcion AS linea,
			prod_sub_lineas.descripcion AS sub_linea,
			productos.item cod_producto,
			productos.descripcion producto,
			ajuste_alcance_reng.cantidad,
			ajuste_alcance_reng_eans.cod_ean
		FROM
			ajuste_alcance,
			ajuste_alcance_reng LEFT JOIN
			ajuste_alcance_reng_eans ON ajuste_alcance_reng_eans.cod_ajuste = ajuste_alcance_reng.cod_ajuste
					AND ajuste_alcance_reng_eans.reng_num = ajuste_alcance_reng.reng_num,
			productos,
			prod_lineas,
			prod_sub_lineas
$where
ORDER BY 1 ASC;";

?>
	<br>
	<table width="95%" class="tabla_sistema">
		<thead>
			<tr>
				<tr class="fondo00">
				<th width="10%" class="etiqueta">Fecha</th>
				<th width="10%" class="etiqueta">Referencia</th>
				<th width="12%" class="etiqueta">Serial </th>
				<th width="24%" class="etiqueta">Producto </th>
				<th width="5%" class="etiqueta">Cantidad</th>
				<th width="5%" class="etiqueta">EAN</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$valor = 0;
			$query = $bd->consultar($sql);

			while ($datos=$bd->obtener_fila($query,0)){
				if ($valor == 0){
					$fondo = 'fondo01';
					$valor = 1;
				}else{
					$fondo = 'fondo02';
					$valor = 0;
				}
				echo '<tr class="'.$fondo.'">
				<td class="texto">'.$datos["fecha"].'</td>
				<td class="texto">'.$datos["referencia"].'</td>
				<td class="texto">'.$datos["cod_producto"].'</td>
				<td class="texto">'.$datos["producto"].'</td>
				<td class="texto">'.$datos["cantidad"].'</td>
				<td class="texto">'.$datos["cod_ean"].'</td>
				</tr>';
			};
		?>
		</tbody>
	</table>
	<br>