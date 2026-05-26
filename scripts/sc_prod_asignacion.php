<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();

$tipo        = $_POST["tipo"];
$fecha       = conversion($_POST["fecha"]);
$descripcion = $_POST["descripcion"];
$ubicacion   = $_POST["ubicacion"];
$trabajador  = $_POST["trabajador"];
$incr        = $_POST["incremento"];
$usuario     = $_POST["usuario"];
$metodo      = $_POST["metodo"];

$href     = "../inicio.php?area=formularios/Cons_prod_asignacion&Nmenu=483";

if($metodo == "agregar"){
	
	$error = false;
	$bd->consultar("START TRANSACTION");

	// 1. Insert header
	$sql = "INSERT INTO prod_asignacion (tipo, fecha, cod_ubicacion, cod_ficha, descripcion, cod_us_ing, fec_us_ing) 
			VALUES ('$tipo', '$fecha', '$ubicacion', '$trabajador', '$descripcion', '$usuario', CURRENT_TIMESTAMP)";
	if(!$bd->consultar($sql)){
		$error = true;
	}else{
		$query_id = $bd->consultar("SELECT LAST_INSERT_ID() AS id");
		$row_id = $bd->obtener_fila($query_id, 0);
		$codigo = $row_id['id'];
		// 2. Loop through details
		for ($i = 1; $i <= $incr; $i++) {
			if(isset($_POST['relacion_'.$i.''])) {
				$relacion = $_POST['relacion_'.$i.''];
				$producto = $_POST['producto_'.$i.''];
				$almacen  = $_POST['almacen_'.$i.''];
				$cantidad = $_POST['cantidad_'.$i.''];
				$eans     = $_POST['eans_'.$i.''];

				if($producto != "" && $cantidad > 0){
					// Insert Detail
					$sql = "INSERT INTO prod_asignacion_det (cod_asignacion, cod_producto, cod_almacen, cantidad)
							VALUES ($codigo, '$producto', '$almacen', $cantidad)";
					if(!$bd->consultar($sql)){ $error = true; break; }

					// Modify Stock reservado
					if($tipo == 'ASIGNACION'){
						$sql = "UPDATE stock SET stock_reservado = stock_reservado + $cantidad 
								WHERE cod_producto = '$producto' AND cod_almacen = '$almacen'";
					}else{
						$sql = "UPDATE stock SET stock_reservado = stock_reservado - $cantidad 
								WHERE cod_producto = '$producto' AND cod_almacen = '$almacen'";
					}
					if(!$bd->consultar($sql)){ $error = true; break; }

					// Insert EANs
					if(trim($eans) != ""){
						$eans_array = explode(",", $eans);
						foreach($eans_array as $ean){
							$ean = trim($ean);
							if($ean != ""){
								$sql = "INSERT INTO prod_asignacion_eans (cod_asignacion, cod_producto, cod_ean)
										VALUES ($codigo, '$producto', '$ean')";
								if(!$bd->consultar($sql)){ $error = true; break; }
							}
						}
						if($error) break;
					}
				}
			}
		}
	}

	if($error){
		$bd->consultar("ROLLBACK");
		echo "<script>alert('Error crítico de base de datos. Se canceló el guardado para proteger la integridad.');</script>";
	}else{
		$bd->consultar("COMMIT");
	}
}

require_once('../funciones/sc_direccionar.php');
?>
