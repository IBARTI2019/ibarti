<script language="JavaScript" type="text/javascript">
  $("#bus_precliente").on('submit', function(evt) {
    evt.preventDefault();
    buscar_precliente(true);
  });

  function Close() {
    $("#myModal").hide();
  }

  function ModalOpen() {
    $("#myModal").show();
  }
</script>
<?php
require "../modelo/cliente_modelo.php";
require "../../../../" . Leng;
$codigo   = $_POST['codigo'];
$metodo   = $_POST['metodo'];

$pag = 0;
$titulo = "";
$cliente = new Cliente;
$proced   = "p_preclientes";
if ($metodo == 'modificar') {
  $codigo    = $_POST['codigo'];
  $cl_nombre =  $cliente->get_cl_nombre($codigo);
  $titulo    = "" . $leng['precliente'] . " : " .  $cl_nombre[0] . "(" . $codigo . ")";
  $cl        = $cliente->editar("$codigo");
  $readonly = "readonly";
  $vistas = '';
} else {
  $cl   = $cliente->inicio();
  $readonly = "";
  $titulo    = "Agregar " . $leng['precliente'];
  $vistas = 'display:none;';
}
?>
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="Close()">&times;</span>
      <span id="modal_title"></span>
    </div>
    <div class="modal-body">
      <div id="contenido_modal"></div>
    </div>
  </div>
</div>

<div id="add_precliente">
  <form id="pdf" name="pdf" action="reportes/rp_precliente.php" method="post" target="_blank">
    <input type="hidden" name="codigo" id="codPDF" value="<?php $codigo; ?>">
  </form>
  <span class="etiqueta_title" id="title_precliente"><?php echo $titulo; ?>
  </span>
  <span style="float: right;" align="center">
    <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/pdf.gif" onclick="Pdf()" title="Imprimir Cliente" id="pdfC" />
    <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/borrar.bmp" title="Borrar Registro" onclick="Borrar_precliente()" id="borrar_precliente" />
    <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/nuevo.bmp" alt="Agregar" onclick="irAAgregarCliente()" title="Agregar Registro" id="agregar_precliente" />
    <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscar_precliente_title" onclick="irABuscarCliente()" />
  </span>
  <div class="TabbedPanels" id="tpprecliente">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab"><?php echo $leng['precliente']; ?></li>
      <li class="TabbedPanelsTab" <?php echo 'style="' . $vistas . '"' ?>>Datos Adiccionales <?php echo $leng['precliente']; ?></li>
    </ul>

    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent"><?php include('p_precliente.php'); ?></div>
      <div class="TabbedPanelsContent"><?php include('p_precliente_ad.php'); ?></div>
    </div>
  </div>
</div>

<div id="buscar_precliente" style="display: none;">
  <form name="bus_precliente" id="bus_precliente" style="float: right;">
    <input type="text" name="codigo_buscar" id="data_buscar_precliente" maxlength="11" placeholder="Escribe aqui para filtrar.. " />
    <select name="filtro_buscar_precliente" id="filtro_buscar_precliente" style="width:110px" required>
      <option value="codigo">Código</option>
      <option value="rif"><?php echo $leng["rif"]; ?></option>
      <option value="nombre">Nombre</option>
    </select>
    <input type="submit" name="buscarCliente" id="buscarCliente" hidden="">
    <span class="art-button-wrapper">
      <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscarC" onclick="buscar_precliente(true);" />
    </span>
  </form>
  <div class="tabla_sistema listar">
    <span class="etiqueta_title"> Consulta De <?php echo $leng['precliente']; ?></span>
    <table width="100%" border="0" align="center" id="lista_preclientes">
      <thead>
        <tr>
          <th width="12%">Codigo</th>
          <th width="12%"><?php echo $leng["rif"]; ?></th>
          <th width="32%">Nombre</th>
          <th width="22%"><?php echo $leng['region']; ?></th>
          <th width="14%">Activo</th>
        </tr>

      </thead>
      <tbody>
        <?php
        $matriz  =  $cliente->get();
        foreach ($matriz as  $datos) {
          echo '<tr onclick="Cons_precliente(\'' . $datos[0] . '\', \'modificar\')" title="Click para Modificar..">
        <td>' . $datos["codigo"] . '</td>
        <td>' . $datos["rif"] . '</td>
        <td>' . $datos["nombre"] . '</td>
        <td>' . $datos["region"] . '</td>
        <td>' . statuscal($datos["status"]) . '</td>
        </tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver_precliente" value="Volver" onclick="volverCliente()" class="readon art-button" />
    </span>
  </div>

</div>

<script language="JavaScript" type="text/javascript">
  var tpprecliente = new Spry.Widget.TabbedPanels("tpprecliente", {
    defaultTab: <?php echo $pag; ?>
  });
</script>