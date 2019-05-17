<?php
/**
 * Insertar una nueva asistencia en la base de datos
 */

require 'Asistencia.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decodificando formato Json
    $body = json_decode(file_get_contents("php://input"), true);
    // Insertar asistencia diaria
    $retorno = Asistencia::insert(
        $body['cod_ficha'],
        $body['cod_cliente'],
        $body['fec_us_ing'],
        $body['cod_concepto'],
        $body['hora_extra'],
        $body['hora_extra_n'],
        $body['vale'],
        $body['cod_ubicacion'],
        $body['cod_us_ing']);

    if ($retorno) {
        // C�digo de �xito
        print json_encode(
            array(
                'estado' => '1',
                'mensaje' => 'Creaci�n �xitosa')
        );
    } else {
        // C�digo de falla
        print json_encode(
            array(
                'estado' => '2',
                'mensaje' => 'Creaci�n fallida')
        );
    }
}

