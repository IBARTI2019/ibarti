     <table width="80%" align="center">
         <tr valign="top">
		     <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL DE NOMINA</td>
         </tr>
         <tr>
    	       <td height="8" colspan="2" align="center"><hr></td>
     	</tr>

    <tr>
	    <td width="40%" class="etiqueta"><?php echo $leng['ficha']?> Usar Preingreso:</td>
		<td width="60%">SI<input type = "radio" name="ficha_preingreso" value = "S" style="width:auto" <?php echo CheckX($ficha_preingreso, "S");?> /> NO<input type = "radio" name="ficha_preingreso"  value = "N" style="width:auto" <?php echo CheckX($ficha_preingreso, "N");?>  /></td>

	</tr>
       <tr>
      <td  width="40%" class="etiqueta">C&oacute;digo de Preingreso Nuevo :</td>
       <td  width="60%" id="input_2_01"><input type="text" name="p_nuevo" maxlength="12" size="15" value="<?php echo $p_nuevo;?>" />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>

    <tr>
      <td class="etiqueta">C&oacute;digo de Preingreso Aprobado :</td>
       <td id="input_2_02"><input type="text" name="p_aprobado" maxlength="12" size="15" value="<?php echo $p_aprobado;?>" />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">C&oacute;digo de Preingreso Rechazado:</td>
       <td id="input_2_03"><input type="text" name="p_rechazado" maxlength="12" size="15" value="<?php echo $p_rechazado;?>" />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['ficha']?> Activo: </td>
      	<td id="select_2_01"><select name="ficha_activo" style="width:200px">
							<option value="<?php echo $cod_ficha_activo;?>"><?php echo $ficha_activo;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM ficha_status
		                      WHERE status = 'T' AND codigo <> '$cod_ficha_activo' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>

     <tr>
      <td class="etiqueta">Dias para Vencimiento de Expedientes:</td>
       <td id="input_2_04"><input type="text" name="expedientes_dias" maxlength="12" size="15" value="<?php echo $expedientes_dias;?>" />

        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">C&oacute;digo de Vale:</td>
       <td id="input_2_05"><input type="text" name="vale_concepto" maxlength="12" size="15" value="<?php echo $vale_concepto;?>" />

        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta">Monto de Vale:</td>
       <td id="input_2_06"><input type="text" name="vale_monto" maxlength="12" size="15" value="<?php echo $vale_monto;?>" />

        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['concepto']?> cestaticket: </td>
      	<td id="select_2_02"><select name="c_cestaticket" style="width:200px">
							<option value="<?php echo $cod_cestaticket;?>"><?php echo $cestaticket;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  conceptos
		                      WHERE status = 'T' AND codigo <> '$cod_cestaticket' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['concepto']?> Hora Extras Diurna: </td>
      	<td id="select_2_03"><select name="c_hora_extras_d" style="width:200px">
							<option value="<?php echo $cod_hora_extras_d;?>"><?php echo $hora_extras_d;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  conceptos
		                      WHERE status = 'T' AND codigo <> '$cod_hora_extras_d' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['concepto']?> Hora Extras Noturna: </td>
      	<td id="select_2_04"><select name="c_hora_extras_n" style="width:200px">
							<option value="<?php echo $cod_hora_extras_n;?>"><?php echo $hora_extras_n;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  conceptos
		                      WHERE status = 'T' AND codigo <> '$cod_hora_extras_n' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['concepto']?> Retirado: </td>
      	<td id="select_2_05"><select name="c_retirado" style="width:200px">
							<option value="<?php echo $cod_c_retirado;?>"><?php echo $c_retirado;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  conceptos
		                      WHERE status = 'T' AND codigo <> '$cod_c_replica' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
     <tr>
      <td class="etiqueta"><?php echo $leng['concepto']?> para Replicar: </td>
      	<td id="select_2_06"><select name="c_replica" style="width:200px">
							<option value="<?php echo $cod_c_replica;?>"><?php echo $c_replica;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  conceptos
		                      WHERE status = 'T' AND codigo <> '$cod_c_replica' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
     <tr>
      <td class="etiqueta">Supervisor Cargo: </td>
      	<td id="select_2_07"><select name="s_cargo" style="width:200px">
							<option value="<?php echo $cod_s_cargo;?>"><?php echo $s_cargo;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM  cargos
		                      WHERE status = 'T' AND codigo <> '$cod_s_cargo' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	 </tr>
     <tr>
        <td height="8" colspan="2" align="center"><hr></td>
     </tr>
     <tr valign="top">
		<td height="23" colspan="2" class="etiqueta_title" align="center">CALCULO AUTOMATICO DE HORAS EXTRAS (CICLO DE TRABAJO)</td>
     </tr>
     <tr>
        <td height="8" colspan="2" align="center"><hr></td>
     </tr>
     <tr>
	    <td class="etiqueta">Activar C&aacute;lculo Autom&aacute;tico:</td>
		<td>SI<input type="radio" name="calc_he_auto" value="S" style="width:auto" <?php echo CheckX($calc_he_auto, "S");?> /> NO<input type="radio" name="calc_he_auto" value="N" style="width:auto" <?php echo CheckX($calc_he_auto, "N");?> /></td>
	 </tr>
     <tr>
        <td class="etiqueta">D&iacute;as del Ciclo (Semanas):</td>
        <td><input type="number" name="dias_ciclo_he" id="dias_ciclo_he" min="1" max="365" size="10" value="<?php echo $dias_ciclo_he;?>" style="width:100px;" /> d&iacute;as (ej. 56 = 8 semanas)</td>
     </tr>
     <tr>
        <td class="etiqueta">L&iacute;mite de Horas Normales en Ciclo:</td>
        <td><input type="number" name="max_horas_ciclo" id="max_horas_ciclo" min="1" max="1000" step="0.5" size="10" value="<?php echo $max_horas_ciclo;?>" style="width:100px;" /> horas (ej. 320.00)</td>
     </tr>
     <tr>
        <td class="etiqueta" valign="top">Conceptos que Computan Horas:</td>
        <td>
           <div style="background:#f4f4f4; padding:5px; border-radius:4px; max-height:260px; overflow-y:auto; border:1px solid #ddd;">
           <table width="100%" border="0">
              <?php
              $sql_c = "SELECT c.codigo, c.abrev, c.descripcion, IFNULL(h.horas_trabajo, 0.00) as horas_trabajo, IFNULL(h.tipo_hora_extra, 'DIURNO') as tipo_hora_extra,
                               IF(cc.cod_concepto IS NOT NULL, 1, 0) AS marcado
                          FROM conceptos c
                          LEFT JOIN horarios h ON c.cod_horario = h.codigo
                          LEFT JOIN control_conceptos_he cc ON c.codigo = cc.cod_concepto
                         WHERE c.status = 'T' AND c.asist_diaria = 'T'
                         ORDER BY c.abrev ASC";
              $query_c = $bd->consultar($sql_c);
              while($row_c = $bd->obtener_fila($query_c, 0)){
                  $checked = ($row_c['marcado'] == 1) ? 'checked="checked"' : '';
                  echo '<tr>
                          <td width="30px"><input type="checkbox" name="conceptos_he[]" value="'.$row_c['codigo'].'" '.$checked.' style="width:auto;" /></td>
                          <td><strong>['.$row_c['abrev'].'] '.$row_c['descripcion'].'</strong> <span style="color:#666;">('.$row_c['codigo'].' - '.$row_c['horas_trabajo'].' hrs - '.$row_c['tipo_hora_extra'].')</span></td>
                        </tr>';
              }
              ?>
           </table>
           </div>
        </td>
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
<script type="text/javascript">
var input_2_01 = new Spry.Widget.ValidationTextField("input_2_01", "none", {validateOn:["blur", "change"]});
var input_2_02 = new Spry.Widget.ValidationTextField("input_2_02", "none", {validateOn:["blur", "change"]});
var input_2_03 = new Spry.Widget.ValidationTextField("input_2_03", "none", {validateOn:["blur", "change"]});
var input_2_04 = new Spry.Widget.ValidationTextField("input_2_04", "none", {validateOn:["blur", "change"]});
var input_2_05 = new Spry.Widget.ValidationTextField("input_2_05", "none", {validateOn:["blur", "change"]});
var input_2_06 = new Spry.Widget.ValidationTextField("input_2_06", "none", {validateOn:["blur", "change"]});

var select_2_01 = new Spry.Widget.ValidationSelect("select_2_01", {validateOn:["blur", "change"]});
var select_2_02 = new Spry.Widget.ValidationSelect("select_2_02", {validateOn:["blur", "change"]});
var select_2_03 = new Spry.Widget.ValidationSelect("select_2_03", {validateOn:["blur", "change"]});
var select_2_04 = new Spry.Widget.ValidationSelect("select_2_04", {validateOn:["blur", "change"]});
var select_2_05 = new Spry.Widget.ValidationSelect("select_2_05", {validateOn:["blur", "change"]});
var select_2_06 = new Spry.Widget.ValidationSelect("select_2_06", {validateOn:["blur", "change"]});
var select_2_07 = new Spry.Widget.ValidationSelect("select_2_07", {validateOn:["blur", "change"]});

</script>
