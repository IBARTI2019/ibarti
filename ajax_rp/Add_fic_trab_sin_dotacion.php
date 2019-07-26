<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');

$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$cliente         = $_POST['cliente'];
$contrato        = $_POST['contrato'];

$trabajador      = $_POST['trabajador'];


$where = " WHERE v_ficha.cod_ficha NOT IN  (SELECT v_prod_dot_max2.cod_ficha FROM v_prod_dot_max2)
AND v_ficha.cod_ficha_status = control.ficha_activo ";


if($rol != "TODOS"){
	$where .= " AND v_ficha.cod_rol = '$rol' ";
}

if($region != "TODOS"){
	$where .= " AND v_ficha.cod_region = '$region' ";
}
if($estado != "TODOS"){
	$where .= " AND v_ficha.cod_estado = '$estado' ";
}
if($contrato != "TODOS"){
	$where  .= " AND v_ficha.cod_contracto = '$contrato' ";
}
if($cliente != "TODOS"){
	$where .= " AND v_ficha.cod_cliente = '$cliente' ";
}

if($trabajador != NULL){
	$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
}

	// QUERY A MOSTRAR //
$sql = "SELECT  v_ficha.* FROM v_ficha, control 
$where
ORDER BY 3 DESC  ";

?>
<table width="100%" border="0" align="center">
	<tr class="fondo00">
		<th width="18%" class="etiqueta"><?php echo $leng['rol']?></th>
		<th width="18%" class="etiqueta"><?php echo $leng['estado']?></th>
		<th width="18%" class="etiqueta"><?php echo $leng['cliente']?> </th>
		<th width="18%" class="etiqueta"><?php echo $leng['ubicacion']?> </th>
		<th width="18%" class="etiqueta"><?php echo $leng['ficha']?> </th>
		<th width="18%" class="etiqueta"><?php echo $leng['trabajador']?></th>
	</tr>
	<?php
	$valor = 0;
	$query = $bd->consultar($sql);

	while ($datos=$bd->obtener_fila($query,0)){
		if ($valor == 0){
			$fondo = 'fondo01';
			$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo '<tr class="'.$fondo.'">
		<td class="texto">'.longitud($datos["rol"]).'</td>
		<td class="texto">'.longitud($datos["estado"]).'</td>
		<td class="texto">'.longitud($datos["cliente"]).'</td>
		<td class="texto">'.longitud($datos["ubicacion"]).'</td>
		<td class="texto">'.$datos["cod_ficha"].'</td>
		<td class="texto">'.longitud($datos["ap_nombre"]).'</td>
		</tr>';
	};?>
</table>
