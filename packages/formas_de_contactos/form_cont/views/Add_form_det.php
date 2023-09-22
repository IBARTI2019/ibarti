<?php
require "../modelo/proyecto_modelo.php";
require "../planif_modelo/modelo/planif_modelo.php";
require "../../../../" . Leng;

$codigo = $_POST['codigo'];
$proyecto = new Proyecto;
$matriz_det  =  $proyecto->get_planif_actividad($codigo);
?>

<table width="100%" border="0" align="center">
	<tr>
		</br>
		<td colspan="4">
			</hr>
		</td>
		</br>
	</tr>
	<tr>
		<th width="30%"></th>
		<th width="30%">Actividad</td>
		<th width="100%">Accion</th>
	</tr>
	<tr>
		<td><input type="hidden" name="cod_det" id="cod_det" value=""> &nbsp;</td>
		<td><textarea name="descripcion" id="descripcion" cols="60" rows="2" autocomplete="off"></textarea></td>
		<td align="center"><span class="art-button-wrapper">
				<span class="art-button-l"> </span>
				<span class="art-button-r"> </span>
				<input type="button" name="submit" id="submit" value="Ingresar" class="readon art-button" onclick="save_det('','agregar')" />
			</span></td>
	</tr>
	<?php
	$i     = 0;
	foreach ($matriz_det as $datos_det) {
		$i++;
		$cod_det     = $datos_det['codigo'];
		echo '<tr>
  					<td>' . $i . '<input type="hidden" name="cod_det" id="cod_det' . $cod_det . '" value="' . $cod_det . '"></td>
					  <td><textarea name="descripcion" id="descripcion' . $cod_det . '" cols="60" rows="2" autocomplete="off">' . $datos_det["descripcion"] . '</textarea></td>
					  <td align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="save_det(\'' . $cod_det . '\',\'modificar\')" />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro" border="null" class="imgLink" width="20px" height="20px" onclick="save_det(\'' . $cod_det . '\',\'eliminar\')"/></td>
              </td>
  				</tr>';
	}
	?>
</table>