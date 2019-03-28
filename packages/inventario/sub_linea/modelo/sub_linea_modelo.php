<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);

class ProductoSubLinea
{
  private $datos;
  private $propiedades;
  private $lineas;
  private $bd;

  function __construct()
  {
    $this->datos     = array();
    $this->propiedades     = array();
    $this->lineas     = array();
    $this->bd        = new Database;
  }

  public function inicio()
  {

    $this->datos = array('codigo' => '',  'descripcion' => '',
      'color' => 'F', 'talla' => 'F','peso' => 'F','piecubico' => 'F','status' => 'T','json' => []);
    return $this->datos;
  }

  public function editar($cod)
  {
    $sql = " SELECT prod_sub_lineas.codigo, prod_sub_lineas.cod_linea, prod_sub_lineas.descripcion,
    prod_sub_lineas.status,prod_sub_lineas.json
    FROM prod_lineas, prod_sub_lineas WHERE prod_lineas.codigo = prod_sub_lineas.cod_linea AND prod_sub_lineas.codigo = '$cod'";

    $query = $this->bd->consultar($sql);
    return $this->producto = $this->bd->obtener_fila($query);
  }


  public function get_lineas(){
    $sql = "SELECT codigo, descripcion FROM prod_lineas
    WHERE  status = 'T'
    ORDER BY descripcion ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->lineas[] = $datos;
    }
    return $this->lineas;
  } 


  public function get_propiedades($cod){
    $WHERE = " WHERE  status = 'T'";
    if($cod != null){
      $WHERE .= " AND codigo = '$cod' ";
    }
    $sql = "SELECT codigo, descripcion FROM prod_propiedades
    $WHERE
    ORDER BY descripcion ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->propiedades[] = $datos;
    }
    return $this->propiedades;
  } 

  public function buscar($data){
    $where = " WHERE prod_lineas.codigo = prod_sub_lineas.cod_linea ";
    if($data != null || $data != ""){
      $where .= "  AND (prod_sub_lineas.codigo LIKE '%$data%' OR  prod_sub_lineas.descripcion LIKE '%$data%') ";
    }
    $sql = "SELECT prod_sub_lineas.codigo,prod_lineas.descripcion linea, prod_sub_lineas.descripcion,prod_sub_lineas.`status` 
    FROM prod_lineas,prod_sub_lineas
    $where ";

    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

}
?>
