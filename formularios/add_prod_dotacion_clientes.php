<script type="text/javascript">
var vectorproducto=[];
function buscarprod(elem,cod){
 console.log(elem,cod);    

}
    
function filtrar(elem, cod){
    console.log(elem);
    var ValorBusqueda = new RegExp($(elem).val(), 'i');
    
        $('#table'+cod+' .detail tr').hide();
        $('#table'+cod+' .detail tr').filter(function (i) {
			
            return ValorBusqueda.test($('#'+cod+''+i ).text());
          }).show();
    }

 function BotonVisible()
{
  document.getElementById("clickMe").style.visibility="visible";
}    
function demoShow()
{
  document.getElementById("clickMe").style.visibility="hidden";
}    
	var ficha = "";
	function Validar(){

		if($("#linea_1").attr("disabled")){
			validarCamp('');
		}else{
			$("#salvar").click();
             
            }
	}

	var cod_producto = "";
	function Pdf(){

		$('#pdf').attr('action', "reportes/rp_inv_prod_dotacion.php");
		$('#pdf').submit();
	}
    function VerEANS(numX,cod_prod,esans){
        var valorX=numX;
        var valorC=cod_prod;
        var valorE=esans;
        $('#cod_prod').attr('action', "reportes/rp_inv_prod_dotacion.php");
		$('#cod_prod').submit();
		
	}

function ActivarSubLinea(codigo, relacion, contenido){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_prod_linea.php";
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
		alert("Debe de Seleccionar Un Producto ");
	}
}
function ActivarProductos(codigo, relacion, contenido){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_prod_lineaojo.php";
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
		alert("Debe de Seleccionar Un Producto ");
	}
}
function comprobarTalla(ficha, producto,cod_talla,callback){ 
    console.log('policia:'+ ficha + ' '+ producto+' '+ cod_talla);
	if(ficha!=''){
		var valor = "ajax/Add_prod_comp_talla_clientes.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				var resp = JSON.parse(ajax.responseText);
				callback(resp.producto == producto);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("ficha="+ficha+"&producto="+producto+"");
	}else{
		alert("Debe de Seleccionar Un Producto ");
	}
}

function Activar01(codigo, relacion, contenido){  // LINEA //
	if(codigo!=''){
		var valor = "ajax/Add_prod_sub_linea_clientes.php";
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
		alert("Debe de Seleccionar Un Producto ");
	}
}

function cantidad_maxima(cod_almacen,relacion) {
	$.ajax({
		data: { 'producto': cod_producto, 'almacen':cod_almacen, 'cod_ficha': ficha},
		url: 'ajax/Add_prod_dot_max_clientes.php',
		type: 'post',
		success: function(response) {
			var resp = JSON.parse(response);
            console.log('Politica:' + resp);
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
		var valor = "ajax/Add_prod_almacen_clientes.php";
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
		alert("Debe de Seleccionar Un Producto ");
	}
}

function mostrar_dotacion_ficha(cod_ficha){
	ficha = cod_ficha;
	var parametros = {'cod_ficha':cod_ficha};
	$.ajax({
		data:  parametros,
		url:   'ajax/Add_dotacion_ficha_clientes.php',
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
						if(i==0){
							$("#cliente_ficha").val(d.cod_cliente);
							$("#ubicacion_ficha").val(d.cod_ubicacion);
						}
						nuevafila= "<tr><td>" +
						d.sub_linea + "</td><td>" +
						d.cantidad  + "</td><td>" +
						d.ult_dotacion  + "</td></tr>";

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

function borrar2(num) {
	borrarElement("Contenedor02", "Tabla"+num+"");
}

function validarAlcance(numX) {
	var sub_linea = $("#sub_linea_"+numX).val();
	var ficha = $("#stdID").val();
	var cod_prod=document.getElementById('producto_'+numX+'').value;
    var cantidad= Number(document.getElementById('cantidad_'+numX+'').value);
    var esans='0';
	if (numX) {
		var parametros = { "sub_linea": sub_linea, "ficha": ficha };
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/views/validarUniforme_clientes.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if(resp.error){
					toast.danger(resp.mensaje);
				}else{
					if(resp.length > 0){
                        // ajax
                        $.ajax({
		                  data: { 'producto': cod_prod},
		                url:'packages/cliente/cl_ubicacion/views/validarEANS_clientes.php',
		                  type: 'post',
		                  success: function(response) {
			             var respeans = JSON.parse(response);
                        
			            if(respeans.length > 0){
                            esans='button';
                            metodo='agregar';
				            comodin="";
                            prod_dotacion_modal(numX,cod_prod,esans,metodo,cantidad,comodin);
                          
                           }else {
                               esans='hidden';
                               prod_dotacion_det(numX);
                            }
		               },
		               error: function(xhr, ajaxOptions, thrownError) {
			                          alert(xhr.status);
			                       alert(thrownError);
		                        }
	                    });
                        
                        //fin nuevo ajax
                        
                       
                        
                       
                        
                        //activar modal si el producto tiene eans
                        
					}else{	
						if(confirm("Esta sub linea no aplica para el alcance de la ubicación. Desea continuar?")){
                          esans='button';
                          metodo="agregar";
                          comodin="";    
				          prod_dotacion_modal(numX,cod_prod,esans,metodo,cantidad,comodin);
                            
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
    var anteriorX;
    var anterior;
 
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
mensaje04 = " Debe Seleccionar Un Almacen \n ";
mensaje06 = " El producto ya esta en el detalle de la dotacion \n ";
select01  = document.getElementById('linea_'+numX+'').value;
select02  = document.getElementById('sub_linea_'+numX+'').value;
select03  = document.getElementById('producto_'+numX+'').value;
anteriorX=numX-1; 

if (anteriorX>0){
    anterior=   document.getElementById('producto_'+anteriorX+'').value;
    vectorproducto[numX]=anterior;
    var compara=select03;
     
} else{
    var compara =select03; 
   vectorproducto[0]=compara;
    anterior="";  
}
select04  = document.getElementById('almacen_'+numX+'').value;
input01   = Number(document.getElementById('cantidad_'+numX+'').value);
input01Max   = Number(document.getElementById('cantidad_'+numX+'').getAttribute("max"));

mensaje05 = input01+ " La Cantidad Supera el Stock Actual \n Stock Actual = "+input01Max;
if(select03 =! "" ){
     
    for(var i=1; i<vectorproducto.length; i++) {
       console.log("Valor vector:"+ i+ vectorproducto[i]);  
        if (compara == vectorproducto[i]){
           valido++;
	       mensaje += mensaje06;
        }
          
     };
	
}
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
if(input01 == ""){
	valido++;
	mensaje += mensaje04;
}

/* if(input01 > input01Max){
	valido++;
	mensaje += mensaje05;
} */
     /////  validar ///
     if(valido ==  1){
		validarAlcance(numX);
     }else{
     	toastr.error(mensaje);
     }
 }
}

function prod_dotacion_modal(numX,cod_prod,esans,metodo,cantidad,comodin){
	var num     = numX+1;
	if(numX != ''){
		var valor = "ajax/Add_prod_dotacion_det_clientes_modal.php";
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
		ajax.send("codigo="+cod_prod+"&numero="+num+"&tieneeans="+esans+"&metodo="+metodo+"&cantidad="+cantidad+"&comodin="+comodin+"");
	}else{
		alert("Falta Codificacion ");
	}
}

function prod_dotacion_det(numX){
	var num     = numX+1;
    console.log("cirilo"+num);
	if(numX != ''){
		var valor = "ajax/Add_prod_dotacion_det_clientes.php";
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
		var valor = "ajax/Add_prod_dotacion_ref_clientes.php";
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
include_once('../funciones/funciones.php');
if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
    
	$sql = "SELECT DATE_FORMAT(prod_dotacion_clientes.fec_dotacion,'%Y-%m-%d')  fec_dotacion , v_cliente_ubic.cod_cliente, v_cliente_ubic.cod_ubicacion,
	v_cliente_ubic.ubicacion AS trabajador, prod_dotacion_clientes.descripcion,
	prod_dotacion_clientes.anulado,
	prod_dotacion_clientes.campo01, prod_dotacion_clientes.campo02,
	prod_dotacion_clientes.campo03, prod_dotacion_clientes.campo04,
	prod_dotacion_clientes.`status`
	FROM v_cliente_ubic , prod_dotacion_clientes
	WHERE prod_dotacion_clientes.codigo = '$codigo'
	AND v_cliente_ubic.cod_cliente = prod_dotacion_clientes.cod_cliente" ;
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);

	$fec_dotacion  = conversion($result["fec_dotacion"]);
	$ficha         = $result["cod_cliente"];
	$cedula        = $result["cod_ubicacion"];
	$trabajador    = $result["trabajador"];
	$descripcion   = $result["descripcion"];
	$campo01       = $result["campo01"];
	$campo02       = $result["campo02"];
	$campo03       = $result["campo03"];
	$campo04       = $result["campo04"];
	$anulado       = $result["anulado"];
	$activo        = $result["status"];

	$sql = "SELECT  CONCAT(prod_sub_lineas.descripcion,' (',prod_sub_lineas.codigo,') ') sub_linea,
	prod_dotacion_det_clientes.cantidad,
   IFNULL((SELECT CONCAT(MAX(prod_dotacion_clientes.fec_us_mod),'  (',prod_dotacion_det_clientes.cantidad,')') FROM prod_dotacion_clientes, prod_dotacion_det_clientes
   WHERE prod_dotacion_clientes.codigo = prod_dotacion_det_clientes.cod_dotacion
   AND prod_dotacion_clientes.cod_ubicacion = v_cliente_ubic.cod_cliente) ,'SIN DOTACION') ult_dotacion
   ,v_cliente_ubic.cod_cliente,v_cliente_ubic.cod_ubicacion
   FROM prod_dotacion_det_clientes LEFT JOIN
   productos ON 
	prod_dotacion_det_clientes.cod_sub_linea = productos.cod_sub_linea,prod_sub_lineas,v_cliente_ubic
   WHERE
   v_cliente_ubic.cod_cliente= '$ficha'
   AND prod_dotacion_det_clientes.cod_sub_linea = prod_sub_lineas.codigo
      GROUP BY prod_dotacion_det_clientes.cod_sub_linea";
	$query_dot         = $bd->consultar($sql);

}else{
	$codigo        = "";
	$fec_dotacion  = conversion($date);
	$ficha         = "";
	$cedula        = "";
	$trabajador    = "";
	$descripcion   = "";
	$campo01       = "";
	$campo02       = "";
	$campo03       = "";
	$campo04       = "";
	$anulado       = "F";
	$activo        = "T";
}
$proced      = "p_prod_dotacion_clientes";
$tieneeans="hidden";
?>

<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<form action="sc_maestros/sc_<?php echo $archivo?>.php" method="post" name="add" id="add">
	<br>
	<div align="center" class="etiqueta_title"> Dotacion De <?php echo $leng['ubicacion'];?></div>
	<hr />
	<div id="Contenedor01"></div>
	<fieldset class="fieldset">
		<legend>Encabezado: </legend>
		<table width="100%" align="left">
			<tr>
				<td class="etiqueta" width="13%">Codigo:</td>
				<td id="imput01" width="20%"><input type="text" name="codigo" size="15" value="<?php echo $codigo;?>"
					readonly="readonly"/><br>
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">Formato Invalido.</span></td>
					<td class="etiqueta" width="13%">Fecha:</td>
					<td id="fecha01" width="20%"><input type="text" name="fecha"  size="15" value="<?php echo $fec_dotacion;?>"/><br>
						<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
						<span class="textfieldInvalidFormatMsg">Formato Invalido.</span></td>
						<td class="etiqueta" width="13%">Descripci&oacute;n:</td>
						<td id="input02" width="20%"><input type="text" name="descripcion" maxlength="60" size="30"
							value="<?php echo $descripcion;?>"/><br>
							<span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
							<span class="textfieldMinCharsMsg">Debe Escribir mínimo 2 Caracteres.</span></td>
						</tr>
						<tr>
							<td class="etiqueta">Filtro:</td>
							<td id="select01"><select id="paciFiltro" style="width:150px" onchange="EstadoFiltro(this.value)" >
								<option value="codigo"><?php echo $leng['ubicacion'];?></option>
								<option value="nombre">Nombre de la Ubicacion</option>
							</select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
							<td class="etiqueta"><?php echo $leng['ubicacion'];?>:</td>
							<td colspan="2">
								<input  id="stdName" type="text" size="36" value="<?php echo $trabajador;?>"/>
								<span id="input03"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $ficha;?>"/>
									<input type="hidden" name="cliente" id="cliente_ficha">
									<input type="hidden" name="ubicacion" id="ubicacion_ficha"><br />
									<span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
									<span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span></td>
									<td colspan="2" class="etiqueta">Anulado: <?php echo valorS($anulado);?>
									<input type="hidden" name="anulado" id="anulado" value="<?php echo $anulado;?>"/></td>
								</tr>
							</table>
						</fieldset>
						<fieldset class="fieldset" id="datos_dotacion">
							<legend>Configuracion Alcance: </legend>
							<table width="100%" align="center" class="tabla_sistema">
								<thead>
									<tr>
										<th>SubLinea</th>
										<th>Cantidad</th>
										<th>Ultima Dotación</th>
									</tr>
								</thead>
								<tbody id="datos_dotacion_detalle">
									<?php 
									if($metodo == "modificar"){
										while ($datos= $bd->obtener_fila($query_dot,0)) {
											echo "<tr><td>" .$datos[0]."</td><td>"  .$datos[2]. "</td><td>"  .$datos[3]. "</td></tr>";
										}	
									}
									?>
								</tbody>
							</table>
						</fieldset>
						<fieldset class="fieldset" id="detalle">
							<legend>Detalle: </legend>
							<table width="100%" align="center">
								<tr>
									<td class="etiqueta" width="25%">Linea:</td>
									<td class="etiqueta" width="25%">SubLinea:</td>
									<td class="etiqueta" width="25%">Producto:</td>
									<?php if($metodo == "agregar"){ ?>
										<td class="etiqueta" width="25">Almacen</td>
									<?php }?>
									<td class="etiqueta" width="17%">Cantidad:</td>
									<td class="etiqueta" width="10%"><?php if($metodo == "agregar"){ ?>
										<span style="float: right;" align="center" ><img src="imagenes/ico_agregar.ico" class="imgLink" title="Agregar Item" onclick="validarCamp('Agregar')" />
											<img  border="null" width="20px" height="20px" src="imagenes/borrar.bmp" title="Borrar Registro" id="borrar_dot" onclick="validarCamp('eliminar')" /></span><?php } ?>
										</td>
									</tr>
									<?php if($metodo == "agregar"){ ?>
										<tr class="text" >
											<td id="select_1_1"><select name="linea_1" id="linea_1" style="width:150px;"
												onchange="ActivarSubLinea(this.value, '1', 'select_2_1')" disabled="disabled">
												<option value="">Seleccione... </option>
												<?php  	$sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
												$query = $bd->consultar($sql);
												while($datos=$bd->obtener_fila($query,0)){
													?>
													<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
												<?php }?>
											</select></td>
											<td id="select_2_1"><select name="sub_linea_1" id="sub_linea_1" style="width:150px;" onchange="Activar01(this.value, '1', 'select_3_1')" disabled="disabled">
												<option value="">Seleccione... </option>
												<?php  	$sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
												$query = $bd->consultar($sql);
												while($datos=$bd->obtener_fila($query,0)){
													?>
													<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
												<?php }?>
											</select></td>
											<td id="select_3_1"><select name="producto_1" id="producto_1" style="width:150px;" disabled="disabled">
												<option value="">Seleccione... </option>
											</select></td>
											<td id="select_4_1"><select name="almacen_1" id="almacen_1" style="width:150px;" disabled="disabled">
												<option value="">Seleccione...</option>
											</select></td>
											<td id="input04"><input type="number" name="cantidad_1" id="cantidad_1" min="1" value="0" style="width: 65px;"/></td>
											<td>&nbsp;<input type="hidden" name="relacion_1" value="1" /></td>
                                            <td width="8%"><input type="<?php echo $tieneeans ?>" id="boton" name="boton"  value="EANS"/></td>
										</tr>
									<?php }else{
                                        $tieneeans="button";
                                        $metodo="modificar";
										$sql = "SELECT productos.cod_linea, prod_lineas.descripcion AS linea, prod_dotacion_det_clientes.cod_producto, concat(productos.descripcion) AS producto, prod_dotacion_det_clientes.cantidad,productos.cod_sub_linea, prod_sub_lineas.descripcion AS sub_linea FROM prod_dotacion_det_clientes , productos , prod_lineas,prod_sub_lineas WHERE prod_dotacion_det_clientes.cod_dotacion = '$codigo' AND prod_dotacion_det_clientes.cod_producto = productos.item AND productos.cod_linea = prod_lineas.codigo AND productos.cod_sub_linea = prod_sub_lineas.codigo ";
										$query = $bd->consultar($sql);
                                        $fila=1;
										while($datos=$bd->obtener_fila($query,0)){
                                            
											$cod_linea    = $datos["cod_linea"];
											$linea        = $datos["linea"];
											$cod_sub_linea    = $datos["cod_sub_linea"];
											$sub_linea        = $datos["sub_linea"];
											$cod_producto = $datos["cod_producto"];
											$producto     = $datos["producto"];
											$cantidad     = $datos["cantidad"];
											?>
											<tr class="text">
												<td id="select_1_1"><select name="linea_1" id="linea_1" style="width:150px;" disabled="disabled"
													onchange="Activar01(this.value, '1', 'select_2_1')">
													<option value="<?php echo $cod_linea;?>"><?php echo $linea;?></option>
													<?php  	$sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
													$query2 = $bd->consultar($sql);
													while($datos2=$bd->obtener_fila($query2,0)){
														?>
														<option value="<?php echo $datos2[0];?>"><?php echo $datos2[1];?></option>
													<?php }?>
												</select></td><td id="select_3_1"><select name="linea_1" id="linea_1" style="width:100px;" disabled="disabled">
													<option value="<?php echo $cod_sub_linea;?>"><?php echo $sub_linea;?></option>
												</select></td>
												<td id="select_2_1"><select name="producto_1" id="producto_1" style="width:100px;" disabled="disabled">
													<option value="<?php echo $cod_producto;?>"><?php echo $producto;?></option>
												</select></td>
												<td id="input04"><input type="text" name="cantidad_1" id="cantidad_1" min="1" size="10" readonly="readonly"
													value="<?php echo $cantidad;?>" style="width: 65px;"/></td>
													<td><input type="hidden" name="relacion_1" value="1" /></td>
												</tr>
                                                 <td width="5%"><input type="<?php echo $tieneeans; ?>" id="<?php echo $cod_producto ?>" name="<?php echo $cod_producto?>"  value="EANS" onClick="prod_dotacion_modal('<?php echo $fila ?>', '<?php echo $cod_producto ?>', '<?php echo $tieneeans?>','<?php echo $metodo?>','<?php echo $cantidad?>')" /></td>
                                                
											<?php }
                                               $fila=$fila + 1;
                                            }?>
										</table>
										<div id="Contenedor01_1"></div>
										<div align="center">
											<?php if ($metodo == "agregar"){ ?>
                                                
												<span class="art-button-wrapper">
													<span class="art-button-l"> </span>
													<span class="art-button-r"> </span>
													<input type="button" name="validar"  id="validar" value="Guardar" class="readon art-button" onclick="Validar()" />
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
															<input type="button" name="pdf" onClick="Pdf()" value="Imprimir" class="readon art-button" />
														</span>&nbsp;
														<!--<span class="art-button-wrapper">
															<span class="art-button-l"> </span>
															<span class="art-button-r"> </span>
															<input type="button" name="anular" id="anular" onclick="Anular()" value="Anular" class="readon art-button" />
														</span>&nbsp;-->

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
													
            // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
        return "autocompletar/tb/ubicaciones.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
    </script>
