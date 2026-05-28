<script type="text/javascript">
	var ficha = "";
	function Validar(){
		$("#validar").prop('disabled', true);
		var ubicacion = $("#ubicacion").val();
		if(ubicacion == ""){
			toastr.error("Debe seleccionar la Ubicación");
			$("#validar").prop('disabled', false);
			return;
		}
		var numX = parseInt(document.getElementById('incremento').value);
		if(numX <= 1){
			toastr.error("Debe agregar al menos un producto");
			$("#validar").prop('disabled', false);
			return;
		}
		
		// Anti-duplicate protection: Disable the button to prevent multiple submissions
		$("#validar").val("Guardando...");
		
		$("#salvar").click();
	}

function ActivarSubLinea(codigo, relacion, contenido){  
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
	}
}

function Activar01(codigo, relacion, contenido){  
	if(codigo!=''){
		var valor = "ajax/Add_prod_sub_linea_asignacion.php";
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
	}
}

function Activar_almacen(codigo, relacion, contenido){ 
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
	}
}

function ActivarUbicacion(codigo){
	if(codigo!=''){
		var valor = "ajax/Add_ubicacion_asignacion.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById('ubicacion').innerHTML = ajax.responseText;
				ToggleFicha();
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo);
	}else{
		document.getElementById('ubicacion').innerHTML = '<option value="">Seleccione...</option>';
		ToggleFicha();
	}
}

function ToggleFicha(){
	document.getElementById("stdName").value = "";
	document.getElementById("stdID").value = "";
	var ubic = document.getElementById("ubicacion").value;
	if(ubic != ""){
		document.getElementById("stdName").disabled = false;
	}else{
		document.getElementById("stdName").disabled = true;
	}
}

function actualizarResumen() {
    var tipo = document.getElementById('tipo').value;
    var ubicacion = document.getElementById('ubicacion').value;
    var trabajador = document.getElementById('stdID') ? document.getElementById('stdID').value : "";

    if(ubicacion != "" || trabajador != "") {
        var valor = "ajax/Add_prod_asignacion_resumen.php";
        ajax = nuevoAjax();
        ajax.open("POST", valor, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4) {
                var contenedor = document.getElementById('resumen_custodia');
                if(contenedor) {
                    contenedor.innerHTML = ajax.responseText;
                }
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("tipo=" + tipo + "&ubicacion=" + ubicacion + "&trabajador=" + trabajador);
    } else {
        var contenedor = document.getElementById('resumen_custodia');
        if(contenedor) {
            contenedor.innerHTML = "";
        }
    }
}

function cantidad_maxima(cod_almacen, relacion) {
    var producto = document.getElementById('stdIDProd').value;
    if (cod_almacen != '') {
        var valor = "ajax/Add_prod_asignacion_max.php";
        ajax = nuevoAjax();
        ajax.open("POST", valor, true);
        ajax.onreadystatechange = function () {
            if (ajax.readyState == 4) {
                var resp = JSON.parse(ajax.responseText);
                var max_disp = parseInt(resp.stock_actual);
                document.getElementById('ped_cantidad').value = ""; // Reset quantity
                document.getElementById('ped_cantidad').max = max_disp;
                if(max_disp == 0){
                    toastr.warning("No hay stock físico disponible para asignar en este almacén.");
                    document.getElementById('ped_cantidad').disabled = true;
                } else {
                    toastr.info("Stock físico disponible: " + max_disp);
                    document.getElementById('ped_cantidad').disabled = false;
                    document.getElementById('ped_cantidad').setAttribute("data-max-stock", max_disp);
                }
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send("producto=" + producto + "&almacen=" + cod_almacen);
    }
}


function BorrarFila(numX){
	$("#tr_asig_"+numX).remove();
    $("#hidden_asig_"+numX).remove();
    // Re-evaluar si quedan filas, si no, no permitir guardar?
    // De momento solo borrar del DOM, sc_prod_asignacion lo maneja
}

var global_num = 1;

function validarCamp(){
	var valido     = 1;
	var mensaje   = " ";

	select03  = document.getElementById('stdIDProd').value;
	select04  = document.getElementById('almacen_0') ? document.getElementById('almacen_0').value : "";
	input01   = Number(document.getElementById('ped_cantidad').value);

	if(select03 == ""){ valido++; mensaje += " Debe Seleccionar Un Producto \n"; }
	if(select04 == ""){ valido++; mensaje += " Debe Seleccionar Un Almacen \n"; }
	if(input01 == "" || input01 == 0){ valido++; mensaje += " Debe Ingresar la Cantidad \n "; }
	
	max_stock = Number(document.getElementById('ped_cantidad').getAttribute("data-max-stock") || 0);
	if(document.getElementById('tipo').value == "ASIGNACION" && input01 > max_stock) { 
		valido++; 
		mensaje += " La cantidad excede el stock físico disponible ("+max_stock+"). \n"; 
	}

	if(valido ==  1){
		getIfEAN(select03, input01, select04, global_num, function(){
			agregar_renglon(global_num);
		});
	}else{
		toastr.error(mensaje);
	}
}

function Selec_producto(id){
	var valor = "ajax/Add_prod_almacen.php";
	ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function()
	{
		if (ajax.readyState==4){
			document.getElementById('td_almacen').innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("codigo="+id+"&relacion=0");
}

function inyectar_renglon(prod_id, prod_name, almacen_id, almacen_name, cant, eans){
    var numX = global_num;
    var tr = '<tr id="tr_asig_'+numX+'">' +
        '<td>'+numX+'</td>' +
        '<td>'+prod_name+'</td>' +
        '<td>'+almacen_name+'</td>' +
        '<td>'+cant+'</td>' +
        '<td>'+eans+'</td>' +
        '<td align="center" class="borrar_td"></td>' +
        '</tr>';
    $("#listar_asignacion").append(tr);
    global_num++;
}

function agregar_renglon(numX){
    var prod_id = $("#stdIDProd").val();
    var prod_name = $("#ped_producto").val();
    var almacen_id = $("#almacen_0").val();
    var almacen_name = $("#almacen_0 option:selected").text();
    var cant = $("#ped_cantidad").val();
    var eans = temporal_eans;
    temporal_eans = "";

    // Insertar en la tabla visible
    var tr = '<tr id="tr_asig_'+numX+'">' +
        '<td>'+numX+'</td>' +
        '<td>'+prod_name+'</td>' +
        '<td>'+almacen_name+'</td>' +
        '<td>'+cant+'</td>' +
        '<td>'+eans+'</td>' +
        '<td align="center"><img border="null" width="20px" height="20px" src="imagenes/borrar.bmp" title="Borrar" onclick="BorrarFila('+numX+')" class="imgLink" /></td>' +
        '</tr>';
    $("#listar_asignacion").append(tr);

    // Insertar los inputs ocultos para que el POST los mande
    var hiddens = '<div id="hidden_asig_'+numX+'">' +
        '<input type="hidden" name="producto_'+numX+'" value="'+prod_id+'" />' +
        '<input type="hidden" name="almacen_'+numX+'" value="'+almacen_id+'" />' +
        '<input type="hidden" name="cantidad_'+numX+'" value="'+cant+'" />' +
        '<input type="hidden" name="eans_'+numX+'" id="eans_'+numX+'" value="'+eans+'" />' +
        '<input type="hidden" name="relacion_'+numX+'" value="'+numX+'" />' +
        '</div>';
    $("#HiddenInputs").append(hiddens);

    // Limpiar formulario superior
    $("#stdIDProd").val("");
    $("#ped_producto").val("");
    $("#td_almacen").html('<select style="width:150px"><option value="">Seleccione...</option></select>');
    $("#ped_cantidad").val("0");

    // Aumentar el incremento
    global_num++;
    document.getElementById('incremento').value = global_num;
}

var eans_seleccionados = [];
var ean_cantidad_requerida = 0;
var ean_renglon_actual = 0;

function eanModalOpen(){
    $("#eanModal").show();
}

function eanCloseModal(){
    $("#eanModal").hide();
}

function filtrarEANS(elem){
    var ValorBusqueda = new RegExp($(elem).val(), 'i');
    $('#listar_eans tr').hide();
    $('#listar_eans tr').filter(function (i) {
        return ValorBusqueda.test($(this).find("input[type=text]").val());
    }).show();
}

function selectEAN(ean, estado, eventObj){
    if(estado){
        if(eans_seleccionados.length >= ean_cantidad_requerida){
            toastr.error("Ya seleccionó la cantidad requerida de EANs (" + ean_cantidad_requerida + ")");
            eventObj.checked = false;
            return;
        }
        var index = eans_seleccionados.indexOf(ean);
        if (index === -1) {
            eans_seleccionados.push(ean);
        }
    }else{
      var index = eans_seleccionados.indexOf(ean);
      if(index > -1) eans_seleccionados.splice(index, 1);
    }
}

function getIfEAN(item, cantidad, almacen, numX, callback){
    $.ajax({
        data: {"codigo": item},
        url: 'ajax/Add_prod_asignacion_getIfEAN.php',
        type: 'post',
        success: function(response) {
            var resp = JSON.parse(response);
            if(resp[0] == 'T'){
                ean_cantidad_requerida = cantidad;
                ean_renglon_actual = numX;
                cargarEANS(item, almacen);
                $("#cant_ing").html(cantidad);
            }else{
                callback();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function cargarEANS(item, almacen){
    var tipo = $("#tipo").val();
    var ubicacion = $("#ubicacion").val();
    var ficha = document.getElementById("stdID").value;
    
    $.ajax({
        data: { 'codigo': item, 'almacen':almacen, 'tipo': tipo, 'ubicacion': ubicacion, 'ficha': ficha },
        url: 'ajax/Add_prod_asignacion_getEANS.php',
        type: 'post',
        success: function(response) {
            eans_seleccionados = [];
            $('#listar_eans').html('');
            var resp = JSON.parse(response);
            if(resp.length > 0){
                var reng_num_ean = 0;
                jQuery.each(resp, function(i) {
                    reng_num_ean++;
                    var tr = ('<tr id="tr_ean_' + reng_num_ean + '"></tr>');
                    var td01 = ('<td><input type="text" id="reng_num_ean_' + reng_num_ean + '" value="' + resp[i].cod_ean + '" style="width:300px" readonly></td>');
                    var td02 = ('<td><input name="activo" type="checkbox" value="T" onclick="selectEAN(\''+resp[i].cod_ean+'\',this.checked, this)"/> </td>');

                    $('#listar_eans').append(tr);
                    $('#tr_ean_' + reng_num_ean + '').append(td01);
                    $('#tr_ean_' + reng_num_ean + '').append(td02);
                });
                eanModalOpen();
            }else{
                toastr.warning('No hay EANs disponibles para esta operación.');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

var temporal_eans = "";

function guardarEans(){
    if(ean_cantidad_requerida == eans_seleccionados.length){
        temporal_eans = eans_seleccionados.join(",");
        eanCloseModal();
        agregar_renglon(ean_renglon_actual);
    }else{
        alert("Debe seleccionar la cantidad correspondiente de EANS ("+ean_cantidad_requerida+"), seleccionados: " + eans_seleccionados.length);
    }
}

// prod_asignacion_det was removed as we are not fetching the DOM from PHP anymore

function Anular(){  
	if (confirm("¿Esta Seguro De Anular Este Registro")) {
		document.getElementById("metodo").value = "anular";
		document.add.submit();
	}
}
</script>
<?php
$archivo       = "prod_asignacion";
$archivo2      = "Cons_prod_asignacion";
$metodo        = isset($_GET['metodo']) ? $_GET['metodo'] : 'agregar';
$codigo        = "";
$date          = date('Y-m-d');
$fecha         = conversion($date);
$usuario       = $_SESSION['usuario_cod'];
$ficha         = "";
$trabajador    = "";
$descripcion   = "";
$tipo          = "ASIGNACION";
$ubicacion     = "";
$proced        = "p_prod_asignacion";
$cod_cliente   = "";

if ($metodo == 'modificar') {
	$codigo = $_GET['codigo'];
	$sql = " SELECT prod_asignacion.fecha, prod_asignacion.tipo,
                 prod_asignacion.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion,
                 clientes_ubicacion.cod_cliente,
                 prod_asignacion.cod_ficha, v_ficha.ap_nombre AS trabajador,
				 prod_asignacion.descripcion
            FROM prod_asignacion 
            LEFT JOIN clientes_ubicacion ON clientes_ubicacion.codigo = prod_asignacion.cod_ubicacion
            LEFT JOIN v_ficha ON v_ficha.cod_ficha = prod_asignacion.cod_ficha
            WHERE prod_asignacion.codigo = '$codigo'";
	$query = $bd->consultar($sql);
	$datos = $bd->obtener_fila($query,0);
	$fecha       = $datos['fecha'];
	$tipo        = $datos['tipo'];
	$ubicacion   = $datos['cod_ubicacion'];
	$cod_cliente = $datos['cod_cliente'];
	$ficha       = $datos['cod_ficha'];
	$trabajador  = $datos['trabajador'];
	$descripcion = $datos['descripcion'];
}
?>

<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>

<div id="eanModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="eanCloseModal()" >&times;</span>
			<span id="modal_titulo">Listado de Eans <span id="span_cant_ing">(<span id="cant_ing"></span>)</span></span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido">
				<br>
				<div align="center" class="etiqueta_title">Seleccionar EANs</div>
				<br>
				<th>Buscador <input type="text" class="text" onkeyup="filtrarEANS(this)" /><br>
				<hr />
				<table id="listar_eans_tb" width="80%" align="center">
						<thead>
						    <tr class="fondo00">
						      <th>Codigo EAN</th>
						      <th>Seleccionar</th>
						    </tr>
						 </thead>
						 <tbody id="listar_eans">
						 </tbody>
				</table>
				<br>
				<div align="center">
					<span class="art-button-wrapper" id="boton_guardar_eans">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" id="boton_eans" class="readon art-button" value="Procesar" onclick="guardarEans()"/>
					</span>
					<span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" class="readon art-button" value="Cerrar" onclick="eanCloseModal()" />
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="scripts/sc_<?php echo $archivo?>.php" method="post" name="add" id="add">
	<br>
	<div align="center" class="etiqueta_title"> Asignación / Devolución de Productos </div>
	<hr />
	<div id="Contenedor01"></div>
	<fieldset class="fieldset">
		<legend>Encabezado: </legend>
		<table width="100%" align="left">
			<tr>
				<td class="etiqueta" width="13%">Operación:</td>
				<td width="20%">
                    <select name="tipo" id="tipo" style="width: 150px;" onchange="actualizarResumen()" <?php if($metodo=="modificar") echo 'disabled="disabled"';?>>
                        <option value="ASIGNACION" <?php if($tipo=="ASIGNACION") echo "selected";?>>ASIGNACION</option>
                        <option value="DEVOLUCION" <?php if($tipo=="DEVOLUCION") echo "selected";?>>DEVOLUCION</option>
                    </select>
                </td>
				<td class="etiqueta" width="13%">Fecha:</td>
				<td id="fecha01" width="20%"><input type="text" name="fecha"  size="15" value="<?php echo $fecha;?>" <?php if($metodo=="modificar") echo 'readonly';?>/><br>
					<span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
					<span class="textfieldInvalidFormatMsg">Formato Invalido.</span></td>
				<td class="etiqueta" width="13%">Descripci&oacute;n:</td>
				<td id="input02" width="20%"><textarea name="descripcion" cols="40" rows="3" maxlength="255" <?php if($metodo=="modificar") echo 'readonly';?>><?php echo htmlspecialchars($descripcion);?></textarea><br>
					<span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
					<span class="textfieldMinCharsMsg">Debe Escribir mínimo 2 Caracteres.</span></td>
			</tr>
			<tr>
				<td class="etiqueta">Cliente:</td>
				<td colspan="2">
                    <select name="cliente" id="cliente" style="width:250px;" onchange="ActivarUbicacion(this.value)" <?php if($metodo=="modificar") echo 'disabled="disabled"';?>>
                        <option value="">Seleccione...</option>
                        <?php
                            $sql = "SELECT codigo, nombre FROM clientes WHERE `status` = 'T' ORDER BY nombre ASC";
                            $query = $bd->consultar($sql);
                            while($datos=$bd->obtener_fila($query,0)){
                                $selected = ($datos[0] == $cod_cliente) ? "selected" : "";
                                echo '<option value="'.$datos[0].'" '.$selected.'>'.$datos[1].'</option>';
                            }
                        ?>
                    </select>
                </td>
				<td class="etiqueta">Ubicación:</td>
				<td colspan="2">
                    <select name="ubicacion" id="ubicacion" style="width:250px;" onchange="ToggleFicha(); actualizarResumen();" <?php if($metodo=="modificar") echo 'disabled="disabled"';?>>
                        <option value="">Seleccione...</option>
                        <?php 
                        if ($metodo == 'modificar') {
                            $sql = "SELECT codigo, descripcion FROM clientes_ubicacion WHERE cod_cliente = '$cod_cliente'";
                            $query = $bd->consultar($sql);
                            while($datos=$bd->obtener_fila($query,0)){
                                $selected = ($datos[0] == $ubicacion) ? "selected" : "";
                                echo '<option value="'.$datos[0].'" '.$selected.'>'.$datos[1].'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
			</tr>
			<tr>
				<td class="etiqueta">Ficha (Opcional):</td>
				<td colspan="2">
					<input id="stdName" type="text" size="36" value="<?php echo $trabajador;?>" disabled="disabled" placeholder="Seleccione Ubicación primero"/>
					<input type="hidden" name="trabajador" id="stdID" value="<?php echo $ficha;?>"/>
				</td>
				<td colspan="3"></td>
			</tr>
		</table>
	</fieldset>
	<div id="resumen_custodia"></div>
	<fieldset class="fieldset" id="detalle">
		<legend>Detalle de Productos: </legend>
		<table width="95%" align="center">
		<?php if($metodo == 'agregar') { ?>
			<tr>
				<td width="35%" class="etiqueta">Producto
					<input type="hidden" id="stdIDProd" value=""/>
				</td>
				<td width="20%" class="etiqueta">Almacen</td>
				<td width="15%" class="etiqueta">Cantidad</td>
				<td width="10%" class="etiqueta" id="add_renglon_etiqueta">Agregar</td>
			</tr>
			<tr>
				<td>
					<input type="text" id="ped_producto" value="" placeholder="Ingrese Dato del Producto" style="width:300px"/>
				</td>
				<td id="td_almacen">
					<select id="ped_almacen" style="width:150px">
						<option value="">Seleccione...</option>
					</select>
				</td>
				<td>
					<input type="number" id="ped_cantidad" style="width:100px" value="0" min="0" placeholder="">
				</td>
				<td align="center">
					<img border="null" width="20px" height="20px" src="imagenes/ico_agregar.ico" id="add_renglon" onclick="validarCamp()" title="Agregar renglon" class="imgLink" />
				</td>
			</tr>
		<?php } ?>
		</table>

		<br>
		<table width="95%" class="tabla_sistema" align="center">
			<thead>
				<tr>
					<th width="5%">N.</th>
					<th width="35%">Producto</th>
					<th width="20%">Almacen</th>
					<th width="15%">Cantidad</th>
					<th width="20%">EANs</th>
					<th width="5%">Borrar</th>
				</tr>
			</thead>
			<tbody id="listar_asignacion">
			</tbody>
		</table>
		<br>
		<div id="HiddenInputs"></div>
		<div align="center">
            <?php if ($metodo == "agregar"){ ?>
                <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" style="display:none;" />
                </span>
                <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button" id="validar" value="Guardar" class="readon art-button" onClick="Validar()"/>
                </span>
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
		</div>
	</fieldset>
</form>
<hr />
<script type="text/javascript">
$(document).ready(function() {
    <?php
    if ($metodo == 'modificar') {
        $sql_det = "SELECT d.cod_producto, p.descripcion AS prod_desc, d.cantidad, d.cod_almacen, a.descripcion AS alm_desc,
                           GROUP_CONCAT(e.cod_ean SEPARATOR ',') AS eans, p.ean
                    FROM prod_asignacion_det d
                    LEFT JOIN productos p ON p.item = d.cod_producto
                    LEFT JOIN almacenes a ON a.codigo = d.cod_almacen
                    LEFT JOIN prod_asignacion_eans e ON e.cod_asignacion = d.cod_asignacion AND e.cod_producto = d.cod_producto
                    WHERE d.cod_asignacion = '$codigo'
                    GROUP BY d.cod_producto, d.cod_almacen";
        $q_det = $bd->consultar($sql_det);
        while($row = $bd->obtener_fila($q_det,0)){
            $prod = $row['cod_producto'];
            $pdesc = addslashes($row['prod_desc']);
            $cant = $row['cantidad'];
            $alm = $row['cod_almacen'];
            $adesc = addslashes($row['alm_desc']);
            $eans = $row['eans'] ? addslashes($row['eans']) : '';
            $maneja_ean = $row['ean'] == 'T' ? 1 : 0;
            echo "inyectar_renglon('$prod', '$pdesc', '$alm', '$adesc', $cant, '$eans');\n";
        }
    }
    ?>
});

	var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
		validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});

	r_cliente = $("#r_cliente").val();
	r_rol     = $("#r_rol").val();
	usuario   = $("#usuario").val();

	new Autocomplete("stdName", function() {
		this.setValue = function(id) {
			document.getElementById("stdID").value = id; 
			actualizarResumen();
		}
		if (this.isModified) {
			this.setValue("");
			actualizarResumen();
		}
		if (this.value.length < 1) return ;
		var ubicacion_val = document.getElementById('ubicacion').value;
		return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro=TODOS&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+"&activos=true&ubicacion="+ubicacion_val;
    });

	new Autocomplete("ped_producto", function() { 
		this.setValue = function(id) {
			$("#stdIDProd").val(id);
			Selec_producto(id);
		}
		if (this.value.length < 1) return ;
		return "autocompletar/tb/producto_base_serial.php?q="+this.text.value +"&filtro=codigo"
    });
</script>
