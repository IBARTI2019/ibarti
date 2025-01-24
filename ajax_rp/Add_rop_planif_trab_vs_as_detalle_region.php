<?php
define("SPECIALCONSTANT",true);
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require_once "../".class_bdI;
require_once "../".Leng;
$bd = new DataBase();

$fecha         = $_POST['fecha'];
$region             = $_POST['region'];
$horario          = $_POST['horario'];

$WHERE1 = " WHERE asistencia_apertura.codigo = v_asistencia.cod_as_apertura AND asistencia_apertura.fec_diaria = '$fecha' 
AND v_asistencia.cod_region = '$region' AND v_asistencia.cod_concepto = conceptos.codigo 
AND conceptos.cod_horario = horarios.codigo AND horarios.codigo = '$horario' 
AND a.cod_ficha = v_asistencia.cod_ficha ";

$WHERE2 = " WHERE cl.cod_region = '$region' AND a.fecha = '$fecha' AND h2.codigo = '$horario' ";


$WHERE3 = " WHERE asistencia_apertura.fec_diaria = '$fecha' AND asistencia_apertura.codigo = v_asistencia.cod_as_apertura 
AND v_asistencia.cod_concepto = conceptos.codigo AND conceptos.cod_horario = horarios.codigo AND horarios.codigo = '$horario' 
AND v_asistencia.cod_ficha = ficha.cod_ficha AND v_asistencia.cod_region = '$region' ";


$WHERE4 = " WHERE cl.cod_region = '$region' AND a.fecha = '$fecha' AND h2.codigo = '$horario' ";

if(isset($_POST['r_cliente'])){
	$r_cliente	    = $_POST['r_cliente'];
	$usuario	    = $_POST['usuario'];
	if($r_cliente  == "T"){
		$WHERE1  .= " AND v_asistencia.cod_ubicacion IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
		$WHERE2  .= " AND a.cod_ubicacion  IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
		$WHERE3  .= " AND v_asistencia.cod_ubicacion  IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
		$WHERE4  .= " AND a.cod_ubicacion  IN (SELECT cod_ubicacion FROM usuario_clientes WHERE
		cod_usuario = '$usuario') ";
	}
}


// QUERY A MOSTRAR //
$sql = "SELECT a.cod_ficha, ficha.cedula, CONCAT(ficha.apellidos,' ',ficha.nombres) trabajador, conceptos.abrev concepto, 
(SELECT v_asistencia.abrev FROM asistencia_apertura , v_asistencia,conceptos,horarios
 $WHERE1 ) concepto_asistencia 
FROM planif_clientes_trab_det AS a INNER JOIN ficha ON a.cod_ficha = ficha.cod_ficha 
INNER JOIN clientes cl ON a.cod_cliente = cl.codigo
INNER JOIN clientes_ubicacion cu ON a.cod_ubicacion = cu.codigo
INNER JOIN estados ON cu.cod_estado = estados.codigo
INNER JOIN turno t ON a.cod_turno = t.codigo
LEFT JOIN horarios h ON t.cod_horario = h.codigo
LEFT JOIN conceptos ON h.cod_concepto = conceptos.codigo
INNER JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
$WHERE2
GROUP BY a.cod_ubicacion,a.cod_ficha,a.cod_turno,a.fecha 
UNION ALL
SELECT v_asistencia.cod_ficha, ficha.cedula, CONCAT(ficha.apellidos,' ',ficha.nombres) AS trabajador, '' concepto,
 v_asistencia.abrev concepto_asistencia
 FROM asistencia_apertura , v_asistencia, ficha,conceptos,horarios 
$WHERE3 AND v_asistencia.cod_ficha NOT IN (SELECT a.cod_ficha FROM planif_clientes_trab_det AS a 
INNER JOIN clientes cl ON a.cod_cliente = cl.codigo
INNER JOIN clientes_ubicacion cu ON a.cod_ubicacion = cu.codigo
INNER JOIN estados ON cu.cod_estado = estados.codigo
INNER JOIN turno t ON a.cod_turno = t.codigo
LEFT JOIN horarios h ON t.cod_horario = h.codigo
LEFT JOIN conceptos ON h.cod_concepto = conceptos.codigo
INNER JOIN horarios h2 ON conceptos.cod_horario = h2.codigo
$WHERE4) 
ORDER BY 1 ASC ";

echo $sql;
	?>
	<br>
	<table width="100%" border="0" align="center">
		<tr class="fondo00">
			<th width="15%" class="etiqueta"><?php echo $leng['ficha']?></th>
			<th width="15%" class="etiqueta"><?php echo $leng['ci']?></th>
			<th width="30%" class="etiqueta"><?php echo $leng['trabajador']?></th>
			<th width="10%" class="etiqueta">Planificacion</th>
			<th width="10%" class="etiqueta">Asistencia</th>
		</tr>
		<?php
		$valor = 0;
		$query = $bd->consultar($sql);

		while ($datos=$bd->obtener_fila($query,0)){
			if ($valor == 0){
				$fondo = 'fondo01';
				$valor = 1;
			}else{
				$fondo = 'fondo02';
				$valor = 0;
			}
			echo '<tr class="'.$fondo.'">
			<td class="texto" id="center">'.$datos["cod_ficha"].'</td>
			<td class="texto" id="center">'.$datos["cedula"].'</td>
			<td class="texto" id="center">'.$datos["trabajador"].'</td>
			<td class="texto" id="center">'.$datos["concepto"].'</td>
			<td class="texto" id="center">'.$datos["concepto_asistencia"].'</td>
			</tr>';
		};?>
	</table>
	<br>