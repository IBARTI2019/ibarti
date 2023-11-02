<?php
require "../modelo/planificacion_modelo.php";
require "../../../../".Leng;
$cliente   = $_POST['cliente'];
$apertura   = $_POST['apertura'];
$plan   = new Planificacion;
$datos = $plan->get_trab_sin_planif($cliente,$apertura);
?>
<br>
<table width="100%" border="0" align="center" class="tabla_sistema">
	<tr class="fondo00">
		<th width="20%" class="etiqueta"><?php echo $leng['rol']?></th>
		<th width="10%" class="etiqueta"><?php echo $leng['ficha']?></th>
		<th width="10%" class="etiqueta"><?php echo $leng['ci']?></th>
		<th width="40%" class="etiqueta"><?php echo $leng['trabajador']?></th>
	</tr>
	<?php
	$valor = 0;

	for ($i=0; $i < count($datos); $i++) { 
		if ($valor == 0){
			$fondo = 'fondo01';
			$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo '<tr class="'.$fondo.'">
		<td class="texto">'.$datos[$i]['rol'].'</td>
		<td class="texto">'.$datos[$i]['cod_ficha'].'</td>
		<td class="texto">'.$datos[$i]['cedula'].'</td>
		<td class="texto">'.$datos[$i]['ap_nombre'].'</td></tr>'; 
	};

?>
</table>
<br>