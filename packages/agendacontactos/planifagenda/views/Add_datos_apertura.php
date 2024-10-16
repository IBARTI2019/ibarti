<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;

$contratacion   = $_POST['codigo'];
$ubic   = $_POST['ubicacion'];
$plan   = new Planificacion;
$cont = $plan->get_contratacion_det($contratacion, $ubic);

echo '<div align="center" class="etiqueta_title">Detalle de Contratacion</div><table width="90%" border="0" align="center">
				<tr>
					<th width="40%">'.$leng["ubicacion"].'</th>
					<th width="25%">'.$leng["turno"].'</th>
					<th width="25%">Cargo</th>
					<th width="10%">Cantidad</th>
				</tr>';
					foreach ($cont as  $datos)
					{
						echo '<tr>
										<td>'.$datos["ubicacion"].' ('.$datos["ub_puesto"].')</td>
										<td>'.$datos["turno_abrev"].'</td>
										<td>'.longitud($datos["cargo"]).'</td>
										<td>'.$datos["cantidad"].'</td>
									<tr>';
					}
					echo '</table>';
?>
