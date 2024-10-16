<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();
$codigo      = $_POST['codigo'];
$tamano      = $_POST['tamano'];
$activar     = $_POST['activar'];

$change = "";

if ($activar == "T") {
	$change =  'onchange="Add_filtroX()"';
}

$sql = "SELECT subruta_de_ventas.codigo, subruta_de_ventas.descripcion
FROM subruta_de_ventas 
WHERE subruta_de_ventas.cod_ruta = '$codigo' 
AND subruta_de_ventas.`status` = 'T'
ORDER BY 2 ASC;";

$query = $bd->consultar($sql);
echo '<select name="sub_ruta" id="sub_ruta" style="width:' . $tamano . 'px" ' . $change . ' required >
<option value="TODOS">TODOS</option>';
while ($row02 = $bd->obtener_fila($query, 0)) {
	echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
}
echo '</select>';
mysql_free_result($query);
