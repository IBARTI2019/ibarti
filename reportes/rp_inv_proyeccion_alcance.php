<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = '578';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_inv_proyeccion_alcance_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " PROYECCION DE DOTACION DE ALCANCES ";

$sql01   = "SELECT control.dias_proyeccion FROM control";
$query01 = $bd->consultar($sql01);
$row01   = $bd->obtener_fila($query01, 0);
$d_proyeccion = $row01[0]; 

if ($_SESSION['r_cliente'] == "F") {
	$sql_cliente    = "SELECT clientes.codigo, clientes.nombre
						   FROM clientes WHERE status = 'T'
						  ORDER BY 2 ASC ";
} else {
	$sql_cliente = " SELECT clientes.codigo, clientes.nombre
							FROM clientes
						WHERE clientes.codigo IN (SELECT DISTINCT clientes_ubicacion.cod_cliente
							FROM usuario_clientes, clientes_ubicacion
						WHERE usuario_clientes.cod_usuario = '" . $_SESSION['usuario_cod'] . "'
							AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo)
						WHERE status = 'T'
						ORDER BY 2 ASC ";
}
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

		var fecha_desde = document.getElementById("fecha_desde").value;
		var d_proyeccion = document.getElementById("d_proyeccion").value;
		var region = document.getElementById("region").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var estado = document.getElementById("estado").value;
		var linea = document.getElementById("linea").value;
		var sub_linea = document.getElementById("sub_linea").value;
		var producto = document.getElementById("stdID").value;

		var error = 0;
		var errorMessage = ' ';

		if (fechaValida(fecha_desde) != true) {
			var errorMessage = ' Campo De Fecha es obligatorio ';
			var error = error + 1;
		}

		if (region == '') {
			var error = error + 1;
			errorMessage = errorMessage + ' \n Debe Seleccionar un region ';
		}


		if (error == 0) {
			var contenido = "contenido";

			ajax = nuevoAjax();
			ajax.open("POST", "ajax_rp/Add_inv_proyeccion_alcance.php", true);
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
			ajax.send("region=" + region + "&d_proyeccion=" + d_proyeccion + "&estado=" + estado + "&cliente=" + cliente + "&ubicacion=" + ubicacion + "&linea=" + linea + "&sub_linea=" + sub_linea + "&fecha_desde=" + fecha_desde + "&producto=" + producto + "");

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
			<td width="10%"><?php echo $leng['region'] ?>: </td>
			<td width="14%"><select name="region" id="region" style="width:120px;" required>
					<option value="TODOS">TODOS</option>
					<?php
					echo $select_region;
					$query01 = $bd->consultar($sql_region);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>
				</select></td>
			<td><?php echo $leng['estado'] ?>: </td>
			<td><select name="estado" id="estado" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_estado);
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
				</select><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
				<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /></td>
		</tr>
		<tr>
			<td colspan="4">
			<td>Producto:</td>
			<td colspan="3">
				<input type="text" id="ped_producto" name="producto" value="" placeholder="Ingrese Dato del <?php echo $leng['producto']; ?>" style="width:330px" />
				<input type="hidden" id="stdID">
			</td>
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


<script language="JavaScript" type="text/javascript">
	new Autocomplete("ped_producto", function() {
		this.setValue = function(id) {
			$("#stdID").val(id);
		}
		if (this.value.length < 1) return;
		ubic = $("#ubicacion").val();
		return "autocompletar/tb/producto_base_serial.php?q=" + this.text.value + "&filtro=codigo";
	});
</script>