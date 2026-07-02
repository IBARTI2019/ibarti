<?php
// Desactivar límites de tiempo por si la API de Zoho tarda un poco
@set_time_limit(120);

// Configurar cabecera para devolver JSON en UTF-8 (Compatible con PHP 5)
header('Content-Type: application/json; charset=utf-8');

// 1. DETECCIÓN AUTOMÁTICA DE ENTORNO (Windows Local vs Linux VPS)
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // --- MODO SIMULACIÓN EN LOCAL (WINDOWS) ---
    // Lee el log de la carpeta neutral que creamos en el disco C
    $logPath = 'C:\\zoho\\zoho_integration_package\\sync_zoho_to_local.log';
    
    if (file_exists($logPath)) {
        $logContent = file_get_contents($logPath);
        $output = explode("\n", trim($logContent));
        $resultCode = (strpos($logContent, 'FIN DEL PROCESO') !== false) ? 0 : 1;
    } else {
        $output = array("No se encontro el archivo log local en: " . $logPath);
        $resultCode = 1;
    }
} else {
    // --- CONFIGURACIÓN DE PRODUCCIÓN REAL (LINUX VPS) ---
    // Rutas absolutas definitivas apuntando al home del desplegador
    $workingDir = '/home/desplegador/zoho_integration';
    $pythonPath = $workingDir . '/.venv/bin/python3';
    $scriptPath = $workingDir . '/sync_zoho.py';
    
    // Ejecución directa y limpia en Linux interceptando errores
    $command = '"' . $pythonPath . '" "' . $scriptPath . '" 2>&1';
    
    $output = array();
    $resultCode = 0;
    exec($command, $output, $resultCode);
}

// 2. RETORNAR RESPUESTA JSON UNIFICADA
echo json_encode(array(
    'status'     => ($resultCode === 0) ? 'success' : 'error',
    'message'    => ($resultCode === 0) ? 'Sincronizacion completada con exito.' : 'Fallo la ejecucion del entorno en produccion.',
    'error_code' => $resultCode,
    'details'    => $output
));
exit;