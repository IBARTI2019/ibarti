<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../" . class_bd;
require "../" . Leng;
$bd = new DataBase();

//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);

$Nmenu      = $_POST['Nmenu'];
$mod        = $_POST['mod'];
$archivo    = $_POST['archivo'] . "&Nmenu=$Nmenu&mod=$mod";
$vinculo    = "inicio.php?area=formularios/Add_$archivo";

$codigo       = $_POST['codigo'];
$clasif     = $_POST['clasif'];
$tipo       = $_POST['tipo'];
$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$status     = $_POST['status'];
$perfil     = $_POST['perfil'];

$metodo      = "modificar";

$where = " WHERE nov_check_list.cod_nov_clasif = nov_clasif.codigo
				AND nov_clasif.campo04 = 'T'
				AND nov_check_list.cod_nov_tipo   = nov_tipo.codigo
				AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                AND nov_perfiles.cod_perfil = '$perfil'
                AND nov_check_list.cod_cliente = clientes.codigo
                AND nov_check_list.cod_ubicacion = clientes_ubicacion.codigo
                AND nov_check_list.cod_ficha = ficha.cod_ficha
                AND nov_check_list.cod_nov_status = nov_status.codigo";

if ($codigo != "" && $codigo != null) {
	$where .= " AND nov_check_list.codigo = '$codigo' ";
}

if ($fecha_D != '0000-00-00' && $fecha_H != '0000-00-00') {
	$where .= " AND nov_check_list.fec_us_ing BETWEEN \"$fecha_D\" AND \"$fecha_H\"";
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
                      CONCAT(ficha.apellidos, ' ', ficha.nombres) AS trabajador,
                      nov_clasif.descripcion AS clasif, nov_tipo.descripcion AS tipo,
					  clientes.nombre AS cliente,
                      clientes_ubicacion.descripcion AS ubicacion, nov_status.descripcion AS `status`
                 FROM nov_check_list , nov_clasif , clientes , clientes_ubicacion ,
                      ficha , nov_status , nov_perfiles , nov_tipo
               $where
                  ORDER BY 2 DESC ";
$query = $bd->consultar($sql);
?><table width="100%" align="center">

	<tr class="fondo00">
		<th width="7%" class="etiqueta">Codigo</th>
		<th width="7%" class="etiqueta">Fecha</th>
		<th width="18%" class="etiqueta">Clasificacion</th>
		<th width="18%" class="etiqueta">Tipo</th>
		<th width="18%" class="etiqueta"><?php echo $leng["cliente"]; ?></th>
		<th width="18%" class="etiqueta"><?php echo $leng["ubicacion"]; ?></th>
		<th width="8%" class="etiqueta">Status</th>
		<th width="6%" align="center"><a href="<?php echo $vinculo . "&codigo=''&metodo=agregar"; ?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null" /></a></td>
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

		// $Modificar = "Add_Mod01('".$datos[0]."', 'modificar')";
		//</a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/>

		$Borrar = "Borrar01('" . $datos[0] . "')";

		echo '<tr class="' . $fondo . '">
                  <td class="texo">' . $datos["codigo"] . '</td>
				  <td class="texo">' . $datos["fec_us_ing"] . '</td>
				  <td class="texo">' . longitudMin($datos["clasif"]) . '</td>
  				  <td class="texo">' . longitudMin($datos["tipo"]) . '</td>
                  <td class="texo">' . longitudMin($datos["cliente"]) . '</td>
				  <td class="texo">' . longitudMin($datos["ubicacion"]) . '</td>
				  <td class="texo">' . longitudMin($datos["status"]) . '</td>
				  <td align="center"><a href="' . $vinculo . '&codigo=' . $datos[0] . '&metodo=' . $metodo . '"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="' . $Borrar . '" class="imgLink"/></td>
            </tr>';
	};
	mysql_free_result($query); ?>
</table>