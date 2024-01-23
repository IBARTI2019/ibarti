<?php 
	require_once('autentificacion/aut_verifica_menu.php');
	$proced      = "p_fichas_04";
    $metodo       = "agregar";	
	$archivo = "pestanas/add_ficha3&Nmenu=$Nmenu&codigo=$codigo&mod=$mod&pagina=3&metodo=modificar";
?>
<form action="scripts/sc_ficha_04.php" method="post" name="add" id="add"> 
  <fieldset class="fieldset">
  <legend>Documento Trabajador </legend>
     <table width="80%" align="center">
    <tr>
      <td colspan="3" class="etiqueta">DOCUMENTOS  xx:</td>	  
    </tr>
	<?php 
		$sql = " SELECT ficha_documentos.cod_documento, documentos.descripcion, ficha_documentos.`checks` 
                   FROM documentos, ficha_documentos
                  WHERE ficha_documentos.cod_ficha = '$codigo'
				  AND ficha_documentos.cod_documento = documentos.codigo ";
			$query = $bd->consultar($sql);
			while($datos=$bd->obtener_fila($query,0)){
			extract($datos);

				echo' 		
					<tr>
						<td width="50%" class="texto">'.$descripcion.'</td>
						<td width="10%"><input name="documento[]" type="checkbox" 
									 value="'.$cod_documento.'" style="width:auto"
						 '.CheckX(''.$checks.'', 'S').' /></td>	
						<td width="40%">&nbsp;</td>
					</tr>';
				}?>
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
                <input type="button" id="volver04" value="Volver" onClick="history.back(-1);" class="readon art-button" />	
                </span>
		<input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
        <input name="proced" type="hidden"  value="<?php echo $proced;?>" />
        <input name="eventual" type="hidden"  value="true" />
		<input name="codigo" type="hidden"  value="<?php echo $codigo;?>" />
        <input name="usuario" type="hidden"  value="<?php echo $usuario;?>" />
	    <input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>"/>		 

</div>  
  </fieldset>
</form>