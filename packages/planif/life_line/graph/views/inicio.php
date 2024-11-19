<?php 
require "../modelo/life_line_graph_modelo.php";
require "../../../../../" . Leng;
$graph   = new LifeLineGraph;
$cliente  =  $graph->get_cliente($_POST["usuario"], $_POST["r_cliente"]);
?>
<div>
	<div align="center" class="etiqueta_title"> Gráfica de Linea de Vida </div>
	<br>
	<table width="100%" align="center">
		<tr>
		<td width="8%"  class="etiqueta"><?php echo $leng['cliente'] ?>:</td>
		<td ><select id="graph_cliente" style="width:250px" required onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'LL', '250')">
			<option value="">Seleccione</option>
			<?php
			foreach ($cliente as  $datos) {
				echo '<option value="' . $datos[0] . '">' . $datos[1] . ' ' . $datos[3] . '</option>';
			} ?>
			</select>
		</td>

		<td width="8%" class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span> </td>
		<td>
			<span id="contenido_ubic"><select id="ubicacion" required onchange="Add_filtroX()" style="width:250px">
				<option value="">Seleccione</option>
			</select>
			</span>
		</td>
		<td width="8%" class="etiqueta"><span id="ubicacion_texto">Tipo:</span> </td>
		<td>
			<span><select id="propuesta" required onchange="Add_filtroX()" style="width:250px">
				<option value="F">Actual</option>
				<option value="T">Propuesta</option>
			</select>
			</span>
		</td>
		</tr>
		<tr>
		<td height="8" colspan="4" align="center">
			<hr>
		</td>
		</tr>
	</table>
	<label style="display: none;" id="sin_data" class="etiqueta">No hay Resultados</label>
	<div class="col-xs-12">
	<div id="grafica">
		<canvas id="chart-area"></canvas>
	</div>
	<div id="estadisticas">
		<table>
			<tr>
				<td  class="etiqueta">Total tiempo critico: <span id="ttc"></span></td>
			</tr>
			<tr>
				<td  class="etiqueta">Maximo tiempo critico: <span id="mtc"></span></td>
			</tr>
		</table>
	</div>
	<br class="brs">
	<br>
	</div>
</div>