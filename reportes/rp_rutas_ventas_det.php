<?php
define("SPECIALCONSTANT",true);
$Nmenu   = 745;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);

$bd = new DataBase();

// $fecha_D         = conversion($_POST['fecha_desde']);
// $fecha_H         = conversion($_POST['fecha_hasta']);

$precliente          = $_POST['precliente'];
$ruta          = $_POST['ruta'];
$sub_ruta          = $_POST['sub_ruta'];

$reporte         = $_POST['reporte'];
$archivo         = "rp_rutas_ventas_det_".$fecha."";
$titulo          = " REPORTE RUTAS DE VENTA \n";


if(isset($reporte)){

	// $where = "   WHERE v_preingreso.fec_us_mod BETWEEN \"$fecha_D\" AND \"$fecha_H\"  ";

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
	ORDER BY 1 ASC;";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
	 echo "<table border=1>";
 	 echo "<tr><th> Código </th><th> Fecha </th><th> Codigo ".$leng['precliente']." </th><th> Abrev. ".$leng['precliente']." </th>
 	 		<th>  ".$leng['precliente']." </th><th> Cod. Ruta De Venta </th><th> Ruta De Venta </th> 
			<th> Cod. Sub Ruta De Venta </th><th> Sub Ruta De Venta</th> <th> Comentario </th> </tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".conversion($row01[1])."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
		           <td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td></tr>";
		}
		echo "</table>";
	}
}
