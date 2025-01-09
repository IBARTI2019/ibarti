<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once  "../../../" . class_bdI;

$bd = new DataBase();
$result = array();

foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}
try {
  $sql = "INSERT INTO equivalencias_conceptos(cod_concepto, cod_concepto_equivalente, status, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod) 
  VALUES('$concepto', '$concepto_equivalente', '$estatus', '$usuario', CURDATE(), '$usuario', CURDATE()) 
  ON DUPLICATE KEY UPDATE status = '$estatus', cod_us_mod = '$usuario', fec_us_mod = CURDATE();";

  $query = $bd->consultar($sql);

  $result['sql'] = $sql;
} catch (Exception $e) {
  $error =  $e->getMessage();
  $result['error'] = true;
  $result['mensaje'] = $error;
  $bd->log_error("Aplicacion", "sc_equivalencia_concepto.php",  "$usuario", "$error", "$sql");
}
print_r(json_encode($result));
return json_encode($result);
