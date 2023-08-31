
<?php
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$proced      = "p_estados";
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod']."";

if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
	$sql = " SELECT estados.codigo, estados.cod_pais, paises.descripcion AS pais, estados.descripcion,
                    estados.status
               FROM estados , paises
              WHERE estados.cod_pais = paises.codigo
			    AND estados.codigo = '$codigo'
              ORDER BY 2 DESC";
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);

	$titulo      = "Modificar  $titulo";
	$cod_pais    = $result["cod_pais"];
	$pais        = $result["pais"];
	$descripcion = $result["descripcion"];
	$activo      = $result["status"];

	}else{

	$titulo      = "Agregar  $titulo";
	$codigo      = "";
	$cod_pais    = "";
	$pais        = " Seleccione... ";
	$descripcion = "";
	$activo      = 'T';

	}
?>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add">
  <fieldset class="fieldset">
  <legend> <?php echo $titulo;?> </legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="input01"><input type="text" name="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo;?>" />
               Activo: <input name="activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" />
              <img src="imagenes/ok.gif" title="Valid" alt="Valid" class="validMsg" border="0"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
	 </tr>
    <tr>
      <td class="etiqueta">Pais:</td>
      	<td id="select01"><select name="pais" style="width:250px">
							<option value="<?php echo $cod_pais;?>"><?php echo $pais;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM paises
		                      WHERE status = 'T' AND codigo <> '$cod_pais' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
    <tr>
      <td class="etiqueta"><?php echo $leng['estado']?>: </td>
      <td id="input02"><input type="text" name="descripcion" maxlength="60" style="width:300px"
                              value="<?php echo $descripcion;?>"/>
		   <img src="imagenes/ok.gif" title="Valid" alt="Valid" class="validMsg" border="0"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
	 <tr>
         <td height="8" colspan="2" align="center"><hr></td>
     </tr>
  </table>
<div align="center"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="submit" name="salvar"  id="salvar" value="Guardar" class="readon art-button" />
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
  		    <input name="metodo" id="metodo" type="hidden"  value="<?php echo $metodo;?>" />
  		    <input name="proced" id="proced" type="hidden"  value="<?php echo $proced;?>" />
            <input name="tabla" id="tabla" type="hidden"  value="<?php echo $tabla;?>" />
            <input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>" />
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>
</div>
  </fieldset>
  </form>
  <script type="text/javascript">
var input01  = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
var input02  = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
</script>
