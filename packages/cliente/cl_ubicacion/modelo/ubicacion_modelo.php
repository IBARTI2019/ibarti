<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class Ubicacion
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->bd = new Database;
	}

	public function get($cliente)
	{
		$this->datos   = array();

		$sql = " SELECT clientes_ubicacion.codigo,
		                clientes_ubicacion.cod_estado, estados.descripcion AS estado,
		        clientes_ubicacion.cod_ciudad, ciudades.descripcion AS ciudad,
		                  clientes_ubicacion.cod_region, regiones.descripcion AS region,
		                  clientes_ubicacion.cod_calendario, nom_calendario.descripcion AS calendario,
		                  clientes_ubicacion.descripcion, clientes_ubicacion.direccion,
		                  clientes_ubicacion.contacto, clientes_ubicacion.telefono,
		                  clientes_ubicacion.email,  clientes_ubicacion.latitud,  clientes_ubicacion.longitud, clientes_ubicacion.direccion_google,
		        clientes_ubicacion.campo01, clientes_ubicacion.campo02,
		        clientes_ubicacion.campo03, clientes_ubicacion.campo04,
		        clientes_ubicacion.`status`
		             FROM clientes_ubicacion, estados,  ciudades , regiones, nom_calendario
		            WHERE clientes_ubicacion.cod_estado = estados.codigo
		        AND clientes_ubicacion.cod_ciudad = ciudades.codigo
		        AND clientes_ubicacion.cod_region = regiones.codigo
		              AND clientes_ubicacion.cod_calendario = nom_calendario.codigo
		      AND clientes_ubicacion.cod_cliente = '$cliente'
		      ORDER BY 5, 3 DESC ";

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
			'cod_estado' => '',  'estado' => 'Seleccione...',
			'cod_ciudad' => '', 'ciudad' => 'Seleccione...',
			'cod_zonas' => '', 'zona' => 'Seleccione...',
			'cod_region' => '',   'region' => 'Seleccione...',
			'cod_calendario' => '', 'calendario' => 'Seleccione...',
			'direccion' => '',  'contacto' => '', 'cargo' => '',  'telefono' => '',
			'cod_zona' => '', 'email' => '', 'observacion' => '',
			'latitud' => '', 'longitud' => '', 'direccion_google' => '',
			'campo01' => '', 'campo02' => '', 'campo03' => '', 'campo04' => '',
			'cod_us_ing' => '', 'fec_us_ing' => '', 'cod_us_mod' => '', 'fec_us_mod' => '', 'status' => ''
		);
		return $this->datos;
	}

	public function editar($cod)
	{
		$this->datos   = array();
		$sql = " SELECT clientes_ubicacion.codigo,
										clientes_ubicacion.cod_estado, estados.descripcion  estado,
						        clientes_ubicacion.cod_ciudad, ciudades.descripcion ciudad,
	                  clientes_ubicacion.cod_region, regiones.descripcion region,
										clientes_ubicacion.cod_zona, zonas.descripcion  zona,
					          clientes_ubicacion.cod_calendario, nom_calendario.descripcion calendario,
	                  clientes_ubicacion.descripcion, clientes_ubicacion.direccion,
	                  clientes_ubicacion.contacto,clientes_ubicacion.latitud,clientes_ubicacion.longitud, 
					  clientes_ubicacion.direccion_google, clientes_ubicacion.cargo,
						        clientes_ubicacion.telefono,
	                  clientes_ubicacion.email, clientes_ubicacion.observacion,
						        clientes_ubicacion.campo01, clientes_ubicacion.campo02,
						        clientes_ubicacion.campo03, clientes_ubicacion.campo04,
						        clientes_ubicacion.`status`,
						(
							SELECT MAX(asistencia_apertura.fec_diaria) FROM asistencia, asistencia_apertura 
							WHERE asistencia.cod_as_apertura = asistencia_apertura.codigo
								AND asistencia.cod_ubicacion = clientes_ubicacion.codigo LIMIT 1
						) fec_ult_asistencia
	             FROM clientes_ubicacion, estados,  ciudades , regiones,
								    nom_calendario, zonas
	            WHERE clientes_ubicacion.cod_estado = estados.codigo
				        AND clientes_ubicacion.cod_ciudad = ciudades.codigo
				        AND clientes_ubicacion.cod_region = regiones.codigo
				        AND clientes_ubicacion.cod_calendario = nom_calendario.codigo
								AND clientes_ubicacion.cod_zona = zonas.codigo
					      AND clientes_ubicacion.codigo = '$cod'";

		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	public function get_cl_nombre($cod)
	{
		$this->datos   = array();
		$sql = " SELECT nombre  FROM clientes WHERE codigo = '$cod'";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}


	public function get_concepto($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion,  a.abrev
	             FROM conceptos AS a
	            WHERE a.`status` = 'T'
							  AND a.asist_diaria = 'T'
	              AND a.codigo <> '$cod'; ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	public function get_region($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion
	             FROM regiones AS a
	            WHERE a.`status` = 'T'
	              AND a.codigo <> '$cod'; ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_cargo($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion
	             FROM cargos AS a
	            WHERE a.`status` = 'T'
	              AND a.codigo <> '$cod'; ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_zona($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion FROM zonas a
		  				WHERE a.`status` = 'T' AND a.codigo <> '$cod'
						  ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_calendario($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion FROM nom_calendario a
							WHERE a.`status` = 'T' 
							AND a.tipo = 'VAR'  
							AND a.codigo <> '$cod'
							ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_estado($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion FROM estados a , control
							WHERE a.cod_pais = control.cod_pais AND a.`status` = 'T'
							  AND a.codigo <> '$cod' ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_ciudad($cod, $cod_estado)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo, a.descripcion FROM ciudades a
							WHERE a.cod_estado = '$cod_estado' AND a.`status` = 'T'
							  AND a.codigo <> '$cod' ORDER BY 2 ASC ";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
