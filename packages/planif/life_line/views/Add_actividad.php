<script language="javascript">
$("#pl_act_form").on('submit', function(evt){
	 evt.preventDefault();
	 save_act_det("X01");
});
	</script>
<?php
require "../modelo/life_line_modelo.php";

$ubic   = $_POST['ubicacion'];
$plan   = new LifeLine;

$actividades     = $plan->get_actividades();

echo '<form id="pl_act_form" name="pl_act_form" method="post">
<table width="100%" border="0" align="center">
			<tr>
				<th width="50%">Actividad</th>
				<th width="20%">Hora inicio</th>
				<th width="20%">Hora fin</th>
				<th width="10%"></th>
			</tr>';
$i = "X01";

	echo '<tr>
					<td align="center"><select id="det_act'.$i.'" style="width:400px" required>
		  					<option value="">Seleccione...</option>';
								foreach ($actividades as $datosX){
									echo '<option value="'.$datosX[0].'">('.$datosX[0].') '.$datosX[1].'</option>';
								}
						echo'</select><input type="hidden" id="det_codigo'.$i.'" value="">
						    					<input type="hidden" id="det_metodo'.$i.'" value="agregar">
					<td align="center"><input id="det_hora_inicio'.$i.'" type="time" required></td>
					<td><input id="det_hora_fin'.$i.'" type="time" required></td>
					<td><span class="art-button-wrapper">
					                    <span class="art-button-l"> </span>
					                    <span class="art-button-r"> </span>
					                <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />
					                </span></td>
				<tr>';
echo '</table>
</form>';
?>
