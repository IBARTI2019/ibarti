<?php
include_once('../../funciones/funciones.php');
require("../../autentificacion/aut_config.inc.php");
require_once("../../" . class_bd);
$bd = new DataBase();
$typing     = $_GET['q'];
$ubic = $_GET['ubic'];
$where  = "  ";

$sql = "SELECT productos.codigo, productos.descripcion, productos.item              
FROM productos, clientes_ub_alcance 
WHERE productos.cod_sub_linea = clientes_ub_alcance.cod_sub_linea 
AND clientes_ub_alcance.cod_cl_ubicacion = $ubic
AND (LOCATE('$typing', productos.codigo) OR LOCATE('$typing', productos.descripcion))         
ORDER BY 2 DESC";

$query = $bd->consultar($sql);
while ($datos = $bd->obtener_fila($query, 0)) {

	$descripcion = "" . $datos[1] . " - (" . $datos[2] . ")&nbsp;";
	$codigo      = $datos[2];
?>

	<li onselect=" this.setText('<?php echo $descripcion; ?>').setValue('<?php echo  $codigo; ?>') ">
		<?php echo $descripcion; ?>
	</li>
<?php
}
?>