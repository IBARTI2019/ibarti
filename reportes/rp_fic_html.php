<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
$Nmenu   = 531;
$mod     =  $_GET['mod'];
$titulo  = " REPORTE DOCUMENTO HTML ";
$archivo = "reportes/rp_fic_html_det.php?Nmenu=$Nmenu&mod=$mod";
require_once('autentificacion/aut_verifica_menu.php');
require_once('sql/sql_report.php');
$bd = new DataBase();
?>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
  <hr /><table width="100%" class="etiqueta">
    <tr><td width="10%">Reporte:</td>
     <td width="14%" id="select10"><select name="reporte" id="reporte" style="width:120px;" required>
       <option value="">Seleccione...</option>
       <?php
       $query01 = $bd->consultar("SELECT men_reportes_html.codigo, men_reportes_html.descripcion
         FROM men_reportes_html
         WHERE men_reportes_html.cod_modulo = '$mod'
         AND men_reportes_html.`status` = 'T'
         ORDER BY 2 DESC");
       while($row01=$bd->obtener_fila($query01,0)){
        echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
      }?></select></td>

      <td width="10%">Filtro <?php echo $leng['trab']?>.:</td>
      <td id="select01" width="14%">
       <select id="paciFiltro"  style="width:120px" onchange="EstadoFiltro(this.value)">
        <option value="codigo"><?php echo $leng['ficha']?></option>
        <option value="cedula"><?php echo $leng['ci']?></option>
        <option value="trabajador"><?php echo $leng['trabajador']?></option>
        <option value="nombres"> Nombre </option>
        <option value="apellidos"> Apellido </option>
      </select></td>
      <td width="10%"><?php echo $leng['trabajador']?>:</td>
      <td  width="14%"><input  id="stdName" type="text" size="22"  required />
       <input type="hidden" name="trabajador" id="stdID" value="" required/></td>
       <td>&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
        <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
        <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
        <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
        <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
        <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td>
        <td width="4%" rowspan="2" id="cont_img">
          <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" value="Procesar" class="readon art-button" onclick="Procesar()">
          </span>
          <span class="art-button-wrapper" id="btnPreparar">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
            <input type="button" value="Preparar" class="readon art-button" onclick="Preparar()">
          </span>
        </td>
      </tr>

      <tr>
       <td><?php echo $leng['cliente']?>:</td>
       <td><select name="cliente" id="cliente" style="width:120px;" onchange="{
        Add_Cl_Ubic(this.value, 'contenido_ubic', 'P', '120'); 
        validarCorreoCliente();
      }">
      <?php
      echo $select_cl;
      $query01 = $bd->consultar($sql_cliente);
      while($row01=$bd->obtener_fila($query01,0)){
        echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
      }?></select></td>
      <td><?php echo $leng['ubicacion']?>: </td>
      <td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
       <option value="TODOS">TODOS</option>
     </select></td>
     <td> Puesto: </td>
     <td id="contenido_puesto"><select name="puesto" id="puesto" style="width:120px;">
      <option value="TODOS">TODOS</option>
    </select></td>
  </tr>
  <tr>
    <td colspan="2"><span id="correo_cliente" hidden="hidden">¿Enviar a Correo de <?php echo $leng['cliente']?>?<input id="enviar_cliente" name="enviar_cliente" type="checkbox" style="width:auto"/></span></td>
    <td colspan="2"><span id="correo_ubicacion" hidden="hidden">¿Enviar a Correo de <?php echo $leng['ubicacion']?>?<input id="enviar_ubicacion" name="enviar_ubicacion" type="checkbox" style="width:auto"/></span></td>
  </tr>
</table>
<div id="msgPreparando" style="display: none;">
  <img src='imagenes/loading.gif'/>Preparando el documento, por favor espere...
</div>
<hr /><!--<div id="listar">&nbsp;</div>-->
<div align="center"><br/>
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
    class="readon art-button">
  </span>&nbsp;
  <input type="submit" id="procesar" hidden="hidden">
</div>
</form>
<script type="text/javascript">
	r_cliente = $("#r_cliente").val();
	r_rol     = $("#r_rol").val();
	usuario   = $("#usuario").val();
	filtroValue = $("#paciFiltro").val();

  new Autocomplete("stdName", function() {
    this.setValue = function(id) {
      document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
    }
    if (this.isModified) this.setValue("");
    if (this.value.length < 1) return ;
    return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+"";
  });

  function validarCorreoCliente(){
    if($('#cliente').val() != 'TODOS'){
      $('#correo_cliente').show();
      $('#correo_ubicacion').hide();
    }else{
      $('#enviar_cliente').prop('checked', false);
      $('#correo_cliente').hide();
      $('#correo_ubicacion').hide();
    }
  }

  function validarCorreoUbic(){
    if($('#ubicacion').val() != 'TODOS'){
      $('#correo_ubicacion').show();
    }else{
      $('#enviar_ubicacion').prop('checked', false);
      $('#correo_ubicaicon').hide();
    }
  }

function Add_Ub_puesto(valor, contenido, tamano){  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
  var error = 0;
  var errorMessage = ' ';
  if(valor == '') {
   var error = error+1;
   errorMessage = errorMessage + ' \n Debe Seleccionar Una Ubicacion ';
 }
 if(error == 0){
  ajax=nuevoAjax();
  ajax.open("POST", "ajax/Add_ub_puesto.php", true);
  ajax.onreadystatechange=function(){
    if (ajax.readyState==4){
      validarCorreoUbic();
      document.getElementById(contenido).innerHTML = ajax.responseText;
    }
  }
  ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send("codigo="+valor+"&tamano="+tamano);
}else{
 alert(errorMessage);
}
}

function Procesar(){
  var cod_ficha = $('#stdID').val();
  var cliente = $('#cliente').val();
  var error        = 0;
  var errorMessage = ' ';
  if(error == 0){
    var parametros = { "codigo":  cod_ficha, "cliente":cliente};
    $.ajax({
      data:  parametros,
      url:   'ajax_rp/Add_fic_valid_vetado.php',
      type:  'post',
      success:  function (response) {
        resp = JSON.parse(response);
        if(resp.length > 0){
        var mensaje = ' ';
        resp.forEach((d,i)=>{
          if(i==0){
            mensaje += d.trabajador+' \n esta vetado para '+
            d.cliente+' \n en las siguienetes ubicaciones: \n '+(i+1)+'.- '+d.ubicacion+'\n'; 
          }else{
            mensaje += (i+1)+'.- '+d.ubicacion+'\n';
          }
        });
        mensaje += '\n ¿Desea procesar el Reporte de todas formas?\n ';
        if(confirm(mensaje)){
          $('#procesar').click();
        }
      }else{
        $('#procesar').click();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status);
      alert(thrownError);}
    });
  }else{
    alert(errorMessage);
  }
}

function Preparar(){
  $("#btnPreparar").hide();
  $("#msgPreparando").show();
  var ficha = $('#stdID').val();
  var client = $('#cliente').val();
  var reporte = $('#reporte').val();
  var ubic = $('#ubicacion').val();
  var puest = $('#puesto').val();
  var error        = 0;
  var errorMessage = ' ';
  if(error == 0){
    var parametros = {
      "trabajador": ficha, "cliente":client, 
      "reporte": reporte,  "ubicacion": ubic, 
      "puesto": puest
    };

    $.ajax({
      data:  parametros,
      url:   'ajax_rp/Add_fic_valid_vetado.php',
      type:  'post',
      success:  function (response) {
        resp = JSON.parse(response);
        if(resp.length > 0){
          var mensaje = ' ';
          resp.forEach((d,i)=>{
          if(i==0){
            mensaje += d.trabajador+' \n esta vetado para '+
            d.cliente+' \n en las siguienetes ubicaciones: \n '+(i+1)+'.- '+d.ubicacion+'\n'; 
          }else{
            mensaje += (i+1)+'.- '+d.ubicacion+'\n';
          }
        });
        mensaje += '\n ¿Desea preparar el reporte de todas formas?\n ';
        if(confirm(mensaje)){
          GenerarReporte(parametros);
        }else{
          $("#btnPreparar").show();
          $("#msgPreparando").hide();
        }
      }else{
        GenerarReporte(parametros);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      $("#btnPreparar").show();
      $("#msgPreparando").hide();
      alert(xhr.status);
      alert(thrownError);
    }
    });
  }else{
    $("#btnPreparar").show();
    $("#msgPreparando").hide();
    alert(errorMessage);
  }
}

function GenerarReporte(parametros){
  $.ajax({
    data:  parametros,
    url:   'ajax_rp/GenerarReporte.php',
    type:  'post',
    success:  function (response) {
      var blob = new Blob([response], {type: 'application/pdf'});
      subirDocumentToS3(blob, `${parametros["trabajador"]}-${parametros["reporte"]}`, parametros["trabajador"], parametros["reporte"]);
    }, 
    error: function (xhr, ajaxOptions, thrownError) {
      var error = JSON.parse(xhr.responseText);
      alert(error["msg"]);
      $("#btnPreparar").show();
      $("#msgPreparando").hide();
    }
  });
}

function subirDocumentToS3(documento, name, ficha, reporte) {
  if(documento){
    var folder = 'firmas'
    var config = [
      {
        folder: folder,
        key: name
      }
    ]
    var formData = new FormData();
    formData.append("images", documento);
    formData.append("config", JSON.stringify(config));

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
          
        },
        //una vez finalizado correctamente
        success: function (data) {
          if(data.error == true){
            alert('Ha ocurrido un error.');
            $("#btnPreparar").show();
            $("#msgPreparando").hide();
          }else{
            actualizarReporte(data.data.image[0], ficha, reporte);
          }
        },
        //si ha ocurrido un error
        error: function () {
          alert('Ha ocurrido un error.');
          $("#btnPreparar").show();
          $("#msgPreparando").hide();
        }
    });
  }else{
    alert('Debe seleccionar el archivo de soporte')
  }
}

function actualizarReporte(url, ficha, reporte) {
    var usuario = $("#usuario").val();
    var parametros = {
      "link": url,
      "ficha": ficha,
      "reporte": reporte,
      "usuario": usuario
    };

    $.ajax({
        url: 'upload/firmas.php',
        type: 'POST',
        data: parametros,
        beforeSend: function () {},
        success: function (data) {
          $("#btnPreparar").show();
          $("#msgPreparando").hide();
          alert('La imagen ha subido correctamente. Actualizando.');
        },
        //si ha ocurrido un error
        error: function () {
          $("#btnPreparar").show();
          $("#msgPreparando").hide();
          alert('Ha ocurrido un error.');  
        }
    });
  }
</script>
