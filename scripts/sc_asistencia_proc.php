<?php
define("SPECIALCONSTANT", true);
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../" . class_bdI);
$bd = new DataBase();

$tabla    = 'asistencia';

$apertura   = $_POST['apertura'];
$rol        = $_POST['rol'];
$contracto  = $_POST['contracto'];
$fec_diaria  = $_POST['fec_diaria'];

$href       = $_POST['href'];
$usuario    = $_POST['usuario'];
$proced     = $_POST['proced'];
$metodo     = $_POST['metodo'];
$mensaje    = "";
$i = $_POST['metodo'];

//  PROBLEMAS CON LA FECHA EN APERTURA DE FECHA

if (isset($_POST['metodo'])) {

	switch ($i) {

		case 'cerrar_as':
			$sql = "SELECT COUNT(trab_roles.cod_ficha) AS trabajadores
               FROM trab_roles , ficha , control
              WHERE trab_roles.cod_ficha = ficha.cod_ficha
                AND trab_roles.cod_rol = '$rol'
                AND ficha.cod_contracto = '$contracto'
                AND ficha.cod_ficha_status = control.ficha_activo
								AND '$fec_diaria' >= ficha.fec_ingreso";

			$query = $bd->consultar($sql);
			$row01 = $bd->obtener_fila($query, 0);
			$trab  = $row01[0];

			$sql = " SELECT COUNT(DISTINCT(asistencia.cod_ficha)) AS trab_reportados
		        FROM trab_roles,ficha, asistencia , asistencia_apertura, control
	           WHERE trab_roles.cod_rol = '$rol'
	             AND trab_roles.cod_ficha = ficha.cod_ficha
		         AND ficha.cod_contracto = '$contracto'
		         AND asistencia.cod_as_apertura = '$apertura'
		         AND asistencia.cod_as_apertura = asistencia_apertura.codigo
		         AND asistencia.cod_ficha = trab_roles.cod_ficha
				 AND asistencia.cod_concepto <> control.concepto_rep";

			$query   = $bd->consultar($sql);
			$row01   = $bd->obtener_fila($query, 0);
			$trab_as = $row01[0];

			$sql = " SELECT COUNT(DISTINCT(asistencia.cod_ficha)) AS concepto_rep
		        FROM trab_roles,ficha, asistencia , asistencia_apertura, control
	           WHERE trab_roles.cod_rol = '$rol'
	             AND trab_roles.cod_ficha = ficha.cod_ficha
		         AND ficha.cod_contracto = '$contracto'
		         AND asistencia.cod_as_apertura = '$apertura'
		         AND asistencia.cod_as_apertura = asistencia_apertura.codigo
		         AND asistencia.cod_ficha = trab_roles.cod_ficha
				 AND asistencia.cod_concepto = control.concepto_rep";

			$query   = $bd->consultar($sql);
			$row01   = $bd->obtener_fila($query, 0);
			$concepto_rep = $row01[0];

			if ($trab == $trab_as) {
				if ($concepto_rep == 0) {
					$sql    = "$SELECT $proced('$metodo', '$apertura', '$fec_diaria', '$rol', '$contracto', '$usuario')";
					$query = $bd->consultar($sql);
					$mensaje = "SE CERRO CORRECTAMENTE LA ASISTENCIA";

					// Temporalmente comentado para depurar
				
					error_log("Iniciando envío de webhooks para asistencias. Apertura: $apertura, Fecha: $fec_diaria");

					// Enviar webhook para cada asistencia registrada
					$sql_asistencias = "SELECT
									asistencia.cod_ficha,
									CONCAT(ficha.nombres, ' ', ficha.apellidos) AS trabajador,
									ficha.telefono,
									conceptos.descripcion turno,
									CONCAT('$fec_diaria', ' ', TIME(asistencia.fec_us_ing)) AS fechaguardia
								FROM
									asistencia
									INNER JOIN asistencia_apertura ON  asistencia.cod_as_apertura = asistencia_apertura.codigo AND asistencia_apertura.fec_diaria = '$fec_diaria'
									INNER JOIN ficha ON asistencia.cod_ficha = ficha.cod_ficha AND ficha.cod_contracto = '$contracto'
									INNER JOIN trab_roles ON ficha.cod_ficha = trab_roles.cod_ficha AND trab_roles.cod_rol = '$rol'
									INNER JOIN conceptos ON asistencia.cod_concepto = conceptos.codigo
								WHERE
									asistencia.cod_as_apertura = '$apertura';";

					error_log("SQL para asistencias: $sql_asistencias");
	/*
					$query_asistencias = $bd->consultar($sql_asistencias);
					if (!$query_asistencias) {
						error_log("Error en consulta de asistencias: " . $bd->error());
					} else {
						$count = 0;
						while ($asistencia = $bd->obtener_fila($query_asistencias, 0)) {
							$count++;
							error_log("Procesando asistencia $count: " . json_encode($asistencia));
							$payload = array(
								"fechaguardia" => $asistencia['fechaguardia'],
								"cod_ficha" => $asistencia['cod_ficha'],
								"trabajador" => $asistencia['trabajador'],
								"turno" => $asistencia['turno'],
								"telefono" => $asistencia['telefono']
							);
							$json_payload = json_encode($payload);
							error_log("Payload JSON: $json_payload");
							$url = 'http://212.56.33.4:5678/webhook/asistencia';
							try {
								$ch = curl_init($url);
								if (!$ch) {
									error_log("Error inicializando curl");
								} else {
									curl_setopt($ch, CURLOPT_POST, true);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout de 10 segundos
									$response = curl_exec($ch);
									if (curl_errno($ch)) {
										error_log("Error en curl: " . curl_error($ch));
									} else {
										error_log("Respuesta del webhook: $response");
									}
									curl_close($ch);
								}
							} catch (Exception $e) {
								error_log("Excepción en curl: " . $e->getMessage());
							}
						}
						error_log("Total asistencias procesadas: $count");
					}
					*/
				} else {
					$mensaje = "HAY CONCEPTOS DE REPLICAR EN LAS ASISTENCIA \n  ASISTENCIA NO CERRADA";
				}
			} else {
				$mensaje = "HAY DIFERENCIA EN LA ASISTENCIA ASISTENCIA. \n TRABAJADORES A REPORTAR: " . $trab . ", TRABAJADORES REPORTADOS " . $trab_as . " ";
			}
			echo '<input type="hidden" id="mensaje_aj" value="' . $mensaje . '"/>';
			break;

		case 'replicar':

			$sql    = "$SELECT $proced('$metodo', '$apertura', '$fec_diaria', '$rol', '$contracto', '$usuario')";
			$query = $bd->consultar($sql);

			break;
		case 'trab_reportar':

			$sql    = "SELECT ficha.cod_ficha, CONCAT(ficha.apellidos, ' ',ficha.nombres) AS trabajador,
                          IFNULL(asistencia.cod_ficha, 'NO') AS valor
                     FROM ficha LEFT JOIN asistencia ON asistencia.cod_as_apertura = '$apertura'
                      AND ficha.cod_ficha = asistencia.cod_ficha , control, trab_roles
					WHERE ficha.cod_ficha_status = control.ficha_activo
                      AND ficha.cod_contracto = '$contracto'
                      AND ficha.cod_ficha = trab_roles.cod_ficha
                      AND trab_roles.cod_rol = '$rol'
											AND '$fec_diaria' >= ficha.fec_ingreso
											AND IFNULL(asistencia.cod_ficha, 'NO') = 'NO'

					  UNION
			  SELECT ficha.cod_ficha, CONCAT(ficha.apellidos, '',ficha.nombres, ' ',' REPLICA') AS trabajador,
                     'NO' AS valor
                FROM ficha, asistencia,  control, trab_roles
               WHERE asistencia.cod_as_apertura = '$apertura'
                 AND ficha.cod_ficha = asistencia.cod_ficha
                 AND asistencia.cod_concepto = control.concepto_rep
                 AND ficha.cod_contracto = '$contracto'
                 AND ficha.cod_ficha = trab_roles.cod_ficha
                 AND trab_roles.cod_rol = '$rol' ";
			$query = $bd->consultar($sql);
			while ($row01 = $bd->obtener_fila($query, 0)) {
				$mensaje .= "ficha: " . $row01[0] . ", " . $row01[1] . " \n ";
			}
			echo '<input type="hidden" id="mensaje_aj" value="' . $mensaje . '"/>';

			break;
	}
}
require_once('../funciones/sc_direccionar.php');
