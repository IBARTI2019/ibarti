	<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
	<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
	<script type="text/javascript" src="packages/planif/planificacion/controllers/planificacionRP.js"></script>
	<?php
	$Nmenu = '592';
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$bd = new DataBase();

	$archivo = "reportes/rp_op_planif_cl_vs_as_det.php?Nmenu=$Nmenu&mod=$mod";
	$titulo = " Planificacion De ".$leng['trabajador']." VS Asistencia ";
	?>

	<script language="JavaScript" type="text/javascript">

	function Add_filtro(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

		var fecha_desde = $( "#fecha_desde").val();
		var fecha_hasta = $( "#fecha_hasta").val();
		var filtro      = $( "#filtro").val();
		var cliente     = $( "#CLIENTE").val();
		var ubicacion   = $( "#ubicacion").val();
		var Nmenu       = $( "#Nmenu").val();
		var mod         = $( "#mod").val();
		var archivo     = $( "#archivo").val();
		var trabajador = $("#stdID").val();

		var error = 0;
		var errorMessage = ' ';

		if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error      = error+1;
		}

		if( cliente == "" || cliente == "TODOS"){
			var errorMessage = errorMessage + ' \n El Campo Cliente Es Requerido ';
			var error      = error+1;
		}

		if( ubicacion == "TODOS"){
			var errorMessage = errorMessage + ' \n El Campo Ubicacion Es Requerido ';
			var error      = error+1;
		}

		if(error == 0){
			$("#img_actualizar").remove();
			$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");

			var contenido = "listar";
			var parametros = {
				"fecha_desde": fecha_desde,
				"fecha_hasta": fecha_hasta,
				"cliente":cliente,
				"ubicacion":ubicacion,
				"Nmenu" : Nmenu,
				"mod" : mod,
				"archivo": archivo,
				"trabajador": trabajador

			};
			$.ajax({
				data:  parametros,
				url:   'ajax_rp/Add_rop_planif_trab_vs_as.php',
				type:  'post',
				success:  function (response) {
					var resp = JSON.parse(response);
					rp_planif_trab_vs_asistencia(resp,'listar')
					$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtro()'>");

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
<div align="center" class="etiqueta_title"> Consuta <?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>" method="post" target="_blank">
	<hr /><table width="100%" class="etiqueta">
		<tr><td width="10%">Fecha Desde:</td>
			<td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
			<td width="10%">Filtro: </td>
			<td width="14%"><select name="filtro" id="filtro" style="width:120px;">
				<option value="TODOS">Seleccione..</option>
				<option value="horario">Horario</option>
				<option value="concepto">Concepto</option>
			</select></td>
				<td width="10%">Tipo Reporte:</td>
				<td width="14%" id="select10" ><select name="reporte" style="width:120px;">
					<option value="pdf">PDF</option>
					<option value="excel">Excel</option>
				</select></td>
				<td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
					onclick=" Add_filtro()" ></td>
				</tr>
				<tr>
					<td>Filtro <?php echo $leng['trabajador']?>.:</td>
					<td id="select01">
						<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
							<option value="TODOS"> TODOS</option>
							<option value="codigo"> <?php echo $leng['ficha']?></option>
							<option value="cedula"><?php echo $leng['ci']?></option>
							<option value="trabajador"><?php echo $leng['trabajador']?></option>
							<option value="nombres"> Nombre </option>
							<option value="apellidos"> Apellido </option>
						</select></td>
						<td><?php echo $leng['trabajador']?>:</td>
						<td><input  id="stdName" type="text" size="22" disabled="disabled" />
							<input type="hidden" name="trabajador" id="stdID" value=""/>
							 <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
								<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
								<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
								<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>  
								&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
								<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
								<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
							</td>
							<td><?php echo $leng['cliente'];?>:</td>
							<td><select name="cliente" id="CLIENTE" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')"  required>
								<?php echo $select_cl;
								$query01 = $bd->consultar($sql_cliente);
								while($row01=$bd->obtener_fila($query01,0)){
									echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
								}?></select></td>
								<td><?php echo $leng['ubicacion'];?>: </td>
								<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
									<option value="TODOS">TODOS</option>
								</select></td>
							</tr>
						</table><hr /><div id="listar"></div>
						<div align="center"><br/>
							<span class="art-button-wrapper">
								<span class="art-button-l"> </span>
								<span class="art-button-r"> </span>
								<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
								class="readon art-button">
							</span>&nbsp;
							<span class="art-button-wrapper">
								<span class="art-button-l"> </span>
								<span class="art-button-r"> </span>
								<input type="submit" name="procesar" id="procesar" value="Procesar" class="readon art-button">
							</span>
						</div>
					</form>
					<script type="text/javascript">
						r_cliente = $("#r_cliente").val();
						r_rol     = $("#r_rol").val();
						usuario   = $("#usuario").val();
						filtroValue = $("#paciFiltro").val();

						new Autocomplete("stdName", function() {
							this.setValue = function(id) {
	        document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
	    }
	    if (this.isModified) this.setValue("");
	    if (this.value.length < 1) return ;
	    return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
	</script>
