
<?php

require "../modelo/clientes_rp_modelo.php";
$regiona = $_POST['region'];
$region = new clientes_reporte;
$datos  = $region->llenar_cliente($regiona);

echo '<option value="TODOS">TODOS</option>';
foreach ($datos as  $dato)
{
	echo '<option value="'.$dato[0].'">'.$dato[1].'</option>';
}



?>

