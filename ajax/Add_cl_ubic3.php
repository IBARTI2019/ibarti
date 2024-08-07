<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bd);
$bd = new DataBase();
$codigo      = $_POST['codigo'];
$tamano      = $_POST['tamano'];
$activar     = $_POST['activar'];

if ($activar == "T") {
	$change =  'onchange="Add_filtroX()"';
} else if ($activar == "P") {
	$change = 'onchange="Add_Ub_puesto(this.value, \'contenido_puesto\', \'120\')"';
} else {
	$change =  'onchange="Validar01(this.value)"';
}

$sql = "SELECT
		CONCAT( productos.descripcion, ' (', productos.codigo, ') ' ) sub_linea,
		clientes_ub_alcance.cantidad,
		IFNULL(
			(
			SELECT
				CONCAT(ajuste_alcance.fecha, ' (', ajuste_alcance_reng.cantidad, ')' ) 
			FROM
				ajuste_alcance,
				ajuste_alcance_reng,
				productos
			WHERE
				ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste 
				AND ajuste_alcance_reng.cod_producto = productos.item
				AND clientes_ub_alcance.cod_sub_linea = productos.cod_sub_linea
				AND ajuste_alcance.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion 
				ORDER BY ajuste_alcance.codigo DESC
				LIMIT 1
			),
			'SIN DOTACION' 
		) ult_dotacion,
		clientes_ubicacion.cod_cliente,
		clientes_ubicacion.codigo 
	FROM
		clientes_ub_alcance
		INNER JOIN productos ON clientes_ub_alcance.cod_sub_linea = productos.cod_sub_linea,
		clientes_ubicacion 
	WHERE
		clientes_ub_alcance.cod_cl_ubicacion = '$codigo' 
		AND clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo 
	GROUP BY
		clientes_ub_alcance.cod_sub_linea";
		
$query = $bd->consultar($sql);
echo '<fieldset class="fieldset" id="datos_dotacion">';
echo '<legend>Configuracion Alcance: </legend>';
echo '<table width="100%" align="center" class="tabla_sistema">
								<thead>
									<tr>
										<th>Sub Linea</th>
										<th>Cantidad</th>
										<th>Ultima Dotación</th>
									</tr>
								</thead>';	
echo '<tbody id="datos_dotacion_detalle">';
									
									
    
while ($row02 = $bd->obtener_fila($query, 0)) {
	echo "<tr><td>" .$row02[0]."</td><td>"  .$row02[1]. "</td><td>"  .$row02[2]. "</td></tr>";
}
echo '</tbody>';
echo '</table>'; 
echo '</fieldset>'; 
mysql_free_result($query);