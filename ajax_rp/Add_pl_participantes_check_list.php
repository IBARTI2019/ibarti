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
$proyecto    = $_POST['proyecto'];
$actividad  = $_POST['actividad'];
$trabajador = $_POST['trabajador'];

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$where = " WHERE pd.fecha_inicio BETWEEN \"$fecha_D\" AND ADDDATE(\"$fecha_H\", 1)
AND p.cod_ficha = f.cod_ficha
AND pd.codigo = p.cod_det
AND pd.cod_actividad = pa.codigo
AND pd.cod_proyecto = pp.codigo
AND pd.cod_planif_cl_trab = pt.codigo
AND pt.cod_cliente = clientes.codigo
AND pt.cod_ubicacion = cu.codigo
AND p.evaluado = 'F'";


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
	$where   .= " AND pt.cod_cliente = '$cliente' ";
}

if ($ubicacion != "TODOS") {
	$where   .= " AND pt.cod_ubicacion = '$ubicacion' ";
}

if ($proyecto != "TODOS") {
	$where   .= " AND pd.cod_proyecto = '$proyecto' ";
}

if ($actividad != "TODOS") {
	$where   .= " AND pd.cod_actividad = '$actividad' ";
}

$sql = "SELECT
DATE_FORMAT(pd.fecha_inicio, '%Y-%m-%d') fecha,
p.cod_ficha,
CONCAT(f.nombres, f.apellidos) ap_nombre,
f.telefono,
f.celular,
pt.cod_cliente,
clientes.abrev abrev_cliente,
clientes.nombre cliente,
pt.cod_ubicacion,
cu.descripcion ubicacion,
pd.cod_proyecto,
pp.descripcion proyecto,
pd.cod_actividad,
pa.descripcion actividad,
p.codigo
FROM
planif_clientes_superv_trab_det_participantes p,
planif_clientes_superv_trab_det pd,
planif_clientes_superv_trab pt,
ficha f,
planif_actividad pa,
planif_proyecto pp,
clientes,
clientes_ubicacion cu
$where
ORDER BY cod_ficha";

?><table width="100%" border="0" align="center" class="tabla_sistema">
	<tr class="fondo00">
		<th class="etiqueta" width="10%">Fecha</th>
		<th class="etiqueta"><?php echo $leng['ficha'] ?></th>
		<th class="etiqueta" width="15%"><?php echo $leng['trabajador'] ?></th>
		<th class="etiqueta" width="8%">Telf. / Celular </th>
		<th class="etiqueta"><?php echo $leng['cliente'] ?> <br> <?php echo $leng['ubicacion'] ?></th>
		<th class="etiqueta">Proyecto </th>
		<th class="etiqueta">Actividad </th>
		<th class="etiqueta">Check List</th>
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
		echo '<tr class="' . $fondo . '">
		<td class="texto">' . $datos["fecha"] . '</td>
		<td class="texto">' . longitud($datos["cod_ficha"]) . '</td>
		<td class="texto">' . longitud($datos["ap_nombre"]) . '</td>
		<td class="texto">' . $datos["telefono"] . ' <br> ' . $datos["celular"] .  '</td>
		<td class="texto">' . longitud($datos["cliente"]) . ' <br> '. longitud($datos["ubicacion"]) .'</td>
		<td class="texto">' . longitud($datos["proyecto"]) . '</td>
		<td class="texto">' . longitud($datos["actividad"]) . '</td>
		<td><a onclick="openModalCheckList('.$datos["codigo"].')"><img src="imagenes/nuevo.bmp" alt="Generar Check List" title="Generar Check List" width="20px" height="20px" border="null" /></a></td>
		</tr>';
	}; ?>
</table>