<?php
$archivo = "pres_constantes";
$href    = "pres_constantes&Nmenu=".$_GET['Nmenu']."";
$codigo  = $_GET['codigo']; 

$result01 = mysql_query("SELECT pres_constantes.codigo, pres_constantes.cod_variables_tipo,
                                pres_constantes.valor, pres_constantes.caracteres,
                                pres_constantes.fecha, pres_constantes.descripcion,
                                pres_constantes.`status`
                           FROM pres_constantes 
                          WHERE pres_constantes.codigo = '$codigo' ", $cnn);  
$row01    = mysql_fetch_array($result01);
?>

<form action="sc_maestros/<?php echo $archivo;?>.php" method="post" name="Mod" id="Mod"> 
     <table width="70%" align="center">
         <tr valign="top">                    
		     <td height="23" colspan="2" class="etiqueta_title" align="center"> MODIFICAR CONSTANTE </td>
         </tr>
         <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
         </tr>	 
    <tr>
      <td class="etiqueta" width="25%">C&oacute;digo:</td>
      <td id="input01" width="75%"><input type="text" name="codigo" maxlength="12" style="width:120px" 
                                           value="<?php echo $row01["codigo"];?>" />	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n:</td>
      <td id="input02"><input type="text" name="descripcion" maxlength="60" value="<?php echo $row01["descripcion"];?>" style="width:250px" />	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>   		
    <tr>
      <td class="etiqueta">USO:</td>
      <td id="radio01" class="texto">
	 Numerico
            <input type = "radio" name="tipo"  value = "NUM" style="width:auto"
                   <?php echo CheckUso("".$row01['cod_variables_tipo']."", "NUM"); ?> />
     Caracter
            <input type = "radio" name="tipo"  value = "CARAC" style="width:auto" 
                   <?php echo CheckUso("".$row01['cod_variables_tipo']."", "CARAC"); ?>/>
     Fecha
            <input type = "radio" name="tipo"  value = "FEC" style="width:auto" 
                   <?php echo CheckUso("".$row01['cod_variables_tipo']."", "FEC"); ?>/>	<br />														 	         <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/>
            <span class="radioRequiredMsg">Debe Seleccionar un campo.</span>
        </td>
    </tr>
    <tr>
      <td class="etiqueta">Valor:</td>
    <td id="input03"><input type="text" name="valor" maxlength="14" style="width:120px" value="<?php echo round($row01["valor"], 2);?>" />
        <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/> <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span></td>
	 </tr>	    
     <tr>
	     <td height="20" class="etiqueta">Status:</td>
	     <td  id="select10">		    
			   <select name="status" style="width:120px;">	 
     				   <option value="<?php echo $row01["status"];?>"><?php echo statuscal($row01["status"]);?></option>
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
			<input type="hidden"  name="metodo" value="Modificar" />
           <input name="usuario" type="hidden"  value="<?php echo $usuario;?>" />			
			<input type="hidden"  name="href" value="../inicio.php?area=maestros/Cons_<?php echo $href;?>" />	 			
			 </td>
	   </tr>
  </table>
</form>
</body>
</html>
<script language="javascript" type="text/javascript">
var input01 = new Spry.Widget.ValidationTextField("input01", "none", {minChars:2, validateOn:["blur", "change"]});
var input02 = new Spry.Widget.ValidationTextField("input02", "none", {minChars:2, validateOn:["blur", "change"]});
var input03 = new Spry.Widget.ValidationTextField("input03", "currency", {format:"comma_dot",validateOn:["blur"], 
     	useCharacterMasking:true});

var radio01 = new Spry.Widget.ValidationRadio("radio01", { validateOn:["change", "blur"]});

var select10 = new Spry.Widget.ValidationSelect("select10", {validateOn:["blur", "change"]});
</script>