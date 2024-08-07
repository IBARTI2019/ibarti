<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 522;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ubicacion          = $_POST['ubicacion'];
$cliente          = $_POST['cliente'];
$rol             = $_POST['rol'];
$contrato        = $_POST['contrato'];

$carnet_vencido  = $_POST['carnet_vencido'];
$foto            = $_POST['foto'];

$reporte         = $_POST['reporte'];
$trabajador      = $_POST['trabajador'];

$archivo         = "rp_fic_trab_carnet_det_".$fecha."";
$titulo          = " REPORTE TRABAJADOR CARNET \n";

if(isset($reporte)){

	$where = " WHERE v_ficha.cod_ficha_status = control.ficha_activo ";

	if($region != "TODOS"){
		$where .= " AND v_ficha.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND v_ficha.cod_estado = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($cliente != "TODOS"){
		$where .= " AND v_ficha.cod_cliente = '$cliente' ";
	}

	if($ubicacion != "TODOS"){
		$where .= " AND v_ficha.cod_ubicacion = '$ubicacion' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND v_ficha.cod_contracto = '$contrato' ";
	}

	if($carnet_vencido != "TODOS"){
		$where .= " AND v_ficha.fec_carnet < '$date' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}
	// QUERY A MOSTRAR //
	    $sql = "SELECT v_ficha.region, v_ficha.rol,
                       v_ficha.estado, v_ficha.ciudad,
                       v_ficha.contracto, v_ficha.cargo,
					   v_ficha.cod_ficha, v_ficha.cedula,
					   v_ficha.ap_nombre, v_ficha.fec_carnet,
					   v_ficha.cliente,v_ficha.ubicacion
                  FROM v_ficha, control
                  $where
              ORDER BY 2 ASC ";


	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";

	  echo "<tr><th> ".$leng['region']." </th> <th> ".$leng['rol']." </th><th> ".$leng['estado']." </th>
	  			<th> ".$leng['cliente']." </th><th> Ubicacion </th><th> ".$leng['ciudad']." </th>
	           <th> ".$leng['contrato']." </th><th> Cargo </th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th>
			   <th> ".$leng['trabajador']." </th> <th> Fecha Venc. Carnet </th><th> Foto </th> </tr>";

	while ($row01 = $bd->obtener_num($query01)){

	$cedula   = $row01[7];
	$filename = "../imagenes/fotos/$cedula.jpg";

		if($foto == "TODOS"){
		$imprimir = "SI";
			if (file_exists($filename)) {
			$fot = "SI";
			}else {
			$fot = "NO";
			}
		}elseif($foto == "S"){
			if (file_exists($filename)) {
			$imprimir = "SI";
			$fot = "SI";
			}else {
			$fot = "NO";
			$imprimir = "NO";
			}

		}elseif($foto == "N"){
			if (file_exists($filename)) {
			$imprimir = "NO";
			$fot = "SI";
			}else{
			$imprimir = "SI";
			$fot = "NO";
			}
		}else{
			$imprimir = "NO";
		}
		if($imprimir == "SI"){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[10]."</td>
				   <td>".$row01[11]."</td><td>".$row01[3]."</td><td>".$row01[4]."</td><td>".$row01[5]."</td>
				   <td>".$row01[6]."</td><td>".$row01[7]."</td><td>".$row01[8]."</td><td>".$row01[9]."</td><td>".$fot."</td></tr>";
		}}
		 echo "</table>";
	}
	if($reporte== 'pantalla'){

		$query01  = $bd->consultar($sql);
		 echo "<table border=1>";

 	 echo "<tr><th> ".$leng['region']." </th> <th> ".$leng['rol']." </th><th> ".$leng['estado']." </th><th> ".$leng['cliente']." </th><th> Ubicacion </th><th> ".$leng['ciudad']." </th>
	           <th> ".$leng['contrato']." </th><th> Cargo </th><th> ".$leng['ficha']." </th><th> ".$leng['ci']." </th>
			   <th> ".$leng['trabajador']." </th><th> Fecha Venc. Carnet </th><th> Foto </th></tr>";

	while ($row01 = $bd->obtener_num($query01)){

	$cedula   = $row01[7];
	$filename = "../imagenes/fotos/$cedula.jpg";

		if($foto == "TODOS"){
		$imprimir = "SI";
			if(file_exists($filename)) {
			$fot = "SI";
			}else {
			$fot = "NO";
			}
		}elseif($foto == "S"){
			if (file_exists($filename)) {
			$imprimir = "SI";
			$fot = "SI";
			}else {
			$fot = "NO";
			$imprimir = "NO";
			}

		}elseif($foto == "N"){
			if (file_exists($filename)) {
			$imprimir = "NO";
			$fot = "SI";
			}else{
			$imprimir = "SI";
			$fot = "NO";
			}
		}else{
			$imprimir = "NO";
		}

		if($imprimir == "SI"){

	 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[10]."</td><td>".$row01[11]."</td><td>".$row01[3]."</td>
			   <td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".$row01[7]."</td>
			   <td>".$row01[8]."</td><td>".$row01[9]."</td><td>";
		$filename = "../imagenes/fotos/".$row01[7].".jpg";

		if (file_exists($filename)){
			 echo "<a href='".$filename."'><img src='".$filename."?nocache=".time()."' border='0' width='45' height='60'/></a>";
		}else{
			   echo '<img src="../imagenes/img_no_disp.png" width="120px" height="80px" />';
		}
		echo "</td></tr>";

		}}
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
            <th width='10%'>".$leng['region']."</th>
            <th width='15%'>".$leng['rol']."</th>
            <th width='15%'>".$leng['estado']."</th>
            <th width='10%'>".$leng['ficha']."</th>
            <th width='30%'>".$leng['trabajador']."</th>
            <th width='15%'>Fecha Venc. Carnet</th>
            <th width='5%'>Foto</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){

    	$cedula   = $row[7];
		$filename = "../imagenes/fotos/$cedula.jpg";

		if($foto == "TODOS"){
		$imprimir = "SI";
			if (file_exists($filename)) {
			$fot = "SI";
			}else {
			$fot = "NO";
			}
		}elseif($foto == "S"){
			if (file_exists($filename)) {
			$imprimir = "SI";
			$fot = "SI";
			}else {
			$fot = "NO";
			$imprimir = "NO";
			}

		}elseif($foto == "N"){
			if (file_exists($filename)) {
			$imprimir = "NO";
			$fot = "SI";
			}else{
			$imprimir = "SI";
			$fot = "NO";
			}
		}else{
			$imprimir = "NO";
		}
		if($imprimir == "SI"){

    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='10%'>".$row[0]."</td>
            <td width='15%'>".$row[1]."</td>
            <td width='15%'>".$row[2]."</td>
            <td width='10%'>".$row[6]."</td>
            <td width='30%'>".$row[8]."</td>
            <td width='15%' style='text-align:center;'>".$row[9]."</td>
            <td width='5%'>".$fot."</td></tr>";

             $f++;
         }
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
