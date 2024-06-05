<?php
require "../modelo/supervision_modelo.php";
require "../../../../" . Leng;
$cod_ruta = $_POST['cod_ruta'];
$actividades = new Supervision;
$actividadesfc = $actividades->get_fc_actividades($cod_ruta);

foreach ($actividadesfc as  $datos) {
	echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
}
?>
