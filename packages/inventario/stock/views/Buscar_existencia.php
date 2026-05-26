<?php
$linea = $_POST['linea'];
$sub_linea = $_POST['sub_linea'];
$producto = $_POST['producto'];
$almacen = $_POST['almacen'];

require "../modelo/existencia_modelo.php";
$existencia = new Existencia;
$lista = $existencia->buscar($linea,$sub_linea,$producto,$almacen);

foreach ($lista as  $datos) {
	echo '
	<tr>
	<td>'.$datos["almacen"].'</td>
	<td>'.$datos["producto"].'</td>
	<td>'.$datos["serial"].'</td>
	<td>'.$datos["stock_actual"].'</td>
	<td>'.$datos["stock_reservado"].'</td>
	<td>'.$datos["importe"].'</td>
	<td>'.$datos["cos_prom_actual"].'</td>
	</tr>';
}
?>