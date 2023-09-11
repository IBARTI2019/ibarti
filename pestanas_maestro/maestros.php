<?php 
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];

$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod'].""; 

if(isset($_GET['pagina'])){
$pag = $_GET['pagina'];	
}else{
$pag = 0;
}
?> 

<form action="sc_maestros/sc_maestros.php" method="post" name="add" id="add">  
 <div align="center" class="etiqueta_title"> 
	 </div>
 <div class="TabbedPanels" id="tp1">	
	 <ul class="TabbedPanelsTabGroup">	 
		<li class="TabbedPanelsTab"><?php echo $titulo;?></li>
		<li class="TabbedPanelsTab">ADICIONALES</li>
	 </ul>				
	  <div class="TabbedPanelsContentGroup"> 
	  	 <div class="TabbedPanelsContent"><?php include('maestros/Add_maestros.php');?></div>
  	  	 <div class="TabbedPanelsContent"><?php include('maestros/add_adicionales_ad.php');?></div>
	  </div>
 </div> 
</form>
	<script language="JavaScript" type="text/javascript">
		var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab:<?php echo $pag;?>});
		var TabbedPanels = new Spry.Widget.TabbedPanels("TabbedPanels");
    </script>