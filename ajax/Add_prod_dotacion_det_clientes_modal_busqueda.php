<?php
include_once('../funciones/funciones.php');
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;

$bd = new DataBase();
//require_once('../autentificacion/aut_config.inc.php');
//include_once('../funciones/mensaje_error.php');
$metodo=$_POST['metodo'];
$rel2   = $_POST['codigo'];
$bloqueEANS=$_POST['numero'];
$producto=$_POST['producto'];
$cantidad=$_POST['cantidad'];
$rel=$_POST['numero'];
$tieneeans=$_POST['tieneeans'];
$vinculo    = "inicio.php?area=formularios/Add_EANS_clientes.php";
if ($metodo=="buscar"){
    $where="1=1";
    if ($rel2 <> "") { $where.="prod_ean.cod_ean like '%$rel2%'"; }

    $where.=" ORDER BY prod_ean.cod_ean DESC";
    $query="SELECT count(*) as filas FROM productos,prod_ean WHERE productos.item=prod_ean.cod_producto";
    $sql="SELECT prod_ean.cod_producto as codigo,prod_ean.cod_ean as eans FROM prod_ean WHERE  prod_ean.cod_producto ='$producto' and prod_ean.cod_ean like '%$rel2%'";
} 

$rs_busqueda=$bd->consultar($query);
$filas=mysql_result($rs_busqueda,0,"filas");

$titulo="Listado de EANS";
$metodo="Agregar";
$Borrar="inicio.php?area=formularios/borrar_EANS_clientes.php";
$ndx='tabla' + '$bloqueEANS';    
$procesar="Procesar01('".$rel2."','".$rel."')";
$salida='salir';

$salir = "Salir01('".$rel2."')";
   $query = $bd->consultar($sql);

		echo '<table width="100%" border="2" class="fondo00" id="'.$rel2.'" name="'.$ndx.'">
			<tr>
				<th width="25%" class="etiqueta">Codigo</th>
				<th width="25%" class="etiqueta">Eans('.$cantidad.')</th>
            	<th width="26%" class="etiqueta">Ok</th>
                <th>Buscador <input type="text" id="txt1" class="text" onchange="showHint(this.value)" /></th>
				</tr>';
		 $valor = 0;
        
	    while($row02=$bd->obtener_fila($query,0)){

		   $clickip = "Clickup('".$row02["codigo"]."','".$row02["eans"]."',$cantidad)";  
           
		if ($valor == 0){
			$fondo = 'fondo01';
		    $valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
        
		  echo'<tr class="'.$fondo.'">
			     <td class="texto">'.$row02["codigo"].'</td>
                 <td class="texto">'.$row02["eans"].'</td>
			
			     <td class="texto"> <td class="aCentro" width="5%"><input type="checkbox" name="checkbox_socio" id="checkbox_socio" value="'.$row02["eans"].'" onclick="'.$clickip.'")</td>
                 </tr>';
        
		}

echo '</table>'; mysql_free_result($query);
echo '<tr class="'.$fondo.'">
			 <div align="center">
					<span class="art-button-wrapper" id="botong">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input name="botong" type="button" title="ProcesarEans" id="boton_eans" class="readon art-button" value="Procesar" onclick="'.$procesar.'"/>
					</span>
					<span class="art-button-wrapper" id="botons">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input name="botons" type="button" title="Cancelar" class="readon art-button"  value="Salir" 
						onclick="'.$salir.'" />
					</span>
				</div>
		</tr>';

 
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
                     id="producto_<?php echo $rel;?>" style="width:200px;" disabled="disabled" required="required">
          <option value="">Seleccione... </option>
        </select></td>

      <td id="select_4_<?php echo $rel;?>"><select name="almacen_<?php echo $rel;?>"
                     id="almacen_<?php echo $rel;?>" style="width:200px;" disabled="disabled" required="required">
          <option value="">Seleccione..</option>
        </select></td>
    <td id="input04_<?php echo $rel;?>"><input type="number" name="cantidad_<?php echo $rel;?>"
                                        id="cantidad_<?php echo $rel;?>" required="required"/></td>
    
    <td width="8%"><input type="<?php echo $tieneeans ?>" id="boton_<?php echo $rel;?>" name="boton_<?php echo $rel;?>"  value=""/></td>
        
	<td width="8%"><input type="hidden" name="relacion_<?php echo $rel;?>"  value="<?php echo $rel;?>"/></td>
       
    </tr></table>
    <div id="Contenedor01_<?php echo $rel;?>"></div>
