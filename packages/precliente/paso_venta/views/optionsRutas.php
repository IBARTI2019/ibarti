<?php
require "../modelo/pasoventa_modelo.php";
require "../../../../" . Leng;
$precliente = $_POST['precliente'];
$pasoventa = new Pasoventa;
$sub_rutas = $pasoventa->get_rutas($precliente);

echo '<option value="">Seleccione</option>';
foreach ($sub_rutas as  $datos) {
	echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
}
?>
