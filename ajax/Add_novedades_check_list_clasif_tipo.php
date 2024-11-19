<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php'); 
//include_once('../funciones/mensaje_error.php');
$ubicacion = $_POST['codigo'];
?><table width="100%">
	<?php
	if (isset($_POST['perfil'])) {
		$perfil    = $_POST['perfil'];
		// $ficha    = $_POST['ficha'];
	?>
		<td class="etiqueta" width="15%">ACTIVIDAD:</td>
		<td width="25%" id="select06"><select name="proyecto" id="proyecto" style="width:150px;" onchange="getClasif(this.value)">
				<option value="">Seleccione...</option>
				<?php
				$sql01 = "SELECT
				planif_actividad.codigo,
				planif_actividad.descripcion
			FROM
				planif_actividad";
				/*$sql01    = "SELECT
					planif_actividad.codigo,
					planif_actividad.descripcion
				FROM
					-- nov_planif_actividad,
					-- planif_clientes_superv_trab_det_participantes,
					-- planif_clientes_superv_trab_det,
					-- planif_actividad
				-- WHERE
					-- planif_clientes_superv_trab_det_participantes.cod_det = planif_clientes_superv_trab_det.codigo
				-- AND planif_clientes_superv_trab_det.cod_actividad = nov_planif_actividad.cod_actividad
				-- AND nov_planif_actividad.cod_actividad = planif_actividad.codigo 
				-- AND planif_clientes_superv_trab_det_participantes.cod_ficha = '$ficha'";
				*/
				$query01 = $bd->consultar($sql01);
				while ($row01 = $bd->obtener_fila($query01, 0)) {
					echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
				} ?>
			</select> </td>
	<?php
	}

if (isset($_POST['checkListTrab'])){
	?>

<tr>
		<td class="etiqueta" width="15%">CLASIFICACION:</td>
		<td width="25%" id="select04"><select name="clasif" id="clasif" style="width:150px;" onchange="getTipos(this.value)">
				<option value="">Seleccione...</option>
				<?php
				$sql01    = "SELECT nov_clasif.codigo, nov_clasif.descripcion
                               FROM nov_cl_ubicacion , novedades, nov_clasif, nov_perfiles
                              WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion' 
                                AND nov_perfiles.cod_nov_clasif = novedades.cod_nov_clasif
                                AND nov_cl_ubicacion.cod_novedad = novedades.codigo 
                                AND novedades.cod_nov_clasif = nov_clasif.codigo
								AND nov_clasif.campo04 = 'E'
                           GROUP BY novedades.cod_nov_clasif ORDER BY 2 ASC";
				$query01 = $bd->consultar($sql01);
				while ($row01 = $bd->obtener_fila($query01, 0)) {
					echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
				} ?>
			</select> </td>
		<td class="etiqueta">TIPO:</td>
		<td id="select05"><select name="tipo" id="tipo" style="width:150px;" onchange="Add_filtroX()">
				<option value="">Seleccione...</option>
			</select> </td>
	</tr>

	<?php
}else{
?>
	<tr>
		<td class="etiqueta" width="15%">CLASIFICACION:</td>
		<td width="25%" id="select04"><select name="clasif" id="clasif" style="width:150px;" onchange="getTipos(this.value)">
				<option value="">Seleccione...</option>
				<?php
				$sql01    = "SELECT nov_clasif.codigo, nov_clasif.descripcion
                               FROM nov_cl_ubicacion , novedades, nov_clasif, nov_perfiles
                              WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion' 
								-- AND nov_perfiles.cod_perfil = '$perfil'
                                AND nov_perfiles.cod_nov_clasif = novedades.cod_nov_clasif
                                AND nov_cl_ubicacion.cod_novedad = novedades.codigo 
                                AND novedades.cod_nov_clasif = nov_clasif.codigo
								AND nov_clasif.campo04 = 'T'
                           GROUP BY novedades.cod_nov_clasif ORDER BY 2 ASC";
				$query01 = $bd->consultar($sql01);
				while ($row01 = $bd->obtener_fila($query01, 0)) {
					echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
				} ?>
			</select> </td>
		<td class="etiqueta">TIPO:</td>
		<td id="select05"><select name="tipo" id="tipo" style="width:150px;" onchange="Add_filtroX()">
				<option value="">Seleccione...</option>
				<?php
				$sql01    = "SELECT nov_tipo.codigo, nov_tipo.descripcion
                               FROM nov_cl_ubicacion , novedades, nov_tipo
                              WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion' 
                                AND nov_cl_ubicacion.cod_novedad = novedades.codigo 
                                AND novedades.cod_nov_tipo = nov_tipo.codigo
                           GROUP BY novedades.cod_nov_tipo ORDER BY 2 ASC";

				$query01 = $bd->consultar($sql01);
				while ($row01 = $bd->obtener_fila($query01, 0)) {
					echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
				} ?>
			</select> </td>
	</tr>
<?php
	}
?>
</table>