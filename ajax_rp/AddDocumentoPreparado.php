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
    header('Content-Type: application/pdf');
    // header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: 'attachment'; filename=\"" . basename($link) . ".pdf\""); 
    // header('Content-Length: '. filesize($link));
    readfile($link);  
} else {
    die("Error: File not found.");
} 
?>