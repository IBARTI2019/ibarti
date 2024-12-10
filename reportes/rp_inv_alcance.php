<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>
<?php
$Nmenu   = '581';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_inv_dotacion_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " DOTACIONES DE ALCANCE ";

?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

		var fecha_desde = document.getElementById("fecha_desde").value;
		var fecha_hasta = document.getElementById("fecha_hasta").value;
		var linea = document.getElementById("linea").value;
		var sub_linea = document.getElementById("sub_linea").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var error = 0;
		var errorMessage = ' ';
		var allFechas = false;

		if(fecha_desde == "" && fecha_hasta == ""){
			allFechas = true;
		} else if (fechaValida(fecha_desde) != true || fechaValida(fecha_hasta) != true) {
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error = error + 1;
		}

		if (cliente == '') {
			var error = error + 1;
			errorMessage = errorMessage + ' \n Debe Seleccionar un Cliente ';
		}

		if (error == 0) {
			var contenido = "contenido";
			var parametros = {
				"linea": linea,
				"sub_linea": sub_linea,
				"cliente": cliente,
				"ubicacion": ubicacion,
				"allFechas": allFechas
			}

			if(allFechas == false){
				parametros["fecha_desde"] = fecha_desde;
				parametros["fecha_hasta"] = fecha_hasta;
			}

			$.ajax({
				data: parametros,
				url: 'ajax_rp/Add_inv_alcance.php',
				type: 'post',
				beforeSend: function() {
					$("#contenido").html('<img src="imagenes/loading.gif" />');
					document.getElementById("cont_img").innerHTML =
						'<img src="imagenes/loading.gif" onclick="" class="imgLink" />';
				},
				success: function(response) {
					$("#contenido").html(response);
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

	function Ver_Detalle(cliente, ubicacion, sub_linea, cod_ubicacion, cod_sub_linea){
		var errorMessage = '';
		var fecha_desde = document.getElementById("fecha_desde").value;
		var fecha_hasta = document.getElementById("fecha_hasta").value;
		var allFechas = false;

		if(fecha_desde == "" && fecha_hasta == ""){
			allFechas = true;
		}
		var parametros = {"cod_ubicacion":cod_ubicacion, "cod_sub_linea":cod_sub_linea, "allFechas": allFechas};
		
		if(allFechas == false){
			parametros["fecha_desde"] = fecha_desde;
			parametros["fecha_hasta"] = fecha_hasta;
		}

		if(errorMessage == ''){
			$.ajax({
				data:  parametros,
				url:   'ajax_rp/Add_inv_alcance_detalle.php',
				type:  'GET',
				beforeSend: function(){
					$("#modal_contenidoDetalle").html('');
					$('#modalDetalle').show();
					$("#Detalle").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px"> Procesando...');
				},
				success:  function (response) {
					$('#fecha_desde_reporte').html(fecha_desde);
					$('#fecha_hasta_reporte').html(fecha_hasta);
					$('#cliente_reporte').html(cliente);
					$('#ubicacion_reporte').html(ubicacion);
					$('#sub_linea_reporte').html(sub_linea);
					$('#modal_contenidoDetalle').html(response);
					$("#Detalle" ).html('');
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);}
				});
		}else{
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
			<td width="10%">Linea</td>
			<td width="14%"><select name="linea" id="linea" style="width:120px;">
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
				<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
				</tr>
				<tr>
			<td>Cliente:</td>
			<td><select name="cliente" id="cliente" onchange="llenar_ubicacion(this.value)" style="width:120px;">
					<?php
					echo $select_cl ;
					$query01 = $bd->consultar($sql_cliente);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?>

				</select></td>
			<td>Ubicacion:</td>
			<td><select name="ubicacion" id="ubicacion" style="width:120px;">
					<option value="TODOS">TODOS</option>
				</select></td>
			<td>&nbsp;
				<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
				<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente']; ?>" />
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /> </td>
		</tr>
	</table>
	<hr />
	<br>
	<table width="95%" class="tabla_sistema">
		<thead>
			<tr>
				<tr class="fondo00">
				<th width="10%" class="etiqueta"><?php echo $leng['cliente']?></th>
				<th width="10%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
				<th width="12%" class="etiqueta">Linea</th>
				<th width="12%" class="etiqueta">Sub Linea</th>
				<th width="5%" class="etiqueta">Total Dotado</th>
				<th width="5%" class="etiqueta">Alcance</th>
			</tr>
		</thead>
		<tbody id="contenido">
		</tbody>
	</table>
	<div align="center"><br />
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" class="readon art-button">
		</span>&nbsp;


		<input type="submit" name="procesar" id="procesar" hidden="hidden">
		<input type="text" name="reporte" id="reporte" hidden="hidden">

		<!-- <img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

		<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0" onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel"> -->
	</div>
</form>
<script type="text/javascript">
	function llenar_ubicacion(cliente) {
		// var cliente =$('#empresa').val();
		$('#ubicacion').html('');
		var estado = 'TODOS'; //$('#estado').val();
		var ciudad = 'TODOS'; //$('#ciudad').val();

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

					$('#ubicacion').append('<option value="">selecione...</option>');
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
			$('#ubicacion').append('<option value= ""></option>');
		}
	}
</script>


<div id="modalDetalle" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="$('#modalDetalle').hide()" >&times;</span>
			<span>Detalle</span>
		</div>
		<div class="modal-body">
			<div id="Detalle"></div>
			<table width="100%" class="etiqueta">
				<tr>
					<td>Fecha Desde:</td>
					<td><span id="fecha_desde_reporte"></span></td>
					<td>Fecha Hasta:</td>
					<td><span id="fecha_hasta_reporte"></span></td>
				</tr>
				<tr>
					<td>Cliente:</td>
					<td><span id="cliente_reporte"></span></td>
					<td>Ubicacion:</td>
					<td><span id="ubicacion_reporte"></span></td>
				</tr>
				<tr>
					<td>Sub Linea:</td>
					<td><span id="sub_linea_reporte"></span></td>
				</tr>
			</table>
			<div id="modal_contenidoDetalle"></div>
		</div>
	</div>
</div>