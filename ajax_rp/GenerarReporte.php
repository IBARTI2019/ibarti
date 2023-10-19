<?php
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
require_once('../'.ConfigDomPdf);
$resultData = array();
$resultData["error"] = false;
if(isset($_POST['trabajador'])){
	$bd = new DataBase();
	$dompdf = new DOMPDF();
	$reporte = $_POST["reporte"];
	$ficha   = $_POST["trabajador"];

	$sql_smtp = "SELECT control.host_smtp,  control.puerto_smtp, control.protocolo_smtp,
	control.cuenta_smtp,control.password_smtp, men_reportes_html.descripcion reporte
	FROM control,men_reportes_html WHERE  men_reportes_html.codigo = '$reporte' ";

	$query = $bd->consultar($sql_smtp);
	$result =$bd->obtener_fila($query,0);
	$host =$result['host_smtp'];
	$puerto =$result['puerto_smtp'];
	$protocolo =$result['protocolo_smtp'];
	$cuenta=$result['cuenta_smtp'];
	$password =$result['password_smtp'];
	$rep = $result['reporte'];

	$sql = "SELECT * FROM v_rp_ficha WHERE v_rp_ficha.cod_ficha = '$ficha' ";
	$query = $bd->consultar($sql);
	$result =$bd->obtener_fila($query,0);

	extract($result);


	if($_POST['ubicacion'] != "TODOS"){
		$sql02 = "SELECT clientes.abrev AS cl_abrev, clientes.nombre AS cliente,
		clientes.observacion AS cliente_observ, clientes_ubicacion.descripcion AS ubicacion,
		clientes_ubicacion.contacto AS cliente_contacto,  clientes_ubicacion.cargo AS cliente_cargo,
		clientes_ubicacion.observacion AS ubicacion_observ,IF(clientes_vetados.cod_ficha,'VETADO','') vetado
		FROM
		clientes ,
		clientes_ubicacion
		LEFT JOIN clientes_vetados ON clientes_vetados.cod_ubicacion = clientes_ubicacion.codigo AND clientes_vetados.cod_ficha = '$ficha'
		WHERE clientes_ubicacion.codigo = ".$_POST['ubicacion']."
		AND clientes_ubicacion.cod_cliente = clientes.codigo ";
		$query02 = $bd->consultar($sql02);
		$result02 =$bd->obtener_fila($query02,0);
		extract($result02);
	}

	if($_POST['puesto'] != "TODOS"){
		$sql02 = "SELECT clientes_ub_puesto.nombre puesto,clientes_ub_puesto.actividades,clientes_ub_puesto.observ puesto_observ FROM clientes_ub_puesto WHERE clientes_ub_puesto.codigo = '".$_POST['puesto'] ."' ";
		$query02 = $bd->consultar($sql02);
		$result02 =$bd->obtener_fila($query02,0);
		extract($result02);
	}

	$sql02 = "SELECT REPLACE(men_reportes_html.html, '&quot;', '\'') AS html, men_reportes_html.descripcion AS nombreHtml
	FROM men_reportes_html
	WHERE men_reportes_html.codigo = $reporte ";
	$query02  = $bd->consultar($sql02);
	$result02 = $bd->obtener_fila($query02,0);
	
	ob_start();
	$html =  html_entity_decode($result02['html'],null, 'UTF-8');
	eval("\$html = \"$html\";");
	?>
	<!DOCTYPE html>
		<html>
		<head>
			<title>IBARTI</title>
			<link rel="stylesheet" type="text/css" href="../<?php echo cssDomPdf?>">
		</head>
		<body>
	<?php
	echo $html. "\n";

	echo "</tbody>
			</table>
		</div>
		</body>
		</html>";
	$dompdf->load_html(ob_get_clean(),'UTF-8');
	$dompdf->render();
	$output = $dompdf->output();
	$name = $reporte."-".$ficha.".pdf";
	$link = "../documentosPreparados/".$name;
	// return $dompdf->stream($name);
	// return $dompdf->stream($result02['descripcion'].".pdf", array('Attachment' => false));
	// return  $dompdf->download($result02['descripcion'].".pdf");
	file_put_contents($link, $output);
	$resultData["name"] = $name;
	$resultData["link"] = $link;
}else{
	$resultData["error"] = true;
	$resultData["msg"] = "Debe especificar al trabajador";
}
print_r(json_encode($resultData));
return json_encode($resultData);
?>
