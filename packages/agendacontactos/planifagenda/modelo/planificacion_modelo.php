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
	function get_cliente_c($usuario)
	{
		$this->datos   = array();
		$sql = "  SELECT preclientes.codigo, 
		-- IF(COUNT(clientes_contratacion.codigo) = 0, 'S/P - ' , '') sc,
		IF(COUNT(clientes_contratacion.codigo) = 0, '' , '') sc,
		preclientes.abrev, preclientes.nombre cliente
		FROM preclientes LEFT JOIN clientes_contratacion ON preclientes.codigo = clientes_contratacion.cod_cliente
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
		$sql = "SELECT b.cod_ubicacion, c.descripcion
		FROM  clientes_supervision b , clientes_ubicacion c
		WHERE b.cod_ubicacion = c.codigo
		AND c.cod_cliente = '$cliente'
		AND c.`status` = 'T'
		GROUP BY b.cod_ubicacion ORDER BY c.descripcion DESC ";

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
		
		$sql = "SELECT  codigo, descripcion, status estatu
		FROM  formas_de_contactos ORDER BY formas_de_contactos.codigo ASC";

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
		$sql = "SELECT * FROM `agendas_contactos_fc` INNER JOIN `agendas_contactos_fc_actividades` ON  agendas_contactos_fc.codigo=agendas_contactos_fc_actividades.cod_agenda";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_det_rp($fecha_desde, $fecha_hasta, $cliente, $ubicacion, $ficha)
	{
		$this->datos   = array();
		$where = " WHERE pcst.cod_ficha = f.cod_ficha
		AND f.cod_ficha_status= control.ficha_activo
		AND f.cod_cargo = c.codigo
		-- AND c.planificable = 'T'
		AND pcst.cod_cliente = cl.codigo
		AND pcst.cod_ubicacion = cu.codigo
		AND pcstd.cod_proyecto = pp.codigo
		AND pcstd.cod_planif_cl_trab = pcst.codigo
		AND pcstd.cod_actividad = pa.codigo";

		if ($fecha_desde != NULL && $fecha_desde != '0000-00-00') {
			$where .= " AND DATE_FORMAT(pcst.fecha_inicio, '%Y-%m-%d') >= '$fecha_desde'";
		}

		if ($fecha_hasta != NULL && $fecha_hasta != '0000-00-00') {
			$where .= " AND DATE_FORMAT(pcst.fecha_fin, '%Y-%m-%d') <= '$fecha_hasta'";
		}

		if ($cliente != NULL && $cliente != "TODOS") {
			$where .= " AND pcst.cod_cliente = '$cliente'";
		}

		if ($ubicacion != NULL && $ubicacion != "TODOS") {
			$where .= " AND pcst.cod_ubicacion = '$ubicacion'";
		}

		if ($ficha != NULL) {
			$where   .= " AND  f.cod_ficha = '$ficha' ";
		}

		$sql = "SELECT pcst.codigo, pcst.cod_cliente, cl.nombre cliente,
		pcst.cod_ubicacion, cu.descripcion ubicacion, pcstd.cod_proyecto, pp.descripcion proyecto,
		pcstd.cod_actividad, pa.descripcion actividad,
		pp.abrev abrev_proyecto, pcst.cod_ficha, CONCAT(f.apellidos,' ', f.nombres) trabajador, f.cedula, pcst.fecha_inicio, pcst.fecha_fin,
		pa.obligatoria, pcstd.fecha_inicio fecha_inicio_act, pcstd.fecha_fin fecha_fin_act, pcstd.realizado, pcst.completado
		FROM planif_clientes_superv_trab_det pcstd, planif_clientes_superv_trab pcst, ficha f, cargos c, control, clientes cl, 
				clientes_ubicacion cu, planif_proyecto pp, planif_actividad pa
		" . $where . "
		ORDER BY codigo ASC, obligatoria DESC, fecha_inicio_act ASC";

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

	function get_planif_trab_ap($cod_pl_trab)
	{
		$this->datos  = array();
		$sql = "SELECT a.cod_planif_cl, planif_clientes_superv.fecha_inicio,
		planif_clientes_superv.fecha_fin, planif_clientes_superv.`status`,
		a.cod_cliente , clientes.nombre cliente, clientes.abrev cl_abrev,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_puesto_trabajo, clientes_ub_puesto.nombre AS puesto_trabajo,
		ficha.cod_ficha, CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre,
		rotacion.abrev rotacion_abrev, rotacion.descripcion rotacion,
		IFNULL(b.cod_ficha,'NO') vetado
		FROM planif_clientes_superv_trab a LEFT JOIN clientes_vetados b ON  a.cod_ficha = b.cod_ficha
		AND a.cod_ubicacion = b.cod_ubicacion, planif_clientes_superv,  clientes_ub_puesto, ficha,
		clientes, clientes_ubicacion, rotacion
		WHERE a.codigo = '$cod_pl_trab'
		AND a.cod_planif_cl = planif_clientes_superv.codigo
		AND a.cod_puesto_trabajo =clientes_ub_puesto.codigo
		AND a.cod_ficha = ficha.cod_ficha
		AND a.cod_cliente = clientes.codigo
		AND a.cod_ubicacion = clientes_ubicacion.codigo
		AND a.cod_rotacion = rotacion.codigo";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
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

	function get_planif_trabajador($aperuta, $ficha)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, a.fecha, Dia_semana_abrev(a.fecha) d_semana,
		a.cod_cliente, clientes.abrev cliente,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_puesto_trabajo, clientes_ub_puesto.nombre puesto_trabajo,
		ficha.cod_ficha, CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre,
		a.cod_turno,turno.abrev tuno_abrev, turno.descripcion turno
		FROM planif_clientes_superv_trab_det a, turno, clientes_ub_puesto, ficha, clientes, clientes_ubicacion
		WHERE a.cod_ficha = '$ficha'
		AND a.cod_turno = turno.codigo
		AND a.cod_puesto_trabajo =clientes_ub_puesto.codigo
		AND a.cod_ficha = ficha.cod_ficha
		AND a.cod_cliente = clientes.codigo
		AND a.cod_ubicacion = clientes_ubicacion.codigo
		ORDER BY a.fecha ASC";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function generar_planif($apertura, $ubic)
	{
		$this->datos  = array();
		$sql = "DELETE FROM planif_clientes_superv_trab
		WHERE planif_clientes_superv_trab.cod_planif_cl = '$apertura'
		AND planif_clientes_superv_trab.cod_ubicacion = '$ubic' ";
		$query = $this->bd->consultar($sql);

		$sql = "DELETE FROM planif_clientes_superv_trab
		WHERE planif_clientes_superv_trab.cod_planif_cl = '$apertura'
		AND planif_clientes_superv_trab.cod_ubicacion = '$ubic' ";
		$query = $this->bd->consultar($sql);
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

	function verificar_cont($cliente, $contratacion)
	{
		$this->datos  = array();
		$sql = "SELECT COUNT(codigo) apertura FROM planif_clientes_superv WHERE `status` = 'T' AND cod_contratacion = '$contratacion' AND cod_cliente = '$cliente'";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_trab($cliente, $ubicacion)
	{
		$this->datos  = array();
		$sql = "SELECT a.cod_ficha, a.cedula, CONCAT(a.apellidos,' ', a.nombres) ap_nomb
		FROM ficha AS a ,	control
		WHERE a.cod_ficha_status = control.ficha_activo
		AND a.cod_ficha NOT IN(SELECT cod_ficha FROM clientes_vetados WHERE clientes_vetados.cod_cliente='$cliente' 
		AND clientes_vetados.cod_ubicacion='$ubicacion')
		ORDER BY a.cod_ficha ASC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_dias_planif_apertura($cliente, $apertura)
	{
		$this->datos  = array();
		$sql = "SELECT
		a.codigo,
		a.fecha,
		cl.nombre cliente,
		cl.codigo cod_cliente,
		cu.descripcion ubicacion,
		cu.codigo cod_ubicacion,
		t.abrev turno,
		t.codigo cod_turno,
		a.cantidad,
		a.fec_us_mod fecha_mod,
		CONCAT(mu.apellido,', ',mu.nombre) nombres
		FROM
		clientes_supervision_ap AS a,
		turno AS t,
		horarios AS h,
		dias_habiles,
		dias_habiles_det,
		dias_tipo,
		clientes AS cl,
		clientes_ubicacion AS cu,
		men_usuarios mu
		WHERE
		a.cod_turno = t.codigo
		AND mu.codigo = a.cod_us_mod
		AND t.cod_horario = h.codigo
		AND t.cod_dia_habil = dias_habiles.codigo
		AND dias_habiles_det.cod_dias_habiles = dias_habiles.codigo
		AND (
		(
		dias_habiles_det.cod_dias_tipo = dias_tipo.dia
		AND Dia_semana (a.fecha) = dias_tipo.descripcion
		)
		OR (
		dias_habiles_det.cod_dias_tipo = dias_tipo.dia
		AND dias_tipo.tipo = 'D'
		)
		OR (
		dias_habiles_det.cod_dias_tipo = dias_tipo.dia
		AND DATE_FORMAT(a.fecha, '%d') = dias_tipo.descripcion
		)
		)
		AND a.cod_cliente = cl.codigo
		AND a.cod_ubicacion = cu.codigo
		AND a.cod_cliente = '$cliente'
		AND a.cod_planif_cl = $apertura
		AND a.`status` = 'T'
		ORDER BY 2,3,5,4,6,7
		";



		$query = $this->bd->consultar($sql);
		//$this->datos["sql"] = $sql;
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}


	function get_trab_sin_planif($cliente, $apertura)
	{
		$this->datos  = array();

		$sql = "SELECT  v_ficha.rol, v_ficha.cod_ficha, v_ficha.cedula, 
		v_ficha.ap_nombre
		FROM v_ficha, control, cargos, clientes_ubicacion, clientes
		WHERE v_ficha.cod_ficha_status = control.ficha_activo  
		AND clientes.codigo = '$cliente'
		AND clientes_ubicacion.cod_region = clientes.cod_region
		AND v_ficha.cod_cargo = cargos.codigo
		AND v_ficha.cod_ubicacion = clientes_ubicacion.codigo
		AND cargos.planificable = 'T'
		AND v_ficha.cod_ficha NOT IN (SELECT planif_clientes_superv_trab.cod_ficha FROM planif_clientes_superv_trab
		WHERE planif_clientes_superv_trab.cod_planif_cl = '$apertura' AND  planif_clientes_superv_trab.cod_cliente = $cliente)
		ORDER BY 1,3";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
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


	function get_fechas_apertura($apertura, $ubic, $cargo)
	{
		$this->datos  = array();
		$sql = " SELECT fecha_inicio, fecha_fin FROM planif_clientes_superv 
		WHERE codigo = 3693 ; ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function validar_fecha($fecha, $cliente)
	{
		$this->datos  = array();
		$this->datos["data"]  = array();
		$this->datos["datacliente"]  = array();

		$sql = "SELECT clientes.codigo, agendas_contactos_fc_actividades.codigo  cod_horario,
		MIN(agendas_contactos_fc_actividades.hora) hora_entrada, MAX(agendas_contactos_fc_actividades.hora) hora_salida
		FROM clientes , agendas_contactos_fc_actividades 
		WHERE clientes.codigo =agendas_contactos_fc_actividades.cod_cliente AND clientes.codigo = '$cliente' ";
		$this->datos["sql"] = $sql;
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos["data"][] = $datos;
		}
		if (count($this->datos["data"]) == 0) {
			$this->datos["msg"] = "El turno de la ficha " . $cod_ficha . " no aplica la fecha " . $fecha . "";
		} else {
			if ($this->datos["data"][0]["cod_ficha"] === null) {
				$this->datos["msg"] = "El turno de la ficha " . $cod_ficha . " no aplica la fecha " . $fecha . "";
				$this->datos["data"] = [];
			}
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
		$sql = "SELECT MIN(h.hora_entrada) hora_inicio, MAX(h.hora_salida) hora_fin
		FROM clientes_supervision_ap  a,turno  t,horarios  h, dias_habiles, dias_habiles_det, dias_tipo, clientes_ubicacion cu
		WHERE a.cod_turno = t.codigo AND t.cod_horario = h.codigo AND t.cod_dia_habil = dias_habiles.codigo
		AND dias_habiles_det.cod_dias_habiles = dias_habiles.codigo   
		AND ((dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana(a.fecha)= dias_tipo.descripcion) 
		OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D') 
		OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT(a.fecha,'%d') = dias_tipo.descripcion))
		AND a.cod_ubicacion = cu.codigo AND a.cod_cliente = '$cliente' AND a.cod_ubicacion = '$ubic'
		AND a.cod_planif_cl = $apertura AND a.`status`='T'  AND a.fecha = '$fecha'
		HAVING (hora_inicio <= '$hora_inicio' AND hora_fin >= '$hora_fin') OR (hora_inicio = hora_fin)";
		$result["sql"] = $sql;
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		if (count($this->datos) > 0) {
			$sql2 = " SELECT p.cod_ubicacion
				FROM planif_clientes_superv_trab p, planif_clientes_superv_trab_det pd
				WHERE p.codigo = pd.cod_planif_cl_trab 
				AND ((p.cod_ficha = '$ficha'
				AND p.cod_planif_cl = $apertura) OR 
				(p.cod_ubicacion = '$ubic'
				AND p.cod_planif_cl = $apertura))
				";
			$i = 0;
			foreach ($actividades as $key => $value) {
				if ($i == 0) {
					$sql2 .= " AND ((pd.cod_proyecto = " . $value['cod_proyecto'] . " AND  pd.cod_actividad = " . $value['codigo'] . ")";
				} else {
					$sql2 .= " OR (pd.cod_proyecto = " . $value['cod_proyecto'] . " AND pd.cod_actividad = " . $value['codigo'] . ")";
				}
				$i++;
			};
			$sql2 .= ") AND DATE_FORMAT(pd.fecha_inicio,'%Y-%m-%d') = '$fecha'
			AND (
				(
					TIME(pd.fecha_inicio) >= '$hora_inicio' 
					AND TIME(pd.fecha_inicio) <= '$hora_fin'
					AND TIME(pd.fecha_fin) > '$hora_inicio'
					AND (TIME(pd.fecha_fin) >= '$hora_fin' OR TIME(pd.fecha_fin) < '$hora_fin')
				)
				OR (
					TIME(pd.fecha_inicio) <= '$hora_inicio' 
					AND TIME(pd.fecha_inicio) <= '$hora_fin'
					AND TIME(pd.fecha_fin) > '$hora_inicio'
					AND TIME(pd.fecha_fin) <= '$hora_fin'
				)
			)";
			$result["sql2"] = $sql2;
			$query2 = $this->bd->consultar($sql2);
			while ($datos = $this->bd->obtener_fila($query2, 0)) {
				$horas[] = $datos;
			}
			if (count($horas) > 0) {
				$result["error"] = true;
				$result["msg"] = "Ya existe planificacion en este rango de horas para esta ficha o ubicación.";
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
}
