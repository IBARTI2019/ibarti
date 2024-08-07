<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../" . class_bdI;

class Supervision
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	public function inicio()
	{
		$this->datos   = array();
		$this->datos   = array(
			'codigo' => '',  'descripcion' => '',
			'fecha_inicio' => '', 'fecha_fin' => '',
			'status' => 'T'
		);
		return $this->datos;
	}
	public function get_fc_actividades($cod_formacontacto)
	{
		$this->datos   = array();
		$sql = " SELECT
					codigo, descripcion
				FROM
					fc_actividades
				WHERE
					cod_formacontacto = $cod_formacontacto; ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	public function get_superv_det($cliente)
	{
		$this->datos   = array();
		$sql = " SELECT
			a.codigo,
			a.cod_ubicacion,
			clientes_ubicacion.descripcion ubicacion,
			a.cod_turno,
			turno.descripcion turno,
			a.cod_cargo,
			cargos.descripcion cargo,
			a.cantidad,
			a.cod_us_ing,
			a.fec_us_ing,
			a.cod_us_mod,
			a.fec_us_mod
		FROM
			clientes_supervision a
			LEFT JOIN cargos ON a.cod_cargo = cargos.codigo,
			clientes_ubicacion,
			turno
		WHERE
			a.cod_ubicacion = clientes_ubicacion.codigo
			AND a.cod_turno = turno.codigo
			AND clientes_ubicacion.cod_cliente = '$cliente'
 		ORDER BY 3,5,7 ASC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_ubicacion($cliente)
	{
		$this->datos   = array();
		$sql = " SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
		FROM clientes_ubicacion
		WHERE clientes_ubicacion.cod_cliente = '$cliente'
		AND clientes_ubicacion.`status` = 'T'
		ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_turno()
	{
		$this->datos   = array();
		$sql = "SELECT turno.codigo, turno.descripcion FROM turno
		WHERE turno.`status` = 'T' ORDER BY 2 ASC";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_cargo()
	{
		$this->datos   = array();
		$sql = " SELECT formas_de_contactos.codigo, formas_de_contactos.descripcion FROM formas_de_contactos
		WHERE formas_de_contactos.`status` = 'T'  ORDER BY 2 ASC  ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
