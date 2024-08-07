<?php   
include_once('../../funciones/funciones.php');
require("../../autentificacion/aut_config.inc.php");
require_once("../../".class_bd);
$bd = new DataBase();
 	$typing     = $_GET['q'];
	$filtro     = $_GET['filtro'];
	$where  = " ";
	switch ($filtro) {	
		case "codigo":
		   $where  .= " WHERE LOCATE('$typing', v_cliente_ubic.cod_ubicacion) "; 		  
		break;
		case "nombre":
		   $where  .= " WHERE LOCATE('$typing', v_cliente_ubic.ubicacion) "; 		  
		break;				
			  		   
	 break;		
	}	
	
	$sql = "SELECT v_cliente_ubic.cod_ubicacion,v_cliente_ubic.ubicacion FROM v_cliente_ubic $where ORDER BY 1 ASC";
   $query = $bd->consultar($sql);

	while ($datos=$bd->obtener_fila($query,0)){

	$descripcion = "".$datos[0]." - (".$datos[1].")&nbsp;"; 
	$codigo      = $datos[0];
?>

<li onselect=" this.setText('<?php echo $descripcion;?>').setValue('<?php echo  $codigo;?>') ">
  <b></b> <?php echo $descripcion;?>

</li>
<?php }?>