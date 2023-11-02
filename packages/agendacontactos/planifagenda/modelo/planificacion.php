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
    if($codigo == ""){
      $sql = "INSERT INTO planif_clientes_trab
                          (cod_planif_cl, cod_cliente, cod_ubicacion,
                          cod_puesto_trabajo, cod_ficha, cod_rotacion, posicion_inicio,
                          posicion_fin, fecha_inicio, fecha_fin,
                          cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
                  VALUES ('$apertura', '$cliente','$ubicacion',
                          '$puesto_trab', '$ficha', '$rotacion', '$posicion',
                          '0','$fecha_inicio', '$fecha_fin',
                          '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP)";
      $query = $bd->consultar($sql);
    }else{
      $sql = "UPDATE planif_clientes_trab SET
                     cod_puesto_trabajo = '$puesto_trab', cod_ficha = '$ficha',
                     cod_rotacion = '$rotacion', posicion_inicio = '$posicion',
                     posicion_fin = '0',
                     fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin',
                     cod_us_mod = '$usuario', fec_us_mod = CURRENT_TIMESTAMP
               WHERE codigo = '$codigo' ";
     $query = $bd->consultar($sql);
    }
  $result['sql'] = $sql;
  }catch (Exception $e) {
     $error =  $e->getMessage();
     $result['error'] = true;
     $result['mensaje'] = $error;
     $bd->log_error("Aplicacion", "sc_planificacion.php",  "$usuario", "$error", "$sql");
 }

	print_r(json_encode($result));
	return json_encode($result);

?>
