<?php
require "../modelo/life_line_modelo.php";
require "../../../../".Leng;

$ubicacion   = $_POST['ubicacion'];
$plan   = new LifeLine;
$actividades   = $plan->get_planif_det($ubicacion);

echo '</br><div align="center" class="etiqueta_title">Linea de Vida Detalle</div>
<img class="imgLink"
src="imagenes\ico_agregar.ico" title="Agregar Actividad"
onclick="B_add_actividad()" width="15px" height="15px">
<table width="100%" border="0" align="center">
<tr>
<th width="50%">Actividad</th>
<th width="20">Hora inicio</th>
<th width="20%">hora fin</th>
<th width="10%"></th>
</tr>';
$i = 0;
foreach ($actividades as  $datos)
{
	if($datos["codigo"] == ""){
		$metodo = "agregar";
	}else{
		$metodo = "modificar";
	}
	$i++;
	echo '<tr>
	<td>'.$datos["actividad"].'
		<input type="hidden" id="det_codigo'.$i.'" value="'.$datos["codigo"].'">
		<input type="hidden" id="det_act'.$i.'" value="'.$datos["cod_actividad"].'">
		<input type="hidden" id="det_metodo'.$i.'" value="'.$metodo.'">
	</td>
	<td><input id="det_hora_inicio'.$i.'" type="time" value="'.$datos["hora_inicio"].'" required>
	</td>
	<td>
	<input id="det_hora_fin'.$i.'" type="time" value="'.$datos["hora_fin"].'" required>
	</td>
	<td>
	<img src="imagenes/actualizar.bmp" width="16px" height="16px" onClick="save_act_det('.$i.')"  alt="Guardar" title="Guardar" class="imgLink">
	<img src="imagenes/borrar.bmp" width="16px" height="16px" onClick="Borrar_act_det('.$i.')"  alt="Borrar" title="Borrar Registro" class="imgLink">
	</td>
	<tr>';
}
echo '</table>';

//  PEDINETE EL CALENDARIO <img src="imagenes/calendario.gif" onClick="Calendario('.$i.')"  alt="Calendario" title="Cargar Calendario" class="imgLink">
// Regenerar Detalle Borrar
?>
