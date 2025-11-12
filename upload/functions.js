$(document).ready(function () {
  $(".messages").hide();
  //queremos que esta variable sea global
  var fileExtension = "";
  //funci�n que observa los cambios del campo file y obtiene informaci�n
  $(":file").change(function () {
    //obtenemos un array con los datos del archivo
    var file = $("#imagen")[0].files[0];
    //obtenemos el nombre del archivo
    var fileName = file.name;
    //obtenemos la extensi�n del archivo
    fileExtension = fileName.substring(fileName.lastIndexOf(".") + 1);
    //obtenemos el tama�o del archivo
    var fileSize = file.size;
    //obtenemos el tipo de archivo image/png ejemplo
    var fileType = file.type;
    //mensaje con la informaci�n del archivo
    // personalizado ING WUILMER GARCIA
    if (validarExt(fileExtension)) {
      // if (validarSize(fileSize)) {
      $("#imgMostrar").show();
      showMessage(
        "<span class='info'>Archivo para subir: " +
          fileName +
          ", peso total: " +
          fileSize +
          " bytes.</span>"
      );
      // } else {
      //     showMessage("<span class='error'>Error: " + fileName + ",Excedio el Tama�o maximo: 10mb (" + fileSize + ")</span>");
      //     $("#imgMostrar").hide();
      // }
    } else {
      showMessage(
        "<span class='error'>Error: " +
          fileName +
          ", Extension Perimitidas: jpg, jpeg, gif, png, pdf, doc </span>"
      );
      $("#imgMostrar").hide();
    }
  });
});

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
    case "jpg":
    case "jpeg":
    case "gif":
    case "png":
    case "pdf":
    case "doc":
    case "docx":
      return true;
      break;
    default:
      return false;
      break;
  }
}

function isImage(extension) {
  switch (extension.toLowerCase()) {
    case "jpg":
    case "gif":
    case "png":
    case "jpeg":
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
    url:
      "upload/upload.php?nombre=" + nombre + "&directorio=" + directorio + "",
    type: "POST",
    // Form data
    //datos del formulario
    data: formData,
    //necesario para subir archivos via ajax
    cache: false,
    contentType: false,
    processData: false,
    //mientras enviamos el archivo
    beforeSend: function () {
      message = $(
        "<span class='before'>Subiendo la imagen, por favor espere...</span>"
      );
      showMessage(message);
    },
    //una vez finalizado correctamente
    success: function (data) {
      message = $(
        "<span class='success'>La imagen ha subido correctamente.</span>"
      );
      showMessage(message);
      uploadActulizar();
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
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
      key: doc,
    },
  ];

  formData.append("config", JSON.stringify(config));

  var message = "";
  //hacemos la petici�n ajax
  $.ajax({
    url: "http://194.163.161.64:9090/docs/upload/",
    type: "POST",
    // Form data
    //datos del formulario
    data: formData,
    //necesario para subir archivos via ajax
    cache: false,
    contentType: false,
    processData: false,
    //mientras enviamos el archivo
    beforeSend: function () {
      message = $(
        "<span class='before'>Subiendo la imagen, por favor espere...</span>"
      );
      showMessage(message);
    },
    //una vez finalizado correctamente
    success: function (data) {
      uploadActualizarS3(data.data.image[0]);
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });
}

function subirVideoS3(codigoFicha, codigoDoc) {
  var fileInput = document.getElementById("upload_video_" + codigoDoc);
  var file = fileInput.files[0];

  if (!file) {
    toastr.error("No se ha seleccionado ningún archivo de video.");
    return;
  }

  // 1. Validar el tipo de archivo (solo videos)
  if (!file.type.startsWith("video/")) {
    toastr.error("El archivo seleccionado no es un video válido.");
    fileInput.value = ""; // Limpiar el input
    return;
  }

  // 2. Validar el tamaño del archivo
  // 100 MB = 100 * 1024 * 1024 bytes
  const MAX_FILE_SIZE_BYTES = 104857600;
  const MAX_FILE_SIZE_MB = MAX_FILE_SIZE_BYTES / (1024 * 1024); // 100

  if (file.size > MAX_FILE_SIZE_BYTES) {
    toastr.error(
      "El video es demasiado grande. El límite es de " +
        MAX_FILE_SIZE_MB +
        " MB."
    );
    fileInput.value = ""; // Limpiar el input
    return;
  }

  if (
    confirm(
      "¿Está seguro de subir el video (" +
        (file.size / (1024 * 1024)).toFixed(2) +
        " MB)?"
    )
  ) {
    // Llama a la función de subida si pasa las validaciones y confirma
    subirVideo(codigoFicha, codigoDoc);
  } else {
    fileInput.value = ""; // Limpiar el input si cancela
  }
}

function subirVideo(ficha, doc) {
  //informaci�n del formulario
  toastr.info("Subiendo el video, por favor espere...");
  var formData = new FormData();
  var folder = ficha;
  var doc = doc;

  var config = [
    {
      folder: folder,
      key: doc,
    },
  ];
  formData.append("images", $("#upload_video_" + doc)[0].files[0]);
  formData.append("config", JSON.stringify(config));

  var message = "";
  //hacemos la petici�n ajax
  $.ajax({
    url: "http://194.163.161.64:9090/docs/upload/",
    type: "POST",
    // Form data
    //datos del formulario
    data: formData,
    //necesario para subir archivos via ajax
    cache: false,
    contentType: false,
    processData: false,
    //mientras enviamos el archivo
    beforeSend: function () {
      message = $(
        "<span class='before'>Subiendo la imagen, por favor espere...</span>"
      );
      showMessage(message);
    },
    //una vez finalizado correctamente
    success: function (data) {
      uploadActualizarVideoS3(data.data.image[0], ficha, doc);
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });
}

function uploadActualizarVideoS3(url, ficha, doc) {
  var parametros = {
    link: url,
    ficha: ficha,
    doc: doc,
    usuario: $("#usuario").val(),
  };

  $.ajax({
    url: "upload/documentos.php",
    type: "POST",
    data: parametros,
    beforeSend: function () {
      toastr.info("Actualizando el video, por favor espere...");
    },
    success: function (data) {
      toastr.success("Video subido correctamente.");
      location.reload();
    },
    //si ha ocurrido un error
    error: function () {
      toastr.error("Ha ocurrido un error.");
      showMessage(message);
    },
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
    url:
      "upload/upload.php?nombre=" + nombre + "&directorio=" + directorio + "",
    type: "POST",
    // Form data
    //datos del formulario
    data: formData,
    //necesario para subir archivos via ajax
    cache: false,
    contentType: false,
    processData: false,
    //mientras enviamos el archivo
    beforeSend: function () {
      message = $(
        "<span class='before'>Subiendo la imagen, por favor espere...</span>"
      );
      showMessage(message);
    },
    //una vez finalizado correctamente
    success: function (data) {
      message = $(
        "<span class='success'>La imagen ha subido correctamente.</span>"
      );
      showMessage(message);
      uploadActualizarCliente();
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });
}

function uploadActualizarS3(url) {
  console.log("uploadActualizarS3: ", url);
  var ficha = $("#ficha").val();
  var ci = $("#ci").val();
  var doc = $("#doc").val();

  var parametros = {
    link: url,
    ficha: ficha,
    ci: ci,
    doc: doc,
  };

  $.ajax({
    url: "upload/documentos.php",
    type: "POST",
    data: parametros,
    //        cache: false,
    //      contentType: false,
    //     processData: false,

    beforeSend: function () {},
    //una vez finalizado correctamente
    success: function (data) {
      message = $(
        "<span class='success'>La imagen ha subido correctamente. Actualizando</span>"
      );
      showMessage(message);
      window.history.go(-1);
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });

  //	  	window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

  //	 window.history.go(-1);
}

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
    link: url,
    ficha: ficha,
    ci: ci,
    doc: doc,
  };

  $.ajax({
    url: "upload/documentos.php",
    type: "POST",
    data: parametros,
    //        cache: false,
    //      contentType: false,
    //     processData: false,

    beforeSend: function () {},
    //una vez finalizado correctamente
    success: function (data) {
      message = $(
        "<span class='success'>La imagen ha subido correctamente. Actualizando</span>"
      );
      showMessage(message);
      window.history.go(-1);
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });

  //	  	window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

  //	 window.history.go(-1);
}

function uploadActualizarCliente(url) {
  var cliente = $("#cliente").val();
  var doc = $("#doc").val();
  var imagen = $("#imagen").val();
  var url = $("#url_new").val();
  var nombre = cliente + "_" + doc;

  var ext = imagen.split(".");
  url = url + nombre + "." + ext[1];

  var parametros = {
    link: url,
    cliente: cliente,
    doc: doc,
  };

  $.ajax({
    url: "upload/documentos_cl.php",
    type: "POST",
    data: parametros,
    //        cache: false,
    //      contentType: false,
    //     processData: false,

    beforeSend: function () {},
    //una vez finalizado correctamente
    success: function (data) {
      message = $(
        "<span class='success'>La imagen ha subido correctamente. Actualizando</span>"
      );
      showMessage(message);
      $("#fotografia").attr("src", url);
      Close();
      //window.history.go(-1);
    },
    //si ha ocurrido un error
    error: function () {
      message = $("<span class='error'>Ha ocurrido un error.</span>");
      showMessage(message);
    },
  });

  //	  	window.location.href="inicio.php?area=formularios/add_imagenes_doc2&ci="+ci+"&ficha="+ficha+"&doc="+doc+"&img="+img+"&ext="+ext+"";

  //	 window.history.go(-1);
}
