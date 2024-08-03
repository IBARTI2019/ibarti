  <?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();
  foreach($_POST as $nombre_campo => $valor){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }
try {
    if($metodo == "agregar"){
      $sql = "INSERT INTO planif_life_line
                          (cod_ubicacion, cod_actividad, hora_inicio, hora_fin,
                          cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                  VALUES ('$ubicacion', '$actividad','$hora_inicio', '$hora_fin',
                          '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP)";
      $query = $bd->consultar($sql);
    }else if($metodo == "modificar"){
      $sql = "UPDATE planif_life_line SET
                     cod_ubicacion = '$ubicacion', cod_actividad = '$actividad',
                     hora_inicio = '$hora_inicio', hora_fin = '$hora_fin',
                     cod_us_mod = '$usuario', fec_us_mod = CURRENT_TIMESTAMP
               WHERE codigo = '$codigo' ";
      $query = $bd->consultar($sql);
    } else if($metodo == "eliminar"){
      $sql = "DELETE FROM planif_life_line WHERE codigo = '$codigo' ";
      $query = $bd->consultar($sql);
    }
    $result['sql'] = $sql;
  }catch (Exception $e) {
     $error =  $e->getMessage();
     $result['error'] = true;
     $result['mensaje'] = $error;
     $bd->log_error("Aplicacion", "sc_life_line.php",  "$usuario", "$error", "$sql");
 }

	print_r(json_encode($result));
	return json_encode($result);

?>
