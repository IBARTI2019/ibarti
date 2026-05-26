<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = '601';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_inv_asignacion_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " REPORTE DE ASIGNACIONES Y DEVOLUCIONES ";
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() {

		var fecha_desde = document.getElementById("fecha_desde").value;
		var fecha_hasta = document.getElementById("fecha_hasta").value;
		var producto = document.getElementById("producto").value;
		var linea = document.getElementById("linea").value;
		var sub_linea = document.getElementById("sub_linea").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var tipo = document.getElementById("tipo").value;
		var trabajador = document.getElementById("stdID").value;
        var almacen = document.getElementById("almacen").value;
		var error = 0;
		var errorMessage = ' ';
		if (fechaValida(fecha_desde) != true || fechaValida(fecha_hasta) != true) {
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error = error + 1;
		}

		if (error == 0) {
			var parametros = {
				"linea": linea,
				"sub_linea": sub_linea,
				"producto": producto,
				"tipo": tipo,
				"trabajador": trabajador,
				"fecha_desde": fecha_desde,
				"fecha_hasta": fecha_hasta,
				"cliente": cliente,
				"ubicacion": ubicacion,
				"almacen"  :almacen
			}
			$.ajax({
				data: parametros,
				url: 'ajax_rp/Add_inv_asignacion.php',
				type: 'post',
				beforeSend: function() {
					$(".listar").html('<img src="imagenes/loading.gif" />');
					document.getElementById("cont_img").innerHTML =
						'<img src="imagenes/loading.gif" onclick="" class="imgLink" />';
				},
				success: function(response) {
					$(".listar").html(response);
					document.getElementById("cont_img").innerHTML =
						'<img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()">';

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
			<td width="10%">Fecha Desde:</td>
			<td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
			<td width="10%">Tipo: </td>
			<td width="14%">
				<select name="tipo" id="tipo" style="width:120px;" onchange="Add_filtroX()">
					<option value="TODOS">TODOS</option>
					<option value="ASIGNACION">ASIGNACION</option>
					<option value="DEVOLUCION">DEVOLUCION</option>
				</select>
			</td>
						<td>Almacen:</td>
			<td>
				<select name="almacen" id="almacen"  style="width:120px;">
				<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_almacen);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>

				</select>
			</td>
			<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
		</tr>
		<tr>
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
<td>Producto: </td>
			<td><select name="producto" id="producto" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_producto);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[2] . '">' . $row01[1] . ' (' . $row01[2] . ')</option>';
					}
					?>
				</select></td>
	
			<td>Cliente:</td>
			<td><select name="cliente" id="cliente" onchange="llenar_ubicacion(this.value)" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_cliente);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>

				</select></td>
							</tr>
		<tr>
			<td>Ubicacion:</td>
			<td><select name="ubicacion" id="ubicacion" style="width:120px;">
					<option value="TODOS">TODOS</option>
				</select></td>
			<td>Filtro <?php echo $leng['trab'] ?>.:</td>
			<td><select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
					<option value="TODOS"> TODOS</option>
					<option value="codigo"> Codigo </option>
					<option value="cedula"> C&eacute;dula </option>
					<option value="nombre"> Nombre </option>
				</select></td>
			<td><?php echo $leng['trabajador'] ?>:</td>
			<td colspan="3"><input id="stdName" type="text" size="35" disabled="disabled" />
				<input type="hidden" name="trabajador" id="stdID" value="" />
            </td>
           
			<td>&nbsp;
				<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /> </td>
		</tr>
	</table>
	<hr />
	<div class="listar">&nbsp;</div>
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
			document.getElementById("stdID").value = id;
		}
		if (this.isModified) this.setValue("");
		if (this.value.length < 1) return;
		return "autocompletar/tb/trabajador.php?q=" + this.text.value + "&filtro=" + filtroValue + "&r_cliente=" + r_cliente + "&r_rol=" + r_rol + "&usuario=" + usuario + ""
	});

	function llenar_ubicacion(cliente) {
		$('#ubicacion').html('');
		var estado = 'TODOS';
		var ciudad = 'TODOS';

		var parametros = {
			'cliente': cliente,
			'estado': estado,
			'ciudad': ciudad
		};
		if (cliente != 'TODOS') {
			$.ajax({
				data: parametros,
				url: 'packages/clientes_rp/views/Get_ubicacion.php',
				type: 'post',
				success: function(response) {
					var datos = JSON.parse(response);

					$('#ubicacion').append('<option value="TODOS">TODOS</option>');
					datos.forEach((res, i) => {
						$('#ubicacion').append("<option value='" + res[0] + "'>" + res[1] + "</option>");

					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		} else {
			$('#ubicacion').append('<option value= "TODOS">TODOS</option>');
		}
	}
</script>
