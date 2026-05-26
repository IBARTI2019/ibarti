<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 483;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

if(($_POST['fecha_desde'] == "" or $_POST['fecha_hasta'] == "")){
exit;
}

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$tipo       = $_POST['tipo'];
$trabajador      = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$almacen	= $_POST['almacen'];
$reporte         = $_POST['reporte'];
$restri	    = $_SESSION['r_cliente'];
$usuario = $_SESSION['usuario_cod'];
$archivo         = "rp_inv_asignacion_".$fecha."";
$titulo          = "  ASIGNACIONES Y DEVOLUCIONES \n";

if(isset($reporte)){

	$where = "  WHERE DATE_FORMAT(prod_asignacion.fecha, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\"
   	              AND prod_asignacion.codigo = prod_asignacion_det.cod_asignacion
   	              AND prod_asignacion.cod_ubicacion = clientes_ubicacion.codigo
				  AND clientes_ubicacion.cod_cliente = clientes.codigo
			      AND prod_asignacion_det.cod_producto = productos.item
			      AND productos.cod_linea = prod_lineas.codigo
			      AND productos.cod_sub_linea = prod_sub_lineas.codigo
				  AND v_ficha.cod_ficha = prod_asignacion.cod_ficha 
			     ";

	if($restri  == "T"){
		$where  .= " AND prod_asignacion.cod_ubicacion IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		usuario_clientes.cod_usuario = '$usuario') ";
	}

	if($linea != "TODOS"){
		$where .= " AND prod_lineas.codigo = '$linea' ";
	}

	if($sub_linea != "TODOS"){
		$where  .= " AND prod_sub_lineas.codigo = '$sub_linea' ";
	}
	if($producto != "TODOS"){
		$where  .= " AND productos.item  = '$producto' ";
	}

	if($tipo != "TODOS" && $tipo != ""){
		$where  .= " AND  prod_asignacion.tipo  = '$tipo' ";
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
	if($almacen != "TODOS" && $almacen != ""){
		$where  .= " AND prod_asignacion_det.cod_almacen = '$almacen' ";
	}
	
 $sql = " SELECT prod_asignacion.codigo, prod_asignacion.fecha as fec_asignacion, prod_asignacion.fec_us_ing, v_ficha.cod_ficha,
                 v_ficha.cedula, v_ficha.ap_nombre AS trabajador,
                 prod_asignacion.descripcion, prod_lineas.descripcion AS linea,
                 prod_sub_lineas.descripcion AS sub_linea, productos.descripcion AS producto,
                 productos.item serial,
                 prod_asignacion_det.cantidad,clientes.nombre cliente, clientes_ubicacion.descripcion ubicacion,
				 prod_asignacion_det.cod_almacen,	almacenes.descripcion almacen, 
				 prod_asignacion.tipo,
				 (SELECT GROUP_CONCAT(cod_ean SEPARATOR ', ') FROM prod_asignacion_eans WHERE cod_asignacion = prod_asignacion.codigo AND cod_producto = prod_asignacion_det.cod_producto) AS eans
            FROM prod_asignacion , prod_asignacion_det , productos , prod_lineas ,
                 prod_sub_lineas, v_ficha,clientes,clientes_ubicacion,almacenes
          $where
		  AND prod_asignacion_det.cod_almacen = almacenes.codigo
ORDER BY 2 DESC, prod_asignacion.codigo DESC ";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";
  	 echo "<tr><th> Código </th><th> Fecha </th><th> Tipo </th><th> Fecha Ingreso</th><th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th>
	  		<th> Cod. Almacén </th><th> Almacén </th>
	           <th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th><th> Descripción </th>
			   <th> Linea </th><th> Sub Linea </th><th> Producto </th><th> Serial </th><th> EANs </th><th> Cantidad </th></tr>";
		
		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[16]."</td><td>".$row01[2]."</td><td>".$row01[11]."</td>
		 			<td>".$row01[12]."</td><td>".$row01[13]."</td><td>".$row01[14]."</td>
					<td>".$row01[3]."</td><td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td>
					<td>".$row01[7]."</td><td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td>
					<td>".$row01[17]."</td><td>".$row01[11]."</td></tr>";
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
            <th width='10%'  style='text-align:center;'>Código</th>
            <th width='15%'>Fecha</th>
			<th width='10%'>Tipo</th>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='20%'>".$leng['trabajador']."</th>
            <th width='15%'>Producto</th>
            <th width='10%'>EANs</th>
            <th width='10%'  style='text-align:center;'>Cantidad</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
   echo   "<td width='10%' style='text-align:center;'>".$row[0]."</td>
            <td width='11%'>".$row[1]."</td>
			<td width='14%'>".$row[16]."</td>
            <td width='10%'>".$row[3]."</td>
            <td width='20%'>".$row[5]."</td>
            <td width='15%'>".$row[9]."</td>
            <td width='10%'>".$row[17]."</td>
            <td width='10%' style='text-align:center;'>".$row[11]."</td>
			</tr>";
             $f++;
         }

    echo "</tbody>
        </table>
</div>
</body>
</html>";

		    $dompdf->load_html(ob_get_clean(),'UTF-8');
		    $dompdf->render();
		    $dompdf->stream($archivo, array('Attachment' => 0));
}
}
