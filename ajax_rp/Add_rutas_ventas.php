<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

$precliente         = $_POST['precliente'];
$ruta           = $_POST['ruta'];
$sub_ruta           = $_POST['sub_ruta'];

$where = "WHERE precliente_rutaventa.cod_precliente = preclientes.codigo 
AND precliente_rutaventa.cod_subrutaventa = subruta_de_ventas.codigo 
AND subruta_de_ventas.codigo = ruta_de_ventas.codigo ";

if($precliente != "TODOS"){	
	$where .= " AND preclientes.codigo = '$precliente' ";
}

if($ruta != "TODOS"){
	$where  .= " AND ruta_de_ventas.codigo = $ruta ";
}

if($sub_ruta != "TODOS"){
	$where  .= " AND subruta_de_ventas.codigo = $sub_ruta ";
}

// QUERY A MOSTRAR //
$sql = " SELECT
			precliente_rutaventa.codigo,
			precliente_rutaventa.fec_us_ing,
			precliente_rutaventa.cod_precliente,
			preclientes.abrev abrev_precliente,
			preclientes.nombre precliente,
			ruta_de_ventas.codigo cod_rutaventa,
			ruta_de_ventas.descripcion rutaventa,
			precliente_rutaventa.cod_subrutaventa,
			subruta_de_ventas.descripcion subrutaventa,
			precliente_rutaventa.comentario 
		FROM
			precliente_rutaventa,
			preclientes,
			ruta_de_ventas,
			subruta_de_ventas 
		$where
		ORDER BY 1 ASC ";
?>
<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="10%" class="etiqueta">Código </th>
            <th width="10%" class="etiqueta">Fecha </th>
            <th width="20%" class="etiqueta"><?php echo $leng['precliente']?></th>
            <th width="20%" class="etiqueta">Ruta de Venta</th>
            <th width="20%" class="etiqueta">Sub Ruta de Venta</th>
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
		   			<td class="texto">'.$datos["codigo"].'</td>
				  	<td class="texto">'.$datos["fec_us_ing"].'</td>
				  	<td class="texto">'.$datos["precliente"].'</td>
				  	<td class="texto">'.$datos["rutaventa"].'</td>
				  	<td class="texto">'.$datos["subrutaventa"].'</td>
				</tr>';
        };?>
    </table>
