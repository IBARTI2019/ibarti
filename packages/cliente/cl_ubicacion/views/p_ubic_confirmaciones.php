<script language="javascript">

</script>
<?php 

$sql = " SELECT
		IFNULL(clientes_ubicacion.min_confirm, control.min_confirm)	min_confirm,
		IFNULL(clientes_ubicacion.max_confirm, control.max_confirm)	max_confirm,
		IFNULL(clientes_ubicacion.min_in_transport, control.min_in_transport) min_in_transport,
		IFNULL(clientes_ubicacion.max_in_transport, control.max_in_transport) max_in_transport
	FROM
		control,
		clientes_ubicacion 
	WHERE
		clientes_ubicacion.codigo = $codigo;";

$query = $bd->consultar($sql);
$result = $bd->obtener_fila($query, 0);
$min_confirm       = $result['min_confirm'];
$max_confirm       = $result['max_confirm'];
$min_in_transport       = $result['min_in_transport'];
$max_in_transport       = $result['max_in_transport'];
?>
<div id="Cont_mensaje" class="mensaje"></div>
<div>
	<table width="80%" align="center">
   <tr valign="top">                    
     <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL DE CONFIRMACIONES</td>
   </tr>
   <tr><td height="8" colspan="2" align="center"><hr></td></tr>			 
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_confirm" id="min_confirm" value="<?php echo $min_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion de asistencia: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_confirm" id="max_confirm" value="<?php echo $max_confirm;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Minimo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="min_in_transport" id="min_in_transport" value="<?php echo $min_in_transport;?>">
    </td>
   </tr>
   <tr>  
     <td class="etiqueta" width="40%">Maximo de minutos para confirmacion en transporte: </td>
     <td width="60%">
      <input type="number" required="required" min="1" name="max_in_transport" id="max_in_transport" value="<?php echo $max_in_transport;?>">
    </td>
   </tr>
   <tr><td height="4" colspan="2" align="center"><hr></td></tr>			  
   <tr>      
   </table>
   <div align="center"><br />
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" name="salvar_confirmaciones" id="salvar_confirmaciones" value="Guardar" onclick="save_confirmaciones()" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
			</span>&nbsp;
			<span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" id="volver" value="Cerrar" onClick="CloseModal();" class="readon art-button" />
			</span>
		</div>
</div>
<div align="center">
	<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
	<input type="hidden" name="usuario" value="<?php echo $usuario; ?>" />

	<input type="hidden" id="cod_ubic_confirmaciones" name="codigo" value="<?php echo $codigo; ?>" />
	<input type="hidden" id="i" value="<?php echo $i; ?>" />
</div>
<br />
<br />