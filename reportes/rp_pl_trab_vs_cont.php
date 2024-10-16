<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<script type="text/javascript" src="packages/planif/planificacion/controllers/planificacionRP.js"></script>

<?php 
$Nmenu   = '5302'; 
$mod     =  $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase(); 
$archivo = "reportes/rp_pl_trab_vs_cont_det.php?Nmenu=$Nmenu&mod=$mod.php";	
$titulo  = " PLANIFICACION VS CONTRATACION ";

$titulo      = "REPORTE $titulo";
?>
<script language="JavaScript" type="text/javascript">
	function Add_filtroX(){}
</script>	
<div id="modalRP" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="$('#modalRP').hide()" >&times;</span>
			<span id="modal_titulo2"></span>
		</div>
		<div class="modal-body">
			<div id="RP"></div>
			<div id="modal_contenidoRP"></div>
			<form action="packages/planif/planificacion/views/rp_planif_serv.php" method="post" name="add_planif_serv_modal" id="add_planif_serv_modal" method="post" target="_blank">
				<input type="hidden" name="contratacion" id="cod_contratacion_serv" value="">
				<input type="hidden" name="ubicacion" id="cod_ubic_serv" value="">
				<input type="hidden" name="body_planif" id="body_planif" value="">
				<input type="hidden" name="reporte" id="reporte_serv" value="">
			</form>
		</div>
	</div>
</div>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div> 
<form name="form_reportes" id="form_reportes_planif" action="<?php echo $archivo;?>"  method="post" target="_blank">
	<hr />
	<table width="100%" class="etiqueta">
		<tr>
			<td width="15%" class="etiqueta"><?php echo $leng['cliente']?></td>
			<td width="35%"><select name="cliente" id="cliente" style="width:200px" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '200'); cl_apertura(this.value)" required>
				<option value="">Seleccione</option>
				<?php 
				$query01 = $bd->consultar($sql_cliente);		
				while($row01=$bd->obtener_fila($query01,0)){							   							
					echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';						   		
				}?>
			</select></td>
			<td width="15%" class="etiqueta"><span id="apertura_texto">Apertura De Planificacion</span></td>
			<td width="35%"><span id="apertura_cont"><select name="apertura" id="apertura" required  style="width:200px" onchange="planif_contratacion()">
				<option value="">Seleccione</option></select></td>
			</tr>
			<tr>

				<td class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion']?>:</span> </td>
				<td><span id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:200px;">
					<option value="">Seleccione</option></select></span></td>
					<td class="etiqueta"><span id="contratacion_texto"><?php echo $leng['contratacion']?>:</span> </td>
					<td><div id="contratacion_cont"></div></td>
				</tr>
				<tr>
					<td height="8" colspan="4" align="center"><hr></td>
				</tr>
			</table>
			<div id="cont_contratacion_det"></div>
			<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
			<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />   </td>
			<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo2;?>" />
			<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
			<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
			<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
			<input type="hidden" name="contratacion" id="contrato" value=""/>
			<input type="hidden" name="reporte" id="reporte" value=""/>
		</tr>            
	</table>
	<div align="center"><br/>
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" 
			class="readon art-button">
		</span>
		<input hidden="hidden" type="submit"> 
		<img class="imgLink" src="imagenes\detalle2.bmp" alt="Ver Planificacion" title="Ver Planificacion"
		onclick="B_reporte()">
	</div>
</form>
<script type="text/javascript">
	function cl_apertura(){
		var cliente = $("#cliente").val();
		var parametros = {"codigo": cliente};
		$.ajax({
			data:  parametros,
			url:   'packages/planif/planificacion/views/Add_planif_apertura.php',
			type:  'post',
			beforeSend: function(){
				$("#apertura").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
			},
			success:  function (response) {
				$("#apertura").html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}

	function Ocultar_contratacion(){
		$('#contratacion_texto').css("display", "none");
		$('#contratacion_cont').css("display", "none");
	}

	function Ocultar_ubicacion(){
		$('#ubicacion_texto').css("display", "none");
		$('#ubicacion_cont').css("display", "none");
	}

	function planif_contratacion(){
		var cliente = $("#cliente").val();
		var apertura = $("#apertura").val();
		var usuario = $("#usuario").val();
		var parametros = {"codigo": apertura, "cliente": cliente,
		"usuario": usuario};
		$.ajax({
			data:  parametros,
			url:   'ajax_rp/Add_planif_contratacion.php',
			type:  'post',
			beforeSend: function(){
				$("#contratacion_cont").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
			},
			success:  function (response){
				var resp = JSON.parse(response);
				if(resp.length == 0){
					alert("No Existe Contratacion!..");
				}else{
					Habilitar_contratacion();
					$("#contrato").val(resp[0]['codigo']);
					$("#contratacion_cont").html(''+resp[0]['descripcion']+' ('+resp[0]['fecha_inicio']+') ');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}

	function Habilitar_ubicacion(){
		$('#ubicacion_texto').css("display", "block");
		$('#ubicacion_cont').css("display", "block");
	}

	function Habilitar_contratacion(){
		$('#contratacion_texto').css("display", "block");
		$('#contratacion_cont').css("display", "block");
	}

	function B_reporte(){
		var errorMessage = '';
		var contratacion = $("#contrato").val();
		var ubicacion = $("#ubicacion").val();
		var apertura = $("#apertura").val();
		var usuario = $('#usuario').val();
		if(ubicacion == 'TODOS' || ubicacion == '' || apertura== ''){
			errorMessage = 'Necesita completar los campos para generar el reporte!..';
		}
		var parametros = {"ubicacion":ubicacion,"apertura" : apertura,"contratacion":contratacion,"usuario":usuario};
		if(errorMessage == ''){
			$.ajax({
				data:  parametros,
				url:   'ajax_rp/Add_planif_servicio_min.php',
				type:  'post',
				beforeSend: function(){
					$('#modalRP').show();
					$("#RP").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px"> Procesando...');
					$("#modal_titulo2").text("Planificacion Servicio");
					limpiarContenedor('modal_contenidoRP');
				},
				success:  function (response) {
					var resp = JSON.parse(response);
					if(typeof resp['servicio'] == 'undefined'){
						$("#RP" ).html('Este Cliente no ha sido Planificado!..');
					}else{
						rp_planif_trab_serv(resp,'modal_contenidoRP',()=>$('#body_planif').val($('#t_reporte').html()));
						$("#RP" ).html('<img class="imgLink" width="25px" src="imagenes/excel.gif" border="0" onclick="rp_planif_serv(\'excel\')">');
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

	function rp_planif_serv(tipo){
		var ubic   = $("#ubicacion").val();
		if(ubic != ""){
			$("#reporte_serv").val(tipo);
			$("#cod_ubic_serv").val(ubic);
			$("#cod_contratacion_serv").val($("#contrato").val());
			$("#add_planif_serv_modal").submit();
		}
	}
</script>