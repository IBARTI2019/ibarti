<?php
define("SPECIALCONSTANT",true);
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();
$bd2 = new DataBase();
$clasif=$_POST['clasificacion'];
$tipo=$_POST['tipo'];
$reporte         = $_POST['reporte'];
$titulo2=	$_POST['tipos'];
$archivo =$titulo2.'.pdf';

$titulo          = " FORMATO DE CHECKLIST "; 	




$codigo = 'SIN CODIGO';
$tomo = 'SIN REVISION';

$sql3 ="SELECT campo01 basc,campo02 rev from nov_tipo where codigo ='".$tipo."'";
$query2=$bd->consultar($sql3);
$contador=0;
while($row2 = $bd->obtener_num($query2)){
	
	if($row2[0]!=null){
		$codigo = $row2[0];
		$tomo 	= $row2[1];
	}
	
}
if($reporte == 'pdf'){
	require_once('../'.ConfigDomPdf);
	$dompdf= new DOMPDF();

	ob_start();

	require('../'.PlantillaDOM.'/header_oesvica_check.php');
	include('../'.pagDomPdf.'/paginacion_ibarti.php');

	$logo = "../imagenes/logo.jpg";
	

	if (file_exists($logo)) {
		$logo_img =  '<img src="'.$logo.'1" />';
	} else {
		$logo_img  =  '<img src="../imagenes/img_no_disp.jpg"/>';
	}

	echo '
	
	<div style="border:1px solid black;margin:0;padding:0;text-align:center;background:#0f7517;color:white;font-size:16px;">'.$titulo2.'</div>
	<div style="border:1px solid black;margin:0;padding:0;text-align:center;">
	
	<table width="100%" style="padding-top: 10px;">

	<tr>
	<td width="40%" style="border:1px solid"><b>Empresa:</b></td>
	<td width="40%" style="border:1px solid"><b>Ubicacion:</b></td>
	<td width="20%" style="border:1px solid"><b>Fecha:</b></td>
	</tr><tr>
	<td style="border:1px solid"><b>Supervisor:</b></td>
	<td style="border:1px solid" colspan="2"><b>Observacion:</b></td>
	</tr>
	</table>
	<p style="font-size:10px;">|
	';

	$sql = "SELECT e.abrev , e.descripcion from novedades a, nov_clasif b,nov_tipo c, nov_valores_det d, nov_valores e
	where a.cod_nov_tipo = c.codigo
	and a.cod_nov_clasif = b.codigo
	and b.codigo = '".$clasif."'
	and c.codigo = '".$tipo."'
	and a.`status` = 'T'
	and d.cod_novedades = a.codigo
	and e.codigo = d.cod_valores
	and a.`status`= 'T'
	GROUP BY e.codigo";
	$query=$bd->consultar($sql);
	while($row = $bd->obtener_num($query)){
		echo "<b>".$row[0]."</b> : ".$row[1]." | ";
	}
	echo '</p>';
	echo '<table width="100%" border="1">
	<tr style="background:#0f7517;color:white;"><td colspan="2">'.$titulo2.'</td></tr>';

	$sql = "SELECT a.codigo,b.descripcion clasif, c.descripcion tipo, a.descripcion pregunta from novedades a, nov_clasif b,nov_tipo c
	where a.cod_nov_tipo = c.codigo
	and a.cod_nov_clasif = b.codigo
	and b.codigo = '".$clasif."'
	and c.codigo = '".$tipo."'
	and a.`status` = 'T'";

	$query=$bd->consultar($sql);
	while($row = $bd->obtener_num($query)){
		echo '<tr><td width="95%"><b>¿'.$row[3].'</b><br>Observacion: <img src="../imagenes/cuadro.jpg" border="1" width="80%" height="30px"></img><br></td>  ' ;


		$sql2="SELECT nov_valores.abrev 
		from nov_valores,nov_valores_det 
		where nov_valores_det.cod_valores = nov_valores.codigo
		and nov_valores_det.cod_novedades = '".$row[0]."'
		order by nov_valores.codigo";

		$query2=$bd2->consultar($sql2);

		echo '<td width="5%" style="text-align:left">';
		$i=0;
		$salto = '';
		while($rows = $bd2->obtener_num($query2)){
			if($i==3){
				$salto ="<hr style='margin: 2px;page-break-after: none;'>";
				$i=0;
			}
			echo $salto.'<span>'.$rows[0].'</span>:<span><img src="../imagenes/cuadro.jpg" border="2" width="16px"></img></span> ';
			//echo $salto.$rows[0].':'.chr(40).'  '.chr(41);
			$salto='';
			$i++;
		}

		echo '</td>';
		//echo '<tr><td colspan="2"><b>Observacion:</b></td></tr>';
		
	}

	$bd2->liberar();
	$bd->liberar();

	echo'
	</tr>
	<tr>
	<td colspan="2"><br></td>
	</tr>
	<tr>
	<td colspan="2" style="padding: 0px !important;">
	<table width="100%" style="margin: 0px !important;padding: 0px !important;">
	<tr>
	<td width="50%" style="text-align:center;">Firma de Supervisor:<br><br><br></td>
	<td width="50%" style="border-left:1px solid; text-align:center;">Firma de representante de la empresa:<br><br><br></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>

	</div>
	
	';

	echo '
	</body>
	</html>';
	
	$dompdf->load_html(ob_get_clean(),'UTF-8');
	//$dompdf->set_paper('letter','landscape');
	$dompdf->render();
	$dompdf->stream($archivo, array('Attachment' => 0));
}else if($reporte == 'excel'){
	echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition:  filename=\"$archivo.xls\";");

	echo "<table border=1>
	<tr><th> Código Clasificación </th><th> Clasificación </th><th> Código Tipo </th><th> Tipo </th><th> Código Novedad </th><th> Novedad </th>";

		// <th> Abreviatura </th><th> Valor </th><th> Valor MAX</th><th> % Cumplimiento </th>
		// <th> Factor </th><th> Observación </th><th> Fec. Ult. Modificación </th><th> Status </th></tr>";

	$sql = "SELECT a.codigo,b.codigo, b.descripcion clasif, c.codigo, c.descripcion tipo, a.codigo, a.descripcion pregunta from novedades a, nov_clasif b,nov_tipo c
	where a.cod_nov_tipo = c.codigo
	and a.cod_nov_clasif = b.codigo
	and a.`status` = 'T'";

	if($clasif != "TODOS"){
		$sql .= " and b.codigo = '".$clasif."' ";
	}
	
	if($tipo != "TODOS"){
		$sql .= " and c.codigo = '".$tipo."' ";
	}
	
	$query=$bd->consultar($sql);
	while($row = $bd->obtener_num($query)){
		echo '<tr><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td>' ;
		echo '</tr>';
	}
	echo "</table>";

}else{
	echo $clasif;
}

?>

