<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../" . class_bd;
require "../" . Leng;
$bd = new DataBase();

$region          = $_POST['region'];
$d_proyeccion = $_POST['d_proyeccion'];
$estado       = $_POST['estado'];
$linea        = $_POST['linea'];
$sub_linea    = $_POST['sub_linea'];
$cliente    = $_POST['cliente'];
$ubicacion    = $_POST['ubicacion'];
$producto    = $_POST['producto'];
$fecha_desde  = $_POST['fecha_desde'];
$fecha_D   = conversion($_POST['fecha_desde']);

$where = " ";

if($linea != "TODOS"){
	$where .= " AND prod_lineas.codigo = '$linea' ";
}

if($sub_linea != "TODOS"){
	$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
}

if($producto != NULL){
	$where  .= " AND productos.item = '$producto' ";
}

if($region != "TODOS"){
	$where .= " AND clientes.cod_region = '$region' ";
}

if($estado != "TODOS"){
	$where .= " AND clientes_ubicacion.cod_estado = '$estado' ";
}

if($cliente != "TODOS"){
	$where  .= " AND clientes_ubicacion.cod_cliente = '$cliente' ";
}

if($ubicacion != "TODOS"){
	$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' AND clientes_ub_alcance.cod_cl_ubicacion = '$ubicacion'";
}


$sql = "SELECT
max( ajuste_alcance.fecha ) fecha,
estados.descripcion estado,
clientes.codigo cod_cliente,
clientes.nombre cliente,
clientes.abrev abrev_cliente,
clientes_ub_alcance.cod_cl_ubicacion cod_ubicacion,
clientes_ubicacion.descripcion ubicacion,
prod_lineas.codigo cod_linea,
prod_lineas.descripcion AS linea,
clientes_ub_alcance.cod_sub_linea,
prod_sub_lineas.descripcion AS sub_linea,
IFNULL( productos.item, prod_sub_lineas.codigo ) cod_producto,
CONCAT( productos.descripcion, ' ', IFNULL( tallas.descripcion, '' ) ) producto,
SUM(
	IFNULL(
		(
		SELECT
			sum( `pdd`.`cantidad` ) 
		FROM
			ajuste_alcance_reng pdd,
			productos sub_producto 
		WHERE
			pdd.cod_ajuste = ajuste_alcance.codigo 
			AND sub_producto.item = pdd.cod_producto 
			AND sub_producto.cod_sub_linea = clientes_ub_alcance.cod_sub_linea 
		HAVING
		IF
			(
				clientes_ub_alcance.vencimiento = 'T',
				DATE_ADD( DATE_FORMAT( IFNULL( ajuste_alcance.fecha, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL clientes_ub_alcance.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
				DATE_ADD( DATE_FORMAT( IFNULL( ajuste_alcance.fecha, '0001-01-01' ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
			) = 0
		),
		0 
	) 
) cantidad,
clientes_ub_alcance.cantidad alcance,
IF
(
	clientes_ub_alcance.vencimiento = 'T',
	DATE_ADD( DATE_FORMAT( max( ajuste_alcance.fecha ), '%Y-%m-%d' ), INTERVAL clientes_ub_alcance.dias DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ),
	DATE_ADD( DATE_FORMAT( max( ajuste_alcance.fecha ), '%Y-%m-%d' ), INTERVAL control.dias_proyeccion DAY ) < DATE_ADD( '$fecha_D', INTERVAL $d_proyeccion DAY ) 
) vencido,
ajuste_alcance_reng.cantidad cantidad_dot 
FROM
clientes_ub_alcance
INNER JOIN control ON control.oesvica = control.oesvica
INNER JOIN prod_sub_lineas ON clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
INNER JOIN `ajuste_alcance` ON `ajuste_alcance`.`anulado` = 'F' 
AND ajuste_alcance.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion
LEFT JOIN `ajuste_alcance_reng` ON `ajuste_alcance`.`codigo` = ajuste_alcance_reng.cod_ajuste
INNER JOIN `productos` ON `productos`.`item` = `ajuste_alcance_reng`.`cod_producto` 
AND productos.cod_sub_linea = clientes_ub_alcance.cod_sub_linea
LEFT JOIN `tallas` ON `productos`.`cod_talla` = `tallas`.`codigo`
INNER JOIN clientes_ubicacion ON clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo 
AND clientes_ubicacion.status = 'T'
INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente AND clientes.status = 'T'
WHERE
control.oesvica = control.oesvica 
".$where."
GROUP BY
cod_ubicacion,
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
	clientes.abrev abrev_cliente,
	clientes_ub_alcance.cod_cl_ubicacion cod_ubicacion,
	clientes_ubicacion.descripcion ubicacion,
	prod_lineas.codigo cod_linea,
	prod_lineas.descripcion AS linea,
	clientes_ub_alcance.cod_sub_linea,
	prod_sub_lineas.descripcion AS sub_linea,
	prod_sub_lineas.codigo cod_producto,
	prod_sub_lineas.descripcion producto,
	0 cantidad,
	clientes_ub_alcance.cantidad alcance,
	1 vencido,
	0 cantidad_dot 
FROM
	clientes_ub_alcance
	INNER JOIN control ON control.oesvica = control.oesvica
	INNER JOIN prod_sub_lineas ON clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
	INNER JOIN prod_lineas ON prod_lineas.codigo = prod_sub_lineas.cod_linea
	INNER JOIN clientes_ubicacion ON clientes_ub_alcance.cod_cl_ubicacion = clientes_ubicacion.codigo
	AND clientes_ubicacion.status = 'T' 
	INNER JOIN estados ON clientes_ubicacion.cod_estado = estados.codigo
	INNER JOIN clientes ON clientes.codigo = clientes_ubicacion.cod_cliente AND clientes.status = 'T'
WHERE
	clientes_ub_alcance.cod_sub_linea NOT IN (
	SELECT
		sub_producto.cod_sub_linea 
	FROM
		ajuste_alcance,
		ajuste_alcance_reng,
		productos sub_producto
	WHERE
		ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste 
		AND ajuste_alcance.cod_ubicacion = clientes_ub_alcance.cod_cl_ubicacion 
		AND sub_producto.item = ajuste_alcance_reng.cod_producto
		AND sub_producto.cod_sub_linea = clientes_ub_alcance.cod_sub_linea 
	) 
	".$where."
ORDER BY
fecha ASC, ubicacion ASC, producto ASC
";

//  echo $sql;
?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
  			<th width="10%" class="etiqueta">Fecha Ult. Dot.</th>
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
				  <td class="texto">'.longitud($datos["abrev_cliente"]).'</td>
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
