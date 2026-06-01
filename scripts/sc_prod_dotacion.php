<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<script type="text/javascript" src="../jquery.js"></script>
<script language="JavaScript" type="text/javascript">
	function Pdf(){
		$('#pdf').attr('action', '../reportes/rp_inv_prod_dotacion.php');
		$('#pdf').submit();
	}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Documento sin t&iacute;tulo</title>
</head>

<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla_id = 'codigo';

$codigo      = $_POST["codigo"];
$fecha       = conversion($_POST["fecha"]);
$descripcion = $_POST["descripcion"];
$trabajador  = $_POST["trabajador"];
$cliente  = $_POST["cliente"];
$ubicacion  = $_POST["ubicacion"];
$incr        = $_POST["incremento"];

$campo01     = $_POST["campo01"];
$campo02     = $_POST["campo02"];
$campo03     = $_POST["campo03"];
$campo04     = $_POST["campo04"];
$usuario = $_POST["usuario"];

$activo   = 'T';
$href     = $_POST['href'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];
$nro_ajuste_c = "";
if(isset($_POST['proced'])){
	$error = false;
	$bd->consultar("START TRANSACTION");

	$sql    = "$SELECT $proced('$metodo', '$codigo', '$fecha','$cliente','$ubicacion', '$trabajador',
	'$descripcion',
	'$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo')";
	if(!$bd->consultar($sql)){ $error = true; }

	if($metodo == "agregar" && !$error){
		$sql    = "SELECT MAX(prod_dotacion.codigo) codigo FROM prod_dotacion
		WHERE cod_ficha = '$trabajador' ";
	
		$query = $bd->consultar($sql);
		if(!$query) { $error = true; } else {
			$datos = $bd->obtener_fila($query,0);
			$codigo = $datos[0];
			$sql = " SELECT a.n_ajuste FROM control a ";
			$query = $bd->consultar($sql);
		}
		if(!$query) { $error = true; } else {
			$data =$bd->obtener_fila($query,0);
			$nro_ajuste   =  $data[0];
			$cod_ajuste = $nro_ajuste + 1;
			$descripcion_ajuste = $descripcion.'  (Ficha:'.$trabajador.')';
			$sql = " INSERT INTO ajuste(codigo, cod_tipo,referencia, cod_proveedor,fecha,  motivo,
			total, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
			VALUES ($cod_ajuste, 'DOT','$codigo','9999', '$fecha', '$descripcion_ajuste',
			0,'$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP); ";
		
			if(!$bd->consultar($sql)){ $error = true; }
			$sql = " UPDATE control SET n_ajuste = $cod_ajuste; ";
			if(!$bd->consultar($sql)){ $error = true; }
		}
	}

	if(!$error){
		for ($i = 1; $i <= $incr; $i++) {
			if($error) break;
			if(isset($_POST['relacion_'.$i.''])) {
				$relacion = $_POST['relacion_'.$i.''];
			} else {
				$relacion =  "";
			}

			if(($relacion !="")&& ($metodo == "agregar")){
			//	$tipo     = $_POST['tipo'.$i.''];
				$producto = $_POST['producto_'.$i.''];
				$producto_old = $producto;
				$cantidad = $_POST['cantidad_'.$i.''];
				$almacen = $_POST['almacen_'.$i.''];
					$sql = "$SELECT p_prod_dotacion_det('$metodo', '$codigo', '$producto', '$producto_old', '$almacen', '$cantidad')";
					if(!$bd->consultar($sql)) { $error = true; break; }
				
				$sql = "SELECT cos_promedio
				FROM ajuste_reng
				WHERE ajuste_reng.cod_producto = '$producto' AND cod_almacen='$almacen'
				ORDER BY cod_ajuste DESC,reng_num DESC
				LIMIT 1";

			
				$query = $bd->consultar($sql);
				$data =$bd->obtener_fila($query,0);
				$cos_promedio   =  $data[0];
				if(is_null($cos_promedio)){
					$sql = "SELECT cos_promedio
					FROM ajuste_reng
					WHERE ajuste_reng.cod_producto = '$producto' AND cod_almacen='001'
					ORDER BY cod_ajuste DESC,reng_num DESC
					LIMIT 1";
				$query = $bd->consultar($sql);
				if(!$query) { $error = true; break; }
				$data =$bd->obtener_fila($query,0);
				$cos_promedio   =  $data[0];
				if(is_null($cos_promedio)){
					$cos_promedio = 0;
				}
				}
				$neto = $cos_promedio * $cantidad;

				$sql = " INSERT INTO ajuste_reng(cod_ajuste, reng_num, cod_almacen, cod_producto,
				fec_vencimiento, cantidad, costo, neto,  importe, cos_promedio)
				VALUES ($cod_ajuste, $i, '$almacen', '$producto', '0000-00-00',
				$cantidad, $cos_promedio, $neto, $neto, $cos_promedio); ";

				if(!$bd->consultar($sql)) { $error = true; break; }

				$sql = " UPDATE stock SET stock_actual = stock_actual - $cantidad
				WHERE cod_producto = '$producto' AND cod_almacen = '$almacen'; ";
				if(!$bd->consultar($sql)) { $error = true; break; }

				// Insert EANs
				$eans     = $_POST['eans_'.$i.''];
				if(trim($eans) != ""){
					$eans_array = explode(",", $eans);
					foreach($eans_array as $ean){
						$ean = trim($ean);
						if($ean != ""){
							$sql = "INSERT INTO prod_dotacion_eans (cod_dotacion, cod_producto, cod_ean)
									VALUES ($codigo, '$producto', '$ean')";
							if(!$bd->consultar($sql)){ $error = true; break; }
						}
					}
					if($error) break;
				}
			}
		}
	}	

	if($error){
		// Capturamos el error pasándole el link actual de la base de datos
		$err = mysql_error($bd->conexion()); 
		$bd->consultar("ROLLBACK");
		
		// Escapamos correctamente para evitar romper el alert de JS
		$err_clean = addslashes($err);
		echo "<script>alert('Error crítico de base de datos: $err_clean. Transacción anulada.');</script>";
	}

	if($metodo == "agregar" && !$error){
		// Query header data
		$sql_header = "SELECT DATE_FORMAT(prod_dotacion.fec_dotacion,'%Y-%m-%d %H:%i:%s') fec_dotacion,
		               v_ficha.cod_ficha, v_ficha.cedula, v_ficha.nombres AS trabajador,
		               prod_dotacion.descripcion, v_ficha.telefono, prod_dotacion.anulado
		FROM prod_dotacion, v_ficha
		WHERE prod_dotacion.cod_ficha = v_ficha.cod_ficha AND prod_dotacion.codigo = '$codigo'";
		$query_header = $bd->consultar($sql_header);
		$header = $bd->obtener_fila($query_header, 0);

		// Query detalle data
		$sql_det = "SELECT prod_lineas.codigo cod_linea, prod_lineas.descripcion linea,
		            prod_dotacion_det.cod_producto,
		            CONCAT(productos.descripcion,' ',tallas.descripcion) producto,
		            prod_dotacion_det.cantidad,
		            prod_sub_lineas.codigo cod_sub_linea, prod_sub_lineas.descripcion sub_linea
		FROM prod_dotacion_det, productos, prod_lineas, prod_sub_lineas, tallas
		WHERE prod_dotacion_det.cod_dotacion = '$codigo'
		AND prod_dotacion_det.cod_producto = productos.item
		AND productos.cod_linea = prod_lineas.codigo
		AND productos.cod_sub_linea = prod_sub_lineas.codigo
		AND productos.cod_talla = tallas.codigo";
		$query_det = $bd->consultar($sql_det);

		$detalle = array();
		while($det = $bd->obtener_fila($query_det, 0)){
			$detalle[] = array(
				"cod_linea" => $det['cod_linea'],
				"linea" => $det['linea'],
				"cod_producto" => $det['cod_producto'],
				"producto" => $det['producto'],
				"cantidad" => (int)$det['cantidad'],
				"cod_sub_linea" => $det['cod_sub_linea'],
				"sub_linea" => $det['sub_linea']
			);
		}

		// Build JSON payload
		$payload = array(
			"fec_dotacion" => $header['fec_dotacion'],
			"cod_ficha" => $header['cod_ficha'],
			"cedula" => $header['cedula'],
			"trabajador" => $header['trabajador'],
			"descripcion" => $header['descripcion'],
			"telefono" => $header['telefono'],
			"anulado" => $header['anulado'],
			"detalle" => $detalle
		);

		$json_payload = json_encode($payload);

		// Send to webhook
		$url = 'http://212.56.33.4:5678/webhook/dotaciones';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);

		// echo $json_payload;
		// Optionally log the response or handle errors
		// For now, just send
	}
}

// require_once('../funciones/sc_direccionar.php');
?>
<body>

</body>
</html>
