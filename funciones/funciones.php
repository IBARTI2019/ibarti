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

function conversion($fecha){
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
		$fecha='0000-00-00';
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

			if ( $valor == '`asisntecia`.`cod_ficha`' ) {
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
