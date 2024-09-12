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
$realizado  = $_POST['realizado'];
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

if ($proyecto != "TODOS") {
	$where   .= " AND pd.cod_proyecto = '$proyecto' ";
}

if ($actividad != "TODOS") {
	$where   .= " AND pd.cod_actividad = '$actividad' ";
}

if ($realizado != "TODOS") {
	if ($realizado == "S") {
		$where   .= " AND pd.realizado = 'T' ";
	} else if ($realizado == "N") {
		$where   .= " AND pd.realizado = 'F' ";
	}
}

$sql = "SELECT p.cod_ficha, CONCAT(f.apellidos, ' ', f.nombres) ap_nombre, p.cod_cliente, cl.nombre cliente, 
p.cod_ubicacion, cu.descripcion ubicacion, DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') fecha, 
TIME(pd.fecha_inicio) hora_inicio, TIME(pd.fecha_fin) hora_fin,
pd.cod_proyecto, pp.descripcion proyecto, pd.cod_actividad, pa.descripcion actividad,
pa.minutos, IF(pd.realizado='T','SI', 'NO') realizado, psod.observacion,  CONCAT(men_usuarios.nombre, ' ', men_usuarios.apellido) marcado_por, pd.link
FROM planif_clientes_superv_trab p, planif_clientes_superv_trab_det pd
LEFT JOIN planif_clientes_superv_trab_det_observ psod ON pd.codigo = psod.cod_det
LEFT JOIN men_usuarios ON pd.cod_us_marcaje = men_usuarios.codigo, clientes cl, clientes_ubicacion cu, ficha f,
planif_proyecto pp, planif_actividad pa
$where
ORDER BY 1,8,3,5,7 ASC";

?><table width="100%" border="0" align="center">
	<tr class="fondo00">
		<th class="etiqueta">Fecha</th>
		<th class="etiqueta"><?php echo $leng['ficha'] ?></th>
		<th class="etiqueta"><?php echo $leng['trabajador'] ?></th>
		<th class="etiqueta"><?php echo $leng['cliente'] ?></th>
		<th class="etiqueta"><?php echo $leng['ubicacion'] ?></th>
		<th class="etiqueta">Proyecto </th>
		<th class="etiqueta">Actividad </th>
		<th class="etiqueta">Hora Inicio </th>
		<th class="etiqueta">Hora Fin </th>
		<th class="etiqueta">Realizado </th>
		<th class="etiqueta">Marcado Por </th>
		<th class="etiqueta">Observación </th>
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
		<td class="texto">' . longitud($datos["cliente"]) . '</td>
		<td class="texto">' . longitud($datos["ubicacion"]) . '</td>
		<td class="texto">' . longitud($datos["proyecto"]) . '</td>
		<td class="texto">' . longitud($datos["actividad"]) . '</td>
		<td class="texto">' . longitud($datos["hora_inicio"]) . '</td>
		<td class="texto">' . longitud($datos["hora_fin"]) . '</td>
		<td class="texto">' . $datos["realizado"]; 
	
		if($datos["realizado"] == 'SI' && $datos["link"]){
			echo '<a style="margin-left: 5px;" href="'.$datos["link"].'"><img style="margin: 0px; padding: 0px;" src="imagenes/consultar.png" alt="Ver Soporte" width="18px" height="18px" title="Ver Soporte" border="null" /></a>';
		}
		
		echo '</td>
		<td class="texto">' . $datos["marcado_por"] . '</td>
		<td class="texto">' . longitud($datos["observacion"]) . '</td>
		</tr>';
	}; ?>
</table>