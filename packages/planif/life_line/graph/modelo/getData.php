<?php
require "../modelo/life_line_graph_modelo.php";
$graph = new LifeLineGraph;

$ubicacion = $_POST['ubicacion'];
echo json_encode($graph->get_data($ubicacion));

?>