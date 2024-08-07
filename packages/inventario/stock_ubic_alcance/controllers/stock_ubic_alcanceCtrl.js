
$("#anular_form").on('submit', function (evt) {
    evt.preventDefault();
    anular();
});

$("#bus_stock_ubic_alcance").on('submit', function (evt) {
    evt.preventDefault();
    buscar_stock_ubic_alcance(true);
});

$(function () {
    //Cons_stock_ubic_alcance();
    Form_stock_ubic_alcance("", "agregar");
});

var lote = "19830906";
var cantidad = 0;
var costo = 0;
var neto = 0;
var producto_cod = "";
var stock_ubic_alcance_cod = "";
var producto_des = "";
var almacen_cod = "";
var almacen_des = "";
var Ped_detalle = [];
var reng_num = 0;
var index = 0;
var sub_total = 0;
var total = 0;

var stock_actual = 0;//Esta variable es para controlar que los stock_ubic_alcances de salida no sobrepasen el stock actual por ubicacion

var eans = [];
var reng_num = 0;
var index = 0;
var callbackAgregarRenglon;

function buscarMovimiento() {  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//
    //console.log('buscarMovimiento');
    var error = 0;
    var errorMessage = ' ';

    var fecha_desde = $("#fecha_desde").val();
    var fecha_hasta = $("#fecha_hasta").val();
    var ubicacion = $("#ubicacion").val();
    var codigo = $("#codigo").val();
    var producto = $("#stdID").val();
    var cliente = $("#cliente").val();

     
    if ((fechaValida(fecha_desde) != true || fechaValida(fecha_hasta) != true) && (fecha_desde != "" || fecha_hasta != "")) {
        var errorMessage = ' Campos De Fecha Incorrectas ';
        var error = error + 1;
    }
    //console.log(fecha_desde,fecha_hasta);
    if (error == 0) {
        var parametros = { fecha_desde, fecha_hasta, ubicacion, codigo, producto,cliente};
        $.ajax({
            data: parametros,
            url: 'packages/inventario/stock_ubic_alcance/views/Buscar_movimiento.php',
            type: 'post',
            beforeSend: function () {
                $("#listar_stock_ubic_alcance").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function (response) {
                $("#listar_stock_ubic_alcance").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        toastr.error(errorMessage);
    }
}


function Cons_stock_ubic_alcance() {
    //console.log('Cons_stock_ubic_alcance');

    var parametros = {}
    $.ajax({
        data: parametros,
        url: 'packages/inventario/stock_ubic_alcance/views/Cons_inicio.php',
        type: 'post',
        success: function (response) {
            $("#Cont_stock_ubic_alcance").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function Form_stock_ubic_alcance(cod, metodo, anulado) {
    //console.log('Form_stock_ubic_alcance');
    var error = 0;
    var errorMessage = ' ';
    stock_ubic_alcance_cod = cod;
    if (error == 0) {
        var parametros = {
            codigo: cod,
            metodo: metodo,
            anulado: anulado
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/stock_ubic_alcance/views/Add_form.php',
            type: 'post',
            success: function (response) {
                $("#Cont_stock_ubic_alcance").html(response);
                Form_stock_ubic_alcance_det(cod, metodo, () => { });
                if (metodo == "modificar") {
                    //Si el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
                    //$("#borrar_stock_ubic_alcance").removeClass("d-none"); 
                    $("#add_renglon_etiqueta").hide();
                    $("#add_renglon").hide();
                    Reng_ped(cod);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        alert(errorMessage);
    }
}

function Form_stock_ubic_alcance_det(cod, metodo, callback) {
    //console.log('Form_stock_ubic_alcance_det');
    var error = 0;
    var errorMessage = ' ';
    Ped_detalle = [];
    reng_num = 0;
    if (error == 0) {
        var parametros = {
            codigo: cod,
            metodo: metodo
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/stock_ubic_alcance/views/Add_form_det.php',
            type: 'post',
            beforeSend: function () {
                $("#stock_ubic_alcance_det").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function (response) {
                $("#stock_ubic_alcance_det").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        alert(errorMessage);
    }

    if (typeof callback == "function") {
        callback();
    }
}

function save_stock_ubic_alcance() {
    //console.log('save_stock_ubic_alcance');
    if (confirm("Esta Seguro de que desea Guardar este movimiento?")) {
        var error = 0;
        var errorMessage = ' ';
        var proced = "p_stock_ubic_alcance";

        var codigo = $("#ped_codigo").val();
        var referencia = $("#ped_referencia").val();
        var ubicacion = $("#ubicacion").val();
        var fecha = $("#ped_fecha").val();
        var descripcion = $("#ped_descripcion").val();
        var metodo = $("#ped_metodo").val();
        var ped_reng = JSON.stringify(Ped_detalle);
        var us = $("#usuario").val();
        if (reng_num == 0) {
            error = 1;
            errorMessage = "Debe de ingresar un articulo";
        }

        if (error == 0) {

            var parametros = {
                codigo: codigo,
                ubic: ubicacion,
                fecha: fecha,
                descripcion: descripcion,
                ped_reng: ped_reng,
                proced: proced,
                us: us,
                metodo: metodo,
                referencia: referencia
            };

            //console.log(parametros);
            $.ajax({
                data: parametros,
                url: 'packages/inventario/stock_ubic_alcance/modelo/stock_ubic_alcance.php',
                type: 'post',
                success: function (response) {
                    //console.log(response);
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        alert(resp.mensaje);
                    } else {
                        if (metodo == "agregar") {
                            if (confirm("Actualización Exitosa!.. \n Desea AGREGAR un NUEVO REGISTRO?")) {
                                Form_stock_ubic_alcance("", "agregar");
                            } else {
                                Cons_stock_ubic_alcance();
                            }
                        } else if (metodo == "modificar") {
                            alert("Actualización Exitosa!..");
                        }
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

        } else {
            alert(errorMessage);
        }
    }
}

function anular_stock_ubic_alcance() {
    //console.log('anular_stock_ubic_alcance');
    if (confirm("Estas seguro de que deseas ANULAR este ajuste?")) {
        ModalOpen();
        $("#modal_titulo").text("Confirmar Anulación");
    }
}

function anular() {
    //console.log('anular');
    $("#anulador").attr('disabled', true);
    var descripcion = $("#ped_descripcion_anular").val();
    if (descripcion != "") {
        if (confirm("Desea continuar esta Operación?")) {
            var codigo = $("#ped_codigo").val();
            var metodo = 'anular';
            //console.log(Ped_detalle);
            var us = $("#usuario").val();

            var parametros = {
                codigo: codigo,
                descripcion: descripcion,
                us: us,
                metodo: metodo
            };

            $.ajax({
                data: parametros,
                url: 'packages/inventario/stock_ubic_alcance/modelo/stock_ubic_alcance.php',
                type: 'post',
                beforeSend: function () {
                    $("#anulador").attr("disabled", true);
                },
                success: function (response) {
                    //console.log(response);
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        alert(resp.mensaje);
                    } else {
                        alert("Actualización Exitosa!..");
                        CloseModal();
                        Cons_stock_ubic_alcance()
                    }
                    $("#ped_descripcion_anular").val("");
                    $("#anulador").attr("disabled", false);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                    $("#anulador").attr("disabled", false);
                }
            });
        }
    } else {
        alert("La descripcion es requerida");
    }
}

function Borrar_stock_ubic_alcance() {
    //console.log('Borrar_stock_ubic_alcance');
    if (confirm('Esta seguro que desea BORRAR este Registro?..')) {
        var proced = "p_stock_ubic_alcance";
        var codigo = $("#ped_codigo").val();
        var parametros = {
            codigo: codigo,

            proced: proced,
            us: us,
            metodo: "borrar"
        };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/stock_ubic_alcance/modelo/stock_ubic_alcance.php',
            type: 'post',
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.error) {
                    alert(resp.mensaje);
                } else {
                    alert('Registro Eliminado con exito!..');
                    Cons_stock_ubic_alcance();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}

function buscar_stock_ubic_alcance(isBuscar) {
    //console.log('buscar_stock_ubic_alcance');
    var data = $('#data_buscar_stock_ubic_alcance').val() || '';
    $.ajax({
        data: { 'data': data },
        url: 'packages/inventario/stock_ubic_alcance/views/Buscar_movimiento.php',
        type: 'post',
        beforeSend: function () {
            $('#buscarstock_ubic_alcance').attr('disabled', true);
        },
        success: function (response) {
            $("#listar_stock_ubic_alcance").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
            $('#buscarHorario').attr('disabled', false);
        }
    });
}
//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
function Agregarstock_ubic_alcance() {
    //console.log('Agregarstock_ubic_alcance');
    var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
    if (confirm(msg)) Form_stock_ubic_alcance('', 'agregar');
}


function cantidad_maxima(cod_producto, cod_almacen) {
    //console.log('cantidad_maxima');
    $.ajax({
        data: { 'producto': cod_producto, 'almacen': cod_almacen },
        url: 'packages/inventario/ajuste/views/Get_stock.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            stock_actual = resp[0];
            $("#ped_cantidad").attr('max', resp[0]);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function validarAlcance(cod_producto, cod_ubicacion, callback) {
    //console.log('cantidad_maxima');
    $.ajax({
        data: { 'codigo': cod_producto, 'ubic': cod_ubicacion },
        url: 'packages/inventario/stock_ubic_alcance/views/Get_alcance.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            alcance = resp[0];
            if (typeof callback == "function") {
                callback(alcance);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
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
        get_almacenes_stock(codigo);
    } else {
        alert("Debe seleccionar un producto");
    }
}

function Add_filtroX() {
    //console.log('Selec_ubic');
    codigo = $("#ped_ubic").val();
    if ((codigo != "") && (codigo != "undefined")) {
        $("#ped_producto").attr("disabled", false);
    }
}

function Selec_almacen(codigo) {
    //console.log('Selec_almacen ');
    if ((codigo != "") && (codigo != "undefined")) {
        almacen_cod = codigo;
        cantidad_maxima(producto_cod, codigo);
        $("#ped_producto").attr("disabled", false);
        almacen_des = $("#ped_almacen option:selected").text();
    } else {
        $("#ped_producto").attr("disabled", true);
        alert("Debe seleccionar un ubicacion");
    }
}

function Limpiar_producto() {
    //console.log('Limpiar_producto');
    //cantidad = 0;
    //costo = 0;
    //neto = 0;
    $('#ped_cantidad').val(0);
    $("#add_renglon").prop('disabled', true);
    $("#ped_cantidad").prop('disabled', true);

}

function get_ubicaciones(cliente, callback) {
    //console.log('get_ubicaciones');
    $.ajax({
        data: { cliente },
        url: 'packages/inventario/stock_ubic_alcance/views/Add_ubicaciones.php',
        type: 'post',
        success: function (response) {
            $("#ubicacion").html(response);
            if (typeof callback == "function") {
                callback();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function get_almacenes_stock(codigo) {
    //console.log('get_ubicaciones_stock');
    $.ajax({
        data: { 'serial': codigo },
        url: 'packages/inventario/ajuste/views/Add_almacenes_stock.php',
        type: 'post',
        success: function (response) {
            $("#ped_almacen").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
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
    cantidad = Number($("#ped_cantidad").val());
    ubic = $("#ubicacion").val();
    if (cantidad > stock_actual) {
        error = 1;
        errorMessage += "\n La cantidad maxima permitida, segun el stock actual es " + stock_actual;
    }
    Ped_detalle.some((stock_ubic_alcance) => {
        if (stock_ubic_alcance.cod_producto == producto_cod && stock_ubic_alcance.cod_almacen == almacen_cod) {
            error = 1;
            errorMessage += "\n El registro ya existe en el detalle del movimiento!:.";
            return true;
        }
    });
    if (error == 0) {
        validarAlcance(producto_cod, ubic, (alcance) => {
            if (alcance != null && cantidad > alcance) {
                error = 1;
                errorMessage += "\n La cantidad supera el alcance permitido, la cantidad debe ser menor o igual a " + alcance;
            }

            if (almacen_cod == "") {
                error = 1;
                errorMessage += "\n Debe Seleccionar un ubicacion..";
            }
            if (cantidad < 1) {
                error = 1;
                errorMessage += "\n Cantidad invalida..";
            }

            if (error == 0) {
                getIfEAN(producto_cod, cantidad, false, () => {

                    reng_num++;
                    var Ped_detalleX = {
                        reng_num: reng_num,
                        cod_producto: producto_cod,
                        producto: producto_des,
                        lote: lote,
                        cod_almacen: almacen_cod,
                        almacen: almacen_des,
                        cantidad: cantidad,
                        eans: []
                    };

                    Ped_detalle.push(Ped_detalleX);
                    var tr = ('<tr id="tr_' + reng_num + '"></tr>');
                    var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
                    var td02 = ('<td>' + producto_des + '</td>');
                    var td03 = ('<td>' + almacen_des + '</td>');
                    var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
                    // var td05 = ('<td><input type="text" id="costo_' + reng_num + '" value="' + costo + '" readonly style="width:100px"></td>');
                    // var td06 = ('<td><input type="text" id="neto_' + reng_num + '" value="' + neto + '" readonly style="width:150px"></td>');
                    var td07 = ('<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

                    $('#listar_stock_ubic_alcance').append(tr);
                    $('#tr_' + reng_num + '').append(td01);
                    $('#tr_' + reng_num + '').append(td02);
                    $('#tr_' + reng_num + '').append(td03);
                    $('#tr_' + reng_num + '').append(td04);
                    // $('#tr_' + reng_num + '').append(td05);
                    // $('#tr_' + reng_num + '').append(td06);
                    $('#tr_' + reng_num + '').append(td07);

                    Limpiar_producto();
                    $("#ped_producto").val("");
                    $("#ped_almacen").val("");

                });
            } else {
                toastr.error(errorMessage);
            }
        });
    } else {
        toastr.error(errorMessage);
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

    $("#add_renglon").prop('hidden', true);
    $("#canc_renglon").prop('hidden', false);
    $("#update_renglon").prop('hidden', false);

    $("#ped_cantidad").val(Ped_detalle[index]["cantidad"]);

    cantidad = Ped_detalle[index]["cantidad"];
    cantidad_maxima(Ped_detalle[index]["cod_producto"], Ped_detalle[index]["cod_almacen"]);
    var prod_option = '<option value="' + Ped_detalle[index]["cod_producto"] + '">' + Ped_detalle[index]["producto"] + '</option>';
    $("#ped_producto").html(prod_option);
    var prod_almacen = '<option value="' + Ped_detalle[index]["cod_almacenn"] + '">' + Ped_detalle[index]["almacen"] + '</option>';
    $("#ped_almacen").html(prod_almacen);
    //$("#ubicacion > option value=" + Ped_detalle[index]["cod_ubicacion"] + "]").attr("selected", true);

}

function Cancelar_renglon() {
    //console.log('Cancelar_renglon');
    $("#ped_producto").prop('disabled', false);
    $("#ped_almacen").prop('disabled', false);
    $("#ped_almacen").val('');
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
    cantidad = Number($("#ped_cantidad").val());
    if (cantidad > stock_actual) {
        error = 1;
        errorMessage += "\n La cantidad maxima permitida, segun el stock actual es " + stock_actual;
    }

    if (error == 0) {
        //console.log(Ped_detalle[index]);
        getIfEAN(Ped_detalle[index]["cod_producto"], cantidad, true, () => {
            var idX = index + 1;
            Ped_detalle[index]["cantidad"] = cantidad;
            Ped_detalle[index]["eans"] = eans;
            $("#cant_" + idX + "").val(cantidad);
            $("#ped_almacen").prop('disabled', false);
            Limpiar_producto();
            Cancelar_renglon();
        });
    } else {
        alert(errorMessage);
    }
}

function Borrar_renglon(intemsV) {
    //console.log('Borrar_renglon');
    var datos = Ped_detalle;
    $("#listar_stock_ubic_alcance").html("");
    Ped_detalle = [];
    reng_num = 0;

    jQuery.each(datos, function (i) {
        if (datos[i]["reng_num"] != intemsV) {
            reng_num++;
            datos[i]["reng_num"] = reng_num;
            Ped_detalle.push(datos[i]);
            //console.log(datos[i]);
            var tr = ('<tr id="tr_' + reng_num + '">');
            var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
            var td02 = ('<td>' + datos[i]["producto"] + '</td>');
            var td03 = ('<td>' + datos[i]["ubicacion"] + '</td>');
            var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + datos[i]["cantidad"] + '" readonly style="width:100px"></td>');
            var td05 = ('<td><input type="text" id="costo_' + reng_num + '" value="' + datos[i]["costo"] + '" readonly style="width:100px"></td>');
            var td06 = ('<td><input type="text" id="neto_' + reng_num + '" value="' + datos[i]["neto"] + '" readonly style="width:150px"></td>');
            if (datos[i]["eans"].length > 0) {
                var td07 = ('<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');
            } else {
                var td07 = ('<td><span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="button"  title="Ver Eans" onclick = verEans(' + reng_num + ') class="readon art-button"  value="EANS" /></span><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');
            }
            $('#listar_stock_ubic_alcance').append(tr);
            $('#tr_' + reng_num + '').append(td01);
            $('#tr_' + reng_num + '').append(td02);
            $('#tr_' + reng_num + '').append(td03);
            $('#tr_' + reng_num + '').append(td04);
            $('#tr_' + reng_num + '').append(td05);
            $('#tr_' + reng_num + '').append(td06);
            $('#tr_' + reng_num + '').append(td07);
        }
    });

}

function Reng_ped(codigo) {
    //console.log('Reng_ped');
    $.ajax({
        data: { 'codigo': codigo },
        url: 'packages/inventario/stock_ubic_alcance/views/Add_renglon.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            Ped_detalle = resp;
            reng_num = 0;
            jQuery.each(Ped_detalle, function (i) {
                //console.log(Ped_detalle[i]);
                getIfEAN(Ped_detalle[i]['cod_producto'], null, false, () => {
                    Reng_ped_ean(Ped_detalle[i]['cod_ajuste'], Ped_detalle[i]['reng_num'], i)
                });
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

function Reng_ped_ean(stock_ubic_alcance, renglon, index_detalle) {
    //console.log('Reng_ped_ean');
    $.ajax({
        data: { 'stock_ubic_alcance': stock_ubic_alcance, 'renglon': renglon },
        url: 'packages/inventario/stock_ubic_alcance/views/Add_renglon_eans.php',
        type: 'post',
        success: function (response) {
            //console.log(response);
            var resp = JSON.parse(response);
            Ped_detalle[index_detalle]["eans"] = resp;
            //console.log(Ped_detalle);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });

}


function consultar_rp() {
    //console.log('consultar_rp');
    var fecha_desde = $("#fecha_desde").val();
    var fecha_hasta = $("#fecha_hasta").val();
    var ubicacion = $("#ubicacion").val();
    var producto = $("#producto").val();
    var tipo = $("#tipo").val();

    var error = 0;
    var errorMessage = ' ';
    if (fechaValida(fecha_desde) != true || fechaValida(fecha_hasta) != true) {
        var errorMessage = ' Campos De Fecha Incorrectas ';
        var error = error + 1;
    }

    if (error == 0) {
        var parametros = { "fecha_desde": fecha_desde, "fecha_hasta": fecha_hasta, "ubicacion": ubicacion, "producto": producto, "tipo": tipo };
        $.ajax({
            data: parametros,
            url: 'packages/inventario/stock_ubic_alcance/views/Add_inv_mov_inventario.php',
            type: 'post',
            success: function (response) {
                $("#listar").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        alert(errorMessage);
    }
}

function Add_productos(ubicacion) {
    //console.log('Add_productos');
    $.ajax({
        data: { "codigo": ubicacion },
        url: 'ajax/Add_stock_productos.php',
        type: 'post',
        success: function (response) {
            $("#productos").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function cargarEANS(item, salida, almacen) {
    //console.log('cargarEANS');
    var reng_num_ean = 0;
    var salida = salida ? salida : null;
    var almacen = almacen ? almacen : null;
    console.log('SALIDA: ', salida, '  ubicacion:', ubicacion, ' ITEM: ', item);
    $.ajax({
        data: { 'codigo': item, 'salida': salida, 'almacen': almacen },
        url: 'packages/inventario/stock_ubic_alcance/views/Add_EANS.php',
        type: 'post',
        success: function (response) {
            //console.log(response);
            eans = [];
            $('#listar_eans').html('');
            var resp = JSON.parse(response);
            if (resp.length > 0) {
                jQuery.each(resp, function (i) {
                    reng_num_ean++;
                    var tr = ('<tr id="tr_ean_' + reng_num_ean + '"></tr>');
                    var td01 = ('<td><input type="text" id="reng_num_ean_' + reng_num_ean + '" value="' + resp[i].cod_ean + '" style="width:300px"></td>');
                    var td02 = ('<td><input name="activo" id="p_activo" type="checkbox" value="T" onclick="selectEAN(' + reng_num_ean + ',this.checked)"/> </td>');

                    $('#listar_eans').append(tr);
                    $('#tr_ean_' + reng_num_ean + '').append(td01);
                    $('#tr_ean_' + reng_num_ean + '').append(td02);
                    $("#prod_ean").val("");
                    $("#boton_guardar_eans").show();
                    $("#span_cant_ing").show();
                });
                eanModalOpen();
            } else {
                toastr.warning('Este producto no tiene EANS disponibles en este almacén!.');
            }

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function getIfEAN(item, cantidad, actualizar, callback) {
    var metodo = $("#ped_metodo").val();
    $.ajax({
        data: { "codigo": item },
        url: 'packages/inventario/stock_ubic_alcance/views/Get_if_EAN.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            console.log('METODO', metodo);
            if (resp[0] == 'T') {
                if (metodo == 'modificar') {
                    callback();
                } else {
                    if (actualizar) {
                        $("#boton_eans").attr("onclick", "actualizarEans()");
                    } else {
                        $("#boton_eans").attr("onclick", "guardarEans()");
                    }
                    cargarEANS(item, true, $("#ped_almacen").val());

                    $("#cant_ing").html(cantidad);
                }
            } else {
                //if(metodo != 'modificar'){
                callback();
                //}
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function guardarEans() {
    //console.log('guardarEans');
    console.log(cantidad, eans);
    if (cantidad == eans.length) {
        reng_num++;
        var Ped_detalleX = {
            reng_num: reng_num,
            cod_producto: producto_cod,
            producto: producto_des,
            cod_almacen: almacen_cod,
            almacen: almacen_des,
            cantidad: cantidad,
            eans: eans
        };

        Ped_detalle.push(Ped_detalleX);
        var tr = ('<tr id="tr_' + reng_num + '"></tr>');
        var td01 = ('<td><input type="text" id="reng_num_' + reng_num + '" value="' + reng_num + '" readonly style="width:100px"></td>');
        var td02 = ('<td>' + producto_des + '</td>');
        var td03 = ('<td>' + almacen_des + '</td>');
        var td04 = ('<td><input type="text" id="cant_' + reng_num + '" value="' + cantidad + '" readonly style="width:100px"></td>');
        var td05 = ('<td><span class="art-button-wrapper"><span class="art-button-l"> </span><span class="art-button-r"> </span><input type="button"  title="Ver Eans" onclick = verEans(' + reng_num + ') class="readon art-button"  value="EANS" /></span><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" onclick="Modificar_renglon(' + reng_num + ')" title="Modificar Registro" />&nbsp;<img  class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp"onclick="Borrar_renglon(' + reng_num + ')" title="Borrar Registro"/> </td>');

        $('#listar_stock_ubic_alcance').append(tr);
        $('#tr_' + reng_num + '').append(td01);
        $('#tr_' + reng_num + '').append(td02);
        $('#tr_' + reng_num + '').append(td03);
        $('#tr_' + reng_num + '').append(td04);
        $('#tr_' + reng_num + '').append(td05);

        Limpiar_producto();
        $("#ped_producto").val("");
        $("#ped_almacen").val("");
        eanCloseModal();

    } else {
        alert("Debe seleccionar la cantidad correspondinete de EANS (" + cantidad + ") ");
    }
}

function actualizarEans() {
    //console.log('actualizarEans');
    if (cantidad == eans.length) {
        var idX = index + 1;
        Ped_detalle[index]["cantidad"] = cantidad;
        Ped_detalle[index]["costo"] = costo;
        Ped_detalle[index]["neto"] = neto;
        Ped_detalle[index]["eans"] = eans;

        $("#cant_" + idX + "").val(cantidad);
        $("#costo_" + idX + "").val(costo);
        $("#neto_" + idX + "").val(neto);
        $("#ubicacion").prop('disabled', false);
        Limpiar_producto();

        Cancelar_renglon();
        eanCloseModal();
    } else {
        alert("Debe seleccionar la cantidad correspondinete de EANS (" + cantidad + ") ");
    }
}

function eanModalOpen() {
    //console.log('eanModalOpen');
    $("#eanModal").show();
}

function eanCloseModal() {
    //console.log('eanCloseModal');
    $("#eanModal").hide();
}

function selectEAN(id, estado) {
    //console.log('selectEAN');
    var ean = $('#reng_num_ean_' + id).val();
    //console.log(id, ean, estado);
    if (estado) {
        var index = eans.indexOf(ean);
        if (index > -1) {
            eans[index] = ean;
        } else {
            eans.push(ean);
        }
    } else {
        var index = eans.indexOf(ean);
        eans.splice(index, 1);
    }
    //console.log(eans);
}

function verEans(index) {
    //console.log(Ped_detalle[index-1]['eans']);
    var reng_num_ean = 0;
    $('#listar_eans').html('');
    console.log(Ped_detalle, index);
    var resp = Ped_detalle[index - 1]['eans'];
    jQuery.each(resp, function (i) {
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

function Get_mayor_a_stock(cod_stock_ubic_alcance, callback) {
    $.ajax({
        data: { 'codigo': cod_stock_ubic_alcance },
        url: 'packages/inventario/stock_ubic_alcance/views/Get_if_mayor_stock_actual.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            if (resp.length > 0) {
                var productos = "";
                resp.forEach((d) => {
                    console.log(d);
                    productos += d.cod_producto + " - ";
                });
                toastr.error(productos, "Es imposible generar esta Operacion porque las cantidades en los siguientes productos superan la existencia actual.");
            } else {
                if (typeof callback === "function") callback();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function imprimir() {
    var codigo_ajuste = $("#ped_codigo").val();
    $("#codigo").val(codigo_ajuste);
    $("#procesar").click();
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