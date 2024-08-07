<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 575;
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
$rol        = $_POST['rol'];
$linea      = $_POST['linea'];
$sub_linea  = $_POST['sub_linea'];
$producto   = $_POST['producto'];
$anulado    = $_POST['anulado'];
$trabajador      = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$almacen	= $_POST['almacen'];
$reporte         = $_POST['reporte'];
$restri	    = $_SESSION['r_cliente'];
$archivo         = "rp_inv_dotacion_".$fecha."";
$titulo          = "  DOTACION TRABAJADOR \n";
$almacen     = $_POST['almacen'];
if(isset($reporte)){

	$where = "  WHERE DATE_FORMAT(prod_dotacion.fec_dotacion, '%Y-%m-%d') BETWEEN  \"$fecha_D\" AND \"$fecha_H\"
   	              AND prod_dotacion.codigo = prod_dotacion_det.cod_dotacion
   	              AND prod_dotacion.cod_cliente = clientes.codigo
   	              AND prod_dotacion.cod_ubicacion = clientes_ubicacion.codigo
			      AND prod_dotacion_det.cod_producto = productos.item
			      AND productos.cod_linea = prod_lineas.codigo
			      AND productos.cod_sub_linea = prod_sub_lineas.codigo
			      AND v_ficha.cod_ficha = prod_dotacion.cod_ficha 
			      AND ajuste.referencia = prod_dotacion.codigo
				AND ajuste_reng.cod_ajuste = ajuste.codigo
				AND ajuste_reng.cod_almacen = prod_dotacion_det.cod_almacen
				AND ajuste_reng.cod_producto = prod_dotacion_det.cod_producto 
				AND (ajuste.cod_tipo = 'DOT' OR ajuste.cod_tipo = 'ANU_DOT')
				AND ajuste_reng.cod_almacen = almacenes.codigo";

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
		$where  .= " AND  prod_dotacion.anulado  = '$anulado' ";
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
		$where  .= " AND ajuste_reng.cod_almacen = '$almacen' ";
	}
	
 $sql = " SELECT DISTINCT prod_dotacion.codigo, prod_dotacion.fec_dotacion, prod_dotacion.fec_us_ing, v_ficha.rol, v_ficha.cod_ficha,
                 v_ficha.cedula, v_ficha.ap_nombre AS trabajador,
                 prod_dotacion.descripcion, prod_lineas.descripcion AS linea,
                 prod_sub_lineas.descripcion AS sub_linea, CONCAT(productos.descripcion,' (',productos.cod_talla,') ') AS producto,
                 productos.item serial,
                 prod_dotacion_det.cantidad,clientes.nombre cliente, clientes_ubicacion.descripcion ubicacion,
				 ajuste_reng.cod_almacen,	almacenes.descripcion almacen, 
				 ajuste_reng.neto importe,Valores(prod_dotacion.anulado) anulado
            FROM prod_dotacion , prod_dotacion_det , productos , prod_lineas ,
                 prod_sub_lineas, v_ficha,clientes,clientes_ubicacion, ajuste,ajuste_reng,tallas,almacenes
          $where
        GROUP BY prod_dotacion.codigo,ajuste.codigo,prod_dotacion_det.cod_producto
HAVING MAX(ajuste.codigo)
ORDER BY 2 ASC ";



	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";
 	 echo "<tr><th> Código </th><th> Fecha </th><th> Fecha Ingreso</th><th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th>
	  		<th> Cod. Almacén </th><th> Almacén </th>	<th> ".$leng['rol']." </th>
	           <th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th><th> Descripción </th>
			   <th> Linea </th><th> Sub Linea </th><th> Producto </th><th> Serial </th><th> Cantidad </th>";
			   echo ($restri=="F")?'<th class="etiqueta">Importe</th>':'';
		       echo "<th> Anulado</th></tr>";
		
		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[13]."</td>
		 			<td>".$row01[14]."</td><td>".$row01[15]."</td><td>".$row01[16]."</td><td>".$row01[3]."</td>
					<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
					<td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td>
					<td>".$row01[12]."</td>";
				   echo ($restri=="F")?'<td class="texto">'.$row01[17].'</td>':''; 
				 echo "  <td>".$row01[18]."</td></tr>";
				 
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
			<th width='15%'  style='text-align:center;'>Almacén</th>
            <th width='20%'>Fecha</th>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='20%'>".$leng['trabajador']."</th>
            <th width='15%'>Producto</th>
            <th width='12%'  style='text-align:center;'>Cantidad</th>
            <th width='10%'  style='text-align:center;'>Anulado</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
   echo   "<td width='10%' style='text-align:center;'>".$row[0]."</td>
   			<td width='15%' style='text-align:center;'>".$row[16]."</td>
            <td width='20%'>".$row[1]."</td>
            <td width='10%'>".$row[4]."</td>
            <td width='20%'>".$row[6]."</td>
            <td width='15%'>".$row[10]." (".$row[11].") </td>
            <td width='12%' style='text-align:center;'>".$row[12]."</td>
            <td width='10%' style='text-align:center;'>".$row[18]."</td>
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