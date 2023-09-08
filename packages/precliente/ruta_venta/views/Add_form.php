<!-- <script language="javascript">
	$("#cl_rutaventa_det_form").on('submit', function(evt) {
		evt.preventDefault();
		save_rutaventa("", "agregar");
	});
</script> -->
<?php
require "../modelo/rutaventa_modelo.php";
require "../../../../" . Leng;
$rutaventa = new Rutaventa;
$precliente    = $_POST['precliente'];
$rutas       = $rutaventa->get_ruta($precliente);
$rutaventa_det   = $rutaventa->get_ruta_det($precliente);
?>
<form id="cl_rutaventa_det_form" name="cl_rutaventa_det_form" method="post">
	<div class="tabla_sistema">
		<table width="100%" border="0" align="center">
			<tr>
				<th width="20%">Ruta de Venta</th>
				<th width="20%">Sub Ruta de Venta</th>
				<th width="45%">Comentario:</th>
				<th width="15%">Usuario</th>
			</tr>
			<!-- <tr>
				<td><select id="ruta_de_venta" required style="width:300px;">
						<option value="">Seleccione...</option>
						<?php
						foreach ($rutas as  $datos) {
							echo '<option value="' . $datos[0] . '">' . $datos[1] . '</option>';
						}
						?>
					</select></td>
				<td>
					<textarea  id="ruta_comentario" cols="60" rows="1"></textarea>
				</td>
				<td align="center"><span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="submit" id="Ingresar_det" value="Ingresar" class="readon art-button" />
					</span></td>
			</tr> -->
			<?php
			$i     = 0;
			foreach ($rutaventa_det as $datos) {
				$i++;
				$cod_det = $datos['codigo'];
				echo 
					'<tr>
						<td>
							' . $datos['rutaventa'] . '
						</td>
						<td>
						' . $datos['subrutaventa'] . '
						</td>
						<td>
							<textarea disabled="disabled" id="ruta_comentario" cols="60" rows="2">' . $datos['comentario'] . '</textarea>
						</td>
						<td>
							' . $datos['usuario'] . '
						</td>
					</tr>';
			}
			?>
		</table>
		<?php if($cl['venta_cerrada'] == 'F') { ?>
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input 
					type="button" name="c_cerrar_venta" id="c_cerrar_venta" value="Cerrar Ruta Venta" class="readon art-button" 
					onclick="cerrar_venta()"
				/>
			</span>&nbsp;
		<?php } ?>
	</div>
</form>