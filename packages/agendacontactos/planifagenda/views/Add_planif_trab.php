<script language="javascript">
$("#pl_trab_form").on('submit', function(evt){
	 evt.preventDefault();
	 save_planif_det("X01", "agregar");
});
	</script>
<?php
require "../modelo/planificacion_modelo.php";
require "../../planif_modelo/modelo/planif_modelo.php";
require "../../../../".Leng;

$apertura   = $_POST['codigo'];
$ubic   = $_POST['ubicacion'];
$clie   = $_POST['cliente'];
$plan   = new Planificacion;
/// $trab   = $plan->get_planif_det($apertura, $ubic);
$datos_ap = $plan->get_planif_ap($apertura);
$puesto   = $plan->get_ubic_puesto($ubic);
$trab     = $plan->get_trab($clie,$ubic);

$modelo   = new Planif_modelo;
$rotacion = $modelo->get_rotacion('');

echo '<form id="pl_trab_form" name="pl_trab_form" method="post">
<table width="100%" border="0" align="center">
			<tr>
				<th width="22%">'.$leng["trabajador"].'</th>
				<th width="16%">Puesto de Trabajo</th>
				<th width="18%">'.$leng["rotacion"].'</th>
				<th width="12%">posicion</th>
				<th width="26%">Fecha</th>
				<th width="6%">Eventos</th>
			</tr>';
$i = "X01";

	echo '<tr>
					<td><select id="det_ficha'.$i.'" style="width:220px" onchange="save_planif_det('.$i.')" required>
		  					<option value="">Seleccione...</option>';
								foreach ($trab as $datosX){
									echo '<option value="'.$datosX[0].'">('.$datosX[0].') '.$datosX[2].'</option>';
								}
						echo'</select><input type="hidden" id="det_codigo'.$i.'" value="">
						    					<input type="hidden" id="det_metodo'.$i.'" value="agregar">
					<td><select id="det_puesto_trab'.$i.'" style="width:140px" onchange="save_planif_det('.$i.')" required>
		  					<option value="">Seleccione...</option>';
								foreach ($puesto as $datosX){
									echo '<option value="'.$datosX[0].'">'.$datosX[1].'</option>';
								}
						echo'</select></td>
					<td><select id="det_rotacion'.$i.'" style="width:150px" required onchange="cargar_rotacion_posicion(this.value, \'det_posicion'.$i.'\')" >
		  					<option value="">Seleccione...</option>';
								foreach ($rotacion as $datosX){
									echo '<option value="'.$datosX[0].'">'.$datosX[1].'</option>';
								}
							 echo'</select></td>
					<td><select id="det_posicion'.$i.'" style="width:120px" required onchange="save_planif_det('.$i.')">
								<option value="">0</option>
							</select></td>
					<td><input id="det_fec_inicio'.$i.'" type="date" value="'.$datos_ap["fecha_inicio"].'" onchange="save_planif_det('.$i.')" required min="'.$datos_ap["fecha_inicio"].'" max="'.$datos_ap["fecha_fin"].'"> | <input id="det_fec_fin'.$i.'" type="date" value="'.$datos_ap["fecha_fin"].'" onchange="save_planif_det('.$i.')" required
					           min="'.$datos_ap["fecha_inicio"].'" max="'.$datos_ap["fecha_fin"].'"></td>
					<td><span class="art-button-wrapper">
					                    <span class="art-button-l"> </span>
					                    <span class="art-button-r"> </span>
					                <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />
					                </span></td>
				<tr>';
echo '</table>
</form>';
?>
