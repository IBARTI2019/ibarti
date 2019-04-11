<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../".class_bdI);

class Producto
{
  private $datos;
  private $bd;
  private $sub_linea;
  private $modelo;
  private $colores;

  function __construct()
  {
    $this->datos     = array();
    $this->bd        = new Database;
    $this->sub_linea     = array();
    $this->modelo     = array();
    $this->colores     = array();
  }

  public function get($linea,$sub_linea,$prod_tipo,$tipo_mov,$filtro,$producto)
  {
    $where =" WHERE productos.cod_linea     = prod_lineas.codigo 
    AND productos.cod_sub_linea = prod_sub_lineas.codigo 
    AND productos.cod_prod_tipo = prod_tipos.codigo ";

    if($linea != "TODOS"){    
      $where .= " AND prod_lineas.codigo  = '$linea' ";
    }
    if($sub_linea != "TODOS" and $sub_linea != ""){
      $where .= "  AND prod_sub_lineas.codigo = '$sub_linea' ";
    }

    if($prod_tipo != "TODOS"){
      $where .= "  AND prod_tipos.codigo = '$prod_tipo' ";
    }

    if($tipo_mov != "TODOS"){
      $where .= "  AND v_prod_ultimo_mov.cod_mov_tipo = '$tipo_mov' ";
    }

    if(($filtro != "TODOS") and ($producto) != ""){
      $where .= "  AND productos.codigo = '$producto' ";
    }

    $sql = "SELECT productos.codigo, prod_lineas.descripcion AS linea,
    prod_sub_lineas.descripcion AS sub_linea, prod_tipos.descripcion AS prod_tipo,
    productos.descripcion, IFNULL(v_prod_ultimo_mov.mov_tipo , 'SIN MOVIMIENTO') AS mov_tipo,
    productos.status
    FROM productos LEFT JOIN v_prod_ultimo_mov ON productos.codigo = v_prod_ultimo_mov.cod_producto ,
    prod_lineas , prod_sub_lineas , prod_tipos , control
    $where           
    ORDER BY productos.codigo ASC ";

    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->  obtener_fila($query))
    {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function inicio()
  {

    $this->datos = array('codigo' => '',  'cod_linea' => '','linea' => 'Seleccione...',
      'cod_sub_linea' => '','sub_linea' => 'Seleccione...', 
      'cod_prod_tipo' => '','prod_tipo' => 'Seleccione...',
      'cod_unidad' => '','unidad' => 'Seleccione...', 
      'cod_color' => '','modelo' => 'Seleccione...', 'item' => '',
      'cod_proveedor' => '','proveedor' => 'Seleccione...',
      'cod_procedencia' => '','procedencia' => 'Seleccione...',
      'cod_iva' => '','iva' => 'Seleccione...','descripcion' => '',
      'cos_actual' => '','fec_cos_actual' => '','cos_promedio' => '','fec_cos_prom' => '',
      'cos_ultimo' => '','fec_cos_ultimo' => '','stock_actual' => '','stock_comp' => '',
      'stock_llegar' => '','punto_pedido' => '','stock_maximo' => '','stock_minimo' => '',
      'garantia' => '','talla' => '','peso' => '','vencimiento' => 'F','fec_vencimiento' => '',
      'fec_prec_v1' => '','prec_vta1' => '','fec_prec_v2' => '','prec_vta2' => '',
      'fec_prec_v3' => '','prec_vta3' => '', 'fec_prec_v4' => '','prec_vta4' => '',
      'fec_prec_v5' => '','prec_vta5' => '', 'piecubico' => '','campo01' => '',
      'campo02' => '','campo03' => '','campo04' => '', 
      'cos_actual' => '', 'fec_cos_actual' => '','cos_promedio' => '', 'fec_cos_prom' => '',
      'cos_ultimo' => '', 'fec_cos_ultimo' => '','stock_actual' => '', 'stock_comp' => '',
      'stock_llegar' => '', 'punto_pedido' => '','stock_maximo' => '', 'stock_minimo' => '',
      'prec_vta1' => '', 'fec_prec_vta1' => '','prec_vta2' => '', 'fec_prec_vta2' => '',
      'prec_vta3' => '', 'fec_prec_vta3' => '','prec_vta4' => '', 'fec_prec_vta4' => '',
      'garantia' => '', 'talla' => '','peso' => '', 'piecubico' => '',
      'venc' => 'F', 'fec_venc' => '','campo01' => '', 'campo02' => '','campo03' => '','campo04' => '',
      'prec_vta5' => '', 'fec_prec_vta5' => '','status' => 'T');
    return $this->datos;
  }

  public function editar($cod)
  {
    $sql = " SELECT productos.codigo, productos.cod_linea, prod_lineas.descripcion AS linea, productos.cod_sub_linea,
    prod_sub_lineas.descripcion AS sub_linea, productos.cod_modelo, modelo.descripcion AS modelo,
    productos.cod_prod_tipo, prod_tipos.descripcion AS prod_tipo, productos.cod_unidad,
    unidades.descripcion AS unidad,
    productos.cod_proveedor, proveedores.nombre AS proveedor, productos.cod_procedencia, 
    prod_procedencia.descripcion AS procedencia,  productos.cod_iva,
    iva.descripcion AS iva, productos.descripcion,
    productos.cos_actual, productos.fec_cos_actual, productos.cos_promedio,
    productos.fec_cos_prom, productos.cos_ultimo, productos.fec_cos_ultimo,
    productos.stock_actual, productos.stock_comp, productos.stock_llegar,
    productos.punto_pedido, productos.stock_maximo, productos.stock_minimo,
    productos.garantia, productos.talla, productos.peso,
    productos.vencimiento, productos.fec_vencimiento,
    productos.fec_prec_v1, productos.prec_vta1,
    productos.fec_prec_v2, productos.prec_vta2,
    productos.fec_prec_v3, productos.prec_vta3,
    productos.fec_prec_v4, productos.prec_vta4,
    productos.fec_prec_v5, productos.prec_vta5,
    productos.piecubico, productos.campo01,
    productos.campo02, productos.campo03,
    productos.campo04, productos.`status`
    FROM productos , prod_lineas , prod_sub_lineas , colores , prod_tipos ,
    unidades , proveedores , iva, prod_procedencia
    WHERE productos.cod_linea = prod_lineas.codigo 
    AND productos.cod_sub_linea = prod_sub_lineas.codigo 
    AND productos.cod_color = colores.codigo 
    AND productos.cod_prod_tipo = prod_tipos.codigo 
    AND productos.cod_unidad = unidades.codigo 
    AND productos.cod_proveedor = proveedores.codigo 
    AND productos.cod_procedencia = prod_procedencia.codigo
    AND productos.cod_iva = iva.codigo
    AND productos.codigo = '$cod'";

    $query = $this->bd->consultar($sql);
    return $this->producto = $this->bd->obtener_fila($query);
  }

  public function get_lineas(){

    $sql = "SELECT codigo,descripcion FROM prod_lineas WHERE `status`='T' ORDER BY descripcion ASC";
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->obtener_fila($query)) {
      $this->linea[] = $datos;
    }
    return $this->linea;
  }

  public function get_sub_lineas($linea){
    $sql = "SELECT codigo, descripcion FROM prod_sub_lineas
    WHERE cod_linea = '$linea'
    AND status = 'T'
    ORDER BY descripcion ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->sub_linea[] = $datos;
    }
    return $this->sub_linea;
  }

   public function get_colores(){
    $sql = "SELECT codigo, descripcion FROM colores
    WHERE status = 'T'
    ORDER BY descripcion ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->colores[] = $datos;
    }
    return $this->colores;
  }

  public function get_modelos($sub_linea){
    $sql = "SELECT codigo, descripcion FROM prod_modelo WHERE cod_sub_linea = '$sub_linea' AND `status` = 'T' 
    ORDER BY 2 ";
    $query = $this->bd->consultar($sql);
    while ($datos = $this->bd->obtener_fila($query)) {
      $this->modelo[] = $datos;
    }
    return $this->modelo;
  }

  public function get_modelo_det($modelo){
    $sql = "SELECT prod_modelo.color, prod_modelo.talla, prod_modelo.peso, prod_modelo.piecubico
    FROM prod_modelo WHERE prod_modelo.codigo = '$modelo'";
    $query = $this->bd->consultar($sql);
    return $this->modelo = $this->bd->obtener_fila($query);
  } 

  public function get_tipos(){
    $sql = "SELECT codigo, descripcion FROM prod_tipos WHERE `status` = 'T' ORDER BY 2 ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->tipo[] = $datos;
    }
    return $this->tipo;
  }

  public function get_unidades(){
    $sql = "SELECT codigo, descripcion FROM unidades WHERE `status` = 'T' ORDER BY 2 ASC";
    $query = $this->bd->consultar($sql);
    while ($datos= $this->bd->obtener_fila($query)) {
      $this->unidad[] = $datos;
    }
    return $this->unidad;
  }

  public function buscar_producto($data,$filtro){
    $where="";
    if ($data) $where =" WHERE producto.$filtro LIKE '%$data%' ";

    $sql = "SELECT * FROM producto ".$where." ORDER BY 2 ASC";
    $query         = $this->bd->consultar($sql);

    while ($datos= $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function buscar($data){
    $sql = "SELECT productos.codigo, prod_lineas.descripcion AS linea, 
    prod_sub_lineas.descripcion AS sub_linea,  prod_tipos.descripcion AS prod_tipo, 
    productos.descripcion,  IFNULL(v_prod_ultimo_mov.mov_tipo , 'SIN MOVIMIENTO') AS mov_tipo,
    productos.status
    FROM productos LEFT JOIN v_prod_ultimo_mov ON productos.codigo = v_prod_ultimo_mov.cod_producto , prod_lineas , prod_sub_lineas , prod_tipos ,  control
    WHERE productos.cod_linea = prod_lineas.codigo 
    AND productos.cod_sub_linea = prod_sub_lineas.codigo 
    AND productos.cod_prod_tipo = prod_tipos.codigo 
    AND productos.fec_us_mod  BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()   
    ORDER BY productos.codigo ASC ";

    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_tipo_mov(){
    $sql = "SELECT prod_mov_tipo.codigo, prod_mov_tipo.descripcion,
    prod_mov_tipo.tipo_movimiento FROM prod_mov_tipo ORDER BY 2 ASC";

    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }
  
}
?>