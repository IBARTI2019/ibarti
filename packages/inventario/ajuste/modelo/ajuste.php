<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require "../../../../autentificacion/aut_config.inc.php";
require "../../../../".class_bdI;
$bd = new DataBase();
$result = array();

foreach($_POST as $nombre_campo => $valor){
  if($valor != "ped_reng"){
    $variables = "\$".$nombre_campo."='".$valor."';";
    eval($variables);
  }
}

//  $xx  = (isset($_POST["xx"]))?$_POST["xx"]:"";
$ped_reng = json_decode(stripslashes($_POST["ped_reng"]));
//$result = json_encode($ped_reng[0]['eans']);
//  $nombre      = htmlentities($nombre);

if(isset($_POST['metodo'])){
	try {
    if(($metodo == "agregar") or ($metodo == "modificar" or ($metodo == "anular"))){
      $anulado = "F";
      $nro_ajuste_c = "";
      if($metodo == "agregar"){
        if($proveedor == ""){
          $proveedor = '9999';
        }
        if($total == ""){
          $total = 0;
        }
        $sql = " SELECT a.n_ajuste FROM control a ";
        $query = $bd->consultar($sql);
        $data =$bd->obtener_fila($query);
        $nro_ajuste   =  $data[0];
        $cod_ajuste = $nro_ajuste + 1;
        $sql = " INSERT INTO ajuste(codigo, cod_tipo,referencia,cod_proveedor, fecha,  motivo,
        total, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod)
        VALUES ($cod_ajuste, '$tipo','$referencia','$proveedor','$fecha', '$descripcion',
        '$total', '$us', CURRENT_TIMESTAMP, '$us', CURRENT_TIMESTAMP); ";
        $bd->consultar($sql);
        $sql = " UPDATE control SET n_ajuste = $cod_ajuste; ";
        $bd->consultar($sql);
      }elseif ($metodo == "modificar") {

        $sql = "UPDATE ajuste SET
        fecha           = '$fecha',              motivo = '$descripcion',
        total           = '$total',
        cod_us_mod      = '$us',            fec_us_mod     = CURRENT_TIMESTAMP
        WHERE codigo          = $nro_ajuste;";
        $bd->consultar($sql);

        $sql = " DELETE FROM ajuste_reng WHERE cod_ajuste =  '$nro_ajuste' ;";
        $bd->consultar($sql);
      }elseif ($metodo == "anular") {
       $anulado = "T";
       $sql = " SELECT a.n_ajuste FROM control a ";
       $query = $bd->consultar($sql);
       $nro_ajuste_c = $nro_ajuste;
       $data =$bd->obtener_fila($query);
       $nro_ajuste  =  $data[0];
       $cod_ajuste = $nro_ajuste + 1;
       $sql = " UPDATE control SET n_ajuste = $cod_ajuste; ";
       $result['sql'][]=$sql;
       $bd->consultar($sql);
       $sql = "UPDATE ajuste SET anulado = 'T'
       WHERE codigo          = $nro_ajuste_c;";
       $result['sql'][]=$sql;
       $bd->consultar($sql);
       $sql = " INSERT INTO ajuste(codigo, cod_tipo,referencia,cod_proveedor, fecha,  motivo,
       total, cod_us_ing, fec_us_ing, cod_us_mod, fec_us_mod,anulado)
       VALUES ($cod_ajuste, '$tipo','$referencia','$proveedor', CURRENT_TIMESTAMP, '$descripcion',
       '$total', '$us', CURRENT_TIMESTAMP, '$us', CURRENT_TIMESTAMP,'T'); ";
       $result['sql'][]=$sql;
       $bd->consultar($sql);
     }

     foreach($ped_reng as $obj) {
      if($nro_ajuste_c == ""){
        $nro_ajuste_c = 0;
      }
      $sql = " INSERT INTO ajuste_reng (cod_ajuste, reng_num, cod_almacen,
      cod_producto,fec_vencimiento,lote, cantidad,  costo,  neto, aplicar,anulado,cod_anulado) VALUES
      ($cod_ajuste, '$obj->reng_num', '$obj->cod_almacen', '$obj->cod_producto', 
      '0000-00-00','$obj->lote',$obj->cantidad, $obj->costo, $obj->neto, '$aplicar','$anulado','$nro_ajuste_c') ";
      $result['sql_reng'][]=$sql;
      $bd->consultar($sql);
      if(count($obj->eans)>0){
        foreach($obj->eans as $ean) {
          $sql = " INSERT INTO ajuste_reng_eans(cod_ajuste, reng_num, cod_ean) VALUES
          ($cod_ajuste, '$obj->reng_num', '$ean') ";
          $bd->consultar($sql);
          if($aplicar=='IN'){
            $sql = " UPDATE prod_ean SET cod_almacen = '$obj->cod_almacen' , inStock='T'
            WHERE cod_producto = '$obj->cod_producto' AND cod_ean = '$ean'";
            $bd->consultar($sql);
          }else{
            $sql = " UPDATE prod_ean SET inStock='F'
            WHERE cod_producto = '$obj->cod_producto' AND cod_ean = '$ean'";
            $bd->consultar($sql);
          }  
        }
      }
    }
    $result["sql"] = $sql;

  }

}catch (Exception $e) {
 $error =  $e->getMessage();
 $result['error'] = true;
 $result['mensaje'] = $error;
 $bd->log_error("Aplicacion", "sistema/sc_ajuste.php",  "$us", "$error", "$sql");
}
}
print_r(json_encode($result));
return json_encode($result);
//     $bd->start();
// $bd->commit();
//  $bd->rollback();
// $bd->start();
?>
