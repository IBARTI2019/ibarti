<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
session_start();
$Nmenu   = 528;
require("../autentificacion/aut_config.inc.php");
include_once('../funciones/funciones.php');
require_once('../autentificacion/aut_verifica_menu_02.php');
require_once("../".class_bd);
$bd = new DataBase();

$reporte         = $_GET['reporte'];
$codigo          = $_GET['codigo'];

$archivo         = "rp_fic_control_saime_".$fecha."";
$titulo          = " CONTROL SAIME \n";

if(isset($reporte)){

	$where = " WHERE v_preingreso.cedula = $codigo
	             AND control.oesvica = clientes.codigo ";

	// QUERY A MOSTRAR //
	    $sql = "SELECT v_preingreso.estado, v_preingreso.ciudad,
                       v_preingreso.cedula, v_preingreso.ap_nombre,
					   v_preingreso.`status`, clientes.nombre
                  FROM v_preingreso, control, clientes
			           $where
			  ORDER BY 2 ASC";
	$query = $bd->consultar($sql);
	$row01=$bd->obtener_fila($query,0);

	   $filename = "../imagenes/fotos/$codigo.jpg";
	  if (file_exists($filename)) {
 		  $img_f = '<img src="'.$filename.'?nocache='.time().'"/>';
		} else {
		  $img_f = '<img src="../imagenes/foto.jpg"/>';
		}

	   $filename = "../imagenes/cedula/$codigo.jpg";
	  if (file_exists($filename)) {
 		  $img_c = '<img src="'.$filename.'?nocache='.time().'"/>';
		} else {
		   $img_c = '<img src="../imagenes/cedula.jpg"/>';
		}

		 echo "<table border=0 width='720px'>";
		 echo "<TR><TH colspan='5'>".$row01['nombre']."</TH></TR>
	          <TR><TH colspan='5'>Trabajador: ".$row01['ap_nombre']."</TH></TR>
			  <TR><TH colspan='5'>Lugar: ".$row01['estado'].", ".$row01['ciudad']."</TH></TR>
		       <tr><td width='10%'>&nbsp;</td><td colspan='3' align='center'>$img_f</td><td width='10%'>&nbsp;</td></tr>
			   <tr><td>&nbsp;</td><TH colspan='3'>FOTO</TH><td>&nbsp;</td></tr>
			   <tr><td>&nbsp;</td><td colspan='3' align='center'><table border='1px'><tr><td width='450px' height='250px'>$img_c</td></tr></table></td><td>&nbsp;</td></tr>
			   <tr><td>&nbsp;</td><TH colspan='3' >C.I.</TH><td>&nbsp;</td></tr>
		       <tr><td >&nbsp;</td><td align='center'><table border='1px'><tr><td width='90px' height='120px'>&nbsp;</td></tr></table></td>
			   <td>&nbsp;</td><td align='center'><table border='1px'><tr><td width='90px' height='120px'>&nbsp;</td></tr></table></td><td>&nbsp;</td></tr>
			    <tr><td>&nbsp;</td><td align='center'>IZQ.</td><td>&nbsp;</td><td align='center'>D.</td><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td><TH colspan='3' >______________________<br/>Firma</TH><td>&nbsp;</td></tr>
			   <tr><td colspan='4'></td><td>$date</td></tr></TR>
		       </table>";
echo '<script language="javascript" type="text/javascript">
              window.print();</script>';
}
?>
