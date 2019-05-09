<?php
/* ********************* */
/* Conexion a PostgreSQL Y MYSQL */
/* ********************* */

define("Sitio", "http://c122.gconex.com");
//	define("Carpeta", "asistencia");
//	define("Sitio", "http://c122.gconex.com");
define("Carpeta", "ib_oesvica");
define("Leng", "autentificacion/leng/index.php");
define("Funcion", "funciones/funciones.php");
//	define("Time_Sistema", 600);
define("Time_Sistema", 1200);
//	$x_time = Time_Sistema - 60;
$x_time = Time_Sistema - 20;
define("Time_Alerta", $x_time);
//	define("Time_Sistema", 600);

define('host','localhost');
define('user','root');
//	define('pass','yth86smg9ual1fcj');
define('pass','');
define('database','ib2_oesvica');
//define('port','5432');
define('port','3306');
define('db_charset','utf8');

define('LogoIbarti','dompdf/img/logo.jpg');
//Logo de Cliente para formato de novedades checklist debe tener una dimencion minima de 500x100
define('LogoCliente','dompdf/img/cliente.png');

define('PlantillaDOM','dompdf/plantillas');
define('ConfigDomPdf','dompdf/dompdf_config.inc.php');
define('pagDomPdf','dompdf/pag');
define('cssDomPdf','dompdf/style.css');
define('Foto','imagenes/fotos');


$bd = "mysql";
if($bd == "mysql"){
	define("class_bd", "bd/class_mysql.php");
	define("class_bdI", "bd/class_mysqlI.php");	

	$SELECT = "CALL";
}elseif($bd == "postgre"){	

	define("class_bd", "bd/class_postgresql.php");
	$SELECT = "SELECT ";	
/*
	$conf_cnn = "dbname=$bd_cnn user=$usuario host=$host password=$password port=$port";
	$cnn = pg_connect($conf_cnn)or die ('NO SE PUDO CONECTAR CON LA BASE DE DATOS POSTGRE:'.pg_last_error());
*/
}else{
/*
	define("class_bd", "bd/class_mysql.php");
	echo "Bases de Datos Indefinida";	
*/
}?>