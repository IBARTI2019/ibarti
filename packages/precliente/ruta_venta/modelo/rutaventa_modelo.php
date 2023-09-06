<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../" . class_bdI;

class Rutaventa
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

	public function get_ruta_det($precliente)
	{
		$this->datos   = array();
		$sql = " SELECT
					a.codigo,
					a.cod_rutaventa,
					b.descripcion rutaventa,
					a.cod_precliente,
					a.comentario,
					a.cod_us_ing,
					a.fec_us_ing,
					a.cod_us_mod,
					a.fec_us_mod,
					CONCAT(men_usuarios.nombre, ' ', men_usuarios.apellido) usuario
				FROM
					precliente_rutaventa a,
					ruta_de_ventas b,
					men_usuarios
				WHERE
					a.cod_rutaventa = b.codigo
					AND a.cod_us_ing = men_usuarios.codigo
					AND a.cod_precliente ='$precliente'
				ORDER BY a.codigo ASC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_ruta($precliente)
	{
		$this->datos   = array();
		$sql = " SELECT DISTINCT
					ruta_de_ventas.codigo,
					ruta_de_ventas.descripcion,
					ruta_de_ventas.orden 
				FROM
					ruta_de_ventas
				WHERE
					ruta_de_ventas.`status` = 'T' 
				ORDER BY 3 ASC; ";
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
		$sql = " SELECT cargos.codigo, cargos.descripcion FROM cargos
		WHERE cargos.`status` = 'T' AND cargos.`planificable` = 'T' ORDER BY 2 ASC  ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
