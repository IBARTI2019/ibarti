<?php
class Planif_modelo
{
	 private $datos;
	 private $bd;

	function __construct()
	{
    $this->bd = new Database;
		$this->datos = array();
	}

	public function get_horario($cod)
	{
		$this->datos = array();
	  $sql = " SELECT a.codigo, a.nombre
						 	FROM horarios a
							WHERE a.`status` = 'T' AND a.codigo <> '$cod'
							ORDER BY 2 ASC ";
	  $query = $this->bd->consultar($sql);
	  while ($datos= $this->bd->obtener_fila($query))
		{
	    $this->datos[] = $datos;
	  }
    return $this->datos;
	}

	public function get_turno($cod)
	{
		$this->datos = array();
	  $sql = " SELECT a.codigo, a.descripcion
						 	FROM turno a
							WHERE a.`status` = 'T' AND a.codigo <> '$cod'
							ORDER BY 2 ASC ";
	  $query = $this->bd->consultar($sql);
	  while ($datos= $this->bd->obtener_fila($query))
		{
	    $this->datos[] = $datos;
	  }
    return $this->datos;
	}

	public function get_rotacion($cod)
	{
		$this->datos = array();
	  $sql = " SELECT a.codigo, a.descripcion, a.abrev
						 	FROM rotacion a
							WHERE a.`status` = 'T' AND a.codigo <> '$cod'
							ORDER BY 2 ASC ";
	  $query = $this->bd->consultar($sql);
	  while ($datos= $this->bd->obtener_fila($query))
		{
	    $this->datos[] = $datos;
	  }
    return $this->datos;
	}

	function get_rotacion_det($rotacion){
		$this->datos  = array();

		$sql = "SELECT a.cod_turno, turno.abrev FROM rotacion_det a, turno
	           WHERE a.cod_rotacion = '$rotacion' AND a.cod_turno = turno.codigo
	        ORDER BY a.codigo ASC ";
		$query = $this->bd->consultar($sql);
	  while ($datos= $this->bd-> obtener_fila($query)){
	    $this->datos[] = $datos;
	  }
	  return $this->datos;
	}

	public function get_dia_habil($cod)
	{
		$this->datos = array();
		$sql = " SELECT a.codigo, a.descripcion
							FROM dias_habiles a
							WHERE a.`status` = 'T' AND a.codigo <> '$cod'
							ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->obtener_fila($query))
		{
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
?>
