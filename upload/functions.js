$(document).ready(function () {

    $(".messages").hide();
    //queremos que esta variable sea global
    var fileExtension = "";
    //funci�n que observa los cambios del campo file y obtiene informaci�n
    $(':file').change(function () {
        //obtenemos un array con los datos del archivo
        var file = $("#imagen")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensi�n del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tama�o del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la informaci�n del archivo
        // personalizado ING WUILMER GARCIA
        if (validarExt(fileExtension)) {
            if (validarSize(fileSize)) {
                $("#imgMostrar").show();
                showMessage("<span class='info'>Archivo para subir: " + fileName + ", peso total: " + fileSize + " bytes.</span>");
            } else {
                showMessage("<span class='error'>Error: " + fileName + ",Excedio el Tama�o maximo: 10mb (" + fileSize + ")</span>");
                $("#imgMostrar").hide();
            }
        } else {
            showMessage("<span class='error'>Error: " + fileName + ", Extension Perimitidas: jpg, jpeg, gif, png, pdf, doc </span>");
            $("#imgMostrar").hide();
        }
    });
})

//como la utilizamos demasiadas veces, creamos una funci�n para 
//evitar repetici�n de c�digo
function showMessage(message) {
    $(".messages").html("").show();
    $(".messages").html(message);
}

//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido

function validarSize(valor) {
    var maximo = 15000000;
    if (maximo >= valor) {
        return true;
    } else {
        return false;
    }
}

//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido

function validarExt(extension) {
    switch (extension.toLowerCase()) {
        case 'jpg': case 'jpeg': case 'gif': case 'png': case 'pdf': case 'doc': case 'docx':
            return true;
            break;
        default:
            return false;
            break;
    }
}

function isImage(extension) {
    switch (extension.toLowerCase()) {
        case 'jpg': case 'gif': case 'png': case 'jpeg':
            return true;
            break;
        default:
            return false;
            break;
    }
}

function subirImagen(directorio) {
    //informaci�n del formulario

    var formData = new FormData($(".formulario")[0]);
    var ci = $("#ci").val();
    var doc = $("#doc").val();
    var nombre = ci + "_" + doc;

    var message = "";
    //hacemos la petici�n ajax  
    $.ajax({
        url: 'upload/upload.php?nombre=' + nombre + '&directorio=' + directorio + '',
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
            message = $("<span class='success'>La imagen ha subido correctamente.</span>");
            showMessage(message);
            uploadActulizar();
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });
}

function subirImagenS3(directorio) {
    //informaci�n del formulario

    var formData = new FormData($(".formulario")[0]);
    var folder = $("#ficha").val();
    var doc = $("#doc").val();

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
            uploadActualizarS3(data.data.image[0]);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un ojo error.</span>");
            showMessage(message);
        }
    });
}


function subirImagenCliente(directorio) {
    //informaci�n del formulario

    var formData = new FormData($(".formulario")[0]);
    var cliente = $("#cliente").val();
    var doc = $("#doc").val();
    var nombre = cliente + "_" + doc;

    var message = "";
    //hacemos la petici�n ajax  
    $.ajax({
        url: 'upload/upload.php?nombre=' + nombre + '&directorio=' + directorio + '',
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
            message = $("<span class='success'>La imagen ha subido correctamente.</span>");
            showMessage(message);
            uploadActualizarCliente();
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });
}

function uploadActualizarS3(url) {
    console.log('uploadActualizarS3: ', url)
    var ficha = $("#ficha").val();
    var ci = $("#ci").val();
    var doc = $("#doc").val();
    
    var parametros = {
        "link": url,
        "ficha": ficha,
        "ci": ci,
        "doc": doc,
        "metodo":'agregar'
    };

    $.ajax({
        url: 'upload/documentos.php',
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
            message = $("<span class='error'>Ha ocurrido un poli error.</span>");
            showMessage(message);
        }
    });

    //window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

     //window.history.go(-1);
};

function uploadActulizar(url) {
    var ficha = $("#ficha").val();
    var ci = $("#ci").val();
    var doc = $("#doc").val();
    var imagen = $("#imagen").val();
    var url = $("#url_new").val();
    var nombre = ci + "_" + doc;

    var ext = imagen.split(".");
    url = url + nombre + "." + ext[1];

    var parametros = {
        "link": url,
        "ficha": ficha,
        "ci": ci,
        "doc": doc
    };

    $.ajax({
        url: 'upload/documentos.php',
        type: 'POST',
        data: parametros,
        //        cache: false,
        //      contentType: false,
        //     processData: false,

        beforeSend: function () {
        },
        //una vez finalizado correctamente
        success: function (data) {
            message = $("<span class='success'>La imagen ha subido correctamente. Actualizando</span>");
            showMessage(message);
            window.history.go(-1);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });

    //	  	window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

    //	 window.history.go(-1);
};

function uploadActualizarCliente(url) {
    var cliente = $("#cliente").val();
    var doc = $("#doc").val();
    var imagen = $("#imagen").val();
    var url = $("#url_new").val();
    var nombre = cliente + "_" + doc;

    var ext = imagen.split(".");
    url = url + nombre + "." + ext[1];

    var parametros = {
        "link": url,
        "cliente": cliente,
        "doc": doc
    };

    $.ajax({
        url: 'upload/documentos_cl.php',
        type: 'POST',
        data: parametros,
        //        cache: false,
        //      contentType: false,
        //     processData: false,

        beforeSend: function () {
        },
        //una vez finalizado correctamente
        success: function (data) {
            message = $("<span class='success'>La imagen ha subido correctamente. Actualizando</span>");
            showMessage(message);
            $("#fotografia").attr('src', url);
            Close();
            //window.history.go(-1);
        },
        //si ha ocurrido un error
        error: function () {
            message = $("<span class='error'>Ha ocurrido un error.</span>");
            showMessage(message);
        }
    });

    //	  	window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

    //	 window.history.go(-1);
};
function cargar_actividadesNO(codigo) {
    var parametros = {
        codigo
    };
    $.ajax({
        data: parametros,
        url: 'packages/planif/planif_marcaje/views/Add_actividades.php',
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
