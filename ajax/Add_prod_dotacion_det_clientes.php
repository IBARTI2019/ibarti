<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
$bd = new DataBase();
$rel   = $_POST['codigo'];
$tieneeans="hidden";
?>
    <table width="100%" align="center">
	<tr class="text" id="tr_1_<?php echo $rel;?>">
     <td width="20%" id="select_1_<?php echo $rel;?>"><select name="linea_<?php echo $rel;?>"
                     id="linea_<?php echo $rel;?>" style="width:150px;"
                     onchange="ActivarSubLinea(this.value, '<?php echo $rel;?>', 'select_2_<?php echo $rel;?>')" required="required">
          <option value="">Seleccione...</option>
          <?php  	$sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select></td>
        <td id="select_2_<?php echo $rel;?>"><select name="sub_linea_<?php echo $rel;?>"
                     id="sub_linea_<?php echo $rel;?>" style="width:200px;"
                     onchange="Activar01(this.value, '<?php echo $rel;?>', 'select_3_<?php echo $rel;?>')" required="required">
          <option value="">Seleccione... </option>
          <?php   $sql = " SELECT codigo, descripcion FROM prod_lineas WHERE `status` = 'T' ORDER BY 2 ASC ";
                $query = $bd->consultar($sql);
                while($datos=$bd->obtener_fila($query,0)){
      ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select></td>
     <td id="select_3_<?php echo $rel;?>"><select name="producto_<?php echo $rel;?>"
                     id="producto_<?php echo $rel;?>"  style="width:200px;" disabled="disabled" onclick="buscarprod(this.value,'producto_<?php echo $rel;?>')" >
          <option value="">Seleccione... </option>
        </select></td>

      <td id="select_4_<?php echo $rel;?>"><select name="almacen_<?php echo $rel;?>"
                     id="almacen_<?php echo $rel;?>" style="width:200px;" disabled="disabled" required="required">
          <option value="">Seleccione..</option>
        </select></td>
    <td id="input04_<?php echo $rel;?>"><input type="number" name="cantidad_<?php echo $rel;?>" style="width: 65px;"
                                        id="cantidad_<?php echo $rel;?>" required="required"/></td>
    
    <td width="8%"><input type="<?php echo $tieneeans ?>" id="boton_<?php echo $rel;?>" name="boton_<?php echo $rel;?>"  value="boton1"/></td>
        
	<td width="8%"><input type="hidden" name="relacion_<?php echo $rel;?>"  value="<?php echo $rel;?>"/></td>
       
    </tr></table>
    <div id="Contenedor01_<?php echo $rel;?>"></div>
