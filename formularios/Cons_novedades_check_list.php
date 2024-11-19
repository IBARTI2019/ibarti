<?php
$Nmenu     = 449;
$mod       = $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
$tabla   = "nov_check_list";
$archivo  = "novedades_check_list";
$vinculo  = "inicio.php?area=formularios/Add_$archivo&Nmenu=$Nmenu&mod=$mod";
$titulo   = "NOVEDADES CHECK LIST INGRESOS";
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX() { // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//
		var Nmenu = $("#Nmenu").val();
		var mod = $("#mod").val();
		var archivo = $("#archivo").val();
		var codigo = $("#codigo").val();
		var fecha_desde = $("#fecha_desde").val();
		var fecha_hasta = $("#fecha_hasta").val();
		var clasif = $("#clasif").val();
		var tipo = $("#tipo").val();
		var cliente = $("#cliente").val();
		var ubicacion = $("#ubicacion").val();
		var status = $("#status").val();
		var perfil = $("#perfil").val();

		var error = 0;
		var errorMessage = ' ';
		if (fechaValida(fecha_desde) != true || fechaValida(fecha_hasta) != true) {
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error = error + 1;
		}

		if (cliente == "") {
			var errorMessage = ' El Campo Cliente Es Requerido ';
			var error = error + 1;
		}

		if (error == 0) {
			var contenido = "listar";
			$("#img_actualizar").remove();
			$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
			ajax = nuevoAjax();
			ajax.open("POST", "ajax/Add_novedades_check_list_cons.php", true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById(contenido).innerHTML = ajax.responseText;
					$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("Nmenu=" + Nmenu + "&mod=" + mod + "&archivo=" + archivo + "&fecha_desde=" + fecha_desde + "&fecha_hasta=" +
				fecha_hasta + "&clasif=" + clasif + "&tipo=" + tipo + "&cliente=" + cliente + "&ubicacion=" + ubicacion + "&status=" +
				status + "&perfil=" + perfil + "&codigo=" + codigo + "");

		} else {
			alert(errorMessage);
		}
	}
</script>
<div align="center" class="etiqueta_title"> CONSULTA <?php echo $titulo; ?> </div>
<hr />
<div id="Contenedor01"></div>
<form name="form_consulta" id="form_consulta" action="<?php echo $archivo; ?>" method="post" target="_blank">
	<fieldset>
		<legend>Filtros:</legend>
		<table width="100%">
			<tr>
				<td width="10%">Fecha Desde: </td>
				<td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" onclick="javascript:muestraCalendario('form_consulta', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_consulta', 'fecha_desde');" border="0" width="17px"></td>
				<td width="10%">Fec. Hasta:</td>
				<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" onclick="javascript:muestraCalendario('form_consulta', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_consulta', 'fecha_hasta');" border="0" width="17px"></td>
				<td width="10%">Clasificacion: </td>
				<td width="14%"><select name="clasif" id="clasif" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$sql01    = " SELECT nov_clasif.codigo, nov_clasif.descripcion
	    	                    FROM nov_clasif, nov_perfiles
		                       WHERE nov_clasif.`status` = 'T'
                                 AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                                 AND nov_perfiles.cod_perfil = '" . $_SESSION['cod_perfil'] . "'
                                 AND nov_clasif.campo04 = 'T' ORDER BY 2 ASC ";
						$query01 = $bd->consultar($sql01);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>
					</select></td>

				<td width="10%">Status: </td>
				<td width="14%"><select name="status" id="status" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_nov_status);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>
					</select></td>
				<td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" Add_filtroX()"></td>
			</tr>
			<tr>
				<td>Tipo: </td>
				<td><select name="tipo" id="tipo" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_nov_tipo);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>
					</select></td>
				<td><?php echo $leng["cliente"]; ?>: </td>
				<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')" required>
						<?php
						echo $select_cl;
						$query01 = $bd->consultar($sql_cliente);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?></select></td>
				<td><?php echo $leng["ubicacion"]; ?>: </td>
				<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_ubicacion);
						while ($row01 = $bd->obtener_fila($query01, 0)) {
							echo '<option value="' . $row01[0] . '">' . $row01[1] . '</option>';
						} ?>
					</select></td>
				<td>Código:</td>
				<td><input style="width:120px;" name="codigo" id="codigo" type="text" /></td>


				<td width="1%">&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu; ?>" />
					<input type="hidden" name="mod" id="mod" value="<?php echo $mod; ?>" />
					<input type="hidden" name="tabla" id="tabla" value="<?php echo $tabla; ?>" />
					<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo; ?>" />
					<input type="hidden" name="perfil" id="perfil" value="<?php echo $_SESSION['cod_perfil']; ?>" />
				</td>
			</tr>
		</table>
	</fieldset>
</form>
<div id="listar">
	<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="7%" class="etiqueta">Codigo</th>
			<th width="7%" class="etiqueta">Fecha</th>
			<th width="18%" class="etiqueta">Clasificacion</th>
			<th width="18%" class="etiqueta">Tipo</th>
			<th width="18%" class="etiqueta"><?php echo $leng["cliente"]; ?></th>
			<th width="18%" class="etiqueta"><?php echo $leng["ubicacion"]; ?></th>
			<th width="8%" class="etiqueta">Status</th>
			<th width="6%" align="center"><a href="<?php echo $vinculo . "&codigo=''&metodo=agregar"; ?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null" /></a></th>
		</tr>
		<?php
		$sql   = " SELECT nov_check_list.codigo, nov_check_list.fec_us_ing,
                      CONCAT(ficha.apellidos, ' ', ficha.nombres) AS trabajador,
                      nov_clasif.descripcion AS clasif, nov_tipo.descripcion AS tipo,
					  clientes.nombre AS cliente,
                      clientes_ubicacion.descripcion AS ubicacion, nov_status.descripcion AS `status`
                 FROM nov_check_list , nov_clasif , clientes , clientes_ubicacion ,
                      ficha , nov_status , nov_perfiles, nov_tipo
                WHERE nov_check_list.fec_us_ing     = CURDATE()
                  AND nov_check_list.cod_nov_clasif = nov_clasif.codigo
			      AND nov_clasif.codigo             = nov_perfiles.cod_nov_clasif
				  AND nov_check_list.cod_nov_tipo   = nov_tipo.codigo
                  AND nov_perfiles.cod_perfil       = '" . $_SESSION['cod_perfil'] . "'
                  AND nov_check_list.cod_cliente    = clientes.codigo
                  AND nov_check_list.cod_ubicacion  = clientes_ubicacion.codigo
                  AND nov_check_list.cod_ficha      = ficha.cod_ficha
                  AND nov_check_list.cod_nov_status = nov_status.codigo
                ORDER BY 2 DESC ";
		$query = $bd->consultar($sql);

		$valor = 0;
		while ($datos = $bd->obtener_fila($query, 0)) {
			if ($valor == 0) {
				$fondo = 'fondo01';
				$valor = 1;
			} else {
				$fondo = 'fondo02';
				$valor = 0;
			}

			// $Modificar = "Add_Mod01('".$datos[0]."', 'modificar')";
			//</a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/>
			$Borrar = "Borrar01('" . $datos[0] . "')";

			echo '<tr class="' . $fondo . '">
                  <td class="texo">' . $datos["codigo"] . '</td>
				  <td class="texo">' . $datos["fec_us_ing"] . '</td>
				  <td class="texo">' . longitudMin($datos["clasif"]) . '</td>
  				  <td class="texo">' . longitudMin($datos["tipo"]) . '</td>
                  <td class="texo">' . longitudMin($datos["cliente"]) . '</td>
				  <td class="texo">' . longitudMin($datos["ubicacion"]) . '</td>
				  <td class="texo">' . longitudMin($datos["status"]) . '</td>
				  <td align="center"><a href="' . $vinculo . '&codigo=' . $datos[0] . '&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="' . $Borrar . '" class="imgLink"/></td>
            </tr>';
		} ?>
	</table>
</div>