<?php
require "../modelo/pasoventa_modelo.php";
require "../../../../" . Leng;
$cod_ruta = $_POST['cod_ruta'];
$pasoventa = new Pasoventa;
$sub_rutas = $pasoventa->get_sub_rutas($cod_ruta);

foreach ($sub_rutas as  $datos) {
	echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
}
?>
