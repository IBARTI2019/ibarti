function Add_filtroX() {
    var ficha = $("#stdID").val();
    var cliente = $("#cliente").val();
    var ubicacion = $("#ubicacion").val();
    if(cliente == 'TODOS' || cliente == ''){
        toastr.error("Debe seleccionar un Cliente!..");
    }else{
        var parametros = {
            cliente, ficha, ubicacion
        };
        $.ajax({
            data: parametros,
            url: 'packages/planif/planif_confirmaciones/views/Add_planif.php',
            type: 'post',
            beforeSend: function () {
                $("#planificacion").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
            },
            success: function (response) {
                $("#planificacion").html(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
}

function setConfirm(codigo, ap_nombre, in_transport) {
    var h_confirm_index = $("#h_confirm"+codigo).val();
    if(h_confirm_index){
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
    }else{
        toastr.error("Debe definir la hora de confirmacion!..");
    }
}

function changeCliente(cliente) {
    Add_Cl_Ubic(cliente, 'contenido_ubic', 'T', '120');
    Add_filtroX();
}