<?php
define("SPECIALCONSTANT", true);
require  "../../../autentificacion/aut_config.inc.php";
require_once "../../../" . Funcion;
require_once  "../../../" . class_bdI;

class Notificaciones
{
	private $datos;
	private $bd;


	function __construct()
	{
		$this->datos   = array();

		$this->bd = new Database;
	}



	public function get_nov_notif($perfil, $usuario)
	{

		$sql_region = "SELECT men_usuarios.cod_region, men_usuarios.cod_estado, men_usuarios.cod_ciudad FROM men_usuarios
		WHERE  men_usuarios.codigo = '$usuario';";

		$query_region        = $this->bd->consultar($sql_region);
		$data = $this->bd->obtener_fila($query_region);

		$region = $data[0];
		$estado = $data[1];
		$ciudad = $data[2];

		$where = " where nov_procesos.cod_novedad = novedades.codigo
		AND nov_status.control_notificaciones = 'T'
		and nov_procesos.fec_us_mod > DATE_SUB(DATE_FORMAT(CURDATE(), '%Y-%m-%d'), INTERVAL control.dias_nov_notif DAY) 
		and men_usuarios.codigo = nov_procesos.cod_us_mod
		and nov_status.codigo = nov_procesos.cod_nov_status
		and nov_procesos_det.cod_nov_proc = nov_procesos.codigo
		and ficha.cod_ficha = nov_procesos.cod_ficha
		
		and nov_procesos.cod_us_ing <> '" . $usuario . "'
		
		AND novedades.cod_nov_clasif = nov_perfiles.cod_nov_clasif
		AND nov_perfiles.cod_perfil = '$perfil' 
		and nov_perfiles.respuesta = 'T'
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T' ";

		if ($region != null && $region != '') {
			$where .= " AND clientes_ubicacion.cod_region = '$region' ";
		}

		if ($estado != null && $estado != '') {
			$where .= " AND clientes_ubicacion.cod_estado = '$estado' ";
		}

		if ($ciudad != null && $ciudad != '') {
			$where .= " AND clientes_ubicacion.cod_ciudad = '$ciudad' ";
		}

		$sql = "SELECT nov_procesos.codigo,nov_procesos_det.codigo cod_proc,nov_procesos_det.observacion ,ficha.cedula,nov_procesos.fec_us_mod fecha,novedades.descripcion,men_usuarios.nombre,nov_status.descripcion as stat,nov_status.color_notificaciones as color
		FROM nov_procesos,novedades, nov_tipo, nov_perfiles, men_usuarios, control,nov_status,ficha,nov_procesos_det, clientes_ubicacion
		$where
		GROUP BY nov_procesos.codigo
		ORDER BY nov_status.control_notif_orden ASC,nov_procesos.fec_us_mod ASC ";

		$query         = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_nov_reciente($perfil, $usuario)
	{
		$sql_region = "SELECT nov_procesos.cod_region, men_usuarios.cod_estado, men_usuarios.cod_ciudad FROM men_usuarios
		WHERE  nov_procesos_det.cod_us_ing <> '$usuario';";

		$query_region        = $this->bd->consultar($sql_region);
		$data = $this->bd->obtener_fila($query_region);

		$region = $data[0];
		$estado = $data[1];
		$ciudad = $data[2];

		$where = " WHERE  nov_procesos.cod_novedad = novedades.codigo and nov_procesos_det.cod_nov_proc = nov_procesos.codigo AND novedades.`status` = 'T' AND 
		(TIME_TO_SEC(nov_procesos_det.hora) > (TIME_TO_SEC(CURRENT_TIME()) - (control.min_nov_notif * 60))) 
		AND nov_procesos_det.fec_us_ing = DATE_FORMAT(CURDATE(), '%Y-%m-%d')
		AND nov_status.control_notificaciones = 'T'
		AND nov_procesos_det.cod_us_ing <> '$usuario'
		AND novedades.cod_nov_clasif = nov_perfiles.cod_nov_clasif
		AND novedades.cod_nov_tipo = nov_tipo.codigo
		AND nov_tipo.kanban != 'T'
		and nov_procesos.cod_nov_status = nov_status.codigo
		AND nov_perfiles.cod_perfil = '$perfil' 
		AND nov_procesos_det.cod_us_ing = men_usuarios.codigo
		and nov_perfiles.respuesta = 'T'
		AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo ";

		if ($region != null && $region != '') {
			$where .= " AND clientes_ubicacion.cod_region = '$region' ";
		}

		if ($estado != null && $estado != '') {
			$where .= " AND clientes_ubicacion.cod_estado = '$estado' ";
		}

		if ($ciudad != null && $ciudad != '') {
			$where .= " AND clientes_ubicacion.cod_ciudad = '$ciudad' ";
		}


		$sql = "SELECT nov_procesos.codigo,men_usuarios.cedula,nov_procesos_det.observacion,nov_procesos_det.fec_us_ing fecha,novedades.descripcion,men_usuarios.nombre,nov_status.descripcion as stat,nov_status.color_notificaciones as color
		FROM nov_procesos,nov_procesos_det,novedades, nov_tipo, nov_perfiles, men_usuarios, control, nov_status, clientes_ubicacion
		$where
		GROUP BY nov_procesos.codigo
		ORDER BY nov_status.control_notif_orden DESC,nov_procesos_det.fec_us_ing ASC";

		$query         = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}



	public function get_intervalo()
	{
		$sql = "
		SELECT min_nov_notif from control
		";

		$query         = $this->bd->consultar($sql);
		return $datos = $this->bd->obtener_fila($query);
	}
}
