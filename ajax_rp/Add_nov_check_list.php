<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../" . class_bd;
require "../" . Leng;
$bd = new DataBase();

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = $_POST['archivo'] . "&Nmenu=$Nmenu&mod=$mod";
$vinculo    = "reportes/rp_nov_check_list_printer.php?Nmenu=$Nmenu&mod=$mod";

$codigo       = $_POST['codigo'];
$tipo       = $_POST['tipo'];
$clasif     = $_POST['clasif'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$status     = $_POST['status'];

$where = " WHERE nov_check_list.cod_nov_clasif = nov_clasif.codigo
				AND nov_check_list.cod_nov_tipo = nov_tipo.codigo
                AND nov_check_list.cod_cliente = clientes.codigo
                AND nov_check_list.cod_ubicacion = clientes_ubicacion.codigo
                AND nov_check_list.cod_ficha = ficha.cod_ficha
                AND nov_check_list.cod_nov_status = nov_status.codigo ";

if ($fecha_D != '0000-00-00' && $fecha_H != '0000-00-00') {
	$where .= " AND nov_check_list.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"";
}

if ($codigo != "" && $codigo != null) {
	$where .= " AND nov_check_list.codigo = '$codigo' ";
}

if ($clasif != "TODOS") {
	$where .= " AND nov_clasif.codigo = '$clasif' ";
}

if ($tipo != "TODOS") {
	$where .= " AND nov_tipo.codigo = '$tipo' ";
}

if ($cliente != "TODOS") {
	$where .= " AND clientes.codigo = '$cliente' ";
}

if ($ubicacion != "TODOS") {
	$where .= " AND clientes_ubicacion.codigo  = '$ubicacion' ";
}

if ($status != "TODOS") {
	$where .= " AND nov_status.codigo = '$status' ";
}

$sql   = " SELECT nov_check_list.codigo, nov_check_list.fec_us_ing,
		                  nov_check_list.hora, nov_clasif.descripcion AS nov_clasif,
		                  clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
		                  nov_check_list.cod_ficha,ficha.cedula,
						  nov_check_list.repuesta, nov_check_list.fec_us_mod,
						  CONCAT(men_usuarios.apellido,' ',men_usuarios.nombre) AS us_mod,
						  CONCAT(ficha.apellidos, ' ', ficha.nombres) AS trabajador, nov_status.descripcion AS nov_status                     FROM nov_check_list LEFT JOIN men_usuarios ON nov_check_list.cod_us_mod = men_usuarios.codigo ,
			        nov_clasif , nov_tipo, clientes , clientes_ubicacion ,
					ficha, nov_status
					$where
                 ORDER BY 2 DESC ";
$query = $bd->consultar($sql);
?><table width="100%" align="center">
	<tr class="fondo00">
		<th width="7%" class="etiqueta">Codigo</th>
		<th width="8%" class="etiqueta">Fecha</th>
		<th width="22%" class="etiqueta">Clasificacion</th>
		<th width="22%" class="etiqueta"><?php echo $leng['cliente'] ?></th>
		<th width="22%" class="etiqueta"><?php echo $leng['ubicacion'] ?></th>
		<th width="15%" class="etiqueta">Status</th>
		<th width="4%" align="center">&nbsp;</td>
	</tr>
	<?php
	$valor = 0;
	while ($datos = $bd->obtener_fila($query, 0)) {
		if ($valor == 0) {
			$fondo = 'fondo01';
			$valor = 1;
		} else {
			$fondo = 'fondo02';
			$valor = 0;
		}

		$Borrar = "Borrar01('" . $datos[0] . "')";
		echo '<tr class="' . $fondo . '">
                  <td class="texo">' . $datos[0] . '</td>
                  <td class="texo">' . $datos[1] . '</td>
				  <td class="texo">' . longitud($datos[3]) . '</td>
                  <td class="texo">' . longitud($datos[4]) . '</td>
				  <td class="texo">' . longitud($datos[5]) . '</td>
				  <td class="texo">' . $datos[12] . '</td>
				  <td align="center"><a target="_blank" href="' . $vinculo . '&codigo=' . $datos[0] . '&metodo=printer"><img src="imagenes/printer.png" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a></td>
            </tr>';
	}; ?>
</table>