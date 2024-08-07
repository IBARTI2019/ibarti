<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();
$codigo      = $_POST['codigo'];
$tamano      = $_POST['tamano'];
$activar     = $_POST['activar'];

if ($activar == "T") {
	$change =  'onchange="Add_filtroX()"';
} else if ($activar == "P") {
	$change = 'onchange="Add_Ub_puesto(this.value, \'contenido_puesto\', \'120\')"';
} else {
	$change =  'onchange="Validar01(this.value)"';
}

$sql = "SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
FROM clientes_ubicacion 
WHERE clientes_ubicacion.cod_cliente = '$codigo' 
AND clientes_ubicacion.`status` = 'T'
ORDER BY 2 ASC";

$query = $bd->consultar($sql);
echo '<select name="ubicacion" id="ubicacion" style="width:' . $tamano . 'px" ' . $change . ' required >
<option value="TODOS">TODOS</option>';
while ($row02 = $bd->obtener_fila($query, 0)) {
	echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
}
echo '</select>';
mysql_free_result($query);
