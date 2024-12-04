
$(function () {
	Cons_movimiento();
});

var lote        = "19830906";
var cantidad    = 0;
var costo 		= 0;
var neto        = 0;

var almacen_cod = "";
var producto_cod = "";
var producto_des = "";
var alm_origen = ""	
var alm_destino = "";
var Ped_detalle = [];
var reng_num    = 0;
var index       = 0;
var stock_actual = 0;
var sub_total  = 0;
var total      = 0;
var detalle = false;
var eans = [];

function reset(){
	lote        = "19830906";
	cantidad    = 0;
	costo 		= 0;
	neto        = 0;
	almacen_cod = "";
	producto_cod = "";
	producto_des = "";
	alm_origen = ""	
	alm_destino = "";
	Ped_detalle = [];
	reng_num    = 0;
	index       = 0;
	stock_actual = 0;
	sub_total  = 0;
	total      = 0;
	detalle = false;
	eans = [];
}

function Cons_movimiento() {
	reset();
	var parametros = { }
	$.ajax({
		data:  parametros,
		url:   'packages/inventario/movimiento/views/Cons_inicio.php',
		type:  'post',
		success:  function (response) {
			$("#Cont_movimiento").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

function save_movimiento() {
	var error = 0;
	var errorMessage = ' ';
	var proced = "p_mov_inventario";

	var codigo = $("#ped_codigo").val();
	var fecha = $("#ped_fecha").val();
	var descripcion = $("#ped_descripcion").val();
	var total = $("#ped_total").val();
	var ped_reng = JSON.stringify(Ped_detalle);
	var us = $("#usuario").val();

	if(reng_num == 0){
		error = 1;
		errorMessage = "Debe de ingresar un articulo";
	}

	if (error == 0) {
		console.log(ped_reng);
		var parametros = {nro_movimiento: codigo, 
			fecha: fecha,             descripcion: descripcion,
			total: total, alm_origen: alm_origen,alm_destino:alm_destino,
			ped_reng : ped_reng,
			proced: proced,           us: us };
			$.ajax({
				data: parametros,
				url: 'packages/inventario/movimiento/modelo/movimiento.php',
				type: 'post',
				success: function (response) {
					var resp = JSON.parse(response);
					console.log(resp);
					if (resp.error) {
						alert(resp.mensaje);
					}else {
						alert("Actualización Exitosa!..")
						Cons_movimiento();
					}
				}
				,
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		} else {
			alert(errorMessage);
		}
	}

	function Borrar_movimiento() {
		if(confirm('Esta seguro que desea BORRAR este Registro?..')){
			var proced  = "p_movimiento";
			var codigo  = $("#ped_codigo").val();
			var parametros = { codigo: codigo,

				proced: proced, us: us,
				metodo: "borrar"
			};
			$.ajax({
				data: parametros,
				url: 'packages/inventario/movimiento/modelo/movimiento.php',
				type: 'post',
				success: function (response) {
					var resp = JSON.parse(response);
					if (resp.error) {
						alert(resp.mensaje);
					} else {
						alert('Registro Eliminado con exito!..');
						Cons_movimiento();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
				}
			});
		}
	}

	//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
	function Agregar_movimiento(){
		var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
		if(confirm(msg)) Form_movimiento('', 'agregar');
	}


	function buscar_producto() {
		var dato = $('#ped_filtro_producto').val() || '';
		var parametros =  {'dato':dato,'almacen':alm_origen};
		$.ajax({
			data: parametros,
			url: 'packages/inventario/movimiento/views/Buscar_prod.php',
			type: 'post',
			beforeSend: function(){
				$('#buscarProducto').attr('disabled',true);
			},
			success: function (response) {
				$("#ped_producto").html(response);
				$('#buscarProducto').attr('disabled',false);
				Limpiar_producto();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
				$('#buscarProducto').attr('disabled',false);
				Limpiar_producto();
			}
		});
	}

	function Selec_producto(codigo) {
		Limpiar_producto();
		if((codigo != "" ) && (codigo != "undefined")) {
			producto_cod = codigo;
			producto_des = $("#ped_producto").val();
			cantidad_maxima(alm_origen,codigo);
			costo_promedio(codigo,alm_origen);
		}else{
			alert("debe seleccionar un porducto");
		}
	}

	function cantidad_maxima(cod_almacen,cod_producto) {
		$.ajax({
			data: { 'producto': cod_producto, 'almacen':cod_almacen },
			url: 'packages/inventario/ajuste/views/Get_stock.php',
			type: 'post',
			success: function(response) {
				var resp = JSON.parse(response);
				if(resp){
					console.log(resp[0]);
					stock_actual = resp[0];
					$("#ped_cantidad").attr('disabled',false);
					$("#ped_cantidad").attr('max',resp[0]);
				}else{
					toastr.warning('Este producto no tiene disponibilidad!.');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function costo_promedio(codigo,cod_almacen) {
		console.log(codigo,cod_almacen);
		$.ajax({
			data: { 'codigo': codigo, 'almacen': cod_almacen },
			url: 'packages/inventario/producto/views/Get_costo_prom.php',
			type: 'post',
			success: function(response) {
				console.log(response);
	   // console.log(response);
	   var resp = JSON.parse(response);
	   costo = resp[0];
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(xhr.status);
		alert(thrownError);
	}
});
	}

	function Limpiar_producto() {
		cantidad = 0;
		$('#ped_cantidad').val(0);
		$("#add_renglon").prop('disabled',true);
		$("#ped_cantidad").prop('disabled',true);
	}

	function get_precio_prod(codigo){
		$.ajax({
			data: {'producto':codigo},
			url: 'packages/inventario/producto/views/Get_precio.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
		// return resp[0];
		precio = resp[0];
		$('#ped_precio').val(precio);
	},
	error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.status);
		alert(thrownError);
	}
});

	}

	function habilitar_destino(codigo){
		reset();
		if(codigo != ""){
			alm_origen = codigo;
			$("#alm_destino").attr('disabled',false);		
			$("#alm_destino").val("");
		}else{
			$("#alm_destino").attr('disabled',true);
		}
		$("#detalle").html("");
		detalle = false;
	}

	function cargar_detalle(codigo){
		var error = 0;
		var errorMessage = ' ';
		var proced = "p_movimiento";
		alm_destino = codigo;
		console.log('ALMACENES: ',alm_origen,alm_destino);
		if(alm_origen == alm_destino){
			error=1;
			errorMessage="Los almacenes(origen,destino) deben ser diferentes..";
		}
		if(error==0){
			if (detalle == false) {
				detalle = true;
				$.ajax({
					data: {'producto':codigo},
					url: 'packages/inventario/movimiento/views/Add_form.php',
					type: 'post',
					success: function (response) {
						$('#detalle').html(response);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
				});
			}
		}else{
			alert(errorMessage);
		}
	}


	function Agregar_renglon(){
		var error = 0;
		var errorMessage = ' ';
		cantidad = Number($("#ped_cantidad").val());

		if(cantidad > stock_actual){
			error = 1;
			errorMessage += "\n La cantidad maxima permitida, segun el stock actual es "+stock_actual;  
		}

		var cant= cantidad;
	//console.log(Ped_detalle);
	Ped_detalle.some((ajuste)=>{
		console.log(ajuste)
		if(ajuste.cod_producto==producto_cod){
			cant = Number(cant) + Number(ajuste.cantidad);
	       //console.log(cant,stock_actual);
	       if(cant>stock_actual){
	       	error = 1;
	       	errorMessage += "\n El detalle del ajuste sobrepasa la cantidad maxima permitida,para este producto en este almacen!:.";  
	       	return true;
	       }
	   }
	});

	Ped_detalle.some((ajuste)=>{
		if(ajuste.cod_producto==producto_cod){
			error = 1;
			errorMessage += "\n El registro ya existe en el detalle del movimiento!:.";  
			return true;
		}
	});

	if (alm_origen == "") {
		error = 1;
		errorMessage += "\n Debe Seleccionar un Almacen..";
	}
	if (cantidad < 1) {
		error = 1;
		errorMessage += "\n Cantidad invalida..";
	}


	if (error == 0) {
		Cal_total();
		getIfEAN(producto_cod,cantidad,false,()=>{
			reng_num++;
			$("#ped_producto").val("");
			var Ped_detalleX  = {reng_num:reng_num, cod_producto: producto_cod,
				producto: producto_des, lote: lote, cantidad: cantidad  ,          
				costo: costo, neto: neto, 
				eans: []};
				Ped_detalle.push(Ped_detalleX);

				var tr = ('<tr id="tr_' + reng_num + '"></tr>');
				var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
				var td02 = ('<td><input type="text" id="prod_' + reng_num + '" value="' + producto_des + '" readonly style="width:400px"></td>');
				var td03 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
				var td04 = ('<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

				$('#listar_movimiento').append(tr);
				$('#tr_' + reng_num + '').append(td01);
				$('#tr_' + reng_num + '').append(td02);
				$('#tr_' + reng_num + '').append(td03);
				$('#tr_' + reng_num + '').append(td04);

				Cal_total();
			});
	} else {
		alert(errorMessage);
	}
}

function getIfEAN(item,cantidad,actualizar,callback){
	    //console.log('getIfEAN');
	    //console.log(Ped_detalle,item);
	    var metodo = $("#ped_metodo").val();
	    $.ajax({
	    	data: {"codigo": item},
	    	url: 'packages/inventario/ajuste/views/Get_if_EAN.php',
	    	type: 'post',
	    	success: function(response) {
	    		var resp = JSON.parse(response);
	            //console.log('METODO',metodo);
	            if(resp[0] == 'T'){
	            	if(metodo == 'modificar'){
	            		callback();
	            	}else{
	            		if(actualizar){
	            			$("#boton_eans").attr("onclick","actualizarEans()");
	            		}else{
	            			$("#boton_eans").attr("onclick","guardarEans()");
	            		}
	                   //console.log($("#ped_aplicar").val());
	                   if($("#ped_aplicar").val() == "IN"){
	                   	cargarEANS(item,false);
	                   }else{
	                   	cargarEANS(item,true,$("#ped_almacen").val());
	                   }

	                   $("#cant_ing").html(cantidad);
	               }
	           }else{
	            //if(metodo != 'modificar'){
	            	callback();
	            //}
	        }
	    },
	    error: function(xhr, ajaxOptions, thrownError) {
	    	alert(xhr.status);
	    	alert(thrownError);
	    }
	});
	}

	function Modificar_renglon(codigo){
		index = codigo-1;
		$("#ped_producto").prop('disabled', true);
		$("#ped_filtro_producto").prop('disabled', true);
		$("#buscarProducto").prop('disabled', true);

		$("#ped_cantidad").prop('disabled', false);

		$("#add_renglon").prop('hidden', true);
		$("#canc_renglon").prop('hidden', false);
		$("#update_renglon").prop('hidden', false);

		$("#ped_cantidad").val(Ped_detalle[index]["cantidad"]);

		cantidad = Ped_detalle[index]["cantidad"];
		precio   = Ped_detalle[index]["precio"];

		var prod_option  = '<option value="'+Ped_detalle[index]["cod_producto"]+'">'+Ped_detalle[index]["producto"]+'</option>';
		$("#ped_producto").html(prod_option);
		Cal_total();
	}

	function Cancelar_renglon(){
		$("#ped_producto").prop('disabled', false);
		$("#ped_filtro_producto").prop('disabled', false);
		$("#buscarProducto").prop('disabled', false);
		$("#add_renglon").prop('hidden', false);
		$("#canc_renglon").prop('hidden', true);
		$("#update_renglon").prop('hidden', true);
		buscar_producto();
	//var prod_option  = '<option value="">Seleccione...</option>';
	//$("#ped_producto").html(prod_option);
}

function Actualizar_renglon(){
	error = 0;
	errorMessage = "";
	cantidad = $("#ped_cantidad").val();
	if(cantidad > stock_actual){
		error = 1;
		errorMessage += "\n La cantidad maxima permitida, segun el stock actual es "+stock_actual;  
	}
	if(error == 0){
		getIfEAN(Ped_detalle[index]["cod_producto"],cantidad,true,()=>{ 
			var idX = index+1;
			Ped_detalle[index]["cantidad"] = cantidad;

			$("#cant_"+idX+"").val(cantidad);

			Cancelar_renglon();
			Cal_total();
		});
	}else {
		alert(errorMessage);
	}

}

function Borrar_renglon(intemsV){

	var datos   = Ped_detalle;
	$("#listar_movimiento").html("");
	Ped_detalle = [];
	reng_num  = 0;

	jQuery.each( datos, function(i) {
		if (datos[i]["reng_num"] != intemsV){
			reng_num++;
			datos[i]["reng_num"] = reng_num;
			Ped_detalle.push(datos[i]);

			var tr    =('<tr id="tr_'+reng_num+'" class="cursor"></tr>');
			var td01  =('<td><input type="text" id="reng_num_'+reng_num+'" value="'+reng_num+'" class="form-control" readonly></td>');
			var td02  =('<td><input type="text" id="prod_'+reng_num+'" value="'+datos[i]["producto"]+'" class="form-control" readonly></td>');
			var td03  =('<td><input type="text" id="cant_'+reng_num+'" value="'+datos[i]["cantidad"]+'" class="form-control" readonly></td>');
			var td04  =('<td><input type="text" id="prec_'+reng_num+'" value="'+datos[i]["precio"]+'" class="form-control" readonly></td>');
			var td05  =('<td><input type="text" id="neto_'+reng_num+'" value="'+datos[i]["neto"]+'" class="form-control" readonly></td>');
			var td06  =('<td><button type="button" onclick="Modificar_renglon('+reng_num+')" title="Modificar Registro" class="btn btn-upd btn-sm ml-1"><i class="fas fa-edit"></i></button><button type="button" onclick="Borrar_renglon('+reng_num+')" title="Borrar Registro" class="btn btn-upd btn-sm ml-1"><i class="fas fa-trash-alt"></i></button> </td>');

			$('#listar_movimiento').append(tr);
			$('#tr_'+reng_num+'').append(td01);
			$('#tr_'+reng_num+'').append(td02);
			$('#tr_'+reng_num+'').append(td03);
			$('#tr_'+reng_num+'').append(td04);
			$('#tr_'+reng_num+'').append(td05);
			$('#tr_'+reng_num+'').append(td06);
		}
	});
	Cal_total();
}


function Cal_total(){


	//	alert(tasa +", "+incluye_iva);
	sub_total = 0;
	jQuery.each(Ped_detalle, function(i) {
		sub_total = (parseFloat(sub_total) + parseFloat(Ped_detalle[i]["neto"]));
	});

	total = sub_total;
	$("#ped_total").val(parseFloat(total.toFixed(2)));

}

function Reng_ped(codigo){
	$.ajax({
		data: {'codigo':codigo},
		url: 'packages/producto/movimiento/views/Add_renglon.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			Ped_detalle = resp;
		//console.log(Ped_detalle);
		reng_num  = 0;
		jQuery.each(Ped_detalle, function(i) {
			reng_num = Ped_detalle[i]["reng_num"];
			//reng_num++;
		});
	},
	error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.status);
		alert(thrownError);
	}
});

}

function Reng_ped_ean(ajuste,renglon,index_detalle) {
	//console.log('Reng_ped_ean');
	$.ajax({
		data: { 'ajuste': ajuste,'renglon': renglon },
		url: 'packages/inventario/ajuste/views/Add_renglon_eans.php',
		type: 'post',
		success: function(response) {
	    //console.log(response);
	    var resp = JSON.parse(response);
	    Ped_detalle[index_detalle]["eans"] = resp;
	    //console.log(Ped_detalle);
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(xhr.status);
		alert(thrownError);
	}
});

}

function cargarEANS(item,salida,almacen){
	//console.log('cargarEANS');
	var reng_num_ean = 0;
	var salida = salida ? salida : null;
	var almacen = almacen ? almacen : null;
	console.log('SALIDA: ', salida,'  ALMACEN:',almacen,'item',item);
	$.ajax({
		data: { 'codigo': item, 'salida':salida,'almacen':almacen },
		url: 'packages/inventario/ajuste/views/Add_EANS.php',
		type: 'post',
		success: function(response) {
	    //console.log(response);
	    eans = [];
	    $('#listar_eans').html('');
	    var resp = JSON.parse(response);
	    if(resp.length > 0){
	    	jQuery.each(resp, function(i) {
	    		reng_num_ean++;
	    		var tr = ('<tr id="tr_ean_' + reng_num_ean + '"></tr>');
	    		var td01 = ('<td><input type="text" id="reng_num_ean_' + reng_num_ean + '" value="' + resp[i].cod_ean + '" style="width:300px"></td>');
	    		var td02 = ('<td><input name="activo" id="p_activo" type="checkbox" value="T" onclick="selectEAN('+reng_num_ean+',this.checked)"/> </td>');

	    		$('#listar_eans').append(tr);
	    		$('#tr_ean_' + reng_num_ean + '').append(td01);
	    		$('#tr_ean_' + reng_num_ean + '').append(td02);
	    		$("#prod_ean").val("");
	    		$("#boton_guardar_eans").show();
	    		$("#span_cant_ing").show();
	    	});
	    	eanModalOpen();
	    }else{
	    	toastr.warning('Este producto no tiene EANS disponibles!.');
	    }

	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(xhr.status);
		alert(thrownError);
	}
});
}

function getIfEAN(item,cantidad,actualizar,callback){
	//console.log('getIfEAN');
	//console.log(Ped_detalle,item);
	var metodo = $("#ped_metodo").val();
	$.ajax({
		data: {"codigo": item},
		url: 'packages/inventario/ajuste/views/Get_if_EAN.php',
		type: 'post',
		success: function(response) {
			var resp = JSON.parse(response);
	    //console.log('METODO',metodo);
	    if(resp[0] == 'T'){
	    	if(metodo == 'modificar'){
	    		callback();
	    	}else{
	    		if(actualizar){
	    			$("#boton_eans").attr("onclick","actualizarEans()");
	    		}else{
	    			$("#boton_eans").attr("onclick","guardarEans()");
	    		}
	           //console.log($("#ped_aplicar").val());
	           if($("#ped_aplicar").val() == "IN"){
	           	cargarEANS(item,false);
	           }else{
	           	cargarEANS(item,true,alm_origen);
	           }

	           $("#cant_ing").html(cantidad);
	       }
	   }else{
	    //if(metodo != 'modificar'){
	    	callback();
	    //}
	}
},
error: function(xhr, ajaxOptions, thrownError) {
	alert(xhr.status);
	alert(thrownError);
}
});
}

function guardarEans(){
	//console.log('guardarEans');
	if(cantidad == eans.length){
		reng_num++;
		var Ped_detalleX = {
			reng_num: reng_num,
			cod_producto: producto_cod,
			producto: producto_des,
			lote: lote,
			cod_almacen: alm_origen,
			// almacen: almacen_des,
			cantidad: cantidad,
			costo: costo,
			neto: neto,
			eans: eans
		};

		Ped_detalle.push(Ped_detalleX);


		var tr = ('<tr id="tr_' + reng_num + '"></tr>');
		var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
		var td02 = ('<td><input type="text" id="prod_' + reng_num + '" value="' + producto_des + '" readonly style="width:400px"></td>');
		var td03 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
		var td04 = ('<td><span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="button"  title="Ver Eans" onclick = verEans('+reng_num+') class="readon art-button"  value="EANS" /></span><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

		$('#listar_movimiento').append(tr);
		$('#tr_' + reng_num + '').append(td01);
		$('#tr_' + reng_num + '').append(td02);
		$('#tr_' + reng_num + '').append(td03);
		$('#tr_' + reng_num + '').append(td04);

		Cal_total();
		Limpiar_producto();
		$("#ped_producto").val("");
		$("#ped_almacen").val("");
		eanCloseModal();

	}else{
		alert("Debe seleccionar la cantidad correspondinete de EANS ("+cantidad+") ");
	}
}

function actualizarEans(){
	//console.log('actualizarEans');
	if(cantidad == eans.length){
		var idX = index + 1;
		Ped_detalle[index]["cantidad"] = cantidad;
		Ped_detalle[index]["costo"] = costo;
		Ped_detalle[index]["neto"] = neto;
		Ped_detalle[index]["eans"] = eans;

		$("#cant_" + idX + "").val(cantidad);
		$("#costo_" + idX + "").val(costo);
		$("#neto_" + idX + "").val(neto);
		$("#ped_almacen").prop('disabled', false);
		Limpiar_producto();
		Cal_total();
		Cancelar_renglon();
		eanCloseModal();
	}else{
		alert("Debe seleccionar la cantidad correspondinete de EANS ("+cantidad+") ");
	}
}

function Cal_prod_neto(evento, valor) {
	//console.log('Cal_prod_neto');
	if ((valor == "") && (valor == "undefined")) {
		alert("Ingrese un valor");
	} else {
		var valorX = parseFloat(valor);

		if (evento == "cantidad") {
			cantidad = valorX;
		}
		if (evento == "costo") {
			costo = valorX;
		}

		neto = parseFloat((cantidad * costo).toFixed(2));

		$('#ped_neto').val(neto);
		if (neto > 0) {
			$("#add_renglon").prop('disabled', false);
		} else {
			$("#add_renglon").prop('disabled', true);
		}

	}

}

function Cal_total() {
	//console.log('Cal_total');
	//	alert(tasa +", "+incluye_iva);
	sub_total = 0;
	jQuery.each(Ped_detalle, function(i) {
		sub_total = (parseFloat(sub_total) + parseFloat(Ped_detalle[i]["neto"]));
	});

	total = sub_total;
	$("#ped_total").val(parseFloat(total.toFixed(2)));

}

function eanModalOpen(){
    //console.log('eanModalOpen');
    $("#eanModal").show();
}

function eanCloseModal(){
    //console.log('eanCloseModal');
    $("#eanModal").hide();
}

function selectEAN(id,estado){
	console.log('selectEAN');
	var ean = $('#reng_num_ean_'+id).val();
	console.log(id,ean,estado);
	if(estado){
		var index = eans.indexOf(ean);
		if (index > -1) {
			eans[index] = ean;
		} else {
			eans.push(ean);
		}
	}else{
		var index = eans.indexOf(ean);
		eans.splice(index, 1);
	}
	console.log(eans);
}

function verEans(index){
    //console.log(Ped_detalle[index-1]['eans']);
    var reng_num_ean = 0;
    $('#listar_eans').html('');
    var resp = Ped_detalle[index-1]['eans'];
    jQuery.each(resp, function(i) {
    	reng_num_ean++;
    	var tr = ('<tr id="tr_ean_' + reng_num_ean + '"></tr>');
    	var td01 = ('<td><input type="text" id="reng_num_ean_' + reng_num_ean + '" value="' + resp[i] + '" style="width:300px"></td>');
    	$('#listar_eans').append(tr);
    	$('#tr_ean_' + reng_num_ean + '').append(td01);
    	$("#prod_ean").val("");
    	$("#boton_guardar_eans").hide();
    	$("#span_cant_ing").hide();
    });
    eanModalOpen();
}