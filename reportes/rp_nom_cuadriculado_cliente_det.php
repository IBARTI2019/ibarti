<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 544;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();
$bd2 = new DataBase();

if(($_POST['fecha_desde'] == "")){
exit;
}

$fecha_D         = conversion($_POST['fecha_desde']);

$quincena       = $_POST['quincena'];
$nomina          = $_POST['nomina'];
$rol             = $_POST['rol'];
$cliente          = $_POST['cliente'];
$ubicacion          = $_POST['ubicacion'];

$reporte         = $_POST['reporte'];
$archivo         = "rp_nom_cuadriculado_clientes_".$fecha."";
$titulo          = " REPORTE CUADRICULADO CLIENTES ASISTENCIA ";

if(isset($reporte)){

	$fecha_N = explode("-", $fecha_D);
	$year1   = $fecha_N[0];
	$mes1    = $fecha_N[1];
	$dia1    = $fecha_N[2];

	$fecha_Inc_M  = mktime(0,0,0,$mes1,$dia1,$year1);
    $fec_mensual = "".$year1."-".$mes1."-01";


	$where01 = "WHERE
	asistencia.cod_as_apertura = asistencia_apertura.codigo 
	AND asistencia.cod_ficha = v_ficha.cod_ficha 
	AND clientes.codigo = clientes_ubicacion.cod_cliente 
	AND asistencia.cod_ubicacion = clientes_ubicacion.codigo ";

	if($nomina != "TODOS"){
		$where01 .= " AND v_ficha.cod_contracto = '$nomina' ";
	}

	if($rol != "TODOS"){
		$where01 .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($cliente != "TODOS"){
		$where01  .= " AND clientes_ubicacion.cod_cliente = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where01  .= " AND asistencia.cod_ubicacion = '$ubicacion' ";
	}

	if ($quincena == "01"){
		$fecha_H = $year1.'-'.$mes1.'-15';
		$fecha_D = $year1.'-'.$mes1.'-01';
	}elseif($quincena == "02"){
		$fecha_x   = mktime(0,0,0, $mes1, 01,$year1);
		$fec_desde = strtotime("+1 months -1 day", $fecha_x);
		$fecha_H   = date("Y-m-d", $fec_desde);
		$fecha_D   = $year1.'-'.$mes1.'-16';
	}

	$where01  .= "AND asistencia_apertura.fec_diaria BETWEEN '$fecha_D' 
	AND  '$fecha_H' ";

	// QUERY A MOSTRAR //
	$sql = " SELECT
		v_ficha.cod_ficha,
		v_ficha.cedula,
		v_ficha.ap_nombre,
		v_ficha.rol,
		v_ficha.contracto,
		clientes.abrev,
		clientes_ubicacion.codigo,
		clientes_ubicacion.descripcion
	FROM
		v_ficha,
		asistencia,
		asistencia_apertura,
		clientes,
		clientes_ubicacion
	$where01
	GROUP BY v_ficha.cod_ficha, asistencia.cod_ubicacion
	ORDER BY 1 ASC";
	
	$titulo .= "QUINCENA $fecha_D HASTA ".conversion($fecha_H)."\n";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");


		$query01  = $bd->consultar($sql);
		echo "<table border=1>";

		if ($quincena == "01"){
			echo "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
				<th> Nómina  </th><th> ".$leng['cliente']."  </th><th> ".$leng['ubicacion']."   </th>
				<th> 01 </th><th> 02 </th><th> 03 </th><th> 04 </th>
				<th> 05 </th><th> 06 </th><th> 07 </th><th> 08 </th>
				<th> 09 </th><th> 10 </th><th> 11 </th><th> 12 </th>
				<th> 13 </th><th> 14 </th><th> 15 </th></tr>";

			while ($row01 = $bd->obtener_num($query01)){
				echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
						<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[7]."</td>";
				$sql_detalle = "SELECT
						asistencia_apertura.fec_diaria,
						conceptos.abrev 
					FROM
						asistencia,
						asistencia_apertura,
						conceptos 
					WHERE
						asistencia.cod_ficha = '$row01[0]' 
						AND asistencia.cod_as_apertura = asistencia_apertura.codigo
						AND asistencia.cod_ubicacion = $row01[6] 
						AND asistencia.cod_concepto = conceptos.codigo 
						AND asistencia_apertura.fec_diaria BETWEEN '$fecha_D' 
						AND '$fecha_H'";

				$detalle = array();
				$query02 = $bd2->consultar($sql_detalle);
				while ($row02 = $bd2->obtener_name($query02)){
					$detalle[] = $row02;
				}
				for ($i=1; $i <= 15; $i++) { 
					if($i < 10){
						$day = '0'.$i;
					}else{
						$day = $i;
					}
					$fecha = $year1.'-'.$mes1.'-'.$day;
					$found_key = array_search($fecha, array_column($detalle, 'fec_diaria'));
					// if(($found_key != '' && $found_key != false) || ($found_key == 0 && gettype($found_key) == 'integer')){
					// 	echo "<td>".$detalle[$found_key]['abrev']."  </td>";
					// }else{
						echo "<td></td>";
					// }
				}
				echo "</tr>";
			}

		}elseif($quincena == "02"){

			echo	$tr = "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
				<th> Nómina  </th><th> ".$leng['cliente']."  </th><th> ".$leng['ubicacion']."   </th>
				<th> 16 </th><th> 17 </th><th> 18 </th><th> 19 </th>
				<th> 20 </th><th> 21 </th><th> 22 </th><th> 23 </th>
				<th> 24 </th><th> 25 </th><th> 26 </th><th> 27 </th>
				<th> 28 </th><th> 29 </th><th> 30 </th><th> 31 </th></tr>";

			while ($row01 = $bd->obtener_num($query01)){
				echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
				<td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[7]."</td>";
				$sql_detalle = "SELECT
					asistencia_apertura.fec_diaria,
					conceptos.abrev 
				FROM
					asistencia,
					asistencia_apertura,
					conceptos 
				WHERE
					asistencia.cod_ficha = '$row01[0]' 
					AND asistencia.cod_as_apertura = asistencia_apertura.codigo
					AND asistencia.cod_ubicacion = $row01[6] 
					AND asistencia.cod_concepto = conceptos.codigo 
					AND asistencia_apertura.fec_diaria BETWEEN '$fecha_D' 
					AND '$fecha_H'";

				$detalle = array();
				$query02 = $bd2->consultar($sql_detalle);
				while ($row02 = $bd2->obtener_name($query02)){
					$detalle[] = $row02;
				}
				for ($day=16; $day <= 31; $day++) { 
					$fecha = $year1.'-'.$mes1.'-'.$day;
					$found_key = array_search($fecha, array_column($detalle, 'fec_diaria'));
					if(($found_key != '' && $found_key != false) || ($found_key == 0 && gettype($found_key) == 'integer')){
						echo "<td>".$detalle[$found_key]['abrev']."  </td>";
					}else{
						echo "<td></td>";
					}
				}
				echo "</tr>";
			}
		}

		 echo "</table>";

	}
}