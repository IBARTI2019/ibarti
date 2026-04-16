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
          '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
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
          "), Esta operación es irreversible!.",
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
              "A ocurrido un error al intentar enviar el archivo!..",
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

function changeCliente(cliente) {
  Add_Cl_Ubic(cliente, "contenido_ubic", "T", "120");
  Add_filtroX();
}

// ============== FUNCIONES DE PARTICIPANTES ==============

function addParticipante(metodo, codigo = "", ficha_delete = "") {
  var cod_ficha = $("#stdIDP").val();
  var cod_det = $("#cod_det").val();
  if (metodo == "agregar") {
    if (
      confirm(
        "Esta seguro de que desea agregar a este trabajador como participante!.",
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
              "A ocurrido un error al intentar agregar al participante!..",
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
          ")!.",
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
              "A ocurrido un error al intentar eliminar el participante!..",
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
        "Esta seguro de que desea agregar a este trabajador como participante!.",
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
              "A ocurrido un error al intentar agregar al participante!..",
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
          ")!.",
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
              "A ocurrido un error al intentar eliminar el participante!..",
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

// ============== FUNCIONES DE OBSERVACIONES ==============

function addObservacion(codigo = "") {
  var observacion = $("#observacion").val();
  if (observacion == "") {
    toastr.warning("La observación no puede estar vacía!..");
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
              "A ocurrido un error al intentar agregar la observación!..",
            );
          } else {
            toastr.success("Observación agregada con exito!..");
            cargar_observaciones(cod_det);
            $("#observacion").val("");
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
    toastr.warning("La observación no puede estar vacía!..");
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
              "A ocurrido un error al intentar agregar la observación!..",
            );
          } else {
            toastr.success("Observación agregada con exito!..");
            cargar_observacionesNO(cod_det);
            $("#observacionNO").val("");
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

// ============== FUNCIONES DE MODALES ==============

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
  cod_planif,
) {
  $("#cod_det2").val(codigo);
  $("#cod_proyecto").val(xproyecto);
  $("#vector").val(xcliente);
  $("#myModalO2").show();
  // Resetear estado al abrir modal
  selectedFiles = {};
  isProcessing = false;
  cargar_actividades(xficha, xcliente, xubicacion, xproyecto, cod_planif);
}

function cerrarModalObservaciones() {
  $("#myModalO").hide();
  $("#observacion").val("");
}

function cerrarModalObservacionesNO() {
  $("#myModalO1").hide();
  $("#observacionNO").val("");
}

function cerrarModalfile() {
  $("#myModalO2").hide();
  $("#imagen").val("");
  // Resetear estado
  selectedFiles = {};
  isProcessing = false;
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

// ============== FUNCIONES DE CARGA DE DATOS ==============

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
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
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
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
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
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
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
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
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

function cargar_actividades(ficha, cliente, ubicacion, proyecto, codigo = "") {
  var parametros = {
    auxficha: ficha,
    auxcliente: cliente,
    auxubicacion: ubicacion,
    auxproyecto: proyecto,
    codigo: codigo,
  };

  $.ajax({
    data: parametros,
    url: "packages/planif/planif_marcaje/views/cargar_actividadesNO.php",
    type: "post",
    beforeSend: function () {
      $("#actividadesNO").html(
        '<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">',
      );
    },
    success: function (response) {
      $("#actividadesNO").html(response);
      // Reiniciar estado después de cargar nuevas actividades
      selectedFiles = {};
      validateSubmitButton();
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);
    },
  });
}

// ============== FUNCIONES DE LOADING OVERLAY ==============

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

// ============== FUNCIONES DE ARCHIVOS ==============

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
          "), Esta operación es irreversible!.",
      )
    ) {
      var usuario = $("#usuario").val();
      var archivo = $("#archivo").val();
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

// ============== FUNCIONES DE MARCAJE (NUEVAS) ==============

let selectedFiles = {};
let isProcessing = false;

function handleFileSelect(fileInput, codigo, esObligatoria) {
  const statusSpan = document.getElementById("status_" + codigo);
  const allowedExtensions = [
    "pdf",
    "jpg",
    "jpeg",
    "png",
    "doc",
    "docx",
    "xls",
    "xlsx",
  ];

  if (fileInput.files.length > 0) {
    const fileName = fileInput.files[0].name;
    const fileExt = fileName.split(".").pop().toLowerCase();

    // Validar extensión
    if (!allowedExtensions.includes(fileExt)) {
      toastr.error("Formato no permitido. Use: PDF, JPG, PNG, DOC, XLS");
      fileInput.value = "";
      return;
    }

    // Validar tamaño (máximo 10MB)
    if (fileInput.files[0].size > 10 * 1024 * 1024) {
      toastr.error("El archivo no debe superar los 10MB");
      fileInput.value = "";
      return;
    }

    selectedFiles[codigo] = fileInput.files[0];
    if (statusSpan) {
      statusSpan.innerHTML = "✅ " + fileName.substring(0, 30);
      statusSpan.style.color = "green";
      statusSpan.style.display = "inline";
    }
    if (esObligatoria) {
      toastr.success("Archivo cargado para actividad obligatoria");
    }
  } else {
    delete selectedFiles[codigo];
    if (statusSpan) {
      if (esObligatoria) {
        statusSpan.innerHTML = "⚠️ Requiere archivo";
        statusSpan.style.color = "red";
        statusSpan.style.display = "inline";
      } else {
        statusSpan.style.display = "none";
      }
    }
  }

  validateSubmitButton();
}

function handleOptionalCheck(checkbox) {
  const codigo = checkbox.value;
  const fileInput = document.getElementById("file_" + codigo);
  const statusSpan = document.getElementById("status_" + codigo);

  if (checkbox.checked) {
    if (fileInput) {
      fileInput.disabled = false;
      if (statusSpan) {
        statusSpan.style.display = "inline";
        statusSpan.innerHTML = "⚠️ Requiere archivo";
        statusSpan.style.color = "#ff6b6b";
      }
    }
  } else {
    if (fileInput) {
      fileInput.disabled = true;
      fileInput.value = "";
      delete selectedFiles[codigo];
      if (statusSpan) {
        statusSpan.style.display = "none";
      }
    }
  }

  validateSubmitButton();
}

function validateSubmitButton() {
  const submitBtn = document.getElementById("subir_img");
  if (!submitBtn) return;

  // Verificar actividades OBLIGATORIAS (siempre deben tener archivo)
  const obligatoryFiles = document.querySelectorAll(".obligatory-file");
  let allObligatoryHaveFiles = true;
  let missingObligatory = [];

  obligatoryFiles.forEach((fileInput) => {
    const match = fileInput.getAttribute("name").match(/archivo\[(\d+)\]/);
    if (match) {
      const codigo = match[1];
      if (!selectedFiles[codigo]) {
        allObligatoryHaveFiles = false;
        missingObligatory.push(codigo);
      }
    }
  });

  if (!allObligatoryHaveFiles) {
    submitBtn.disabled = true;
    submitBtn.style.opacity = "0.5";
    submitBtn.title = "Complete archivos de actividades obligatorias";
    return;
  }

  // Verificar actividades NO OBLIGATORIAS marcadas
  const optionalChecked = document.querySelectorAll(
    ".optional-checkbox:checked",
  );
  let allOptionalHaveFiles = true;
  let missingOptional = [];

  optionalChecked.forEach((checkbox) => {
    const codigo = checkbox.value;
    if (!selectedFiles[codigo]) {
      allOptionalHaveFiles = false;
      missingOptional.push(codigo);
    }
  });

  if (!allOptionalHaveFiles) {
    submitBtn.disabled = true;
    submitBtn.style.opacity = "0.5";
    submitBtn.title = "Complete archivos de actividades marcadas";
    return;
  }

  // Verificar que hay al menos una actividad
  const totalObligatory = obligatoryFiles.length;
  const totalOptional = optionalChecked.length;

  if (totalObligatory === 0 && totalOptional === 0) {
    submitBtn.disabled = true;
    submitBtn.style.opacity = "0.5";
    submitBtn.title = "No hay actividades para marcar";
    return;
  }

  // Todo bien, habilitar botón
  submitBtn.disabled = false;
  submitBtn.style.opacity = "1";
  submitBtn.title = "Subir archivos y marcar actividades";

  const totalFiles = Object.keys(selectedFiles).length;
  if (totalFiles > 0) {
    toastr.success(`✅ ${totalFiles} archivo(s) listo(s) para subir`);
  }
}

// Función principal de marcaje (reemplaza la original)
window.subirImagenS3marcaje = function () {
  if (isProcessing) {
    toastr.warning("Ya hay un proceso en curso, espere");
    return;
  }

  // Obtener todas las actividades marcadas (obligatorias + opcionales marcadas)
  const obligatoryChecked = document.querySelectorAll(".obligatory-checkbox");
  const optionalChecked = document.querySelectorAll(
    ".optional-checkbox:checked",
  );

  let actividadesAMarcar = [];

  // Agregar obligatorias
  obligatoryChecked.forEach((cb) => {
    actividadesAMarcar.push(cb.value);
  });

  // Agregar opcionales marcadas
  optionalChecked.forEach((cb) => {
    actividadesAMarcar.push(cb.value);
  });

  if (actividadesAMarcar.length === 0) {
    toastr.error("No hay actividades para marcar");
    return;
  }

  // Verificar que todas tengan archivo
  let missingFiles = [];
  for (const codigo of actividadesAMarcar) {
    if (!selectedFiles[codigo]) {
      missingFiles.push(codigo);
    }
  }

  if (missingFiles.length > 0) {
    toastr.error(
      "Faltan archivos para las actividades: " + missingFiles.join(", "),
    );
    return;
  }

  // Mostrar resumen al usuario
  const totalObligatory = obligatoryChecked.length;
  const totalOptional = optionalChecked.length;
  const totalFiles = Object.keys(selectedFiles).length;

  const confirmMessage =
    `📋 RESUMEN DEL MARCAJE\n\n` +
    `📌 Actividades OBLIGATORIAS: ${totalObligatory}\n` +
    `📌 Actividades adicionales: ${totalOptional}\n` +
    `📁 Total archivos a subir: ${totalFiles}\n\n` +
    `⚠️ Esta operación es IRREVERSIBLE.\n` +
    `¿Desea continuar?`;

  if (!confirm(confirmMessage)) {
    return;
  }

  isProcessing = true;
  showLoadingOverlay("Preparando archivos...");

  // Preparar archivos para subir
  const filesToUpload = [];
  for (const codigo of actividadesAMarcar) {
    if (selectedFiles[codigo]) {
      filesToUpload.push({
        codigo: codigo,
        file: selectedFiles[codigo],
      });
    }
  }

  uploadFilesWithPromise(filesToUpload, actividadesAMarcar);
};

function uploadFilesWithPromise(filesToUpload, actividadesAMarcar) {
  let uploadedUrls = {};
  let completed = 0;
  let hasError = false;

  if (filesToUpload.length === 0) {
    saveMarking(actividadesAMarcar, {});
    return;
  }

  for (let i = 0; i < filesToUpload.length; i++) {
    const fileData = filesToUpload[i];
    const formData = new FormData();
    formData.append("images", fileData.file);
    formData.append("codigo", fileData.codigo);

    const usuario = $("#usuario").val();
    const ficha = $("#stdID").val();
    const cliente = $("#cliente").val();
    const ubicacion = $("#ubicacion").val();
    const proyecto = $("#cod_proyecto").val();

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
      success: function (data) {
        if (data && data.data && data.data.image && data.data.image[0]) {
          uploadedUrls[fileData.codigo] = data.data.image[0];
          completed++;
          updateLoadingProgress(
            `Subido ${completed} de ${filesToUpload.length}`,
          );

          if (completed === filesToUpload.length && !hasError) {
            saveMarking(actividadesAMarcar, uploadedUrls);
          }
        } else {
          hasError = true;
          hideLoadingOverlay();
          toastr.error("Error en respuesta del servidor de archivos");
          isProcessing = false;
        }
      },
      error: function (xhr, status, error) {
        hasError = true;
        hideLoadingOverlay();
        toastr.error(
          "Error al subir archivo: " + (error || "Error de conexión"),
        );
        isProcessing = false;
      },
    });
  }
}

function saveMarking(actividadesAMarcar, uploadedUrls) {
  updateLoadingMessage("Guardando marcaje en el sistema...");

  const usuario = $("#usuario").val();
  const cod_ficha = $("#stdID").val();
  const cod_cliente = $("#cliente").val();
  const cod_ubicacion = $("#ubicacion").val();
  const proyecto = $("#cod_proyecto").val();

  // Verificar si el checkbox de enviar correo está marcado
  const enviarCorreo = $("#enviar_correo").is(":checked");

  const parametros = {
    usuario: usuario,
    vector: JSON.stringify(actividadesAMarcar),
    links: JSON.stringify(uploadedUrls),
    cod_ficha: cod_ficha,
    cod_cliente: cod_cliente,
    cod_ubicacion: cod_ubicacion,
    cod_proyecto: proyecto,
  };

  $.ajax({
    url: "packages/planif/planif_marcaje/modelo/marcar.php",
    type: "POST",
    data: parametros,
    success: function (response) {
      try {
        const data =
          typeof response === "string" ? JSON.parse(response) : response;

        if (data.error) {
          toastr.error("Error: " + (data.mensaje || "Error al guardar"));
          hideLoadingOverlay();
          isProcessing = false;
          return;
        }

        // Si el marcaje fue exitoso y se debe enviar correo
        if (enviarCorreo && Object.keys(uploadedUrls).length > 0) {
          // Tomar el primer link como archivo principal para el correo
          const primerLink = Object.values(uploadedUrls)[0];

          enviarCorreoMarcaje(
            primerLink,
            cod_ubicacion,
            usuario,
            cod_ficha,
            cod_cliente,
            cod_ubicacion,
          );
        }

        toastr.success("✅ Marcaje completado exitosamente");

        setTimeout(function () {
          cerrarModalfile();
          Add_filtroX();
        }, 1500);
      } catch (e) {
        toastr.error("Error al procesar respuesta del servidor");
      }
      hideLoadingOverlay();
      isProcessing = false;
    },
    error: function (xhr, status, error) {
      hideLoadingOverlay();
      toastr.error(
        "Error al guardar el marcaje: " + (error || "Error de conexión"),
      );
      isProcessing = false;
    },
  });
}

function enviarCorreoMarcaje(
  link,
  ubicacion,
  usuario,
  cod_ficha,
  cod_cliente,
  cod_ubicacion,
) {
  const params = {
    link: link,
    ubicacion: ubicacion,
    usuario: usuario,
    cod_ficha: cod_ficha,
    cod_cliente: cod_cliente,
    cod_ubicacion: cod_ubicacion,
  };

  $.ajax({
    url: "packages/planif/planif_marcaje/modelo/enviaremail.php",
    type: "POST",
    data: params,
    dataType: "json", // Ahora recibirá UNA sola respuesta JSON
    success: function (response) {
      if (response.error) {
        toastr.warning(
          "Marcaje guardado, pero error al enviar correo: " + response.mensaje,
        );
      } else {
        toastr.success("Correo enviado exitosamente");
      }
    },
    error: function () {
      toastr.warning("Marcaje guardado, pero no se pudo enviar el correo");
    },
  });
}
