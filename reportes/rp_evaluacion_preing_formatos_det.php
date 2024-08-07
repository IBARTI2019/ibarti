<?php
define("SPECIALCONSTANT", true);
session_start();
$Nmenu   = 731;
require("../autentificacion/aut_config.inc.php");
include_once('../' . Funcion);
require_once("../" . class_bdI);
require_once("../" . Leng);
$bd = new DataBase();

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$tipo       = $_POST['tipo'];
$clasif     = $_POST['clasif'];
$reporte         = $_POST['reporte'];
$archivo         = "rp_nov_check_list_eval";
$titulo          = " REPORTE DE FORMATOS EVALUACION CHECKLIST \n";

if (isset($reporte)) {

	$where = " WHERE
	novedades.cod_nov_tipo = nov_tipo.codigo 
	AND novedades.cod_nov_clasif = nov_clasif.codigo 
	AND nov_clasif.campo04 = 'P' 
	AND novedades.`status` = 'T' 
	AND novedades.codigo = nov_valores_det.cod_novedades 
	AND nov_valores.codigo = nov_valores_det.cod_valores 
	AND nov_valores.cod_clasif_val = nov_valores_clasif.codigo ";

	if ($clasif != "TODOS" && $clasif != "") {
		$where .= " AND nov_clasif.codigo = '$clasif' ";
	}

	if ($tipo != "TODOS" && $tipo != "") {
		$where .= " AND nov_tipo.codigo  = '$tipo' ";
	}


	// QUERY A MOSTRAR //

	$sql = "SELECT
        novedades.codigo,
        nov_clasif.codigo cod_clasificacion,
        nov_clasif.descripcion clasificacion,
        nov_tipo.codigo cod_tipo,
        nov_tipo.descripcion tipo,
        novedades.descripcion,
        nov_valores_det.valor,
        nov_valores.descripcion descricion_valor,
        nov_valores_clasif.descripcion clasif_valor,
        novedades.valor_maximo
    FROM
        novedades,
        nov_clasif,
        nov_tipo,
        nov_valores_det,
        nov_valores,
        nov_valores_clasif 
    $where
    ORDER BY nov_clasif.codigo, nov_tipo.codigo, novedades.codigo
    ";

	if ($reporte == 'excel') {
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");


		$mostrar = '
			<table width="100%" align="center">
				<tr class="fondo00">
					<th  class="etiqueta">Codigo</th>
					<th  class="etiqueta">Cod. Clasificación</th>
					<th  class="etiqueta">Clasificacion</th>
					<th  class="etiqueta">Cod. Tipo</th>
					<th  class="etiqueta">Tipo</th>
					<th  class="etiqueta">Descripción</th>
					<th  class="etiqueta">Valor</th>
                    <th  class="etiqueta">Descripción Valor</th>
					<th  class="etiqueta">Clasif. Valor</th>
					<th  class="etiqueta">Valor Maximo</th>
			</tr>';
		$query01  = $bd->consultar($sql);

		//echo '<tr><td>'.json_encode($query01).'</td></tr>';
		while ($datos = $bd->obtener_fila($query01, 0)) {
			$mostrar .= '<tr>
					<td>' . $datos[0] . '</td>
					<td>' . $datos[1] . '</td>
					<td>' . $datos[2] . '</td>
					<td>' . $datos[3] . '</td>
					<td>' . $datos[4] . '</td>
					<td>' . $datos[5] . '</td>
					<td>' . $datos[6] . '</td>
                    <td>' . $datos[7] . '</td>
                    <td>' . $datos[8] . '</td>
                    <td>' . $datos[9] . '</td>
					</tr>';
		};
		echo $mostrar;
	}

}
