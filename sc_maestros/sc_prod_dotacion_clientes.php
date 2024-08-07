<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla_id = 'codigo';

$codigo      = $_POST['codigo'];
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
$ubicacion  = $_POST["trabajador"];
$trabajador=".";

$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];
$nro_ajuste_c = "";

if(isset($_POST['proced'])){
    // buscar si la dotacion existe por codigo y fecha
	$sql    = "$SELECT $proced('$metodo', '$codigo', '$fecha','$cliente','$ubicacion', '$trabajador','$descripcion','$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo')";
	$query = $bd->consultar($sql);
     // procedimiento debe retonrar un valor pediente.. OJO ///
     // y eliminar el SELECT MAX

	if($metodo == "agregar"){
		$sql    = "SELECT MAX(prod_dotacion_clientes.codigo) codigo FROM prod_dotacion_clientes
		WHERE cod_ubicacion = '$ubicacion'";
	
		$query = $bd->consultar($sql);
 		$datos = $bd->obtener_fila($query,0);
		$codigo = $datos[0];
		/* Ajuste de INventario */

	}

	for ($i = 1; $i <= $incr; $i++) {
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
				$sql = "$SELECT p_prod_dotacion_det_clientes('$metodo', '$codigo', '$producto', '$producto_old', '$almacen', '$cantidad')";
				$query = $bd->consultar($sql);
                $sql2    = "SELECT *  FROM vectoreans WHERE codigo = '$producto'";
                $query2 = $bd->consultar($sql2);
                $sql3    = "SELECT codigo,cod_dotacion,cod_producto  FROM prod_dotacion_det_clientes WHERE cod_producto = '$producto'";
                $query3 = $bd->consultar($sql3);
                while ($pddc = $bd->obtener_fila($query3,0)){
                    $iddetalle=$pddc["codigo"];
                    $iddotacion=$pddc["cod_dotacion"];
                    $idproducto=$pddc["cod_producto"];
                    while($datos = $bd->obtener_fila($query2,0)){
                        $codigoean=$datos["vector"];
                        $sql = "INSERT INTO prod_dotacion_clientes_eans (cod_dotacion,cod_dotacion_det,cod_ean) 
					   VALUES ('$iddotacion','$iddetalle','$codigoean')";
                        $query = $bd->consultar($sql);
                
                    } 
                }
               $sql4   = "DELETE FROM vectoreans WHERE activo='1'";
               $query4 = $bd->consultar($sql4);
               
            /* 
              Ajuste de Inventario
			*/
		}

		if($incr > $i){
			echo '<form id="pdf" name="pdf" action="" method="post" target="_blank">
			<input type="hidden" id="codigo" name="codigo" value="'.$codigo.'">
			</form>';
			echo "<script> Pdf(); </script>";
		}
	}

}

	require_once('../funciones/sc_direccionar.php');
?>

