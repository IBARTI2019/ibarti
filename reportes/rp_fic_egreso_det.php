<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 526;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

$fecha_D         = conversion($_POST['fecha_desde']);
$fecha_H         = conversion($_POST['fecha_hasta']);
$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ciudad          = $_POST['ciudad'];
$cargo           = $_POST['cargo'];
$contrato        = $_POST['contrato'];

$status          = $_POST['status'];
$reporte         = $_POST['reporte'];
$trabajador      = $_POST['trabajador'];

$archivo         = "rp_fic_egreso_".$fecha."";
$titulo          = "  REPORTE FICHA TRABAJADOR EGRESO \n";

if(isset($reporte)){

	$where = " WHERE ficha_egreso.fec_egreso BETWEEN \"$fecha_D\" AND \"$fecha_H\"
	             AND v_ficha.cod_ficha = ficha_egreso.cod_ficha ";

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($region != "TODOS"){
		$where .= " AND v_ficha.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND v_ficha.cod_estado = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($ciudad != "TODOS"){
		$where  .= " AND v_ficha.cod_ciudad = '$ciudad' ";
	}

	if($cargo != "TODOS"){
		$where  .= " AND v_ficha.cod_cargo = '$cargo' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND v_ficha.cod_contracto = '$contrato' ";
	}

	if($status != "TODOS"){
		$where .= " AND v_ficha.cod_ficha_status = '$status' ";
	}
	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}

	// QUERY A MOSTRAR //
	    $sql = "SELECT v_ficha.rol, v_ficha.region,
                       v_ficha.estado, v_ficha.ciudad,
                       v_ficha.cliente,v_ficha.ubicacion,
                       v_ficha.cod_ficha, v_ficha.cedula,
                       v_ficha.ap_nombre, v_ficha.cargo,
                       v_ficha.contracto,  v_ficha.fec_ingreso,
					   ficha_egreso.fec_egreso, ficha_egreso.fec_us_ing,
					   ficha_egreso.motivo,
					   ficha_egreso.preaviso, ficha_egreso.fec_inicio,
					   ficha_egreso.fec_culminacion, ficha_egreso.calculo,
					   ficha_egreso.calculo_status, ficha_egreso.fec_calculo,
					   ficha_egreso.fec_posible_pago, ficha_egreso.fec_pago,
					   ficha_egreso.cheque, ficha_egreso.banco,
					   ficha_egreso.importe, ficha_egreso.observacion,
					   ficha_egreso.observacion2, v_ficha.`status`, 
					   ficha_egreso_motivo.descripcion causa,
						IF(ficha_egreso.entrega_uniforme = 'S', 'SI', 'NO') entrega_uniforme
                  FROM v_ficha , ficha_egreso LEFT JOIN ficha_egreso_motivo ON ficha_egreso.cod_ficha_egreso_motivo = ficha_egreso_motivo.codigo 
                       $where
              ORDER BY ficha_egreso.fec_egreso DESC";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";

 	 echo "<tr><th> ".$leng['rol']." </th><th> ".$leng['region']." </th><th> ".$leng['estado']." </th><th> ".$leng['ciudad']." </th><th> ".$leng['cliente']." </th><th> ".$leng['ubicacion']." </th>
	           <th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th> <th> Cargo</th>
				<th> ".$leng['contrato']."</th><th> Fecha Ingreso </th><th> Fecha Egreso </th> <th> Sistema Fecha Egreso </th>
				<th> Motivo </th>
			   <th> Preaviso</th><th> Fecha Inicio Preaviso </th><th> Fec. Culminación Preaviso </th><th> Calculo </th>
			   <th> Calculo Status </th><th> Fec. Calculo </th><th> Fec. Posible de Pago </th><th> Fec. de Pago </th>
			   <th> Cheque </th><th> Banco </th><th> Importe </th><th> Observación </th>
			   <th> Comentario </th><th> Status </th><th>Causa</th><th>Entrega Uniforme</th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
		           <td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td>
				   <td>".$row01[12]."</td><td>".$row01[13]."</td><td>".$row01[14]."</td><td>".$row01[15]."</td>
				   <td>".$row01[16]."</td><td>".$row01[17]."</td><td>".$row01[18]."</td><td>".$row01[19]."</td>
				   <td>".$row01[20]."</td><td>".$row01[21]."</td><td>".$row01[22]."</td><td>".$row01[23]."</td>
				   <td>".$row01[24]."</td><td>".$row01[25]."</td><td>".$row01[26]."</td>
				   <td>".$row01[27]."</td><td>".$row01[28]."</td><td>".$row01[29]."</td><td>".$row01[30]."</td></tr>";
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
            <th width='12%'>".$leng['rol']."</th>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='30%'>".$leng['trabajador']."</th>
            <th width='24%'>".$leng['contrato']."</th>
            <th width='14%'>Fecha Egreso</th>
            <th width='10%'>Status</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='12%'>".$row[0]."</td>
            <td width='10%'>".$row[4]."</td>
            <td width='30%'>".$row[6]."</td>
            <td width='24%'>".$row[8]."</td>
            <td width='14%'>".conversion($row[10])."</td>
            <td width='10%'>".$row[26]."</td></tr>";

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
