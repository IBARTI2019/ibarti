<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = 592;
$mod     =  $_GET['mod'];
$titulo  = " Reporte Log Cambios de Clave";
$archivo = "reportes/rp_logchangepass_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

		var perfil = $("#perfil").val();
		var status = $("#status").val();
		var usuario = $("#stdID").val();
		var fecha_desde = $("#fecha_desde").val();
		var fecha_hasta = $("#fecha_hasta").val();
		var Nmenu = $("#Nmenu").val();
		var mod = $("#mod").val();
		var archivo = $("#archivo").val();

		var error = 0;
		var errorMessage = ' ';

		if (fechaValida(fecha_desde) != true && fecha_desde != "") {
			var errorMessage = ' Campos De Fecha Inicial Incorrecta ';
			var error = error + 1;
		}
		if (fechaValida(fecha_hasta) != true && fecha_hasta != "") {
			var errorMessage = ' Campos De Fecha Final Incorrectas ';
			var error = error + 1;
		}
		if (perfil == '') {
			var error = error + 1;
			errorMessage = errorMessage + ' \n Debe Seleccionar un Rol ';
		}
		if (error == 0) {

			var parametros = {
				"perfil": perfil,
				"status": status,
				"usuario": usuario,
				"r_cliente": r_cliente,
				"fecha_desde": fecha_desde,
				"fecha_hasta": fecha_hasta,
				"Nmenu": Nmenu,
				"mod": mod,
				"archivo": archivo
			};
			$.ajax({
				data: parametros,
				url: 'ajax_rp/Add_logchangepass.php',
				type: 'post',
				beforeSend: function() {
					$("#img_actualizar").remove();
					$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
				},
				success: function(response) {
					$("#listar").html(response);
					$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				},

				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}

			});

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
			<td width="12%">Fecha Desde:</td>
			<td width="12%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
			<td width="12%">Fecha Hasta:</td>
			<td width="12%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
			<td width="10%">Perfil:</td>
			<td width="14%"><select name="perfil" id="perfil" style="width:120px;" required>
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_perfil);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td width="10%">Estatus: </td>
			<td width="14%"><select name="status" id="status" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<option value="T">ACTIVO</option>
					<option value="F">INACTIVO</option>
				</select></td>
			<td>&nbsp;</td>
			<td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" Add_filtroX()"></td>
		</tr>
		<tr>
			<td>Filtro Usuario:</td>
			<td id="select01">
				<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
					<option value="TODOS"> TODOS</option>
					<option value="codigo"> Código </option>
					<option value="cedula"><?php echo $leng['ci'] ?> </option>
					<option value="nombre">Nombre</option>
					<option value="apellido"> Apellido </option>
				</select>
			</td>
			<td>Usuario:</td>
			<td colspan="6"><input id="stdName" type="text" size="60" />
				<input type="hidden" name="usuario_filter" id="stdID" value="" />
			</td>
			<td>&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo; ?>" />
				<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />

				<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" />
			</td>
		</tr>
	</table>
	<hr />
	<div id="listar">&nbsp;</div>
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
		return "autocompletar/tb/usuario.php?q=" + this.text.value + "&filtro=" + filtroValue + "&r_cliente=" + r_cliente + "&r_rol=" + r_rol + "&usuario=" + usuario + ""
	});
</script>