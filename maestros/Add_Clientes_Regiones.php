<?php 
$id     = $_GET['id']; 
$Nmenu  = $_GET['Nmenu'];
?>
<form action="sc_maestros/Clientes_Regiones.php" method="post" name="add" id="add"> 
     <table width="70%" align="center">
         <tr valign="top">                    
		     <td height="23" colspan="2" class="etiqueta_title" align="center">
		          AGREGAR REGIONES </td>
         </tr>
         <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
         </tr>	 
    <tr>
      <td class="etiqueta" width="25%">C&oacute;digo:</td>
      <td id="input01" width="75%"><input type="text" name="codigo" maxlength="10" style="width:120px" readonly="true" value="<?php echo $id; ?>" />	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>
     <tr>
	     <td class="etiqueta">Descripcion:</td>
	     <td  id="select01">		    
			   <select name="descripcion" style="width:200px;">	 
     				   <option value=""> Seleccione...</option>
					   <?php 
					   $query02 = mysql_query("SELECT * FROM regiones WHERE status = 1", $cnn);
					   while($row02=(mysql_fetch_array($query02))){
					   echo '<option value="'.$row02[0].'">'.$row02[1].'</option>';
					   }
					   ?>    
	           </select><img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>	
     <tr>
	     <td height="20" class="etiqueta">Status:</td>
	     <td  id="select02">		    
			   <select name="status" style="width:120px;">	 
     				   <option value="1"> Activo </option>
                       <option value="2"> Inactivo </option>    
	           </select><img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
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
			<input type="hidden" name="Nmenu" value="<?php echo $_GET['Nmenu']; ?>"/>
			<input type="hidden" name="href" value="../inicio.php?area=maestros/Cons_Clientes_Regiones&id=<?php echo $id?>&Nmenu=<?php echo $Nmenu;?>"/> 		
			</td>
	   </tr>
  </table>
</form>
</body>
</html>
<script language="javascript" type="text/javascript">
var input01 = new Spry.Widget.ValidationTextField("input01", "none", {minChars:2, validateOn:["blur", "change"]});


var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
var select02 = new Spry.Widget.ValidationSelect("select02", {validateOn:["blur", "change"]});
</script>