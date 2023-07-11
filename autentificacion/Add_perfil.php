<?php 
	$metodo = $_GET['metodo'];
	$mod   = $_GET['mod'];
	$Nmenu = $_GET['Nmenu'];
    $titulo = "Perfil";
	$vinculo = "../inicio.php?area=autentificacion/Cons_Perfil&Nmenu=".$Nmenu."&mod=".$mod."";
	if(isset($_GET['codigo'])){ //== ''

    	$titulo = " Modificar ".$titulo."";	
		$codigo = $_GET['codigo'];
		$archivo = "empresa"; 
		$archivo2 = "autentificacion/cons_perfil&Nmenu=".$Nmenu.""; 

	$sql = " SELECT men_perfiles.codigo,men_perfiles.descripcion,men_perfiles.orden, men_perfiles.status,men_perfiles.idcriticidad,criticidad.descripcion as descrit
  FROM men_perfiles inner join criticidad on men_perfiles.idcriticidad = criticidad.codigo WHERE men_perfiles.codigo = '$codigo'";
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);
	  	   
	$codigo      = $result['codigo'];
	$descripcion = $result['descripcion'];
	$orden       = $result['orden'];
	$status      = $result['status'];
  $cod_criticidad= $result['idcriticidad'];
  $criticidad = $result['descrit'];
	}else{
	$codigo      = '';
  $titulo = " Agregar ".$titulo."";	
	$descripcion = '';
	$orden       = '';
	$status    = '';
  $criticidad='';
  $cod_criticidad =0;
	}
?>
<form action="autentificacion/sc_Menu_Perfil.php" method="post" name="add" id="add"> 
  <fieldset class="fieldset">
  <legend><?php echo $titulo;?> </legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">Codigo:</td>
      <td id="input01"><input type="text" name="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo;?>" /> 
         Activo: <input name="status" type="checkbox"<?php echo statusCheck("$status");?> value="T" /><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">Descripcion:</td>
      <td id="input02"><input type="text" name="descripcion" maxlength="60" style="width:300px" value="<?php echo $descripcion;?>"/><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">Orden:</td>
      <td id="input03"><input type="text" name="orden" maxlength="3" style="width:120px" value="<?php echo $orden;?>" /><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>  
   <tr>
      <td class="etiqueta">Criticidad:</td>
      	<td id="select01"><select name="cod_criticidad" style="width:250px">
							<option value="<?php echo $cod_criticidad;?>"><?php echo $criticidad;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM criticidad
		                      WHERE status = 'T' AND codigo <> '$cod_criticidad' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>   
	 <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
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
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />	
                </span>
            <input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
	        <input name="href" type="hidden" value="<?php echo $vinculo;?>"/>
            <input name="usuario" type="hidden" value="<?php echo $usuario;?>"/>
            <input name="modulo" type="hidden" value=""/>
            <input name="menu" type="hidden" value=""/>
</div> 
  </fieldset>
</form>
</body>
</html>
<script type="text/javascript">
var input01  = new Spry.Widget.ValidationTextField("input01", "none", { validateOn:["blur", "change"]});
var input02  = new Spry.Widget.ValidationTextField("input02", "none", { validateOn:["blur", "change"]});
var input03  = new Spry.Widget.ValidationTextField("input03", "integer", {validateOn:["blur"], useCharacterMasking:true, minValue:"0"});	

</script>