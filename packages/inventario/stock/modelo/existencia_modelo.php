<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../".class_bdI;

class Existencia
{
  private $datos;
  private $bd;

  function __construct()
  {
    $this->datos   = array();
    $this->bd = new Database;
  }

  public function get_prod_linea(){
    $this->datos   = array();
    $sql = " SELECT a.codigo, a.descripcion FROM prod_lineas a
    WHERE a.`status` = 'T'
    ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_prod_sub_linea($linea){
    $this->datos   = array();
    $sql = " SELECT a.codigo, a.descripcion FROM prod_sub_lineas a
    WHERE a.`status` = 'T'";
    if($linea != "TODOS"){
      $sql .= " AND a.cod_linea = '$linea'";
    } 
    $sql .= " ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_prod_productos($sub_linea){
    $this->datos   = array();
    $sql = " SELECT a.item codigo, CONCAT(a.descripcion,' (',a.item,') ') descripcion FROM productos a
    WHERE a.`status` = 'T'";
    if($sub_linea != "TODOS"){
      $sql .= " AND a.cod_sub_linea = '$sub_linea'";
    } 
    $sql .= " ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_prod_almacenes(){
    $this->datos   = array();
    $sql = " SELECT a.codigo, a.descripcion FROM almacenes a
    WHERE a.`status` = 'T' ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_prod_almacen_stock($producto){
    $this->datos   = array();
    $sql = " SELECT a.codigo, a.descripcion FROM almacenes a ,stock b
    WHERE b.cod_almacen = a.codigo 
    AND b.cod_producto = '$producto' ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function buscar($linea,$sub_linea,$producto,$almacen){
    $sql = "SELECT c.descripcion almacen, b.item serial, b.descripcion producto, a.stock_actual, 
      IFNULL((SELECT d.importe FROM ajuste_reng d
    WHERE  d.cod_almacen = a.cod_almacen 
    AND d.cod_producto = a.cod_producto
    ORDER BY d.cod_ajuste DESC, d.reng_num DESC LIMIT 1),'SIN DATA') importe,
    IFNULL((SELECT e.cos_promedio FROM ajuste_reng e
    WHERE e.cod_almacen = a.cod_almacen 
    AND e.cod_producto = a.cod_producto
    ORDER BY e.cod_ajuste DESC, e.reng_num DESC LIMIT 1),'SIN DATA') cos_prom_actual
    FROM stock a, productos b, almacenes c 
    WHERE a.cod_producto = b.item AND a.cod_almacen = c.codigo AND c.status = 'T'
    ";
    if($linea != "TODOS"){
      $sql .= " AND b.cod_linea = '$linea'";
    } 
    if($sub_linea != "TODOS"){
      $sql .= " AND b.cod_sub_linea = '$sub_linea'";
    } 
    if($producto != "TODOS"){
      $sql .= " AND a.cod_producto = '$producto'";
    } 
    if($almacen != "TODOS"){
      $sql .= " AND a.cod_almacen = '$almacen'";
    } 
    $sql .= " ORDER BY 1,2 ASC";
    $query        = $this->bd->consultar($sql);
    while ($datos = $this->bd->obtener_fila($query)){
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

    public function buscar_inicio(){
    $sql = "SELECT c.descripcion almacen, b.item serial, b.descripcion producto, a.stock_actual, 
      IFNULL((SELECT d.importe FROM ajuste_reng d
    WHERE  d.cod_almacen = a.cod_almacen 
    AND d.cod_producto = a.cod_producto
    ORDER BY d.cod_ajuste DESC, d.reng_num DESC LIMIT 1),'SIN DATA') importe,
    IFNULL((SELECT e.cos_promedio FROM ajuste_reng e
    WHERE e.cod_almacen = a.cod_almacen 
    AND e.cod_producto = a.cod_producto
    ORDER BY e.cod_ajuste DESC, e.reng_num DESC LIMIT 1),'SIN DATA') cos_prom_actual
    FROM stock a, productos b, almacenes c 
    WHERE a.cod_producto = b.item AND a.cod_almacen = c.codigo AND c.status = 'T'
    ORDER BY 1,2 ASC LIMIT 100";
    $query        = $this->bd->consultar($sql);
    while ($datos = $this->bd->obtener_fila($query)){
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

}
?>
