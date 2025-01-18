<?php
$Nmenu = '447';
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
$bd = new DataBase();
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
	$r_cliente = $_SESSION['r_cliente'];
}else{
	$us = $_POST['usuario'];
	$r_cliente = $_POST['r_cliente'];
}
$archivo = "reportes/rp_op_planif_cl_vs_as_horario_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo = " Planificacion De ".$leng['cliente']."  VS Asistencia Horario ";
?>
<script type="text/javascript" src="libs/planificacionRP.js"></script>
<script language="JavaScript" type="text/javascript">


function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var fecha_desde = $( "#fecha_desde").val();
	var fecha_hasta = $( "#fecha_hasta").val();
	var region      = $( "#REGION").val();
	var estado      = $( "#ESTADO").val();
	var cliente     = $( "#CLIENTE").val();
	var ubicacion   = $( "#ubicacion").val();
	var horario     = $( "#HORARIO").val();
	var Nmenu       = $( "#Nmenu").val();
	var usuario         = $( "#usuario").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();

	var error = 0;
	var errorMessage = ' ';

	if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
		var errorMessage = ' Campos De Fecha Incorrectas ';
		var error      = error+1;
	}else{
		var fecha1 = moment(fecha_desde,"DD-MM-YYYY");
		var fecha2 = moment(fecha_hasta,"DD-MM-YYYY");

		if(fecha2.diff(fecha1, 'days')>31){
			var errorMessage = ' El rango de fechas no puede ser mayor a 31 dias ';
			var error      = error+1;
		}
	}
	
	if(error == 0){
		var contenido = "listar";
		$("#img_actualizar").remove();
		$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
		var parametros = {
			"fecha_desde": fecha_desde,
			"fecha_hasta": fecha_hasta,
			"region": region,
			"estado": estado,
			"cliente":cliente,
			"ubicacion":ubicacion,
			"horario":horario,
			"usuario":usuario,
			"Nmenu" : Nmenu,
			"mod" : mod,
			"archivo": archivo
		};
		$.ajax({
			data:  parametros,
			url:   'ajax_rp/Add_rop_planif_cl_vs_as_horario.php',
			type:  'post',
			success:  function (response) {
				$('#listar').html('');
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				var resp = JSON.parse(response);
				if(resp['error']){
					$('#listar').html(resp['error']);
				}else{
					if(typeof resp['asistencia'] == 'undefined'){
						$("#listar").html('Sin Resultados!..');
					}else{
						$("#listar").html('');
						rp_planif_serv_vs_contratacion_horario(resp,'listar',()=>
							$("#contenido_reporte").val($('#t_reporte').html()));
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}

			});

	}else{
		alert(errorMessage);
	}
}


function Add_Cl_Ubic_Sesion(valor, contenido, activar, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Un Cliente ';
	}
	var usuario         = $( "#usuario").val();
	var r_cliente         = $( "#r_cliente").val();

	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_cl_ubic2.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				if (activar == "T") {
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "&usuario=" + usuario + "&r_cliente=" + r_cliente + "");
	} else {
		alert(errorMessage);
	}
}

</script>
<div align="center" class="etiqueta_title"> Consulta <?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>" method="post" target="_blank">
	<input type="hidden" name="contenido_reporte" id="contenido_reporte" value="">
	<hr /><table width="100%" class="etiqueta">
		<tr><td width="10%">Fecha Desde:</td>
			<td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
			<td width="10%">Horario: </td>
			<td width="14%"><select name="horario" id="HORARIO" style="width:120px;">
				<option value="TODOS">TODOS</option>
				<?php
				$query01 = $bd->consultar($sql_horario);
				while($row01=$bd->obtener_fila($query01,0)){
					echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
				}?></select></td>
				<td width="20%"></td>
				<td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
					onclick=" Add_filtroX()" ></td>
				</tr>
				<tr>
					<td width="10%"><?php echo $leng['estado'];?>: </td>
					<td width="14%"><select name="estado" id="ESTADO" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_estado);
						while($row01=$bd->obtener_fila($query01,0)){
							echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
						}?></select></td>
					<td><?php echo $leng['cliente'];?>:</td>
					<td><select name="cliente" id="CLIENTE" style="width:120px;" onchange="Add_Cl_Ubic_Sesion(this.value, 'contenido_ubic', 'T', '120')" required>
						<?php echo $select_cl;
						$query01 = $bd->consultar($sql_cliente);
						while($row01=$bd->obtener_fila($query01,0)){
							echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
						}?></select></td>
						<td><?php echo $leng['ubicacion'];?>: </td>
						<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
							<option value="TODOS">TODOS</option>
						</select></td>
						<td>&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
							<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
							<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" /></td>
							<input type="hidden" name="usuario" id="usuario" value="<?php echo $us;?>" /></td>
							<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $r_cliente;?>" /></td>
						</tr>
					</table><hr /><div id="listar"></div>
					<div align="center"><br/>
						<span class="art-button-wrapper">
							<span class="art-button-l"> </span>
							<span class="art-button-r"> </span>
							<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
							class="readon art-button">
						</span>&nbsp;
						<input type="submit" name="procesar" id="procesar" hidden="hidden">
						<input type="text" name="reporte" id="reporte" hidden="hidden">

						<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
						onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

						<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
						onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
					</div>
				</form>
