<?php
define("SPECIALCONSTANT", true);

include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  if($nombre_campo != "eans"){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }
}
$fecha_actual = date('Y-m-d H:i:s');
$eans = $_POST["eans"];

if(isset($_POST['proced'])){
  try {
    $sql    = "$SELECT $proced('$metodo', '$codigo', '$linea', '$sub_linea', '$color', '$prod_tipo',
    '$unidad',  '$proveedor','$procedencia','$almacen', '$iva','$item', '$descripcion', 
    '$prec_vta1', '$prec_vta2',' $prec_vta3', '$prec_vta4',  '$prec_vta5',   
    '$garantia', '$talla','$peso', '$piecubico','$venc', '$fec_venc',
    '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo','$ean')";

    $query   = $bd->consultar($sql);

    $result['sql'][] = $sql;

    if($ean == 'T'){
        $sql = "DELETE FROM prod_ean WHERE cod_producto = '$item' ";
        $query   = $bd->consultar($sql);
        foreach($eans as $eanX) {
          $sql = "INSERT INTO prod_ean(cod_producto,cod_ean,cod_us_ing,fec_us_ing,cod_us_mod,fec_us_mod) VALUES('$item','$eanX','$usuario','$fecha_actual','$usuario','$fecha_actual')";
          $query   = $bd->consultar($sql);
          $result['sql'][] = $sql;
          $result['eans'][] = $ean;
      }
    }

  }catch (Exception $e) {
   $error =  $e->getMessage();
   $result['error'] = true;
   $result['mensaje'] = $error;

   $bd->log_error("Aplicacion", "sc_producto.php",  "$usuario", "$error", "$sql");
 }

}
print_r(json_encode($result));
return json_encode($result);

?>
