<?php 
$titulo  = $_GET['titulo']; 
$archivo = $_GET['archivo'];
$tabla   = $_GET['tabla'];
$Nmenu   =  $_GET['Nmenu'];
$metodo  = "AgregarCodigo";
$href     = "../inicio.php?area=maestros/Cons_".$archivo."&Nmenu=".$_GET['Nmenu']."";
require_once('autentificacion/aut_verifica_menu.php');
?>
<form action="sc_maestros/sc_maestros.php" method="post" name="add" id="add" accept-charset="UTF-8"> 
     <table width="70%" align="center">
         <tr valign="top">                    
		     <td height="23" colspan="2" class="etiqueta_title" align="center"> AGREGAR <?php echo $titulo; ?> </td>
         </tr>
         <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
         </tr>	 
    <tr>
      <td class="etiqueta" width="25%">C&oacute;digo:</td>
      <td id="input01" width="75%"><input type="text" name="codigo" maxlength="12" style="width:120px"/>	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span></td>
    </tr>
    <tr>
      <td class="etiqueta" width="25%">Descripci&oacute;n: ojo</td>
      <td id="input02" width="75%"><input type="text" name="descripcion" maxlength="60" style="width:300px"/>	  
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>		
     <tr>
	     <td height="20" class="etiqueta">Status:</td>
	     <td  id="select01">		    
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
      		<input  type="submit" name="salvar"  id="salvar" value="Guardar" class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">	&nbsp;
		    <input type="reset"     id="limpiar"  value="Restablecer" class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">	&nbsp;
		    <input type="button"   id="volver"  value="Volver" onClick="history.back(-1);"  class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">
			<input type="hidden" name="metodo" value="<?php echo $metodo;?>" />
			<input type="hidden" name="tabla" value="<?php echo $tabla;?>" />
			<input type="hidden" name="usuario" value="<?php echo $usuario;?>" />		
			<input type="hidden" name="href" value="<?php echo $href;?>"/> 					   			
		     </td>
	   </tr>
  </table>
</form>
</body>
</html>
<script language="javascript" type="text/javascript">
var input01 = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
var input02 = new Spry.Widget.ValidationTextField("input02", "none", {minChars:2, validateOn:["blur", "change"]});
var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
</script>