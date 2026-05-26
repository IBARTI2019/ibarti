<script type="text/javascript" src="../jquery.js"></script>
<script language="JavaScript" type="text/javascript">
function Pdf(){
 	$('#pdf').attr('action', '../reportes/rp_prod_movimiento.php');
	$('#pdf').submit();

  setTimeout(Redireccionar, 2000);
  }

  function Redireccionar(){
     location.href=$('#href').val();
  	}
</script>
<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$tabla    = 'nov_procesos';
$tabla_id = 'codigo';

$codigo         = $_POST['codigo'];
$producto       = $_POST['producto'];
$cliente        = $_POST['cliente'];
$ubicacion      = $_POST['ubicacion'];
$trabajador     = $_POST['trabajador'];
$tipo_mov       = $_POST['tipo_mov'];
$fecha          = conversion($_POST['fecha']);
$observacion    = htmlentities($_POST['observacion']);

$campo01        = $_POST['campo01'];
$campo02        = $_POST['campo02'];
$campo03        = $_POST['campo03'];
$campo04        = $_POST['campo04'];
$activo    = statusbd($_POST['activo']);

$href     = $_POST['href'];
$usuario  = $_POST['usuario'];
$proced   = $_POST['proced'];
$metodo   = $_POST['metodo'];

	if(isset($_POST['proced'])){
		$error = false;
		$bd->consultar("START TRANSACTION");

  	 $sql    = "$SELECT $proced('$metodo', '$codigo', '$producto', '$trabajador',
	                            '$cliente', '$ubicacion', '$tipo_mov', '$fecha',
							    '$observacion',
                                '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo')";
	 if(!$bd->consultar($sql)) { $error = true; }

  	if($metodo == "agregar" && !$error){
  		$sql    = "SELECT MAX(prod_movimiento.codigo) codigo FROM prod_movimiento
                  WHERE prod_movimiento.cod_ficha = '$trabajador'";

  		 $query = $bd->consultar($sql);
		   if(!$query) { $error = true; } else {
  		 $datos = $bd->obtener_fila($query,0);
  		 $codigo = $datos[0];
		   }
  	}

	if($error){
		$bd->consultar("ROLLBACK");
		echo "<script>alert('Error crítico de base de datos. Transacción anulada.');</script>";
	}else{
		$bd->consultar("COMMIT");
		if($metodo == "agregar"){
			echo '<form id="pdf" name="pdf" action="" method="post" target="_blank">
						<input type="hidden" id="codigo" name="codigo" value="'.$codigo.'">
			<input type="hidden" id="href" name="href" value="'.$href.'">
					</form>';
			echo "<script> Pdf(); </script>";
		}
	}
	}
 require_once('../funciones/sc_direccionar.php');
?>
