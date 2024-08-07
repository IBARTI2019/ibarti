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
		$sql = "  SELECT clientes.codigo, IF(COUNT(clientes_contratacion.codigo) = 0, 'S/C - ' , '') sc,
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

	function replicar_rot($cliente, $ubic, $contratacion, $apertura, $usuario)
	{
		$sql = "CALL p_planif_serv_rotacion('$cliente','$ubic',$contratacion,$apertura,'$usuario');";
		$query = $this->bd->consultar($sql);
		return $query;
	}

	function get_cl_contrato($cliente)
	{

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

	function get_planif_contrato($cliente)
	{
		$this->datos  = array();
		$sql = "SELECT codigo, descripcion,fecha_inicio
		FROM clientes_contratacion 
		WHERE cod_cliente = '$cliente' AND status = 'T'";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_ap($apertura)
	{
		$this->datos  = array();
		$sql = " SELECT a.codigo, a.fecha_inicio, a.fecha_fin, a.`status`
		FROM planif_clientes a
		WHERE a.codigo = '$apertura' ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function delete_planif_ap($apertura)
	{
		$existencia  = array();
		$sql = "SELECT a.codigo
		FROM planif_clientes_trab a
		WHERE a.cod_planif_cl = '$apertura' LIMIT 2";
		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$existencia[] = $datos;
		}
		if (empty($existencia) && $apertura) {
			$sql = "DELETE fROM planif_clientes WHERE codigo = '$apertura';";
			$query = $this->bd->consultar($sql);
			return $query;
		}
		return null;
	}

	function get_planif_apertura($cliente, $contratacion)
	{
		$this->datos  = array();
		$sql = "SELECT a.*, CONCAT(b.apellido,' ', b.nombre) us_mod
		FROM planif_clientes a LEFT JOIN men_usuarios b ON a.cod_us_mod = b.codigo
		WHERE a.cod_cliente = '$cliente' AND a.cod_contratacion = '$contratacion'
		ORDER BY 1 DESC";
		$query = $this->bd->consultar($sql);

		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}


	function get_planif_act($cliente, $contratacion)
	{
		$this->datos = array();
		$sql = "SELECT a.* FROM planif_clientes a
		WHERE a.`status` = 'T' AND a.cod_cliente = '$cliente' AND a.cod_contratacion = '$contratacion'
		ORDER BY 1 DESC;";
		$query    = $this->bd->consultar($sql);

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_ap_ubic($cliente, $contratacion)
	{
		$this->datos  = array();
		$sql = "SELECT b.cod_ubicacion, c.descripcion
		FROM  clientes_contratacion_det b , clientes_ubicacion c
		WHERE b.cod_contracion = $contratacion
		AND b.cod_ubicacion = c.codigo
		AND c.`status` = 'T'
		GROUP BY b.cod_ubicacion ORDER BY c.descripcion DESC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_ap_inicio($cliente, $contratacion)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, IFNULL (DATE_ADD(fecha_fin, INTERVAL 1 DAY), CURDATE()) fecha_inicio
		FROM planif_clientes a WHERE a.cod_cliente = '$cliente' AND a.cod_contratacion = '$contratacion'
		ORDER BY a.codigo DESC LIMIT 0, 1; ";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_contratacion_det($contratacion, $ubic)
	{
		$this->datos  = array();
		$sql = "SELECT b.descripcion ubicacion, c.nombre ub_puesto,
		d.abrev turno_abrev, d.descripcion turno,
		e.descripcion cargo, a.cantidad
		FROM clientes_contratacion_det a, clientes_ubicacion b,
		clientes_ub_puesto c, 	turno d, cargos e
		WHERE a.cod_contracion = '$contratacion'
		AND a.cod_ubicacion = '$ubic'
		AND a.cod_ubicacion = b.codigo AND a.cod_ub_puesto = c.codigo
		AND a.cod_turno = d.codigo AND a.cod_cargo = e.codigo ORDER BY 2 DESC ";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_planif_det($apertura, $ubic)
	{

		$sql = "SELECT c.codigo, a.fecha_inicio ap_fecha_inicio,  a.fecha_fin ap_fecha_fin,
		b.cod_ficha, CONCAT (b.apellidos,' ',b.nombres,IF(d.cod_ficha,' (VETADO)','')) trabajador,
		c.cod_rotacion, (SELECT x.descripcion  FROM rotacion x WHERE x.codigo =  c.cod_rotacion) rotacion,
		c.cod_puesto_trabajo, (SELECT x.nombre  FROM clientes_ub_puesto x WHERE x.codigo =  c.cod_puesto_trabajo) puesto_trabajo,
		c.posicion_inicio, RotTurno(c.cod_rotacion, c.posicion_inicio) turno,
		IFNULL(c.fecha_inicio, a.fecha_inicio) fecha_inicio, IFNULL(c.fecha_fin, a.fecha_fin) fecha_fin, IFNULL(d.cod_ficha,'NO') vetado
		FROM planif_clientes a, ficha b 
		LEFT JOIN planif_clientes_trab c ON  b.cod_ficha = c.cod_ficha AND c.cod_planif_cl = '$apertura' AND c.cod_ubicacion = '$ubic'
		LEFT JOIN clientes_vetados d ON  (c.cod_ficha = d.cod_ficha AND c.cod_ubicacion = d.cod_ubicacion) 
		OR (b.cod_ficha = d.cod_ficha AND b.cod_ubicacion = d.cod_ubicacion), control
		WHERE a.codigo = '$apertura'
		AND b.cod_ubicacion = '$ubic'
		AND b.cod_ficha_status= control.ficha_activo
		UNION ALL
		SELECT b.codigo,  a.fecha_inicio ap_fecha_inicio,  a.fecha_fin ap_fecha_fin,
		b.cod_ficha, CONCAT (c.apellidos,' ',c.nombres,IF(d.cod_ficha,' (VETADO)','')) trabajador,
		b.cod_rotacion , rotacion.descripcion rotacion,
		b.cod_puesto_trabajo, clientes_ub_puesto.nombre puesto_trabajo,
		b.posicion_inicio , RotTurno(b.cod_rotacion, b.posicion_inicio) turno,
		IFNULL(b.fecha_inicio, a.fecha_inicio) fecha_inicio, IFNULL(b.fecha_fin, a.fecha_fin) fecha_fin,IFNULL(d.cod_ficha,'NO') vetado
		FROM planif_clientes a, planif_clientes_trab b LEFT JOIN clientes_vetados d ON  b.cod_ficha = d.cod_ficha AND b.cod_ubicacion = d.cod_ubicacion, ficha c, rotacion, clientes_ub_puesto
		WHERE a.codigo = '$apertura'
		AND a.codigo = b.cod_planif_cl
		AND b.cod_ficha = c.cod_ficha
		AND b.cod_ubicacion = '$ubic'
		AND b.cod_rotacion = rotacion.codigo
		AND b.cod_puesto_trabajo = clientes_ub_puesto.codigo
		AND b.cod_ubicacion != c.cod_ubicacion
		ORDER BY 4 ASC ;";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function get_ultima_mod($apertura, $ubic)
	{

		$sql = "SELECT MAX(a.fec_us_mod) fecha,CONCAT(b.apellido,' ',b.nombre) us_mod
		FROM
		planif_clientes_trab_det a
		INNER JOIN men_usuarios b ON a.cod_us_mod = b.codigo
		WHERE a.cod_planif_cl = '$apertura' AND a.cod_ubicacion = '$ubic'";

		$query = $this->bd->consultar($sql);
		return $datos = $this->bd->obtener_fila($query);
	}

	function get_planif_trab_ap($cod_pl_trab)
	{
		$this->datos  = array();
		$sql = "SELECT a.cod_planif_cl, planif_clientes.fecha_inicio,
		planif_clientes.fecha_fin, planif_clientes.`status`,
		a.cod_cliente , clientes.nombre cliente, clientes.abrev cl_abrev,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_puesto_trabajo, clientes_ub_puesto.nombre AS puesto_trabajo,
		ficha.cod_ficha, CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre,
		rotacion.abrev rotacion_abrev, rotacion.descripcion rotacion,
		IFNULL(b.cod_ficha,'NO') vetado
		FROM planif_clientes_trab a LEFT JOIN clientes_vetados b ON  a.cod_ficha = b.cod_ficha
		AND a.cod_ubicacion = b.cod_ubicacion, planif_clientes,  clientes_ub_puesto, ficha,
		clientes, clientes_ubicacion, rotacion
		WHERE a.codigo = '$cod_pl_trab'
		AND a.cod_planif_cl = planif_clientes.codigo
		AND a.cod_puesto_trabajo =clientes_ub_puesto.codigo
		AND a.cod_ficha = ficha.cod_ficha
		AND a.cod_cliente = clientes.codigo
		AND a.cod_ubicacion = clientes_ubicacion.codigo
		AND a.cod_rotacion = rotacion.codigo";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function get_planif_trab_det($cod_pl_trab)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, a.fecha, Dia_semana_abrev(a.fecha) d_semana,
		a.cod_cliente, clientes.abrev cliente,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_puesto_trabajo, clientes_ub_puesto.nombre puesto_trabajo,
		ficha.cod_ficha, CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre,
		a.cod_turno,turno.abrev tuno_abrev, turno.descripcion turno
		FROM planif_clientes_trab_det a, turno, clientes_ub_puesto, ficha, clientes, clientes_ubicacion
		WHERE a.cod_planif_cl_trab = '$cod_pl_trab'
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

	function get_planif_trabajador($aperuta, $ficha)
	{
		$this->datos  = array();
		$sql = "SELECT a.codigo, a.fecha, Dia_semana_abrev(a.fecha) d_semana,
		a.cod_cliente, clientes.abrev cliente,
		a.cod_ubicacion, clientes_ubicacion.descripcion ubicacion,
		a.cod_puesto_trabajo, clientes_ub_puesto.nombre puesto_trabajo,
		ficha.cod_ficha, CONCAT(ficha.apellidos,' ',ficha.nombres) ap_nombre,
		a.cod_turno,turno.abrev tuno_abrev, turno.descripcion turno
		FROM planif_clientes_trab_det a, turno, clientes_ub_puesto, ficha, clientes, clientes_ubicacion
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
		$sql = "DELETE FROM planif_clientes_trab
		WHERE planif_clientes_trab.cod_planif_cl = '$apertura'
		AND planif_clientes_trab.cod_ubicacion = '$ubic' ";
		$query = $this->bd->consultar($sql);

		$sql = "DELETE FROM planif_clientes_trab
		WHERE planif_clientes_trab.cod_planif_cl = '$apertura'
		AND planif_clientes_trab.cod_ubicacion = '$ubic' ";
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
		$sql = "SELECT COUNT(codigo) contra FROM clientes_contratacion WHERE `status` = 'T' AND cod_cliente = '$cliente'";
		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}

	function verificar_cont($cliente, $contratacion)
	{
		$this->datos  = array();
		$sql = "SELECT COUNT(codigo) apertura FROM planif_clientes WHERE `status` = 'T' AND cod_contratacion = '$contratacion' AND cod_cliente = '$cliente'";
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

	function get_dias_planif_apertura($cliente, $ubic, $contratacion, $apertura)
	{
		$this->datos  = array();
		$sql = "SELECT
		a.codigo,
		a.fecha,
		cl.nombre cliente,
		cl.codigo cod_cliente,
		cp.nombre puesto,
		cp.codigo cod_puesto,
		cu.descripcion ubicacion,
		cu.codigo cod_ubicacion,
		t.abrev turno,
		t.codigo cod_turno,
		c.descripcion cargo,
		c.codigo cod_cargo,
		a.cantidad,
		a.fec_us_mod fecha_mod
		FROM
		clientes_contratacion_ap AS a,
		turno AS t,
		horarios AS h,
		dias_habiles,
		dias_habiles_det,
		dias_tipo,
		clientes AS cl,
		clientes_ubicacion AS cu,
		clientes_ub_puesto AS cp,
		cargos c
		WHERE
		a.cod_turno = t.codigo
		AND t.cod_horario = h.codigo
		AND c.codigo = a.cod_cargo
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
		AND a.cod_ub_puesto = cp.codigo
		AND a.cod_cliente = '$cliente'
		AND a.cod_ubicacion = '$ubic'
		AND a.cod_contratacion = '$contratacion'
		AND a.cod_planif_cl = '$apertura'
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


	function get_trab_sin_planif($cliente, $ubic, $apertura)
	{
		$this->datos  = array();

		$sql = "SELECT  v_ficha.rol, v_ficha.cod_ficha, v_ficha.cedula, 
		v_ficha.ap_nombre, v_ficha.cargo
		FROM v_ficha, control, clientes, clientes_ubicacion
		WHERE v_ficha.cod_ficha_status = control.ficha_activo 
		AND clientes.codigo = clientes_ubicacion.cod_cliente 
		AND clientes_ubicacion.codigo = v_ficha.cod_ubicacion
		AND v_ficha.cod_cliente = '$cliente' AND v_ficha.cod_ubicacion = $ubic
		AND v_ficha.cod_ficha NOT IN (SELECT planif_clientes_trab_det.cod_ficha FROM planif_clientes_trab_det
		WHERE planif_clientes_trab_det.cod_planif_cl = '$apertura' AND  planif_clientes_trab_det.cod_cliente = '$cliente' AND planif_clientes_trab_det.cod_ubicacion = $ubic)
		AND v_ficha.cod_ficha NOT IN (SELECT clientes_vetados.cod_ficha FROM clientes_vetados
		WHERE clientes_vetados.cod_cliente = '$cliente' AND clientes_vetados.cod_ubicacion = $ubic)";

		$query = $this->bd->consultar($sql);
		while ($datos = $this->bd->obtener_fila($query, 0)) {
			$this->datos[] = $datos;
		}
		return $this->datos;
	}

	function cantidad_trab_sin_planif($cliente, $ubic, $apertura)
	{
		$this->datos  = array();
		$sql = "SELECT COUNT(v_ficha.cod_ficha) cantidad
		FROM v_ficha, control
		WHERE v_ficha.cod_ficha_status = control.ficha_activo 
		AND v_ficha.cod_cliente = '$cliente' AND v_ficha.cod_ubicacion = $ubic
		AND v_ficha.cod_ficha NOT IN (SELECT planif_clientes_trab_det.cod_ficha FROM planif_clientes_trab_det
		WHERE planif_clientes_trab_det.cod_planif_cl = '$apertura' AND  planif_clientes_trab_det.cod_cliente = '$cliente' AND planif_clientes_trab_det.cod_ubicacion = $ubic)
		AND v_ficha.cod_ficha NOT IN (SELECT clientes_vetados.cod_ficha FROM clientes_vetados
		WHERE clientes_vetados.cod_cliente = '$cliente' AND clientes_vetados.cod_ubicacion = $ubic)";

		$query = $this->bd->consultar($sql);
		return $this->datos = $this->bd->obtener_fila($query);
	}
}
