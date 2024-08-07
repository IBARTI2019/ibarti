<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class Cliente
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->bd = new Database;
	}

	public function get()
	{
		$this->datos   = array();
		$sql = " SELECT clientes.codigo, clientes.cod_cl_tipo, clientes_tipos.descripcion AS cl_tipo,
		clientes.cod_vendedor, vendedores.nombre AS vendedor, clientes.cod_region,
		regiones.descripcion AS region, clientes.abrev, clientes.rif,
		clientes.nit, clientes.nombre, clientes.telefono, clientes.contacto,
		clientes.status, clientes.latitud, clientes.longitud, clientes.direccion_google
		FROM clientes, clientes_tipos, vendedores, regiones
		WHERE clientes.cod_cl_tipo = clientes_tipos.codigo
		AND clientes.cod_vendedor = vendedores.codigo
		AND clientes.cod_region = regiones.codigo
		ORDER BY nombre ASC ";
		//    $query =  parent::consultar($sql);
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function inicio()
	{

		$this->datos = array(
			'codigo' => '',  'nombre' => '',
			'cod_cl_tipo' => '',  'cl_tipo' => 'Seleccione...',
			'cod_vendedor' => '', 'vendedor' => 'Seleccione...', 'contacto' => '',
			'cod_region' => '',   'region' => 'Seleccione...',
			'abrev' => '', 'rif' => '', 'nit' => '', 'telefono' => '',
			'fax' => '', 'direccion' => '', 'dir_entrega' => '', 'email' => '',
			'website' => '', 'contacto' => '', 'observacion' => '', 'juridico' => '',
			'contribuyente' => '', 'lunes' => '', 'martes' => '', 'miercoles' => '',
			'jueves' => '', 'viernes' => '', 'sabado' => '', 'domingo' => '',
			'limite_cred' => '', 'plazo_pago' => '', 'desc_global' => '', 'desc_p_pago' => '',
			'campo01' => '', 'campo02' => '', 'campo03' => '', 'campo04' => '',
			'cod_us_ing' => '', 'fec_us_ing' => '', 'cod_us_mod' => '', 'fec_us_mod' => '', 'status' => '',
			'latitud' => '', 'longitud' => '', 'direccion_google' => ''
		);
		return $this->datos;
	}

	public function editar($cod)
	{
		$this->datos   = array();
		$sql = " SELECT a.codigo,  a.nombre,
		a.cod_cl_tipo, clientes_tipos.descripcion cl_tipo,
		a.cod_vendedor, vendedores.nombre vendedor,
		a.cod_region, regiones.descripcion region,
		a.abrev, a.rif, a.nit,  a.telefono,
		a.fax, a.direccion, a.dir_entrega, a.email,
		a.website,  a.contacto, a.observacion, a.juridico,
		a.contribuyente, a.lunes, a.martes, a.miercoles,
		a.jueves, a.viernes, a.sabado, a.domingo,
		a.limite_cred, a.plazo_pago, a.desc_global, a.desc_p_pago,
		a.campo01, a.campo02, a.campo03, a.campo04, a.contacto,
		a.cod_us_ing, a.fec_us_ing,a.cod_us_mod, a.fec_us_mod, a.`status`, 
		a.latitud, a.longitud, a.direccion_google,
		(
			SELECT MAX(asistencia_apertura.fec_diaria) FROM asistencia, asistencia_apertura WHERE 
			asistencia.cod_as_apertura = asistencia_apertura.codigo
			AND asistencia.cod_cliente = a.codigo LIMIT 1
		) fec_ult_asistencia
		FROM clientes a , clientes_tipos , vendedores , regiones
		WHERE a.codigo = '$cod'
		AND a.cod_cl_tipo = clientes_tipos.codigo
		AND a.cod_vendedor = vendedores.codigo
		AND a.cod_region = regiones.codigo";

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

	public function buscar_cliente($data, $filtro)
	{
		$where = "";

		if ($data) $where = " AND clientes.$filtro LIKE '%$data%' ";

		$sql = "SELECT clientes.codigo, clientes.cod_cl_tipo, clientes_tipos.descripcion AS cl_tipo,
		clientes.cod_vendedor, vendedores.nombre AS vendedor, clientes.cod_region,
		regiones.descripcion AS region, clientes.abrev, clientes.rif,
		clientes.nit, clientes.nombre, clientes.telefono,
		clientes.status, clientes.latitud, clientes.longitud, clientes.direccion_google
		FROM clientes, clientes_tipos, vendedores, regiones
		WHERE clientes.cod_cl_tipo = clientes_tipos.codigo
		AND clientes.cod_vendedor = vendedores.codigo
		AND clientes.cod_region = regiones.codigo " . $where . " ORDER BY nombre ASC";

		$query         = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}


	public function get_documento($cliente, $doc)
	{
		$this->datos   = array();
		$sql = "SELECT control.url_doc, IFNULL(clientes_documentos.link, 0) link 
		FROM control LEFT JOIN clientes_documentos 
		ON clientes_documentos.cod_cliente = '$cliente' 
		AND clientes_documentos.cod_documento = '$doc' ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}
}
