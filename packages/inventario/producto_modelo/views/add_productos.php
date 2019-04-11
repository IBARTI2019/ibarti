<div id="Contenedor" class="mensaje"></div>
<fieldset class="fieldset">
	<legend>Datos Producto Modelo</legend>
	<table width="80%" align="center" id="prod_tabla">
		<tr>
			<td class="etiqueta">C&oacute;digo:</td>
			<td id="input01">
				<input type="text" name="codigo" maxlength="11" disabled="disabled" id="p_codigo" size="15" value="<?php echo $prod['codigo']; ?>"/>
				Activo: <input name="activo" id="p_activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
		</tr>
		<!--<tr>
			<td class="etiqueta">Item (Serial):</td>
			<td id="input02"><input type="text" name="item" maxlength="20" size="20" value="<?php // echo $prod['item'];?>" /><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido...</span>
			</td>
		</tr>--> 
		<tr>
			<td class="etiqueta">Producto: </td>
			<td id="input03"><input type="text" name="descripcion" id="p_descripcion" maxlength="60" size="40" value="<?php echo $prod['descripcion'];?>"/><br />
				<span class="textfieldRequiredMsg">El Campo es Requerido... </span>
			</td>
		</tr>
		<tr>
			<td class="etiqueta">Producto Tipo:</td>
			<td id="select04"><select name="prod_tipo" id="p_prod_tipo" style="width:250px">
				<option value="<?php echo $prod['cod_prod_tipo'];?>"><?php echo $prod['prod_tipo'];?></option>
				<?php  
				$tipos  =  $producto->get_tipos();
				foreach ($tipos as  $datos) {
					echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
				?>		  	  
			</select></td>      	        	    
		</tr> 
		<tr>
			<td class="etiqueta">Linea: </td>
			<td id="select01"><select name="linea" id="p_linea" onchange="get_sub_lineas(this.value)" style="width:250px">
				<option value="<?php echo $prod['cod_linea'];?>"><?php echo $prod['linea'];?></option>
				<?php  
				$lineas  =  $producto->get_lineas();
				foreach ($lineas as  $datos) {
					echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
				?>		  	  
			</select></td>       	        	    
		</tr>
		<tr>
			<td class="etiqueta">Sub Linea:</td>
			<td id="sub_linea"><select name="sub_linea" id="p_sub_linea" onchange="get_propiedades(this.value)" style="width:250px">
				<option value="<?php echo $prod['cod_sub_linea'];?>"><?php echo $prod['sub_linea'];?></option>
				<?php  
				$sub_lineas  =  $producto->get_sub_lineas($prod['cod_linea']);
				foreach ($sub_lineas as  $datos) {
					echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
				?>			  	  
			</select></td>
		</tr> 
		<tr>
			<td class="etiqueta">Unidad:</td>
			<td id="select05"><select name="unidad" id="p_unidad" style="width:250px">
				<option value="<?php echo $prod['cod_unidad'];?>"><?php echo $prod['unidad'];?></option>
				<?php  
				$unidades  =  $producto->get_unidades();
				foreach ($unidades as  $datos) {
					echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
				?>			  	  
			</select></td>       	        	    
		</tr>
		<tr>
			<td class="etiqueta">Proveedor:</td>
			<td id="select06"><select name="proveedor" id="p_proveedor" style="width:250px">
				<option value="<?php echo $prod['cod_proveedor'];?>"><?php echo $prod['proveedor'];?></option>
				<?php  	$sql = " SELECT codigo, nombre FROM proveedores WHERE `status` = 'T' ORDER BY 2 ASC ";
				$query = $bd->consultar($sql);
				while($datos=$bd->obtener_fila($query,0)){	
					?>
					<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
				<?php }?>		  	  
			</select>
			<br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>       	        	    
		</tr>
		<tr>
			<td class="etiqueta">Procedencia:</td>
			<td id="select07"><select name="procedencia" id="p_procedencia" style="width:250px">
				<option value="<?php echo $prod['cod_procedencia'];?>"><?php echo $prod['procedencia'];;?></option>
				<?php  	$sql = " SELECT codigo, descripcion FROM prod_procedencia WHERE `status` = 'T' ORDER BY 2 ASC ";
				$query = $bd->consultar($sql);
				while($datos=$bd->obtener_fila($query,0)){	
					?>
					<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
				<?php }?>		  	  
			</select>
			<br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>       	        	    
		</tr>
		<tr>
			<td class="etiqueta">Iva:</td>
			<td id="select08"><select name="iva" id="p_iva" style="width:250px">
				<option value="<?php echo $prod['cod_iva'];?>"><?php echo $prod['iva'];?></option>
				<?php  	$sql = " SELECT codigo, descripcion FROM iva WHERE `status` = 'T' ORDER BY 2 ASC ";
				$query = $bd->consultar($sql);
				while($datos=$bd->obtener_fila($query,0)){	
					?>
					<option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
				<?php }?>		  	  
			</select></td>       	        	    
		</tr>  
		<tr>
			<td class="etiqueta">Color:</td>
			<td id="td_colores"></td>
		</tr>
	</table>
	<div align="center">
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />	
		</span>&nbsp;
		<span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />	
		</span> 	
	</div>
</fieldset>