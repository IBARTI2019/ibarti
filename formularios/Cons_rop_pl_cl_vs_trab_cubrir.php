<?php
$Nmenu = '448';
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report_t.php');
$bd = new DataBase();
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
$archivo = "reportes/rp_op_pl_cl_vs_trab_cubrir_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo = " Planificacion  De ".$leng['cliente']." A Cubrir VS ".$leng['trabajador']." Activos ";
?>
<script type="text/javascript" src="libs/planificacionRP.js"></script>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var fecha_desde = $( "#fecha_desde").val();
	var region      = $( "#REGION").val();
	var estado      = $( "#ESTADO").val();
	//var cargos    = $( "#cargos").val();
	var cliente      = $( "#cliente").val();
	var ubicacion    = $( "#ubicacion").val();

	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();
	var usuario     = $( "#usuario").val();

	var error = 0;
	var errorMessage = ' ';

	if( fechaValida(fecha_desde) !=  true ){
		var errorMessage = ' Campos De Fecha Incorrectas ';
		var error      = error+1;
	}

/*
	if(( cargos ==  null ) && ( error ==  0 )){
		var errorMessage = ' Debe Selecionar Un Cargo ';
		var error      = error+1;
	}
	*/
	if(error == 0){
		$("#img_actualizar").remove();
		$("#contenido_listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");

		var contenido = "contenido_listar";
		var parametros = {
			"fecha_desde": fecha_desde,
			"region": region, 		    "estado": estado,
			"cliente": cliente, 		    "ubicacion": ubicacion,
			"Nmenu" : Nmenu,  			"mod" : mod,
			"archivo": archivo, "usuario":usuario
		};
		$.ajax({
			data:  parametros,
			url:   'ajax_rp/Add_rop_planif_cl_vs_trab_cubrir.php',
			type:  'post',
			success:  function (response) {
				$("#contenido_listar").html('');
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0' onclick='Add_filtroX()'>");
				var resp = JSON.parse(response);
				if(typeof resp['contrato'] == 'undefined'){
					$("#contenido_listar" ).html('Sin Resultados!..');
				}else{							
					rp_planif_contratacion_vs_trab_cubrir(resp,'contenido_listar',cliente,ubicacion,()=>$('#body_cubrir').val($('#t_reporte').html()));
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
</script>
<div align="center" class="etiqueta_title"> Consulta <?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>" method="post" target="_blank">
	<hr /><table width="100%" class="etiqueta">
		<tr><!--
			<td rowspan="3" width="12%">Cargos: </td>
			<td rowspan="3" width="18%"><select multiple="multiple" name="cargos" id="cargos" style="width:300px;height: 80px">
				<?php
				/*$query01 = $bd->consultar($sql_cargo);
				while($row01=$bd->obtener_fila($query01,0)){
					echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
				}*/?></select></td>
			
				<td width="12%">Fecha Desde:</td>-->
				<td width="12%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" value="<?php echo date("d-m-Y") ?>">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
				<td width="7%"><?php echo $leng['region'];?>: </td>
				<td width="14%"><select name="region" id="REGION" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
					$query01 = $bd->consultar($sql_region);
					while($row01=$bd->obtener_fila($query01,0)){
						echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
					}?></select></td>

					<td width="7%"><?php echo $leng['estado'];?>: </td>
					<td width="14%" colspan="2"><select name="estado" id="ESTADO" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_estado);
						while($row01=$bd->obtener_fila($query01,0)){
							echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
						}?></select></td>
						<td>
							<input type="hidden" name="body_cubrir" id="body_cubrir" value="" />
							<input type="hidden" name="reporte" id="reporte" value=""/>
							<input type="hidden" name="usuario" id="usuario" value="<?php echo $us;?>" />

							<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
							<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
							<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
						</td>
						<td width="7%"><?php echo $leng['cliente']?>:</td>
						<td width="14%"><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')" required>

							<?php
							echo $select_cl;
							$query01 = $bd->consultar($sql_cliente);
							while($row01=$bd->obtener_fila($query01,0)){
								echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
							}?></select>
						</td>
						<td><?php echo $leng['ubicacion']?>: </td>
						<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;" required>
							<option value="TODOS">TODOS</option>
						</select>
					</td>
					<td width="5%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
						onclick=" Add_filtroX()" ></td>
					</tr>   
				</table><hr /><div id="contenido_listar" class="listar"></div>
				<div align="center"><br/>
					<span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
						class="readon art-button">
					</span>&nbsp;
						<input type="submit" name="submit" hidden="hidden" id="submit_reporte">
						<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0" onclick="{$('#reporte').val('pdf'); $('#submit_reporte').click();}" width="25px">
						<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
						onclick="{$('#reporte').val('excel'); $('#submit_reporte').click();}" width="25px">
				</div>
			</form>
