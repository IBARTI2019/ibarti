<div id="Contenedor" class="mensaje"></div>
  <fieldset class="fieldset">
  <legend>DATOS BASICOS <?php echo $titulo;?> </legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="input01"><input type="text" name="codigo" id="codigo" maxlength="11" style="width:120px"
                              value="<?php echo $codigo;?>" onblur="<?php echo $codigo_onblur;?>" <?php echo $disabled; ?> required="required"/>
        Activo: <input name="activo" id="activo" type="checkbox"  <?php echo statusCheck("$status");?> value="T"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
	 </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n: </td>
      <td id="input02"><input type="text" name="descripcion" id = "descripcion" maxlength="60" style="width:250px"
                              value="<?php echo $descripcion;?>" required="required"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>	
	 <tr> 
         <td height="8" colspan="2" align="center"><hr>
          <input type="hidden" name="metodo" id="maestro_metodo" value="<?php echo $metodo;?>"></td>
     </tr>	
  </table>
  <div align="center"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />	
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />	
                </span>
   </div>
  
            <input name="tabla" id="tabla" type="hidden"  value="<?php echo $tabla;?>" />            
            <input name="titulo" id="titulo" type="hidden"  value="<?php echo $titulo;?>" />           
	           			
  </fieldset>
<script type="text/javascript">
	var input01  = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
	var input02  = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});
</script>