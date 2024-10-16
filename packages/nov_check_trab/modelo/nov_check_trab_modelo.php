<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once "../../../" . Funcion;
require_once  "../../../" . class_bdI;

class check_list
{
	private $datos;
	private $bd;


	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	public function obtener_existentes($codigo)
	{
		$this->datos   = array();
		$sql = "SELECT nov_check_list_trab_det.cod_novedades,nov_check_list_trab_det.cod_valor FROM nov_check_list_trab_det WHERE cod_check_list = '$codigo'";

		$query         = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}

		return $this->datos;
	}

	public function obtener_novedad($tipo, $clasificion)
	{
		$this->datos   = array();
		$sql = 'SELECT
							novedades.codigo,
							novedades.descripcion novedad,
							novedades.cod_nov_agrupacion cod_agrupacion
						FROM
							novedades,
							nov_tipo,
							nov_clasif
						WHERE
							 novedades.cod_nov_clasif = nov_clasif.codigo
						AND novedades.cod_nov_tipo = nov_tipo.codigo
						AND nov_clasif.codigo = "' . $tipo . '"
						AND nov_tipo.codigo = "' . $clasificion . '"
						AND nov_clasif.campo04 = "P"
						AND novedades.`status` = "T"
						ORDER BY
		1,
		2 ASC
		';
		$query         = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function obtener_valor($novedad)
	{
		$this->datos   = array();
		$sql = "SELECT
		nov_valores.codigo,
		nov_valores.abrev,
		nov_valores.descripcion,
		novedades.descripcion nov
	FROM
		nov_valores,
		nov_valores_det,
		novedades
	WHERE
		nov_valores_det.cod_novedades = '$novedad'
	AND nov_valores_det.cod_valores = nov_valores.codigo
	AND novedades.codigo = nov_valores_det.cod_novedades
		
		";

		$query         = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
