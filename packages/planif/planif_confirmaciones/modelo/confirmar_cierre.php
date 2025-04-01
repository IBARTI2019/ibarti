<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();
$result['error'] = false;

if (isset($_POST['codigos'])) {
  try {
    $codigos = $_POST['codigos'];
    $usuario = $_POST['usuario'];
    $documentUrl = $_POST['documentUrl'];

    $codigos_str = implode(",", $codigos);

    $sql    = "UPDATE planif_clientes_trab_det SET cierre_confirmado = 'T', cod_us_cierre = '$usuario', fec_cierre = CURRENT_TIMESTAMP, documento_cierre = '$documentUrl' WHERE codigo IN ($codigos_str) AND cierre_confirmado = 'F';";
    $query = $bd->consultar($sql);
    $result['sql'] = $sql;
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "confirmar_cierre.php",  "$usuario", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
