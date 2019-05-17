<?php
define("SPECIALCONSTANT",true);
$Nmenu   = 523;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);

$bd = new DataBase();

$fecha_H         = conversion($_POST['fecha_H']);
$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$contrato        = $_POST['contrato'];
$n_contrato      = $_POST['n_contrato'];


$reporte         = $_POST['reporte'];
$trabajador      = $_POST['trabajador'];

$archivo         = "rp_fic_venc_contracto_det_".$fecha."";
$titulo          = " REPORTE VENCIMIENTO DE CONTRATO ($fecha_H) \n";

if(isset($reporte)){
		$where = " WHERE v_ficha.cod_ficha_status = control.ficha_activo
                     AND ficha_n_contracto.vencimiento = 'T'
				     AND v_ficha.cod_n_contracto = ficha_n_contracto.codigo
				     AND ficha_historial.cod_ficha = v_ficha.cod_ficha
					 AND ficha_historial.cod_n_contrato = ficha_n_contracto.codigo ";

	if($region != "TODOS"){
		$where .= " AND v_ficha.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND v_ficha.cod_estado = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND v_ficha.cod_contracto = '$contrato' ";
	}

	if($n_contrato != "TODOS"){
		$where  .= " AND v_ficha.cod_n_contracto = '$n_contrato' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}
	// QUERY A MOSTRAR //
	    $sql = "SELECT v_ficha.region, v_ficha.rol,
                       v_ficha.estado, v_ficha.ciudad,
                       v_ficha.contracto, v_ficha.cod_ficha,
					   v_ficha.cedula,  v_ficha.ap_nombre,
					   ficha_n_contracto.descripcion AS n_contracto,
					   v_ficha.fec_ingreso , ADDDATE(v_ficha.fec_ingreso,INTERVAL ficha_n_contracto.dias DAY ) AS fec_vencimiento ,
					((ficha_n_contracto.dias) -(DATEDIFF('$fecha_H',MAX(ficha_historial.fec_inicio)))) AS dias_venc
                  FROM v_ficha, control, ficha_n_contracto, ficha_historial
                $where
                GROUP BY 6
              ORDER BY 2 ASC";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";

 	 echo "<tr><th> ".$leng['region']." </th> <th> ".$leng['rol']." </th><th> ".$leng['estado']." </th><th> ".$leng['ciudad']." </th>
	           <th> ".$leng['contrato']." </th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th>
			   <th> Número de ".$leng['contrato']." </th><th> Fecha Ingreso </th><th> Fecha Venc. ".$leng['contrato']."</th>
			   <th> dias_vencimiento ((+) Ha vencer, (-) vencidos)</th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
		           <td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td>  <td>".$row01[10]."</td><td>".$row01[11]."</td></tr>";
		}
		 echo "</table>";
	}

	if($reporte == 'pdf'){

	$titulo = " REPORTE VENCIMIENTO DE CONTRATO <br>($fecha_H) \n";

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
            <th width='9%'>".$leng['region']."</th>
            <th width='13%'>".$leng['rol']."</th>
            <th width='12%'>".$leng['estado']."</th>
            <th width='12%'>".$leng['ficha']."</th>
            <th width='20%'>".$leng['trabajador']."</th>
            <th width='12%'>N° de Contrato</th>
            <th width='20%'>Dias Vencimiento Contrato</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='10%'>".$row[0]."</td>
            <td width='12%'>".$row[1]."</td>
            <td width='12%'>".$row[2]."</td>
            <td width='12%'>".$row[5]."</td>
            <td width='27%'>".$row[7]."</td>
            <td width='15%'>".$row[8]."</td>
            <td width='12%' style='text-align: center;'>".$row[11]."</td></tr>";

             $f++;
         }

    echo "</tbody>
        </table>
</div>
</body>
</html>";

		    $dompdf->load_html(ob_get_clean(),'UTF-8');
		    $dompdf->set_paper ('letter','landscape');
		    $dompdf->render();
		    $dompdf->stream($archivo, array('Attachment' => 0));
	}
}
