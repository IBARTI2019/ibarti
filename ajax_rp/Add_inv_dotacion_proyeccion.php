<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

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
OR ( vencido = 0 AND cantidad < alcance ) 
UNION
	SELECT
	'SIN DOTAR' fecha,
	estados.descripcion estado,
	clientes.codigo cod_cliente,
	clientes.nombre cliente,
	clientes_ub_uniforme.cod_cl_ubicacion cod_ubicacion,
	clientes_ubicacion.descripcion ubicacion,
	contractos.descripcion AS contrato,
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

//  echo $sql;
?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
  			<th width="10%" class="etiqueta">Fecha Ult. Dot.</th>
            <th width="10%" class="etiqueta"><?php echo $leng['ficha']?></th>
			<th width="15%" class="etiqueta"><?php echo $leng['cliente']?></th>
			<th width="15%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
            <th width="15%" class="etiqueta">Sub Linea</th>
            <th width="20%" class="etiqueta">Producto </th>
            <th width="5%" class="etiqueta">Cant.</th>
			<th width="5%" class="etiqueta">Alc.</th>
			<th width="5%" class="etiqueta">Dotar</th>
			<th width="5%" class="etiqueta">Vencido</th>	
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
		$vencido = "NO";
		if($datos["vencido"] == 1){
			$vencido = "SI";
		}
        echo '<tr class="'.$fondo.'">
			      <td class="texto">'.$datos["fecha"].'</td>
				  <td class="texto">'.$datos["cod_ficha"].'</td>
				  <td class="texto">'.longitud($datos["cliente"]).'</td>
				  <td class="texto">'.longitud($datos["ubicacion"]).'</td>
				  <td class="texto">'.longitud($datos["sub_linea"]).'</td>
				  <td class="texto">'.longitudMax($datos["producto"]).'</td>
				  <td class="texto">'.$datos["cantidad"].'</td>
				  <td class="texto">'.$datos["alcance"].'</td>
				  <td class="texto">'.($datos["alcance"] - $datos["cantidad"]).'</td>
				  <td class="texto">'.$vencido. '</td>
           </tr>';
        };?>
    </table>
