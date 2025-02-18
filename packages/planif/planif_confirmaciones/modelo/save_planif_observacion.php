<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();
$result['error'] = false;

$codigo = $_POST["codigo"];
$observacion = $_POST["observacion"];
$usuario = $_POST["usuario"];

if (isset($codigo)) {
  try {
      $sql    = "UPDATE planif_clientes_trab_det SET observacion = '$observacion', cod_us_in_observacion = '$usuario', fec_observacion = CURRENT_TIMESTAMP WHERE codigo = $codigo;";

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
