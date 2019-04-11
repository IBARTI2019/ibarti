<?php
require "../modelo/ficha_dotacion_modelo.php";
require "../../../".Leng;
$codigo   = $_POST['codigo'];

$pag = 0;
$ficha_dot = new FichaDotacion;
$proced   = "p_ficha_dotacion";
$titulo    = " MODIFICAR PRODUCTO (".$codigo.")";
$prod        = $ficha_dot->cargar_dotacion($codigo);
$lineas  =  $ficha_dot->get_lineas();
?>

<table width="95%" align="center">
  <tr>
    <td width="15%" class="etiqueta">Linea</td>
    <td width="15%" class="etiqueta">Sub Linea</td>
    <td width="10%" class="etiqueta"><?php echo $leng['producto'];?></td>
    <td width="20%" class="etiqueta" id="add_renglon_etiqueta">Agregar</td>
  </tr>
  <tr>
    <td>
      <select id="dot_linea" onchange="get_sub_lineas(this.value)" style="width:250px">
        <option value="">Seleccione...</option>
         <?php 
        foreach ($lineas as  $datos) {
          echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
        }
        ?>        
      </select>
    </td>
    <td id="sub_lineas">
      <select id="dot_sub_linea" style="width:250px" >
        <option value="">Seleccione...</option>
      </select>
    </td>
    <td id="productos">
      <select id="dot_producto" style="width:250px" >
        <option value="">Seleccione...</option>
      </select>
    </td>
    <td align="center">
      <img  border="null" width="20px" height="20px" src="imagenes/ico_agregar.ico" id="add_renglon" onclick="Agregar_renglon()" disabled title="Agregar renglon" />
    </td>
  </tr>
</table>