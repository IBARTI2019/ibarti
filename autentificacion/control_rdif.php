<script type="text/javascript">
	var ficha = "";
	function Validar(){
       if($("#linea_1").attr("disabled")){
			validarCamp('');
		}else{
			var numX     = parseInt(document.getElementById('incremento').value);
			var producto   = document.getElementById('producto_'+numX+'').value;
			var cod_talla   = document.getElementById('cod_talla_'+numX+'').value;
			comprobarTalla(ficha, producto,cod_talla,function(tallas_iguales){
				if(tallas_iguales){
					var cantidad = Number(document.getElementById('cantidad_'+numX+'').value);
					var cantidad_max   = Number(document.getElementById('cantidad_'+numX+'').getAttribute("max"));
					if(cantidad > cantidad_max){
						toastr.error(cantidad+ " La Cantidad Supera el Stock Actual \n Stock Actual = "+cantidad_max);
					}else{
						$("#salvar").click();
					}
				}else{
					toastr.error("La talla no corresponde a la de la Configuracion!..")
				}
			});
		}
	}

	var cod_producto = "";
function grabar(metodo){
		var numX     = parseInt(document.getElementById('incremento').value);
		
		for (let i = 1; i < numX; i++) {
    		select01  = document.getElementById('linea_'+i+'').value;
			select02  = document.getElementById('sub_linea_'+i+'').value;
			select03  = document.getElementById('producto_'+i+'').value;
			select04  = document.getElementById('almacen_'+i+'').value;
			existecontrolrdif(select01,select02,select03,select04);
		}
}		
function agregarfilacontrolrfdi(fila1,fila2,fila3,fila4){  // LINEA //
	if(fila1!=''){
		var valor = "sc_maestros/sc_controlrfid.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("fila1="+fila1+"&fila2="+fila2+"&fila3="+fila3+"&fila4="+fila4+"");
	}else{
		alert("Debe de Seleccionar Un Concepto Para Vienen ");
	}
}
function ActivarSubLinea(codigo, relacion, contenido){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_conceptos_vienen.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo+"&relacion="+relacion+"");
	}else{
		alert("Debe de Seleccionar Un Concepto Para Vienen ");
	}
}

function comprobarTalla(ficha, producto,cod_talla,callback){ 
	if(ficha!=''){
		var valor = "ajax/Add_prod_comp_talla.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				var resp = JSON.parse(ajax.responseText);
				callback(resp.cod_talla == cod_talla);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("ficha="+ficha+"&producto="+producto+"&cod_talla="+cod_talla+"");
	}else{
		alert("Debe de Seleccionar Un Producto policia ");
	}
}

function Activar01(codigo, relacion, contenido){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_prod_sub_linea.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo+"&relacion="+relacion+"");
	}else{
		alert("Debe de Seleccionar Un Producto  ");
	}
}
function existecontrolrdif(fila1,fila2,fila3,fila4) {
	$.ajax({
		data: {'fila1': fila1, 'fila2':fila2, 'fila3':fila3,'fila4':fila4},
		url: 'ajax/buscarcontrolrfid.php',
		type: 'post',
		success: function(response) {
			var resp = JSON.parse(response);
			if(resp[0]){
				alert(resp);
			}else if(fila1 != ""){
				 agregarfilacontrolrfdi(fila1,fila2,fila3,fila4);
				 toastr.warning("Registros Guaradados con exitos...");
	
			}

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}
function cantidad_maxima(cod_almacen,relacion) {
	$.ajax({
		data: { 'producto': cod_producto, 'almacen':cod_almacen, 'cod_ficha': ficha},
		url: 'ajax/Add_prod_dot_max.php',
		type: 'post',
		success: function(response) {
			var resp = JSON.parse(response);
			if(resp){
				$("#cantidad_"+relacion).attr('max',resp[0]);
			}else if(cod_almacen != ""){
				$("#cantidad_"+relacion).attr('max',0);
				toastr.warning("Sin Existencia en este almacen");
			}

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function Activar_almacen(codigo, relacion, contenido){  // LINEA //
	cod_producto = codigo;
	if(codigo!=''){
		var valor = "ajax/Add_prod_almacen.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo+"&relacion="+relacion+"");
	}else{
		alert("Debe de Seleccionar Una Planificacion ");
	}
}

function mostrar_dotacion_ficha(cod_ficha){
	ficha = cod_ficha;
	var parametros = {'cod_ficha':cod_ficha};
	$.ajax({
		data:  parametros,
		url:   'ajax/Add_dotacion_ficha.php',
		type:  'post',
		success:  function (response) {
			var nuevafila= "";
			var resp = JSON.parse(response);
			$("#datos_dotacion_detalle").html("");
			if(resp.length > 0){
				if (resp.error) {
					alert(resp.mensaje);
					$("#cliente_ficha").val("");
					$("#cliente_ficha").val("");
				} else {
					resp.forEach((d,i)=>{
						var aplica = d.aplica == null ? 'NO' : 'SI';
						if(i==0){
							$("#cliente_ficha").val(d.cod_cliente);
							$("#ubicacion_ficha").val(d.cod_ubicacion);
						}
						nuevafila= "<tr><td>" +
						d.sub_linea + "</td><td>" +
						d.talla + "</td><td>" +
						d.cantidad  + "</td><td>" +
						d.ult_dotacion  + "</td><td>" +
						aplica + "</td></tr>";

						$("#datos_dotacion_detalle").append(nuevafila);
					});
					$("#linea_1").attr('disabled',false);
				//$("#detalle").show();
			}
		}else{
			$("#linea_1").attr('disabled',true);
			nuevafila= '<tr><td colspan="5">Sin Configuracion</td></tr>';
			$("#datos_dotacion_detalle").append(nuevafila);
			$("#cliente_ficha").val("");
			$("#ubicacion_ficha").val("");
		}
			//$("#datos_dotacion").show();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			toastr.error(xhr.status);
			toastr.error(thrownError);}
		});
}

function mostrar_cliente_ficha(cod_ficha){
	ficha = cod_ficha;
	var parametros = {'cod_ficha':cod_ficha};
	$.ajax({
		data:  parametros,
		url:   'ajax/Add_cliente_ficha.php',
		type:  'post',
		success:  function (response) {
			var resp = JSON.parse(response);
			if (resp.error) {
				alert(resp.mensaje);
				$("#cliente_ficha_nombre").val("");
				$("#ubicacion_ficha_nombre").val("");
			} else {
				$("#tr_cliente_ficha").show();
				$("#cliente_ficha_nombre").val(resp.cliente);
				$("#ubicacion_ficha_nombre").val(resp.ubicacion);
			}			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			toastr.error(xhr.status);
			toastr.error(thrownError);}
		});
}

function borrar2(num) {
	borrarElement("Contenedor02", "Tabla"+num+"");
}

function validarAlcance(numX) {
	var sub_linea = $("#sub_linea_"+numX).val();
	var ficha= "000003";
	
	if (numX) {
		var parametros = {'sub_linea': sub_linea,'ficha': ficha};
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/views/validarUniforme.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if(resp.error){
					toast.danger(resp.mensaje);
				}else{
					if(resp.length > 0){
						prod_dotacion_det(numX);
					}else{	
						if(confirm("Este Control RFDI no existe . Desea Registrarlo...?")){
							prod_dotacion_det(numX);
						}
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	} else {
		alert("Error en Data para aperturar");
	}
}

function validarCamp(metodo){
	var numX     = parseInt(document.getElementById('incremento').value);
	if(metodo == 'eliminar'){
		document.getElementById('tr_1_'+numX).remove();
		document.getElementById('incremento').value = numX -1;
	}else{
		var valido     = 1;
		//	alert(metodo);
		var mensaje   = " ";
		mensaje01 = " Debe Seleccionar La Linea \n";
		mensaje02 = " Debe Seleccionar La SubLinea \n";
		mensaje03 = " Debe Seleccionar Un Productos \n";
		mensaje04 = " Debe Ingresar la Cantidad \n ";
		mensaje04 = " Debe Seleccionar  Un Almacen \n ";

		select01  = document.getElementById('linea_'+numX+'').value;
		select02  = document.getElementById('sub_linea_'+numX+'').value;
		select03  = document.getElementById('producto_'+numX+'').value;
		select04  = document.getElementById('almacen_'+numX+'').value;
		input01   = 0;
		input01Max   = 0;
        
		mensaje05 = input01+ " La Cantidad Supera el Stock Actual \n Stock Actual = "+input01Max;

		if(select01 == ""){
			valido++;
			mensaje += mensaje01;
		}
		if(select02 == ""){
			valido++;
			mensaje += mensaje02;
		}
		if(select03 == ""){
			valido++;
			mensaje += mensaje03;
		}
		if(select04 == ""){
			valido++;
			mensaje += mensaje05;
		}
		
		if(valido ==  1){
				validarAlcance(numX);
			}else{
				toastr.error(valido);
			}
		}
}


function prod_dotacion_det(numX){
	var num     = numX+1;
	if(numX != ''){
		var valor = "ajax/Add_controlpdfi_detalles.php";
		var contenido = "Contenedor01_"+numX+"";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
				document.getElementById('incremento').value = num;
				spryN(num);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+num+"");
	}else{
		alert("Falta Codificacion ");
	}
}

function  dotacion_ref(codigo){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_prod_dotacion_ref.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById("Contenedor02").innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo+"");
	}
}

function Anular(){  // CARGAR EL MODULO DE AGREGAR//

	if (confirm("¿Esta Seguro De Anular Este Registro")) {
		document.getElementById("metodo").value = "anular";
		document.add.submit();
	}
}

</script>
<?php
$metodo='modificar';
if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();

	$sql = "SELECT DISTINCT T2.descripcion vienen, T3.descripcion planificacion,T1.feriado Feriado, t4.descripcion registro FROM control_rfid T1 INNER JOIN conceptos T2 ON T1.cod_concepto_viene = T2.codigo INNER JOIN conceptos t3 on T1.cod_concepto_planif=T3.codigo INNER JOIN conceptos T4 ON T1.cod_concepto_registro = T4.codigo where T1.codigo='$codigo' GROUP by T1.codigo  " ;
	
	$query_dot         = $bd->consultar($sql);
	

}else{
	$codigo        = "";
	$vienen        ="";
	$planificacion="";
    $feriado ="";
	$registro="";
}

?>

<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<form action="sc_maestros/sc_<?php echo $archivo?>.php" method="post" name="add" id="add">
	<br>
	<div align="center" class="etiqueta_title"> Control RFDI</div>
	<hr />
	<div id="Contenedor01"></div>
	<fieldset class="fieldset">
							
						<fieldset class="fieldset" id="detalle">
							<legend>Detalles: </legend>
							<table width="100%" align="center">
								<tr>
									<td class="etiqueta" width="25%">Vienen:</td>
									<td class="etiqueta" width="25%">Planificacion:</td>
									<td class="etiqueta" width="25%">Feriado:</td>
									
									<td class="etiqueta" width="17%">Registro:</td>
									<td class="etiqueta" width="25%"><?php if($metodo == "agregar"){ ?>
										<span style="float: right;" align="center" ><img src="imagenes/ico_agregar.ico" class="imgLink" title="Agregar Item" onclick="validarCamp('Agregar')" />
											<img  border="null" width="20px" height="20px" src="imagenes/borrar.bmp" title="Borrar Registro" id="borrar_dot" onclick="validarCamp('eliminar')" /></span><?php } ?>
										</td>
									</tr>
									<?php if($metodo == "agregar"){ ?>
										<tr class="text" >
											<td id="select_1_1">
												<select name="linea_1" id="linea_1" style="width:150px;"
												onchange="ActivarSubLinea(this.value, '1', 'select_2_1')" >
												<option value="">Seleccione... </option>
												<?php  	$sql = "SELECT conceptos.codigo, conceptos.abrev, conceptos.descripcion FROM conceptos, horarios WHERE horarios.cod_concepto = conceptos.codigo GROUP BY conceptos.codigo AND conceptos.status= 'T'";
												$query = $bd->consultar($sql);
												while($datos=$bd->obtener_fila($query,0)){
													?>
													<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
												<?php }?>
											</select></td>
											<td id="select_2_1">
											<select name="sub_linea_1" id="sub_linea_1" style="width:150px;" >
												<option value="">Seleccione... </option>
												<?php  	$sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
												$query = $bd->consultar($sql);
												while($datos=$bd->obtener_fila($query,0)){
													?>
													<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
												<?php }?>
											</select></td>
											<td id="select_3_1"><select name="producto_1" id="producto_1" style="width:150px;" >
												<option value="">Seleccione... </option>
												<option value="T">Si</option>
 												 <option value="F" selected>No</option>
  												
											</select></td>
											<td id="select_4_1"><select name="almacen_1" id="almacen_1" style="width:150px;" >
												<option value="">Seleccione... </option><?php  
												$sql = "SELECT conceptos.codigo, conceptos.abrev, conceptos.descripcion FROM conceptos WHERE conceptos.`status` = 'T' AND asist_diaria = 'T';";
												$query = $bd->consultar($sql);
												while($datos=$bd->obtener_fila($query,0)){
													?>
													<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
												<?php }?>

											</select></td>
											<td>&nbsp;<input type="hidden" name="relacion_1" value="1" /></td>
										</tr>
									<?php }else{
										$sql = "SELECT DISTINCT T2.descripcion vienen, T3.descripcion planificacion,T1.feriado feriado, t4.descripcion registro FROM control_rfid T1 INNER JOIN conceptos T2 ON T1.cod_concepto_viene = T2.codigo INNER JOIN conceptos t3 on T1.cod_concepto_planif=T3.codigo INNER JOIN conceptos T4 ON T1.cod_concepto_registro = T4.codigo where T1.codigo='$codigo' GROUP by T1.codigo  ";
										$query = $bd->consultar($sql);
										while($datos=$bd->obtener_fila($query,0)){
											$cod_linea    = $datos["vienen"];
											$linea        = $datos["planificacion"];
											$cod_sub_linea    = $datos["feriado"];
											$sub_linea        = $datos["registro"];
											
											?>
											<tr class="text">
												<td id="select_1_1"><select name="linea_1" id="linea_1" style="width:180px;" disabled="disabled"
													>
													<option value="<?php echo $cod_linea;?>"><?php echo $linea;?></option>
													<?php  	$sql = "SELECT conceptos.codigo, conceptos.abrev, conceptos.descripcion FROM conceptos, horarios WHERE horarios.cod_concepto = conceptos.codigo GROUP BY conceptos.codigo AND conceptos.status= 'T' ";
													$query2 = $bd->consultar($sql);
													while($datos2=$bd->obtener_fila($query2,0)){
														?>
														<option value="<?php echo $datos2[0];?>"><?php echo $datos2[1];?></option>
													<?php }?>
												</select></td><td id="select_3_1"><select name="linea_1" id="linea_1" style="width:180px;" disabled="disabled">
													<option value="<?php echo $cod_sub_linea;?>"><?php echo $sub_linea;?></option>
												</select></td>
												<td id="select_2_1"><select name="producto_1" id="producto_1" style="width:250px;" disabled="disabled">
													<option value="<?php echo $cod_producto;?>"><?php echo $producto;?></option>
												</select></td>
												<td id="input04"><input type="text" name="cantidad_1" id="cantidad_1" maxlength="15" size="15" readonly="readonly"
													value="<?php echo $cantidad;?>"/></td>
													<td>&nbsp;<input type="hidden" name="relacion_1" value="1" /></td>
												</tr>

											<?php }}?>
										</table>
										<div id="Contenedor01_1"></div>
										<div align="center">
											<?php if ($metodo == "agregar"){ ?>
												<span class="art-button-wrapper">
													<span class="art-button-l"> </span>
													<span class="art-button-r"> </span>
													<input type="button" name="validar"  id="validar" value="Guardar" class="readon art-button" onclick="grabar('Agregar')" />
													<input type="submit" name="salvar"  id="salvar" hidden="hidden" />
												</span>&nbsp;
												<span class="art-button-wrapper">
													<span class="art-button-l"> </span>
													<span class="art-button-r"> </span>
													<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
													</span>&nbsp;  <?php }else{ ?>

														<span class="art-button-wrapper">
															<span class="art-button-l"> </span>
															<span class="art-button-r"> </span>
															<input type="button" name="pdf" onClick="Pdf()" value="Gurdar" class="readon art-button" />
														</span>&nbsp;
													

													<?php } ?>
													<span class="art-button-wrapper">
														<span class="art-button-l"> </span>
														<span class="art-button-r"> </span>
														<input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
													</span>
													<input type="hidden" name="metodo" id="metodo" value="<?php echo $metodo;?>" />
													<input type="hidden" name="proced" value="<?php echo $proced;?>" />
													<input type="hidden" name="usuario" value="<?php echo $usuario;?>" />
													<input type="hidden" name="href" value="<?php echo $archivo2;?>"/>
													<input type="hidden" name="incremento" id="incremento" value="1" />
													<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
													<input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
													<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
												</div>
											</fieldset>
										</form>
										<hr />
										<div id="Contenedor02"></div>

										<script type="text/javascript">
											var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
												validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});

											var input02  = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});
											var input03  = new Spry.Widget.ValidationTextField("input03", "none", {validateOn:["blur", "change"]});
											var input04 = new Spry.Widget.ValidationTextField("input04", "real", {validateOn:["blur", "change"], useCharacterMasking:true});

											var select01  = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
											var select_1_1 = new Spry.Widget.ValidationSelect("select_1_1", {validateOn:["blur", "change"]});


											function spryN(num){
												var input04 = new Spry.Widget.ValidationTextField("input04_"+num+"", "real", {validateOn:["blur", "change"], useCharacterMasking:true , isRequired:false});
											}

											r_cliente = $("#r_cliente").val();
											r_rol     = $("#r_rol").val();
											usuario   = $("#usuario").val();
											filtroValue = $("#paciFiltro").val();

											new Autocomplete("stdName", function() {
												this.setValue = function(id) {
													document.getElementById("stdID").value = id; 
													toastr.clear(toastr.getLastToast);
													mostrar_dotacion_ficha(id);
													mostrar_cliente_ficha(id);
													
            // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
        return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
    </script>
