<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();
session_start();
$rol        = $_POST['rol'];
$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$anulado    = $_POST['anulado'];
$trabajador = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$restri	    = $_SESSION['r_cliente'];
$fecha_D    = conversion($_POST['fecha_desde']);
$fecha_H    = conversion($_POST['fecha_hasta']);

	$where = "  WHERE DATE_FORMAT(ajuste_alcance.fecha, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\"
   	              AND ajuste_alcance.codigo = ajuste_alcance_reng.cod_ajuste
   	              AND ajuste_alcance.cod_ubicacion = clientes_ubicacion.codigo
                  AND ajuste_alcance_reng.cod_producto = productos.item
			      AND productos.cod_linea = prod_lineas.codigo
                  AND productos.cod_sub_linea = prod_sub_lineas.codigo
                  AND clientes_ubicacion.cod_cliente=clientes.codigo";
                  
	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($linea != "TODOS"){
		$where .= " AND prod_lineas.codigo = '$linea' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($sub_linea != "TODOS"){
		$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
	}
	if($producto != "TODOS"){
		$where  .= " AND productos.item  = '$producto' ";
	}

	if($anulado != "TODOS"){
		$where  .= " AND  ajuste_alcance.anulado  = '$anulado' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}

	
	if($cliente != "TODOS" && $cliente != ""){
		$where  .= " AND  clientes.codigo  = '$cliente' ";
	}

	if($ubicacion != "TODOS" && $ubicacion != ""){
		$where  .= " AND clientes_ubicacion.codigo = '$ubicacion' ";
	}

 $sql = "SELECT DISTINCT ajuste_alcance.codigo, ajuste_alcance.fecha,
                 ajuste_alcance_reng.cod_producto as descripcion,
                 clientes.nombre cliente,
                 clientes_ubicacion.descripcion ubicacion,
                 ajuste_alcance_reng.aplicar as neto,
                 ajuste_alcance.anulado,
                 prod_lineas.descripcion AS linea,
                 prod_sub_lineas.descripcion AS sub_linea, productos.descripcion AS producto,ajuste_alcance_reng.cantidad
            FROM ajuste_alcance , ajuste_alcance_reng,clientes,clientes_ubicacion,productos , prod_lineas ,prod_sub_lineas 
        $where GROUP BY ajuste_alcance.codigo,ajuste_alcance_reng.cod_producto
HAVING MAX(ajuste_alcance.codigo)
ORDER BY 2 ASC";

?>

<table width="100%" border="0" align="center">
		<tr class="fondo00">
  			<th width="9%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
            <th width="10%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
             <th width="10%" class="etiqueta">Linea</th>
            <th width="10%" class="etiqueta">Sub Linea</th>
            <th width="24%" class="etiqueta">Producto </th>
            <th width="5%" class="etiqueta">Cantidad</th>
            <?php echo ($restri=="F")?'<th width="5%" class="etiqueta">Tipo</th>':'';?>
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
			      <td class="texto">'.$datos["fecha"].'</td>
                  <td class="texto">'.$datos["ubicacion"].'</td>
				 <td class="texto">'.$datos["linea"].'</td>
                  <td class="texto">'.$datos["sub_linea"].'</td>
                  <td class="texto">'.$datos["producto"].'</td>
                  <td class="texto">'.$datos["cantidad"].'</td>
				  ';
				  echo ($restri=="F")?'<td class="texto">'.$datos["neto"].'</td>':'';
        };?>
    </table>
