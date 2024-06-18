<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();
$result['error'] = false;
foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}

if (isset($codigo)) {
  try {
    if($in_transport == 'T'){
      $sql    = "UPDATE planif_clientes_trab_det SET in_transport = 'T', cod_us_in_transport = '$usuario', fec_in_transport = CURRENT_TIMESTAMP WHERE codigo = '$codigo'";
    }else{
      $sql    = "UPDATE planif_clientes_trab_det SET confirm = 'T', cod_us_confirm = '$usuario', fec_confirm = CURRENT_TIMESTAMP WHERE codigo = '$codigo'";
    }
    $query = $bd->consultar($sql);
    $result['sql'] = $sql;
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_confirmaciones_supervisor.php",  "$usuario", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
