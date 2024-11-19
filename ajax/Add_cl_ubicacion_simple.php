<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();
$codigo      = $_POST['codigo'];

$sql = "SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
			  FROM clientes_ubicacion 
			 WHERE clientes_ubicacion.cod_cliente = '$codigo' 
			   AND clientes_ubicacion.`status` = 'T'
			 ORDER BY 2 ASC";

$query = $bd->consultar($sql);
echo '<select name="ubicacion" id="ubicacion" style="width:120px">
			     <option value="">Seleccione...</option>';
while ($row02 = $bd->obtener_fila($query, 0)) {
	echo '<option value="' . $row02[0] . '">' . $row02[1] . '</option>';
}
echo '</select>';
