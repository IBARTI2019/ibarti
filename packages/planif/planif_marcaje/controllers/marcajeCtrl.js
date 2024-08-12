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
    var folder =$("#stdID").val();;
    var doc = $("#cod_det2").val();
    var usuario=$("#usuario").val();
    var nombre = ci + "_" + doc;
    var config = [
        {
          folder: folder,
          key: doc
        }
      ]
    
      formData.append("config", JSON.stringify(config));
    var message = "";
    //hacemos la petici�n ajax  
    $.ajax({
        url: 'http://194.163.161.64:9090/docs/upload_marcaje/',
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
            uploadActulizarS3marcaje(data.data.image[0],folder,doc,usuario);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });
}

function uploadActulizarS3marcaje(url,cod,archi,xusuario) {
    
    var ficha = cod;
    
    var cod_ficha =$("#stdID").val();;
    var cod_cliente=$("#cliente").val();
    var cod_ubicacion=$("#ubicacion").val();
    var marcados=document.some_form['marcado'];
    var lista=[];
    if (marcados.length >0 ){
	    for(i=0;i<marcados.length;i++){
		    if(marcados[i].checked){
                 lista.push(i);
		    }
        }
    } 
    
    let vectorJSON = JSON.stringify(lista);
    var doc =archi;
    var tusuario=xusuario
    var parametros = {
        "link": url,
        "codigo": doc,
        "doc": doc,
        "usuario": xusuario,
        "vector" : vectorJSON,
        "cod_ficha":cod_ficha,
        "cod_cliente":cod_cliente,
        "cod_ubicacion":cod_ubicacion
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
            Add_filtroX();
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });

    cerrarModalfile();
   
        
    
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
function addParticipanteNO(metodo, codigo = '', ficha_delete = '') {
    var cod_ficha = $("#stdIDP1").val();
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
                        cargar_participantesNO(cod_det)
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
                        cargar_participantesNO(cod_det)
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
function addObservacionNO(codigo = '') {
    var observacion = $("#observacionNO").val();
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
                        cargar_observacionesNO(cod_det)
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
function openModalObservacionesNO(codigo) {
    $("#cod_det").val(codigo);
    $("#myModalO1").show();
    cargar_observacionesNO(codigo);
}
function openModalObservacionesdos(codigo,xficha,xcliente,xubicacion,xproyecto) {
    $("#cod_det2").val(codigo);
    $("#cod_proyecto").val(xproyecto);
    $("#vector").val(xcliente);  
    $("#myModalO2").show();
    cargar_actividades(xficha,xcliente,xubicacion,xproyecto);
}


function cerrarModalObservaciones() {
    $("#myModalO").hide();
}
function cerrarModalObservacionesNO() {
    $("#myModalO1").hide();
}
function cerrarModalfile() {
    $("#myModalO2").hide();
}
function openModalParticipantes(codigo) {
    $("#cod_det").val(codigo);
    $("#myModalP").show();
    cargar_participantes(codigo);

}
function openModalParticipantesNO(codigo) {
    $("#cod_det").val(codigo);
    $("#myModalP2").show();
    cargar_participantesNO(codigo);

}

function cerrarModalParticipantesNO() {
    $("#myModalP2").hide();
}
function cerrarModalParticipantes() {
    $("#myModalP").hide();
}
function cargar_participantesNO(codigo) {
    var parametros = {
        codigo
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/Add_participantes.php',
        type: 'post',
        beforeSend: function () {
            $("#participantesNO").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            $("#participantesNO").html(response);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
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
function cargar_observacionesNO(codigo) {
    var parametros = {
        codigo
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/Add_observaciones.php',
        type: 'post',
        beforeSend: function () {
            $("#observacionesNO").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            $("#observacionesNO").html(response);
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}

function cargar_actividades(ficha,cliente,ubicacion,proyecto) {
    
    var parametros = {
        auxficha:ficha,auxcliente:cliente,auxubicacion:ubicacion,auxproyecto:proyecto
    };
    
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/cargar_actividadesNO.php',
        type: 'post',
        beforeSend: function () {
            $("#actividadesNO").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
        },
        success: function (response) {
            
             $("#actividadesNO").html(response);
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
        }
    });
}
function activarcheckbox() {
    var marcados=document.some_form['marcado'];
    
    if (marcados.length >0 ){
	    for(i=0;i<marcados.length;i++){
		    marcados[i].disabled= false ;
          
        }
    } ;
  }