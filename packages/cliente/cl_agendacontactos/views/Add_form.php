<script language="javascript">
	$("#cl_supervision_det_form").on('submit', function(evt) {
		evt.preventDefault();
		save_agenda_det("", "agregar");
	});
</script>
<?php
require "../modelo/supervision_modelo.php";
require "../../../../" . Leng;
$supervision = new supervision;
$cliente    = $_POST['cliente'];
$ubic       = $supervision->get_ubicacion($cliente);
$turno      = $supervision->get_turno();
$cargos      = $supervision->get_cargo();

$superv_det   = $supervision->get_superv_det($cliente);
?><form id="cl_supervision_det_form" name="cl_supervision_det_form" method="post">
	<div class="tabla_sistema">
		<table width="100%" border="0" align="center">
			<tr>
				<th width="24%"><?php echo $leng["ubicacion"]; ?></th>
				<th width="25%">Forma de Contactos:</th>
				<th width="14%">Actividad</th>
				<th width="25%">Fecha </th>
				<th width="25%">Hora </th>
				<th width="12%">Acciones</th>
			</tr>
			<tr>
				<td><select id="superv_ubicacion" required style="width:240px;">
						<option value="">Seleccione...</option>
						<?php
						foreach ($ubic as  $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						}
						?>
					</select></td>
					<td><select id="superv_formacontacto" required style="width:240px;"  onchange="getfcactividades(this.value)">
						<option value="">Seleccione...</option>
						<?php
						foreach ($cargos as  $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						}
						?>
					</select></td>
					<td><select id="superv_actividad" required style="width:240px;">
						<option value="">Seleccione...</option>
					</select></td>
					<td>   
        			<input type="date" id="agenda_fecha" value="<?php echo $ped['fecha'];?>" placeholder="Fecha"
       				 required>
      				</td>
					  <td>   
					  <input type="time" name="hora" value="00:00:00" max="06:30:00" min="08:00:00" step="1">
      				</td>
				
				
				<td align="center"><span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="submit" id="Ingresar_det" value="Ingresar" class="readon art-button" />
					</span></td>
			</tr>
			<?php
			$i     = 0;
			foreach ($superv_det as $datos) {
				$i++;
				$cod_det     = $datos['codigo'];
				echo '<tr>
    					<td><select id="superv_ubicacion' . $cod_det . '"  style="width:260px;" onchange="Cargar_puesto(this.value, \'superv_puesto' . $cod_det . '\')">
							    <option  value="' . $datos["cod_ubicacion"] . '">' . $datos["ubicacion"] . '</option>';
				foreach ($ubic as  $d_det) {
					if ($datos["cod_ubicacion"] <> $d_det[0])
						echo '<option value="' . $d_det[0] . '">' . $d_det[1] . '</option>';
				}
				echo '
							</select></td>
							<td><select id="superv_turno' . $cod_det . '" style="width:240px;">
							     	<option  value="' . $datos["cod_turno"] . '">' . $datos["turno"] . '</option>';
				foreach ($turno as  $d_det) {
					if ($datos["cod_turno"] <> $d_det[0])
						echo '<option value="' . $d_det[0] . '">' . $d_det[1] . '</option>';
				}
				echo '</select></td>
				<td><select id="superv_cargo' . $cod_det . '" style="width:240px;">
							     	<option  value="' . $datos["cod_cargo"] . '">' . $datos["cargo"] . '</option>';
				foreach ($cargos as  $d_det) {
					if ($datos["cod_cargo"] <> $d_det[0])
						echo '<option value="' . $d_det[0] . '">' . $d_det[1] . '</option>';
				}
				echo '</select></td>
										<td align="center"><input type="number" id="superv_cantidad' . $cod_det . '" style="width:100px;" value="' . $datos["cantidad"] . '" min="1"></td> 
										<td align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="save_agenda_det(\'' . $cod_det . '\',\'modificar\')" />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro" border="null" class="imgLink" width="20px" height="20px" onclick="save_agenda_det(\'' . $cod_det . '\',\'borrar\')"/></td>
				</td>
</tr>';
			}
			?>
		</table>
	</div>
</form>