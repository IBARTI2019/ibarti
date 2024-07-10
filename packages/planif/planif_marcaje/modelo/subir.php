<?php

$url = "http://194.163.161.64:9090/docs/upload/"; // URL de destino
$archivoLocal = "archivo_local.txt"; // Ruta del archivo local

// Abrir el archivo local en modo lectura
$fp = fopen($archivoLocal, "r");

// Leer el contenido del archivo local en una variable
$contenido = fread($fp, filesize($archivoLocal));

// Cerrar el archivo local
fclose($fp);

// Configurar las opciones de solicitud HTTP
$options = array(
    "http" => array(
        "header" => "Content-Type: application/x-www-form-urlencoded",
        "method" => "POST",
        "content" => http_build_query(array("archivo" => $contenido)),
    ),
);

// Enviar la solicitud HTTP y obtener la respuesta
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

?>