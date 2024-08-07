

<table width="75%" border="0" align="center">
    <td height="8" colspan="2" align="center"><hr></td>
    <tr><td class="etiqueta" >Region:</td><td><select name="region" id="region" style="width:220px"onchange="llenar_cliente(this.value)" /></td></tr>
    <tr><td class="etiqueta">Estado:</td><td><select name="estado" id="estado"style="width:220px" onchange="llenar_ciudad(this.value)" /></td></tr>
    <tr><td class="etiqueta">Ciudad:</td><td><select name="ciudad" id="ciudad"style="width:220px"/></td></tr>
    <tr><td class="etiqueta">Empresa:</td><td><select name="cliente" id="cliente"style="width:220px" onchange="llenar_ubicacion(this.value)" /></td></tr>
    <tr><td class="etiqueta">ubicacion:</td><td><select name="ubicacion" id="ubicacion"style="width:220px" onchange="rellenar(this.selectedIndex,this.value)" /></td></tr>
    <tr><td class="etiqueta">Puesto:</td><td><select name="puesto" id="puesto" style="width:220px" onchange="" /></td></tr>
    <tr>
		<td class="etiqueta">Estatus:</td>	
		<td >
			<select id="estatu" name="estatu"  style="width:220px" onchange="">
				<option value="TODOS">TODOS</option>
				<option value="T">ACTIVO</option>
				<option value="F">INACTIVO</option>
		</select>
      
    </tr>
    <td height="8" colspan="2" align="center"><hr></td>
</table>
<input type="submit" name="procesar" id="procesar" hidden="hidden">
<input type="text" name="reporte" id="reporte" hidden="hidden">

<img class="imgLink" id="img_pdf" src="imagenes/pdf.gif" border="0"
onclick="reportes('pdf')" width="25px" title="imprimir a pdf">

<img class="imgLink" id="img_excel" src="imagenes/excel.gif" border="0"
onclick="reportes('excel')" width="25px" title="imprimir a excel">

<span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
</span>
<input name="usuario" type="hidden"  value="<?php echo $usuario;?>"/>
</div>
