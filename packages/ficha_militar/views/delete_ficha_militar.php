<?php
require "../modelo/ficha_militar_modelo.php";
$codigos = $_POST['codigo'];
$notif      = new ficha_militar;
$notif-> eliminar_registro($codigos);

?>