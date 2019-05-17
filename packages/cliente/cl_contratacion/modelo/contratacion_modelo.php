<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../".class_bdI;

class Contratacion
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	public function get($cliente){
		$this->datos   = array();
		$sql = " SELECT * FROM clientes_contratacion a WHERE a.cod_cliente = '$cliente' ORDER BY 3 DESC ";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->  obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function inicio(){
		$this->datos   = array();
		$this->datos   = array('codigo' => '',  'descripcion' => '',
			'fecha_inicio' => '', 'fecha_fin' => '',
			'status' => 'T');
		return $this->datos;
	}

	public function editar($cod){
		$this->datos   = array();
		$sql = " SELECT * FROM clientes_contratacion
		WHERE clientes_contratacion.codigo = '$cod'  ";
		$query = $this->bd->consultar($sql);
		return 	$this->datos = $this->bd->obtener_fila($query);

	}

	public function get_cont_det($contratacion){
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.cod_contracion,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_ub_puesto, clientes_ub_puesto.nombre  puesto,
		a.cod_turno, turno.descripcion turno,
		a.cod_cargo, cargos.descripcion cargo,
		a.cantidad, a.cod_us_ing,
		a.fec_us_ing, a.cod_us_mod,
		a.fec_us_mod
		FROM clientes_contratacion_det a , clientes_ubicacion ,
		clientes_ub_puesto, turno, cargos
		WHERE a.cod_contracion = '$contratacion'
		AND a.cod_ubicacion = clientes_ubicacion.codigo
		AND a.cod_ub_puesto = clientes_ub_puesto.codigo
		AND a.cod_turno = turno.codigo
		AND a.cod_cargo = cargos.codigo ORDER BY 3,5 ASC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->cont_det[] = $datos;
		}
		return $this->cont_det;
	}

	public function get_ubicacion($cliente){
		$this->datos   = array();
		$sql = " SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
		FROM clientes_ubicacion
		WHERE clientes_ubicacion.cod_cliente = '$cliente'
		AND clientes_ubicacion.`status` = 'T'
		ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->  obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_turno(){
		$this->datos   = array();
		$sql = "SELECT turno.codigo, turno.descripcion FROM turno
		WHERE turno.`status` = 'T' ORDER BY 2 ASC";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->  obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_cargo(){
		$this->datos   = array();
		$sql = " SELECT cargos.codigo, cargos.descripcion FROM cargos
		WHERE cargos.`status` = 'T' ORDER BY 2 ASC  ";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->  obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_puesto($ubic){
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.nombre FROM clientes_ub_puesto a
		WHERE a.cod_cl_ubicacion = '$ubic'
		ORDER BY 2 ASC  ";
		$query = $this->bd->consultar($sql);
		while ($datos= $this->bd->  obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function verificar_cont_det($cliente,$contrat){
		$this->datos   = array();
		$sql = " SELECT COUNT(a.codigo) contra, MIN(a.fecha) fecha_min, ADDDATE(MAX(a.fecha), INTERVAL 1 DAY) fecha_max 
		FROM clientes_contratacion_ap a
		WHERE a.cod_cliente = '$cliente' AND a.cod_contratacion = '$contrat'  ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}
}
?>
