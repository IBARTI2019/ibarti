<?php
define("SPECIALCONSTANT", true);

include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);
$bd = new DataBase();
$result = array();
$eans = [];
foreach($_POST as $nombre_campo => $valor){
  if($nombre_campo != "eans"){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }
}
$fecha_actual = date('Y-m-d H:i:s');

if(isset($_POST["eans"])){
  $eans = $_POST["eans"];
} else {
  $eans = [];
}

if(isset($_POST['proced'])){
  try {
    if($fec_venc == 'DD-MM-AAAA') $fec_venc = '0000-00-00';
    if($peso == "") $peso = 0;
    if($piecubico == "") $piecubico = 0;
    
    $sql = "$SELECT $proced('$metodo', '$codigo', '$linea', '$sub_linea', '$color', '$prod_tipo',
    '$unidad',  '$proveedor','$procedencia','$almacen', '$iva','$item', '$descripcion',    
    '$garantia', '$talla','$peso', '$piecubico','$venc', '$fec_venc',
    '$campo01', '$campo02', '$campo03', '$campo04', '$usuario', '$activo','$ean')";

    $result['sql'][] = $sql;
    $query = $bd->consultar($sql);

    if($ean == 'T'){
      // Obtener EANs actuales de la base de datos
      $sql_existing = "SELECT cod_ean FROM prod_ean WHERE cod_producto = '$item'";
      $query_existing = $bd->consultar($sql_existing);
      $existing_eans = array();
      
      while ($row = $bd->obtener_fila($query_existing)) {
        $existing_eans[] = $row['cod_ean'];
      }
      
      $new_eans = $eans; // Los EANs que vienen del formulario
      
      // EANs a eliminar (están en la BD pero no en el nuevo array)
      $eans_to_delete = array_diff($existing_eans, $new_eans);
      
      // EANs a insertar (están en el nuevo array pero no en la BD)
      $eans_to_insert = array_diff($new_eans, $existing_eans);
      
      // Eliminar solo los EANs que ya no están en la lista y que NO tienen movimientos
      if (!empty($eans_to_delete)) {
        $eans_delete_list = "'" . implode("','", $eans_to_delete) . "'";
        $sql_delete = "DELETE FROM prod_ean 
                       WHERE cod_producto = '$item' 
                       AND cod_ean IN ($eans_delete_list) 
                       AND cod_ean NOT IN (SELECT cod_ean FROM ajuste_reng_eans)
                       AND cod_ean NOT IN (SELECT cod_ean FROM prod_asignacion_eans)
                       AND cod_ean NOT IN (SELECT cod_ean FROM ajuste_alcance_reng_eans)";
        $bd->consultar($sql_delete);
        $result['sql'][] = $sql_delete;
      }
      
      // Insertar solo los nuevos EANs
      foreach($eans_to_insert as $eanX) {
        try { 
          $sql_insert = "INSERT INTO prod_ean(cod_producto,cod_ean,cod_almacen,cod_us_ing,fec_us_ing,cod_us_mod,fec_us_mod) VALUES('$item','$eanX','$almacen','$usuario','$fecha_actual','$usuario','$fecha_actual')";
          $bd->consultar($sql_insert);
          $result['sql'][] = $sql_insert;
        } catch (Exception $e) { 
          $result['sqlException'][] = $sql_insert;
        } 
      }
      
      $result['eans_processed'] = [
        'existing' => $existing_eans,
        'to_delete' => array_values($eans_to_delete),
        'to_insert' => array_values($eans_to_insert),
        'final' => $new_eans
      ];
    }

  } catch (Exception $e) {
    $error = $e->getMessage();
    $result['error'] = true;
    $result['mensaje'] = $error;

    $bd->log_error("Aplicacion", "sc_producto.php", "$usuario", "$error", "$sql");
  }
}

print_r(json_encode($result));
return json_encode($result);
?>