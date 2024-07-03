	<table width="80%" align="center">
	   <tr valign="top">
	      <td height="23" colspan="2" class="etiqueta_title" align="center">CONTROL DE MENSAJERÍA</td>
	   </tr>
	   <tr>
	      <td height="8" colspan="2" align="center">
	         <hr>
	      </td>
	   </tr>
	   <tr valign="top">
	      <td height="23" colspan="2" class="etiqueta" align="center">CLASIFICACIONES DE MENSAJERÍA</td>
	   </tr>
	   <tr>
	      <td height="8" colspan="2" align="center">
	         <hr>
	      </td>
	   </tr>
	   <tr>
	      <?php
         $i   = 0;
         $sql = " SELECT nov_clasif.codigo , nov_clasif.descripcion, nov_clasif.control_mensajeria
                  FROM nov_clasif WHERE nov_clasif.`status` = 'T'
               ORDER BY 2 ASC ";
         $query = $bd->consultar($sql);
         echo '<table width="60%" align="center">';

         while ($row03 = $bd->obtener_fila($query, 0)) {
        	extract($row03);
            echo '
				<tr>
					<td class="etiqueta">' . $row03[1] . '</td>					
					<td><input name="clasif_mensajeria[]" type="checkbox" value="' . $row03[0] . '" 
						           style="width:auto" ' . CheckX('' . $row03[2] . '', 'T') . ' /></td>
			   </tr>';
         }
         echo '</tr></table>';   ?>
		 <tr>
	           	<td id="select_4_01" width="60%"><select  hidden="true" name="nov_clasif_sms" style="width:200px">
							         <option value="<?php  echo $cod_clasif_sms;
                                             ?>"><?php echo $clasif_sms; ?></option>
          <!-- <?php $sql = " SELECT codigo, descripcion FROM nov_clasif
		                      WHERE status = 'T' AND codigo <> '$cod_clasif_sms' ORDER BY 2 ASC ";
		            // $query = $bd->consultar($sql);
            		// while($datos=$bd->obtener_fila($query,0)){ ?>
          <option value="<?php  // echo $datos[0];
                           ?>"><?php // echo $datos[1];
                                 ?></option>
          <?php// }
            ?> -->
                             </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td> 
	   </tr>
	   <tr>
	      <td height="8" colspan="2" align="center">
	         <hr>
	      </td>
	   </tr>
	   <tr valign="top">
	      <td height="23" colspan="2" class="etiqueta" align="center">CONTROL MENSAJERIA A ENVIAR</td>
	   </tr>
	   <tr>
	      <td height="8" colspan="2" align="center">
	         <hr>
	      </td>
	   </tr>

	</table>
	<table width="60%" align="center">
	   <?php
      $i   = 0;
      $sql = " SELECT nov_status.codigo AS cod_nov , nov_status.descripcion, nov_status.control_mensajeria
               FROM nov_status
			  ORDER BY 2 ASC ";
      $query = $bd->consultar($sql);
      while ($row03 = $bd->obtener_fila($query, 0)) {
         //	extract($row03);
         echo '
				<tr>
					<td class="etiqueta">' . $row03[1] . '</td>					
					<td><input name="mensajeria[]" type="checkbox" value="' . $row03[0] . '" 
						           style="width:auto" ' . CheckX('' . $row03[2] . '', 'T') . ' /></td>
			   </tr>';
      }  ?>
	</table>
	<div align="center"><span class="art-button-wrapper">
	      <span class="art-button-l"> </span>
	      <span class="art-button-r"> </span>
	      <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
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
	<script type="text/javascript" {>
	   var select_4_01 = new Spry.Widget.ValidationSelect("select_4_01", {
	      validateOn: ["blur", "change"]
	   });
	</script>