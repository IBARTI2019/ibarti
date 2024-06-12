
<?php
require "../modelo/contratacion_modelo.php";
require "../../../../".Leng;

$ubic = $_POST['codigo'];
$contratacion = new Contratacion;
$puesto_ubic     =  $contratacion->get_planificaciones($ubic);

	foreach ($puesto_ubic as  $datos) {
			echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
	}
?>