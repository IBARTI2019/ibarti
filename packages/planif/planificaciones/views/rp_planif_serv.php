<?php
define("SPECIALCONSTANT",true);
require "../../../../autentificacion/aut_config.inc.php";
include_once('../../../../funciones/funciones.php');
include_once('../../../../dompdf/dompdf_config.inc.php');
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

    echo "<table border=1>";
    echo $_POST['body_planif'];
    echo "</table>";
   	require('../../../../'.pagDomPdf.'/paginacion_ibarti.php');
    require_once('../../../../'.ConfigDomPdf);
		$dompdf= new DOMPDF();
    
    $dompdf->set_paper('legal', 'landscape');
    $dompdf->load_html(ob_get_clean(),'UTF-8');
		$dompdf->render();
		$dompdf->stream($archivo, array('Attachment' => 0));
    $dompdf->output($archivo);

}
}
?>
