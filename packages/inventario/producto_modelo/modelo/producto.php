<?php
define("SPECIALCONSTANT", true);

include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  $variables = "\$".$nombre_campo."='".$valor."';";
  eval($variables);
}

if(isset($_POST['proced'])){
  try {

    $sql    = "$SELECT $proced('$metodo', '$codigo', '$linea', '$sub_linea', '$color', '$prod_tipo',
    '$unidad',  '$proveedor','$procedencia', '$iva','$descripcion', '$cos_actual', '$cos_promedio',
    '$cos_ultimo', '$stock_actual', '$stock_comp', '$stock_llegar', '$punto_pedido', '$stock_maximo', 
    '$stock_minimo', '$prec_vta1', '$prec_vta2',' $prec_vta3', '$prec_vta4',  '$prec_vta5',   
    '$garantia', '$talla','$peso', '$piecubico','$venc', '$fec_venc',
    '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo')";

    $query   = $bd->consultar($sql);
    $result['sql'] = $sql;

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