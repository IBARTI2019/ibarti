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
		<td width="10%"  class="etiqueta"><?php echo $leng['cliente'] ?>:</td>
		<td ><select id="graph_cliente" style="width:300px" required onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'LL', '300')">
			<option value="">Seleccione</option>
			<?php
			foreach ($cliente as  $datos) {
				echo '<option value="' . $datos[0] . '">' . $datos[1] . ' ' . $datos[3] . '</option>';
			} ?>
			</select>
		</td>

		<td width="15%" class="etiqueta"><span id="ubicacion_texto"><?php echo $leng['ubicacion'] ?>:</span> </td>
		<td width="35%">
			<span id="contenido_ubic"><select id="ubicacion" required onchange="Add_filtroX()" style="width:300px">
				<option value="">Seleccione</option>
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
	<div style="display: none;" class="barra_vertical" id="division"></div>
	<div id="grafica2">
		<canvas id="chart-area2"></canvas>
	</div>
	<br class="brs">
	<br>
	</div>
</div>