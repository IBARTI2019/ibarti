
<?php

require "../modelo/clientes_rp_modelo.php";

$region      = new clientes_reporte;
$datos = $region->llenar_region();

echo '
<option value="TODOS">TODOS</option>';
foreach ($datos as  $dato)
{
	echo '<option value="'.$dato[0].'">'.$dato[1].'</option>';
}

?>

