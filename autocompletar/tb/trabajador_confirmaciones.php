<?php
include_once('../../funciones/funciones.php');
require("../../autentificacion/aut_config.inc.php");
require_once("../../" . class_bd);
$bd = new DataBase();
$typing     = $_GET['q'];
$filtro     = $_GET['filtro'];
$where  = " ";
switch ($filtro) {
	case "codigo":
		$where  .= " WHERE LOCATE('$typing', v_ficha.cod_ficha) ";
		break;
	case "cedula":
		$where  .= " WHERE LOCATE('$typing', v_ficha.cedula) ";
		break;
	case "trabajador":
		$where  .= " WHERE LOCATE('$typing', v_ficha.ap_nombre) ";
		break;
	case "nombres":
		$where  .= " WHERE LOCATE('$typing', v_ficha.nombres) ";
		break;
	case "apellidos":
		$where  .= " WHERE LOCATE('$typing', v_ficha.apellidos) ";
		break;
	case "telefono":
		$where  .= " WHERE LOCATE(REPLACE(REPLACE('".$typing."', '-', ''), ' ', ''), REPLACE(REPLACE(v_ficha.telefono , '-', ''), ' ', ''))";
		break;
	case "TODOS":
		$where  .= " WHERE LOCATE('$typing', v_ficha.cod_ficha) OR LOCATE('$typing', v_ficha.ap_nombre) OR LOCATE('$typing', v_ficha.cedula) ";
		break;
}

$sql = "SELECT v_ficha.cod_ficha, v_ficha.cedula,  v_ficha.ap_nombre FROM v_ficha $where AND (
	v_ficha.cod_cargo NOT IN ('ARLAT', '063', 'CES', '071', '100', 'ASLL')) ORDER BY 3 ASC";
$query = $bd->consultar($sql);

while ($datos = $bd->obtener_fila($query, 0)) {

	$descripcion = "" . $datos[0] . " - (" . $datos[1] . ")&nbsp;" . $datos[2];
	$codigo      = $datos[0];
?>

	<li onselect=" this.setText('<?php echo $descripcion; ?>').setValue('<?php echo  $codigo; ?>') ">
		<b></b> <?php echo $descripcion; ?>

	</li>
<?php } ?>