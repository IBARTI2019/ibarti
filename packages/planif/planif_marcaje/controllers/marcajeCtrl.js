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
        if (confirm("Esta seguro de que desea marcar como realizada esta actividad (" + codigo + "), Esta operación es irreversible!.")) {
            var usuario = $("#usuario").val();
            var parametros = {
                codigo, usuario
            };
            $.ajax({
                data: parametros,
                url: 'packages/planif/planif_marcaje/modelo/marcar.php',
                type: 'post',
                success: function (response) {
                    var resp = JSON.parse(response);
                    if (resp.error) {
                        toastr.error("A ocurrido un error al intentar marcar la actividad!..");
                    } else {
                        toastr.success("Actividad Marcada con Exitoso!..");
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

function cerrarModalObservaciones() {
    $("#myModalO").hide();
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