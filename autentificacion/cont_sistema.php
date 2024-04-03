<?php
require_once('autentificacion/aut_verifica_menu.php');
$titulo = " CONTROL DE SISTEMA ";
$bd = new DataBase();

$href = "../inicio.php?area=autentificacion/Cons_$archivo&Nmenu=$Nmenu&mod=$mod";
$proced = "p_control";
$titulo = "MODIFICAR $titulo";

$sql = " SELECT control.fec_inicio,  control.cod_pais,
paises.descripcion AS pais, control.administrador,
control.oesvica AS cod_cliente, clientes.nombre AS cliente,
control.cl_rif,  control.ficha_preingreso,
control.preingreso_nuevo, control.preingreso_aprobado,
control.preingreso_rechazado, control.ficha_activo AS cod_ficha_activo,
ficha_status.descripcion AS ficha_activo, control.expedientes_dias,
control.vale_concepto, control.vale_monto,
control.cod_nomina, control.ingreso_status_sistema,
control.cod_cestaticket, cest.descripcion AS cestaticket,
control.cod_hora_extras AS cod_hora_extras_d, hora_ex_d.descripcion AS hora_extras_d,
control.cod_hora_extras_n,  hora_ex_n.descripcion AS hora_extras_n,
control.concep_retirado AS cod_c_retirado,
r.descripcion AS c_retirado,
control.concepto_rep AS cod_concepto_rep, conceptos.descripcion AS concepto_rep,
control.dias_proyeccion, control.lim_apertura_plantilla,
control.novedad, control.foros_codigo,
control.cod_rol, roles.descripcion AS rol,
control.nota_unif, control.nota_doc,
control.cod_superv_cargo,
cargos.descripcion AS superv_cargo,
control.cl_campo_04_act, control.cl_campo_04_desc,
control.cod_turno_dl, turno.descripcion AS turno_dl ,
control.control_arma_linea AS cod_ar_linea, prod_lineas.descripcion AS ar_linea,
control.control_uniforme_linea AS cod_uniforme_linea, pl.descripcion AS uniforme_linea,
control.url_doc, control.rop_meses,control.dias_nov_notif,control.min_nov_notif, control.porc_min_aprob_encuesta_preing
FROM control, paises, clientes, ficha_status,
conceptos, conceptos AS r, conceptos AS cest, conceptos AS hora_ex_d, conceptos AS hora_ex_n,
roles, cargos, turno, prod_lineas, prod_lineas pl
WHERE control.cod_pais = paises.codigo
AND control.oesvica = clientes.codigo
AND control.ficha_activo = ficha_status.codigo
AND control.concepto_rep = conceptos.codigo
AND control.concep_retirado = r.codigo
AND control.cod_cestaticket = cest.codigo
AND control.cod_hora_extras = hora_ex_d.codigo
AND control.cod_hora_extras_n = hora_ex_n.codigo
AND control.cod_rol = roles.codigo
AND control.cod_superv_cargo = cargos.codigo
AND control.cod_turno_dl = turno.codigo
AND control.control_arma_linea = prod_lineas.codigo
AND control.control_uniforme_linea = pl.codigo ";

$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);

$fec_inicio    = conversion($result['fec_inicio']);
$cod_pais      = $result['cod_pais'];
$pais          = $result['pais'];
$administrador = $result['administrador'];
$cod_cliente   = $result['cod_cliente'];
$cliente       = $result['cliente'];
$rif           = $result['cl_rif'];
$ficha_preingreso = $result['ficha_preingreso'];
$p_nuevo       = $result['preingreso_nuevo'];
$p_aprobado    = $result['preingreso_aprobado'];
$p_rechazado   = $result['preingreso_rechazado'];
$cod_ficha_activo = $result['cod_ficha_activo'];
$ficha_activo    = $result['ficha_activo'];
$ingreso_status_sistema = $result['ingreso_status_sistema'];
$expedientes_dias  = $result['expedientes_dias'];
$vale_concepto   = $result['vale_concepto'];
$vale_monto      = $result['vale_monto'];
$cod_nomina      = $result['cod_nomina'];
$cod_cestaticket = $result['cod_cestaticket'];
$cestaticket     = $result['cestaticket'];
$cod_hora_extras_d = $result['cod_hora_extras_d'];
$hora_extras_d   = $result['hora_extras_d'];
$cod_hora_extras_n = $result['cod_hora_extras_n'];
$hora_extras_n   = $result['hora_extras_n'];
$cod_c_retirado  = $result['cod_c_retirado'];
$c_retirado      = $result['c_retirado'];
$cod_c_replica   = $result['cod_concepto_rep'];
$c_replica       = $result['concepto_rep'];
$dias_proyeccion = $result['dias_proyeccion'];
$lim_plantilla   = $result['lim_apertura_plantilla'];
$cod_rol         = $result['cod_rol'];
$rol             = $result['rol'];
$nota_unif       = $result['nota_unif'];
$nota_doc        = $result['nota_doc'];
$cod_s_cargo     = $result['cod_superv_cargo'];
$s_cargo         = $result['superv_cargo'];
$cl_campo_04_act = $result['cl_campo_04_act'];
$cl_campo_04_desc = $result['cl_campo_04_desc'];
$cod_turno_dl    = $result['cod_turno_dl'];
$turno_dl        = $result['turno_dl'];
$cod_ar_linea    = $result['cod_ar_linea'];
$ar_linea        = $result['ar_linea'];
$cod_uniforme_linea    = $result['cod_uniforme_linea'];
$uniforme_linea        = $result['uniforme_linea'];
$cod_clasif_sms  = $result['cod_nov_clasif_sms'];
$clasif_sms      = $result['nov_clasif_sms'];
$url_doc         = $result['url_doc'];
$rop_meses       = $result['rop_meses'];
$dias_nov_notif         = $result['dias_nov_notif'];
$min_nov_notif       = $result['min_nov_notif'];
$porc_min_aprob_encuesta_preing       = $result['porc_min_aprob_encuesta_preing'];

?>
<table width="80%" align="center">
  <tr valign="top">
    <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL GENERAL DE SISTEMA</td>
  </tr>
  <tr>
    <td height="8" colspan="2" align="center">
      <hr>
    </td>
  </tr>
  <tr>
    <td class="etiqueta" width="40%">Fecha de Inicio de Sistema: </td>
    <td id="fecha_1_01" width="60%"><input type="text" name="fec_inicio" size="15" value="<?php echo $fec_inicio; ?>" readonly="readonly" />
      <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
      <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta">Pais Principal: </td>
    <td id="select_1_01"><select name="pais" style="width:200px">
        <option value="<?php echo $cod_pais; ?>"><?php echo $pais; ?></option>
        <?php $sql = " SELECT codigo, descripcion FROM paises
		                      WHERE status = 'T' AND paises.codigo <> '$cod_pais' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {
        ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select>
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta">C&oacute;digo del Administrador :</td>
    <td id="input_1_01"><input type="text" name="administrador" maxlength="12" size="15" value="<?php echo $administrador; ?>" />
      <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
      <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta"><?php echo $leng['cliente'] ?> Principal: </td>
    <td id="select_1_02"><select name="cl_principal" style="width:200px">
        <option value="<?php echo $cod_cliente; ?>"><?php echo $cliente; ?></option>
        <?php $sql = " SELECT clientes.codigo, clientes.nombre FROM clientes
		                      WHERE status = 'T' AND clientes.codigo <> '$cod_cliente' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {
        ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select><br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta"><?php echo $leng['rif'] ?> :</td>
    <td id="input_1_02"><input type="text" name="rif" maxlength="20" size="15" value="<?php echo $rif; ?>" />
      <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
      <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta"><?php echo $leng['rol'] ?> Por Defecto: </td>
    <td id="select_1_03"><select name="rol" style="width:200px">
        <option value="<?php echo $cod_rol; ?>"><?php echo $rol; ?></option>
        <?php $sql = " SELECT codigo, descripcion FROM roles
		                      WHERE status = 'T' AND codigo <> '$cod_rol' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {   ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select><br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  </tr>

  <tr>
    <td class="etiqueta">Ingreso Status Por Sistema:</td>
    <td>SI<input type="radio" name="ing_status_sistema" value="S" style="width:auto" <?php echo CheckX($ingreso_status_sistema, "S"); ?> /> NO<input type="radio" name="ing_status_sistema" value="N" style="width:auto" <?php echo CheckX($ingreso_status_sistema, "N"); ?> /></td>
  </tr>
  <tr>
    <td class="etiqueta">Dias Proyeccion De Uniformes:</td>
    <td id="input_1_03"><input type="text" name="d_proyeccion" maxlength="12" size="15" value="<?php echo $dias_proyeccion; ?>" />
      <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
      <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta">Limite de registro de apertura Plantilla:</td>
    <td id="input_1_04"><input type="text" name="lim_plantilla" maxlength="12" size="15" value="<?php echo $lim_plantilla; ?>" />
      <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
      <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span>
    </td>
  </tr>
  <tr>
    <td class="etiqueta">Turno DL: </td>
    <td id="select_1_03"><select name="turno_dl" style="width:200px">
        <option value="<?php echo $cod_turno_dl; ?>"><?php echo $turno_dl; ?></option>
        <?php $sql = " SELECT codigo, descripcion FROM turno
		                      WHERE status = 'T' AND codigo <> '$cod_turno_dl' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {   ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select><br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  <tr>
  <tr>
    <td class="etiqueta">Armamento Linea: </td>
    <td id="select_1_04"><select name="ar_linea" style="width:200px">
        <option value="<?php echo $cod_ar_linea; ?>"><?php echo $ar_linea; ?></option>
        <?php $sql = " SELECT codigo, descripcion FROM prod_lineas
		                      WHERE status = 'T' AND codigo <> '$cod_ar_linea' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {   ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select><br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  <tr>
  <tr>
    <td class="etiqueta">Uniforme Linea: </td>
    <td id="select_1_05"><select name="uniforme_linea" style="width:200px">
        <option value="<?php echo $cod_uniforme_linea; ?>"><?php echo $uniforme_linea; ?></option>
        <?php $sql = " SELECT codigo, descripcion FROM prod_lineas
		                      WHERE status = 'T' AND codigo <> '$cod_uniforme_linea' ORDER BY 2 ASC ";
        $query = $bd->consultar($sql);
        while ($datos = $bd->obtener_fila($query, 0)) {   ?>
          <option value="<?php echo $datos[0]; ?>"><?php echo $datos[1]; ?></option>
        <?php } ?>
      </select><br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  <tr>
  <tr>
    <td class="etiqueta">Porcentaje mínimo de aprobación de encuestas preeliminares: </td>
    <td><input type="number" name="porc_min_aprob_encuesta_preing" id="porc_min_aprob_encuesta_preing" min="1" max="100" value="<?php echo $porc_min_aprob_encuesta_preing; ?>"> %<br />
      <span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span>
    </td>
  <tr>
  <tr>
    <td height="8" colspan="2" align="center">
      <hr>
    </td>
  </tr>
</table>
<div align="center"><span class="art-button-wrapper">
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
    <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
  </span>
  <input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
  <input name="proced" type="hidden" value="<?php echo $proced; ?>" />
  <input name="usuario" type="hidden" value="<?php echo $usuario; ?>" />
  <input name="href" type="hidden" value="<?php echo $href; ?>" />
  <input name="codigo" type="hidden" value="<?php echo $codigo; ?>" />
</div>
</body>

</html>
<script type="text/javascript">
  var fecha_1_01 = new Spry.Widget.ValidationTextField("fecha_1_01", "date", {
    format: "dd-mm-yyyy",
    hint: "DD-MM-AAAA",
    validateOn: ["blur", "change"],
    useCharacterMasking: true,
    isRequired: false
  });

  var input_1_01 = new Spry.Widget.ValidationTextField("input_1_01", "none", {
    validateOn: ["blur", "change"]
  });
  var input_1_02 = new Spry.Widget.ValidationTextField("input_1_02", "none", {
    validateOn: ["blur", "change"]
  });
  var input_1_03 = new Spry.Widget.ValidationTextField("input_1_03", "none", {
    validateOn: ["blur", "change"]
  });
  var input_1_04 = new Spry.Widget.ValidationTextField("input_1_04", "none", {
    validateOn: ["blur", "change"]
  });

  var select_1_01 = new Spry.Widget.ValidationSelect("select_1_01", {
    validateOn: ["blur", "change"]
  });
  var select_1_02 = new Spry.Widget.ValidationSelect("select_1_02", {
    validateOn: ["blur", "change"]
  });
  var select_1_03 = new Spry.Widget.ValidationSelect("select_1_03", {
    validateOn: ["blur", "change"]
  });
  var select_1_04 = new Spry.Widget.ValidationSelect("select_1_04", {
    validateOn: ["blur", "change"]
  });
  var select_1_05 = new Spry.Widget.ValidationSelect("select_1_05", {
    validateOn: ["blur", "change"]
  });
  var select_1_06 = new Spry.Widget.ValidationSelect("select_1_06", {
    validateOn: ["blur", "change"]
  });
</script>