function Add_filtroX() {
  var ficha = $("#stdID").val();
  var cliente = $("#cliente").val();
  var ubicacion = $("#ubicacion").val();
  if (ficha && cliente && cliente != "TODOS") {
    var parametros = {
      cliente,
      ficha,
      ubicacion,
    };

    $.ajax({
      data: parametros,
      url: "packages/planif/planif_marcaje/views/Add_actividades.php",
      type: "post",
      beforeSend: function () {
        $("#actividades").html(
          '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
        );
      },
      success: function (response) {
        $("#actividades").html(response);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      },
    });
  }
}

function setRealizado(codigo) {
  if (codigo) {
    if (
      confirm(
        "Esta seguro de que desea enviar el archivo (" +
          codigo +
          "), Esta operación es irreversible!."
      )
    ) {
      var usuario = $("#usuario").val();
      var archivo = $("#archivo").val();

      var parametros = {
        codigo,
        usuario,
        archivo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/marcar.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar enviar el archivo!.."
            );
          } else {
            toastr.success("Archivo ennviado con Exitoso!..");
            Add_filtroX();
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  }
}
function showMessage(message) {
  $(".messages").html("").show();
  $(".messages").html(message);
}

function subirImagenS3marcaje(codigo) {
  //informacin del formulario
  var form = document.getElementsByName("some_form")[0];
  var marcados = form["marcado"];
  var hasMarkedActivities = false;
  var missingFiles = [];
  var filesToUpload = [];

  if (marcados.length > 0) {
    for (i = 0; i < marcados.length; i++) {
      if (marcados[i].checked) {
        hasMarkedActivities = true;
        var fileInput =
          marcados[i].parentElement.nextElementSibling.querySelector(
            'input[type="file"]'
          );
        if (!fileInput.files[0]) {
          missingFiles.push(marcados[i].id);
        } else {
          filesToUpload.push({
            codigo: marcados[i].id,
            file: fileInput.files[0],
            isMainFile: false,
          });
        }
      }
    }
  } else if (marcados && marcados.checked) {
    hasMarkedActivities = true;
    var fileInput =
      marcados.parentElement.nextElementSibling.querySelector(
        'input[type="file"]'
      );
    if (!fileInput.files[0]) {
      missingFiles.push(marcados.id);
    } else {
      filesToUpload.push({
        codigo: marcados.id,
        file: fileInput.files[0],
        isMainFile: false,
      });
    }
  }

  if (!hasMarkedActivities) {
    toastr.error("Debe marcar al menos una actividad obligatoria");
    return;
  }

  if (missingFiles.length > 0) {
    toastr.error(
      "Debe cargar archivos para todas las actividades marcadas: " +
        missingFiles.join(", ")
    );
    return;
  }

  // Add main file if exists - this is required for marking
  var mainFileInput = document.getElementById("imagen");
  if (!mainFileInput.files[0]) {
    toastr.error(
      "Debe cargar el archivo principal antes de marcar actividades"
    );
    return;
  }

  filesToUpload.unshift({
    codigo: $("#cod_det2").val(),
    file: mainFileInput.files[0],
    isMainFile: true,
  });

  if (
    confirm(
      "¿Esta seguro de continuar con el registro del marcaje?. Esta operación es irreversible!?"
    )
  ) {
    // Show loading overlay
    showLoadingOverlay("Iniciando carga de archivos...");

    // Start uploading files one by one
    uploadFilesSequentially(filesToUpload, 0);
  }
}

function uploadFilesSequentially(files, index) {
  if (index >= files.length) {
    // All files uploaded, now mark activities
    markActivitiesBatch();
    return;
  }

  var fileData = files[index];
  var formData = new FormData();
  formData.append("images", fileData.file);
  formData.append("codigo", fileData.codigo);

  var usuario = $("#usuario").val();
  var ficha = $("#stdID").val();
  var cliente = $("#cliente").val();
  var ubicacion = $("#ubicacion").val();
  var proyecto = $("#cod_proyecto").val();

  formData.append("usuario", usuario);
  formData.append("ficha", ficha);
  formData.append("cliente", cliente);
  formData.append("ubicacion", ubicacion);
  formData.append("proyecto", proyecto);

  $.ajax({
    url: "http://194.163.161.64:9090/docs/upload_marcaje/",
    type: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      updateLoadingMessage(
        "Subiendo archivo " + (index + 1) + " de " + files.length + "..."
      );
    },
    success: function (data) {
      console.log("Upload response for file " + (index + 1) + ":", data);
      // Store the uploaded file info
      fileData.uploadedUrl = data.data.image[0];
      console.log(
        "Stored URL for " + fileData.codigo + ":",
        fileData.uploadedUrl
      );
      // Store URL on the actual file input element for later reference
      if (fileData.isMainFile) {
        document.getElementById("imagen").uploadedUrl = data.data.image[0];
        console.log(
          "Main file URL stored:",
          document.getElementById("imagen").uploadedUrl
        );
      } else {
        // Find the corresponding file input and store the URL
        var fileInputs = document.querySelectorAll(
          'input[type="file"][name^="archivo["]'
        );
        for (var j = 0; j < fileInputs.length; j++) {
          var inputName = fileInputs[j].getAttribute("name");
          var codigoMatch = inputName.match(/archivo\[(\d+)\]/);
          if (codigoMatch && codigoMatch[1] == fileData.codigo) {
            fileInputs[j].uploadedUrl = data.data.image[0];
            console.log(
              "Activity file URL stored for " + fileData.codigo + ":",
              fileInputs[j].uploadedUrl
            );
            break;
          }
        }
      }
      // Continue with next file
      uploadFilesSequentially(files, index + 1);
    },
    error: function () {
      showMessage(
        "<span class='error'>Error al subir archivo " + (index + 1) + "</span>"
      );
    },
  });
}

function markActivitiesBatch() {
  var form = document.getElementsByName("some_form")[0];
  var marcados = form["marcado"];
  var usuario = $("#usuario").val();
  var cod_ficha = $("#stdID").val();
  var cod_cliente = $("#cliente").val();
  var cod_ubicacion = $("#ubicacion").val();
  var proyecto = $("#cod_proyecto").val();

  var lista = [];
  var links = {};

  console.log("Starting markActivitiesBatch");
  console.log("Form marcados:", marcados);

  // Get main file URL if exists
  var mainFileUrl = "";
  var mainFileInput = document.getElementById("imagen");
  if (mainFileInput && mainFileInput.uploadedUrl) {
    mainFileUrl = mainFileInput.uploadedUrl;
    console.log("Main file URL:", mainFileUrl);
  }

  // Collect marked activities and their links
  if (marcados.length > 0) {
    for (i = 0; i < marcados.length; i++) {
      if (marcados[i].checked) {
        lista.push(marcados[i].id);
        console.log("Processing checked activity:", marcados[i].id);
        // Find the corresponding uploaded file
        var fileInput =
          marcados[i].parentElement.nextElementSibling.querySelector(
            'input[type="file"]'
          );
        console.log("File input found:", fileInput);
        console.log(
          "File input uploadedUrl:",
          fileInput ? fileInput.uploadedUrl : "undefined"
        );
        if (fileInput && fileInput.uploadedUrl) {
          // Each activity gets its own file URL
          links[marcados[i].id] = fileInput.uploadedUrl;
          console.log(
            "Using activity-specific URL for",
            marcados[i].id,
            ":",
            fileInput.uploadedUrl
          );
        } else {
          // If no specific file, use main file URL for mandatory activities
          links[marcados[i].id] = mainFileUrl;
          console.log(
            "Using main file URL for",
            marcados[i].id,
            ":",
            mainFileUrl
          );
        }
      }
    }
  } else if (marcados && marcados.checked) {
    lista.push(marcados.id);
    console.log("Processing single checked activity:", marcados.id);
    var fileInput =
      marcados.parentElement.nextElementSibling.querySelector(
        'input[type="file"]'
      );
    console.log("File input found:", fileInput);
    console.log(
      "File input uploadedUrl:",
      fileInput ? fileInput.uploadedUrl : "undefined"
    );
    if (fileInput && fileInput.uploadedUrl) {
      links[marcados.id] = fileInput.uploadedUrl;
      console.log(
        "Using activity-specific URL for",
        marcados.id,
        ":",
        fileInput.uploadedUrl
      );
    } else {
      links[marcados.id] = mainFileUrl;
      console.log("Using main file URL for", marcados.id, ":", mainFileUrl);
    }
  }

  console.log("Final lista:", lista);
  console.log("Final links:", links);

  let vectorJSON = JSON.stringify(lista);

  // Add main file URL to links if it exists
  if (mainFileUrl) {
    links[$("#cod_det2").val()] = mainFileUrl;
  }

  var parametros = {
    links: JSON.stringify(links),
    codigo: $("#cod_det2").val(),
    doc: $("#cod_det2").val(),
    usuario: usuario,
    vector: vectorJSON,
    cod_ficha: cod_ficha,
    cod_cliente: cod_cliente,
    cod_ubicacion: cod_ubicacion,
    cod_proyecto: proyecto,
  };

  console.log("Sending parameters to marcar.php:", parametros);

  $.ajax({
    url: "packages/planif/planif_marcaje/modelo/marcar.php",
    type: "POST",
    data: parametros,
    beforeSend: function () {
      updateLoadingMessage("Guardando marcaje...");
    },
    success: function (data) {
      console.log("Response from marcar.php:", data);
      hideLoadingOverlay();
      showMessage(
        "<span class='success'>Los archivos han sido guardados con exito...</span>"
      );
      $("#imagen").val("");
      // Show main file viewer if main file was uploaded
      var mainFileInput = document.getElementById("imagen");
      if (mainFileInput && mainFileInput.uploadedUrl) {
        $("#mainFileViewer").show();
      }
      Add_filtroX();
      cerrarModalfile();
    },
    error: function () {
      hideLoadingOverlay();
      showMessage(
        "<span class='error'>Ha ocurrido un error al guardar el marcaje.</span>"
      );
    },
  });
}

function changeCliente(cliente) {
  Add_Cl_Ubic(cliente, "contenido_ubic", "T", "120");
  Add_filtroX();
}

function addParticipante(metodo, codigo = "", ficha_delete = "") {
  var cod_ficha = $("#stdIDP").val();
  var cod_det = $("#cod_det").val();
  if (metodo == "agregar") {
    if (
      confirm(
        "Esta seguro de que desea agregar a este trabajador como participante!."
      )
    ) {
      var usuario = $("#usuario").val();
      var parametros = {
        cod_det,
        cod_ficha,
        usuario,
        metodo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/participante.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar agregar al participante!.."
            );
          } else {
            toastr.success("Participante agregado con exito!..");
            cargar_participantes(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  } else if (metodo == "eliminar") {
    if (
      confirm(
        "Esta seguro de que desea eliminar el participante (" +
          ficha_delete +
          ")!."
      )
    ) {
      var usuario = $("#usuario").val();
      var parametros = {
        codigo,
        metodo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/participante.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar eliminar el participante!.."
            );
          } else {
            toastr.success("Participante eliminado con exito!..");
            cargar_participantes(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  }
}
function addParticipanteNO(metodo, codigo = "", ficha_delete = "") {
  var cod_ficha = $("#stdIDP1").val();
  var cod_det = $("#cod_det").val();
  if (metodo == "agregar") {
    if (
      confirm(
        "Esta seguro de que desea agregar a este trabajador como participante!."
      )
    ) {
      var usuario = $("#usuario").val();
      var parametros = {
        cod_det,
        cod_ficha,
        usuario,
        metodo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/participante.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar agregar al participante!.."
            );
          } else {
            toastr.success("Participante agregado con exito!..");
            cargar_participantesNO(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  } else if (metodo == "eliminar") {
    if (
      confirm(
        "Esta seguro de que desea eliminar el participante (" +
          ficha_delete +
          ")!."
      )
    ) {
      var usuario = $("#usuario").val();
      var parametros = {
        codigo,
        metodo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/participante.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar eliminar el participante!.."
            );
          } else {
            toastr.success("Participante eliminado con exito!..");
            cargar_participantesNO(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  }
}

function addObservacion(codigo = "") {
  var observacion = $("#observacion").val();
  if (observacion == "") {
    toastr.success("La observación no puede estar vacía!..");
  } else {
    var cod_det = $("#cod_det").val();
    if (confirm("Esta seguro de que desea agregar esta observación")) {
      var usuario = $("#usuario").val();
      var parametros = {
        cod_det,
        observacion,
        usuario,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/observacion.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar agregar la observación!.."
            );
          } else {
            toastr.success("Observación agregada con exito!..");
            cargar_observaciones(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  }
}
function addObservacionNO(codigo = "") {
  var observacion = $("#observacionNO").val();
  if (observacion == "") {
    toastr.success("La observación no puede estar vacía!..");
  } else {
    var cod_det = $("#cod_det").val();
    if (confirm("Esta seguro de que desea agregar esta observación")) {
      var usuario = $("#usuario").val();
      var parametros = {
        cod_det,
        observacion,
        usuario,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/observacion.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error(
              "A ocurrido un error al intentar agregar la observación!.."
            );
          } else {
            toastr.success("Observación agregada con exito!..");
            cargar_observacionesNO(cod_det);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
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
function openModalObservacionesdos(
  codigo,
  xficha,
  xcliente,
  xubicacion,
  xproyecto,
  realizado
) {
  $("#cod_det2").val(codigo);
  $("#cod_proyecto").val(xproyecto);
  $("#vector").val(xcliente);
  $("#myModalO2").show();
  if (realizado == true) {
    $("#table_file_soporte").hide();
    $("#table_boton_subir").hide();
  } else {
    $("#table_file_soporte").show();
    $("#table_boton_subir").show();
  }
  cargar_actividades(xficha, xcliente, xubicacion, xproyecto, realizado);
}

function cerrarModalObservaciones() {
  $("#myModalO").hide();
}
function cerrarModalObservacionesNO() {
  $("#myModalO1").hide();
}
function cerrarModalfile() {
  $("#myModalO2").hide();
  $("#imagen").val("");
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
    codigo,
  };
  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/Add_participantes.php",
    type: "post",
    beforeSend: function () {
      $("#participantesNO").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
      );
    },
    success: function (response) {
      $("#participantesNO").html(response);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}
function cargar_participantes(codigo) {
  var parametros = {
    codigo,
  };
  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/Add_participantes.php",
    type: "post",
    beforeSend: function () {
      $("#participantes").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
      );
    },
    success: function (response) {
      $("#participantes").html(response);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}
function cargar_observaciones(codigo) {
  var parametros = {
    codigo,
  };
  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/Add_observaciones.php",
    type: "post",
    beforeSend: function () {
      $("#observaciones").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
      );
    },
    success: function (response) {
      $("#observaciones").html(response);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}
function cargar_observacionesNO(codigo) {
  var parametros = {
    codigo,
  };
  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/Add_observaciones.php",
    type: "post",
    beforeSend: function () {
      $("#observacionesNO").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
      );
    },
    success: function (response) {
      $("#observacionesNO").html(response);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}

function cargar_actividades(ficha, cliente, ubicacion, proyecto, realizado) {
  var parametros = {
    auxficha: ficha,
    auxcliente: cliente,
    auxubicacion: ubicacion,
    auxproyecto: proyecto,
    realizado,
  };

  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/cargar_actividadesNO.php",
    type: "post",
    beforeSend: function () {
      $("#actividadesNO").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">'
      );
    },
    success: function (response) {
      $("#actividadesNO").html(response);
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}
function enableMarking() {
  let marcados = [];
  var form = document.getElementsByName("some_form")[0];
  marcados = form["marcado"];
  let ubi = form["enviar_ubicacion"];

  console.log(marcados);

  if (marcados.length === 1) {
    marcados = [form["marcado"]];
  } else {
    if (marcados.length === undefined) {
      marcados = [form["marcado"]];
    } else {
      marcados = form["marcado"];
    }
  }

  if (marcados.length >= 0) {
    for (i = 0; i < marcados.length; i++) {
      marcados[i].disabled = false;
    }
  }
  ubi.disabled = false;
}

function activarcheckbox() {
  enableMarking();
}
function enableFileInput(checkbox) {
  var fileInput =
    checkbox.parentElement.nextElementSibling.querySelector(
      'input[type="file"]'
    );
  if (checkbox.checked) {
    fileInput.disabled = false;
    fileInput.required = true;
  } else {
    fileInput.disabled = true;
    fileInput.required = false;
    fileInput.value = "";
  }
}

function subirImagenActividad(codigo) {
  var fileInput = document.querySelector(
    'input[name="archivo[' + codigo + ']"]'
  );
  if (!fileInput.files[0]) {
    toastr.error("Debe seleccionar un archivo para esta actividad");
    return;
  }

  var formData = new FormData();
  formData.append("images", fileInput.files[0]);
  formData.append("codigo", codigo);

  var usuario = $("#usuario").val();
  var ficha = $("#stdID").val();
  var cliente = $("#cliente").val();
  var ubicacion = $("#ubicacion").val();
  var proyecto = $("#cod_proyecto").val();

  formData.append("usuario", usuario);
  formData.append("ficha", ficha);
  formData.append("cliente", cliente);
  formData.append("ubicacion", ubicacion);
  formData.append("proyecto", proyecto);

  $.ajax({
    url: "http://194.163.161.64:9090/docs/upload_marcaje/",
    type: "POST",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      toastr.info("Subiendo archivo...");
    },
    success: function (data) {
      // Marcar la actividad como realizada
      marcarActividad(codigo, data.data.image[0], usuario);
    },
    error: function () {
      toastr.error("Error al subir el archivo");
    },
  });
}

function marcarActividad(codigo, link, usuario) {
  var parametros = {
    codigo: codigo,
    usuario: usuario,
    link: link,
  };

  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/modelo/marcar.php",
    type: "post",
    success: function (response) {
      var resp = JSON.parse(response);
      if (resp.error) {
        toastr.error("Error al marcar la actividad");
      } else {
        toastr.success("Actividad marcada exitosamente");
        // Recargar actividades
        Add_filtroX();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}

function showLoadingOverlay(message) {
  $("#loadingMessage").text(message);
  $("#loadingProgress").text("");
  $("#loadingOverlay").show();
}

function updateLoadingMessage(message) {
  $("#loadingMessage").text(message);
}

function updateLoadingProgress(progress) {
  $("#loadingProgress").text(progress);
}

function hideLoadingOverlay() {
  $("#loadingOverlay").hide();
}

function verArchivo(url) {
  if (url) {
    window.open(url, "_blank");
  }
}

function verArchivoPrincipal() {
  var mainFileInput = document.getElementById("imagen");
  if (mainFileInput && mainFileInput.uploadedUrl) {
    window.open(mainFileInput.uploadedUrl, "_blank");
  }
}

function enviaremail(auxcliente, auxubicacion) {
  if (auxubicacion && auxcliente) {
    if (
      confirm(
        "Esta seguro de que desea enviar el Email (" +
          auxubicacion +
          "), Esta operación es irreversible!."
      )
    ) {
      var usuario = $("#usuario").val();
      var archivo = $("#archivo").val();
      // alert(archivo);
      var parametros = {
        ubicacion: auxubicacion,
        usuario,
        link: archivo,
      };
      $.ajax({
        data: parametros,
        url: "packages/planif/planif_marcaje/modelo/enviaremail.php",
        type: "post",
        success: function (response) {
          var resp = JSON.parse(response);
          if (resp.error) {
            toastr.error("A ocurrido un error al intentar enviar el email!..");
          } else {
            toastr.success("email enviado con exito!....");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        },
      });
    }
  }
}
