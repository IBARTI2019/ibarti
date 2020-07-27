<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<link href='libs/fullcalendar/lib/main.css' rel='stylesheet' />
<script type="text/javascript" src="funciones/modal.js"></script>
<script type="text/javascript" src="packages/planif/planif_supervisor/controllers/planificacionCtrl.js"></script>
<script src='libs/fullcalendar/lib/main.js'></script>
<script src='libs/fullcalendar/lib/locales/es.js'></script>

<style>
#supervisor-wrap{
	width: 19vw;
	left: 0px;
	position: absolute;
	height: 470px;
}
#external-events {
	padding: 0 10px;
	left: 0px;
    top: 0px;
	width: 230px;
	border: 1px solid #ccc;
    background: #eee;
    text-align: left;
}
  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
  }

  #external-events .fc-event {
    margin: 3px 0;
    cursor: move;
  }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
  }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

  #calendar-wrap {
    margin-left: 250px;
	min-height:400px;
  }

  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }

</style>

<?php
$Nmenu = '4404';
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
?>
<div id="myModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="cerrarModal()" >&times;</span>
			<span id="modal_titulo"></span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido"></div>
		</div>
	</div>
</div>

<div id="modalRP" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="cancelarActividad()" >&times;</span>
			<span>Agregar Actividad</span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido">
			<table width="90%" align="center">
			<tr>
			<td class="etiqueta"><span>Fecha:</span></td>
      <td id="planf_fechaRP" ></td>
	  <td rowspan="5"><img id="fotoRP" src="" style="width:120px;" alt="Sin Foto">
	  <br>
	  <span id="cedulaRP"></span></td>
		</tr>
    <tr>
   <td class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span> </td>
      <td ><select id="planf_ubicacionRP" required style="width:200px">
            <option value="">Seleccione</option>
		  </select></td>
		  </tr>
		  <tr>
		<td class="etiqueta"><span id="hora_texto">Hora de Inicio:</span> </td>
      <td  ><input type="time" name="hora" id="planf_horaRP" step="30" onchange="updateFecFin()"></td></tr>
	  <tr>
	  <tr>
		<td class="etiqueta"><span id="hora_texto">Hora de Fin:</span> </td>
      <td ><input type="time" name="hora_fin" id="planf_hora_finRP" step="30" readonly="readonly"></td></tr>
		  <tr>
		  <td class="etiqueta"><span id="proyecto_texto">Proyectos:</span> </td>
      	<td id="planf_proyectoRP"></td>
		  </tr>
		  <tr >
		  <td class="etiqueta"><span id="actividad_texto">Adtividades:</span> </td>
		  <td id="planf_actividadRP"></td>
		</tr>
	</table>
	<div align="center" id="guardarActividad"><span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="guardar_actividad" value="Guardar" onclick="saveActividad()" class="readon art-button" />
    </span></div>
	<br>
			</div>
		</div>
		<br>
		<div>
		<label id="titulo_detalle_trab"></label>
		<br><br>
		<div id="calendarTrab" style="width:90%;"></div>
		</div>
		<br>
	</div>
</div>
<div id="Cont_planificacion"></div>

<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />