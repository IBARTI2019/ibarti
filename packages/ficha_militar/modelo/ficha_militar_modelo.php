<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once "../../../".Funcion;
require_once  "../../../".class_bdI;

class ficha_militar
{
	private $datos;
	private $bd;
	

	function __construct()
	{
		$this->datos   = array();
		
		$this->bd = new Database;
	}

	public function llenar_tabla_status_militar(){
		
		$sql   = "SELECT codigo,descripcion,status FROM ficha_status_militar ORDER BY 2 ASC";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	public function eliminar_registro($codigo){
		
		$sql   = "DELETE FROM ficha_status_militar WHERE  codigo = '$codigo'";
		$query = $this->bd->consultar($sql);
	}
}



?>
