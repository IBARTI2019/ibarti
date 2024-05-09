<?php
define("SPECIALCONSTANT",true);
require "../../../../autentificacion/aut_config.inc.php";
include_once('../../../../funciones/funciones.php');
require_once('../../../../'.ConfigDomPdf);
require_once "../../../../".class_bdI;
require_once "../../../../".Leng;
$bd = new DataBase();

$reporte      = $_POST['reporte'];
$archivo      = "rp_planif_serv_".$fecha."";
$titulo       = "Reporte Servicio ".$leng['cliente']." \n";


if(isset($reporte)){
  if($reporte== 'excel'){
    echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: filename=\"$archivo.xls\";");

    echo "<table border=1>";
    echo $_POST['body_planif'];
    echo "</table>";

  }
  if($reporte== 'pdf'){
    
    ob_start();
  require('../../../../'.PlantillaDOM.'/packages_header2.php');
	// require('../../../../'.pagDomPdf.'/paginacion_ibarti.php');
		
echo "
<html>
<head>
  <style>
    tr:nth-child(even) {
      background-color:  #f2f2f2 ;
      text-align:center;
    }
   
    th {
      background-color: #c3f5ae;
      color: black;
      text-align:center;
    }
    td {
      color: black;
      text-align:center;
    }
    tbody th {
      background-color: #36c;
      color: #fff;
      text-align:center;
    }
    
    tbody tr:nth-child(even) th {
      background-color: #25c;
      text-align:center;
      
    }
    
  tr:last-of-type td:last-of-type {
    width: 50x;
    background-color: #ffffff;
    color: #505050;
    font-weight: bold;
    text-align: center;
  }
   header {
      position: fixed;
      top: 30px;
      left: 2px;
      right: 80px;
      line-height: 30px;
      text-align: right;
  
  }
  #footer {
    text-align: right;
    font-size: 12px;
    margin-top: 20px;
  }
  footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
      right: 0px;
      text-align: center;
      line-height: 35px;
  }
  h1 {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
}
  </style>
 
</head>" ;
echo "<body>";
echo  "<table border=1 >";
echo $_POST['body_planif'];
echo "</table>";
echo "</body>";
echo "</html>";
   
		$dompdf= new DOMPDF();
    
    $dompdf->set_paper('legal', 'landscape');
    $dompdf->load_html(ob_get_clean(),'UTF-8');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
    $dompdf->output($archivo);

}
}
?>
