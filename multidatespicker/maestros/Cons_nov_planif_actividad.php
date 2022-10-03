<?php
$Nmenu    =  3410;
$metodo   = "actualizar";
$titulo   = " Novedades Actividades";
$archivo  = "nov_planif_actividad";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
require_once('sql/sql_report.php');
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=$Nmenu&mod=" . $_GET['mod'] . "";
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

		var proyecto = document.getElementById("proyecto").value;
		var actividad = document.getElementById("actividad").value;
		var nov_clasif = document.getElementById("nov_clasif").value;
		var nov_tipo = document.getElementById("nov_tipo").value;

		var campo01 = 1;
		var errorMessage = 'Debe Seleccionar Todo Los Campos';

		if (proyecto == '' || proyecto == 'TODOS') {
			var errorMessage = 'Proyecto Invalido';
			var campo01 = campo01 + 1;
		}

		if ((actividad == '') || (actividad == 'TODOS')) {
			var campo01 = campo01 + 1;
		}
		if (nov_clasif == '' || nov_clasif == 'TODOS') {
			var campo01 = campo01 + 1;
		}

		if (nov_tipo == '' || nov_tipo == 'TODOS') {
			var campo01 = campo01 + 1;
		}

		if (campo01 == 1) {
			var valor = "ajax/nov_planif_actividad.php";
			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Contenedor01").innerHTML = ajax.responseText;
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("proyecto=" + proyecto + "&actividad=" + actividad + "&nov_clasif=" + nov_clasif + "&nov_tipo=" + nov_tipo + "");
		} else {
			toastr.info(errorMessage);
		}
	}

	function Validar01() {
		var valorX = "XXX";
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


	function llenar_nov_tipo(clasificacion) {

		var parametros = {
			'clasificacion': clasificacion,
			'inicial': 'TODOS'
		};
		$.ajax({
			data: parametros,
			url: 'ajax/Add_novedades_tipo.php',
			type: 'post',
			success: function(response) {
				$('#nov_tipo').html(response);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}
</script>
<form action="sc_maestros/sc_<?php echo $archivo; ?>.php" method="post" name="add" id="add">
	<fieldset class="fieldset">
		<legend><?php echo $titulo; ?></legend>
		<table width="90%" align="center">
			<tr>
				<td class="etiqueta">Proyecto:</td>
				<td><select name="proyecto" id="proyecto" style="width:200px;" onchange="cargar_actividades(this.value)">
						<option value="">Seleccione</option>
						<?php
						$query01 = $bd->consultar($sql_proyecto);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>
					</select></td>
				<td class="etiqueta">Actividad: </td>
				<td><select name="actividad" id="actividad" style="width:200px;">
						<option value="">Seleccione</option>
					</select></td>
			</tr>
			<tr>
				<td class="etiqueta">Novedades Clasificacion:</td>
				<td id="select01"><select id="nov_clasif" required name="nov_clasif" style="width:200px" onchange="llenar_nov_tipo(this.value)">
						<option value="">Seleccione</option>
						<?php
						$query = $bd->consultar($sql_nov_clasif);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select>
					<img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0" /><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>

				<td class="etiqueta">Novedades Tipo:</td>
				<td id="select01"><select id="nov_tipo" name="nov_tipo" style="width:200px" onchange="Add_filtroX()">
						<option value="">Seleccione</option>
						<?php
						$query = $bd->consultar($sql_nov_tipo);
						while ($datos = $bd->obtener_fila($query, 0)) {
						?>
							<option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
						<?php } ?>
					</select>
					<img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0" /><br />
					<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
				</td>
			</tr>
			<tr>
				<td height="8" colspan="4" align="center">
					<hr>
				</td>
			</tr>
			<tr>
				<td colspan="4" id="Contenedor01">&nbsp; </td>
			</tr>
			<tr>
				<td height="8" colspan="4" align="center">
					<hr>
				</td>
			</tr>
		</table>
		<div align="center">
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
			</span>
			<input name="metodo" id="metodo" type="hidden" value="<?php echo $metodo; ?>" />
			<input name="tabla" id="tabla" type="hidden" value="<?php echo $tabla; ?>" />
			<input name="usuario" id="usuario" type="hidden" value="<?php echo $usuario; ?>" />
			<input name="href" type="hidden" value="<?php echo $archivo2; ?>" />
		</div>
	</fieldset>
</form>
</body>

</html>