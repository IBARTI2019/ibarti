<?php   
include_once('../../funciones/funciones.php');
require("../../autentificacion/aut_config.inc.php");
require_once("../../".class_bd);
$bd = new DataBase();
$typing     = $_GET['q'];
$where  = "  ";

$sql = "SELECT productos.codigo, productos.descripcion, productos.item              
FROM productos 
WHERE productos.codigo = productos.codigo
AND LOCATE('$typing', productos.codigo)         
ORDER BY 2 DESC";
$query = $bd->consultar($sql);
while ($datos=$bd->obtener_fila($query,0)){

	$descripcion = "".$datos[1]." - (".$datos[2].")&nbsp;";
	$codigo      = $datos[2];
?>

	<li onselect=" this.setText('<?php echo $codigo;?>').setValue('<?php echo  $codigo;?>') ">
		<?php echo $descripcion;?>
	</li>
<?php 
	}
?>