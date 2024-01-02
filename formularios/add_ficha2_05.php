<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
function validarfechaegreso(fecha){

let fechaactual = new Date(); 
var anio = fechaactual.getFullYear();

var dia = fechaactual.getDate();
var _mes = fechaactual.getMonth(); //viene con valores de 0 al 11
_mes = _mes + 1; //ahora lo tienes de 1 al 12
if (_mes < 10) //ahora le agregas un 0 para el formato date
{
    var mes = "0" + _mes;
} else {
    var mes = _mes.toString;
}

var fecha_minimo = dia + '-' + mes + '-' + anio; // Nueva variable
document.getElementById("fec_egreso").setAttribute('max', fecha_minimo);  
fec_egreso.max = new Date().toISOString().split("T")[0]; 

}
   


</script>

<?php
// require_once('autentificacion/aut_verifica_menu.php');
$archivo  = "$area&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=4&metodo=modificar";
$proced   = "p_fichas_05";
$sql = "SELECT IFNULL(max(asistencia_apertura.fec_diaria),'') fecha from v_asistencia,asistencia_apertura
where asistencia_apertura.codigo = v_asistencia.cod_as_apertura
and v_asistencia.cod_ficha = '$codigo'";

$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);
$fecha_min = '';
$fecha_min = conversion($result['fecha']);

$sql = " SELECT ficha_egreso.cod_ficha, ficha_egreso.fec_egreso,
ficha_egreso.motivo, ficha_egreso.cod_color,
ficha_egreso.preaviso, 
ficha_egreso.fec_inicio, ficha_egreso.fec_culminacion,
ficha_egreso.dia_p_legal, ficha_egreso.dia_p_cumplido,
ficha_egreso.calculo, ficha_egreso.calculo_status,
ficha_egreso.fec_calculo, ficha_egreso.fec_posible_pago,
ficha_egreso.fec_pago, ficha_egreso.cheque,
ficha_egreso.banco, ficha_egreso.importe,
ficha_egreso.entrega_uniforme, ficha_egreso.observacion,
ficha_egreso.observacion2, 
IFNULL(ficha_egreso.cod_ficha_egreso_motivo, NULL) cod_motivo_egreso, IFNULL(ficha_egreso_motivo.descripcion, 'Sin definir') motivo_egreso, 
ficha_egreso.fec_us_ing 
FROM ficha_egreso LEFT JOIN ficha_egreso_motivo ON ficha_egreso.cod_ficha_egreso_motivo = ficha_egreso_motivo.codigo
WHERE ficha_egreso.cod_ficha = '$codigo' ";

$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);
if (count($result) == 0) {

  $metodo = "agregar";
  $fec_egreso     = '';
  $fec_sistema_ing = '';
  $motivo         = '';
  $cod_color        = '';
  $preaviso       = '';
  $p_fec_inicio     = '';
  $p_fec_culminacion = '';
  $d_p_laboral    = '';
  $d_p_cumplido   = '';
  $calculo        = '';
  $calculo_status = '';
  $fec_calculo    = '';
  $fec_posible_pago = '';
  $fec_pago       = '';
  $cheque         = '';
  $banco          = '';
  $importe        = '';
  $entrega_uniforme = '';
  $observacion    = '';
  $observacion2   = '';
  $cod_motivo_egreso    = '';
  $motivo_egreso   = 'Seleccione';
} else {

  $metodo = "modificar";
  if ($result['fec_egreso'] == NULL) {
    $fec_egreso = "";
  } else {
    $fec_egreso       = conversion($result['fec_egreso']);
  }

  $fec_sistema_ing  = conversion($result['fec_us_ing']);
  $motivo           = $result['motivo'];
  $cod_color        = $result['cod_color'];
  $preaviso         = $result['preaviso'];
  $p_fec_inicio     = conversion($result['fec_inicio']);
  $p_fec_culminacion = conversion($result['fec_culminacion']);
  $d_p_laboral      = $result['dia_p_legal'];
  $d_p_cumplido     = $result['dia_p_cumplido'];
  $calculo          = $result['calculo'];
  $calculo_status   =  $result['calculo_status'];
  $fec_calculo      = conversion($result['fec_calculo']);
  $fec_posible_pago = conversion($result['fec_posible_pago']);
  $fec_pago       = conversion($result['fec_pago']);
  $cheque         = $result['cheque'];
  $banco          = $result['banco'];
  $importe        = $result['importe'];
  $entrega_uniforme = $result['entrega_uniforme'];
  $observacion    = $result['observacion'];
  $observacion2   = $result['observacion2'];
  $cod_motivo_egreso   =  $result['cod_motivo_egreso'];
  $motivo_egreso   =  $result['motivo_egreso'];
}
?>
<form action="scripts/sc_ficha_05.php" method="post" name="add" id="add">
  <fieldset class="fieldset">
    <legend>Egreso Trabajador </legend>
    <table width="80%" align="center">
      <tr>
        <td class="etiqueta">Fecha de Egreso:</td>
        <td id="fecha01_5">
          <input type="date" name="fec_egreso" size="15" id="fec_egreso" required="required"  onfocus="validarfechaegreso(this.value)" value="<?php echo $fec_egreso; ?> " placeholder="dd-mm-aaaa">
      </tr>
      <tr>
        <td class="etiqueta">Fecha Sistema de Egreso:</td>
        <td>
          <input type="text" name="fec_sistema_egr" size="15" value="<?php echo $fec_sistema_ing; ?>" readonly="readonly" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Motivo de Egreso:</td>
        <td id="radio01_5" class="texto">
          Renuncia <input type="radio" name="motivo" value="R" style="width:auto" <?php echo  CheckX($motivo, 'R'); ?> onclick="handleMotivoClick(this.value);" />
          Despido <input type="radio" name="motivo" value="D" style="width:auto" <?php echo  CheckX($motivo, 'D'); ?> onclick="handleMotivoClick(this.value);" /><br />
          <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
      </tr>
      <tr>
        <td height="20" class="etiqueta">Causa de egreso:</td>
        <td id="select10_6">
          <select name="cod_motivo_egreso" id="causa" style="width:200px;">
            <option value="<?php echo $cod_motivo_egreso; ?>"><?php echo $motivo_egreso; ?></option>
            <?php $sql = "  SELECT codigo, descripcion 
            FROM ficha_egreso_motivo  WHERE status = 'T' AND motivo = '$motivo' AND ficha_egreso_motivo.codigo <> '$cod_motivo_egreso' ORDER BY 2 ASC ";
            $query = $bd->consultar($sql);
            while ($datos = $bd->obtener_fila($query, 0)) {
            ?>
              <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
            <?php } ?>
          </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Calificativo:</td>
        <td id="radio02_5" class="texto">

          <?php $sql = " SELECT color.codigo, color.cod_color 
                FROM color
                WHERE `status` = 'A' ORDER BY 1 ASC  ";
          $query = $bd->consultar($sql);
          while ($datos = $bd->obtener_fila($query, 0)) {
          ?>
            <div class="circulo" style="background:<?php echo $datos[1]; ?>">&nbsp;

              <input type="radio" name="color" value="<?php echo $datos[0]; ?>" <?php echo  CheckX($cod_color, '' . $datos[0] . ''); ?>>
              &nbsp;
            </div>
          <?php } ?><br />
          <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Preaviso Laboral:</td>
        <td id="radio03_5" class="texto">
          Si <input type="radio" name="preaviso" value="S" style="width:auto" <?php echo CheckX($preaviso, 'S'); ?> disabled="disabled" />
          No <input type="radio" name="preaviso" value="N" style="width:auto" <?php echo CheckX($preaviso, 'N'); ?> disabled="disabled" /><br />
          <input type="hidden" name="preaviso" value="<?php echo $preaviso; ?>" />
          <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha de Inicio:</td>
        <td id="fecha02_5">
          <input type="text" name="p_fec_inicio" style="width:150px" value="<?php echo $p_fec_inicio; ?>" readonly="readonly" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha de Culminacion:</td>
        <td id="fecha03_5">
          <input type="text" name="p_fec_culminacion" size="15" value="<?php echo $p_fec_culminacion; ?>" readonly="readonly" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Dias de Preaviso Laboral:</td>
        <td id="input01_5"><input type="text" name="d_p_laboral" maxlength="60" size="15" value="<?php echo $d_p_laboral; ?>" readonly="true" /><br />
          <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
          <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Dias de Preaviso Cumplidos:</td>
        <td id="input02_5"><input type="text" name="d_p_cumplido" maxlength="60" size="15" value="<?php echo $d_p_cumplido; ?>" /><br />
          <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
          <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Calculo:</td>
        <td id="radio04_5" class="texto">
          Si <input type="radio" name="calculo" value="S" style="width:auto" <?php echo CheckX($calculo, 'S'); ?> />
          No <input type="radio" name="calculo" value="N" style="width:auto" <?php echo CheckX($calculo, 'N'); ?> /><br />
          <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Calculo Status:</td>
        <td id="radio05_5" class="texto">
          En Espera <input type="radio" name="calculo_status" value="N" style="width:auto" <?php echo CheckX($calculo_status, 'N'); ?> />
          Aprobado <input type="radio" name="calculo_status" value="A" style="width:auto" <?php echo CheckX($calculo_status, 'A'); ?> /><br />
          <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha de Elaboracion del Calculo:</td>
        <td id="fecha04_5">
          <input type="text" name="fec_calculo" size="15" value="<?php if (isset($fec_calculo)) {
                                                                    echo $fec_calculo;
                                                                  } ?>" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha de Posible Pago:</td>
        <td id="fecha05_5">
          <input type="text" name="fec_posible_pago" size="15" value="<?php if (isset($fec_posible_pago)) {
                                                                        echo $fec_pago;
                                                                      } ?>" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Fecha de Pago:</td>
        <td id="fecha06_5">
          <input type="text" name="fec_pago" size="15" value="<?php if (isset($fec_pago)) {
                                                                echo $fec_pago;
                                                              } ?>" /><br />
          <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
          <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Cheque/Transferencia N&ordm;:</td>
        <td id="input03_5"><input type="text" name="cheque" maxlength="20" size="20" value="<?php echo $cheque; ?>" /> <br />
          <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
          <span class="textfieldMinCharsMsg">Debe Escribir 4 caracteres.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Banco:</td>
        <td id="input04_5"><input type="text" name="banco" size="20" value="<?php echo $banco; ?>" /><br />
          <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
          <span class="textfieldMinCharsMsg">Debe Escribir 4 caracteres.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Importe Neto:</td>
        <td id="input05_5"><input type="text" name="importe" maxlength="14" size="20" value="<?php echo $importe; ?>" /><br />
          <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
          <span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span>
        </td>
      </tr>

      <tr>
        <td class="etiqueta">Entrega de Uniforme:</td>
        <td id="radio06_5" class="texto">
          Si <input type="radio" name="entrega_uniforme" value="S" style="width:auto" <?php echo CheckX($entrega_uniforme, 'S'); ?> disabled="disabled" />
          No <input type="radio" name="entrega_uniforme" value="N" style="width:auto" <?php echo CheckX($entrega_uniforme, 'N'); ?> disabled="disabled" /><br />
          <span class="radioRequiredMsg">Debe seleccionar un Sexo.</span>
          <input type="hidden" name="entrega_uniforme" value="<?php echo $entrega_uniforme; ?>" />
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Observaci&oacute;n:</td>
        <td id="textarea01_5"><textarea name="observacion" cols="50" rows="3" readonly="readonly"><?php echo $observacion; ?></textarea>
          <span id="Counterror_mess1" class="texto">&nbsp;</span><br />
          <span class="textareaRequiredMsg">El Campo es Requerido.</span>
          <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
          <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Comentario:</td>
        <td id="textarea02_5"><textarea name="observacion2" cols="50" rows="3"><?php echo $observacion2; ?></textarea>
          <span id="Counterror_mess1" class="texto">&nbsp;</span><br />
          <span class="textareaRequiredMsg">El Campo es Requerido.</span>
          <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
          <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span>
        </td>
      </tr>
      <tr>
        <td height="20" class="etiqueta">Status:</td>
        <td id="select10_5">
          <select name="status" style="width:200px;">
            <option value="<?php echo $cod_status; ?>"><?php echo $status; ?></option>
            <?php $sql = " SELECT codigo, descripcion FROM ficha_status 
                                         WHERE status = 'T' AND ficha_status.codigo <> '$cod_status' ORDER BY 2 ASC ";
            $query = $bd->consultar($sql);
            while ($datos = $bd->obtener_fila($query, 0)) {
            ?>
              <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
            <?php } ?>
          </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
        </td>
      </tr>
      <tr>
        <td height="8" colspan="2" align="center">
          <hr>
        </td>
      </tr>
    </table>
    <input type="text" hidden="hidden" id="fec_min" value="<?php echo $fecha_min; ?>">
    <div align="center">
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
      </span>&nbsp;
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
      </span>&nbsp;
      <span class="art-button-wrapper">
        <span class="art-button-l"> </span>
        <span class="art-button-r"> </span>
        <input type="button" id="volver05" value="Volver" onClick="history.back(-1);" class="readon art-button" />
      </span>
      <input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
      <input name="pestana" type="hidden" value="egreso" />
      <input name="proced" id="proced" type="hidden" value="<?php echo $proced; ?>" />
      <input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" />
      <input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>" />
    </div>
  </fieldset>
</form>
<script language="javascript" type="text/javascript">
  function funcionSubmit(event) {
    var uno = $('#fec_min').val();
    var dos = $('#fec_egreso').val();
    var uno1 = moment(transformar_fecha(uno));
    var dos1 = moment(transformar_fecha(dos));


    // esta linea detiene la ejecucion del submit
    event.preventDefault();
    if (dos1 > uno1) {
      event.target.submit();
    } else {

      alert('Fecha de Egreso Invalida');
    }
  }

  /*  $('#fec_egreso').datepicker({

    minDate: $('#fec_min').val(),
    dateFormat: "dd-mm-yy",
    // Primer dia de la semana El lunes
    firstDay: 1,
    // Dias Largo en castellano
    dayNames: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
    // Dias cortos en castellano
    dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
    // Nombres largos de los meses en castellano
    monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    // Nombres de los meses en formato corto 
    monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dec"],

  });*/


  function transformar_fecha(valor) {
    var date = valor.split("-")
    var nuevo = `${date[2]}-${date[1]}-${date[0]}`;
    return nuevo
  }
  var input01_5 = new Spry.Widget.ValidationTextField("input01_5", "integer", {
    validateOn: ["blur", "change"],
    isRequired: false
  });
  var input02_5 = new Spry.Widget.ValidationTextField("input02_5", "integer", {
    validateOn: ["blur", "change"],
    isRequired: false
  });
  var input03_5 = new Spry.Widget.ValidationTextField("input03_5", "none", {
    minChars: 4,
    validateOn: ["blur", "change"],
    isRequired: false
  });
  var input04_5 = new Spry.Widget.ValidationTextField("input04_5", "none", {
    minChars: 4,
    validateOn: ["blur", "change"],
    isRequired: false
  });
  var input05_5 = new Spry.Widget.ValidationTextField("input05_5", "currency", {
    format: "comma_dot",
    validateOn: ["blur"],
    useCharacterMasking: true,
    isRequired: false
  });

  //var fecha01_5 = new Spry.Widget.ValidationTextField("fecha01_5", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
  validateOn:["blur", "change"], useCharacterMasking:true});
  var fecha02_5 = new Spry.Widget.ValidationTextField("fecha02_5", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });
  var fecha03_5 = new Spry.Widget.ValidationTextField("fecha03_5", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });
  var fecha04_5 = new Spry.Widget.ValidationTextField("fecha04_5", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });
  var fecha05_5 = new Spry.Widget.ValidationTextField("fecha05_5", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });
  var fecha06_5 = new Spry.Widget.ValidationTextField("fecha06_5", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });

  var radio01_5 = new Spry.Widget.ValidationRadio("radio01_5", {
    validateOn: ["change", "blur"]
  });
  var radio02_5 = new Spry.Widget.ValidationRadio("radio02_5", {
    validateOn: ["change", "blur"]
  });
  var radio03_5 = new Spry.Widget.ValidationRadio("radio03_5", {
    validateOn: ["change", "blur"]
  });
  var radio04_5 = new Spry.Widget.ValidationRadio("radio04_5", {
    validateOn: ["change", "blur"]
  });
  var radio05_5 = new Spry.Widget.ValidationRadio("radio05_5", {
    validateOn: ["change", "blur"]
  });
  var radio06_5 = new Spry.Widget.ValidationRadio("radio05_5", {
    validateOn: ["change", "blur"]
  });

  var textarea01_5 = new Spry.Widget.ValidationTextarea("textarea01_5", {
    maxChars: 255,
    validateOn: ["blur", "change"],
    counterType: "chars_count",
    counterId: "Counterror_mess1",
    useCharacterMasking: false,
    isRequired: false
  });
  var textarea02_5 = new Spry.Widget.ValidationTextarea("textarea01_5", {
    maxChars: 255,
    validateOn: ["blur", "change"],
    counterType: "chars_count",
    counterId: "Counterror_mess1",
    useCharacterMasking: false,
    isRequired: false
  });

  var select10_5 = new Spry.Widget.ValidationSelect("select10_5", {
    validateOn: ["blur", "change"]
  });
  var select10_6 = new Spry.Widget.ValidationSelect("select10_6", {
    validateOn: ["blur", "change"]
  });

  function handleMotivoClick(motivo) {
    var parametros = {
      "motivo": motivo,
    }
    $.ajax({
      data: parametros,
      url: 'ajax/Add_causas.php',
      type: 'post',
      beforeSend: function() {
        $("#causa").html('<img src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px">');
      },
      success: function(response) {
        $("#causa").html(response);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
  }
</script>