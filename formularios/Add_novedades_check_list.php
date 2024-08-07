<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu = 449;
$NmenuX = 435;
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
$mod = $_GET['mod'];
$titulo = "CHECK LIST";
$archivo = "novedades_check_list";
$metodo  = $_GET['metodo'];
$href  = "../inicio.php?area=formularios/Cons_$archivo&Nmenu=$Nmenu&mod=$mod";
// $href2 = "'inicio.php?area=formularios/Add_novedades&Nmenu=$NmenuX&mod=$mod&codigo=&metodo=agregar2'";

$sql    = " SELECT control.cl_campo_04_desc  FROM control ";
$query  = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);
$campo_04  = $result[0];

$proced    = "p_nov_check_list";
$proced2   = "p_nov_check_list_det";
$proced3   = "p_nov_check_list_max";

if ($metodo == 'modificar') {
	$titulo = "MODIFICAR $titulo";
	$codigo = $_GET['codigo'];
	$bd = new DataBase();

	$sql = " SELECT nov_check_list.codigo,
                     nov_check_list.hora, nov_check_list.cod_ficha,
                    ficha.cedula, CONCAT(ficha.apellidos, ' ', ficha.nombres) AS trabajador,
                    nov_check_list.cod_nov_clasif, nov_clasif.descripcion AS nov_clasif,
					nov_check_list.cod_nov_tipo, nov_tipo.descripcion AS nov_tipo,
                    nov_check_list.cod_cliente, clientes.nombre AS cliente,
                    nov_check_list.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion,
                    nov_check_list.cod_nov_status, nov_status.descripcion AS nov_status,
                    nov_check_list.observacion, nov_check_list.repuesta,
					clientes_ubicacion.contacto, clientes_ubicacion.campo04,
                    nov_check_list.fec_us_ing,  nov_check_list.fec_us_mod,
					nov_check_list.cod_us_mod,
                    CONCAT(men_usuarios.apellido,' ',men_usuarios.nombre) AS us_mod
               FROM nov_check_list LEFT JOIN men_usuarios ON nov_check_list.cod_us_mod = men_usuarios.codigo ,
			        nov_clasif , nov_tipo, clientes , clientes_ubicacion ,
					ficha ,  nov_status
              WHERE nov_check_list.codigo         = '$codigo'
                AND nov_check_list.cod_nov_clasif = nov_clasif.codigo
			    AND nov_check_list.cod_nov_tipo   = nov_tipo.codigo
                AND nov_check_list.cod_cliente    = clientes.codigo
                AND nov_check_list.cod_ubicacion  = clientes_ubicacion.codigo
                AND nov_check_list.cod_ficha      = ficha.cod_ficha
                AND nov_check_list.cod_nov_status = nov_status.codigo ";

	$query = $bd->consultar($sql);
	$result = $bd->obtener_fila($query, 0);

	$fecha_sistema = conversion($result['fec_us_ing']);
	$hora          = $result['hora'];
	$us_mod        = $result['us_mod'];
	$fec_us_mod    = conversion($result['fec_us_mod']);
	$cod_ficha     = $result['cod_ficha'];
	$trabajador    = $result['cod_ficha'] . ' - ' . ' (' . $result['cedula'] . ') ' . $result['trabajador'];
	$cod_clasif    = $result['cod_nov_clasif'];
	$clasif        = $result['nov_clasif'];
	$cod_tipo      = $result['cod_nov_tipo'];
	$tipo          = $result['nov_tipo'];
	$cod_cliente   = $result['cod_cliente'];
	$cliente       = $result['cliente'];
	$cod_ubicacion = $result['cod_ubicacion'];
	$ubicacion     = $result['ubicacion'];
	$observacion   = $result['observacion'];
	$respuesta     = $result['repuesta'];
	$contato       = '';
	$campo_04_d    = '';
	$cod_status   = $result['cod_nov_status'];
	$status       = $result['nov_status'];

	$supervisor    = '<option value="trabajador"> Supervisor </option>';
} elseif ($metodo == 'agregar') {

	$sql   = "SELECT codigo, descripcion FROM nov_status, control
			   WHERE status =  'T'
				 AND nov_status.codigo = control.novedad ";
	$query     = $bd->consultar($sql);
	$row02     = $bd->obtener_fila($query, 0);


	$titulo       = "AGREGAR $titulo";
	$codigo       = '';
	$cod_ficha    = '';
	$trabajador   = '';
	$cod_clasif   = '';
	$clasif       = 'Seleccione...';
	$cod_tipo     = '';
	$tipo         = 'Seleccione...';
	$cod_cliente  = '';
	$cliente      = 'Seleccione...';
	$cod_ubicacion = '';
	$ubicacion    = 'Seleccione...';
	$respuesta    = '';
	$observacion  = '';

	$contato      = '';
	$campo_04_d   = '';

	$cod_status   = $row02[0];
	$status       = $row02[1];

	$fecha_sistema = conversion($date);
	$hora          = date("H:i:s");
	$us_mod        = '';
	$fec_us_mod    = '';
	$supervisor    = '<option value="">Seleccione...</option>
	        		  <option value="codigo"> C&oacute;digo </option>
				      <option value="cedula"> C&eacute;dula </option>
				      <option value="trabajador"> Supervisor </option>
                      <option value="apellidos"> Apellido </option>
                      <option value="nombres"> Nombre </option>';
} else {
	exit;
}
$href2 = "'inicio.php?area=formularios/Add_novedades&Nmenu=$NmenuX&mod=$mod&metodo=agregar2&cl=$cod_cliente&ubic=$cod_ubicacion&codigo=$cod_ficha";
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

		var clasif = document.getElementById("clasif").value;
		var tipo = document.getElementById("tipo").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var superv = document.getElementById("stdID").value;
		var Nmenu = document.getElementById("Nmenu").value;
		var mod = document.getElementById("mod").value;

		var error = 0;
		var errorMessage = ' ';

		if (cliente == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Cliente';
		}

		if (ubicacion == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Una Ubicacion';
		}

		if (superv == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Supervisor';
		}

		if (error == 0) {
			var contenido = "listar_detalle";
			ajax = nuevoAjax();
			ajax.open("POST", "ajax/Add_novedades_check_list.php", true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById(contenido).innerHTML = ajax.responseText;
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("clasif=" + clasif + "&tipo=" + tipo + "&ubicacion=" + ubicacion + "&mod=" + mod + "&Nmenu=" + Nmenu + "");
		} else {
			alert(errorMessage);
		}
	}

	function Validar01(valor) {

		if (valor != '') {
			var contenido = "Contenedor01";
			ajax = nuevoAjax();
			ajax.open("POST", "ajax/Add_cl_ubicacion_det.php", true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById(contenido).innerHTML = ajax.responseText;
					Valores();
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo=" + valor + "");
		}
	}

	function Valores() {
		document.getElementById("contanto").value = document.getElementById("cl_ubic_contato").value;
		document.getElementById("campo_04").value = document.getElementById("cl_ubic_campo_04").value;

		// Clasificacion y Tipo
		Clasif_Tipo();
	}

	function Clasif_Tipo() {
		var ubicacion = document.getElementById("ubicacion").value;
		var perfil = document.getElementById("perfil").value;

		if (ubicacion != '') {
			var contenido = "Contenedor02";
			ajax = nuevoAjax();
			ajax.open("POST", "ajax/Add_novedades_check_list_clasif_tipo.php", true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById(contenido).innerHTML = ajax.responseText;

				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo=" + ubicacion + "&perfil=" + perfil + "");
		}
	}

	function getClasif(){
		Add_filtroX();
	}


	function getTipos(clasif) {
		var parametros = {
			clasificacion: clasif,
			inicial: 'S'
		};
		$.ajax({
			data: parametros,
			url: 'ajax/Add_novedades_tipo.php',
			type: 'post',
			success: function(response) {
				$('#tipo').html(response);
				Add_filtroX();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function Guardar() {

		var clasif = document.getElementById("clasif").value;
		var tipo = document.getElementById("tipo").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var superv = document.getElementById("stdID").value;
		var error = 0;
		var errorMessage = ' ';
		var radio_checked = $("#checklist").val()

		if (clasif == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Clasificacion';
		}

		if (tipo == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Tipo';
		}

		if (cliente == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Cliente';
		}

		if (ubicacion == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Una Ubicacion';
		}

		if (superv == '') {
			var error = error + 1;
			var errorMessage = ' Debe Selecionar Un Supervisor';
		}

		if (error == 0) {
			document.add.submit();
		} else {
			alert(errorMessage);
		}
	}


	function Add_ajax01(codigo, archivo, contenido) { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//
		if (codigo != '') {
			var parametros = {
				codigo,
				archivo,
				contenido
			};
			$.ajax({
				data: parametros,
				url: archivo,
				type: 'post',
				success: function(response) {
					$("#" + contenido).html(response);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
	}

	function change_client(value) {
		Add_ajax01(value, 'ajax/Add_cl_ubicacion.php', 'select03');
		Clasif_Tipo();
	}
</script>
<form action="scripts/sc_<?php echo $archivo; ?>.php" method="post" name="add" id="add">
	<div id="Contenedor01"></div>
	<table width="100%" align="center">
		<tr valign="top">
			<td height="23" colspan="2" class="etiqueta_title" align="center"><?php echo $titulo; ?></td>
		</tr>
		<tr>
			<td height="8" colspan="4" align="center">
				<hr>
			</td>
		</tr>
		<tr>
			<td class="etiqueta" width="15%">Codigo:</td>
			<td width="25%"><input name="codigo" type="text" readonly="readonly" value="<?php echo $codigo; ?>" /></td>
			<td class="etiqueta" width="15%">Fecha De Sistema:</td>
			<td id="fecha01" width="25%"><input type="text" size="20" value="<?php echo $fecha_sistema . ' &nbsp; ' . $hora; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td class="etiqueta">Supervisor:</td>
			<td id="select01">
				<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:200px">
					<?php echo $supervisor; ?>
				</select><br />
				<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
			</td>
			<td class="etiqueta">Nombre:</td>
			<td><input id="stdName" type="text" style="width:300px" disabled="disabled" value="<?php echo $trabajador; ?>" />
				<span id="input01"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_ficha; ?>" /> <br />
					<span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
					<span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta"><?php echo $leng["cliente"]; ?>:</td>
			<td id="select02"><select name="cliente" id="cliente" style="width:200px" onchange="change_client(this.value)">
					<option value="<?php echo $cod_cliente; ?>"><?php echo $cliente; ?></option>
					<?php
					if ($metodo == 'agregar') {
						$query = $bd->consultar($sql_cliente);
						while ($row02 = $bd->obtener_fila($query, 0)) {
					?>
							<option value="<?php echo $row02[0]; ?>"><?php echo $row02[1]; ?></option>
					<?php }
					}
					?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
			<td class="etiqueta"><?php echo $leng["ubicacion"]; ?>:</td>
			<td id="select03"><select name="ubicacion" id="ubicacion" style="width:200px">
					<option value="<?php echo $cod_ubicacion; ?>"><?php echo $ubicacion; ?></option>
					<?php
					$sql   = "SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
                                           FROM clientes_ubicacion
						                  WHERE clientes_ubicacion.cod_cliente = ' $cod_cliente'
						                    AND clientes_ubicacion.`status` = 'T'
						                  ORDER BY 2 ASC";
					$query = $bd->consultar($sql);
					while ($row02 = $bd->obtener_fila($query, 0)) {
					?>
						<option value="<?php echo $row02[0]; ?>"><?php echo $row02[1]; ?></option>
					<?php } ?>
				</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
		</tr>
		<tr>
			<td class="etiqueta">Contacto:</td>
			<td><input id="contanto" type="text" style="width:200px" disabled="disabled" value="<?php echo $contato; ?>" /></td>
			<td class="etiqueta"><?php echo $campo_04; ?>:</td>
			<td><input id="campo_04" type="text" style="width:300px" disabled="disabled" value="<?php echo $campo_04_d; ?>" /></td>
		</tr>
		<tr>
			<td class="etiqueta">Observacion:</td>
			<td id="textarea01"><textarea name="observacion" cols="42" rows="2"><?php echo $observacion; ?></textarea>
				<span id="Counterror_mess1" class="texto">&nbsp;</span><br />
				<span class="textareaRequiredMsg">El Campo es Requerido.</span>
				<span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span>
			</td>
			<td class="etiqueta">Repuesta:</td>
			<td id="textarea02"><textarea name="repuesta" cols="42" rows="2" readonly="readonly"><?php echo $respuesta; ?></textarea>
				<span id="Counterror_mess2" class="texto">&nbsp;</span><br />
				<span class="textareaRequiredMsg">El Campo es Requerido.</span>
				<span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
				<span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 500.</span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Status:</td>
			<td id="select10"><select name="status" style="width:200px">
					<option value="<?php echo $cod_status; ?>"><?php echo $status; ?></option>
				</select><br />
				<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
			</td>
			<td class="etiqueta">Usuario Mod. Sistema: </td>
			<td><?php echo $us_mod; ?></td>
		</tr>
		<tr>
			<td class="etiqueta" colspan="2">&nbsp;</td>
			<td class="etiqueta">Fecha Usuario Mod.: </td>
			<td><?php echo $fec_us_mod; ?></td>
		</tr>
	</table>
	<fieldset>
		<legend>Filtros:</legend>
		<table width="100%">
			<tr id="Contenedor02">
				<td class="etiqueta" width="15%">CLASIFICACION:</td>
				<td width="25%" id="select04"><select name="clasif" id="clasif" style="width:150px;" onchange="Add_filtroX()">
						<option value="<?php echo $cod_clasif; ?>"><?php echo $clasif; ?></option>
					</select> </td>
				<td class="etiqueta">TIPO:</td>
				<td id="select05"><select name="tipo" id="tipo" style="width:150px;" onchange="Add_filtroX()">
						<option value="<?php echo $cod_tipo; ?>"><?php echo $tipo; ?></option>
					</select></td>
			</tr>
		</table>
	</fieldset>
	<div class="listar" id="listar_detalle">
		<table width="100%" align="center">
			<tr>
				<td class="etiqueta" width="45%">Check List:</td>
				<td class="etiqueta" width="15%">Valor:</td>
				<td class="etiqueta" width="40%">Observacion:</td>
			</tr>

			<?php if ($metodo == "modificar") { // echo statusCheck("$activo");
				$sql   = "  SELECT nov_check_list_det.cod_check_list,
                           nov_check_list_det.cod_novedades, novedades.descripcion AS novedad,
                           nov_check_list_det.cod_valor, nov_check_list_det.observacion
                      FROM nov_check_list_det , novedades
                     WHERE nov_check_list_det.cod_check_list = '$codigo'
                       AND nov_check_list_det.cod_novedades = novedades.codigo ";

				$query = $bd->consultar($sql);
				while ($datos = $bd->obtener_fila($query, 0)) {
					$cod_nov = $datos[1];
					$cod_valor = $datos[3];
					$obsev = "&observ=" . $datos[4];

					$sql02 = " SELECT nov_valores.codigo, nov_valores.abrev,nov_valores.descripcion
                     FROM nov_valores_det , nov_valores
                    WHERE nov_valores_det.cod_novedades = '$cod_nov'
                      AND nov_valores_det.cod_valores = nov_valores.codigo
                 ORDER BY 1 ASC ";
					$query02 = $bd->consultar($sql02);
					echo '
    <tr>
      <td><textarea disabled="disabled" cols="60" rows="1">' . $datos[2] . '</textarea>
	      <input type="hidden" name="cod_valor_' . $cod_nov . '" value="' . $datos[0] . '" />
		  <input type="hidden" name="check_list[]" value="' . $cod_nov . '" /></td>
	  <td>';
					while ($datos02 = $bd->obtener_fila($query02, 0)) {
						echo ' ' . $datos02[1] . ' <input type = "radio" name="check_list_valor_' . $cod_nov . '" value = "' . $datos02[0] . '" style="width:auto" title="' . $datos02[2] . '"
	   ' . CheckX('' . $cod_valor . '', '' . $datos02[0] . '') . '/>';
					}
					echo '</td>
      <td><textarea  name="observacion_' . $cod_nov . '" cols="50" rows="1">' . $datos[4] . '</textarea><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null" class="imgLink" onclick="Vinculo(' . $href2 . $obsev . '\')"/><img src="imagenes/nuevo2.bmp" alt="Agregar Registro Kanban" title="Agregar Registro Kanban" width="20px" height="20px" border="null" class="imgLink" onclick="Vinculo(' . $href2 . $obsev . '&kanban=true\')"/></td>
    </tr>';
				}
			} ?>
		</table>
	</div>
	<div align="center">
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salvar" id="salvar" value="Guardar" class="readon art-button" onclick="Guardar()" />
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
		<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
		<input name="proced" type="hidden" value="<?php echo $proced; ?>" />
		<input name="proced2" type="hidden" value="<?php echo $proced2; ?>" />
		<input name="proced3" type="hidden" value="<?php echo $proced3; ?>" />
		<input name="Nmenu" id="Nmenu" type="hidden" value="<?php echo $Nmenu; ?>" />
		<input name="mod" id="mod" type="hidden" value="<?php echo $mod; ?>" />
		<input name="href" type="hidden" value="<?php echo $href; ?>" />
		<input name="perfil" id="perfil" type="hidden" value="<?php echo $_SESSION['cod_perfil']; ?>" />
		<input type="hidden" value="<?php echo $cod_status; ?>" />
		<input type="hidden" name="descripcion" value="" />
		<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol']; ?>" />
		<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente']; ?>" />
		<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod']; ?>" />
	</div>
</form>
</body>

</html>
<script type="text/javascript">
	//var input01 = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
	var select01 = new Spry.Widget.ValidationSelect("select01", {
		validateOn: ["blur", "change"]
	});
	var select02 = new Spry.Widget.ValidationSelect("select02", {
		validateOn: ["blur", "change"]
	});
	var select10 = new Spry.Widget.ValidationSelect("select10", {
		validateOn: ["blur", "change"]
	});

	var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {
		maxChars: 500,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess1",
		useCharacterMasking: false,
		isRequired: false
	});

	var textarea02 = new Spry.Widget.ValidationTextarea("textarea02", {
		maxChars: 500,
		validateOn: ["blur", "change"],
		counterType: "chars_count",
		counterId: "Counterror_mess2",
		useCharacterMasking: false,
		isRequired: false
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