<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = 560;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE NOVEDADES ";
$archivo2 = "reportes/rp_nov_novedad_det";
$archivo  = "$archivo2.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<script language="JavaScript" type="text/javascript">



function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //
	var novedad     = $( "#novedad").val();
	var clasif      = $( "#clasif").val();
	var cliente     = $( "#cliente").val();
	var ubicacion   = $( "#ubicacion").val();
	var status      = $( "#status").val();
	var detalle     = $( "#detalle").val();

	var trabajador  = $( "#stdID").val();
	var cod_nov  = $( "#stdNovedadID").val();
	var fecha_desde = $( "#fecha_desde").val();
	var fecha_hasta = $( "#fecha_hasta").val();
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();


	var error = 0;
	var errorMessage = ' ';
	if(cod_nov==''){
		if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
			var errorMessage = ' Campos De Fecha Incorrectas ';
			var error = error+1;
		}


		if( cliente == ""){
			var errorMessage = ' El Campo Cliente Es Requerido ';
			var error      = error+1;
		}
	}
	


	if(error == 0){

		var contenido = "listar";
		var parametros = {
			"novedad" : novedad,
			"clasif" : clasif,
			"cliente" : cliente,
			"ubicacion": ubicacion,
			"status": status,
			"detalle": detalle,
			"trabajador": trabajador,
			"fecha_desde": fecha_desde,
			"fecha_hasta": fecha_hasta,
			"cod_nov":cod_nov,
			"Nmenu" : Nmenu,
			"mod" : mod,
			"archivo": archivo
		};
		$.ajax({
			data:  parametros,
			url:   'ajax_rp/Add_nov_novedad.php',
			type:  'post',
			beforeSend: function () {
				$("#img_actualizar").remove();
				$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
			},
			success:  function (response) {
				console.log(response);
				$("#listar").html(response);
				
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
			},

			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}else{
		alert(errorMessage);
	}
}

function reportes(tipo) {
	var cod_nov  = $( "#stdNovedadID").val();
	var fecha_desde = $( "#fecha_desde").val();
	var fecha_hasta = $( "#fecha_hasta").val();
	if(cod_nov!=''){
		$('#reporte').val(tipo);
		$('#procesar').click();
		
	}
	else{

		var error = 0;
		var errorMessage = ' ';
		
			if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
				var errorMessage = ' Campos De Fecha Incorrectas ';
				var error = error+1;
			}

			if (error == 0){
				$('#reporte').val(tipo);
				$('#procesar').click();
			}else{
				alert(errorMessage);
			}
			

		
	}
	$( "#stdNovedadID").val('');
	$('#stdNovedad').val('');
}

</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?></div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
	<hr /><table width="100%" class="etiqueta">
		<tr><td width="10%">Fecha Desde:</td>
			<td width="14%" id="fecha01" onclick="{$('#stdNovedadID').val('');$('#stdNovedad').val('');}"><input type="text" name="fecha_desde" id="fecha_desde" size="9"  onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');"  border="0" width="17px"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9"   onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>

			<td width="10%">Status: </td>
			<td width="14%"><select name="status" id="status" style="width:120px;" onchange="Add_filtroX()">
				<option value="TODOS">TODOS</option>
				<?php
				$query01 = $bd->consultar($sql_nov_status);
				while($row01=$bd->obtener_fila($query01,0)){
					echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
				}?></select></td>
				<td>Detalle:</td>
				<td><select  name="detalle" id="detalle" style="width:120px;" >
					<option value="N">NO</option>
					<option value="S">SI</option>
				</select></td>
				<td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="Add_filtroX()"></td>
			</tr>
			<tr>
				<td> Novedad: </td>
				<td><select name="novedad" id="novedad" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php 		$sql_novedad = "SELECT novedades.codigo, novedades.descripcion
					FROM novedades , nov_clasif
					WHERE novedades.`status` = 'T'
					AND novedades.cod_nov_clasif = nov_clasif.codigo
					AND nov_clasif.campo04 = 'F'
					ORDER BY 2 ASC ";

					$query01 = $bd->consultar($sql_novedad);
					while($row01=$bd->obtener_fila($query01,0)){
						echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
					}?></select></td>
					<td>Clasificacion:</td>
					<td><select name="clasif" id="clasif" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_nov_clasif);
						while($row01=$bd->obtener_fila($query01,0)){
							echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
						}?></select></td>
						<td><?php echo $leng['cliente']?>:</td>
						<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')" required>
							<?php
							echo $select_cl;
							$query01 = $bd->consultar($sql_cliente);
							while($row01=$bd->obtener_fila($query01,0)){
								echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
							}?></select></td>
							<td><?php echo $leng['ubicacion']?>: </td>
							<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
								<option value="TODOS">TODOS</option>
							</select></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Filtro <?php echo $leng['trab']?>.:</td>
							<td id="select01">
								<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
									<option value="TODOS"> TODOS</option>
									<option value="codigo"><?php echo $leng['ficha']?></option>
									<option value="cedula"><?php echo $leng['ci']?></option>
									<option value="trabajador"><?php echo $leng['trabajador']?></option>
									<option value="nombres"> Nombre </option>
									<option value="apellidos"> Apellido </option>
								</select></td>

								<td><?php echo $leng['trabajador']?>:</td>
								<td colspan="2"><input  id="stdName" type="text" style="width:200px" disabled="disabled" />
									<input type="hidden" name="trabajador" id="stdID" value=""/></td>
									
									<td>Codigo Novedad:</td>
									<td colspan="2"><input  id="stdNovedad" type="text" style="width:200px"  />
										<input type="hidden" name="stdNovedadID" id="stdNovedadID" value=""/></td>
										
										<td>&nbsp;</td>
										<td>&nbsp; <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
											<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
											<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo2;?>" />
											<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
											<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
											<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/> </td>
										</tr>
									</table><hr /><div id="listar">&nbsp;</div>
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
										onclick="reportes('pdf')" width="25px" title="imprimir a pdf">

										<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
										onclick="reportes('excel')" width="25px" title="imprimir a excel">
									</div>
								</form>
								<script type="text/javascript">
									r_cliente = $("#r_cliente").val();
									r_rol     = $("#r_rol").val();
									usuario   = $("#usuario").val();
									filtroValue = $("#paciFiltro").val();
									
									new Autocomplete("stdNovedad", function() {
										this.setValue = function(id) {
            document.getElementById("stdNovedadID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        console.log('entro');
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
        
        return "autocompletar/tb/novedades.php?q="+this.text.value});

									new Autocomplete("stdName", function() {
										this.setValue = function(id) {
            document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
        return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
    </script>
