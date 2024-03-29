<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../" . class_bdI;

class Proyecto
{
  private $datos;
  private $horario;
  private $concepto;
  private $bd;

  function __construct()
  {
    $this->datos   = array();
    $this->bd = new Database;
  }

  public function get()
  {
    $sql = " SELECT * FROM planif_proyecto ORDER BY 2 ASC ";
    $query = $this->bd->consultar($sql);
    while ($datos = $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function inicio()
  {
    $this->datos = array(
      'codigo' => '',  'descripcion' => '',
      'abrev' => '', 'status' => 'T'
    );
    return $this->datos;
  }

  public function editar($cod)
  {
    $this->datos   = array();
    $sql = " SELECT a.*, b.codigo cod_area, IFNULL(b.descripcion, 'SIN DEFINIR') area 
    FROM planif_proyecto a LEFT JOIN area_proyecto b ON a.cod_area = b.codigo
            WHERE a.codigo = '$cod' ";
    $query = $this->bd->consultar($sql);
    return $this->datos = $this->bd->obtener_fila($query);
  }

  public function get_planif_actividad($proyecto)
  {
    $this->datos   = array();
    $sql = " SELECT planif_actividad.codigo, planif_actividad.descripcion,
            planif_actividad.minutos, planif_actividad.obligatoria,  planif_actividad.participantes
            FROM planif_actividad
            WHERE planif_actividad.cod_proyecto = '$proyecto'
          ORDER BY 1 ASC ";
    $query = $this->bd->consultar($sql);

    while ($datos = $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_planif_cargos($proyecto)
  {
    $this->datos   = array();
    $sql = " SELECT
        cargos.codigo,
        cargos.descripcion,
        IFNULL(ppc.cod_cargo, 'NO') existe,
        ppc.`status`
      FROM
        cargos
        LEFT JOIN planif_proyecto_cargos ppc ON ppc.cod_proyecto = '$proyecto'
        AND ppc.cod_cargo = cargos.codigo
      WHERE
        cargos.`status` = 'T'
        AND cargos.planificable = 'T'
        ORDER BY 2 ASC";
    $query = $this->bd->consultar($sql);

    while ($datos = $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }

  public function get_turno_det($turno)
  {
    $this->datos   = array();
    $sql = " SELECT horarios.nombre horario, dias_habiles.descripcion dh
             FROM turno , horarios , dias_habiles
            WHERE  turno.codigo = '$turno'
              AND turno.cod_dia_habil = dias_habiles.codigo
              AND turno.cod_horario = horarios.codigo ";
    $query = $this->bd->consultar($sql);
    return $this->datos = $this->bd->obtener_fila($query);
  }

  public function buscar_proyecto($data, $filtro)
  {
    $where = "";
    if ($data) $where = " WHERE a.$filtro LIKE '%$data%' ";

    $sql   = "SELECT * FROM proyecto a " . $where . " ORDER BY 2 ASC";
    $query = $this->bd->consultar($sql);

    while ($datos = $this->bd->obtener_fila($query)) {
      $this->datos[] = $datos;
    }
    return $this->datos;
  }
}
