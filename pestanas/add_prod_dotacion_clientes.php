<?php
$metodo = $_GET['metodo'];
$titulo = "Dotacion De ".$leng['ubicacion']."";
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=formularios/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod']."";

if(isset($_GET['pagina'])){
	$pag = $_GET['pagina'];
}else{
	$pag = 0;
}
?>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">
 <div align="center" class="etiqueta_title">
	 </div>
 <div class="TabbedPanels" id="tp1">
	 <ul class="TabbedPanelsTabGroup">
		<li class="TabbedPanelsTab">DOTACION</li>
  		<li class="TabbedPanelsTab">ADICIONALES</li>
	 </ul>
	  <div class="TabbedPanelsContentGroup">
	  	 <div class="TabbedPanelsContent"><?php include('formularios/add_prod_dotacion_clientes.php');?></div>
  	  	 <div class="TabbedPanelsContent"><?php include('formularios/add_adicionales_ad.php');?></div>
	  </div>
 </div>
</form>
<form id="pdf" name="pdf" action="" method="post" target="_blank">
		<input type="hidden" name="codigo" value="<?php echo $_GET["codigo"]?>">
</form>
	<script language="JavaScript" type="text/javascript">
		var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab:<?php echo $pag;?>});
		var TabbedPanels = new Spry.Widget.TabbedPanels("TabbedPanels");
    </script>
