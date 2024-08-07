<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once "../../../".Funcion;
require_once  "../../../".class_bdI;

class check_list
{
	private $datos;
	private $bd;
	

	function __construct()
	{
		$this->datos   = array();
		
		$this->bd = new Database;
	}

	public Function obtener_clasif_check(){
		$sql = "SELECT codigo,descripcion from nov_clasif where campo04 = 'T'
		";
		$query         = $this->bd->consultar($sql);
		while ($datos= $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;	
    }

	public Function obtener_clasif_evaluacion(){
		$sql = "SELECT codigo,descripcion from nov_clasif where campo04 = 'P'
		";
		$query         = $this->bd->consultar($sql);
		while ($datos= $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;	
    }



}
?>
