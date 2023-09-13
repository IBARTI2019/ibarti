	
<?php

require "../modelo/clientes_rp_modelo.php";
$cliente = $_POST['cliente'];
$estado = isset($_POST['estado'])?$_POST['estado']:'TODOS';
$ciudad = isset($_POST['ciudad'])?$_POST['ciudad']:'TODOS';


	$region      = new clientes_reporte;
	$datos = $region->llenar_ubicacion($cliente,$estado,$ciudad);
	echo json_encode($datos);
	


?>

