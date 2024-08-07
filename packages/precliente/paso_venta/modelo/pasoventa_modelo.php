<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require  "../../../../autentificacion/aut_config.inc.php";
require_once  "../../../../" .class_bdI;

class Pasoventa
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	public function get_preclientes(){
		$this->datos   = array();
		$sql = " SELECT
					a.codigo,
					a.nombre
				FROM
					preclientes a
				WHERE
					a.status = 'T'
					AND a.venta_cerrada = 'F'
				ORDER BY 2 ASC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_rutas()
	{
		$this->datos   = array();
		$sql = " SELECT
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

	public function get_sub_rutas($cod_ruta)
	{
		$this->datos   = array();
		$sql = " SELECT
					codigo, descripcion, cod_ruta
				FROM
					subruta_de_ventas
				WHERE
					cod_ruta = $cod_ruta; ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
