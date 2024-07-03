	<table width="80%" align="center">
   <tr valign="top">                    
     <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL DE CONFIRMACIONES</td>
   </tr>
   <tr><td height="8" colspan="2" align="center"><hr></td></tr>			 
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_confirm" value="<?php echo $min_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_confirm" value="<?php echo $max_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_in_transport" value="<?php echo $min_in_transport;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_in_transport" value="<?php echo $max_in_transport;?>">
    </td>
   </tr>
   <tr><td height="4" colspan="2" align="center"><hr></td></tr>			  
   <tr>      
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
  </span>&nbsp;
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />	
  </span>           
</div> 
