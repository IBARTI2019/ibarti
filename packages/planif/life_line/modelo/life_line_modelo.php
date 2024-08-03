<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class LifeLine
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	function get_actividades(){
		$sql = "SELECT codigo, descripcion FROM planif_life_line_actividades WHERE status = 'T';";
		
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_cliente($usuario, $r_cliente)
	{
		if ($r_cliente == "F") {
			$sql = "  SELECT clientes.codigo, IF(COUNT(clientes_contratacion.codigo) = 0, 'S/C - ' , '') sc,
			clientes.abrev, clientes.nombre cliente
			FROM clientes LEFT JOIN clientes_contratacion ON clientes.codigo = clientes_contratacion.cod_cliente
			WHERE clientes.`status` = 'T'
			GROUP BY clientes.codigo ORDER BY 2 ASC ";
		} else {
			$sql = "  SELECT clientes.codigo, IF(COUNT(clientes_contratacion.codigo) = 0, 'S/C - ' , '') sc,
			clientes.abrev, clientes.nombre cliente
			FROM clientes LEFT JOIN clientes_contratacion ON clientes.codigo = clientes_contratacion.cod_cliente
			WHERE clientes.`status` = 'T'
			AND clientes.codigo IN (SELECT DISTINCT clientes_ubicacion.cod_cliente
										FROM usuario_clientes, clientes_ubicacion
										WHERE usuario_clientes.cod_usuario = '" . $usuario . "'
											AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo)
			GROUP BY clientes.codigo ORDER BY 2 ASC ";
		}
		
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_det($ubic)
	{
		$sql = "SELECT
					planif_life_line.codigo,
					planif_life_line.cod_ubicacion,
					clientes_ubicacion.descripcion ubicacion,
					planif_life_line.cod_actividad,
					planif_life_line_actividades.descripcion actividad,
					planif_life_line_actividades.abrev abrev_actividad,
					planif_life_line.hora_inicio,
					planif_life_line.hora_fin
				FROM
					planif_life_line,
					planif_life_line_actividades,
					clientes_ubicacion 
				WHERE
					planif_life_line.cod_ubicacion = clientes_ubicacion.codigo
					AND planif_life_line.cod_actividad = planif_life_line_actividades.codigo  
					AND planif_life_line.cod_ubicacion = $ubic
				ORDER BY planif_life_line.hora_inicio ASC;";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
