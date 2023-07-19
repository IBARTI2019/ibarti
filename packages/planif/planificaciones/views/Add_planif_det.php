<?php
require "../modelo/planificacion_modelo.php";
require "../../planif_modelo/modelo/planif_modelo.php";
require "../../../../".Leng;

$apertura   = $_POST['codigo'];
$ubic   = $_POST['ubicacion'];
$plan   = new Planificacion;
$trab   = $plan->get_planif_det($apertura, $ubic);
$mod   = $plan->get_ultima_mod($apertura, $ubic);
$puesto = $plan->get_ubic_puesto($ubic);

$modelo   = new Planif_modelo;
$rotacion = $modelo->get_rotacion('');

echo '</br><div align="center" class="etiqueta_title">Planificacion Detalle</div>
<div align="right">
	<span class="art-button-wrapper">
	<span class="art-button-l"> </span>
	<span class="art-button-r"> </span>
	<input type="button" id="replicar" value="Replicar Rotacion" onClick="replicar_rot()" class="readon art-button" />
	</span>
</div>
<div align="right"><span class="etiqueta">Ultima Modificacion: </span> '.$mod["fecha"].' ('.$mod["us_mod"].')</div>
<div align="right"><span class="etiqueta">Nro. de Trabajadores sin planificar: <h6 id="cantidad_sin_planif"></h6></div>
<table width="100%" border="0" align="center">
<tr>
<th width="22%">'.$leng["trabajador"].' <img class="imgLink"
src="imagenes\ico_agregar.ico" title="Agregar '.$leng["trabajador"].'"
onclick="B_planif_trab()" width="15px" height="15px"></th>
<th width="18%">Puesto de Trabajo</th>
<th width="12%">'.$leng["rotacion"].'</th>
<th width="8%">posicion</th>
<th width="28%">Fecha</th>
<th width="12%">Eventos</th>
</tr>';
$i = 0;
foreach ($trab as  $datos)
{
	if($datos["codigo"] == ""){
		$metodo = "agregar";
	}else{
		$metodo = "modificar";
	}
	if($datos["vetado"] == "NO"){
		$readonly = "";
		$disabled = "";
	}else{
		$readonly = "readonly";
		$disabled = "disabled";
	}
	$i++;
	echo '<tr>
	<td>('.$datos["cod_ficha"].') - '.$datos["trabajador"].'<input type="hidden" id="det_codigo'.$i.'" value="'.$datos["codigo"].'">
	<input type="hidden" id="det_ficha'.$i.'" value="'.$datos["cod_ficha"].'">
	<input type="hidden" id="det_metodo'.$i.'" value="'.$metodo.'"></td>
	<td><select id="det_puesto_trab'.$i.'" style="width:140px" '.$disabled.' required>
	<option value="'.$datos["cod_puesto_trabajo"].'">'.$datos["puesto_trabajo"].'</option>';
	foreach ($puesto as $datosX){
		echo '<option value="'.$datosX[0].'">'.$datosX[1].'</option>';
	}
	echo'</select></td>
	<td><select id="det_rotacion'.$i.'" style="width:150px" required onchange="cargar_rotacion_posicion(this.value, \'det_posicion'.$i.'\')" '.$disabled.'>
	<option value="'.$datos["cod_rotacion"].'">'.$datos["rotacion"].'</option>';
	foreach ($rotacion as $datosX){
		echo '<option value="'.$datosX[0].'">'.$datosX[1].'</option>';
	}
	echo'</select></td>
	<td><select id="det_posicion'.$i.'" style="width:120px" required '.$disabled.'>
	<option value="'.$datos["posicion_inicio"].'">('.($datos["posicion_inicio"]+1).') - '.$datos["turno"].'</option>';
	$rotacion_det = $modelo->get_rotacion_det($datos["cod_rotacion"]);
	$i2 = 0;
	foreach ($rotacion_det as $datosX){
		echo '<option value="'.$i2.'">'.($i2+1).' - ('.$datosX["abrev"].')</option>';
		$i2++;
	}
	echo '</select></td>
	<td><input id="det_fec_inicio'.$i.'" type="date" value="'.$datos["fecha_inicio"].'" required min="'.$datos["ap_fecha_inicio"].'" max="'.$datos["ap_fecha_fin"].'" '.$readonly.'> | <input id="det_fec_fin'.$i.'" type="date" value="'.$datos["fecha_fin"].'" required
	min="'.$datos["ap_fecha_inicio"].'" max="'.$datos["ap_fecha_fin"].'" '.$readonly.'></td>
	<td>
	<img src="imagenes/actualizar.bmp" width="16px" height="16px" onClick="save_planif_det('.$i.')"  alt="Guardar" title="Guardar" class="imgLink">
	<img src="imagenes/detalle.bmp" width="16px" height="16px" onClick="cargar_planif_trab_det('.$i.', \'NO\')"  alt="Detalle" title="Cargar Detalle" class="imgLink">
	<img src="imagenes/borrar.bmp" width="16px" height="16px" onClick="Borrar_trab_det('.$i.')"  alt="Borrar" title="Borrar Registro" class="imgLink">
	<img src="imagenes/excel.gif" width="16px" height="16px" onClick="rp_planif_trab('.$i.', \'excel\')"  alt="Detalle" title="Cargar Detalle" class="imgLink">
	<img src="imagenes/pdf.gif" width="16px" height="16px" onClick="rp_planif_trab('.$i.', \'pdf\')"  alt="Detalle" title="Cargar Detalle" class="imgLink">
	</td>
	<tr>';
}
echo '</table>';

//  PEDINETE EL CALENDARIO <img src="imagenes/calendario.gif" onClick="Calendario('.$i.')"  alt="Calendario" title="Cargar Calendario" class="imgLink">
// Regenerar Detalle Borrar
?>
