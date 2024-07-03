<?php 
$Nmenu = '006';
require_once('autentificacion/aut_verifica_menu.php');

$archivo = "control";
$metodo  = "modificar";
$mod     = $_GET['mod'];
$titulo  = "CONTROL DE SISTEMA";

if(isset($_GET['pagina'])){
	$pag = $_GET['pagina'];	
	}else{
	$pag = 0;
}?> 
 <div align="center" class="etiqueta_title"> 
<?php echo $titulo;?>
 </div>
<form action="autentificacion/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">  
 <div class="TabbedPanels" id="tp1">	
	 <ul class="TabbedPanelsTabGroup">	 
		<li class="TabbedPanelsTab">SISTEMA</li>
		<li class="TabbedPanelsTab">NOMINA</li>
    	<li class="TabbedPanelsTab">MENSAJERIA</li>
    	<li class="TabbedPanelsTab">SERVIDOR DE CORREO SALIENTE (SMTP)</li>
    	<li class="TabbedPanelsTab">NOTIFICACIONES</li>
		<li class="TabbedPanelsTab">CONFIRMACIONES</li>
		<li class="TabbedPanelsTab">OTROS</li>		
	 </ul>				
	  <div class="TabbedPanelsContentGroup">
         <div class="TabbedPanelsContent"><?php include("cont_sistema.php");?></div>        
  	  	 <div class="TabbedPanelsContent"><?php include("cont_nomina.php");?></div>
         <div class="TabbedPanelsContent"><?php include("cont_mensajeria.php");?></div>
         <div class="TabbedPanelsContent"><?php include("cont_smtp.php");?></div>
	     <div class="TabbedPanelsContent"><?php include("cont_notificaciones.php");?></div>
		 <div class="TabbedPanelsContent"><?php include("cont_confirmaciones.php");?></div>
		 <div class="TabbedPanelsContent"><?php include("cont_otros.php");?></div>
	  </div>	 
 </div> 
</form>
<script language="JavaScript" type="text/javascript">
var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab:<?php echo $pag;?>});
var TabbedPanels = new Spry.Widget.TabbedPanels("TabbedPanels");
</script>