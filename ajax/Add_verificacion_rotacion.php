<?php
define("SPECIALCONSTANT",true);
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bdI;
require "../".Leng;

$bd = new DataBase();
$bd2 = new DataBase();

if(($_POST['fecha_desde'] == "")){
	exit;
}
	
$fecha_D         = conversion($_POST['fecha_desde']);

$quincena       = $_POST['quincena'];
$nomina          = $_POST['nomina'];
$rol             = $_POST['rol'];

if(isset($_POST['region'])){
	$region          = $_POST['region'];
}else{
	$region = 'TODOS';
}

if(isset($_POST['estado'])){
$estado          = $_POST['estado'];
}else{
	$estado = 'TODOS';
}

if(isset($_POST['ciudad'])){
$ciudad          = $_POST['ciudad'];
}else{
	$ciudad = 'TODOS';
}

if(isset($_POST['cliente'])){
$cliente          = $_POST['cliente'];
}else{
	$cliente = 'TODOS';
}

if(isset($_POST['ubicacion'])){
$ubicacion          = $_POST['ubicacion'];
}else{
	$ubicacion = 'TODOS';
}

$fecha_N = explode("-", $fecha_D);
$year1   = $fecha_N[0];
$mes1    = $fecha_N[1];
$dia1    = $fecha_N[2];

$fecha_Inc_M  = mktime(0,0,0,$mes1,$dia1,$year1);
$fec_mensual = "".$year1."-".$mes1."-01";

// Obtener la fecha actual
$fecha_actual = date("Y-m-d");

$where01 = "WHERE asistencia_quincenal01.fec_mensual = '$fec_mensual'
		AND r.cod_ubicacion = v_ficha.cod_ubicacion ";

$where02 = "WHERE asistencia_quincenal02.fec_mensual = '$fec_mensual'
		AND r.cod_ubicacion = v_ficha.cod_ubicacion ";

if($nomina != "TODOS"){
	$where01 .= " AND v_ficha.cod_contracto = '$nomina' ";
	$where02 .= " AND v_ficha.cod_contracto = '$nomina' ";
}

if($rol != "TODOS"){
	$where01 .= " AND v_ficha.cod_rol = '$rol' ";
	$where02 .= " AND v_ficha.cod_rol = '$rol' ";
}


if($region != "TODOS"){
	$where01 .= " AND v_ficha.cod_region = '$region' ";
	$where02 .= " AND v_ficha.cod_region = '$region' ";
}

if($estado != "TODOS"){
	$where01 .= " AND v_ficha.cod_estado = '$estado' ";
	$where02 .= " AND v_ficha.cod_estado = '$estado' ";
}

if($ciudad != "TODOS"){
	$where01  .= " AND v_ficha.cod_ciudad = '$ciudad' ";
	$where02  .= " AND v_ficha.cod_ciudad = '$ciudad' ";
}

if($cliente != "TODOS"){
	$where01  .= " AND v_ficha.cod_cliente = '$cliente' ";
	$where02  .= " AND v_ficha.cod_cliente = '$cliente' ";
}

if($ubicacion != "TODOS"){
	$where01  .= " AND v_ficha.cod_ubicacion = '$ubicacion' ";
	$where02  .= " AND v_ficha.cod_ubicacion = '$ubicacion' ";
}


if ($quincena == "01"){

	$fecha_H = $year1.'-'.$mes1.'-15';
	$fecha_D = $year1.'-'.$mes1.'-01';

	// Verificar si $fecha_H es mayor que la fecha actual
	if ($fecha_H > $fecha_actual) {
		// Si $fecha_H es mayor, asignar la fecha de ayer
		$fecha_H = date("Y-m-d", strtotime("-1 day"));
	}

	// QUERY A MOSTRAR //
	$sql = " SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.ap_nombre,
					v_ficha.rol,  v_ficha.region,
					v_ficha.estado,  v_ficha.ciudad,
					v_ficha.contracto,
					asistencia_quincenal01.d01, asistencia_quincenal01.d02,
					asistencia_quincenal01.d03, asistencia_quincenal01.d04,
					asistencia_quincenal01.d05, asistencia_quincenal01.d06,
					asistencia_quincenal01.d07, asistencia_quincenal01.d08,
					asistencia_quincenal01.d09, asistencia_quincenal01.d10,
					asistencia_quincenal01.d11, asistencia_quincenal01.d12,
					asistencia_quincenal01.d13, asistencia_quincenal01.d14,
					asistencia_quincenal01.d15,
					IF(verificar_equivalencia(r.secuencia_repetida, REPLACE(SUBSTRING_INDEX(CONCAT_WS(
						',',
						COALESCE(asistencia_quincenal01.d01, 'B'),
						COALESCE(asistencia_quincenal01.d02, 'B'),
						COALESCE(asistencia_quincenal01.d03, 'B'),
						COALESCE(asistencia_quincenal01.d04, 'B'),
						COALESCE(asistencia_quincenal01.d05, 'B'),
						COALESCE(asistencia_quincenal01.d06, 'B'),
						COALESCE(asistencia_quincenal01.d07, 'B'),
						COALESCE(asistencia_quincenal01.d08, 'B'),
						COALESCE(asistencia_quincenal01.d09, 'B'),
						COALESCE(asistencia_quincenal01.d10, 'B'),
						COALESCE(asistencia_quincenal01.d11, 'B'),
						COALESCE(asistencia_quincenal01.d12, 'B'),
						COALESCE(asistencia_quincenal01.d13, 'B'),
						COALESCE(asistencia_quincenal01.d14, 'B'),
						COALESCE(asistencia_quincenal01.d15, 'B')
					), ',', DATEDIFF('$fecha_H', '$fecha_D'') + 1), ' ', '')), 'SI', 'NO') AS cumple_rotacion
				FROM  asistencia_quincenal01
				JOIN v_ficha ON asistencia_quincenal01.cod_ficha = v_ficha.cod_ficha
				JOIN (
				SELECT
				p.cod_ficha,
				p.posicion_inicio,
				p.cod_ubicacion,
				r.secuencia_turnos,
				
				TRIM(BOTH ',' FROM (
					SUBSTRING_INDEX(
						REPEAT(
							CONCAT(
								TRIM(BOTH ',' FROM (
									CONCAT(
										SUBSTRING_INDEX(r.secuencia_turnos, ',', -((LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1) - (p.posicion_inicio + 1) + 1)),
										',',
										SUBSTRING_INDEX(r.secuencia_turnos, ',', (p.posicion_inicio + 1) - 1)
									)
								)),
								','
							),
							CEIL((DATEDIFF('$fecha_H', '$fecha_D') + 1) / 
							(LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1))
						),
						',',
									DATEDIFF('$fecha_H', '$fecha_D') + 1  
					)
				)) AS secuencia_repetida
			FROM
				planif_clientes_trab p
				JOIN (
					SELECT
						cod_rotacion,
						GROUP_CONCAT(c2.abrev ORDER BY rotacion_det.codigo ASC) AS secuencia_turnos
					FROM
						rotacion_det
						JOIN turno ON rotacion_det.cod_turno = turno.codigo
						JOIN horarios ON turno.cod_horario = horarios.codigo
						JOIN conceptos ON horarios.cod_concepto = conceptos.codigo
						JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
						JOIN conceptos c2 ON h2.cod_concepto = c2.codigo
					GROUP BY
						cod_rotacion
				) r ON p.cod_rotacion = r.cod_rotacion
			WHERE
				p.fecha_inicio = '$fec_mensual'
				) r ON asistencia_quincenal01.cod_ficha = r.cod_ficha
				$where01
			ORDER BY 1 ASC";
		echo $sql;
		echo "<table width='100%' border='0' align='center' class='tabla_sistema'>
			<tr><th>".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
					<th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
					<th> 01 </th><th> 02 </th><th> 03 </th><th> 04 </th>
					<th> 05 </th><th> 06 </th><th> 07 </th><th> 08 </th>
					<th> 09 </th><th> 10 </th><th> 11 </th><th> 12 </th>
					<th> 13 </th><th> 14 </th><th> 15 </th></tr>";
					

			$query = $bd->consultar($sql);
			while ($datos = $bd->obtener_fila($query, 0)) {
				if ($datos['cumple_rotacion'] == 'SI') {
					echo '<tr>';
				} else {
					echo '<tr class="color fondo03">';	
				}

				echo '
				<td class="texto">' . $result[0] . '</td>
				<td class="texto">' . $datos["cedula"] . '</td>
				<td class="texto">' . $datos["ap_nombre"] . '</td>
				<td class="texto">' . $datos["rol"] . '</td>
				<td class="texto">' . $datos["region"] . '</td>
				<td class="texto">' . $datos["estado"] . '</td>
				<td class="texto">' . $datos["ciudad"] . '</td>
				<td class="texto">' . $datos["contracto"] . '</td>
				<td class="texto">' . $datos["d01"] . '</td>
				<td class="texto">' . $datos["d02"] . '</td>
				<td class="texto">' . $datos["d03"] . '</td>
				<td class="texto">' . $datos["d04"] . '</td>
				<td class="texto">' . $datos["d05"] . '</td>
				<td class="texto">' . $datos["d06"] . '</td>
				<td class="texto">' . $datos["d07"] . '</td>
				<td class="texto">' . $datos["d08"] . '</td>
				<td class="texto">' . $datos["d09"] . '</td>
				<td class="texto">' . $datos["d10"] . '</td>
				<td class="texto">' . $datos["d11"] . '</td>
				<td class="texto">' . $datos["d12"] . '</td>
				<td class="texto">' . $datos["d13"] . '</td>
				<td class="texto">' . $datos["d14"] . '</td>
				<td class="texto">' . $datos["d15"] . '</td>
				</tr>';
			};
		echo '</table>';

}elseif($quincena == "02"){
		$fecha_x   = mktime(0,0,0, $mes1, 01,$year1);
		$fec_desde = strtotime("+1 months -1 day", $fecha_x);
		$fecha_H   = date("Y-m-d", $fec_desde);
		$fecha_D   = $year1.'-'.$mes1.'-16';

	// Verificar si $fecha_H es mayor que la fecha actual
	if ($fecha_H > $fecha_actual) {
		// Si $fecha_H es mayor, asignar la fecha de ayer
		$fecha_H = date("Y-m-d", strtotime("-1 day"));
	}

	$sql = " SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.ap_nombre,
				v_ficha.rol,  v_ficha.region,
				v_ficha.estado,  v_ficha.ciudad,
				v_ficha.contracto,
				asistencia_quincenal02.d16,
				asistencia_quincenal02.d17, asistencia_quincenal02.d18,
				asistencia_quincenal02.d19, asistencia_quincenal02.d20,
				asistencia_quincenal02.d21, asistencia_quincenal02.d22,
				asistencia_quincenal02.d23, asistencia_quincenal02.d24,
				asistencia_quincenal02.d25, asistencia_quincenal02.d26,
				asistencia_quincenal02.d27, asistencia_quincenal02.d28,
				asistencia_quincenal02.d29, asistencia_quincenal02.d30,
				asistencia_quincenal02.d31,
				IF(verificar_equivalencia(r.secuencia_repetida, REPLACE(SUBSTRING_INDEX(CONCAT_WS(
					',',
					COALESCE(asistencia_quincenal02.d16, 'B'),
					COALESCE(asistencia_quincenal02.d17, 'B'),
					COALESCE(asistencia_quincenal02.d18, 'B'),
					COALESCE(asistencia_quincenal02.d19, 'B'),
					COALESCE(asistencia_quincenal02.d20, 'B'),
					COALESCE(asistencia_quincenal02.d21, 'B'),
					COALESCE(asistencia_quincenal02.d22, 'B'),
					COALESCE(asistencia_quincenal02.d23, 'B'),
					COALESCE(asistencia_quincenal02.d24, 'B'),
					COALESCE(asistencia_quincenal02.d25, 'B'),
					COALESCE(asistencia_quincenal02.d26, 'B'),
					COALESCE(asistencia_quincenal02.d27, 'B'),
					COALESCE(asistencia_quincenal02.d28, 'B'),
					COALESCE(asistencia_quincenal02.d29, 'B'),
					COALESCE(asistencia_quincenal02.d30, 'B'),
					COALESCE(asistencia_quincenal02.d31, 'B')
				), ',', DATEDIFF('$fecha_H', '$fecha_D') + 1), ' ', '')), 'SI', 'NO') AS cumple_rotacion
				FROM  asistencia_quincenal02
				JOIN v_ficha ON asistencia_quincenal02.cod_ficha = v_ficha.cod_ficha
				JOIN (
				SELECT
				p.cod_ficha,
				p.posicion_inicio,
				p.cod_ubicacion,
				r.secuencia_turnos,
				-- Generar la secuencia repetida utilizando la secuencia ajustada
				TRIM(BOTH ',' FROM (
					SUBSTRING_INDEX(CONCAT(SUBSTRING_INDEX(
						REPEAT(
							CONCAT(
								TRIM(BOTH ',' FROM (
									CONCAT(
										SUBSTRING_INDEX(r.secuencia_turnos, ',', -((LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1) - (p.posicion_inicio + 1) + 1)),
										',',
										SUBSTRING_INDEX(r.secuencia_turnos, ',', (p.posicion_inicio + 1) - 1)
									)
								)),
								','
							), -- Repetir la secuencia ajustada separada por comas
							CEIL((DATEDIFF('$fecha_H', '$fec_mensual') + 1) / 
							(LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1)) -- Número de repeticiones necesarias
						),
						',',
									DATEDIFF('$fecha_H', '$fec_mensual') + 1  -- Longitud exacta en términos de opciones
					), ','),',',-(DATEDIFF( '$fecha_H', '$fecha_D' ) + 2 ))
				)) AS secuencia_repetida
			FROM
				planif_clientes_trab p
				JOIN (
					SELECT
						cod_rotacion,
						GROUP_CONCAT(c2.abrev ORDER BY rotacion_det.codigo ASC) AS secuencia_turnos
					FROM
						rotacion_det
						JOIN turno ON rotacion_det.cod_turno = turno.codigo
						JOIN horarios ON turno.cod_horario = horarios.codigo
						JOIN conceptos ON horarios.cod_concepto = conceptos.codigo
						JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
						JOIN conceptos c2 ON h2.cod_concepto = c2.codigo
					GROUP BY
						cod_rotacion
				) r ON p.cod_rotacion = r.cod_rotacion
			WHERE
				p.fecha_inicio = '$fec_mensual'
				) r ON asistencia_quincenal02.cod_ficha = r.cod_ficha
			$where02
		ORDER BY 1 ASC";
	echo $sql;
	echo "<table width='100%' border='0' align='center' class='tabla_sistema'>
	<tr><th>".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
			<th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
			<th> 16 </th><th> 17 </th><th> 18 </th><th> 19 </th>
			   <th> 20 </th><th> 21 </th><th> 22 </th><th> 23 </th>
			   <th> 24 </th><th> 25 </th><th> 26 </th><th> 27 </th>
			   <th> 28 </th><th> 29 </th><th> 30 </th><th> 31 </th></tr>";
	
	$query = $bd->consultar($sql);
	while ($datos = $bd->obtener_fila($query, 0)) {

		if ($datos['cumple_rotacion'] == 'SI') {
			echo '<tr>';
		} else {
			echo '<tr class="color fondo03">';	
		}
		
		echo '
		<td class="texto">' . $datos["cod_ficha"] . '</td>
		<td class="texto">' . $datos["cedula"] . '</td>
		<td class="texto">' . $datos["ap_nombre"] . '</td>
		<td class="texto">' . $datos["rol"] . '</td>
		<td class="texto">' . $datos["region"] . '</td>
		<td class="texto">' . $datos["estado"] . '</td>
		<td class="texto">' . $datos["ciudad"] . '</td>
		<td class="texto">' . $datos["contracto"] . '</td>
		<td class="texto">' . $datos["d16"] . '</td>
		<td class="texto">' . $datos["d17"] . '</td>
		<td class="texto">' . $datos["d18"] . '</td>
		<td class="texto">' . $datos["d19"] . '</td>
		<td class="texto">' . $datos["d20"] . '</td>
		<td class="texto">' . $datos["d21"] . '</td>
		<td class="texto">' . $datos["d22"] . '</td>
		<td class="texto">' . $datos["d23"] . '</td>
		<td class="texto">' . $datos["d24"] . '</td>
		<td class="texto">' . $datos["d25"] . '</td>
		<td class="texto">' . $datos["d26"] . '</td>
		<td class="texto">' . $datos["d27"] . '</td>
		<td class="texto">' . $datos["d28"] . '</td>
		<td class="texto">' . $datos["d29"] . '</td>
		<td class="texto">' . $datos["d30"] . '</td>
		<td class="texto">' . $datos["d31"] . '</td>
		</tr>';
	};
	echo '</table>';
}
?>
