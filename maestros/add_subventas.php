
<?php
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=" . $_GET['Nmenu'] . "&mod=" . $_GET['mod'] . "";

if ($metodo == 'modificar') {
  $codigo = $_GET['codigo'];

  $bd = new DataBase();
  $sql = " SELECT $tabla.codigo, $tabla.descripcion,
    $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
    $tabla.status, $tabla.cod_ruta, ruta_de_ventas.descripcion as ruta
    FROM $tabla  inner join ruta_de_ventas on $tabla.cod_ruta=ruta_de_ventas.codigo  WHERE $tabla.codigo = '$codigo' ";
  
  $query = $bd->consultar($sql);
  $result = $bd->obtener_fila($query, 0);
  
  $codigo_onblur = "";
  $descripcion = $result['descripcion'];
  $campo01     = $result['campo01'];
  $campo02     = $result['campo02'];
  $campo03     = $result['campo03'];
  $campo04     = $result['campo04'];
  $status      = $result['status'];
  $cod_ruta      = $result['cod_ruta'];
  $ruta    =$result['ruta'];
  $readonly = 'readonly="readonly"';
} else {
  $codigo="";
  $sql_tipos = "SELECT max(codigo) as Codigo FROM subruta_de_ventas WHERE codigo > 0 ;";
  $query_tipos = $bd->consultar($sql_tipos);
  $result = $bd->obtener_fila($query_tipos, 0);
  $codigo = $result['Codigo'] + 1;
  $readonly = '';
  $orden = '';
  $codigo_onblur = "Add_ajax_maestros(this.value, 'ajax/validar_maestros.php', 'Contenedor', '$tabla')";

  $descripcion = '';

  $campo01     = '';
  $campo02     = '';
  $campo03     = '';
  $campo04     = '';
  $status      = 'T';
}
?>
<div id="Contenedor" class="mensaje"></div>
<input type="hidden" name="codigos" value="<?php echo $codigo; ?>">
<fieldset class="fieldset">
  <legend>DATOS BASICOS <?php echo $titulo; ?> </legend>
  <table width="80%" align="center">
    <tr>
      <td class="etiqueta"></td>
      <td id="input01"><input type="hidden" name="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo; ?> "  readonly />
        Activo: <input name="activo" type="checkbox" <?php echo statusCheck("$status"); ?> value="T" /> 
        <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    <tr>
	     <td class="etiqueta">Ruta de Venta:</td>
	     <td id="select01">
			   <select name="cod_ruta" style="width:200px;">
     				   <option value="<?php echo $cod_ruta;?>"><?php echo $ruta;?></option>
	        <?php  	$sql = " SELECT codigo, descripcion FROM ruta_de_ventas
			                  WHERE status = 'T' AND ruta_de_ventas.codigo <> '$cod_ruta'
						   ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		    ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?></select><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n: </td>
      <td id="input02"><input type="text" name="descripcion" maxlength="100" style="width:300px" value="<?php echo $descripcion; ?>"  /><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    
    <tr>
      <td height="8" colspan="2" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
    </span>&nbsp;
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
    </span>
  </div>
  <input name="metodo" id="metodo" type="hidden" value="<?php echo $metodo; ?>" />
  <input name="tabla" id="tabla" type="hidden" value="<?php echo $tabla; ?>" />
  <input name="usuario" id="usuario" type="hidden" value="<?php echo $usuario; ?>" />
  <input name="href" type="hidden" value="<?php echo $archivo2; ?>" />
</fieldset>
<script type="text/javascript">
  var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
    validateOn: ["blur", "change"]
  });
  var input02 = new Spry.Widget.ValidationTextField("input02", "none", {
    validateOn: ["blur", "change"]
  });
  
  var radio01_5 = new Spry.Widget.ValidationRadio("radio01_5", {
    validateOn: ["change", "blur"]
  });
</script>