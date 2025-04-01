var ajaxTimeController = setInterval(() => { refresh(); }, 30000);

$(function () {
    Add_filtroX();
    document.getElementById('documento_close').addEventListener('change', function (e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Ningún documento cargado';
        document.getElementById('file-name').textContent = fileName;
    });
});

function cargarDocumento() {
    $('#documento_close').click();
}

function Add_filtroX() {
    clearInterval(ajaxTimeController);
    refresh();
    ajaxTimeController = setInterval(() => { refresh(true); }, 30000);
}

function refresh(auto) {
    var ficha = $("#stdID").val();
    var cliente = $("#cliente").val();
    var ubicacion = $("#ubicacion").val();
    var horario = $("#horario").val();
    var parametros = {
        cliente, ficha, ubicacion, horario
    };
    Add_Estadistica();
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_confirmaciones/views/Add_planif.php',
        type: 'post',
        beforeSend: function () {
            if (!auto || auto == undefined) {
                $("#planificacion").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            }
        },
        success: function (response) {
            var resp = JSON.parse(response);
            $("#planificacion").html(resp["html"]);
            console.log(ubicacion != 'TODOS', ubicacion != '', horario, Array.isArray(horario), resp["confirmado"] == false, resp["confirmado"]);
            if (ubicacion != 'TODOS' && ubicacion != '' && horario && Array.isArray(horario) && resp["confirmado"] == false) {
                if (!horario.includes('TODOS')) {
                    $("#boton_close").show();
                    $("#documento_form").show();
                } else {
                    $("#boton_close").hide();
                    $("#documento_form").hide();
                }
            } else {
                $("#boton_close").hide();
                $("#documento_form").hide();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function Add_Estadistica() {
    var ficha = $("#stdID").val();
    var cliente = $("#cliente").val();
    var ubicacion = $("#ubicacion").val();
    var horario = $("#horario").val();

    var parametros = {
        cliente, ficha, ubicacion, horario
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_confirmaciones/views/Add_planif_estadistica.php',
        type: 'post',
        beforeSend: function () {
            $("#estadistica").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            $("#estadistica").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function setConfirm(codigo, ap_nombre, in_transport) {
    var h_confirm_index = $("#h_confirm" + codigo).val();
    if (h_confirm_index) {
        if (codigo) {
            if (confirm(`Esta seguro de que desea confirmar ${in_transport == 'T' ? 'que se encuentra en el transporte el trabajador' : 'la asistencia de el trabajador'} ${ap_nombre}. Esta operación es irreversible!.`)) {
                var usuario = $("#usuario").val();
                var parametros = {
                    codigo, usuario, in_transport
                };
                $.ajax({
                    data: parametros,
                    url: 'packages/planif/planif_confirmaciones/modelo/confirmar.php',
                    type: 'post',
                    success: function (response) {
                        var resp = JSON.parse(response);
                        if (resp.error) {
                            toastr.error("A ocurrido un error al intentar confimar la asistencia!..");
                        } else {
                            toastr.success("Asistencia confirmada exitosamente!..");
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
    } else {
        toastr.error("Debe definir la hora de confirmacion!..");
    }
}

function changeCliente(cliente) {
    $("#boton_close").hide();
    $("#documento_form").hide();
    Add_Cl_Ubic(cliente, 'contenido_ubic', 'T', '120');
    Add_filtroX();
}


function showMessage(message) {
    $(".messages").html("").show();
    $(".messages").html(message);
}

function subirDocumentoS3(archivo) {
    var horario = $("#horario").val();
    var ubicacion = $("#ubicacion").val();
    const fechaActual = new Date();

    const year = fechaActual.getFullYear();
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
    const dia = String(fechaActual.getDate()).padStart(2, '0');

    var doc = ubicacion + "_" + horario;

    var config = [
        {
            folder: "cuadres_" + year + mes + dia,
            key: doc
        }
    ]


    var formData = new FormData();
    // formData.append("images", archivo);
    formData.append("config", JSON.stringify(config));
    var message = "";

    $.ajax({
        url: 'http://194.163.161.64:9090/docs/upload_marcaje/',
        type: 'POST',
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
            closeService(data.data.image[0]);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });
}

function onCloseService() {
    const archivo = $("#documento_close")[0].files[0];
    if (archivo) {
        subirDocumentoS3(archivo);
    } else {
        closeService();
    }
}

function closeService(documentUrl) {
    var usuario = $("#usuario").val();
    var horario = $("#horario").val();
    var ubicacion = $("#ubicacion").val();
    var parametros = { "usuario": usuario, "ubicacion": ubicacion, "horario": horario, documentUrl };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_confirmaciones/views/Add_verify_service.php',
        type: 'post',
        success: function (response) {
            var resp = JSON.parse(response);
            var codigos = [];
            $('input[name="codigos[]"]').each(function () {
                codigos.push($(this).val());
            });
            if (resp.length > 0) {
                toastr.error("Existen anomalías sin observación!..");
            } else {

                if (confirm(`Esta seguro de que desea confirmar el cierre de este servicio. Esta operación es irreversible!.`)) {
                    var usuario = $("#usuario").val();
                    var parametros = {
                        usuario, codigos: codigos, documentUrl
                    };
                    $.ajax({
                        data: parametros,
                        url: 'packages/planif/planif_confirmaciones/modelo/confirmar_cierre.php',
                        type: 'post',
                        success: function (response) {
                            var resp = JSON.parse(response);
                            if (resp.error) {
                                toastr.error("A ocurrido un error al intentar confirmar el cierrre del servicio!..");
                            } else {
                                $('#documento_form')[0].reset();
                                toastr.success("Cierre de servicio confirmado exitosamente!..");
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
            console.log(resp)
            // ModalOpen();
            $("#modal_titulo").text("Confirmar cuadre de servico");
            $("#modal_contenido").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function onAddObservation(codigo, edit = false, confirmado, asistencia) {
    var usuario = $("#usuario").val();
    var parametros = { "usuario": usuario, "codigo": codigo, "edit": edit, "confirmado": confirmado, "asistencia": asistencia };
    console.log(parametros);
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_confirmaciones/views/Add_planif_observacion.php',
        type: 'post',
        success: function (response) {
            ModalOpen();
            $("#modal_titulo").text("Confirmar cuadre de servico");
            $("#modal_contenido").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function savePlanifObservation(codigo, edit, asistencia) {
    var usuario = $("#usuario").val();
    var observacion = $("#observacion" + codigo).val();
    var observacion_asisto = $("#observacion_asisto" + codigo).val();

    if (observacion_asisto) {
        var parametros = { "usuario": usuario, "observacion": observacion, "codigo": codigo, "observacion_asisto": observacion_asisto, 'asistencia': asistencia };
        $.ajax({
            data: parametros,
            url: 'packages/planif/planif_confirmaciones/modelo/save_planif_observacion.php',
            type: 'post',
            beforeSend: function () {
                $("#boton_guardar_observacion").hide();
                $("#loading_observacion").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function (response) {
                $("#loading_observacion").hide();
                $("#boton_guardar_observacion").show();
                toastr.success("Observación guardada exitosamente!..");
                if (!edit) {
                    cerrarModal();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#loading_observacion").hide();
                $("#boton_guardar_observacion").show();
                alert(xhr.status);
                alert(thrownError);
            }
        });
    } else {
        toastr.error("Debe seleccionar una observación!..");
    }
}

function cerrarModal() {
    $("#myModal").hide();
    Add_filtroX();
}

