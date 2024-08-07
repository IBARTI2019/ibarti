<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>
<?php
$Nmenu   = '5309';
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$archivo = "reportes/rp_pl_proyecto_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " PROYECTOS ";

$titulo      = "REPORTE $titulo";
$codigo      = '';

$sql_area = "SELECT codigo, descripcion FROM area_proyecto WHERE status = 'T';";
?>
<script language="JavaScript" type="text/javascript">
	function showDetail(proyecto) {
		var parametros = {
			proyecto
		};
		$.ajax({
			data: parametros,
			url: 'ajax_rp/Add_projectDetail.php',
			type: 'post',
			success: function(response) {
				$("#modal_contenido").html(response);
				ModalOpen();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function cargar_actividades(proyecto) {
		var parametros = {
			"codigo": proyecto
		};
		$.ajax({
			data: parametros,
			url: 'ajax/Add_actividades.php',
			type: 'post',
			success: function(response) {
				$("#actividad").html(response);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function cargar_proyectos(proyecto) {
		var parametros = {
			"codigo": proyecto
		};
		$.ajax({
			data: parametros,
			url: 'ajax/Add_proyectos.php',
			type: 'post',
			success: function(response) {
				$("#proyecto").html(response);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //
		var area = $("#area").val();
		var proyecto = $("#proyecto").val();
		var status = $("#status").val();
		var actividad = $("#actividad").val();
		var realizado = $("#realizado").val();
		var cargo = $("#cargo").val();

		var trabajador = $("#stdID").val();
		var Nmenu = $("#Nmenu").val();
		var mod = $("#mod").val();
		var archivo = $("#archivo").val();

		var error = 0;
		var errorMessage = ' ';

		if (error == 0) {

			var contenido = "listar";

			var parametros = {
				"area": area,
				"proyecto": proyecto,
				"actividad": actividad,
				"realizado": realizado,
				"status": status,
				"cargo": cargo,
				"Nmenu": Nmenu,
				"mod": mod,
				"archivo": archivo
			};
			$.ajax({
				data: parametros,
				url: 'ajax_rp/Add_pl_proyectos.php',
				type: 'post',
				beforeSend: function() {
					$("#img_actualizar").remove();
					$(".listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
				},
				success: function(response) {
					$(".listar").html(response);
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
			<td>Area:</td>
			<td><select name="area" id="area" style="width:120px;" onchange="cargar_proyectos(this.value)" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_area);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td>Proyecto:</td>
			<td><select name="proyecto" id="proyecto" style="width:120px;" onchange="cargar_actividades(this.value)" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_proyecto);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td>Actividad:</td>
			<td><select name="actividad" id="actividad" style="width:120px;" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_actividad);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>
			<td>Cargo:</td>
			<td><select name="cargo" id="cargo" style="width:120px;" required>
					<?php
					echo '<option value="TODOS">TODOS</option>';
					$query01 = $bd->consultar($sql_cargo);
					while ($row01 = $bd->obtener_fila($query01, 0)) {
						echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
					} ?></select></td>

			<td>Activo: </td>
			<td><select name="status" id="status" style="width:120px;">
					<option value="TODOS"> TODOS </option>
					<option value="T"> SI </option>
					<option value="F"> NO </option>
				</select></td>
			<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>

			<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo2; ?>" />
			<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
			<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente']; ?>" />
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" /> </td>
		</tr>
	</table>
	<hr />
	<div class="listar tabla_sistema">&nbsp;</div>
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

<div id="myModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="CloseModal()">&times;</span>
			<span id="modal_titulo">Detalle de Proyecto</span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido"></div>
		</div>
	</div>
</div>

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