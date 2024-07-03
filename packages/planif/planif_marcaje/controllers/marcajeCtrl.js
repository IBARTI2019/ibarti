function Add_filtroX() {
    var ficha = $("#stdID").val();
    var cliente = $("#cliente").val();
    var ubicacion = $("#ubicacion").val();
    if (ficha && cliente && cliente != 'TODOS') {
        var parametros = {
            cliente, ficha, ubicacion
        };
        $.ajax({
            data: parametros,
            url: 'packages/planif/planif_marcaje/views/Add_actividades.php',
            type: 'post',
            beforeSend: function () {
                $("#actividades").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function (response) {
                $("#actividades").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}

function setRealizado(codigo) {
    if (codigo) {
        if (confirm("Esta seguro de que desea enviar el archivo (" + codigo + "), Esta operación es irreversible!.")) {
            var usuario = $("#usuario").val();
            var archivo = $("#archivo").val();
            
            alert(archivo);
            var parametros = {
                codigo, usuario,archivo
            };
            $.ajax({
                data: parametros,
                url: 'packages/planif/planif_marcaje/modelo/marcar.php',
                type: 'post',
                success: function (response) {
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        toastr.error("A ocurrido un error al intentar enviar el archivo!..");
                    } else {
                        toastr.success("Archivo ennviado con Exitoso!..");
                        Add_filtroX();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    }
}
function showMessage(message) {
    $(".messages").html("").show();
    $(".messages").html(message);
}
function subirImagenS3marcaje(codigo) {
    //informaci�n del formulario

    var formData = new FormData($(".formulario")[0]);
    var ci =$("#cod_det2").val();;
    var doc = $("#archivo").val();
    var usuario=$("#usuario").val();
    var nombre = ci + "_" + doc;
       
    var message = "";
    //hacemos la petici�n ajax  
    $.ajax({
        url: 'http://194.163.161.64:9090/docs/upload/',
        type: 'POST',
        // Form data
        //datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        cache: false,
        contentType: false,
        processData: false,
        //mientras enviamos el archivo
        beforeSend: function () {
            message = $("<span class='before'>Subiendo la imagen, por favor espere...</span>");
            showMessage(message)
        },
        //una vez finalizado correctamente
        success: function (data) {
            uploadActulizarS3marcaje(data.data.image[0],ci,doc,usuario);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });
}

function uploadActulizarS3marcaje(url,cod,archi,xusuario) {
    alert('uploadActulizarS3: ', url);
    var ficha = cod;
    var doc =archi;
    var tusuario=xusuario
    var parametros = {
        "link": url,
        "codigo": ficha,
        "doc": doc,
        "usuario": xusuario
    };

    $.ajax({
        url: 'packages/planif/planif_marcaje/modelo/marcar.php',
        type: 'POST',
        data: parametros,
        //        cache: false,
        //      contentType: false,
        //     processData: false,

        beforeSend: function () {
        },
        //una vez finalizado correctamente
        success: function (data) {
            message = $("<span class='success'>La imagen ha sido guardada con exitos...</span>");
            showMessage(message);
            window.history.go(-1);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });

    window.location.href="inicio.php?area=packages/planif/planif_marcaje/index&Nmenu=4406&mod=003";
    return false; 
};


function changeCliente(cliente) {
    Add_Cl_Ubic(cliente, 'contenido_ubic', 'T', '120');
    Add_filtroX();
}

function addParticipante(metodo, codigo = '', ficha_delete = '') {
    var cod_ficha = $("#stdIDP").val();
    var cod_det = $("#cod_det").val()
    if (metodo == "agregar") {
        if (confirm("Esta seguro de que desea agregar a este trabajador como participante!.")) {
            var usuario = $("#usuario").val();
            var parametros = {
                cod_det, cod_ficha, usuario, metodo
            };
            $.ajax({
                data: parametros,
                url: 'packages/planif/planif_marcaje/modelo/participante.php',
                type: 'post',
                success: function (response) {
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        toastr.error("A ocurrido un error al intentar agregar al participante!..");
                    } else {
                        toastr.success("Participante agregado con exito!..");
                        cargar_participantes(cod_det)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    } else if (metodo == "eliminar") {
        if (confirm("Esta seguro de que desea eliminar el participante (" + ficha_delete + ")!.")) {
            var usuario = $("#usuario").val();
            var parametros = {
                codigo, metodo
            };
            $.ajax({
                data: parametros,
                url: 'packages/planif/planif_marcaje/modelo/participante.php',
                type: 'post',
                success: function (response) {
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        toastr.error("A ocurrido un error al intentar eliminar el participante!..");
                    } else {
                        toastr.success("Participante eliminado con exito!..");
                        cargar_participantes(cod_det)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    }
}

function addObservacion(codigo = '') {
    var observacion = $("#observacion").val();
    if (observacion == '') {
        toastr.success("La observación no puede estar vacía!..");
    } else {
        var cod_det = $("#cod_det").val()
        if (confirm("Esta seguro de que desea agregar esta observación")) {
            var usuario = $("#usuario").val();
            var parametros = {
                cod_det, observacion, usuario
            };
            $.ajax({
                data: parametros,
                url: 'packages/planif/planif_marcaje/modelo/observacion.php',
                type: 'post',
                success: function (response) {
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        toastr.error("A ocurrido un error al intentar agregar la observación!..");
                    } else {
                        toastr.success("Observación agregada con exito!..");
                        cargar_observaciones(cod_det)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    }
}

function openModalObservaciones(codigo) {
    $("#cod_det").val(codigo);
    $("#myModalO").show();
    cargar_observaciones(codigo);
}

function openModalObservacionesdos(codigo,xficha,xcedula) {
    $("#cod_det2").val(codigo);
    $("#ficha").val(xficha);
    $("#myModalO2").show();
    
}


function cerrarModalObservaciones() {
    $("#myModalO").hide();
}

function cerrarModalfile() {
    $("#myModalO2").hide();
}
function openModalParticipantes(codigo) {
    $("#cod_det").val(codigo);
    $("#myModalP").show();
    cargar_participantes(codigo);

}

function cerrarModalParticipantes() {
    $("#myModalP").hide();
}

function cargar_participantes(codigo) {
    var parametros = {
        codigo
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/Add_participantes.php',
        type: 'post',
        beforeSend: function () {
            $("#participantes").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            $("#participantes").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function cargar_observaciones(codigo) {
    var parametros = {
        codigo
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/Add_observaciones.php',
        type: 'post',
        beforeSend: function () {
            $("#observaciones").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            $("#observaciones").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}