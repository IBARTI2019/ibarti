<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 544;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

if(($_POST['fecha_desde'] == "")){
exit;
}

$fecha_D         = conversion($_POST['fecha_desde']);

$quincena       = $_POST['quincena'];
$nomina          = $_POST['nomina'];
$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ciudad          = $_POST['ciudad'];

$reporte         = $_POST['reporte'];
$archivo         = "rp_nom_cuadriculado_".$fecha."";
$titulo          = " REPORTE CUADRICULADO ASISTENCIA ";

if(isset($reporte)){

	$fecha_N = explode("-", $fecha_D);
	$year1   = $fecha_N[0];
	$mes1    = $fecha_N[1];
	$dia1    = $fecha_N[2];

	$fecha_Inc_M  = mktime(0,0,0,$mes1,$dia1,$year1);
    $fec_mensual = "".$year1."-".$mes1."-01";


	$where01 = "WHERE asistencia_quincenal01.fec_mensual = '$fec_mensual'
				  AND asistencia_quincenal01.cod_ficha = v_ficha.cod_ficha ";

	$where02 = "WHERE asistencia_quincenal02.fec_mensual = '$fec_mensual'
				  AND asistencia_quincenal02.cod_ficha = v_ficha.cod_ficha ";

	if($nomina != "TODOS"){
		$where01 .= " AND v_ficha.cod_contracto = '$nomina' ";
	    $where02 .= " AND v_ficha.cod_contracto = '$nomina' ";
	}

	if($rol != "TODOS"){
		$where01 .= " AND v_ficha.cod_rol = '$rol' ";
		$where02 .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($region != "TODOS"){
		$where01 .= " AND v_ficha.cod_region = '$region' ";
		$where02 .= " AND v_ficha.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where01 .= " AND v_ficha.cod_estado = '$estado' ";
		$where02 .= " AND v_ficha.cod_estado = '$estado' ";
	}

	if($ciudad != "TODOS"){
		$where01  .= " AND v_ficha.cod_ciudad = '$ciudad' ";
		$where02  .= " AND v_ficha.cod_ciudad = '$ciudad' ";
	}

		 if ($quincena == "01"){

			$fecha_H = $year1.'-'.$mes1.'-15';
			$fecha_D = $year1.'-'.$mes1.'-01';

	// QUERY A MOSTRAR //
        $sql = " SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.ap_nombre,
                        v_ficha.rol,  v_ficha.region,
                        v_ficha.estado,  v_ficha.ciudad,
                        v_ficha.contracto,
						asistencia_quincenal01.d01, asistencia_quincenal01.d02,
						asistencia_quincenal01.d03, asistencia_quincenal01.d04,
						asistencia_quincenal01.d05, asistencia_quincenal01.d06,
						asistencia_quincenal01.d07, asistencia_quincenal01.d08,
						asistencia_quincenal01.d09, asistencia_quincenal01.d10,
						asistencia_quincenal01.d11, asistencia_quincenal01.d12,
						asistencia_quincenal01.d13, asistencia_quincenal01.d14,
						asistencia_quincenal01.d15
                   FROM asistencia_quincenal01 , v_ficha
                 $where01
			   ORDER BY 1 ASC";
		}elseif($quincena == "02"){
			 $fecha_x   = mktime(0,0,0, $mes1, 01,$year1);
			 $fec_desde = strtotime("+1 months -1 day", $fecha_x);
			 $fecha_H   = date("Y-m-d", $fec_desde);
			 $fecha_D   = $year1.'-'.$mes1.'-16';


		$sql = " SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.ap_nombre,
                        v_ficha.rol,  v_ficha.region,
                        v_ficha.estado,  v_ficha.ciudad,
                        v_ficha.contracto,
					    asistencia_quincenal02.d16,
						asistencia_quincenal02.d17, asistencia_quincenal02.d18,
						asistencia_quincenal02.d19, asistencia_quincenal02.d20,
						asistencia_quincenal02.d21, asistencia_quincenal02.d22,
						asistencia_quincenal02.d23, asistencia_quincenal02.d24,
						asistencia_quincenal02.d25, asistencia_quincenal02.d26,
						asistencia_quincenal02.d27, asistencia_quincenal02.d28,
						asistencia_quincenal02.d29, asistencia_quincenal02.d30,
						asistencia_quincenal02.d31
                   FROM asistencia_quincenal02 , v_ficha
                 $where02
			   ORDER BY 1 ASC";
		}
		$titulo .= "QUINCENA $fecha_D HASTA ".conversion($fecha_H)."\n";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		 header("Content-Disposition:  filename=\"rp_$archivo.xls\";");


		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";

		 if ($quincena == "01"){

echo	$tr = "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
	           <th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
			   <th> ".$leng['cliente']."  </th><th> ".$leng['ubicacion']."   </th>
	           <th> 01 </th><th> 02 </th><th> 03 </th><th> 04 </th>
			   <th> 05 </th><th> 06 </th><th> 07 </th><th> 08 </th>
			   <th> 09 </th><th> 10 </th><th> 11 </th><th> 12 </th>
			   <th> 13 </th><th> 14 </th><th> 15 </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td>
				   <td>".$row01[2]."</td><td>".$row01[3]."</td>
				   <td>".$row01[4]."</td><td>".$row01[5]."</td>
				   <td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td>
				   <td>".$row01[10]."</td><td>".$row01[11]."</td>
				   <td>".$row01[12]."</td><td>".$row01[13]."</td>
				   <td>".$row01[14]."</td><td>".$row01[15]."</td>
				   <td>".$row01[16]."</td><td>".$row01[17]."</td>
				   <td>".$row01[18]."</td><td>".$row01[19]."</td>
				   <td>".$row01[20]."</td><td>".$row01[21]."</td>
				   <td>".$row01[22]."</td><td>".$row01[23]."</td>
				   <td>".$row01[24]."</td></tr>";
		}

		}elseif($quincena == "02"){

echo	$tr = "<tr><th> ".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
	           <th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
			   <th> ".$leng['cliente']."  </th><th> ".$leng['ubicacion']."   </th>
	           <th> 16 </th><th> 17 </th><th> 18 </th><th> 19 </th>
			   <th> 20 </th><th> 21 </th><th> 22 </th><th> 23 </th>
			   <th> 24 </th><th> 25 </th><th> 26 </th><th> 27 </th>
			   <th> 28 </th><th> 29 </th><th> 30 </th><th> 31 </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td > ".$row01[0]." </td><td>".$row01[1]."</td>
				   <td>".$row01[2]."</td><td>".$row01[3]."</td>
				   <td>".$row01[4]."</td><td>".$row01[5]."</td>
				   <td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td>
				   <td>".$row01[10]."</td><td>".$row01[11]."</td>
				   <td>".$row01[12]."</td><td>".$row01[13]."</td>
				   <td>".$row01[14]."</td><td>".$row01[15]."</td>
				   <td>".$row01[16]."</td><td>".$row01[17]."</td>
				   <td>".$row01[18]."</td><td>".$row01[19]."</td>
				   <td>".$row01[20]."</td><td>".$row01[21]."</td>
				   <td>".$row01[22]."</td><td>".$row01[23]."</td>
				   <td>".$row01[24]."</td><td>".$row01[25]."</td></tr>";
			}
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

	if($quincena=="01"){
		echo "<br><div>
        <table>
		<tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='20%'>".$leng['trabajador']."</th>
            <th>01</th>
            <th>02</th>
            <th>03</th>
            <th>04</th>
            <th>05</th>
            <th>06</th>
            <th>07</th>
            <th>08</th>
            <th>09</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            </tr>";

                        $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='10%'>".$row[0]."</td>
		    <td width='20%'>".$row[2]."</td>
		    <td>".$row[10]."</td>
		    <td>".$row[11]."</td>
		    <td>".$row[12]."</td>
		    <td>".$row[13]."</td>
		    <td>".$row[14]."</td>
		    <td>".$row[15]."</td>
		    <td>".$row[16]."</td>
		    <td>".$row[17]."</td>
		    <td>".$row[18]."</td>
		    <td>".$row[19]."</td>
		    <td>".$row[20]."</td>
		    <td>".$row[21]."</td>
		    <td>".$row[22]."</td>
			<td>".$row[23]."</td>
		    <td>".$row[24]."</td></tr>";

             $f++;
         }

    echo "</tbody>
        </table>
		</div>
		</body>
		</html>";

	}elseif($quincena=="02"){

			echo "<br><div>
        <table>
		<tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='20%'>".$leng['trabajador']."</th>
            <th>16</th>
            <th>17</th>
            <th>18</th>
            <th>19</th>
            <th>20</th>
            <th>21</th>
            <th>22</th>
            <th>23</th>
            <th>24</th>
            <th>25</th>
            <th>26</th>
            <th>27</th>
            <th>28</th>
            <th>29</th>
            <th>30</th>
            <th>31</th>
            </tr>";

                        $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
 echo   "<td>".$row[0]."</td>
		    <td width='10%'>".$row[0]."</td>
		    <td width='20%'>".$row[2]."</td>
		    <td>".$row[10]."</td>
		    <td>".$row[11]."</td>
		    <td>".$row[12]."</td>
		    <td>".$row[13]."</td>
		    <td>".$row[14]."</td>
		    <td>".$row[15]."</td>
		    <td>".$row[16]."</td>
		    <td>".$row[17]."</td>
		    <td>".$row[18]."</td>
		    <td>".$row[19]."</td>
		    <td>".$row[20]."</td>
		    <td>".$row[21]."</td>
		    <td>".$row[22]."</td>
		    <td>".$row[23]."</td>
			<td>".$row[24]."</td>
		    <td>".$row[25]."</td></tr>";

             $f++;
         }

    echo "</tbody>
        </table>
		</div>
		</body>
		</html>";
	}

		    $dompdf->load_html(ob_get_clean(),'UTF-8');
		    $dompdf->set_paper ('letter','landscape');
		    $dompdf->render();
		    $dompdf->stream($archivo, array('Attachment' => 0));
	}
}
