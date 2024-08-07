<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../" . class_bdI;
$bd = new DataBase();
$result = array();

foreach ($_POST as $nombre_campo => $valor) {
  if ($valor != "ped_reng") {
    $variables = "\$" . $nombre_campo . "='" . $valor . "';";
    eval($variables);
  }
}

//  $xx  = (isset($_POST["xx"]))?$_POST["xx"]:"";
$ped_reng = json_decode(stripslashes($_POST["ped_reng"]));
//$result = json_encode($ped_reng[0]['eans']);
//  $nombre      = htmlentities($nombre);

if (isset($_POST['metodo'])) {
  try {
    if (($metodo == "agregar") || ($metodo == "anular")) {
      $anulado = "F";
      $nro_stock_alcance_c = 0;
      if ($metodo == "agregar") {
        $sql = " INSERT INTO ajuste_alcance(cod_ubicacion, fecha, motivo, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
        VALUES ('$ubic','$fecha', '$descripcion', '$us', CURRENT_TIMESTAMP, '$us', CURRENT_TIMESTAMP); ";
        $bd->consultar($sql);
        $sql = " SELECT MAX(codigo) FROM ajuste_alcance WHERE cod_us_ing = '$us';";
        $query = $bd->consultar($sql);
        $data = $bd->obtener_fila($query);
        $cod_stock_alcance   =  $data[0];

        foreach ($ped_reng as $obj) {
          $sql = " INSERT INTO ajuste_alcance_reng (cod_ajuste, reng_num,
        cod_producto, cod_almacen, cantidad, aplicar, cod_anulado) VALUES
        ($cod_stock_alcance, '$obj->reng_num','$obj->cod_producto', '$obj->cod_almacen',
        $obj->cantidad, 'OUT',$nro_stock_alcance_c) ";
          $result['sql_reng'][] = $sql;
          $bd->consultar($sql);
          if (count($obj->eans) > 0) {
            foreach ($obj->eans as $ean) {
              $sql = " INSERT INTO ajuste_alcance_reng_eans(cod_ajuste, reng_num, cod_ean) VALUES
            ($cod_stock_alcance, '$obj->reng_num', '$ean') ";
              $bd->consultar($sql);
              $sql = " UPDATE prod_ean SET inStock='F' WHERE cod_producto = '$obj->cod_producto' AND cod_ean = '$ean'";
              $bd->consultar($sql);
            }
          }
        }
      } elseif ($metodo == "anular") {
        $anulado = "T";
        $sql = "UPDATE ajuste_alcance SET anulado = '$anulado', descripcion_anulacion= '$descripcion'
          WHERE codigo = " . $codigo . "";
        $result['sql'][] = $sql;
        $bd->consultar($sql);
      }
    }
  } catch (Exception $e) {
    $error =  $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;
    $bd->log_error("Aplicacion", "sistema/sc_stock_alcance.php",  "$us", "$error", "$sql");
  }
}
print_r(json_encode($result));
return json_encode($result);
