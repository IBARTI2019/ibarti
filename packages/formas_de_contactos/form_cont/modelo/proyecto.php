<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();

  foreach($_POST as $nombre_campo => $valor){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }
  $nombre          = htmlentities($nombre);

	if(isset($_POST['proced'])){
	try {
    if($metodo == "agregar"){
      $sql = "INSERT INTO formas_de_contactos (codigo, abrev, descripcion, 
                                    cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod, `status`)
                            VALUES (NULL, '$abrev', '$nombre', '$usuario', current_date, '$usuario', current_date, '$status')";
    }else{
      $sql = "UPDATE formas_de_contactos SET
             abrev          = '$abrev',     descripcion    = '$nombre',
             fec_us_mod     = current_date, cod_us_mod     = '$usuario',
             `status`       = '$status'
        WHERE codigo         = '$codigo'";
    }

	 $query   = $bd->consultar($sql);

 		}catch (Exception $e) {
       $error =  $e->getMessage();
       $result['error'] = true;
       $result['mensaje'] = $error;

       $bd->log_error("Aplicacion", "sc_proyecto.php",  "$usuario", "$error", "$sql");
   }

	}
	print_r(json_encode($result));
	return json_encode($result);
?>
