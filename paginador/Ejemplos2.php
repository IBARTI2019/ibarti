<?php 
require_once('../autentificacion/aut_config.inc.php');
require_once('PHPPaging.lib.php');

// Abrimos la conexi�n al servidor MySQL
//$link = mysql_connect('localhost', 'usuario', 'password');
mysql_select_db($bd_cnn, $cnn);

// Instanciamos, indicando el recurso de conexi�n
$paging = new PHPPaging($cnn);
?>
<table border="1" style="width: 80%; margin: 10px;">
    <tr>
        <td><b>Id</b></td>
        <td><b>Nombres</b></td>
        <td><b>Nombres</b></td>
 
    </tr>

    <?php
    
        // Apertura de la conexi�n a la base de datos e Inclusi�n del script
        
        // Instanciamos el objeto
        $paging = new PHPPaging;
        
        // Indicamos la consulta al objeto 
        $paging->agregarConsulta("SELECT * FROM clientes ORDER BY co_cli ASC");
        
        // Ejecutamos la paginaci�n
        $paging->ejecutar();  
        
        // Imprimimos los resultados, para esto creamos un ciclo while
        // Similar a while($datos = mysql_fetch_array($sql))
        while($datos = $paging->fetchResultado()) { 
            echo "<tr>"; 
            echo "<td>".$datos[0]."</td>"; 
            echo "<td>".$datos[1]."</td>"; 
           	echo "<td>".$datos[2]."</td>"; 
            echo "</tr>"; 
        } 
    
    ?>

</table>

<?php
    // Imprimimos la barra de navegaci�n
    echo $paging->fetchNavegacion(),' <b>Navegaci�n</b>:';
?>