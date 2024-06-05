<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class Planificacion
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	function get_cliente()
	{
		$this->datos   = array();
		$sql = "  SELECT clientes.codigo, 
		-- IF(COUNT(clientes_contratacion.codigo) = 0, 'S/P - ' , '') sc,
		IF(COUNT(clientes_contratacion.codigo) = 0, '' , '') sc,
		clientes.abrev, clientes.nombre cliente
		FROM clientes LEFT JOIN clientes_contratacion ON clientes.codigo = clientes_contratacion.cod_cliente
		WHERE clientes.`status` = 'T'
		GROUP BY clientes.codigo ORDER BY 2 ASC ";

		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_preclientes($usuario)
	{
		$this->datos   = array();
		$sql = "  SELECT preclientes.codigo, 
		preclientes.abrev, preclientes.nombre cliente
		FROM preclientes
		WHERE preclientes.`status` = 'T'
		GROUP BY preclientes.codigo ORDER BY 2 ASC ";

		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
	
	function get_supervisores($ubicacion, $filtro, $usuario, $cargo)
	{
		$this->datos   = array();
		$sql = "SELECT * FROM agendas_contactos_fc WHERE  agendas_contactos_fc.cod_ubicacion= $ubicacion";
		$query = $this->bd->consultar($sql);
		
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_cargos($cliente, $ubic)
	{
		$this->datos   = array();
		$sql = " SELECT cargos.codigo, cargos.descripcion FROM cargos
		WHERE cargos.`status` = 'T' AND cargos.`planificable` = 'T' ORDER BY 2 ASC  ";
		if ($cliente != '') {
			$sql = " SELECT
						c.codigo,
						c.descripcion
					FROM
						clientes_supervision a,
						cargos c
					WHERE
						a.cod_cliente = $cliente
					AND a.cod_cargo = c.codigo
					GROUP BY 1  ";
			if ($ubic != '') {
				$sql = " SELECT
						c.codigo,
						c.descripcion
					FROM
						clientes_supervision a,
						cargos c
					WHERE
						a.cod_ubicacion = $ubic
					AND a.cod_cargo = c.codigo
					GROUP BY 1  ";
			}
		}
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_fcontactos($cliente, $ubic)
	{
		$this->datos   = array();
		$sql = " SELECT formas_de_contactos.codigo, formas_de_contactos.descripcion FROM formas_de_contactos
		WHERE formas_de_contactos.`status` = 'T'  ORDER BY 2 ASC  ";
		
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function replicar_rot($cliente, $ubic, $contratacion, $apertura, $usuario)
	{
		$sql = "CALL p_planif_serv_rotacion('$cliente','$ubic',$contratacion,$apertura,'$usuario');";
		$query = $this->bd->consultar($sql);
		return $query;
	}

	function get_cl_contrato($cliente)
	{
		$this->datos   = array();
		$sql = "SELECT a.codigo, a.descripcion, a.fecha_inicio, a.fecha_fin FROM clientes_contratacion a
		WHERE a.cod_cliente = '$cliente' AND a.`status` = 'T'
		ORDER BY 1 DESC;";
		$query = $this->bd->consultar($sql);
		if ($this->bd->obtener_num($query) > 0) {
			$query = $this->bd->consultar($sql);
			while ($datos = $this->bd->obtener_fila($query)) {
				$this->datos[] = $datos;
			}
		} else {
			$this->datos[] = array('codigo' => '', 'descripcion' => 'Sin Contrato');
		}
		return $this->datos;
	}

	function get_planif_ap($apertura)
	{
		$this->datos  = array();
		$sql = " SELECT a.codigo, a.fecha_inicio, a.fecha_fin, a.`status`
		FROM planif_clientes_superv a
		WHERE a.codigo = '$apertura' ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_planif_apertura($cliente)
	{
		$this->datos  = array();
		$sql = "SELECT a.*, CONCAT(b.apellido,' ', b.nombre) us_mod
		FROM planif_clientes_superv a LEFT JOIN men_usuarios b ON a.cod_us_mod = b.codigo
		WHERE a.cod_cliente = '$cliente'
		ORDER BY 1 DESC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_act($ubic, $cargo)
	{
		$this->datos = array();
		$sql = "SELECT a.* FROM planif_clientes_superv a
		WHERE a.`status` = 'T' AND a.cod_ubicacion = $ubic
		AND a.cod_cargo = '$cargo'
		ORDER BY 1 DESC;";
		$query    = $this->bd->consultar($sql);

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_fcactividades($ubic, $cargo)
	{
		$this->datos = array();
		$sql = "SELECT a.* FROM fc_actividades a
		WHERE a.`status` = 'T' AND a.cod_formacontacto = '$cargo'
		ORDER BY 1 DESC;";
		$query    = $this->bd->consultar($sql);

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_regiones($cliente)
	{
		$this->datos = array();
		$sql = "SELECT regiones.codigo, regiones.descripcion 
		FROM clientes_ubicacion, regiones 
		WHERE clientes_ubicacion.cod_cliente = '$cliente' 
		AND clientes_ubicacion.cod_region = regiones.codigo
		AND clientes_ubicacion.status = 'T' GROUP BY 1;";
		$query    = $this->bd->consultar($sql);

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_ap_ubic($cliente)
	{
		
		$this->datos  = array();
		$sql = "SELECT codigo,descripcion
		FROM  clientes_ubicacion where cod_cliente='$cliente'";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_proyectos()
	{
		$this->datos  = array();
		$sql = "SELECT p.codigo, p.descripcion
		FROM  planif_proyecto p
		WHERE  p.`status` = 'T' 
		AND p.codigo IN (SELECT cod_proyecto FROM planif_actividad WHERE planif_actividad.status = 'T') 
		ORDER BY p.descripcion DESC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_actividades()
	{
		$this->datos  = array();
		
		$sql = "SELECT
					formas_de_contactos.codigo cod_proyecto,
					formas_de_contactos.descripcion proyecto_descripcion,
					fc_actividades.codigo cod_actividad,
					fc_actividades.descripcion descripcion,
					formas_de_contactos.status estatu 
				FROM
					formas_de_contactos,
					fc_actividades 
				WHERE
					formas_de_contactos.codigo = fc_actividades.cod_formacontacto 
				ORDER BY
					formas_de_contactos.codigo ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_ap_inicio($ubic, $cargo)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, IFNULL (DATE_ADD(fecha_fin, INTERVAL 1 DAY), CURDATE()) fecha_inicio
		FROM planif_clientes_superv a WHERE a.cod_ubicacion = $ubic AND a.cod_cargo = '$cargo'
		ORDER BY a.codigo DESC LIMIT 0, 1; ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_supervision_det($cliente, $ubicacion, $cargo)
	{
		$this->datos  = array();
		$sql = "SELECT b.descripcion ubicacion, d.descripcion turno, d.abrev turno_abrev, c.descripcion cargo, d.descripcion turno, a.cantidad
		FROM clientes_supervision a, clientes_ubicacion b,	turno d, cargos c
		WHERE b.cod_cliente = '$cliente'
		AND a.cod_ubicacion = b.codigo
		AND a.cod_cargo = c.codigo
		AND a.cod_turno = d.codigo ";

		if ($ubicacion !== '') {
			$sql .= " AND b.codigo = '$ubicacion'";
		}

		if ($cargo !== '') {
			$sql .= " AND a.cod_cargo = '$cargo'";
		}
		$sql .= " ORDER BY 2 DESC";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_det()
	{
		$this->datos   = array();
		$sql = "SELECT DISTINCT a.codigo as codigo,b.descripcion as descripcion ,c.codigo as cod_cliente,c.nombre, a.fecha_inicio,a.fecha_fin,a.hora as hora_inicio,a.hora_fin as hora_fin FROM agendas_contactos_fc_actividades as a, fc_actividades as b ,preclientes as c,formas_de_contactos as d where a.cod_cliente=c.codigo and a.cod_actividad=b.codigo; ";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_ultima_mod($apertura)
	{
		$this->datos   = array();
		$sql = "SELECT MAX(a.fec_us_mod) fecha,CONCAT(b.apellido,' ',b.nombre) us_mod
		FROM
		agendas_contactos_fc_actividades a
		INNER JOIN men_usuarios b ON a.cod_us_mod = b.codigo
		WHERE a.codigo >0";

		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_ubic_puesto($ubic)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, 		a.nombre
		FROM clientes_ub_puesto AS a
		WHERE a.cod_cl_ubicacion = '$ubic'
		AND a.`status` = 'T'
		ORDER BY 2 DESC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function verificar_cl($cliente)
	{
		$this->datos  = array();
		$sql = "SELECT COUNT(agendas_contactos_fc.codigo) contra FROM agendas_contactos_fc, clientes_ubicacion 
		WHERE agendas_contactos_fc.cod_ubicacion = clientes_ubicacion.codigo 
		AND clientes_ubicacion.cod_cliente = '$cliente'";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function cantidad_trab_sin_planif($cliente, $apertura)
	{
		$this->datos  = array();
		$sql = "SELECT agendas_contactos_fc_actividades.codigo  cantidad
		FROM agendas_contactos_fc_actividades
		WHERE agendas_contactos_fc_actividades.codigo >0 ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function validar_fecha($fecha, $cliente)
	{
		$this->datos  = array();
		$this->datos["data"]  = array();
		$this->datos["datacliente"]  = array();
        $this->datos["dataubicacion"]  = array();
		$sql = "SELECT clientes.codigo , agendas_contactos_fc_actividades.codigo  cod_horario,clientes_ubicacion.codigo as cod_ubicacion,clientes_ubicacion.descripcion as ubicacion,
		MIN(agendas_contactos_fc_actividades.hora) hora_entrada, MAX(agendas_contactos_fc_actividades.hora) hora_salida
		FROM clientes , agendas_contactos_fc_actividades,clientes_ubicacion
		WHERE clientes.codigo =agendas_contactos_fc_actividades.cod_cliente AND clientes.codigo=clientes_ubicacion.cod_cliente and clientes.codigo = '$cliente' ";
		$this->datos["sql"] = $sql;
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos["datacliente"][] = $datos;
		}
		if (count($this->datos["datacliente"]) > 0) {
			$sql= "SELECT codigo as cod_nuevo,descripcion as descripcion_nueva
			FROM  clientes_ubicacion where cod_cliente='$cliente'";
			$this->datos["sql"] = $sql;
			$query = $this->bd->consultar($sql);
			while ($datos = $this->bd->obtener_fila($query, 0)) {
				$this->datos["dataubicacion"][] = $datos;
			}
			$sql = "SELECT clientes.codigo , agendas_contactos_fc_actividades.codigo  cod_horario,clientes_ubicacion.codigo as cod_ubicacion,clientes_ubicacion.descripcion as ubicacion,
			MIN(agendas_contactos_fc_actividades.hora) hora_entrada, MAX(agendas_contactos_fc_actividades.hora) hora_salida
			FROM clientes , agendas_contactos_fc_actividades,clientes_ubicacion
			WHERE clientes.codigo =agendas_contactos_fc_actividades.cod_cliente AND clientes.codigo=clientes_ubicacion.cod_cliente and clientes.codigo = '$cliente' ";
			$this->datos["sql"] = $sql;
			$query = $this->bd->consultar($sql);
			while ($datos = $this->bd->obtener_fila($query, 0)) {
				$this->datos["data"][] = $datos;
			}
			if (count($this->datos["data"]) == 0) {
				$this->datos["msg"] = "El turno del cliente" . $cliente . " no aplica la fecha " . $fecha . "";
			} 
		} else {
			$this->datos["msg"] = "El cliente no aplica la fecha " . $fecha . "  planificable en el mismo.";
		}
		return $this->datos;
		
		
	}

	function validar_ingreso($apertura, $cliente, $ubic, $actividades, $fecha, $hora_inicio, $hora_fin, $ficha)
	{
		$result = array();
		$result["error"] = false;
		$result["data"] = [];
		$horas = array();
		$this->datos  = array();
		$sql = "select * from agendas_contactos_fc where cod_cliente='$cliente'";
		$result["sql"] = $sql;
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		if (count($this->datos) > 0) {
			$sql2 = " SELECT *
				FROM agendas_contactos_fc_actividades
				WHERE agendas_contactos_fc_actividades.cod_cliente= '$cliente' and 
				agendas_contactos_fc_actividades.cod_ubicacion='$ubic'
				";
			$i = 0;
			foreach ($actividades as $key => $value) {
				if ($i == 0) {
					$sql2 .= " AND ((cod_actividad = " . $value['codigo'] . " AND  cod_actividad = " . $value['codigo'] . ")";
				} else {
					$sql2 .= " OR (codigo = " . $value['cod_actividad'] . " AND cod_actividad = " . $value['codigo'] . ")";
				}
				$i++;
			};
			$sql2 .= ") AND DATE_FORMAT(fecha_inicio,'%Y-%m-%d') = '$fecha'
			AND (
				(
					TIME(fecha_inicio) >= '$hora_inicio' 
					AND TIME(fecha_inicio) <= '$hora_fin'
					AND TIME(fecha_fin) > '$hora_inicio'
					AND (TIME(fecha_fin) >= '$hora_fin' OR TIME(fecha_fin) < '$hora_fin')
				)
				OR (
					TIME(fecha_inicio) <= '$hora_inicio' 
					AND TIME(fecha_inicio) <= '$hora_fin'
					AND TIME(fecha_fin) > '$hora_inicio'
					AND TIME(fecha_fin) <= '$hora_fin'
				)
			)";
			$result["sql2"] = $sql2;
			$query2 = $this->bd->consultar($sql2);
			while ($datos = $this->bd->obtener_fila($query2, 0)) {
				$horas[] = $datos;
			}
			if (count($horas) > 0) {
				$result["error"] = true;
				$result["msg"] = "Ya existe planificacion en este rango de horas para este cliente y ubicación.";
				$result["data"] = $horas;
				return $result;
			} else {
				/* 					
					$sql3 = "SELECT SUBSTR(ADDTIME(MAX(p.fecha_fin), '00:10:00') FROM 12) hora_inicio
					FROM planif_clientes_superv_trab p
					WHERE  p.cod_ficha = '$ficha'
				   	AND TIME(pd.fecha_inicio,'%Y-%m-%d') = '$fecha'";
					$result["sql3"] = $sql3;
					$query3 = $this->bd->consultar($sql3);
					$hora_inicio = $this->bd->obtener_fila($query3, 0);
					$result["hora_inicio"] = $hora_inicio; 
					*/
				$result["data"] = $this->datos;
				return $result;
			}
		} else {
			$result["msg"] = "Rango de horas no válido";
			return $result;
		}
	}

	function get_planif_trab_det($apertura, $cliente, $ficha, $fechas)
	{
		$this->datos  = array();
		$where = " WHERE pcst.cod_ficha = f.cod_ficha
		AND f.cod_ficha_status= control.ficha_activo
		AND pcst.cod_ficha = '$ficha'
		AND f.cod_cargo = c.codigo
		AND pcst.cod_cliente = cl.codigo
		AND pcst.cod_ubicacion = cu.codigo
		AND pcstd.cod_proyecto = pp.codigo
		AND pcstd.cod_planif_cl_trab = pcst.codigo
		AND pcstd.cod_actividad = pa.codigo";

		if ($cliente != null) {
			$where .= " AND pcst.cod_cliente = '$cliente'";
		}

		if ($apertura != null) {
			$where .= " AND pcst.cod_planif_cl = $apertura";
		}

		if ($fechas != null) {
			$where .= " AND pcst.fecha_inicio >= '" . $fechas['fecha_inicio'] . "' AND pcst.fecha_fin <= '" . $fechas['fecha_fin'] . "'";
		}

		$sql = "SELECT pcst.codigo, pcst.cod_cliente, cl.nombre cliente,
		pcst.cod_ubicacion, cu.descripcion ubicacion, pcstd.cod_proyecto, pp.descripcion proyecto,
		pcstd.cod_actividad, pa.descripcion actividad, pa.minutos,
		pp.abrev abrev_proyecto, pcst.cod_ficha, CONCAT(f.apellidos,' ', f.nombres) trabajador, f.cedula, pcst.fecha_inicio, pcst.fecha_fin,
		pa.obligatoria, pcstd.realizado, pcst.completado,	pcstd.fecha_inicio fecha_inicio_act, pcstd.fecha_fin fecha_fin_act
		FROM planif_clientes_superv_trab_det pcstd, planif_clientes_superv_trab pcst, ficha f, cargos c, control, clientes cl, 
			clientes_ubicacion cu, planif_proyecto pp, planif_actividad pa
			" . $where . " 
		ORDER BY
			codigo ASC,
			obligatoria DESC,
			fecha_inicio_act ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}
}
