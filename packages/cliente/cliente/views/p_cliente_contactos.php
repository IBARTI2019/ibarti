
<?php
require "../../../../".Leng;
?>
<form action="" method="post" id="add_cliente_contacto_form">
  <fieldset class="fieldset">
    <legend>Datos Contactos <?php echo $leng['cliente']; ?></legend>
    <table width="90%" id="data_ad" align="center">
      <tr>
        <td width="10%" class="etiqueta"><?php echo $leng['ubicacion']; ?>:</td>
        <td width="30%">
          <input type="text" name="c_doc" id="c_doc" style="width:160px;">
        </td>
        <td width="10%" class="etiqueta">Nombres:</td>
        <td width="30%"><input type="text" name="c_nombres" id="c_nombres" style="width:160px;"></td>
        <td width="20%" rowspan="3" style="text-aling:left"><img src="imagenes/ico_agregar.ico" id="add_contacto" title="Agregar Contacto" onclick="agregar_arreglo_contactos()" alt=""></td>
      </tr>
      <tr>
        <td class="etiqueta">Cargo:</td>
        <td><input type="text" name="c_cargo" id="c_cargo" style="width:160px;"></td>
        <td class="etiqueta">Telefono:</td>
        <td><input type="text" name="c_tel" id="c_tel" style="width:160px;"></td>
      </tr>
      <tr class="etiqueta">
        <td>Correo:</td>
        <td><input type="text" name="c_correo" id="c_correo" style="width:160px;"></td>
        <td>Observacion:</td>
        <td colspan="2"><input type="text" name="c_observacion" id="c_observacion" style="width:100%;"></td>
      </tr>
      <tr>
        <td colspan="5" heigth="10px"></td>
      </tr>
      <tr>
        <td colspan="5">
          <div class="tabla_sistema">
            <table id="tabla_add" width="100%"  border="1">
              <thead>
                <tr>
                  <th class="etiqueta" style="align-content: center;" width="10%">Documento</th>
                  <th class="etiqueta" style="align-content: center;" width="20%">Nombres</th>
                  <th class="etiqueta" style="align-content: center;" width="20%">Cargo</th>
                  <th class="etiqueta" style="align-content: center;" width="20%">Telefono</th>
                  <th class="etiqueta" style="align-content: center;" width="20%">Correo</th>
                  <th class="etiqueta" style="align-content: center;" width="10%"></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </td>
      </tr>
    </table>
    
    <input id="c_metodo" type="hidden" value="<?php echo $metodo; ?>" />
    <input name="cambios" id="c_cambios" type="hidden" value="false" />
  </fieldset>
</form>