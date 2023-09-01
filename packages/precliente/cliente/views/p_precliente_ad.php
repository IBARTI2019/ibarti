<script type="text/javascript">
  $("#add_precliente_ad_form").on('submit', function(evt){
    evt.preventDefault();
    save_precliente();
  });
  $("#add_precliente_ad_form input, select").change(function (evt) { 
    evt.preventDefault();  
    $("#c_cambios").val('true');
    $('#salvar_precliente_ad').attr('disabled',false);
  }); 
</script>
<form action="" method="post" name="add_precliente_ad" id="add_precliente_ad_form">
  <fieldset class="fieldset">
    <legend>Datos Adiccionales <?php echo $leng['precliente'];?> </legend>
    <table width="100%" align="center">
     <tr>
      <td width="18%" class="etiqueta">Limite de Credito: </td>
      <td width="32%"><input type="text" id="c_limite_cred" maxlength="60" size="20" value="<?php echo $cl['limite_cred'];?>"/></td>
      <td width="18%" class="etiqueta">Dias Plazo Pago: </td>
      <td width="32%"><input type="text" id="c_plazo_pago" maxlength="60" size="20" value="<?php echo $cl['plazo_pago'];?>"/></td>
    </tr>
    <tr>
      <td class="etiqueta">Descuento Pronto Pago: </td>
      <td><input type="text" id="c_desc_p_pago" maxlength="60" size="20" value="<?php echo $cl['desc_p_pago'];?>"/></td>
      <td class="etiqueta">Descuento Global: </td>
      <td><input type="text" id="c_desc_global" maxlength="60" size="20" value="<?php echo $cl['desc_global'];?>"/></td>
    </tr>
    <tr>
      <td class="etiqueta">Direcci&oacute;n de Entrega:</td>
      <td id="textarea03"><textarea  id="c_dir_entrega" cols="35" rows="4"><?php echo $cl['dir_entrega'];?></textarea>
        <span id="Counterror_mess3" class="texto">&nbsp;</span>
        <td class="etiqueta">Dias de Visitas: </td>
        <td id="input05">
          <table width="100%" border="0">
            <tr>
              <td width="50%" >Lunes:<input type="checkbox" id="c_lunes"  <?php echo statusCheck($cl['lunes']);?> value="T" /></td>
              <td width="50%">Martes:<input type="checkbox" id="c_martes"  <?php echo statusCheck($cl['martes']);?> value="T" /></td>
            </tr>
            <tr>
              <td>Miercoles: <input id="c_miercoles" type="checkbox"  <?php echo statusCheck($cl['miercoles']);?> value="T" /></td>
              <td>jueves: <input id="c_jueves" type="checkbox"  <?php echo statusCheck($cl['jueves']);?> value="T" /></td>
            </tr>
            <tr>
              <td>Viernes: <input id="c_viernes" type="checkbox"  <?php echo statusCheck($cl['viernes']);?> value="T" /></td>
              <td>Sabado: <input id="c_sabado" type="checkbox"  <?php echo statusCheck($cl['sabado']);?> value="T" /></td>
            </tr>
            <tr>
              <td colspan="2">Domingo: <input id="c_domingo" type="checkbox"  <?php echo statusCheck($cl['domingo']);?> value="T" /></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td class="etiqueta">Campo adiccional 01: </td>
        <td><input type="text" id="c_campo01" maxlength="60" size="35" value="<?php echo $cl['campo01'];?>"/></td>
        <td class="etiqueta">Campo adiccional 02: </td>
        <td><input type="text" id="c_campo02" maxlength="60" size="35" value="<?php echo $cl['campo02'];?>"/></td>
      </tr>
      <tr>
        <td class="etiqueta">Campo adiccional 03: </td>
        <td><input type="text" id="c_campo03" maxlength="60" size="35" value="<?php echo $cl['campo03'];?>"/></td>
        <td class="etiqueta">Campo adiccional 04: </td>
        <td><input type="text" id="c_campo04" maxlength="60" size="35" value="<?php echo $cl['campo04'];?>"/></td>
      </tr>
      <tr>
       <td height="8" colspan="4" align="center"><hr></td>
     </tr>
   </table>
   <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="submit" name="salvar"  id="salvar_precliente_ad" value="Guardar" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="reset" id="limpiar_precliente" value="Restablecer" class="readon art-button" />
    </span>
  </div>
</fieldset>
</form>
