 <?php
  require "../modelo/stock_ubic_alcance_modelo.php";
  require "../../../../" . Leng;
  $stock_ubic_alcance = new stock_ubic_alcance;
  $metodo = $_POST['metodo'];
  if ($metodo == 'modificar') {
    $codigo   = $_POST['codigo'];
    $ped      =  $stock_ubic_alcance->editar($codigo);
  } else {
    $ped       = $stock_ubic_alcance->inicio();
    $codigo    = -1;
  }
  $reng       = $stock_ubic_alcance->get_aj_reng($codigo);
  if ($metodo == "agregar") {
  ?>
    
          <fieldset class="fieldset" id="datos_dotacion">
							<legend>Configuracion Alcance: </legend>
							<table width="100%" align="center" class="tabla_sistema">
								<thead>
									<tr>
										<th>SubLinea</th>
										<th>Cantidad</th>
										<th>Ultima Dotación</th>
									</tr>
								</thead>
								<tbody id="datos_dotacion_detalle">
									<?php 
									if($metodo == "agregar"){
										while ($datos= $row02) {
											echo "<tr><td>" .$datos[0]."</td><td>"  .$datos[0]. "</td><td>"  .$datos[0]. "</td></tr>";
										}	
									}
									?>
								</tbody>
							</table>
						</fieldset>
       
    <table width="95%" align="center">
     <tr>
       <td width="30%" class="etiqueta"><?php echo $leng['producto']; ?>
         <input type="hidden" name="producto" id="stdID" value="" /></td>
       <td width="30%" class="etiqueta">Almacén</td>
       <td width="10%" class="etiqueta">Cantidad</td>
       <td width="10%" class="etiqueta" id="add_renglon_etiqueta">Opciones</td>
     </tr>
     <tr>
       <td>
         <input type="text" id="ped_producto" value="" disabled="true" placeholder="Ingrese Dato del <?php echo $leng['producto']; ?>" style="width:250px" />
       </td>
       <td>
         <select id="ped_almacen" onchange="Selec_almacen(this.value)" style="width:250px">
           <option value="">Seleccione...</option>
         </select>
       </td>
       <td>
         <input type="number" id="ped_cantidad" style="width:100px" value="0" min="0" placeholder="">
       </td>
       <td align="center">
         <img border="null" width="20px" height="20px" src="imagenes/ico_agregar.ico" id="add_renglon" onclick="Agregar_renglon()" disabled title="Agregar renglon" />
         <img border="null" width="20px" height="20px" src="imagenes/borrar.bmp" id="canc_renglon" onclick="Cancelar_renglon()" hidden title="Cancelar renglon" />
         <img border="null" width="20px" height="20px" src="imagenes/actualizar.bmp" id="update_renglon" onclick="Actualizar_renglon()" hidden title="Actualizar renglon" />
       </td>
     </tr>
   </table>
 <?php } ?>
 <table width="95%" class="tabla_sistema">
   <thead>
     <tr>
       <th width="5%">N.</th>
       <th width="40%">N producto</th>
       <th width="30%">Almacen</th>
       <th width="5%">Cantidad</th>
       <th width="20%"></th>
     </tr>
   </thead>
   <tbody id="listar_stock_ubic_alcance">
     <?php
      foreach ($reng as  $datos) {
        echo '
      <tr id="tr_' . $datos["reng_num"] . '">
      <td><input type="text" id="items_' . $datos["reng_num"] . '" value="' . $datos["reng_num"] . '" readonly style="width:100px"></td>
      <td><input type="hidden" id="prod_' . $datos["reng_num"] . '" value="' . $datos["producto"] . ' ' . $datos["serial"] . '">
      ' . $datos["producto"] . ' (' . $datos["serial"] . ')</td>
      <td><input type="hidden" id="almacen_' . $datos["reng_num"] . '" value="' . $datos["almacen"] . '">' . $datos["almacen"] . '</td>
      <td><input type="text" id="cant_' . $datos["reng_num"] . '" value="' . $datos["cantidad"] . '" readonly style="width:100px"></td>';
        if ($metodo == "agregar") {
          echo '<td><img class="imgLink" border="null" width="20px" height="20px" src="imagenes/actualizar.bmp"  id="update_renglon" onclick="Modificar_renglon(' . $datos["reng_num"] . ')" title="Modificar Registro />&nbsp; <img class="imgLink" border="null" width="20px" height="20px" src="imagenes/borrar.bmp" id="canc_renglon" onclick="Borrar_renglon(' . $datos["reng_num"] . ')" title="Borrar Registro" /></td></tr>';
        } else {
          if($metodo == "modificar"){
            if($datos["ean"] == 'T'){
              echo '<td><span class="art-button-wrapper">
              <span class="art-button-l"> </span>
              <span class="art-button-r"> </span>
              <input type="button"  title="Ver Eans" onclick = verEans('.$datos["reng_num"].') class="readon art-button"  value="EANS" />
              </span></td>';
            }else{
             echo '<td></td>';
           }
         }
          echo "</tr>";
        }
      } ?>
   </tbody>
 </table>
 <br>

 <script language="JavaScript" type="text/javascript">
   new Autocomplete("ped_producto", function() {
     this.setValue = function(id) {
       $("#stdID").val(id);
       Selec_producto(id);
     }
     if (this.value.length < 1) return;
     ubic = $("#ubicacion").val();
     return "autocompletar/tb/producto_base_serial_alcance.php?q=" + this.text.value + "&filtro=codigo&ubic=" + ubic + "";
   });
 </script>