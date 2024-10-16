$(function () {
  Cons_precliente("", "agregar");
});

$('#addDoc').submit(function (ev) {
  saveDocuments();
  ev.preventDefault();
});

function Cons_precliente(cod, metodo) {
  var usuario = $("#usuario").val();
  var error = 0;
  var errorMessage = " ";
  if (error == 0) {
    var parametros = {
      codigo: cod,
      metodo: metodo,
      usuario: usuario
    };
    $.ajax({
      data: parametros,
      url: "packages/precliente/cliente/views/Add_form.php",
      type: "post",
      success: function (response) {
        $("#Cont_precliente").html(response);

        if (metodo == "modificar") {
          //Desabilito el campo codigo, para evitar errores de INSERT
          $("#codPDF").val(cod);
          $("#c_codigo").attr("disabled", true);
          //Si el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
          $("#pdfC").show();
          $("#borrar_precliente").show();
          $("#agregar_precliente").show();
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

function save_precliente() {
  var error = 0;
  var errorMessage = " ";
  var proced = "p_preclientes";

  var codigo = $("#c_codigo").val();
  var abrev = $("#c_abrev").val();
  var juridico = Status($("#c_juridico:checked").val());
  var rif = $("#c_rif").val();
  var nit = $("#c_nit").val();
  var contrib = Status($("#c_contrib:checked").val());
  var nombre = $("#c_nombre").val();
  var telefono = $("#c_telefono").val();
  var fax = $("#c_fax").val();
  var cl_tipo = $("#c_cl_tipo").val();
  var region = $("#c_region").val();
  var vendedor = $("#c_vendedor").val();
  var email = $("#c_email").val();
  var website = $("#c_website").val();
  var contacto = $("#c_contacto").val();
  var direccion = $("#c_direccion").val();
  var observ = $("#c_observ").val();

  var limite_cred = $("#c_limite_cred").val();
  var plazo_pago = $("#c_plazo_pago").val();
  var desc_p_pago = $("#c_desc_p_pago").val();
  var desc_global = $("#c_desc_global").val();
  var dir_entrega = $("#c_dir_entrega").val();

  var campo01 = $("#c_campo01").val();
  var campo02 = $("#c_campo02").val();
  var campo03 = $("#c_campo03").val();
  var campo04 = $("#c_campo04").val();

  var latitud = $("#prec_latitud").val();
  var longitud = $("#prec_longitud").val();
  var direccion_google = $("#c_direccion_google").val();

  var lunes = Status($("#c_lunes:checked").val());
  var martes = Status($("#c_martes:checked").val());
  var miercoles = Status($("#c_miercoles:checked").val());
  var jueves = Status($("#c_jueves:checked").val());
  var viernes = Status($("#c_viernes:checked").val());
  var sabado = Status($("#c_sabado:checked").val());
  var domingo = Status($("#c_domingo:checked").val());

  var activo = Status($("#c_activo:checked").val());
  var usuario = $("#usuario").val();
  var metodo = $("#c_metodo").val();

  var responsable = $("#c_responsable").val();
  var empresa_actual = $("#c_empresa_actual").val();
  var cantidad_hombres = $("#c_cantidad_hombres").val();
  var problema_identificado = $("#c_problema_identificado").val();

  if (error == 0) {
    var parametros = {
      codigo: codigo,
      activo: activo,
      rif: rif,
      nit: nit,
      juridico: juridico,
      contrib: contrib,
      abrev: abrev,
      nombre: nombre,
      telefono: telefono,
      fax: fax,
      cl_tipo: cl_tipo,
      region: region,
      vendedor: vendedor,
      email: email,
      website: website,
      contacto: contacto,
      direccion: direccion,
      observ: observ,
      limite_cred: limite_cred,
      plazo_pago: plazo_pago,
      desc_p_pago: desc_p_pago,
      desc_global: desc_global,
      dir_entrega: dir_entrega,
      lunes: lunes,
      martes: martes,
      miercoles: miercoles,
      jueves: jueves,
      viernes: viernes,
      sabado: sabado,
      domingo: domingo,
      campo01: campo01,
      campo02: campo02,
      campo03: campo03,
      campo04: campo04,
      proced: proced,
      usuario: usuario,
      metodo: metodo,
      latitud: latitud,
      longitud: longitud,
      direccion_google: direccion_google,
      responsable: responsable,
      empresa_actual: empresa_actual,
      cantidad_hombres: cantidad_hombres,
      problema_identificado: problema_identificado
    };

    $.ajax({
      data: parametros,
      url: "packages/precliente/cliente/modelo/cliente.php",
      type: "post",
      success: function (response) {
        var resp = JSON.parse(response);
        if (resp.error) {
          alert(resp.mensaje);
        } else {
          if (metodo == "agregar") {
            alert("Actualización Exitosa!..");
            $(".TabbedPanelsTabGroup>li").show();
            //Cambio el metodo a 'modificar'
            $("#c_metodo").val("modificar");
            //Cambio el titulo de Agregar X a Modificar X
            var string = $("#title_precliente")
              .text()
              .split("Agregar")
              .join("Modificar");
            $("#title_precliente").text(string);
            //Desabilito el campo Código hasta que el formulario sufra cambios
            $("#c_codigo").attr("disabled", true);
            //Ya que el metodo es modificar muestro los botones AGREGAR Y ELIMINAR
            $("#pdfC").show();
            $("#borrar_precliente").show();
            $("#agregar_precliente").show();
          } else if (metodo == "modificar") {
            $("#codPDF").val(codigo);
            alert("Actualización Exitosa!..");
          }
          //Cambio la propiedad type del boton de RESTABLECER de reset a button
          //para Evitar errores de historial y a su evento click le una funcion que cargue
          //los datos de el registro que contenga el codigo actual y asi restablecer los valores
          $("#limpiar_precliente").prop("type", "button");
          $("#limpiar_precliente").click(function () {
            Cons_precliente(codigo, "modificar");
          });

          Cons_precliente(codigo, "modificar");
          //Paso a false el input que almacena el valor que indica si el formulario a sufrido cambios
          //$("#c_cambios").val('false');
          //Desabilito el boton guardar hasta que el formulario sufra cambios
          //$('#salvar_precliente').attr('disabled',true);
          //Actualizo la tabla de busqueda para que se muestre con la modificacion,
          //con el parametro en true para no cambiar de vista.
          buscar_precliente(true);
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

function Borrar_precliente() {
  if (confirm("Esta seguro que desea BORRAR este Registro?..")) {
    var usuario = $("#usuario").val();
    var cod = $("#c_codigo").val();
    var parametros = {
      codigo: cod,
      tabla: "preclientes",
      usuario: usuario
    };
    $.ajax({
      data: parametros,
      url: "packages/general/controllers/sc_borrar.php",
      type: "post",
      success: function (response) {
        var resp = JSON.parse(response);
        if (resp.error) {
          alert(resp.mensaje);
        } else {
          //Paso el parametro false para que cambie de vista buscar_precliente(isBuscar)->if(!isBuscar){...
          buscar_precliente(false);
          alert("Registro Eliminado con exito!..");
          //cambio la funcion del evento click del boton Volver para evitar errores de historial
          //volver_precliente -> Cons_precliente('', 'agregar'), para asi no mostrar los datos del registro antes
          //eliminado y mostrar la vista Agregar X
          $("#volver_precliente").click(function () {
            Cons_precliente("", "agregar");
          });
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }
}

//Funcion para ir a la vista Agregar, cuanto se esta en Modificar X
function irAAgregarCliente() {
  var msg = "Desea Agregar un NUEVO REGISTRO?.. ";
  //Obtengo el valor que define si el formulario a sufrido cambios, para definir el mensaje de confirmacion
  var cambiosSinGuardar = $("#c_cambios").val();
  if (cambiosSinGuardar == "true")
    msg =
      "Puede haber cambios sin guardar en el formulario, ¿Desea agregar un NUEVO Registro de todas formas?.";
  if (confirm(msg)) Cons_precliente("", "agregar");
}

////Funcion para ir a la vista Agregar, cuanto se esta en
//Buscar X y previamente no se ha modificado ningún registro
function volverCliente() {
  // Oculto la vista Buscar X y Muestro la vista Agregar X
  $("#add_precliente").show();
  $("#buscar_precliente").hide();
}

function irABuscarCliente() {
  //Limpio la data del campo que recibe el filtro de busqueda
  $("#filtro_preclientes").val("");
  // Oculto la vista Agregar X y Muestro la vista Buscar X
  $("#add_precliente").hide();
  $("#buscar_precliente").show();
}

function buscar_precliente(isBuscar) {
  var data = $("#data_buscar_precliente").val() || "";
  var filtro = $("#filtro_buscar_precliente").val() || "";
  $.ajax({
    data: { data: data, filtro: filtro },
    url: "packages/precliente/cliente/views/Buscar_preclientes.php",
    type: "post",
    beforeSend: function () {
      //Deshabilito el submit del form Buscar X
      $("#buscarCliente").attr("disabled", true);
      //Deshabilito el boton(img) de form Buscar X
      $("#buscarC").attr("disabled", true);
      //Cambio la imagen del boton Buscar X
      $("#buscarC").prop("src", "imagenes/loading3.gif");
    },
    success: function (response) {
      // Oculto la vista Agregar X y Muestro la vista Buscar X
      //solo cuando estoy fuera de la vista Buscar X (lógico)
      if (!isBuscar) {
        $("#add_precliente").hide();
        $("#buscar_precliente").show();
      }
      //Limpio el cuerpo de la tabla con los clientes y pinto el resultado de la busqueda
      $("#lista_preclientes tbody tr").remove();
      $("#lista_preclientes tbody").html(response);
      //habilito el boton(img) de form Buscar X
      $("#buscarCliente").attr("disabled", false);
      //Habilito el submit del form Buscar X
      $("#buscarC").attr("disabled", false);
      //Restablesco la imagen por defecto del boton buscar X
      $("#buscarC").prop("src", "imagenes/buscar.bmp");
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);

      $("#buscarCliente").attr("disabled", false);
      $("#buscarC").attr("disabled", false);
      $("#buscarC").prop("src", "imagenes/buscar.bmp");
    }
  });
}
////////////////////////////////////////////////////////////////////////
var modificar = false;
var pos_modificar;


function agregar_db() {
  var documentos = [];
  var nombres = [];
  var cargos = [];
  var telefonos = [];
  var correos = [];
  var observacion = [];
  var cliente = $("#c_codigo").val();

  var parametros = {
    cliente: cliente,
    codigos: documentos,
    nombres: nombres,
    cargos: cargos,
    telefonos: telefonos,
    correos: correos,
    observacion: observacion,
    motivo: "set_contactos"
  };
  $.ajax({
    data: parametros,
    url: "packages/precliente/cliente/modelo/cliente.php",
    type: "post",
    success: function (response) {
      toastr.success("Actualización Exitosa!..");
    },
    error: function (xhr, ajaxOptions, thrownError) {
      toastr.error(xhr.status);
      toastr.error(thrownError);
    }
  });
}

function Pdf() {
  $("#pdf").submit();
}