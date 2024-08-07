<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once "../../../".Funcion;
require_once  "../../../".class_bdI;
session_start();
class clientes_reporte
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		
		$this->bd = new Database;
	}

	public function llenar_region(){
		$sql = "SELECT regiones.codigo, regiones.descripcion
		FROM regiones WHERE regiones.`status` = 'T'
		ORDER BY regiones.descripcion ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	
	public function llenar_estados(){
		$sql = "SELECT estados.codigo, estados.descripcion
		FROM estados , control
		WHERE estados.cod_pais = control.cod_pais AND estados.`status` = 'T'
		ORDER BY 2 ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	public function llenar_estatus(){
		$sql = "SELECT estados.codigo, estados.descripcion
		FROM estados , control
		WHERE estados.cod_pais = control.cod_pais AND estados.`status` = 'T'
		ORDER BY 2 ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	public function llenar_ciudades($estado){
		$where='';
		if($estado!='TODOS'){
			$where = "WHERE ciudades.cod_estado = '$estado'";
		}
		$sql = "SELECT ciudades.codigo, ciudades.descripcion
		FROM ciudades
		$where
		ORDER BY descripcion ASC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function llenar_cliente($region){
		$where ="";
		if($region!="TODOS"){
			$where = "AND clientes.cod_region = '$region'";
		}
		$sql = "SELECT clientes.codigo, clientes.nombre
		FROM clientes
		WHERE clientes.`status` = 'T'
		AND clientes.codigo IN (SELECT DISTINCT clientes_ubicacion.cod_cliente
		FROM usuario_clientes, clientes_ubicacion
		WHERE usuario_clientes.cod_usuario = '".$_SESSION['usuario_cod']."'
		AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo)
		$where
		ORDER BY 2 ASC  ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function llenar_ubicacion($cliente,$estado,$ciudad){

		$where="";
		if($cliente!= 'TODOS'){
			$where.="and clientes_ubicacion.cod_cliente = '$cliente' ";
		}
		if($estado!='TODOS'){
			$where.="and clientes_ubicacion.cod_estado = '$estado'";
		}
		if($ciudad!='TODOS'){
			$where.="and clientes_ubicacion.cod_ciudad = '$ciudad'";
		}

		

		$sql = "select clientes_ubicacion.codigo, clientes_ubicacion.descripcion, clientes_ubicacion.cod_region,clientes_ubicacion.cod_estado,cod_ciudad,clientes_ubicacion.cod_cliente,regiones.descripcion region,estados.descripcion estado,ciudades.descripcion ciudad
		FROM clientes_ubicacion ,ciudades,estados,regiones
		WHERE clientes_ubicacion.`status` = 'T'
		and ciudades.codigo = clientes_ubicacion.cod_ciudad
		and regiones.codigo = clientes_ubicacion.cod_region
		and estados.codigo = clientes_ubicacion.cod_estado
		$where


		ORDER BY 2 ASC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function llenar_puesto($cliente,$ubicacion){
		$sql = "select codigo,nombre from clientes_ub_puesto where cod_cliente = '$cliente' and cod_cl_ubicacion = '$ubicacion' ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	
	
	
	
	
}



?>
