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

if (isset($_POST['metodo'])) {
  try {
    if ($metodo == "agregar") {
      $sql  = "INSERT INTO planif_clientes_superv
      (codigo, cod_ubicacion, cod_cargo,fecha_inicio, fecha_fin,
      cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod,  `status`)
      VALUES (NULL, $ubic, '$cargo', '$fec_inicio', '$fec_fin',
      '$usuario', CURRENT_TIMESTAMP, '$usuario', CURRENT_TIMESTAMP, 'T');";
    } elseif ($metodo == "cerrar") {
      $sql  = "UPDATE planif_clientes_superv SET `status` = 'F', cod_us_mod = '$usuario',
      fec_us_mod = CURRENT_TIMESTAMP
      WHERE codigo = '$codigo'";
    } elseif ($metodo == "modificar_apertura") {
      $sql  = "UPDATE clientes_supervision_ap
      SET clientes_supervision_ap.cantidad = '$cantidad',clientes_supervision_ap.cod_us_mod = '$usuario',clientes_supervision_ap.fec_us_mod = CURRENT_TIMESTAMP
      WHERE
      clientes_supervision_ap.codigo = '$apertura'
      AND clientes_supervision_ap.cod_cliente  = '$cliente'
      AND clientes_supervision_ap.cod_ubicacion = '$ubicacion'
      AND clientes_supervision_ap.cod_turno = '$turno'
      AND clientes_supervision_ap.fecha = '$fecha'
      ";
    }

    $query = $bd->consultar($sql);

    $result['sql'] = $sql;
    $result['error'] = false;
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sc_planificacion_aoertura.php",  "$usuario", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
