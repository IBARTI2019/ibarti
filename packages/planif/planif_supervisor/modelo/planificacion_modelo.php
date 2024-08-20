<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);
require_once('../../../../sql/sql_report.php');

class Planificacion
{
	private $datos;
	private $bd;

	function __construct()
	{
		$this->datos   = array();
		$this->bd = new Database;
	}

	function get_cliente($usuario, $r_cliente)
	{
		$this->datos   = array();

		if ($r_cliente == "F") {
			$sql = "  SELECT clientes.codigo, 
					-- IF(COUNT(clientes_contratacion.codigo) = 0, 'S/P - ' , '') sc,
					IF(COUNT(clientes_contratacion.codigo) = 0, '' , '') sc,
					clientes.abrev, clientes.nombre cliente
					FROM clientes LEFT JOIN clientes_contratacion ON clientes.codigo = clientes_contratacion.cod_cliente
					WHERE clientes.`status` = 'T'
					GROUP BY clientes.codigo ORDER BY 2 ASC ";
		  } else {
			$sql = "  SELECT clientes.codigo, 
						-- IF(COUNT(clientes_contratacion.codigo) = 0, 'S/P - ' , '') sc,
						IF(COUNT(clientes_contratacion.codigo) = 0, '' , '') sc,
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

	function get_supervisores($ubicacion, $filtro, $usuario, $cargo)
	{
		$this->datos   = array();
		$sql = "SELECT clientes_ubicacion.cod_region FROM clientes_ubicacion WHERE  clientes_ubicacion.codigo = $ubicacion";
		$query_region = $this->bd->consultar($sql);
		$dato = $this->bd->obtener_fila($query_region);
		$region = $dato[0];
		$where = " WHERE v_ficha.cod_ubicacion = clientes_ubicacion.codigo
		AND clientes_ubicacion.`status` = 'T'
		AND v_ficha.cod_cargo = cargos.codigo
		AND cargos.planificable = 'T'
		AND v_ficha.cod_ficha_status = control.ficha_activo 
		AND men_usuarios.codigo = '$usuario'
		AND men_usuarios.cod_perfil = planif_perfil_cargos.cod_perfil
		AND planif_perfil_cargos.cod_cargo = v_ficha.cod_cargo
		AND planif_perfil_cargos.`status` = 'T'";

		if ($filtro != null and $filtro != "") {
			$where .= " AND (LOCATE('$filtro', v_ficha.cod_ficha) 
			OR LOCATE('$filtro', v_ficha.ap_nombre) 
			OR LOCATE('$filtro', cargos.descripcion)) ";
		}

		if ($cargo != '') {
			$sql = "SELECT tipos_cargo.descripcion FROM cargos, tipos_cargo WHERE cargos.cod_tipo = tipos_cargo.codigo AND cargos.codigo = '$cargo'";
			$query_tipo_cargo = $this->bd->consultar($sql);
			$dato = $this->bd->obtener_fila($query_tipo_cargo);
			$tipo_cargo = $dato[0];
			if ($tipo_cargo == 'REGIONAL') {
				$where .= " AND clientes_ubicacion.cod_region = '$region'";
			}
			$where .= " AND v_ficha.cod_cargo = '$cargo'";
		}

		$sql = "  SELECT v_ficha.cod_ficha, v_ficha.ap_nombre, v_ficha.cargo, v_ficha.nombres, v_ficha.apellidos, v_ficha.cedula, 
		cargos.descripcion cargo
		FROM v_ficha, cargos, control, clientes_ubicacion, men_usuarios, planif_perfil_cargos
		" . $where . "
		ORDER BY 1,3 ASC ";

		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	public function get_cargos($cliente, $ubic, $usuario)
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
						cargos c,
						men_usuarios,
						planif_perfil_cargos
					WHERE
						a.cod_cliente = $cliente
					AND a.cod_cargo = c.codigo
					AND men_usuarios.codigo = '$usuario'
					AND men_usuarios.cod_perfil = planif_perfil_cargos.cod_perfil
					AND planif_perfil_cargos.cod_cargo = c.codigo
					AND planif_perfil_cargos.`status` = 'T'
					GROUP BY 1  ";
			if ($ubic != '') {
				$sql = " SELECT
						c.codigo,
						c.descripcion
					FROM
						clientes_supervision a,
						cargos c,
						men_usuarios,
						planif_perfil_cargos
					WHERE
						a.cod_ubicacion = $ubic
					AND a.cod_cargo = c.codigo
					AND men_usuarios.codigo = '$usuario'
					AND men_usuarios.cod_perfil = planif_perfil_cargos.cod_perfil
					AND planif_perfil_cargos.cod_cargo = c.codigo
					AND planif_perfil_cargos.`status` = 'T'
					GROUP BY 1  ";
			}
		}
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

	function get_planif_ap_ubic($cliente, $usuario, $r_cliente)
	{
		$this->datos  = array();
		if ($r_cliente == "F") {
			$sql = "SELECT b.cod_ubicacion, c.descripcion
			FROM  clientes_supervision b , clientes_ubicacion c
			WHERE b.cod_ubicacion = c.codigo
			AND c.cod_cliente = '$cliente'
			AND c.`status` = 'T'
			GROUP BY b.cod_ubicacion ORDER BY c.descripcion DESC ";
		}else {
			$sql = "SELECT b.cod_ubicacion, c.descripcion
			FROM  clientes_supervision b , clientes_ubicacion c, usuario_clientes
			WHERE b.cod_ubicacion = c.codigo
			AND c.cod_cliente = '$cliente'
			AND c.`status` = 'T'
			AND usuario_clientes.cod_usuario = '$usuario'  AND usuario_clientes.cod_ubicacion = c.codigo
			GROUP BY b.cod_ubicacion ORDER BY c.descripcion DESC ";
		}
		
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

	function get_actividades($proyecto, $ficha)
	{
		$this->datos  = array();
		$where = " WHERE pa.`status` = 'T' AND pa.cod_proyecto = pp.codigo
			AND ficha.cod_ficha = '$ficha'
			AND ficha.cod_cargo = ppc.cod_cargo
			AND pa.cod_proyecto = ppc.cod_proyecto
			AND ppc.`status` = 'T'";
		if ($proyecto != null) {
			$where .= " AND pa.cod_proyecto = " . $proyecto . " ";
		}
		$sql = "SELECT pp.codigo cod_proyecto, pp.descripcion proyecto_descripcion, pa.codigo, pa.descripcion, pa.minutos, pa.obligatoria
		FROM  planif_actividad pa, planif_proyecto pp, ficha, planif_proyecto_cargos ppc
		" . $where . "
		ORDER BY pp.codigo ASC, pa.codigo ASC, pa.obligatoria DESC, pa.descripcion DESC";

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

	function get_planif_det($apertura, $ubic, $cargo)
	{
		$this->datos   = array();
		$sql = "SELECT pcst.codigo, pcst.cod_cliente, cl.nombre cliente,
			pcst.cod_ubicacion, cu.descripcion ubicacion, pcstd.cod_proyecto, pp.descripcion proyecto,
			pcstd.cod_actividad, pa.descripcion actividad, pp.abrev abrev_proyecto, pcst.cod_ficha, 
			CONCAT(f.apellidos,' ', f.nombres) trabajador, f.cedula, f.cod_cargo, c.descripcion cargo, pcst.fecha_inicio, pcst.fecha_fin,
			pa.obligatoria, pcstd.fecha_inicio fecha_inicio_act, pcstd.fecha_fin fecha_fin_act, pcstd.realizado, pcst.completado
			FROM planif_clientes_superv_trab_det pcstd, planif_clientes_superv_trab pcst, ficha f, cargos c, control, clientes cl, 
				clientes_ubicacion cu, planif_proyecto pp, planif_actividad pa
			WHERE pcst.cod_ficha = f.cod_ficha
			AND f.cod_ficha_status= control.ficha_activo
			AND pcst.cod_planif_cl = $apertura
			AND f.cod_cargo = c.codigo
			AND c.planificable = 'T'
			AND pcst.cod_cliente = cl.codigo
			AND pcst.cod_ubicacion = cu.codigo
			AND pcstd.cod_proyecto = pp.codigo
			AND pcstd.cod_planif_cl_trab = pcst.codigo
			AND pcstd.cod_actividad = pa.codigo
			AND cu.codigo = $ubic
			AND c.codigo = '$cargo'
			ORDER BY codigo ASC, obligatoria DESC, fecha_inicio_act ASC";
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
		planif_clientes_superv_trab a
		INNER JOIN men_usuarios b ON a.cod_us_mod = b.codigo
		WHERE a.cod_planif_cl = '$apertura'";

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
		$sql = "SELECT COUNT(clientes_supervision.codigo) contra FROM clientes_supervision, clientes_ubicacion 
		WHERE clientes_supervision.cod_ubicacion = clientes_ubicacion.codigo 
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
		$sql = "SELECT COUNT(v_ficha.cod_ficha) cantidad
		FROM v_ficha, cargos, control, clientes_ubicacion, clientes
		WHERE v_ficha.cod_ficha_status = control.ficha_activo
		AND clientes.codigo = '$cliente'
		AND clientes_ubicacion.cod_region = clientes.cod_region
		AND v_ficha.cod_ubicacion = clientes_ubicacion.codigo
		AND v_ficha.cod_cargo = cargos.codigo
		AND cargos.planificable = 'T'
		AND v_ficha.cod_ficha NOT IN (SELECT planif_clientes_superv_trab.cod_ficha FROM planif_clientes_superv_trab
		WHERE planif_clientes_superv_trab.cod_planif_cl = $apertura AND  planif_clientes_superv_trab.cod_cliente = '$cliente')";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}


	function get_fechas_apertura($apertura, $ubic, $cargo)
	{
		$this->datos  = array();
		$sql = " SELECT fecha_inicio, fecha_fin FROM planif_clientes_superv 
		WHERE cod_cargo = '$cargo' AND cod_ubicacion = $ubic AND codigo = $apertura ; ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function validar_fecha($fecha, $cliente, $apertura, $ubicacion, $cod_ficha, $cargo)
	{
		$this->datos  = array();
		$this->datos["data"]  = array();
		$this->datos["datacliente"]  = array();
		$sql = "SELECT Dia_semana(a.fecha) dia_semana, a.cod_ubicacion, cu.descripcion ubicacion, a.fecha,
		Sum(a.cantidad)  cantidad, h.codigo  cod_horario, h.nombre  horario,
		MIN(h.hora_entrada) hora_entrada, MAX(h.hora_salida) hora_salida
		FROM clientes_supervision_ap  a,turno  t,horarios  h, dias_habiles, dias_habiles_det, dias_tipo, clientes_ubicacion cu, ficha f
	   WHERE a.cod_turno = t.codigo AND t.cod_horario = h.codigo AND t.cod_dia_habil = dias_habiles.codigo
	   AND dias_habiles_det.cod_dias_habiles = dias_habiles.codigo   
	   AND ((dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana(a.fecha)= dias_tipo.descripcion) 
	   OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D') 
	   OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT(a.fecha,'%d') = dias_tipo.descripcion))
	   AND a.cod_ubicacion = cu.codigo AND a.cod_cliente = '$cliente' 
	   AND a.cod_planif_cl = $apertura AND a.`status`='T'  AND a.fecha = '$fecha'
	   AND cu.codigo = '$ubicacion' AND a.cod_cargo = f.cod_cargo
		AND f.cod_ficha = '$cod_ficha'
		AND a.cod_cargo = '$cargo'
	   GROUP BY a.cod_cliente, a.cod_ubicacion, a.cod_turno, a.fecha";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos["datacliente"][] = $datos;
		}
		if (count($this->datos["datacliente"]) > 0) {
			$sql = "SELECT a.cod_ficha, h.codigo  cod_horario, h.nombre  horario,
			MIN(h.hora_entrada) hora_entrada, MAX(h.hora_salida) hora_salida,  dias_habiles.descripcion dias_habiles
			FROM ficha a, horarios h, turno t, dias_habiles, dias_habiles_det, dias_tipo
			WHERE a.cod_turno = t.codigo AND t.cod_horario = h.codigo AND t.cod_dia_habil = dias_habiles.codigo
			AND dias_habiles_det.cod_dias_habiles = dias_habiles.codigo   
			AND ((dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND Dia_semana('$fecha')= dias_tipo.descripcion) 
			OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND dias_tipo.tipo = 'D') 
			OR (dias_habiles_det.cod_dias_tipo = dias_tipo.dia AND DATE_FORMAT('$fecha','%d') = dias_tipo.descripcion))
			AND a.cod_ficha = '$cod_ficha' ";
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
		} else {
			$this->datos["msg"] = "El cliente no aplica la fecha " . $fecha . " o el cargo del trabjador no es planificable en el mismo.";
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
