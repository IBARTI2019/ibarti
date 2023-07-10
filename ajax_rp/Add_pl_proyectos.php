<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../" . class_bd;
require "../" . Leng;
$bd = new DataBase();

$area    = $_POST['area'];
$proyecto    = $_POST['proyecto'];
$status    = $_POST['status'];
$actividad  = $_POST['actividad'];
$cargo  = $_POST['cargo'];

$where = " WHERE planif_proyecto.codigo = planif_proyecto.codigo ";


if ($area != "TODOS") {
	$where  .= " AND area_proyecto.codigo = '$area' ";
}

if ($proyecto != "TODOS") {
	$where  .= " AND planif_proyecto.codigo = '$proyecto' ";
}

if ($status != "TODOS") {
	$where  .= " AND planif_proyecto.status = '$status' ";
}

if ($cargo != "TODOS") {
	$where  .= " AND planif_proyecto_cargos.cod_cargo = '$cargo' ";
}

if ($actividad != "TODOS") {
	$where   .= " AND planif_actividad.codigo = '$actividad' ";
}

$sql = "SELECT
planif_proyecto.codigo,
planif_proyecto.descripcion,
planif_proyecto.abrev,
Valores(planif_proyecto.`status`) AS `status`
FROM
planif_proyecto
LEFT JOIN planif_actividad ON planif_actividad.cod_proyecto = planif_proyecto.codigo
LEFT JOIN planif_proyecto_cargos ON planif_proyecto_cargos.cod_proyecto = planif_proyecto.codigo
LEFT JOIN area_proyecto ON planif_proyecto.cod_area = area_proyecto.codigo
$where
GROUP BY planif_proyecto.codigo";

// echo $sql;
?><table width="100%" border="0" align="center">
	<tr class="fondo00">
		<th class="etiqueta">Código</th>
		<th class="etiqueta">Proyecto</th>
		<th class="etiqueta">Abreviatura</th>
		<th class="etiqueta">Actividades</th>
		<th class="etiqueta">Cargos</th>
		<th class="etiqueta">Minutos</th>
		<th class="etiqueta">Activo</th>
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
		echo '<tr class="' . $fondo . '" onclick="showDetail(' . $datos["codigo"] . ')">
		<td class="texto">' . $datos["codigo"] . '</td>
		<td class="texto">' . $datos["descripcion"] . '</td>
		<td class="texto">' . $datos["abrev"] . '</td>
		<td class="texto">';
		$sql_actividades = "SELECT
			planif_actividad.codigo,
			planif_actividad.descripcion,
			planif_actividad.obligatoria,
			planif_actividad.minutos
		FROM
			planif_actividad
		WHERE
			cod_proyecto = " . $datos["codigo"] . " AND `status` = 'T'";
		$query_actividades = $bd->consultar($sql_actividades);
		while ($actividad = $bd->obtener_fila($query_actividades, 0)) {
			echo $actividad["descripcion"] . "</br>";
		}
		echo '</td><td class="texto">';
		$sql_cargos = "SELECT
			planif_proyecto_cargos.cod_cargo,
			cargos.descripcion
		FROM
			planif_proyecto_cargos, cargos
		WHERE planif_proyecto_cargos.cod_cargo = cargos.codigo  
		AND cod_proyecto = " . $datos["codigo"];
		$query_cargos = $bd->consultar($sql_cargos);
		while ($cargo = $bd->obtener_fila($query_cargos, 0)) {
			echo $cargo["descripcion"] . "</br>";
		}
		echo '</td><td class="texto">';
		$sql_minutos = "SELECT
			SUM(minutos) minutos
		FROM
			planif_actividad	
		WHERE cod_proyecto = " . $datos["codigo"] . " AND `status` = 'T'";
		$query_minutos = $bd->consultar($sql_minutos);
		$minutos = $bd->obtener_fila($query_minutos, 0);
		echo $minutos["minutos"];
		echo "</td>";
		echo '<td class="texto">' . $datos["status"] . '</td></tr>';
	}; ?>
</table>