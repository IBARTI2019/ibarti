<?php 
require("autentificacion/aut_config.inc.php");
// Inicio la sesi�n
session_start();
header("Cache-control: private"); // Arregla IE 6

 // descoloco todas la variables de la sesi�n
 session_unset();

 // Destruyo la sesi�n
 session_destroy();

 $vinc = Sitio."/".Carpeta."/";
 //Y me voy al inicio 
	 Redirec("$vinc");

     echo "<html></html>";
   exit;
?> 

