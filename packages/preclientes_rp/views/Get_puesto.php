<?php

require "../modelo/clientes_rp_modelo.php";
$cliente = $_POST['cliente'];
$ubicacion = $_POST['ubicacion'];
$region      = new clientes_reporte;
echo '<option value="TODOS">TODOS</option>';
$datos = $region->llenar_puesto($cliente,$ubicacion);
if($cliente != 'TODOS' && $ubicacion!='TODOS'){

	foreach ($datos as  $dato)
	{
		echo '<option value="'.$dato[0].'">'.$dato[1].'</option>';
	}

}

?>
