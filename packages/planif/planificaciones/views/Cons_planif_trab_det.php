<script language="javascript">
	$("#pl_trab_det_form").on('submit', function(evt){
		evt.preventDefault();
		save_planif_trab_det("", "agregar");
	});
</script>
<?php
require "../modelo/planificacion_modelo.php";
require "../../planif_modelo/modelo/planif_modelo.php";
require "../../../../".Leng;
$modelo          = new Planif_modelo;
$planificacion   = new Planificacion;

$cod_pl_trab     = $_POST['codigo'];

$datos_ap    = $planificacion->get_planif_trab_ap($cod_pl_trab);
$datos_det   = $planificacion->get_planif_trab_det($cod_pl_trab);
$turno       = $modelo->get_turno('');

$ubicaciones =  $planificacion->get_planif_ap_ubic($datos_ap["cod_cliente"], $datos_ap["cod_planif_cl"]);
$puesto      =  $planificacion->get_ubic_puesto($datos_ap["cod_ubicacion"]);

if($datos_ap["vetado"] == "NO" ){
	$disabled = "";
}else{
	$disabled = "disabled";
}
?>
<div align="center" class="etiqueta_title"></div> <hr />
<table width="100%" align="center">
	<tr>
		<td width="13%" class="etiqueta"><span id="apertura_texto">Apertura Planificacion</span></td>
		<td width="20%"><?php echo $datos_ap["fecha_inicio"].' - '.$datos_ap["fecha_fin"];?></td>
		<td width="14%" class="etiqueta"><?php echo $leng['trabajador']?></td>
		<td width="20%"><?php echo $datos_ap["ap_nombre"];?></td>
		<td width="13%" class="etiqueta"><?php echo $leng['rotacion']?></td>
		<td width="20%"><?php echo $datos_ap["rotacion"];?></td>
	</tr>
	<tr>
		<td class="etiqueta"><?php echo $leng['cliente']?></td>
		<td><?php echo $datos_ap["cliente"];?></td>
		<td class="etiqueta"><?php echo $leng['ubicacion']?></td>
		<td><?php echo $datos_ap["ubicacion"];?></td>
		<td class="etiqueta">Puesto de Trabajo</td>
		<td><?php echo $datos_ap["puesto_trabajo"];?></td>
	</tr>
</table>
</div><hr />
<form id="pl_trab_det_form" name="pl_trab_det_form" method="post">
	<div class="tabla_sistema listar">
		<table width="100%" border="0" align="center">
			<tr>
				<td colspan="6"></hr></td>
			</tr>
			<tr>
				<th width="10%">Fecha</th>
				<th width="20%"><?php echo $leng["cliente"];?></th>
				<th width="20%"><?php echo $leng["ubicacion"];?></th>
				<th width="20%">Puesto Trabajo</th>
				<th width="20%">Turno:</th>
				<th width="8%"><img src="imagenes/loading2.gif" width="30px" height="30px"/></th>
			</tr>
			<tr>
				<td><input type="date" id="pl_trab_fecha" required min="<?php echo $datos_ap["fecha_inicio"];?>" max="<?php echo $datos_ap["fecha_fin"];?>">
					<input type="hidden" id="pl_trab_ficha" value="<?php echo $datos_ap["cod_ficha"];?>" />
					<input type="hidden" id="planif_cl_trab" value="<?php echo $cod_pl_trab;?>"	/>
				</td>
				<td><select id="pl_trab_cliente" style="width:160px;" required>
					<option value="<?php echo $datos_ap["cod_cliente"];?>"><?php echo $datos_ap["cliente"];?></option>
				</select>
			</td>
			<td><select id="pl_trab_ubicacion" style="width:160px;" onchange="Cargar_puesto(this.value, 'pl_trab_puesto_trab')" required>
				<option value="<?php echo $datos_ap["cod_ubicacion"];?>"><?php echo $datos_ap["ubicacion"];?></option>
				<?php
				foreach ($ubicaciones as  $datos){
					if($datos[0] <> $datos_ap["cod_ubicacion"])
						echo '<option value="'.$datos[0].'">'.$datos[1].' '.$datos[3].'</option>';
				}?>
			</select>
		</td>
		<td><select id="pl_trab_puesto_trab" style="width:160px;" required>
			<option value="<?php echo $datos_ap["cod_puesto_trabajo"];?>"><?php echo $datos_ap["puesto_trabajo"];?></option>
			<?php
			foreach ($puesto as  $datos) {
				if($datos[0] <> $datos_ap["cod_puesto_trabajo"])
					echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
			}?>
		</select>
	</td>
	<td><select id="pl_trab_turno" style="width:160px;" required>
		<option  value="">Seleccione...</option>
		<?php
		foreach ($turno as  $datos) {
			echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
		}
		?>
	</select></td>
	<td align="center"><span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<?php 
		echo '<input type="submit"  id="Ingresar_det" value="Ingresar" class="readon art-button" '.$disabled.'/>'; 
		?>
	</span></td>
</tr>
<?php
$i     = 0;
foreach ($datos_det as $datos) {
	$i     = $datos['codigo'];
	echo '<tr>
	<td>'.$datos["fecha"].' - '.$datos["d_semana"].'<input type="hidden" id="pl_trab_ficha'.$i.'" value="'.$datos["cod_ficha"].'"	/>
	<input type="hidden" id="pl_trab_fecha'.$i.'" value="'.$datos["fecha"].'"	/></td>
	<td>'.longitudMax($datos["cliente"]).'<input type="hidden" id="pl_trab_cliente'.$i.'" value="'.$datos["cod_cliente"].'"	/></td>
	<td><select id="pl_trab_ubicacion'.$i.'" style="width:160px;"  onchange="Cargar_puesto(this.value, \'pl_trab_puesto_trab'.$i.'\')" >
	<option  value="'.$datos["cod_ubicacion"].'">'.$datos["ubicacion"].'</option>';
	foreach ($ubicaciones as $d_det) {
		if($datos["cod_ubicacion"] <> $d_det[0])
			echo '<option value="'.$d_det[0].'">'.$d_det[1].'</option>';
	}
	echo '</select></td>
	<td><select id="pl_trab_puesto_trab'.$i.'" style="width:160px;">
	<option  value="'.$datos["cod_puesto_trabajo"].'">'.$datos["puesto_trabajo"].'</option>';
	$puestoX      = $planificacion->get_ubic_puesto($datos["cod_ubicacion"]);
	foreach ($puestoX as $d_det) {
		if($datos["cod_puesto_trabajo"] <> $d_det[0])
			echo '<option value="'.$d_det[0].'">'.$d_det[1].'</option>';
	}
	echo '</select></td>
	<td><select id="pl_trab_turno'.$i.'" style="width:160px;">
	<option  value="'.$datos["cod_turno"].'">'.$datos["turno"].'</option>';
	foreach ($turno as $d_det) {
		if($datos["cod_turno"] <> $d_det[0])
			echo '<option value="'.$d_det[0].'">'.$d_det[1].'</option>';
	}
	echo '</select></td>
	<td align="center">';
	if($datos_ap['vetado'] == "NO" ){  
		echo '<img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="save_planif_trab_det(\''.$i.'\',\'modificar\')"/>&nbsp;';
	}
	echo '<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro" border="null" class="imgLink" width="20px" height="20px" onclick="save_planif_trab_det(\''.$i.'\',\'borrar\')"/></td>
	</tr>';
}
?></table>
<div />
</form>
