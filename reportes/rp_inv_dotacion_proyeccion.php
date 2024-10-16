<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = '576';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_inv_dotacion_proyeccion_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " PROYECCION DE DOTACION DE TRABAJADOR ";

$sql01   = "SELECT control.dias_proyeccion FROM control";
$query01 = $bd->consultar($sql01);
$row01   = $bd->obtener_fila($query01, 0);
$d_proyeccion = $row01[0]; ?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

		var fecha_desde = document.getElementById("fecha_desde").value;
		var d_proyeccion = document.getElementById("d_proyeccion").value;
		var rol = document.getElementById("rol").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var estado = document.getElementById("estado").value;
		var contrato = document.getElementById("contrato").value;
		var linea = document.getElementById("linea").value;
		var sub_linea = document.getElementById("sub_linea").value;
		var trabajador = document.getElementById("stdID").value;

		var error = 0;
		var errorMessage = ' ';

		if (fechaValida(fecha_desde) != true) {
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error = error + 1;
		}

		if (rol == '') {
			var error = error + 1;
			errorMessage = errorMessage + ' \n Debe Seleccionar un Rol ';
		}


		if (error == 0) {
			var contenido = "contenido";

			ajax = nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_inv_dotacion_proyeccion.php", true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 1 || ajax.readyState == 2 || ajax.readyState == 3) {
					document.getElementById(contenido).innerHTML = '<img src="imagenes/loading.gif" />';
					document.getElementById("cont_img").innerHTML =
						'<img src="imagenes/loading.gif" onclick="" class="imgLink" />';
				}
				if (ajax.readyState == 4) {
					document.getElementById(contenido).innerHTML = ajax.responseText;
					document.getElementById("cont_img").innerHTML =
						'<img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()">';
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("rol=" + rol + "&d_proyeccion=" + d_proyeccion + "&estado=" + estado + "&contrato=" + contrato + "&cliente=" + cliente + "&ubicacion=" + ubicacion + "&linea=" + linea + "&sub_linea=" + sub_linea + "&trabajador=" + trabajador + "&fecha_desde=" + fecha_desde + "");

		} else {
			alert(errorMessage);
		}
	}
</script>
<div align="center" class="etiqueta_title"><?php echo $titulo; ?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo; ?>" method="post" target="_blank">
	<hr />
	<table width="100%" class="etiqueta">
		<tr>
			<td width="10%">Fecha:</td>
			<td width="16%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="12" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
			<td width="10%">Dias Proyec.:</td>
			<td width="14%" id="integer01"><input type="text" id="d_proyeccion" name="d_proyeccion" maxlength="12" size="10" value="<?php echo $d_proyeccion; ?>" /></td>
			<td width="10%"><?php echo $leng['rol'] ?>: </td>
			<td width="14%"><select name="rol" id="rol" style="width:120px;" required>
					<?php
					echo $select_rol;
					$query01 = $bd->consultar($sql_rol);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td><?php echo $leng['estado'] ?>: </td>
			<td><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_rol);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
		<tr>
			<td class="etiqueta"><?php echo $leng['cliente'] ?> :</td>
			<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')">
					<option value="TODOS"> TODOS</option>
					<?php $query02 = $bd->consultar($sql_cliente);
					while ($row02 = $bd->obtener_fila($query02, 0)) {
						echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
					} ?>
				</select></td>
			<td class="etiqueta"><?php echo $leng['ubicacion']; ?> : </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
					<option value="TODOS">TODOS</option>
				</select></td>
			<td>Linea: </td>
			<td><select name="linea" id="linea" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_linea);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td>Sub Linea:</td>
			<td><select name="sub_linea" id="sub_linea" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_sub_lineas);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
		</tr>
		<tr>
			<td><?php echo $leng['contrato'] ?>: </td>
			<td><select name="contrato" id="contrato" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_contrato);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td>Filtro <?php echo $leng['trab'] ?>.:</td>
			<td><select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
					<option value="TODOS"> TODOS</option>
					<option value="codigo"> Codigo </option>
					<option value="cedula"> C&eacute;dula </option>
					<option value="trabajador"> Trabajador </option>
				</select></td>
			<td><?php echo $leng['trabajador'] ?>:</td>
			<td colspan="3"><input id="stdName" type="text" size="35" disabled="disabled" />
				<input type="hidden" name="trabajador" id="stdID" value="" /></td>
			<td>&nbsp;
				<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
				<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /></td>
		</tr>
	</table>
	<hr />
	<div class="listar" id="contenido">&nbsp;</div>
	<div align="center"><br />
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" class="readon art-button">
		</span>&nbsp;


		<input type="submit" name="procesar" id="procesar" hidden="hidden">
		<input type="text" name="reporte" id="reporte" hidden="hidden">

		<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

		<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0" onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
	</div>
</form>
<script type="text/javascript">
	var integer01 = new Spry.Widget.ValidationTextField("integer01", "integer", {
		validateOn: ["blur", "change"],
		useCharacterMasking: false
	});

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