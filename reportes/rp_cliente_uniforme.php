<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = '716';
$mod     =  $_GET['mod'];
require_once('sql/sql_report.php');
require_once('autentificacion/aut_verifica_menu.php');

$bd = new DataBase();

$archivo = "reportes/rp_cliente_uniforme_det.php?Nmenu=$Nmenu&mod=$mod";
$titulo  = " Reporte ". $leng['ubicacion']." Uniforme";

?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var region      = $( "#region").val();
	var estado      = $( "#estado").val();
	var ciudad      = $( "#ciudad").val();
	var cliente      = $( "#cliente").val();
	var ubicacion      = $( "#ubicacion").val()
	var sub_linea  = $( "#stdID").val();
	var error = 0;
	var errorMessage = ' ';

	if(error == 0){

		var parametros = {
			"region" : region,
			"estado" : estado,
			"ciudad" : ciudad,
			"cliente" : cliente,
			"ubicacion" : ubicacion,
			"sub_linea":sub_linea
		};
		$.ajax({
			data:  parametros,
			url:   'ajax_rp/Add_cliente_uniforme.php',
			type:  'post',
			beforeSend: function () {
				$("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
			},
			success:  function (response) {
				$("#listar").html(response);
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

<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
	<hr /><table width="100%" class="etiqueta">
<tr>
					<td><?php echo $leng['region']?>:</td>
					<td><select name="region" id="region" style="width:120px;">
						<option value="TODOS">TODOS</option>
						<?php
						$query01 = $bd->consultar($sql_region);
						while($row01=$bd->obtener_fila($query01,0)){
							echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
						}?></select></td>
							<td><?php echo $leng['estado']?>: </td>
							<td><select name="estado" id="estado" style="width:120px;">
								<option value="TODOS">TODOS</option>
								<?php
								$query01 = $bd->consultar($sql_estado);
								while($row01=$bd->obtener_fila($query01,0)){
									echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
								}?></select></td>
								<td><?php echo $leng['ciudad']?>: </td>
								<td><select name="ciudad" id="ciudad" style="width:120px;">
									<option value="TODOS">TODOS</option>
									<?php
									$query01 = $bd->consultar($sql_ciudad);
									while($row01=$bd->obtener_fila($query01,0)){
										echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
									}?></select></td>
														<td width="5%" rowspan="2">
		<img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" Add_filtroX()">
		</td>
								</tr>

								<tr>
									<td class="etiqueta"><?php echo $leng['cliente']?> :</td>
									<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')">
										<option value="TODOS"> TODOS</option>
										<?php $query02 = $bd->consultar($sql_cliente);
										while($row02=$bd->obtener_fila($query02,0)){
											echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
										}?></select></td>
										<td class="etiqueta"><?php echo $leng['ubicacion'];?> : </td>
										<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
											<option value="TODOS">TODOS</option>
										</select></td>
												<td>Sub Linea:</td>
												<td colspan="2"><input  id="stdName" type="text" size="22"  style="width:200px" />
													<input type="hidden" name="sub_linea" id="stdID" value=""/></td>
													<td>&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
														<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
														<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
														<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
														<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
														<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td>
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
													<input type="text" name="archivo" value="<?php echo $archivo; ?>" hidden="hidden">
													<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
													onclick="{$('#reporte').val('pdf');$('#procesar').click();}" width="25px" title="imprimir a pdf">

													<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
													onclick="{$('#reporte').val('excel');$('#procesar').click();}" width="25px" title="imprimir a excel">
												</div>
											</form>
											<script type="text/javascript">
												r_cliente = $("#r_cliente").val();
												r_rol     = $("#r_rol").val();
												usuario   = $("#usuario").val();

												new Autocomplete("stdName", function() {
													this.setValue = function(id) {
            document.getElementById("stdID").value = id; 
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
		return "autocompletar/tb/sub_linea_base_serial_uniforme.php?q="+this.text.value });
		</script>

