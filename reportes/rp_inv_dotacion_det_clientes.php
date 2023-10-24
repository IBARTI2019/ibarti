<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 579;
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
$only_active    = $_POST['only_active'];
$trabajador      = $_POST['trabajador'];
$cliente	= $_POST['cliente'];
$ubicacion	= $_POST['ubicacion'];
$reporte         = $_POST['reporte'];
$restri	    = $_SESSION['r_cliente'];
$archivo         = "rp_inv_dotacion_clientes".$fecha."";
$titulo          = "  DOTACION CLIENTES \n";
if(isset($reporte)){

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

	if($only_active == "T"){
		$where  .= " AND  clientes.status  = 'T' ";
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
                  clientes.nombre empresa ,clientes_ubicacion.descripcion ubicacion,
                 ajuste_alcance_reng.cod_producto,
                 prod_lineas.descripcion AS linea,
                
                 ajuste_alcance_reng.aplicar as neto,
                 ajuste_alcance.anulado,
                 
                 prod_sub_lineas.descripcion AS sub_linea, productos.descripcion AS producto,ajuste_alcance_reng.cantidad
            FROM ajuste_alcance , ajuste_alcance_reng,clientes,clientes_ubicacion,productos , prod_lineas ,prod_sub_lineas 
        $where GROUP BY ajuste_alcance.codigo,ajuste_alcance_reng.cod_producto
HAVING MAX(ajuste_alcance.codigo)
ORDER BY 2 ASC";



	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";
 	 echo "<tr><th> Código </th><th> Fecha </th><th>Cliente</th><th>".$leng['ubicacion']."
			   <th> Linea </th><th> Sub Linea </th><th> Cod. Producto </th><th> Producto </th><th> Cantidad </th>";
			   echo ($restri=="F")?'<th class="etiqueta">Tipo</th>':'';
		echo "<th> Anulado</th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td> ".$row01[0]." </td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td><td>".$row01[5]."</td><td>".$row01[9]."</td>
		 <td>".$row01[4]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td <td>".$row01[6]."";
				   echo ($restri=="F")?'<td class="texto">'.$row01[7].'</td>':''; 
				    
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
            <th width='20%'>Fecha</th>
            <th width='25%'>".$leng['ubicacion']."</th>
            <th width='15%'>Linea</th>
            <th width='15%'>Sub Linea</th>
            <th width='15%'>Producto</th>
            <th width='10%'  style='text-align:center;'>Cantidad</th>
            <th width='10%'  style='text-align:center;'>Tipo</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
   echo   "<td width='10%' style='text-align:center;'>".$row[0]."</td>
            <td width='20%'>".$row[1]."</td>
            <td width='10%'>".$row[2]."</td>
            <td width='10%'>".$row[4]."</td>
            <td width='10%'>".$row[7]."</td>
             <td width='10%'>".$row[8]."</td>
             <td width='10%'>".$row[9]."</td>
             <td width='10%'>".$row[5]."</td>
            ";
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
}