<?php
define("SPECIALCONSTANT", true);
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../" . class_bdI;

$bd = new DataBase();
$result = array();

foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}
try {
  $sql = "INSERT INTO planif_proyecto_cargos(cod_proyecto, cod_cargo, status, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod) 
  VALUES('$proyecto', '$cargo', '$estatus', '$usuario', CURDATE(), '$usuario', CURDATE()) 
  ON DUPLICATE KEY UPDATE status = '$estatus', cod_us_mod = '$usuario', fec_us_mod = CURDATE();";

  $query = $bd->consultar($sql);

  $result['sql'] = $sql;
} catch (Exception $e) {
  $error =  $e->getMessage();
  $result['error'] = true;
  $result['mensaje'] = $error;
  $bd->log_error("Aplicacion", "sc_perfil_cargo.php",  "$usuario", "$error", "$sql");
}
print_r(json_encode($result));
return json_encode($result);
