<form action="sc_maestros/Documentos.php" method="post" name="add" id="add"> 
     <table width="70%" align="center">
         <tr valign="top">                    
		     <td height="23" colspan="2" class="etiqueta_title" align="center"> AGREGAR DOCUMENTOS </td>
         </tr>
         <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
         </tr>	 

    <tr>
      <td class="etiqueta" width="25%">C&oacute;digo:</td>
      <td id="input01" width="75%"><input type="text" name="codigo" maxlength="12" style="width:120px"/>	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>
    <tr>
      <td class="etiqueta" width="25%">Descripci&oacute;n:</td>
      <td id="input02" width="75%"><input type="text" name="descripcion" maxlength="120" style="width:250px"/>	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>		
          <tr>
              <td colspan="2" align="center"><hr></td>
         </tr>
         <tr> 
		     <td colspan="2" align="center">
             <span class="art-button-wrapper">
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
			<input type="hidden"  name="metodo" value="Agregar" />
			<input type="hidden"  name="usuario" id="usuario" value="<?php echo $usuario;?>"/>	
			<input type="hidden"  name="href" value="../inicio.php?area=maestros/Cons_Documentos&Nmenu=<?php echo $_GET['Nmenu']; ?>"/>
			 
			 </td>
	   </tr>
  </table>
</form>
</body>
</html>
<script language="javascript" type="text/javascript">
var input01 = new Spry.Widget.ValidationTextField("input01", "none", {minChars:2, validateOn:["blur", "change"]});
var input02 = new Spry.Widget.ValidationTextField("input02", "none", {minChars:2, validateOn:["blur", "change"]});

</script>