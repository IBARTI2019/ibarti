<?php
require("../autentificacion/aut_config.inc.php");
$result = array();
$result["error"] = false;

$link = $_GET["link"];
$ficha = $_GET["ficha"];
$result["link"] = $link;
$result["ficha"] = $ficha;

if (file_exists($link)) {
    // header('Content-Description: File Transfer');    
    // header('Content-Type: application/pdf');
    header('Content-Type: application/octet-stream');
    // header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . basename($link) . ".pdf\""); 
    // header('Content-Length: '. filesize($link));
    $result = readfile($link);
    print_r($result);   
} else {
    die("Error: File not found.");
} 
?>