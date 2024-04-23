<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />

<?php
$Nmenu = '4410';
if (isset($_SESSION['usuario_cod'])) {
  require_once('autentificacion/aut_verifica_menu.php');
  require_once('sql/sql_report.php');
  $us = $_SESSION['usuario_cod'];
} else {
  $us = $_POST['usuario'];
}
?>
<div id="Cont_marcaje">

  <span class="etiqueta_title" id="title_horario">Participantes Check List</span>
  <br>
  <hr>
  <br>
  	<form name="filtros" id="filtros">
		<table width="100%" class="etiqueta">
		<tr>
			<td width="10%">Fecha Desde:</td>
			<td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('filtros', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('filtros', 'fecha_desde');" border="0" width="17px"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('filtros', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('filtros', 'fecha_hasta');" border="0" width="17px"></td>

			<td>Region: </td>
			<td><select name="region" id="region" style="width:120px;" onchange="Add_filtroX()">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_region);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td>Estado: </td>
			<td><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_estado);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
		</tr>
		<tr>

			<td>Cliente:</td>
			<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')" required>
					<?php
					echo $select_cl;
					$query01 = $bd->consultar($sql_cliente);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td>Ubicacion: </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
					<option value="TODOS">TODOS</option>
				</select></td>
			<td>Proyecto:</td>
			<td><select name="proyecto" id="proyecto" style="width:120px;" onchange="cargar_actividades(this.value)" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_proyecto_paticipantes);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td>Actividad:</td>
			<td><select name="actividad" id="actividad" style="width:120px;" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_actividad_paticipantes);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
		</tr>
		<tr>
			<td>Filtro Trab.:</td>
			<td id="select01">
				<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
					<option value="TODOS"> TODOS</option>
					<option value="codigo"> C&oacute;digo </option>
					<option value="cedula"> C&eacute;dula </option>
					<option value="trabajador"> Trabajador </option>
					<option value="nombres"> Nombre </option>
					<option value="apellidos"> Apellido </option>
				</select>
			</td>
			<td>Trabajador:</td>
			<td colspan="4"><input id="stdName" type="text" style="width:220px" disabled="disabled" />
				<input type="hidden" name="trabajador" id="stdID" value="" />
				&nbsp; <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
			</td>
			<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo2; ?>" />
			<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
			<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /> </td>
		</tr>
		</table>
	</form>
  <br>
  <hr>
	<div id="lista" height="100%">&nbsp;</div>
</div>

<div id="myModalCheckList" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="cerrarModalCheckList()">&times;</span>
      <span>Check List Participante</span>
    </div>
    <div class="modal-body">
      <div id="modal_check_list_contenido">
      </div>
    </div>
  </div>
</div>

<input name=" usuario" id="usuario" type="hidden" value="<?php echo $us; ?>" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<script type="text/javascript" src="packages/planif/planif_check_list/controllers/planifCheckListCtrl.js"></script>

<script type="text/javascript">
	r_cliente = $("#r_cliente").val();
	r_rol = $("#r_rol").val();
	usuario = $("#usuario").val();
	filtroValue = $("#paciFiltro").val();

	new Autocomplete("stdName", function() {
		this.setValue = function(id) {
			document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
		}
		if (this.isModified) this.setValue("");
		if (this.value.length < 1) return;
		return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValue + "&r_cliente=" + r_cliente + "&r_rol=" + r_rol + "&usuario=" + usuario + ""
	});
</script>