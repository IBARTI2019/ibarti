<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();

$cliente    = $_POST['cliente'];
$ubicacion  = $_POST['ubicacion'];
$trabajador = $_POST['trabajador'];

$fecha_D   = conversion($_POST['fecha_desde']);
$fecha_H   = conversion($_POST['fecha_hasta']);
$where = " WHERE (planif_clientes_trab_det.asistencia = 'T' OR planif_clientes_trab_det.confirm = 'T' OR planif_clientes_trab_det.in_transport = 'T')
AND planif_clientes_trab_det.fecha BETWEEN \"$fecha_D\" AND \"$fecha_H\"
AND planif_clientes_trab_det.cod_cliente = clientes.codigo
AND planif_clientes_trab_det.cod_ubicacion = clientes_ubicacion.codigo
AND planif_clientes_trab_det.cod_ficha = v_ficha.cod_ficha";

if($trabajador != NULL){
	$where   .= " AND  v_ficha.cod_ficha = '$trabajador' ";
}

if($cliente  != "TODOS"){
	$where   .= " AND planif_clientes_trab_det.cod_cliente = '$cliente' ";
}

if($ubicacion != "TODOS"){
	$where   .= " AND planif_clientes_trab_det.cod_ubicacion = '$ubicacion' ";
}

$sql = "SELECT planif_clientes_trab_det.fecha,
clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
planif_clientes_trab_det.cod_ficha,
v_ficha.ap_nombre, turno.abrev turno, horarios.nombre  AS horario,
planif_clientes_trab_det.servicio_confirm,
TIME(planif_clientes_trab_det.fec_confirm) hora_confirm,
planif_clientes_trab_det.servicio_in_transport,
TIME(planif_clientes_trab_det.fec_in_transport) hora_transport,
TIME(planif_clientes_trab_det.fec_asistencia) hora_asistencia
FROM
planif_clientes_trab_det
INNER JOIN turno ON planif_clientes_trab_det.cod_turno = turno.codigo
INNER JOIN horarios ON turno.cod_horario = horarios.codigo ,
clientes ,
clientes_ubicacion ,
v_ficha
$where
ORDER BY 1, 2 ASC ";

?><table class="tabla" width="96%" border="0" align="center">
	<tr class="fondo00">
		<th width="8%" class="etiqueta">Fecha</th>
		<th width="5%" class="etiqueta"><?php echo $leng['ficha']?></th>
		<th width="12%" class="etiqueta"><?php echo $leng['trabajador']?></th>
		<th width="15%" class="etiqueta"><?php echo $leng['cliente']?></th>
		<th width="15%" class="etiqueta"><?php echo $leng['ubicacion']?></th>
		<th width="5%" class="etiqueta"><?php echo $leng['turno']?> </th>
		<th width="15%" class="etiqueta"><?php echo $leng['horario']?> </th>
		<th width="10%" class="etiqueta">Asisto </th>
		<th width="10%" class="etiqueta">En Transporte </th>
		<th width="5%" class="etiqueta">Asistencia </th>
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
		<td class="texto">'.$datos["fecha"].'</td>
		<td class="texto">'.$datos["cod_ficha"].'</td>
		<td class="texto">'.$datos["ap_nombre"].'</td>
		<td class="texto">'.$datos["cliente"].'</td>
		<td class="texto">'.$datos["ubicacion"].'</td>
		<td class="texto">'.$datos["turno"].'</td>
		<td class="texto">'.$datos["horario"].'</td>
		<td class="texto">'.$datos["servicio_confirm"].' '.$datos["hora_confirm"].'</td>
		<td class="texto">'.$datos["servicio_in_transport"].' '.$datos["hora_transport"].'</td>
		<td class="texto">'.$datos["hora_asistencia"].'</td>
	</tr>';
	};?>
</table>