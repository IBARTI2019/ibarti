<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 525;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

$rol           = $_POST['rol'];
$region        = $_POST['region'];
$estado        = $_POST['estado'];
$ciudad        = $_POST['ciudad'];
$contrato      = $_POST['contrato'];
$documento     = $_POST['documento'];
$doc_check     = $_POST['doc_check'];
$doc_vencimiento     = $_POST['doc_vencimiento'];

$status        = $_POST['status'];
$reporte       = $_POST['reporte'];
$trabajador    = $_POST['trabajador'];

$archivo       = "rp_fic_documento_".$fecha."";
$titulo        = "  REPORTE DOCUMENTOS TRABAJADOR \n";

if(isset($reporte)){

	$where = " WHERE ficha.cod_ficha = ficha_documentos.cod_ficha
                 AND ficha_documentos.cod_documento = documentos.codigo
				 AND documentos.`status` = 'T'
				 AND ficha.cod_ficha = trab_roles.cod_ficha
				 AND trab_roles.cod_rol = roles.codigo
				 AND ficha.cod_region = regiones.codigo
				 AND ficha.cod_estado = estados.codigo
				 AND ficha.cod_ciudad = ciudades.codigo
				 AND ficha.cod_contracto = contractos.codigo
				 AND ficha.cod_ficha_status = ficha_status.codigo ";

	if($rol != "TODOS"){
		$where .= " AND roles.codigo = '$rol' ";
	}

	if($region != "TODOS"){
		$where .= " AND regiones.codigo = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND estados.codigo = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($ciudad != "TODOS"){
		$where  .= " AND ciudades.codigo = '$ciudad' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND contractos.codigo = '$contrato' ";
	}

	if($documento != "TODOS"){
		$where  .= " AND ficha_documentos.cod_documento = '$documento' ";
	}

	if($doc_check != "TODOS"){
		$where  .= " AND ficha_documentos.checks = '$doc_check' ";
	}

	if($doc_vencimiento != "TODOS"){
		$where  .= " AND ficha_documentos.vencimiento = '$doc_vencimiento' ";
	}

	if($status != "TODOS"){
		$where .= " AND ficha_status.codigo = '$status' ";
	}
	if($trabajador != NULL){
		$where  .= " AND ficha.cod_ficha = '$trabajador' ";
	}

	// QUERY A MOSTRAR //
        $sql = "SELECT roles.descripcion AS rol,
        				regiones.descripcion AS region ,
		               estados.descripcion AS estado, ciudades.descripcion AS ciudad,
                       ficha.cod_ficha, ficha.cedula,
					   CONCAT(ficha.apellidos,' ', ficha.nombres) AS ap_nombre, contractos.descripcion AS contrato, ficha_documentos.cod_documento,  documentos.descripcion AS doc,
						 StatusD(ficha_documentos.checks) checks, StatusD(ficha_documentos.vencimiento) vencimiento,
						 if(ficha_documentos.vencimiento = 'N','SIN VENCIMIENTO',ficha_documentos.venc_fecha), ficha_status.descripcion AS `status`,
						 ficha.fec_ingreso, ficha_documentos.venc_fecha
                  FROM ficha , trab_roles, ficha_documentos , documentos , roles, regiones, estados, ciudades,
					   contractos, ficha_status
                $where
			  ORDER BY 1, 5 ASC   ";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";
 	 echo "<tr><th> ".$leng['rol']." </th><th> ".$leng['region']." </th><th> ".$leng['estado']." </th><th> ".$leng['ciudad']." </th>
	           <th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th><th> ".$leng['trabajador']." </th> <th> ".$leng['contrato']."</th>
			   <th> Cod. Documento </th><th> Documento </th><th> CHECKS </th> <th> VENCIMIENTO </th> <th> FECHA VENCIMIENTO </th><th> Status </th>
			   <th> Fecha de Ingreso </th><th> Fecha de Vencimiento </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
		           <td>'".$row01[4]."'</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
				   <td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td>
					 <td>".$row01[12]."</td><td>".$row01[13]."</td><td>".$row01[14]."</td><td>".$row01[15]."</td>
			   </tr>";
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
            <th width='15%'>".$leng['rol']."</th>
            <th width='14%'>".$leng['ficha']."</th>
            <th width='26%'>".$leng['trabajador']."</th>
            <th width='27%'>Documento</th>
            <th width='8%'>Checks</th>
						<th width='8%'>Venc.</th>

            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='15%'>".$row[0]."</td>
            <td width='14%'>".$row[4]."</td>
            <td width='26%'>".$row[6]."</td>
            <td width='27%'>".$row[9]."</td>
            <td width='8%'>".$row[10]."</td>
						<td width='8%'>".$row[11]."</td>";

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
