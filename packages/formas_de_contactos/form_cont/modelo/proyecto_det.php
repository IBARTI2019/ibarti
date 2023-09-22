<?php
define("SPECIALCONSTANT", true);
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();

foreach ($_POST as $nombre_campo => $valor) {
  $variables = "\$" . $nombre_campo . "='" . $valor . "';";
  eval($variables);
}

if (isset($_POST['proced'])) {

  try {
    $sql    = "$SELECT $proced('$metodo','$descripcion','$codigo','$usuario', '$proyecto','$act')";
    $query   = $bd->consultar($sql);
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;

    $bd->log_error("Aplicacion", "sc_proyecto.php",  "$usuario", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
