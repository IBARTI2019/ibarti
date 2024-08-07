<?php
require "../modelo/proyecto_modelo.php";
require "../planif_modelo/modelo/planif_modelo.php";
require "../../../../" . Leng;

$codigo = $_POST['codigo'];
$proyecto = new Proyecto;
$matriz_det  =  $proyecto->get_planif_cargos($codigo);
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
		<th width="10%">Código</th>
		<th width="60%">Descripcion</td>
		<th width="10%">Check</td>
	</tr>
	<?php
	$i     = 0;
	foreach ($matriz_det as $datos_det) {
		$i++;
		$cod_det     = $datos_det['codigo'];
		if ($datos_det['existe'] != 'NO' && $datos_det['status'] == 'T') {
			$check = 'checked="checked"';
		} else {
			$check = '';
		}
		echo '<tr>
  					<td>' . $i . '<input type="hidden" name="cod_det_cargo" id="cod_det_cargo' . $cod_det . '" value="' . $cod_det . '"></td>
					  <td>' . $datos_det["descripcion"] . '</td>
					  <td><input type="checkbox" id="check' . $cod_det . '" value="' . $cod_det . '" name="check" value="T" ' . $check . ' onclick="actualizar(this.value)"></td>
              </td>
  				</tr>';
	}
	?>
</table>