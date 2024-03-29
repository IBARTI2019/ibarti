<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = 717;
$mod     =  $_GET['mod'];
$titulo  = " Reporte Documentos " . $leng["cliente"];
$archivo = "reportes/rp_cliente_documento_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //
		var cliente = $("#cliente").val();
		var region = $("#region").val();
		var doc_check = $("#doc_check").val();
		var documento = $("#documento").val();
		var doc_vencimiento = $("#doc_vencimiento").val();
		var doc_vencido = $("#doc_vencido").val();
		var c_activo = $("#c_activo").val();

		var error = 0;
		var errorMessage = ' ';

		if (error == 0) {
			var contenido = "listar";

			ajax = nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_cliente_documento.php", true);
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
			ajax.send("cliente=" + cliente + "&region=" + region + "&documento=" + documento + "&doc_check=" + doc_check + "&doc_vencimiento=" + doc_vencimiento + "&doc_vencido=" + doc_vencido + "&c_activo=" + c_activo + "");

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
			<td width="10%"><?php echo $leng['region'] ?>:</td>
			<td width="14%"><select name="region" id="region" style="width:120px;" onchange="">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_region);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td><?php echo $leng["cliente"]; ?></td>
			<td><select name="cliente" id="cliente" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_cliente);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>

			<td>Documento: </td>
			<td><select name="documento" id="documento" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_documento);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td><?php echo $leng['cliente']; ?> Estatus: </td>
			<td><select name="c_activo" id="c_activo" style="width:120px;">
					<option value="TODOS"> TODOS </option>
					<option value="T"> ACTIVO </option>
					<option value="F"> INACTIVO </option>
				</select></td>
			<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>

		</tr>
		<tr>
			<td>Doc. Checks: </td>
			<td><select name="doc_check" id="doc_check" style="width:120px;">
					<option value="TODOS"> TODOS </option>
					<option value="S"> SI </option>
					<option value="N"> NO </option>
				</select></td>
			<td>Doc. Venc.: </td>
			<td><select name="doc_vencimiento" id="doc_vencimiento" style="width:120px;">
					<option value="TODOS"> TODOS </option>
					<option value="S"> SI </option>
					<option value="N"> NO </option>
				</select></td>
			<td>Vencido: </td>
			<td><select name="doc_vencido" id="doc_vencido" style="width:120px;">
					<option value="TODOS"> TODOS </option>
					<option value="S"> SI </option>
					<option value="N"> NO </option>
				</select></td>
			<td>&nbsp; <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
				<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" />
			</td>
		</tr>
	</table>
	<hr />
	<div class="listar" id="listar">&nbsp;</div>
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