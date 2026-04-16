<?php
require("../../../../autentificacion/aut_config.inc.php");
require("../../../../libs/PHPMailer/enviar.php");
require_once("../../../../" . class_bd);

$bd = new DataBase();

$result = array();
$result['error'] = false;
$result['mensaje'] = '';

// Obtener datos del POST
$link = isset($_POST["link"]) ? $_POST["link"] : '';
$ubicacion = isset($_POST["ubicacion"]) ? $_POST["ubicacion"] : '';
$usuario = isset($_POST["usuario"]) ? $_POST["usuario"] : '';
$cod_ficha = isset($_POST["cod_ficha"]) ? $_POST["cod_ficha"] : '';
$cod_cliente = isset($_POST["cod_cliente"]) ? $_POST["cod_cliente"] : '';
$cod_ubicacion = isset($_POST["cod_ubicacion"]) ? $_POST["cod_ubicacion"] : '';

// Iniciar buffer para capturar la salida de enviar_mail_html
ob_start();

try {
    if (empty($ubicacion)) {
        $result['error'] = true;
        $result['mensaje'] = 'No se especificó la ubicación';
        echo json_encode($result);
        exit;
    }
    
    // Obtener datos del trabajador
    $sqlTrabajador = "SELECT CONCAT(apellidos, ' ', nombres) as nombre FROM v_ficha WHERE cod_ficha = '$cod_ficha'";
    $queryTrabajador = $bd->consultar($sqlTrabajador);
    $rowTrabajador = $bd->obtener_fila($queryTrabajador, 0);
    $nombreTrabajador = $rowTrabajador['nombre'];
    
    // Obtener configuración SMTP
    $sql_smtp = "SELECT control.host_smtp, control.puerto_smtp, control.protocolo_smtp,
                 control.cuenta_smtp, control.password_smtp 
                 FROM control";
    $query_smtp = $bd->consultar($sql_smtp);
    $result_smtp = $bd->obtener_fila($query_smtp, 0);
    
    $host = $result_smtp['host_smtp'];
    $puerto = $result_smtp['puerto_smtp'];
    $protocolo = $result_smtp['protocolo_smtp'];
    $cuenta = $result_smtp['cuenta_smtp'];
    $password = $result_smtp['password_smtp'];
    
    // Obtener emails de la ubicación
    $sql = "SELECT email, email1, email2, email3 FROM clientes_ubicacion WHERE codigo = '$ubicacion'";
    $query = $bd->consultar($sql);
    $result_emails = $bd->obtener_fila($query, 0);
    
    $emails = array();
    if (!empty($result_emails['email'])) {
        $emails[] = $result_emails['email'];
    }
    if (!empty($result_emails['email1'])) {
        $emails[] = $result_emails['email1'];
    }
    if (!empty($result_emails['email2'])) {
        $emails[] = $result_emails['email2'];
    }
    if (!empty($result_emails['email3'])) {
        $emails[] = $result_emails['email3'];
    }
    
    if (count($emails) > 0) {
        // Preparar contenido del correo
        $asunto = "Marcaje de Supervisor - " . date('d/m/Y');
        
        $cuerpoHtml = "<html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { background-color: #4CAF50; color: white; padding: 10px; }
                .content { padding: 20px; }
                .info { margin: 10px 0; }
                .label { font-weight: bold; color: #333; }
                .link { color: #4CAF50; text-decoration: none; }
                .footer { font-size: 12px; color: #999; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>Marcaje de Supervisor</h2>
            </div>
            <div class='content'>
                <p>Se ha realizado un marcaje con los siguientes detalles:</p>
                
                <div class='info'>
                    <span class='label'>Trabajador:</span> $nombreTrabajador<br>
                    <span class='label'>Código de ubicación:</span> $ubicacion<br>
                    <span class='label'>Fecha:</span> " . date('d/m/Y H:i:s') . "<br>
                    <span class='label'>Usuario que marcó:</span> $usuario<br>
                </div>
                
                <div class='info'>
                    <span class='label'>Archivo del marcaje:</span><br>
                    <a href='$link' class='link' target='_blank'>Ver archivo</a>
                </div>
            </div>
            <div class='footer'>
                <p>Este es un correo automático, por favor no responder.</p>
            </div>
        </body>
        </html>";
        
        // Enviar correo a todos los emails
        $enviados = 0;
        $respuestas = array();
        
        foreach ($emails as $email) {
            // Limpiar buffer antes de cada envío
            ob_clean();
            
            // Llamar a la función existente (que imprime JSON)
            enviar_mail_html(
                $host, $puerto, $protocolo, $cuenta, $password,
                'IBARTI', $asunto, $cuerpoHtml, $cuerpoHtml, $email, $link
            );
            
            // Capturar la salida de la función
            $output = ob_get_contents();
            $respuestas[] = $output;
            
            // Intentar parsear la respuesta para saber si fue exitoso
            $respuestaJson = json_decode($output, true);
            if ($respuestaJson && !isset($respuestaJson['error'])) {
                $enviados++;
            }
        }
        
        if ($enviados > 0) {
            $result['error'] = false;
            $result['mensaje'] = "Correo(s) enviado(s) exitosamente a $enviados destinatario(s)";
            $result['emails'] = $emails;
            $result['enviados'] = $enviados;
            $result['respuestas_parciales'] = $respuestas;
        } else {
            $result['error'] = true;
            $result['mensaje'] = 'No se pudo enviar el correo. Verifique la configuración SMTP';
            $result['respuestas_parciales'] = $respuestas;
        }
        
    } else {
        $result['error'] = true;
        $result['mensaje'] = 'La ubicación no tiene correos configurados';
    }
    
} catch (Exception $e) {
    $result['error'] = true;
    $result['mensaje'] = $e->getMessage();
    $bd->log_error("Aplicacion", "enviaremail.php", $usuario, $e->getMessage(), "");
}

// Limpiar buffer y devolver SOLO nuestra respuesta
ob_clean();
header('Content-Type: application/json');
echo json_encode($result);
?>