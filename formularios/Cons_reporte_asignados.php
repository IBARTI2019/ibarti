<?php
	$Nmenu = '455'; 
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$bd = new DataBase();
	$titulo = " Reporte de Productos Asignados / Reservados";
?>
<div align="center" class="etiqueta_title"> <?php echo $titulo;?></div>
<div id="Contenedor01"></div>

<div id="listar" class="listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="20%" class="etiqueta">Ubicación</th>
			<th width="8%" class="etiqueta"><?php echo $leng['ficha'];?></th>
            <th width="20%" class="etiqueta"><?php echo $leng['trabajador'];?></th>
            <th width="20%" class="etiqueta">Producto</th>
            <th width="10%" class="etiqueta">Almacen</th>
            <th width="10%" class="etiqueta">Cantidad Reservada</th>
            <th width="12%" class="etiqueta">EANs Asignados</th>
		</tr>
    <?php
	$valor = 0;
	$sql = " SELECT clientes_ubicacion.descripcion AS ubicacion,
	                v_ficha.cod_ficha,
					v_ficha.ap_nombre AS trabajador,
					productos.descripcion AS producto,
                    almacenes.descripcion AS almacen,
                    SUM(IF(prod_asignacion.tipo='ASIGNACION', prod_asignacion_det.cantidad, -prod_asignacion_det.cantidad)) AS cant_reservada,
                    (SELECT GROUP_CONCAT(e.cod_ean SEPARATOR ', ') FROM prod_asignacion_eans e 
                     JOIN prod_asignacion pa ON pa.codigo = e.cod_asignacion 
                     WHERE pa.cod_ubicacion = prod_asignacion.cod_ubicacion 
                     AND IFNULL(pa.cod_ficha,'') = IFNULL(prod_asignacion.cod_ficha,'') 
                     AND e.cod_producto = prod_asignacion_det.cod_producto) AS eans
               FROM prod_asignacion 
               JOIN prod_asignacion_det ON prod_asignacion_det.cod_asignacion = prod_asignacion.codigo
               JOIN productos ON productos.item = prod_asignacion_det.cod_producto
               JOIN almacenes ON almacenes.codigo = prod_asignacion_det.cod_almacen
               JOIN clientes_ubicacion ON clientes_ubicacion.codigo = prod_asignacion.cod_ubicacion
               LEFT JOIN v_ficha ON v_ficha.cod_ficha = prod_asignacion.cod_ficha
               GROUP BY prod_asignacion.cod_ubicacion, prod_asignacion.cod_ficha, prod_asignacion_det.cod_producto, prod_asignacion_det.cod_almacen
               HAVING cant_reservada > 0
		       ORDER BY ubicacion ASC, trabajador ASC ";

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
				  <td class="texto">'.longitud($datos["ubicacion"]).'</td>
                  <td class="texto">'.$datos["cod_ficha"].'</td>
				  <td class="texto">'.longitud($datos["trabajador"]).'</td>
				  <td class="texto">'.longitud($datos["producto"]).'</td>
				  <td class="texto">'.longitud($datos["almacen"]).'</td>
				  <td class="texto">'.$datos["cant_reservada"].'</td>
				  <td class="texto">'.longitud($datos["eans"]).'</td>
            </tr>';
        }
	?>
    </table>
</div>
