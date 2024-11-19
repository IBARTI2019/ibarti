<script language="javascript">
  $("#add_horario_form").on('submit', function(evt){
    evt.preventDefault();
    save_horario();
  });
//  $("#add_horario_form input, select").on('keyup',function (evt) {
  $("#add_horario_form input, select").change(function (evt) {
    evt.preventDefault();
    $("#h_cambios").val('true');
    $('#salvar_horario').attr('disabled',false);
  });

  $("#bus_horario").on('submit', function(evt){
    evt.preventDefault();
    buscar_horario(true);
  });
</script>
<?php
include_once('../../../../funciones/funciones.php');
require "../modelo/horario_modelo.php";
require "../../../../".Leng;
$horario = new Horario;
$metodo = $_POST['metodo'];

if($metodo == 'modificar')
{
	$codigo   = $_POST['codigo'];
	$titulo   = "Modificar ".$leng['horario'];
	$hor      =  $horario->editar("$codigo");
}else{
 $titulo    = "Agregar ".$leng['horario'];
 $hor      =  $horario->inicio();
}
$concepto = $horario->get_concepto($hor['cod_concepto']);
?>
<div id="add_horario">
  <form action="" method="post" name="add_horario" id="add_horario_form">
    <span class="etiqueta_title" id="title_horario"><?php echo $titulo;?></span>
    <span style="float: right;" align="center" >
      <img  style ="display: none;" border="null" width="25px" height="25px" src="imagenes/borrar.bmp" title="Borrar Registro" onclick="Borrar_horario()" id="borrar_horario" />
      <img style="display: none;" border="null" width="25px" height="25px" src="imagenes/nuevo.bmp" alt="Agregar" onclick="irAAgregarHorario()" title="Agregar Registro" id="agregar_horario" />
      <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscar_horario_title" onclick="irABuscarHorario()" />
    </span>
    <table width="90%" align="center">
      <tr>
        <td height="8" colspan="4" align="center"><hr></td>
      </tr>
      <tr>
       <td width="15%" class="etiqueta">C&oacute;digo:</td>
       <td width="35%"><input type="text" name="codigo" id="h_codigo" minlength="2" maxlength="11" required value="<?php echo $hor['codigo'];?>" />
         Activo: <input name="activo" id="activo" type="checkbox" <?php echo statusCheck($hor['status']);?> />
       </td>
       <td width="15%" class="etiqueta"><?php echo $leng['concepto']?>:</td>
       <td width="35%"><select name="concepto" id="h_concepto" style="width:200px" required>
        <option value="<?php echo $hor['cod_concepto'];?>"><?php echo $hor['concepto'].' ('.$hor['abrev'].')'; ?></option>
         <?php
         foreach ($concepto as  $datos) {
          echo '<option value="'.$datos[0].'">'.$datos[1].' ('.$datos[2].')</option>';
        }
        ?>
      </select></td>
    </tr>
    <tr>
      <td class="etiqueta">Nombre: </td>
      <td colspan="3"><input type="text" name="nombre" id="h_nombre" minlength="4" maxlength="60" required style="width:300px"
        value="<?php echo $hor['nombre'];?>"/>
        <br /><span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Hora Entrada: </td>
      <td><input type="time" name="h_entrada" id="h_entrada" required value="<?php echo $hor['hora_entrada'];?>"/></td>

      <td class="etiqueta">Hora Salida: </td>
      <td><input type="time" name="h_salida" id="h_salida" required value="<?php echo $hor['hora_salida'];?>"/></td>
    </tr>
    <tr>
      <td class="etiqueta">Inicio Marcaje Entrada: </td>
      <td><input type="time" name="inicio_m_entrada" id="inicio_m_entrada" required  value="<?php echo $hor['inicio_marc_entrada'];?>"/></td>

      <td class="etiqueta">Fin Marcaje Entrada: </td>
      <td><input type="time" name="fin_m_entrada" id="fin_m_entrada" required  value="<?php echo $hor['fin_marc_entrada'];?>"/></td>
    </tr>
    <tr>
      <td class="etiqueta">Inicio Marcaje Salida: </td>
      <td><input type="time" name="inicio_m_salida" id="inicio_m_salida" required  value="<?php echo $hor['inicio_marc_salida'];?>"/></td>

      <td class="etiqueta">Fin Marcaje Salida: </td>
      <td><input type="time" name="fin_m_salida" id="fin_m_salida" required value="<?php echo $hor['fin_marc_salida'];?>"/></td>
    </tr>
    <tr>
      <td class="etiqueta">Dias De Trabajo: </td>
      <td id="input03">
        <input type="number" name="dia_trabajo" id="dia_trabajo"  max="3" required value="<?php echo $hor['dia_trabajo'];?>"  />
      </td>
      <td class="etiqueta">Minutos De Trabajo: </td>
      <td><input type="number" name="minutos_trabajo" id="minutos_trabajo" required max="4320"   value="<?php echo $hor['minutos_trabajo'];?>">
      </td>
    </tr>
    <tr>
      <td height="8" colspan="4" align="center"><hr></td>
    </tr>
  </table>

  <div align="center"><span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="submit" name="salvar"  id="salvar_horario" value="Guardar" class="readon art-button" />
  </span>&nbsp;
  <span class="art-button-wrapper">
    <span class="art-button-l"> </span>
    <span class="art-button-r"> </span>
    <input type="reset" id="limpiar_horario" value="Restablecer" class="readon art-button" />
  </span>&nbsp;
  <input name="metodo" id="h_metodo" type="hidden"  value="<?php echo $metodo;?>" />
  <input name="cambios" id="h_cambios" type="hidden"  value="false"/>
</div>
</form>
</div>

<div id="buscar_horario" style="display: none;">
  <form name="bus_horario" id="bus_horario" style="float: right;">
    <input type="text" name="codigo_buscar" id="data_buscar_horario" maxlength="11" placeholder="Escribe aqui para filtrar.. "/>
    <select name="filtro_buscar_horario" id="filtro_buscar_horario" style="width:110px" required>
     <option value="codigo">Código</option>
     <option value="nombre">Nombre</option>
     <option value="hora_entrada">Hora Entrada</option>
     <option value="hora_salida">Hora Salida</option>
     <option value="inicio_marc_entrada">Inicio Marcaje Entrada</option>
     <option value="inicio_marc_salida">Fin Marcaje Entrada</option>
   </select>
   <input type="submit" name="buscarHorario" id="buscarHorario" hidden="">
   <span class="art-button-wrapper">
    <img border="null" width="25px" height="25px" src="imagenes/buscar.bmp" title="Buscar Registro" id="buscarH" onclick="buscar_horario(true);" />
  </span>
</form>

<div class="tabla_sistema listar">
  <span class="etiqueta_title"> Consulta De <?php echo $leng['horario'];?></span>
  <table id="lista_horarios" width="100%" border="0" align="center">
    <thead>
      <tr>
        <th width="12%">Código</th>
        <th width="25%">Nombre</th>
        <th width="12%">Hora Entrada</th>
        <th width="12%">Hora Salida</th>
        <th width="12%">Inicio Marcaje<br/>Entrada</th>
        <th width="12%">Fin Marcaje<br />Entrada</th>
        <th width="15%" class="etiqueta">Status</th>
      </tr>
    </thead>
    <tbody>
     <?php
     $lista_horarios = $horario->get();

     foreach ($lista_horarios as  $datos) {
      echo '<tr onclick="Cons_horario(\''.$datos["codigo"].'\', \'modificar\')" title="Click para Modificar..">
      <td>'.$datos["codigo"].'</td>
      <td>'.longitud($datos["nombre"]).'</td>
      <td>'.$datos["hora_entrada"].'</td>
      <td>'.$datos["hora_salida"].'</td>
      <td>'.$datos["inicio_marc_entrada"].'</td>
      <td>'.$datos["fin_marc_entrada"].'</td>
      <td>'.statuscal($datos["status"]).'</td>
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
    <input type="button" id="volver_horario" value="Volver" onclick="volverHorario()" class="readon art-button" />
  </span>
</div>
</div>
