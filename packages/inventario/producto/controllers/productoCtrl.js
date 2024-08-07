var metodo = "AGREGAR";

var eans = [];
var reng_num = 0;
var index = 0;

$(function() {
	Cons_producto('', 'AGREGAR');
});

function ActivarEan(activo){
 if(activo){
 	$("#tab_ean").show();
 }else{
 	$("#tab_ean").hide();
 }
}

function Cons_producto(cod, met){
	var error        = 0;
	var errorMessage = ' ';
	metodo = met;
	if(error == 0){
		var parametros = {"codigo" : cod, "metodo": metodo};
		$.ajax({
			data:  parametros,
			url:   'packages/inventario/producto/views/Add_form.php',
			type:  'post',
			success:  function (response) {
				$("#Cont_producto").html(response);
				$('#p_metodo').val(metodo);
				if(metodo == "MODIFICAR"){
					$("#p_codigo").attr('readonly',true);
					$("#peso").attr('readonly',true);
					$("#piecubico").attr('readonly',true);
					$('#borrar_producto').show();
					$('#agregar_producto').show();
					var sub_linea       = $("#p_sub_linea").val();
					get_propiedades(sub_linea);
					if($("#p_ean:checked").val()){
						cargarEans(cod);
					}
				}else if(metodo=="AGREGAR" && cod != ""){
					var sub_linea       = $("#p_sub_linea").val();
					get_propiedades(sub_linea);
					$('#agregar_producto').show();
					$("#p_codigo").attr("disabled",true);
				}
				if($("#p_ean:checked").val()){
					$("#tab_ean").show();
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

function save_producto(){
	var error = 0;
	var errorMessage 	= ' ';
	var proced       	= "p_productos";

	var codigo          = $("#p_codigo").val();
	var linea           = $("#p_linea").val();
	var sub_linea       = $("#p_sub_linea").val();
	var color         	= $("#p_color").val();
	var prod_tipo       = $("#p_prod_tipo").val();
	var unidad     		= $("#p_unidad").val();
	var proveedor     	= $("#p_proveedor").val();
	var iva     		= $("#p_iva").val();
	var descripcion     = $("#p_descripcion").val();
	var procedencia     = $("#p_procedencia").val();
	var almacen     = $("#p_almacen").val();
	/*var cost_actual     = $("#cos_actual").val();
	var cost_promedio   = $("#cos_promedio").val();
	var cost_ultimo     = $("#cos_ultimo").val();
	var stock_actual    = $("#stock_actual").val();
	var stock_comp     	= $("#stock_comp").val();
	var stock_llegar    = $("#stock_llegar").val();
	var punto_pedido   	= $("#punto_pedido").val();
	var stock_maximo    = $("#stock_maximo").val();
	var stock_minimo    = $("#stock_minimo").val();*/
	var prec_vta1     	= $("#prec_vta1").val();
	var prec_vta2     	= $("#prec_vta2").val();
	var prec_vta3     	= $("#prec_vta3").val();
	var prec_vta4     	= $("#prec_vta4").val();
	var prec_vta5     	= $("#prec_vta5").val();
	var garantia     	= $("#garantia").val();
	var talla     		= $("#p_talla").val();
	var peso     		= $("#peso").val();
	var piecubico     	= $("#piecubico").val();
	var venc     		= $('input:radio[name=venc]:checked').val();
	var fec_venc     	= $("#fec_venc").val();
	var campo01     	= $("#campo01").val();
	var campo02     	= $("#campo02").val();
	var campo03     	= $("#campo03").val();
	var campo04     	= $("#campo04").val();

	var activo       	= Status($("#p_activo:checked").val());
	var ean       	= Status($("#p_ean:checked").val());
	var usuario      	= $("#usuario").val();
	var metodo       	= $("#p_metodo").val();
	var item = codigo+"-"+linea+"-"+sub_linea;

	if(color){
		item= item + "-"+color;
	}else{
		color = "9999";
	}
	if(talla){
		item= item + "-"+talla;
	}else{
		talla = "9999";
	}
	if(peso){
		item= item + "-"+parseInt(peso);
	}else{
		peso = "";
	}
	if(piecubico){
		item= item + "-"+parseInt(piecubico);
	}else{
		piecubico = "";
	}

	if(error == 0){
		var parametros = {"codigo": codigo, "activo": activo, "ean": ean, "eans":eans, "linea": linea,         
		"sub_linea" : sub_linea, "color": color,         "prod_tipo": prod_tipo ,
		"unidad": unidad , "proveedor": proveedor , "iva": iva , "item":item, "descripcion": descripcion ,
		"procedencia": procedencia,"almacen":almacen, "prec_vta1":prec_vta1, "prec_vta2":prec_vta2,"prec_vta3":prec_vta3,
		"prec_vta4":prec_vta4,"prec_vta5":prec_vta5,"garantia": garantia, "talla":talla, 
		"peso": peso,"piecubico":piecubico,"venc": venc, "fec_venc":fec_venc,
		"campo01":campo01,"campo02":campo02,"campo03":campo03,"campo04":campo04,
		"proced": proced, "usuario": usuario, "metodo":metodo };

		$.ajax({
			data:  parametros,
			url:   'packages/inventario/producto/modelo/producto.php',
			type:  'post',
			success:  function (response) {
				console.log(response);
				var resp = JSON.parse(response);
				if(resp.error){
					alert(resp.mensaje);
				}else{
					if(metodo == "AGREGAR") {
						if(confirm("Actualización Exitosa!.. \n Desea AGREGAR un NUEVO REGISTRO?")){
							Cons_producto("", "AGREGAR");
						}else{
							Cons_producto(item, "MODIFICAR");
						}
					}else if(metodo == "MODIFICAR"){
						alert("Actualización Exitosa!..");
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

function get_sub_lineas(linea,id){
	var parametros = {
		"codigo": linea
	};
	$.ajax({
		data: parametros,
		url: 'packages/inventario/producto/views/Add_sub_linea.php',
		type: 'post',
		success: function (response) {
			$("#td_sub_linea").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}


function cargar_colores(){
	var serial = $("#p_item").val();
	$.ajax({
		data: {"metodo":metodo,"serial":serial},
		url: 'packages/inventario/producto/views/Add_colores.php',
		type: 'post',
		success: function (response) {
			$("#td_color").html(response);
			$("#tr_color").show();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function cargar_tallas(){
	var serial = $("#p_item").val();
	$.ajax({
		data: {"metodo":metodo,"serial":serial},
		url: 'packages/inventario/producto/views/Add_tallas.php',
		type: 'post',
		success: function (response) {
			$("#td_talla").html(response);
			$("#tr_talla").show();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function get_propiedades(sub_linea){
	var parametros = {
		"codigo": sub_linea
	};
	$.ajax({
		data: parametros,
		url: 'packages/inventario/producto/views/Add_propiedades.php',
		type: 'post',
		success: function (response) {
			var resp = JSON.parse(response);
			if(resp[0].talla == 'T'){
				cargar_tallas();
				$("#tr_talla").show();
			}else{
				$("#td_talla").html("");
				$("#tr_talla").hide();
			}
			if(resp[0].color == 'T'){
				cargar_colores();
			}else{
				$("#td_color").html("");
				$("#tr_color").hide();
			}
			if(resp[0].peso == 'T'){
				$("#tr_peso").show();
			}else{
				$("#peso").val("");
				$("#peso").attr("required",false);
				$("#tr_peso").hide();
			}
			if(resp[0].piecubico == 'T'){
				$("#tr_piecubico").show();
			}else{
				$("#piecubico").val("");
				$("#piecubico").attr("required",false);
				$("#tr_piecubico").hide();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);
		}
	});
}

function Borrar_producto(){
	if(confirm('Esta seguro que desea BORRAR este Registro?..')){
		var usuario = $("#usuario").val();
		var serial = $("#p_item").val();
		var parametros = {
			"serial": serial,
			"usuario": usuario
		};
		$.ajax({
			data: parametros,
			url: 'packages/inventario/producto/modelo/sc_borrar.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if (resp.error) {
					toastr.error(resp.mensaje);
				} else {
					toastr.success('Registro Eliminado con exito!..');
					Cons_producto('', 'AGREGAR');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				toastr.error(xhr.status);
				toastr.error(thrownError);
			}
		});
	}
}

function B_productos(){
	var parametros = {};
	$.ajax({
		data:  parametros,
		url:   'packages/inventario/producto/views/Cons_productos.php',
		type:  'post',
		success:  function (response) {
			$("#add_producto").hide();
			$("#buscar_producto").show();
			$("#lista_productos").html( response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			toastr.error(xhr.status);
			toastr.error(thrownError);}
		});
}

function buscar(){						
	var linea     = $("#linea").val(); 						
	var sub_linea = $("#sub_linea").val(); 
	var prod_tipo = $("#prod_tipo").val();
	var tipo_mov  = $("#tipo_mov").val(); 

	var filtro    = $("#paciFiltro").val(); 
	var producto  = $("#stdID").val(); 
	var error     = 0; 
	var errorMessage = ' ';

	if(error == 0){
		var parametros = {"linea":linea,"sub_linea":sub_linea,"prod_tipo":prod_tipo,"tipo_mov":tipo_mov,
		"filtro":filtro, "producto":producto};
		$.ajax({
			data:  parametros,
			url:   'packages/inventario/producto/views/Add_productos2.php',
			type:  'post',
			success:  function (response) {
				$("#lista_productos").html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				toastr.error(xhr.status);
				toastr.error(thrownError);}
			});
	}else{
		toastr.error(errorMessage);
	}

}

//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
function irAAgregarProducto(){
	var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
	if(confirm(msg)) Cons_producto('', 'AGREGAR');
}

function volver(){
	$("#buscar_producto").hide();
	$("#add_producto").show();
}

function agregar_ean(){
    var error        = 0;
    var errorMessage = ' ';
    var ean = $("#prod_ean").val();
    var existe = eans.some((d)=>d.ean == ean);

    if(ean == ""){
        error = 1;
        errorMessage = "El EAN es Obligatorio.";
    }

    if(existe){
    	error = 1;
        errorMessage = "Este EAN ya existe!..";
    }

    if(error == 0){
    	reng_num++;
        eans.push(ean);
        var tr = ('<tr id="tr_' + reng_num + '"></tr>');
        var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + ean + '" style="width:300px"></td>');
		var td02 = ('<td><img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_ean(' + reng_num + ')" title="Borrar Registro"/> </td>');

        $('#listar_eans').append(tr);
        $('#tr_' + reng_num + '').append(td01);
        $('#tr_' + reng_num + '').append(td02);
        $("#prod_ean").val("");
	}else{
	    toastr.warning(errorMessage);
	}
}

function Borrar_ean(intemsV){
 if(confirm("Esta seguro de borrar temporalmente este EAN?")){
    var error        = 0;
    var errorMessage = ' ';

   // eanIsStock(cod_producto, ean, =>{

   // });
   console.log(eans[intemsV]);
    if(error == 0){

    var datos = eans;
    $("#listar_eans").html("");
    eans = [];
    reng_num = 0;
    jQuery.each(datos, function(i) {
        reng_num++;
        console.log(intemsV,reng_num);
        if (reng_num != intemsV) {
            eans.push(datos[i]);

	        var tr = ('<tr id="tr_' + eans.length + '"></tr>');
	        var td01 = ('<td><input type="text" id="reng_num_' + eans.length + '" value="' + datos[i] + '" style="width:300px"></td>');
			var td02 = ('<td><img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_ean(' + eans.length + ')" title="Borrar Registro"/> </td>');

	        $('#listar_eans').append(tr);
	        $('#tr_' + eans.length + '').append(td01);
	        $('#tr_' + eans.length + '').append(td02);
        }
    });
    }else{
        toastr.error(errorMessage);
    }
}
}

function cargarEans(codigo){
	$.ajax({
	        data: { 'codigo': codigo },
	        url: 'packages/inventario/producto/views/Add_EAN.php',
	        type: 'post',
	        success: function(response) {
	        	console.log(response);
	            var resp = JSON.parse(response);
	            reng_num = 0;
	            jQuery.each(resp, function(i) {
	            	    	reng_num++;
					        eans.push(resp[i].cod_ean);
					        var tr = ('<tr id="tr_' + reng_num + '"></tr>');
					        var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + resp[i].cod_ean + '" style="width:300px"></td>');
							var td02 = ('<td><img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_ean(' + reng_num + ')" title="Borrar Registro"/> </td>');

					        $('#listar_eans').append(tr);
					        $('#tr_' + reng_num + '').append(td01);
					        $('#tr_' + reng_num + '').append(td02);
					        $("#prod_ean").val("");
	            });
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	            alert(xhr.status);
	            alert(thrownError);
	        }
	});
}