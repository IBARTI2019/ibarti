<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../".class_bdI;

class Horario
{
  private $datos;
  private $horario;
  private $concepto;
  private $bd;

  function __construct()
  {
    $this->datos   = array();
    $this->horario = array();
    $this->concepto = array();
    $this->bd = new Database;
  }

  public function get(){
    $sql = " SELECT * FROM horarios ORDER BY 2 ASC ";
    //    $query =  parent::consultar($sql);
    $query = $this->bd->consultar($sql);

    while ($datos= $this->bd->  obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function inicio(){
   $this->horario = array('codigo' => '',  'nombre' => '',
    'cod_concepto' => '', 'concepto' => 'Seleccione...',
    'abrev' => '','hora_entrada' => '',
    'hora_salida' => '', 'inicio_marc_entrada' => '',
    'fin_marc_entrada' => '', 'inicio_marc_salida' => '',
    'fin_marc_salida' => '', 'dia_trabajo' => '',
    'minutos_trabajo' => '', 'horas_trabajo' => '0.00', 'tipo_hora_extra' => 'DIURNO', 'status' => 'T');
   return $this->horario;
 }

 public function editar($cod){

  $sql = " SELECT horarios.codigo, horarios.nombre,
  horarios.cod_concepto, conceptos.descripcion AS concepto,
  conceptos.abrev,
  horarios.hora_entrada, horarios.hora_salida,
  horarios.inicio_marc_entrada, horarios.fin_marc_entrada,
  horarios.inicio_marc_salida, horarios.fin_marc_salida,
  horarios.dia_trabajo, horarios.minutos_trabajo,
  horarios.horas_trabajo, horarios.tipo_hora_extra,
  horarios.`status`
  FROM horarios, conceptos
  WHERE horarios.codigo = '$cod'
  AND horarios.cod_concepto = conceptos.codigo ";

  $query = $this->bd->consultar($sql);
  return $this->horario = $this->bd->obtener_fila($query);
}

public function get_concepto($cod){

  $sql = " SELECT a.codigo, a.descripcion,  a.abrev
  FROM conceptos AS a
  WHERE a.`status` = 'T'
  AND a.asist_diaria = 'T'
  AND a.codigo <> '$cod'; ";
  $query = $this->bd->consultar($sql);

  while ($datos= $this->bd->obtener_fila($query)) {
    $this->concepto[] = $datos;
  }
  return $this->concepto;
}

public function buscar_horario($data,$filtro){
 $where="";
 if ($data) $where =" WHERE horarios.$filtro LIKE '%$data%' ";

 $sql = "SELECT * FROM horarios ".$where." ORDER BY 2 ASC";
 $query         = $this->bd->consultar($sql);

 while ($datos= $this->bd->obtener_fila($query)) {
  $this->datos[] = $datos;
}
return $this->datos;
}
}
?>
