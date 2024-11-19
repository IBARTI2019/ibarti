<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$codigo   = $_POST['codigo'];
$relacion = $_POST['relacion'];

$sql = "SELECT conceptos.codigo, conceptos.abrev, conceptos.descripcion FROM conceptos, horarios WHERE horarios.cod_concepto = conceptos.codigo GROUP BY conceptos.codigo AND conceptos.`status` = 'T';";
$query = $bd->consultar($sql);

echo'<select name="sub_linea_'.$relacion.'" id="sub_linea_'.$relacion.'" style="width:200px" 
>
<option value="">Seleccione...</option>';
while($datos=$bd->obtener_fila($query,0)){
	echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
}
echo'</select>';
mysql_free_result($query);
?>
