


<link rel="stylesheet" type="text/css" href="packages/grafica/css/grafica.css">
<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<script type="text/javascript" src="funciones/modal.js"></script>

<?php $titulo  = " NOVEDADES GENERAL "; ?>


<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="CloseModal()" >&times;</span>
      <span id="modal_titulo"></span>
    </div>
    <div class="modal-body">
      <div id="modal_contenido"></div>
    </div>
  </div>
</div>


<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">

  <hr /><table width="100%" class="etiqueta" id="prueba">

    <tr><td width="10%">Fecha Desde:</td>
     <td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>
        <td width="10%">Fecha Hasta:</td>
     <td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta" size="9" required onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_reportes', 'fecha_hasta');" border="0" width="17px"></td>
    
     <td width="10%">Status: </td>
     <td width="14%" id="at">
      <div id="contenedor2">

      <select name="status" id="status" style="width:120px;"  required>
        <option value="TODOS">TODOS</option>
       </select></td>
       </div>

       <td width="10%">Departamentos: </td>
       <td width="14%" id="et">
      <div id="contenedor3">

      <select name="departamentos" id="departamentos" style="width:120px;"  required>
        <option value="TODOS">TODOS</option>
       </select></td>
       </div>



    <td width="4%" id="cont_img"><img class="imgLink" src="imagenes/actualizar.png" border="0" onclick="llenar_tb_novedades_general()">
     <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
     <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
     <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/> 
   </td>
 </tr>  
</table><hr />
<div id="cargar"></div>
<div id="listar">
</div>
<div align="center"><br/>
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    
    <input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')"
    class="readon art-button">
  </span>&nbsp;
</div>

</form>

<script type="text/javascript" src="packages/grafica/js/ib-graficas.js"></script>
<script type="text/javascript" src="packages/novedades_rp/controllers/novedades_rpCtrl.js"></script>
<script type="text/javascript">
  consultar(2);
</script>

