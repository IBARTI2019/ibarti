<?php
define("SPECIALCONSTANT", true);
require("../autentificacion/aut_config.inc.php");
include_once("../".Funcion);
require_once("../".class_bdI);
$bd       = new DataBase();
$codigo   = $_POST['codigo'];
$cod_det   = $_POST['cod_det'];
 $result = array();

  try {
	// $codigo aquí siempre es el calendario VAR que se está editando (el dropdown que
	// dispara esta consulta vía Feriado() está oculto para calendarios FIJO), así que
	// sus fechas mantienen el año real sin remapear. $cod_det, en cambio, siempre es el
	// calendario FIJO recién seleccionado en el dropdown — sus fechas se remapean al año
	// actual para que el picker las muestre marcadas sin importar en qué año fueron
	// guardadas originalmente (ver misma lógica/comentario en Add_nom_calendario_det.php).
	$sql = " SELECT DATE_FORMAT(nom_calendario_det.fecha, '%m/%d/%Y') fecha
	           FROM nom_calendario_det
			      WHERE nom_calendario_det.cod_calendario = '$codigo'
            UNION ALL
           SELECT DATE_FORMAT(
                    STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(nom_calendario_det.fecha), '-', DAY(nom_calendario_det.fecha)), '%Y-%m-%d'),
                    '%m/%d/%Y') fecha
           FROM nom_calendario_det
            WHERE nom_calendario_det.cod_calendario = '$cod_det'
            ORDER BY 1 ASC ";

   $query = $bd->consultar($sql);
	 $fecha_matris = '';

	 while($rows=$bd->obtener_name($query)){
			 $result[] = $rows;

	 }

} catch (Exception $e) {
		$error =  $e->getMessage();
		$result['error'] = true;
		$result['mensaje'] = $error;
//	$bd->log_error("Aplicacion", "pl_visitas.php",  "$usuario", "$error", "$sql");
		$bd->log_error("Aplicacion", "pl_visitas.php",  "usuario", "error", "$sql");
}

	print_r(json_encode($result));
	return json_encode($result);
		?>

<?php
/*
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd       = new DataBase();
$codigo   = $_POST['codigo'];	// estados
$i        = 0;
	$sql = "  SELECT estados.codigo, estados.descripcion, IFNULL(feriado_fecha_det.fecha, 'NO') AS existe
                FROM estados LEFT JOIN feriado_fecha_det ON '$codigo' = feriado_fecha_det.fecha
                 AND estados.codigo = feriado_fecha_det.cod_estado
			   ORDER BY 2 ASC";
   $query = $bd->consultar($sql);


	echo'<table width="90%" align="center">';

    while($row03=$bd->obtener_fila($query,0)){
	$i++;
		if($row03['existe'] != 'NO'){
			echo'
				<tr>
					<td class="etiqueta">'.$row03[1].'</td>
					<td><input type="checkbox" name="estado[]" value="'.$row03['codigo'].'" style="width:auto" checked="checked"/>
					</td>
			   </tr>';
		  }else{

			echo'
				<tr>
					<td class="etiqueta">'.$row03[1].'</td>
					<td><input type="checkbox" name="estado[]" value="'.$row03['codigo'].'" style="width:auto"/>
					</td>
			   </tr>';
		  }}
	echo'</table>';
	mysql_free_result($query);
*/
	?>
