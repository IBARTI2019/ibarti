<?php
define("SPECIALCONSTANT",true);
$Nmenu   = 576;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

if(($_POST['fecha_desde'] == "")){
	exit;
}
$reporte      = $_POST['reporte'];
$archivo      = "rp_inv_dotacion_proyeccion_".$fecha."";
$titulo       = "  PROYECCION DE DOTACION DE TRABAJADOR \n";

$rol          = $_POST['rol'];
$d_proyeccion = $_POST['d_proyeccion'];
$estado       = $_POST['estado'];
$contrato     = $_POST['contrato'];
$linea        = $_POST['linea'];
$sub_linea    = $_POST['sub_linea'];
$cliente    = $_POST['cliente'];
$ubicacion    = $_POST['ubicacion'];
$fecha_desde  = $_POST['fecha_desde'];
$trabajador   = $_POST['trabajador'];
$fecha_D   = conversion($_POST['fecha_desde']);

$where = " ";

if($linea != "TODOS"){
	$where .= " AND prod_lineas.codigo = '$linea' ";
}

if($sub_linea != "TODOS"){
	$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
}

if($trabajador != NULL){
	$where  .= " AND ficha.cod_ficha = '$trabajador' ";
}

if($rol != "TODOS"){
	$where .= " AND ficha.cod_rol = '$rol' ";
}

if($cliente != "TODOS"){
	$where  .= " AND clientes_ubicacion.cod_cliente = '$cliente' ";
}


if($ubicacion != "TODOS"){
	$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' AND clientes_ub_uniforme.cod_cl_ubicacion = '$ubicacion'";
}

if($estado != "TODOS"){
	$where .= " AND clientes_ubicacion.cod_estado = '$estado' ";
}

if($contrato != "TODOS"){
	  $where .= " AND contractos.codigo = '$contrato' ";
}

$sql = "SELECT
max( `prod_dotacion`.`fec_dotacion` ) fecha,
estados.descripcion estado,
clientes.codigo cod_cliente,
clientes.nombre cliente,
clientes_ub_uniforme.cod_cl_ubicacion cod_ubicacion,
clientes_ubicacion.descripcion ubicacion,
contractos.descripcion AS contrato,
cargos.descripcion cargo,
ficha.cod_ficha,
ficha.cedula,
CONCAT( ficha.apellidos, ' ', ficha.nombres ) ap_nombre,
prod_lineas.codigo cod_linea,
prod_lineas.descripcion AS linea,
clientes_ub_uniforme.cod_sub_linea,
prod_sub_lineas.descripcion AS sub_linea,
IFNULL(productos.item, prod_sub_lineas.codigo ) cod_producto,
CONCAT(productos.descripcion, ' ', IFNULL(tallas.descripcion, '' ) ) producto,
SUM(
	IFNULL(
		(
		SELECT
			sum( `pdd`.`cantidad` ) 
		FROM
			prod_dotacion_det pdd 
		WHERE
			pdd.cod_sub_linea = prod_dotacion_det.cod_sub_linea 
			AND pdd.cod_dotacion = prod_dotacion.codigo 
			AND pdd.cod_sub_linea = clientes_ub_uniforme.cod_sub_linea 
		HAVING
		IF
			(
				clientes_ub_uniforme.vencimiento = 'T',
				DATE_ADD( DATE_FORMAT( IFNULL( prod_dotacion.fec_dotacion, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL clientes_ub_uniforme.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
				DATE_ADD( DATE_FORMAT( IFNULL( prod_dotacion.fec_dotacion, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
			) = 0 
		),
		0 
	) 
) cantidad,
clientes_ub_uniforme.cantidad alcance,
IF
(
	clientes_ub_uniforme.vencimiento = 'T',
	DATE_ADD( DATE_FORMAT( max( `prod_dotacion`.`fec_dotacion` ), '%Y-%m-%d' ), INTERVAL clientes_ub_uniforme.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
	DATE_ADD( DATE_FORMAT( max( `prod_dotacion`.`fec_dotacion` ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
) vencido,
prod_dotacion_det.cantidad cantidad_dot 
FROM
clientes_ub_uniforme
INNER JOIN control ON control.oesvica = control.oesvica
INNER JOIN prod_sub_lineas ON clientes_ub_uniforme.cod_sub_linea = prod_sub_lineas.codigo
INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
INNER JOIN ficha ON ficha.cod_cargo = clientes_ub_uniforme.cod_cargo 
AND ficha.cod_ficha_status = control.ficha_activo 
AND ficha.cod_ubicacion = clientes_ub_uniforme.cod_cl_ubicacion
INNER JOIN cargos ON cargos.codigo = clientes_ub_uniforme.cod_cargo AND  cargos.codigo = ficha.cod_cargo
INNER JOIN trab_roles ON trab_roles.cod_ficha = ficha.cod_ficha
INNER JOIN roles ON trab_roles.cod_rol = roles.codigo
INNER JOIN `prod_dotacion` ON `prod_dotacion`.`status` = 'T' 
AND `prod_dotacion`.`anulado` = 'F' 
AND prod_dotacion.cod_ficha = ficha.cod_ficha
LEFT JOIN `prod_dotacion_det` ON `prod_dotacion`.`codigo` = `prod_dotacion_det`.`cod_dotacion` 
AND prod_dotacion_det.cod_sub_linea = clientes_ub_uniforme.cod_sub_linea
AND prod_dotacion_det.cod_dotacion IN (SELECT MAX( pdd.cod_dotacion ) cod_dotacion FROM prod_dotacion pd, prod_dotacion_det pdd WHERE pd.codigo = pdd.cod_dotacion AND pd.cod_ficha = ficha.cod_ficha GROUP BY pdd.cod_sub_linea)
INNER JOIN `productos` ON `productos`.`item` = `prod_dotacion_det`.`cod_producto`
LEFT JOIN `tallas` ON `productos`.`cod_talla` = `tallas`.`codigo`
INNER JOIN contractos ON ficha.cod_contracto = contractos.codigo
INNER JOIN clientes_ubicacion ON clientes_ub_uniforme.cod_cl_ubicacion = clientes_ubicacion.codigo 
AND ficha.cod_ubicacion = clientes_ubicacion.codigo
INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente 
WHERE control.oesvica = control.oesvica 
".$where."
GROUP BY
cod_ficha,
cod_linea,
cod_sub_linea 
HAVING
( vencido = 1 ) 
OR ( vencido = 0 AND cantidad < alcance AND cantidad > 0 ) 
UNION
	SELECT
	'SIN DOTAR' fecha,
	estados.descripcion estado,
	clientes.codigo cod_cliente,
	clientes.nombre cliente,
	clientes_ub_uniforme.cod_cl_ubicacion cod_ubicacion,
	clientes_ubicacion.descripcion ubicacion,
	contractos.descripcion AS contrato,
	cargos.descripcion cargo,
	ficha.cod_ficha,
	ficha.cedula,
	CONCAT( ficha.apellidos, ' ', ficha.nombres ) ap_nombre,
	prod_lineas.codigo cod_linea,
	prod_lineas.descripcion AS linea,
	clientes_ub_uniforme.cod_sub_linea,
	prod_sub_lineas.descripcion AS sub_linea,
	prod_sub_lineas.codigo cod_producto,
	prod_sub_lineas.descripcion producto,
	0 cantidad,
	clientes_ub_uniforme.cantidad alcance,
	1 vencido,
	0 cantidad_dot 
FROM
	clientes_ub_uniforme
	INNER JOIN control ON control.oesvica = control.oesvica
	INNER JOIN prod_sub_lineas ON clientes_ub_uniforme.cod_sub_linea = prod_sub_lineas.codigo
	INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
	INNER JOIN ficha ON ficha.cod_cargo = clientes_ub_uniforme.cod_cargo 
	AND ficha.cod_ficha_status = control.ficha_activo 
	AND ficha.cod_ubicacion = clientes_ub_uniforme.cod_cl_ubicacion
	INNER JOIN cargos ON cargos.codigo = clientes_ub_uniforme.cod_cargo AND  cargos.codigo = ficha.cod_cargo
	INNER JOIN trab_roles ON trab_roles.cod_ficha = ficha.cod_ficha
	INNER JOIN roles ON trab_roles.cod_rol = roles.codigo
	INNER JOIN contractos ON ficha.cod_contracto = contractos.codigo
	INNER JOIN clientes_ubicacion ON clientes_ub_uniforme.cod_cl_ubicacion = clientes_ubicacion.codigo 
	AND ficha.cod_ubicacion = clientes_ubicacion.codigo
	INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
	INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente 
WHERE
	clientes_ub_uniforme.cod_sub_linea NOT IN (
	SELECT
		prod_dotacion_det.cod_sub_linea 
	FROM
		prod_dotacion,
		prod_dotacion_det 
	WHERE
		prod_dotacion.codigo = prod_dotacion_det.cod_dotacion 
		AND prod_dotacion.cod_ficha = ficha.cod_ficha 
		AND prod_dotacion_det.cod_sub_linea = clientes_ub_uniforme.cod_sub_linea 
	) 
	".$where."
ORDER BY
fecha ASC, ap_nombre ASC, producto ASC
";


	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		echo "<table border=1>";

		echo "<tr><th> Fecha </th><th> ".$leng['estado']." </th><th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th>
		<th>".$leng['contrato']."</th> <th>Cargo</th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th>
		<th> Linea </th><th> Sub Linea </th><th> Cod. Producto</th> <th> Producto </th>
		<th> Cantidad </th><th> Alcance </th><th> Cant. A Dotar </th><th>Vencido</th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
			$vencido = "NO";
			if($row01[19] == 1){
				$vencido = "SI";
			}
			echo "<tr><td>".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[3]."</td><td>".$row01[5]."</td>
			<td>".$row01[6]."</td><td>".$row01[7]."</td><td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td>
			<td>".$row01[12]."</td><td>".$row01[14]."</td><td>".$row01[15]."</td><td>".$row01[16]."</td>
			<td>".$row01[17]."</td><td>".$row01[18]."</td><td>".($row01[18] - $row01[17])."</td><td>".$vencido."</td></tr>";
		}
		echo "</table>";
	}

	if($reporte == 'pdf'){

		require_once('../'.ConfigDomPdf);
		$dompdf= new DOMPDF();

		$query  = $bd->consultar($sql);

		ob_start();

		require('../'.PlantillaDOM.'/header_ibarti_2.php');
		include('../'.pagDomPdf.'/paginacion_ibarti.php');

		echo "<br><div>
		<table>
		<tbody>
		<tr style='background-color: #4CAF50;'>
		<th width='12%'>Fecha</th>
		<th width='20%'>".$leng['cliente']."</th>
		<th width='10%'>".$leng['ficha']."</th>
		<th width='20%'>".$leng['trabajador']."</th>
		<th width='18%'>Producto</th>
		<th width='5%''>Cant.</th>
		<th width='5%'>Alc.</th>
		<th width='5%'>Dotar</th>
		</tr>";

		$f=0;
		while ($row = $bd->obtener_num($query)){
			if ($f%2==0){
				echo "<tr>";
			}else{
				echo "<tr class='class= odd_row'>";
			}
			echo   " <td width='12%'>".$row[0]."</td>
			<td width='20%'>".$row[3]."</td>
			<td width='10%'>".$row[8]."</td>
			<td width='20%'>".$row[10]."</td>
			<td width='18%'>".$row[16]."</td>
			<td width='5%'>".$row[17]."</td>
			<td width='5%'>".$row[18]."</td>
			<td width='5%'>".($row[18] - $row[17])."</td></tr>";

			$f++;
		}

		echo "</tbody>
		</table>
		</div>
		</body>
		</html>";

		$dompdf->load_html(ob_get_clean(),'UTF-8');
		$dompdf->set_paper('letter','landscape');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
	}