<?php
function verIP(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function get_client_ip() {
	$ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}

function Select($valor,$valido){ 
	if (($valor == $valido)){ 
		$result = 'selected'; 
	}else{ 
		$result = ''; 
	} 
	return $result; 
} 

function devuelveArrayFechasEntreOtrasDos($fechaInicio, $fechaFin)
{
	$arrayFechas=array();
	$fechaMostrar = $fechaInicio;

	while(strtotime($fechaMostrar) <= strtotime($fechaFin)) {
		$arrayFechas[]=$fechaMostrar;
		$fechaMostrar = date("Y-m-d", strtotime($fechaMostrar . " + 1 day"));
	}

	return $arrayFechas;
}  

function mensajeria($mensaje) {
	echo'<script language="javascript">
	alert("'.$mensaje.'");
	</script>';
}

function conversion($fecha, $allow_blanck = false){
	if($fecha!=''){
		if($fecha == 'DD-MM-AAAA'){
			$fecha='0000-00-00';
		}else{
			$fecha_N1 = explode("-", $fecha);
			$a   = $fecha_N1[0];
			$m   = $fecha_N1[1];
			$d   = $fecha_N1[2];

			if(($a=='0000') or ($m=="") or ($d=="")){
				$fecha='';
			}else{
				$fecha=$d."-".$m."-".$a;
			}
		}
	}else{
		if($allow_blanck){
			return $fecha;
		}else{
			$fecha='0000-00-00';
		}
	}
	
	return $fecha;
}

function Rconversion($fecha){
	$fecha_N1 = explode("-", $fecha);
	$d   = $fecha_N1[0];
	$m   = $fecha_N1[1];
	$a   = $fecha_N1[2];

	if(($a=='0000') or ($m=="") or ($d=="")){
		$fecha='';
	}else{
		$fecha=$a."-".$m."-".$d;
	}
	return $d;
}

function Redirec($pagina){
	echo '<script languaje="JavaScript" type="text/javascript">
	location.href="'.$pagina.'";
	</script>';
}

function fsalida($cad2){
	$tres=substr($cad2, 0, 4);
	$dos=substr($cad2, 5, 2);
	$uno=substr($cad2, 8, 2);
	$cad = ($uno."/".$dos."/".$tres);
	return $cad;
}
// CALCULO DE LA EDAD
function fnacimient($fecha)
{
	$dia=date(j);
	$mes=date(n);
	$ano=date(Y);

	$dia_nac = substr($fecha, 8, 2);
	$mes_nac = substr($fecha, 5, 2);
	$anonac = substr($fecha, 0, 4);

	if ( $anonac==0000 or $mes_nac == 0 or $dia_nac == 0 ){
		return $edad = 'INDEFINIDO';
	}else{

		if (($mes_nac == $mes) && ($dia_nac > $dia)) {
			$ano=($ano-1); }

			if ($mesnaz > $mes) {
				$ano=($ano-1);}

				$edad=($ano-$anonac);
				return $edad;
			}
		}

// calcular el valor real de S o N  ==> SI � NO
		function valorF($valor){

			if ( ($valor == 'S') or ($valor == 's') ) {
				$valorS = 'SI';

			}elseif (($valor == 'N') or ($valor == 'n')){
				$valorS = 'NO';

			}elseif (($valor == 'T') or ($valor == 'T')){
				$valorS = 'SI';

			}elseif (($valor == 'F') or ($valor == 'f')){
				$valorS = 'NO';

			}else{
				$valorS ='INDEFINIDO';
			}

			return $valorS;
		}

// calcular el valor real de S o N  ==> SI � NO
		function valorS($valor){

			if ( ($valor == 'S') or ($valor == 's') ) {
				$valorS = 'SI';

			}elseif (($valor == 'N') or ($valor == 'n')){
				$valorS = 'NO';

			}elseif (($valor == 'T') or ($valor == 'T')){
				$valorS = 'SI';

			}elseif (($valor == 'F') or ($valor == 'f')){
				$valorS = 'NO';

			}else{
				$valorS ='INDEFINIDO';
			}

			return $valorS;
		}

// calcular el status  de 1 o 0  ==> Activo  � Inactivo
// AL CAMBIAR DEFINICION TAMBIEN AL ARCHIVO fr_hospital_cama_mantenimiento
function statusrfid($valor){

	if ( ($valor == 'T') or ($valor == 't') ) {
		$status = 'Si';

	}elseif (($valor == 'F') or ($valor == 'f')){
		$status = 'No';
	}else{
		$status ='INDEFINIDO';
	}
	return $status;
}
		function statuscal($valor){

			if ( ($valor == 'T') or ($valor == 't') ) {
				$status = 'ACTIVO';

			}elseif (($valor == 'F') or ($valor == 'f')){
				$status = 'INACTIVO';
			}else{
				$status ='INDEFINIDO';
			}
			return $status;
		}
		function statusbd($valor){

			if ( ($valor == 'T') or ($valor == 't') ) {
				$status = 'T';
			}else{
				$status ='F';
			}
			return $status;
		}

		function statusCheck($valor){

			if (($valor == 'T') or ($valor == 't')) {
				$result = 'checked="checked"';

			}else{
				$result = '';
			}
			return $result;
		}

// calcular el valor real de S o N  ==> SI � NO
		function Nacion($valor){

			if ( ($valor == 'V') or ($valor == 'v') ) {
				$valorcal = 'VENEZOLANO';

			}elseif (($valor == 'E') or ($valor == 'e')){
				$valorcal = 'EXTRANJERO';
			}else{
				$valorcal ='DESCONOCIDO';
			}

			return $valorcal;
		}

// calcular el status  de 1 o 0  ==> Activo  � Inactivo
// AL CAMBIAR DEFINICION TAMBIEN AL ARCHIVO fr_hospital_cama_mantenimiento
		function Disponibilidad($valor){

			if ( ($valor == 'O')  ) {
				$Disponibilidad = 'Ocupado';

			}elseif (($valor == 'D') ){
				$Disponibilidad = 'Disponible';
			}else{
				$Disponibilidad ='Indefinido';
			}

			return $Disponibilidad;
		}

		function CheckX($valor, $valido){

			if (($valor == $valido)){
				$result = 'checked="checked"';
			}else{
				$result = '';
			}
			return $result;
		}

		function CheckUso($valor, $valido){

			if (($valor == 'NUM')&&($valido == 'NUM')){
				$result = 'checked="checked"';

			}elseif (($valor == 'CARAC')&&($valido == 'CARAC')){
				$result = 'checked="checked"';

			}elseif (($valor == 'FEC')&&($valido == 'FEC')){
				$result = 'checked="checked"';

			}else{
				$result = '';
			}
			return $result;
		}

		function Chequepolc($valor){

			if ( ($valor == 'S')  ) {
				$Disponibilidad = 'Apto';

			}elseif (($valor == 'N') ){
				$Disponibilidad = 'No Apto';
			}else{
				$Disponibilidad ='Indefinido';
			}
			return $Disponibilidad;
		}

		function valorN($valor){

			if ( ($valor == 'S') or ($valor == 'S') ) {
				$valor = 'S';

			}else{
				$valor = 'N';
			}
			return $valor;
		}



		function Asistencia_orden($valor){

			if ( $valor == '`asistencia`.`cod_ficha`' ) {
				$result = 'Ficha';

			}elseif ($valor == '`ficha`.`cedula`' ){
				$result = 'Cedula';
			}elseif ($valor == 'trabajador'){
				$result = 'Trabajador';

			}elseif ($valor == 'cliente'){
				$result = 'Cliente';


			}elseif ($valor == 'ubicacion'){
				$result = 'Ubicacion';

			}else{
				$result = 'Indefinido';
			}

			return $result;
		}

		function restaFechas($fecha1, $fecha2)
		{
			$fecha_N1 = explode("-", $fecha1);
			$year1   = $fecha_N1[0];
			$mes1    = $fecha_N1[1];
			$dia1    = $fecha_N1[2];

			$fecha_N2 = explode("-", $fecha2);
			$year2   = $fecha_N2[0];
			$mes2    = $fecha_N2[1];
			$dia2    = $fecha_N2[2];

			$date1 = mktime(0,0,0,$mes1,$dia1,$year1);
			$date2 = mktime(0,0,0,$mes2,$dia2,$year2);

			return round(($date2 - $date1) / (60 * 60 * 24));
		}

		$MOver  = "this.id ,'A',  'button1Act', 'button1'";
		$MOut   = "this.id ,'D', 'button1Act', 'button1'";


		date_default_timezone_set("America/La_Paz");
		$yeary = date("Y"); $mesm = date("m"); $diad = date("d");
		$date = $yeary.'-'.$mesm.'-'.$diad;
		$fecha = $diad.'-'.$mesm.'-'.$yeary;
		$date_time = date("Y-m-d H:i:s");

		function Redondear2d($valor) {
			$float_redondeado=round($valor * 100) / 100;
			return $float_redondeado;
		}

		function Dec2($valor) {
			$result =  number_format($valor, 2, '.', '');
			return $result;
		}

// DATOS MAXIMO A MOTRAR
		function longitudMin($campo){
			$log = substr($campo, 0, 16);
			return $log;
		}

		function longitud($campo){
			$log = substr($campo, 0, 30);
			return $log;
		}

		function longitudMax($campo){
			$log = substr($campo, 0, 42);
			return $log;
		}

		function calculate_time_past($start_time, $end_time, $format = "s") {
			$time_span = strtotime($end_time) - strtotime($start_time);
    if ($format == "s") { // is default format so dynamically calculate date format
    	if ($time_span > 60) { $format = "i:s"; }
    	if ($time_span > 3600) { $format = "H:i:s"; }
    }
    return gmdate($format, $time_span);
}

function Feriado_as($valor, $tipo){
	if ( ($valor == '1') && ($tipo == "FER") ) {
		$resul = ' ,(FER) ';

	}elseif (($valor == '1') && ($tipo == 'NL')){
		$resul = ' ,(NL) ';

	}else{
		$resul ='';
	}
	return $resul;
}

// Estilos "scoped" para la pantalla de Asistencia (toolbar/resumen/tabla).
// Todo va prefijado bajo .asistencia-toolbar/.asistencia-resumen/.asistencia-tabla-wrap
// para no pisar clases globales (.fondo00/01/02, .etiqueta, .texto, .art-button, etc.)
// que usan las demás pantallas del sistema.
function AsistenciaGridCSS(){
	return '<style>
  .asistencia-toolbar { background-color: #EAFFEA; border-radius: 8px; padding: 10px 12px; margin-bottom: 10px; }
  .asistencia-resumen { border: 1px solid #cfe8cf; border-radius: 8px; padding: 8px 12px; margin-bottom: 10px; }
  .asistencia-resumen span { margin-right: 10px; }
  .asistencia-tabla-wrap { overflow-x: auto; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.15); }
  .asistencia-tabla-wrap table { width: 100%; }
  .asistencia-tabla-wrap td, .asistencia-tabla-wrap th { padding: 6px 8px; font-size: 11px; }
  .asistencia-tabla-wrap tr.fondo00 th { position: sticky; top: 0; z-index: 1; }
  .asistencia-tabla-wrap table tr:hover { background-color: yellow; }
  .asistencia-tabla-wrap .imgLink img { width: 26px; height: 26px; padding: 3px; }
  .asistencia-tabla-wrap .imgLink img:hover { background-color: rgba(0,0,0,0.08); }
</style>';
}

function PropuestaBadgeCSS(){
	return '<style>
  .badge-auto { background-color: #dcfce7; color: #1a7f37; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 11px; white-space: nowrap; }
  .badge-revisar { background-color: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 11px; white-space: nowrap; }
  .badge-alerta { background-color: #fee2e2; color: #b91c1c; padding: 3px 8px; border-radius: 10px; font-weight: bold; font-size: 11px; white-space: nowrap; }
  .badge-obs { display: block; margin-top: 3px; font-size: 10px; color: #5a6472; white-space: normal; word-break: break-word; overflow-wrap: break-word; max-width: 270px; }
</style>';
}

function PropuestaBadgeHTML($modo, $obs){
	$modo_txt = !empty($modo) ? $modo : 'AUTO';
	$badge_class = 'badge-auto';
	$modo_display = $modo_txt;
	if ($modo_txt == 'REVISAR') $badge_class = 'badge-revisar';
	if ($modo_txt == 'ALERTA')  $badge_class = 'badge-alerta';
	if ($modo_txt == 'AUTO')    $modo_display = 'OK';

	$obs_txt = !empty($obs) ? '<span class="badge-obs">'.htmlspecialchars($obs).'</span>' : '';

	return '<span class="'.$badge_class.'">'.htmlspecialchars($modo_display).'</span>'.$obs_txt;
}

function PropuestaResumenHTML($bd, $sql_base){
	$conteos = array('AUTO' => 0, 'REVISAR' => 0, 'ALERTA' => 0);

	$sql_resumen = "SELECT prop_modo, COUNT(*) AS total FROM (" . $sql_base . ") x GROUP BY prop_modo";
	$query = $bd->consultar($sql_resumen);
	while ($row = $bd->obtener_fila($query, 0)) {
		$modo = !empty($row['prop_modo']) ? $row['prop_modo'] : 'AUTO';
		if (isset($conteos[$modo])) {
			$conteos[$modo] = (int) $row['total'];
		}
	}

	$html  = '<div id="resumen_estado" style="margin:8px 0;">';
	$html .= '<span class="badge-auto">'    . $conteos['AUTO']    . ' OK</span>&nbsp;&nbsp;';
	$html .= '<span class="badge-revisar">' . $conteos['REVISAR'] . ' REVISAR</span>&nbsp;&nbsp;';
	$html .= '<span class="badge-alerta">'  . $conteos['ALERTA']  . ' ALERTA</span>';
	$html .= '</div>';

	return $html;
}

// Query compartida entre la carga inicial (Cons_asistencia_det.php) y el
// refresco AJAX (ajax/Add_asistencia_det.php): trabajadores con asistencia ya
// registrada para la apertura, UNION trabajadores con turno planificado ese
// día que todavía no tienen fila en asistencia (badge REVISAR, concepto real
// resuelto vía turno->horario->concepto).
function SQL_AsistenciaDet($cod_apertura, $fec_diaria, $co_cont, $cod_rol, $orden){
	return "SELECT
asistencia.cod_ficha,
ficha.cedula,
CONCAT( ficha.apellidos, ' ', ficha.nombres ) trabajador,
asistencia.cod_cliente,
clientes.nombre cliente,
asistencia.cod_ubicacion,
clientes_ubicacion.descripcion ubicacion,
asistencia.cod_concepto,
conceptos.descripcion concepto,
IF
( ISNULL( asistencia_clasif.descripcion ), '9999', asistencia.cod_asistencia_clasif ) cod_asistencia_clasif,
IF
( ISNULL( asistencia_clasif.descripcion ), 'N/A', asistencia_clasif.descripcion ) asistencia_clasif,
conceptos.abrev,
asistencia.hora_extra hora_extra_d,
asistencia.hora_extra_n,
asistencia.vale,
asistencia.feriado,
asistencia.no_laboral AS NL,
IFNULL(asistencia.prop_modo, 'AUTO') AS prop_modo,
IFNULL(asistencia.prop_observacion, '') AS prop_observacion
FROM
asistencia
LEFT JOIN asistencia_clasif ON asistencia_clasif.codigo = asistencia.cod_asistencia_clasif,
ficha,
trab_roles,
clientes,
clientes_ubicacion,
conceptos
WHERE
asistencia.cod_as_apertura = '$cod_apertura'
AND asistencia.cod_ficha = ficha.cod_ficha
AND ficha.cod_ficha = trab_roles.cod_ficha
AND asistencia.cod_cliente = clientes.codigo
AND asistencia.cod_ubicacion = clientes_ubicacion.codigo
AND asistencia.cod_concepto = conceptos.codigo
AND trab_roles.cod_rol = '$cod_rol' AND '$fec_diaria' >= ficha.fec_ingreso UNION
SELECT
pctd.cod_ficha,
f.cedula,
CONCAT( f.apellidos, ' ', f.nombres ) trabajador,
pctd.cod_cliente,
c.nombre cliente,
pctd.cod_ubicacion,
cu.descripcion ubicacion,
cc.codigo cod_concepto,
cc.descripcion concepto,
'9999' cod_asistencia_clasif,
'N/A' asistencia_clasif,
cc.abrev abrev,
0 hora_extra_d,
0 hora_extra_n,
0 vale,
0 feriado,
0 NL,
'REVISAR' AS prop_modo,
'Turno planificado, aún no registrado en asistencia.' AS prop_observacion
FROM
planif_clientes_trab_det pctd,
ficha f,
trab_roles,
clientes c,
clientes_ubicacion cu,
turno t,
horarios h,
conceptos cc,
control
WHERE
pctd.fecha = '$fec_diaria'
AND pctd.cod_ficha = f.cod_ficha
AND pctd.cod_cliente = c.codigo
AND pctd.cod_ubicacion = cu.codigo
AND pctd.cod_turno = t.codigo
AND t.cod_horario = h.codigo
AND h.cod_concepto = cc.codigo
AND f.cod_contracto =  '$co_cont'
AND f.cod_ficha_status = control.ficha_activo
AND f.cod_ficha = trab_roles.cod_ficha
AND trab_roles.cod_rol = '$cod_rol'
AND pctd.cod_ficha NOT IN ( SELECT cod_ficha FROM asistencia WHERE asistencia.cod_as_apertura = '$cod_apertura' )
ORDER BY FIELD(prop_modo, 'ALERTA', 'REVISAR', 'AUTO'), $orden ASC";
}

function imgExtension($link){
	$ext =  end(explode(".", $link));

	switch ($ext) {
		case 'png': case 'jpg': case 'jpeg': case 'gif':
		$img_ext = $link;
		break;
		case 'pdf':
		$img_ext = "imagenes/pdf.gif";
		break;

		case 'doc': case 'docx':
		$img_ext = "imagenes/word.gif";
		break;

		case 'ppt':
		$img_ext = "imagenes/powerpoint.png";
		break;

		case 'xls':
		$img_ext = "imagenes/excel.gif";
		break;
		default:
		$img_ext = $link;
		break;
	}
	return $img_ext;

}
// listar Select In
function MatrizListar($valorX) {
	$listar = "";
	$cant = 0;
	foreach ($valorX as  $valor) {
		if($cant == 0){
			$listar .= "'".$valor."' ";
			$cant++;
		}else{

			$listar .= " ,'".$valor."' ";
		}
	}

	return $listar;
}

function MatrizListar2($valorX) {
	$listar = "";
	$cant = 0;
	foreach ($valorX as  $valor) {
		if($cant == 0){
			$listar .= "(".$valor.")";
			$cant++;
		}else{

			$listar .= " (".$valor.")";
		}
	}

	return $listar;
}

// rango de fecha y hora
//  $start_date ="2017-05-31 05:30:00";
//  $end_date = "2017-05-31 06:30:00";
//  $buscar_date = "2017-05-31 06:21:00";
//  check_range($start_date, $end_date, $buscar_date);

function check_range($start_date, $end_date, $buscar_date)
{
    // Convert to timestamp
	$start_ts = strtotime($start_date);
	$end_ts = strtotime($end_date);
	$buscar_ts = strtotime($buscar_date);
    /*
    if (($buscar_ts >= $start_ts) && ($buscar_ts <= $end_ts)){
  	$result = 'true';

  	}else{
  		$result = 'false';
  	}
  return $result;
   */
  return (($buscar_ts >= $start_ts) && ($buscar_ts <= $end_ts));
}


function Semana($valor, $tipo){
    // $tipo C = Corta, o Larga
	if(($tipo == "C") || ($tipo == "c")) {

		switch ($valor) {
			case 0:
			$res = "Dom";
			break;
			case 1:
			$res = "Lun";
			break;
			case 2:
			$res = "Mar";
			break;
			case 3:
			$res = "Mie";
			break;
			case 4:
			$res = "Jue";
			break;
			case 5:
			$res = "Vie";
			break;
			case 6:
			$res = "Sab";
			break;
			default:
			$res = "Error En Dia de Semana";
			break;
		}

	}elseif(($tipo == "L" ) || ($tipo == "l")) {
		switch ($valor) {
			case 0:
			$res = "Domingo";
			break;
			case 1:
			$res = "Lunes";
			break;
			case 2:
			$res = "Martes";
			break;
			case 3:
			$res = "Miecoles";
			break;
			case 4:
			$res = "Jueves";
			break;
			case 5:
			$res = "Viernes";
			break;
			case 6:
			$res = "Sabado";
			break;
			default:
			$res = "Error En Dia de Semana";
			break;
		}

	}else{
		$res = "Error en Valor Tipo De Semana";

	}

	return $res;
}

function fondo_cal($cli, $trab){
	if ($cli > $trab) {
		$bg = "b_mas";
	}elseif($cli < $trab) {
		$bg = "b_menos";
	}else{
		$bg = "";
	}
	return $bg;
}

function fondo_diff($valor){
	if ($valor > 0) {
		$bg = "#4CAF50";
	}elseif($valor < 0) {
		$bg = "#ef5350";
	}else{
		$bg = "#EAFFEA";
	}
	return $bg;
}
?>
