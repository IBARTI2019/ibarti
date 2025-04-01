<?php
require "../modelo/rotacion_modelo.php";
require "../../planif_modelo/modelo/planif_modelo.php";
require "../../../../".Leng;

$codigo = $_POST['codigo'];
$rotacion = new Rotacion;
$matriz_det  =  $rotacion->get_rotacion_det($codigo);

$modelo   =   new Planif_modelo;
$turno   =   $modelo->get_turno('');
?>
	 <table width="100%" border="0" align="center">
     <tr>
          </br>
          <td colspan="4"></hr></td>
          </br>
     </tr>
    	<tr>
					<th width="10%">Posicion</th>
					<th width="30%"><?php echo $leng["turno"];?></th>
			    <th width="45%">Detalle:</th>
			    <th width="10%"><img src="imagenes/loading2.gif" width="30px" height="30px"/></th>
			</tr>
			<tr>
				<td><input type="hidden" name="cod_det" id="cod_det" value=""> &nbsp;</td>
				<td><select name="r_turno" id="r_turno" style="width:200px;" onchange="Mostrar_Detalle(this.value, 'detalleX')">
				<option  value="">Seleccione...</option>
				<?php
        foreach ($turno as $datos) {
				      echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
				}
				?>
				</select></td>
	        <td id="mostrar_detalle"></td>
				  <td align="center"><span class="art-button-wrapper">
	                    <span class="art-button-l"> </span>
	                    <span class="art-button-r"> </span>
	                 <input type="button" name="submit" id="submit" value="Ingresar" class="readon art-button"
	                        onclick="save_det('','agregar')" />
	                </span></td>
			</tr>
      <?php
      $i     = 0;
      foreach ($matriz_det as $datos_det) {
          	$i++;
            $cod_det     = $datos_det['codigo'];
    echo '<tr>
  					<td>'.$i.'<input type="hidden" name="cod_det" id="cod_det'.$cod_det.'" value="'.$cod_det.'"></td>
  					<td><select name="r_turno" id="r_turno'.$cod_det.'" style="width:200px;" onchange="Mostrar_Detalle(this.value, \'detalleX_'.$cod_det.'\')">
  					<option  value="'.$datos_det["cod_turno"].'">'.$datos_det["turno"].'</option>';
            foreach ($turno as $datos) {
              echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
            }
  		echo '</select></td>
  		        <td id="mostrar_detalle_'.$cod_det.'">'.$datos_det["detalle"].'</td>
  					  <td align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="save_det(\''.$cod_det.'\',\'modificar\')" />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro" border="null" class="imgLink" width="20px" height="20px" onclick="save_det(\''.$cod_det.'\',\'borrar\')"/></td>
              </td>
  				</tr>';
        }
       ?>
	</table>
