
$("#anular_form").on('submit', function(evt){
    evt.preventDefault();
    anular();
});

$("#bus_ajuste").on('submit', function(evt){
    evt.preventDefault();
    buscar_ajuste(true);
});

$(function() {
    //Cons_ajuste();
    Form_ajuste("", "agregar");
});

var lote = "19830906";
var cantidad = 0;
var costo = 0;
var neto = 0;
var producto_cod = "";
var ajuste_cod = "";
var producto_des = "";
var almacen_cod = "";
var almacen_des = "";
var Ped_detalle = [];
var reng_num = 0;
var index = 0;
var sub_total = 0;
var total = 0;

var stock_actual=0;//Esta variable es para controlar que los ajustes de salida no sobrepasen el stock actual por almacen

var eans = [];
var reng_num = 0;
var index = 0;
var callbackAgregarRenglon;
function buscarMovimiento(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//
    //console.log('buscarMovimiento');
    var error = 0;
    var errorMessage = ' ';

    var fecha_desde      = $("#fecha_desde").val();
    var fecha_hasta  = $("#fecha_hasta").val();
    var tipo_mov   = $("#tipo_mov").val();
    var proveedor     = $("#proveedor").val();
    var referencia     = $("#referencia").val();

    if( (fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true)&&(fecha_desde!="" || fecha_hasta !="")){
        var errorMessage = ' Campos De Fecha Incorrectas ';
        var error = error+1;
    }
    //console.log(fecha_desde,fecha_hasta);
    if(error == 0){
        var parametros = {fecha_desde:fecha_desde, fecha_hasta:fecha_hasta,tipo_mov:tipo_mov,proveedor:proveedor,referencia:referencia};
        $.ajax({
            data: parametros,
            url: 'packages/inventario/ajuste/views/Buscar_movimiento.php',
            type: 'post',
            beforeSend: function(){
                $("#listar_ajuste").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function(response) {
                $("#listar_ajuste").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }else{
        toastr.error(errorMessage);
    }
}


function Cons_ajuste() {
    //console.log('Cons_ajuste');

    var parametros = {}
    $.ajax({
        data: parametros,
        url: 'packages/inventario/ajuste/views/Cons_inicio.php',
        type: 'post',
        success: function(response) {
            $("#Cont_ajuste").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function Form_ajuste(cod, metodo, tipo,anulado) {
    //console.log('Form_ajuste');
    var error = 0;
    var errorMessage = ' ';
    ajuste_cod = cod;
    if (error == 0) {
        var parametros = {
            codigo: cod,
            metodo: metodo,
            anulado: anulado
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/ajuste/views/Add_form.php',
            type: 'post',
            success: function(response) {
                $("#Cont_ajuste").html(response);
                if (metodo == "modificar") {
                    //Si el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
                    //$("#borrar_ajuste").removeClass("d-none"); 
                    $("#add_renglon_etiqueta").hide();
                    $("#add_renglon").hide();
                    if(typeof tipo != "undefined"){
                       Form_ajuste_det(cod,metodo,tipo,()=>Reng_ped(cod)); 
                   }
               }
           },
           error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
    } else {
        alert(errorMessage);
    }
}

function Form_ajuste_det(cod, metodo,tipo,callback) {
    //console.log('Form_ajuste_det');
    var error = 0;
    var errorMessage = ' ';
    Ped_detalle = [];
    reng_num = 0;
    sub_total = 0;
    total = 0;
    if (error == 0) {
        var parametros = {
            codigo: cod,
            cod_tipo:tipo,//Codigo del tipo de Ajuste
            metodo: metodo
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/ajuste/views/Add_form_det.php',
            type: 'post',
            beforeSend: function(){
                $("#ajuste_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function(response) {
                $("#ajuste_det").html(response);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        alert(errorMessage);
    }

    if(typeof callback == "function"){
        callback();
    }
}

function save_ajuste() {
    //console.log('save_ajuste');
    if(confirm("Esta Seguro de que desea Guardar este movimiento?")){ 
        var error = 0;
        var errorMessage = ' ';
        var proced = "p_ajuste";

        var codigo = $("#ped_codigo").val();
        var referencia = $("#ped_referencia").val();
        var tipo = $("#ped_tipo").val();
        var proveedor = $("#ped_proveedor").val();
        var fecha = $("#ped_fecha").val();
        var descripcion = $("#ped_descripcion").val();
        var total = $("#ped_total").val();
        var moneda = $("#ped_moneda").val();
        var metodo = $("#ped_metodo").val();
        var aplicar = $("#ped_aplicar").val();
        var ped_reng = JSON.stringify(Ped_detalle);
        var us = $("#usuario").val();
        if (reng_num == 0) {
            error = 1;
            errorMessage = "Debe de ingresar un articulo";
        }

        if (error == 0) {

            var parametros = {
                nro_ajuste: codigo,
                tipo: tipo,
                proveedor: proveedor,
                fecha: fecha,
                descripcion: descripcion,
                total: total,
                moneda: moneda,
                ped_reng: ped_reng,
                proced: proced,
                us: us,
                metodo: metodo,
                aplicar: aplicar,
                referencia:referencia
            };
            
            //console.log(parametros);
            $.ajax({
                data: parametros,
                url: 'packages/inventario/ajuste/modelo/ajuste.php',
                type: 'post',
                success: function(response) {
                    //console.log(response);
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        alert(resp.mensaje);
                    } else {
                        if (metodo == "agregar") {
                            if (confirm("Actualización Exitosa!.. \n Desea AGREGAR un NUEVO REGISTRO?")) {
                                Form_ajuste("", "agregar");
                            } else {
                                Cons_ajuste();
                            }
                        } else if (metodo == "modificar") {
                            alert("Actualización Exitosa!..");
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
            
        } else {
            alert(errorMessage);
        }
    }
}

function anular_ajuste() {
    //console.log('anular_ajuste');
    if(confirm("Estas seguro de que deseas ANULAR este ajuste?")){

        ModalOpen();
        $("#modal_titulo").text("Confirmar Anulación");
    }
}

function anular(){
    //console.log('anular');
    $("#anulador").attr('disabled', true);
    var descripcion = $("#ped_descripcion_anular").val();
    if(descripcion != ""){
        if(confirm("Desea continuar esta Operación?")){
            var error = 0;
            var errorMessage = ' ';
            var proced = "p_ajuste";

            var codigo = $("#ped_codigo").val();
            var referencia = $("#ped_referencia").val();
            var proveedor = $("#ped_proveedor").val();
            var cod_tipo = $("#ped_cod_tipo").val();
            var tipo = '9999';
            if(cod_tipo == 'DOT'){
                tipo = 'ANU_DOT';
            }
            var fecha = $("#ped_fecha").val();
            var total = $("#ped_total").val();
            var metodo = 'anular';
            var aplicar = "";
            if($("#ped_aplicar").val() == "IN"){
              aplicar = 'OUT';
          }else if($("#ped_aplicar").val() == "OUT"){
              aplicar = 'IN';  
          }
          //console.log(Ped_detalle);
          var ped_reng = JSON.stringify(Ped_detalle);

          var us = $("#usuario").val();
          if (reng_num == 0) {
            error = 1;
            errorMessage = "Debe de ingresar un articulo";
        }

        
        if (error == 0) {

            if($("#ped_aplicar").val() == "IN"){
                Get_mayor_a_stock(codigo,()=>{
                    var parametros = {
                        nro_ajuste: codigo,
                        tipo: tipo,
                        fecha: fecha,
                        descripcion: descripcion,
                        total: total,
                        ped_reng: ped_reng,
                        proced: proced,
                        us: us,
                        metodo: metodo,
                        aplicar: aplicar,
                        referencia: referencia,
                        proveedor: proveedor
                    };

                    $.ajax({
                        data: parametros,
                        url: 'packages/inventario/ajuste/modelo/ajuste.php',
                        type: 'post',
                        beforeSend: function(){
                            $("#anulador").attr("disabled",true);
                        },
                        success: function(response) {
                                //console.log(response);
                                var resp = JSON.parse(response);
                                if (resp.error) {
                                    alert(resp.mensaje);
                                } else {
                                    alert("Actualización Exitosa!..");
                                    CloseModal();
                                    Cons_ajuste()
                                }
                                $("#ped_descripcion_anular").val("");
                                $("#anulador").attr("disabled",false);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status);
                                alert(thrownError);
                                $("#anulador").attr("disabled",false);
                            }
                        });
                });
            }else{
                var parametros = {
                    nro_ajuste: codigo,
                    tipo: tipo,
                    fecha: fecha,
                    descripcion: descripcion,
                    total: total,
                    ped_reng: ped_reng,
                    proced: proced,
                    us: us,
                    metodo: metodo,
                    aplicar: aplicar,
                    referencia: referencia,
                    proveedor: proveedor
                };

                $.ajax({
                    data: parametros,
                    url: 'packages/inventario/ajuste/modelo/ajuste.php',
                    type: 'post',
                    beforeSend: function(){
                        $("#anulador").attr("disabled",true);
                    },
                    success: function(response) {
                    //console.log(response);
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        alert(resp.mensaje);
                    } else {
                        alert("Actualización Exitosa!..");
                        CloseModal();
                        Cons_ajuste()
                    }
                    $("#ped_descripcion_anular").val("");
                    $("#anulador").attr("disabled",false);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                    $("#anulador").attr("disabled",false);
                }
            });
            }
            
            
        } else {
            alert(errorMessage);
            $("#anulador").attr('disabled', false);
        }
    }
}else{
    alert("La descripcion es requerida");
}
}
function Borrar_ajuste() {
    //console.log('Borrar_ajuste');
    if (confirm('Esta seguro que desea BORRAR este Registro?..')) {
        var proced = "p_ajuste";
        var codigo = $("#ped_codigo").val();
        var parametros = {
            codigo: codigo,

            proced: proced,
            us: us,
            metodo: "borrar"
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/ajuste/modelo/ajuste.php',
            type: 'post',
            success: function(response) {
                var resp = JSON.parse(response);
                if (resp.error) {
                    alert(resp.mensaje);
                } else {
                    alert('Registro Eliminado con exito!..');
                    Cons_ajuste();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}

function buscar_ajuste(isBuscar) {
    //console.log('buscar_ajuste');
    var data = $('#data_buscar_ajuste').val() || '';
    $.ajax({
        data: { 'data': data },
        url: 'packages/inventario/ajuste/views/Buscar_movimiento.php',
        type: 'post',
        beforeSend: function() {
            $('#buscarajuste').attr('disabled', true);
        },
        success: function(response) {
            $("#listar_ajuste").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
            $('#buscarHorario').attr('disabled', false);
        }
    });
}
//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
function Agregarajuste() {
    //console.log('Agregarajuste');
    var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
    if (confirm(msg)) Form_ajuste('', 'agregar');
}


function mostrar_costo_promedio(codigo,cod_almacen) {
    //console.log('mostrar_costo_promedio');
    $.ajax({
        data: { 'codigo': codigo, 'almacen': cod_almacen },
        url: 'packages/inventario/producto/views/Get_costo_prom.php',
        type: 'post',
        success: function(response) {
         var resp = JSON.parse(response);
         costo = resp[0];
         $("#ped_costo").val(resp[0]);
     },
     error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
    }
});
}

function cantidad_maxima(cod_producto,cod_almacen) {
    //console.log('cantidad_maxima');
    $.ajax({
        data: { 'producto': cod_producto, 'almacen':cod_almacen },
        url: 'packages/inventario/ajuste/views/Get_stock.php',
        type: 'post',
        success: function(response) {
           var resp = JSON.parse(response);
           stock_actual = resp[0];
           $("#ped_cantidad").attr('max',resp[0]);
       },
       error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
    }
});
}

function Selec_producto(codigo) {
    //console.log('Selec_producto');
    cantidad = 0;
    costo = 0;
    neto = 0;
    Limpiar_producto();
    if ((codigo != "") && (codigo != "undefined")) {
        producto_cod = codigo;
        producto_des = $("#ped_producto").val();
        $("#ped_cantidad").prop('disabled', false);
        if($("#ped_aplicar").val() == 'IN'){
            get_almacenes(() => {}
                //get_almacen_default(codigo)
                );
            $("#ped_costo").prop('disabled', false);
        }else{
            get_almacenes_stock(codigo);
        }
    } else {
        alert("Debe seleccionar un producto");
    }
}

function Selec_tipo(codigo) {
    //console.log('Selec_tipo');
    if ((codigo != "") && (codigo != "undefined")) {
        if(codigo == "COM"){
            $("#etiqueta_proveedor").show();
            $("#select_proveedor").show();
            $("#ped_proveedor").attr("required",true);
        }else{
            $("#ped_proveedor").val('');
            $("#ped_proveedor").attr("required",false);
            $("#etiqueta_proveedor").hide();
            $("#select_proveedor").hide();
        }
        var metodo = $("#ped_metodo").val();
        Form_ajuste_det(ajuste_cod,metodo,codigo);
    } else {
        alert("Debe seleccionar un Tipo de Movimiento");
    }
}

function Selec_almacen(codigo) {
    //console.log('Selec_almacen');
    if ((codigo != "") && (codigo != "undefined")) {
        almacen_cod = codigo;
        if($("#ped_aplicar").val() == 'OUT'){
            cantidad_maxima(producto_cod,codigo);
            mostrar_costo_promedio(producto_cod,codigo);
        }
        almacen_des = $("#ped_almacen option:selected").text();
    } else {
        alert("Debe seleccionar un almacen");
    }
}

function Limpiar_producto() {
    //console.log('Limpiar_producto');
  //cantidad = 0;
  //costo = 0;
  //neto = 0;
  $('#ped_cantidad').val(0);
  $('#ped_costo').val(0);
  $('#ped_neto').val(0);
  $("#add_renglon").prop('disabled', true);
  $("#ped_cantidad").prop('disabled', true);
  $("#ped_costo").prop('disabled', true);

}

function get_almacenes(callback) {
    //console.log('get_almacenes');
    $.ajax({
        data: {},
        url: 'packages/inventario/ajuste/views/Add_almacenes.php',
        type: 'post',
        success: function(response) {
            $("#ped_almacen").html(response);
            if (typeof callback == "function") {
                callback();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function get_almacenes_stock(codigo) {
    //console.log('get_almacenes_stock');
    $.ajax({
        data: {'serial':codigo},
        url: 'packages/inventario/ajuste/views/Add_almacenes_stock.php',
        type: 'post',
        success: function(response) {
            $("#ped_almacen").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
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

function Agregar_renglon() {
    //console.log('Agregar_renglon');
    var error = 0;
    var errorMessage = ' ';
    var cantidad = Number($("#ped_cantidad").val());
    if($("#ped_aplicar").val() == 'OUT'){
        if(cantidad > stock_actual){
            error = 1;
            errorMessage += "\n La cantidad maxima permitida, segun el stock actual es "+stock_actual;  
        }
/*
    var cant= cantidad;
    Ped_detalle.some((ajuste)=>{
        if(ajuste.cod_producto==producto_cod && ajuste.cod_almacen==almacen_cod){
                //cant = Number(cant) + Number(ajuste.cantidad);
                //if(cant>stock_actual){
                    error = 1;
                    errorMessage += "\n El detalle del ajuste sobrepasa la cantidad maxima permitida,para este producto en este almacen!:.";  
                    return true;
                //}
            }
        });
        */
    }

    Ped_detalle.some((ajuste)=>{
        if(ajuste.cod_producto==producto_cod && ajuste.cod_almacen==almacen_cod){
            error = 1;
            errorMessage += "\n El registro ya existe en el detalle del movimiento!:.";  
            return true;
        }
    });

    if (almacen_cod == "") {
        error = 1;
        errorMessage += "\n Debe Seleccionar un Almacen..";
    }
    if (cantidad < 1) {
        error = 1;
        errorMessage += "\n Cantidad invalida..";
    }

    if (error == 0) {
        getIfEAN(producto_cod,cantidad,false,()=>{

            reng_num++;
            var Ped_detalleX = {
                reng_num: reng_num,
                cod_producto: producto_cod,
                producto: producto_des,
                lote: lote,
                cod_almacen: almacen_cod,
                almacen: almacen_des,
                cantidad: cantidad,
                costo: costo,
                neto: neto,
                eans: []
            };

            Ped_detalle.push(Ped_detalleX);
            var tr = ('<tr id="tr_' + reng_num + '"></tr>');
            var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
            var td02 = ('<td>'+ producto_des + '</td>');
            var td03 = ('<td>' + almacen_des + '</td>');
            var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
            var td05 = ('<td><input type="text" id="costo_' + reng_num + '" value="' + costo + '" readonly style="width:100px"></td>');
            var td06 = ('<td><input type="text" id="neto_' + reng_num + '" value="' + neto + '" readonly style="width:150px"></td>');
            var td07 = ('<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

            $('#listar_ajuste').append(tr);
            $('#tr_' + reng_num + '').append(td01);
            $('#tr_' + reng_num + '').append(td02);
            $('#tr_' + reng_num + '').append(td03);
            $('#tr_' + reng_num + '').append(td04);
            $('#tr_' + reng_num + '').append(td05);
            $('#tr_' + reng_num + '').append(td06);
            $('#tr_' + reng_num + '').append(td07);
            Cal_total();
            Limpiar_producto();
            $("#ped_producto").val("");
            $("#ped_almacen").val("");

        });
    } else {
        alert(errorMessage);
    }
}


function Modificar_renglon(codigo) {
    //console.log('Modificar_renglon');
    index = codigo - 1;
    $("#ped_producto").prop('disabled', true);
    $("#ped_filtro_producto").prop('disabled', true);
    $("#buscarProducto").hide();
    $("#ped_almacen").prop('disabled', true);
    $("#ped_cantidad").prop('disabled', false);

    if($("#ped_aplicar").val()=='IN'){
        $("#ped_costo").prop('disabled', false);
    }

    $("#add_renglon").prop('hidden', true);
    $("#canc_renglon").prop('hidden', false);
    $("#update_renglon").prop('hidden', false);

    $("#ped_cantidad").val(Ped_detalle[index]["cantidad"]);
    $("#ped_costo").val(Ped_detalle[index]["costo"]);
    $("#ped_neto").val(Ped_detalle[index]["neto"]);

    cantidad = Ped_detalle[index]["cantidad"];
    costo = Ped_detalle[index]["costo"];
    neto = Ped_detalle[index]["neto"];
    if($("#ped_aplicar").val() == 'OUT'){
        cantidad_maxima(Ped_detalle[index]["cod_producto"] ,Ped_detalle[index]["cod_almacen"]);
    }
    var prod_option = '<option value="' + Ped_detalle[index]["cod_producto"] + '">' + Ped_detalle[index]["producto"] + '</option>';
    $("#ped_producto").html(prod_option);
    var prod_almacen = '<option value="' + Ped_detalle[index]["cod_almacen"] + '">' + Ped_detalle[index]["almacen"] + '</option>';
    $("#ped_almacen").html(prod_almacen);
    //$("#ped_almacen > option value=" + Ped_detalle[index]["cod_almacen"] + "]").attr("selected", true);
    Cal_total();
}

function Cancelar_renglon() {
    //console.log('Cancelar_renglon');
    $("#ped_producto").prop('disabled', false);
    $("#ped_almacen").prop('disabled', false);
    $("#ped_filtro_producto").prop('disabled', false);
    $("#buscarProducto").show();
    $("#add_renglon").prop('hidden', false);
    $("#canc_renglon").prop('hidden', true);
    $("#update_renglon").prop('hidden', true);
    Limpiar_producto();
    //var prod_option  = '<option value="">Seleccione...</option>';
    //$("#ped_producto").html(prod_option);
}

function Actualizar_renglon() {
    //console.log('Actualizar_renglon');
    error = 0;
    errorMessage = "";
    if($("#ped_aplicar").val() == 'OUT'){
        if(cantidad > stock_actual){
            error = 1;
            errorMessage += "\n La cantidad maxima permitida, segun el stock actual es "+stock_actual;  
        }
    }
    if(error == 0){
        //console.log(Ped_detalle[index]);
        getIfEAN(Ped_detalle[index]["cod_producto"],cantidad,true,()=>{ 
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
            Cancelar_renglon();});
    }else {
        alert(errorMessage);
    }
}

function Borrar_renglon(intemsV) {
    //console.log('Borrar_renglon');
    var datos = Ped_detalle;
    $("#listar_ajuste").html("");
    Ped_detalle = [];
    reng_num = 0;

    jQuery.each(datos, function(i) {
        if (datos[i]["reng_num"] != intemsV) {
            reng_num++;
            datos[i]["reng_num"] = reng_num;
            Ped_detalle.push(datos[i]);
            //console.log(datos[i]);
            var tr = ('<tr id="tr_' + reng_num + '">');
            var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
            var td02 = ('<td>' + datos[i]["producto"] + '</td>');
            var td03 = ('<td>' + datos[i]["almacen"] + '</td>');
            var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + datos[i]["cantidad"] + '" readonly style="width:100px"></td>');
            var td05 = ('<td><input type="text" id="costo_' + reng_num + '" value="' + datos[i]["costo"] + '" readonly style="width:100px"></td>');
            var td06 = ('<td><input type="text" id="neto_' + reng_num + '" value="' + datos[i]["neto"] + '" readonly style="width:150px"></td>');
            if(datos[i]["eans"].length > 0){
                var td07 = ('<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');
            }else{
             var td07 = ('<td><span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="button"  title="Ver Eans" onclick = verEans('+reng_num+') class="readon art-button"  value="EANS" /></span><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');
         }
         $('#listar_ajuste').append(tr);
         $('#tr_' + reng_num + '').append(td01);
         $('#tr_' + reng_num + '').append(td02);
         $('#tr_' + reng_num + '').append(td03);
         $('#tr_' + reng_num + '').append(td04);
         $('#tr_' + reng_num + '').append(td05);
         $('#tr_' + reng_num + '').append(td06);
         $('#tr_' + reng_num + '').append(td07);
     }
 });
    Cal_total();
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

function Reng_ped(codigo) {
    //console.log('Reng_ped');
    $.ajax({
        data: { 'codigo': codigo },
        url: 'packages/inventario/ajuste/views/Add_renglon.php',
        type: 'post',
        success: function(response) {
            var resp = JSON.parse(response);
            Ped_detalle = resp;
            reng_num = 0;
            jQuery.each(Ped_detalle, function(i) {
                //console.log(Ped_detalle[i]);
                getIfEAN(Ped_detalle[i]['cod_producto'],null,false,()=>{
                   Reng_ped_ean(Ped_detalle[i]['cod_ajuste'],Ped_detalle[i]['reng_num'],i)
               });
                reng_num = Ped_detalle[i]["reng_num"];
                //reng_num++;
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
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


function consultar_rp() {
    //console.log('consultar_rp');
    var fecha_desde = $("#fecha_desde").val();
    var fecha_hasta = $("#fecha_hasta").val();
    var almacen     = $("#almacen").val();
    var producto    = $("#producto").val();
    var tipo    = $("#tipo").val();

    var error = 0;
    var errorMessage = ' ';
    if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
        var errorMessage = ' Campos De Fecha Incorrectas ';
        var error = error+1;
    }

    if(error == 0){
        var parametros = {"fecha_desde":fecha_desde, "fecha_hasta":fecha_hasta, "almacen": almacen, "producto":producto, "tipo": tipo};
        $.ajax({
          data: parametros,
          url: 'packages/inventario/ajuste/views/Add_inv_mov_inventario.php',
          type: 'post',
          success: function(response) {
            $("#listar").html(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
    }else{
        alert(errorMessage);
    }
}

function Add_productos(almacen){
    //console.log('Add_productos');
    $.ajax({
        data: {"codigo": almacen},
        url: 'ajax/Add_stock_productos.php',
        type: 'post',
        success: function(response) {
           $("#productos").html(response);
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
    console.log('SALIDA: ', salida,'  ALMACEN:',almacen,' ITEM: ',item);
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
    console.log('getIfEAN');
    console.log(Ped_detalle,item);
    var metodo = $("#ped_metodo").val();
    $.ajax({
        data: {"codigo": item},
        url: 'packages/inventario/ajuste/views/Get_if_EAN.php',
        type: 'post',
        success: function(response) {
            var resp = JSON.parse(response);
            console.log('METODO',metodo);
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

function guardarEans(){
    //console.log('guardarEans');
    if(cantidad == eans.length){
        reng_num++;
        var Ped_detalleX = {
            reng_num: reng_num,
            cod_producto: producto_cod,
            producto: producto_des,
            lote: lote,
            cod_almacen: almacen_cod,
            almacen: almacen_des,
            cantidad: cantidad,
            costo: costo,
            neto: neto,
            eans: eans
        };

        Ped_detalle.push(Ped_detalleX);
        var tr = ('<tr id="tr_' + reng_num + '"></tr>');
        var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
        var td02 = ('<td>' + producto_des + '</td>');
        var td03 = ('<td>' + almacen_des + '</td>');
        var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
        var td05 = ('<td><input type="text" id="costo_' + reng_num + '" value="' + costo + '" readonly style="width:100px"></td>');
        var td06 = ('<td><input type="text" id="neto_' + reng_num + '" value="' + neto + '" readonly style="width:150px"></td>');
        var td07 = ('<td><span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="button"  title="Ver Eans" onclick = verEans('+reng_num+') class="readon art-button"  value="EANS" /></span><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

        $('#listar_ajuste').append(tr);
        $('#tr_' + reng_num + '').append(td01);
        $('#tr_' + reng_num + '').append(td02);
        $('#tr_' + reng_num + '').append(td03);
        $('#tr_' + reng_num + '').append(td04);
        $('#tr_' + reng_num + '').append(td05);
        $('#tr_' + reng_num + '').append(td06);
        $('#tr_' + reng_num + '').append(td07);
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

function eanModalOpen(){
    //console.log('eanModalOpen');
    $("#eanModal").show();
}

function eanCloseModal(){
    //console.log('eanCloseModal');
    $("#eanModal").hide();
}

function selectEAN(id,estado){
    //console.log('selectEAN');
    var ean = $('#reng_num_ean_'+id).val();
    //console.log(id,ean,estado);
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
  //console.log(eans);
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

function Get_mayor_a_stock(cod_ajuste,callback) {
    $.ajax({
        data: { 'codigo': cod_ajuste },
        url: 'packages/inventario/ajuste/views/Get_if_mayor_stock_actual.php',
        type: 'post',
        success: function(response) {
            var resp = JSON.parse(response);
            if(resp.length > 0){
                var productos = "";
                resp.forEach((d)=>{
                    console.log(d);
                    productos += d.cod_producto + " - ";
                });
                toastr.error(productos,"Es imposible generar esta Operacion porque las cantidades en los siguientes productos superan la existencia actual.");
            }else{
                if(typeof callback === "function") callback();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function filtrarEANS(elem){
    var ValorBusqueda = new RegExp($(elem).val(), 'i');
    $('#listar_eans tr').hide();
    $('#listar_eans tr').filter(function (i) {
        i++;
        console.log(ValorBusqueda, $('#reng_num_ean_'+i).val(), '#reng_num_ean_'+i);
        return ValorBusqueda.test($('#reng_num_ean_'+i ).val());
    }).show();
}