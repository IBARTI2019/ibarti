<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../" . class_bd;
require "../" . Leng;
$bd = new DataBase();

$region     = $_POST['region'];
$estado     = $_POST['estado'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$area_proyecto    = $_POST['area_proyecto'];
$proyecto    = $_POST['proyecto'];
$actividad  = $_POST['actividad'];
$trabajador = $_POST['trabajador'];

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$where = " WHERE p.fecha_inicio BETWEEN \"$fecha_D\" AND ADDDATE(\"$fecha_H\", 1)
AND p.codigo = pd.cod_planif_cl_trab
AND p.cod_ficha = f.cod_ficha
AND p.cod_cliente = cl.codigo
AND p.cod_ubicacion = cu.codigo
AND pd.cod_proyecto = pp.codigo 
AND pd.cod_actividad = pa.codigo ";


if ($region != "TODOS") {
	$where  .= " AND f.cod_region = '$region' ";
}

if ($estado != "TODOS") {
	$where  .= " AND f.cod_estado = '$estado' ";
}

if ($trabajador != NULL) {
	$where   .= " AND  f.cod_ficha = '$trabajador' ";
}

if ($cliente  != "TODOS") {
	$where   .= " AND p.cod_cliente = '$cliente' ";
}

if ($ubicacion != "TODOS") {
	$where   .= " AND p.cod_ubicacion = '$ubicacion' ";
}

if ($proyecto != "TODOS" && $proyecto != NULL) {
	$where   .= " AND pd.cod_proyecto = '$proyecto' ";
}

if ($area_proyecto != "TODOS" && $area_proyecto != NULL) {
	$where   .= " AND pp.cod_area = '$area_proyecto' ";
}

if ($actividad != "TODOS" && $actividad != NULL) {
	$where   .= " AND pd.cod_actividad = '$actividad' ";
}

$sql = "SELECT pd.codigo, p.cod_ficha, CONCAT(f.apellidos, ' ', f.nombres) ap_nombre, p.cod_cliente, cl.nombre cliente, 
p.cod_ubicacion, cu.descripcion ubicacion, DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') fecha, 
TIME(pd.fecha_inicio) hora_inicio, TIME(pd.fecha_fin) hora_fin,
pd.cod_proyecto, pp.descripcion proyecto, pd.cod_actividad, pa.descripcion actividad,
pa.minutos, IF(pd.realizado='T','SI', 'NO') realizado
FROM planif_clientes_superv_trab p, planif_clientes_superv_trab_det pd, clientes cl, clientes_ubicacion cu, ficha f,
planif_proyecto pp, planif_actividad pa
$where
ORDER BY 1,2,9,4,6,8 ASC";

?><table width="100%" border="0" align="center">
	<tr class="fondo00">
		<th  class="etiqueta">Código</th>
		<th class="etiqueta">Fecha</th>
		<th  class="etiqueta"><?php echo $leng['ficha']?></th>
		<th  class="etiqueta"><?php echo $leng['trabajador']?></th>
		<th  class="etiqueta"><?php echo $leng['cliente']?></th>
		<th  class="etiqueta"><?php echo $leng['ubicacion']?></th>
		<th  class="etiqueta">Proyecto </th>
		<th  class="etiqueta">Actividad </th>
		<th  class="etiqueta">Hora Inicio </th>
		<th  class="etiqueta">Hora Fin </th>
		<th  class="etiqueta">Minutos</th>
		<th  class="etiqueta">Realizado </th>
	</tr>
	<?php
	$valor = 0;
	$query = $bd->consultar($sql);

	while ($datos = $bd->obtener_fila($query, 0)) {
		if ($valor == 0) {
			$fondo = 'fondo01';
			$valor = 1;
		} else {
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo '<tr class="'.$fondo.'">
		<td class="texto">'.$datos["codigo"].'</td>
		<td class="texto">'.$datos["fecha"].'</td>
		<td class="texto">'.longitud($datos["cod_ficha"]).'</td>
		<td class="texto">'.longitud($datos["ap_nombre"]).'</td>
		<td class="texto">'.longitud($datos["cliente"]).'</td>
		<td class="texto">'.longitud($datos["ubicacion"]).'</td>
		<td class="texto">'.longitud($datos["proyecto"]).'</td>
		<td class="texto">'.longitud($datos["actividad"]).'</td>
		<td class="texto">'.longitud($datos["hora_inicio"]).'</td>
		<td class="texto">'.longitud($datos["hora_fin"]).'</td>
		<td class="texto">'.longitud($datos["minutos"]).'</td>
		<td class="texto">'.longitud($datos["realizado"]).'</td>
		</tr>';
	}; ?>
</table>