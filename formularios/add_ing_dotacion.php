<?php 
require_once('autentificacion/aut_verifica_menu.php');
?>
  <fieldset class="fieldset">
  <legend>Informacion De Uniformes</legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">Talla De Pantal&oacute;n:</td>
       <td id="select01_2"><select name="t_pantalon" style="width:250px">
							<option value="<?php echo $cod_t_pantalon?>"><?php echo $t_pantalon;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM preing_pantalon
							  WHERE `status` = 'T' 
							AND codigo <> '$cod_t_pantalon'  ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">Talla Camisa:</td>
       <td id="select02_2"><select name="t_camisa" style="width:250px">
							<option value="<?php echo $cod_t_camisa;?>"><?php echo $t_camisa;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM preing_camisas
							  WHERE `status` = 'T' 
							AND codigo <> '$cod_t_camisa'  ORDER BY 2 ASC ";

		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>	
    <tr>
      <td class="etiqueta">N&uacute;mero De Zapato:</td>
       <td id="select03_2"><select name="n_zapato" style="width:250px">
							<option value="<?php echo $cod_n_zapato;?>"><?php echo $n_zapato; ?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM preing_zapatos
							  WHERE `status` = 'T' 
							AND codigo <> '$cod_n_zapato'  ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>        
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
</div>
  </fieldset>
<script language="javascript" type="text/javascript"> 

var select01_2 = new Spry.Widget.ValidationSelect("select01_2", {validateOn:["blur", "change"]});
var select02_2 = new Spry.Widget.ValidationSelect("select02_2", {validateOn:["blur", "change"]});
var select03_2 = new Spry.Widget.ValidationSelect("select03_2", {validateOn:["blur", "change"]});

</script>