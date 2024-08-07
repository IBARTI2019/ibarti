<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
$Nmenu   = 542;
session_start();
require("../autentificacion/aut_config.inc.php");
include_once("../".Funcion);
require_once('../autentificacion/aut_verifica_menu_02.php');

$body 		= $_POST['body_cubrir'];
$reporte    = $_POST['reporte'];
$archivo    = "rop_planif_cl_vs_trab_cubrir_".$fecha."";
$titulo 	= " PLANIFICACION DE CLIENTES A CUBRIR VS TRABAJADORES ACTIVOS ";

if($reporte== 'excel'){

		header("Content-type: application/vnd.ms-excel");
		 header("Content-Disposition:  filename=\"rp_$archivo.xls\";");
		 echo "<table border=1>";
		echo $body;
		echo "</table>";
	}

	if($reporte == 'pdf'){
		require_once('../'.ConfigDomPdf);
		$dompdf= new DOMPDF();

		ob_start();

		require('../'.PlantillaDOM.'/header_ibarti_2.php');
		include('../'.pagDomPdf.'/paginacion_ibarti.php');

		echo "<br><div>
        <table border=1>";
            echo $body;

echo "</table>
		</div>
		</body>
		</html>";

		    $dompdf->load_html(ob_get_clean(),'UTF-8');
		    $dompdf->set_paper ('letter','landscape');
		    $dompdf->render();
		    $dompdf->stream($archivo, array('Attachment' => 0));
	}
?>
