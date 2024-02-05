<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php'); 
//include_once('../funciones/mensaje_error.php');

$clasif    = $_POST['clasif'];
$tipo      = $_POST['tipo'];
$ubicacion = $_POST['ubicacion'];

if ($tipo != "") {
	$sql = "SELECT campo03,campo04 from nov_tipo where codigo='" . $tipo . "'";
	$query = $bd->consultar($sql);
	while ($datos = $bd->obtener_fila($query, 0)) {
		if ($datos['campo03'] != "") {
			echo '<div style="border:1px solid;"><label aling="left">Datos Adicionales:</label><br><table width="100%"><tr><td width="5%">' . $datos["campo03"] . ':</td><td width="10%"><input type="text" name="campo03"></td><td width="85%"></td></tr></table></div>';
		} else {
			echo '';
		}
	}
}

$where = " WHERE nov_cl_ubicacion.cod_cl_ubicacion = '$ubicacion' 
AND nov_cl_ubicacion.cod_novedad = novedades.codigo
AND novedades.`status` = 'T'
AND novedades.cod_nov_clasif = '$clasif' 
AND novedades.cod_nov_tipo = '$tipo' ";

$sql   = " SELECT novedades.codigo, novedades.descripcion, novedades.cod_nov_agrupacion
FROM nov_cl_ubicacion, novedades                      
$where
ORDER BY  novedades.orden, 2 ASC ";

?><table width="100%" align="center">
	<tr>
		<td class="etiqueta" width="45%">Check List:</td>
		<td class="etiqueta" width="15%">Valor:</td>
		<td class="etiqueta" width="40%">Observacion:</td>
	</tr>
	<?php

	$query = $bd->consultar($sql);
	while ($datos = $bd->obtener_fila($query, 0)) {
		$cod_c = $datos[0];

		$sql02 = " SELECT nov_valores.codigo, nov_valores.abrev ,nov_valores.descripcion
                     FROM nov_valores_det , nov_valores
                    WHERE nov_valores_det.cod_novedades = '$cod_c'
                      AND nov_valores_det.cod_valores = nov_valores.codigo
                 ORDER BY 1 ASC ";
		$query02 = $bd->consultar($sql02);

		echo '<tr>
      <td><textarea disabled="disabled" cols="60">' . $datos[1] . '</textarea></td>
	  <td>';
		while ($datos02 = $bd->obtener_fila($query02, 0)) {
			echo ' ' . $datos02[1] . ' <input type = "radio"  name="check_list_valor_' . $cod_c . '" value ="' . $datos02[0] . '" style="width:auto" title="' . $datos02[2] . '" />';
		}
		echo '<input type="hidden" name="cod_agrupacion_' . $cod_c  . '" value="' . $datos[2] . '" /><input type="hidden" name="cod_valor_' . $cod_c . '" value="' . $datos[0] . '" /><input type="hidden" name="check_list[]" value="' . $datos[0] . '" /> </td>
      <td><textarea  name="observacion_' . $datos[0] . '" cols="50" rows="1"></textarea>
    </tr>';
	}
	mysql_free_result($query); ?>
</table>