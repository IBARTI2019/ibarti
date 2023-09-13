
<?php

require "../modelo/clientes_rp_modelo.php";
$estado = $_POST['estado'];
$region      = new clientes_reporte;
$datos = $region->llenar_ciudades($estado);

echo '
<option value="TODOS">TODOS</option>';
foreach ($datos as  $dato)
{
	echo '<option value="'.$dato[0].'">'.$dato[1].'</option>';
}


?>

