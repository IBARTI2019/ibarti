<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;

if(($_POST['fecha_desde'] == "")){
	exit;
}
	
$fecha_D         = conversion($_POST['fecha_desde']);

$quincena       = $_POST['quincena'];
$nomina          = $_POST['nomina'];
$rol             = $_POST['rol'];
$region          = $_POST['region'];
$estado          = $_POST['estado'];
$ciudad          = $_POST['ciudad'];

$fecha_N = explode("-", $fecha_D);
$year1   = $fecha_N[0];
$mes1    = $fecha_N[1];
$dia1    = $fecha_N[2];

$fecha_Inc_M  = mktime(0,0,0,$mes1,$dia1,$year1);
$fec_mensual = "".$year1."-".$mes1."-01";


$where01 = "WHERE asistencia_quincenal01.fec_mensual = '$fec_mensual'";

$where02 = "WHERE asistencia_quincenal02.fec_mensual = '$fec_mensual' ";

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

if ($quincena == "01"){

	$fecha_H = $year1.'-'.$mes1.'-15';
	$fecha_D = $year1.'-'.$mes1.'-01';

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
					CASE WHEN 
					CONCAT_WS(
						',',
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d01, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d02, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d03, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d04, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d05, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d06, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d07, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d08, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d09, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d10, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d11, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d12, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d13, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d14, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal01.d15, ' ', '') = c1.abrev
							LIMIT 1), 'B')
					) = r.secuencia_repetida THEN
							'SI'
						ELSE
							'NO'
					END AS cumple_rotacion
				FROM  asistencia_quincenal01
				JOIN v_ficha ON asistencia_quincenal01.cod_ficha = v_ficha.cod_ficha
				JOIN (
				SELECT
				p.cod_ficha,
				p.posicion_inicio,
				r.secuencia_turnos,
				-- Ajustar la secuencia para que comience desde posicion_inicio
				TRIM(BOTH ',' FROM (
					CONCAT(
						SUBSTRING_INDEX(r.secuencia_turnos, ',', -((LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1) - (p.posicion_inicio + 1) + 1)),
						',',
						SUBSTRING_INDEX(r.secuencia_turnos, ',', p.posicion_inicio - 1)
					)
				)) AS secuencia_ajustada,
				-- Generar la secuencia repetida utilizando la secuencia ajustada
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
							), -- Repetir la secuencia ajustada separada por comas
							CEIL((DATEDIFF('$fecha_H', '$fecha_D') + 1) / 
							(LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1)) -- Número de repeticiones necesarias
						),
						',',
									DATEDIFF('$fecha_H', '$fecha_D') + 1  -- Longitud exacta en términos de opciones
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
				p.fecha_inicio = '$fecha_D'
				) r ON asistencia_quincenal01.cod_ficha = r.cod_ficha
				$where01
			ORDER BY 1 ASC";


		echo "<table width='100%' border='0' align='center' class='tabla_planif'>
			<tr><th>".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
					<th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
					<th> 01 </th><th> 02 </th><th> 03 </th><th> 04 </th>
					<th> 05 </th><th> 06 </th><th> 07 </th><th> 08 </th>
					<th> 09 </th><th> 10 </th><th> 11 </th><th> 12 </th>
					<th> 13 </th><th> 14 </th><th> 15 </th></tr>";
					

			$query = $bd->consultar($sql);
			while ($datos = $bd->obtener_fila($query, 0)) {
				$signo = "";
				if ($datos["cumple_rotacion"] == "NO") {
				echo '<tr class="color fondo03">';
				} else {
					echo '<tr>';
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
				CASE WHEN 
					CONCAT_WS(
						',',
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d16, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d17, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d18, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d19, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d20, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d21, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d22, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d23, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d24, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d25, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d26, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d27, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d28, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d29, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d30, ' ', '') = c1.abrev
							LIMIT 1), 'B'),
						COALESCE(
							(SELECT c_final.abrev
							FROM conceptos c1
							JOIN horarios h1 ON c1.cod_horario = h1.codigo
							JOIN conceptos c_final ON h1.cod_concepto = c_final.codigo
							WHERE REPLACE(asistencia_quincenal02.d31, ' ', '') = c1.abrev
							LIMIT 1), 'B')
					) = r.secuencia_repetida THEN
							'SI'
						ELSE
							'NO'
					END AS cumple_rotacion
				FROM  asistencia_quincenal02
				JOIN v_ficha ON asistencia_quincenal02.cod_ficha = v_ficha.cod_ficha
				JOIN (
				SELECT
				p.cod_ficha,
				p.posicion_inicio,
				r.secuencia_turnos,
				-- Ajustar la secuencia para que comience desde posicion_inicio
				TRIM(BOTH ',' FROM (
					CONCAT(
						SUBSTRING_INDEX(r.secuencia_turnos, ',', -((LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1) - (p.posicion_inicio + 1) + 1)),
						',',
						SUBSTRING_INDEX(r.secuencia_turnos, ',', p.posicion_inicio - 1)
					)
				)) AS secuencia_ajustada,
				-- Generar la secuencia repetida utilizando la secuencia ajustada
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
							), -- Repetir la secuencia ajustada separada por comas
							CEIL((DATEDIFF('$fecha_H', '$fecha_D') + 1) / 
							(LENGTH(r.secuencia_turnos) - LENGTH(REPLACE(r.secuencia_turnos, ',', '')) + 1)) -- Número de repeticiones necesarias
						),
						',',
									DATEDIFF('$fecha_H', '$fecha_D') + 1  -- Longitud exacta en términos de opciones
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
				p.fecha_inicio = '$fecha_D'
				) r ON asistencia_quincenal02.cod_ficha = r.cod_ficha
			$where02
		ORDER BY 1 ASC";

	echo "<table width='100%' border='0' align='center' class='tabla_planif'>
	<tr><th>".$leng['ficha']." </th><th> ".$leng['ci']."  </th><th> Nombres  </th><th> ".$leng['rol']."  </th>
			<th> ".$leng['region']." </th><th> ".$leng['estado']."  </th><th> ".$leng['ciudad']."  </th><th> Nómina  </th>
			<th> 16 </th><th> 17 </th><th> 18 </th><th> 19 </th>
			   <th> 20 </th><th> 21 </th><th> 22 </th><th> 23 </th>
			   <th> 24 </th><th> 25 </th><th> 26 </th><th> 27 </th>
			   <th> 28 </th><th> 29 </th><th> 30 </th><th> 31 </th></tr>";
	
	$query = $bd->consultar($sql);
	while ($datos = $bd->obtener_fila($query, 0)) {
		$signo = "";
		if ($datos["cumple_rotacion"] == "NO") {
		echo '<tr class="color fondo03">';
		} else {
			echo '<tr>';
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
