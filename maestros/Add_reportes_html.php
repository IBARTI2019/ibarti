	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/js/sample.js"></script>
  	<link rel="stylesheet" href="ckeditor/samples/css/samples.css">
<?php 
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod'].""; 
$proced      = "p_reporte_html"; 
if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
	$sql = " SELECT men_reportes_html.descripcion, men_reportes_html.cod_modulo,
                    men_modulos.descripcion AS modulo, men_modulos.vista,
					men_reportes_html.html, men_reportes_html.orden,
                    men_reportes_html.campo01, men_reportes_html.campo02,
                    men_reportes_html.campo03, men_reportes_html.campo04,
					men_reportes_html.cod_us_mod, men_reportes_html.fec_us_mod,
                    men_reportes_html.`status`
               FROM men_reportes_html , men_modulos
              WHERE men_reportes_html.codigo =  '$codigo'
                AND men_reportes_html.cod_modulo = men_modulos.codigo ";
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);

	$titulo       = "MODIFICAR $titulo";	  	   
	$cod_modulo   = $result["cod_modulo"];
	$modulo       = $result["modulo"];
    $vista        = $result["vista"];
	$orden        = $result["orden"];
	$descripcion  = $result["descripcion"];
 	$doc_html     = $result["html"];
	$activo       = $result["status"];
	$disabled     = " ";

	}else{

	$titulo      = "AGREGAR  $titulo";	
	$codigo      = "";		
	$cod_modulo  = "";
	$modulo      = " Seleccione... ";
    $vista       = "";
	$orden       = "100";
	$descripcion = "";
	$doc_html    = "";	
	$activo      = 'T';
	$disabled    = " disabled ";
	}
?>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add"> 
     <table width="100%" align="center">
    <tr>
    <tr><td colspan="8" class="etiqueta_title" align="center"> <?php echo $titulo;?></td><tr>
     <tr>
    	<td colspan="8"><hr /></td>
    </tr>        
      <td width="10%" class="etiqueta">C&oacute;digo:</td>
      <td width="16%" id="input01"><input type="text" name="codigo" maxlength="11" style="width:60px" value="<?php echo $codigo;?>" readonly="readonly" />
               Activo: <input name="activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" /><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
      <td width="10%" class="etiqueta">Modulo:</td>
      	<td width="12%" id="select01"><select name="modulo" style="width:120px" onchange="Add_ajax01(this.value, 'ajax/Add_rp_html_modulo.php', 'select02')">
							<option value="<?php echo $cod_modulo;?>"><?php echo $modulo;?></option>
          <?php  	$sql = " SELECT men_modulos.codigo, men_modulos.descripcion
                               FROM men_modulos WHERE men_modulos.`status` = 'T'
                              ORDER BY orden ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
        <td width="10%" class="etiqueta">CAMPOS: </td>
      	<td width="12%" id="select02"><select name="campos" style="width:120px" <?php echo $disabled;?> >
							<option value="">CAMPOS DISPONIBLES... </option>
          <?php  	$sql = "  SHOW COLUMNS FROM $vista ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo '$'.$datos[0];?></option>
          <?php }?>		  	  
        </select><br /><span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
      <td width="10%" class="etiqueta">Descripcion: </td>
      <td width="12%" id="input03"><input type="text" name="descripcion" maxlength="120" style="width:120px" 
                              value="<?php echo $descripcion;?>"/><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
     <tr>
    	<td colspan="8"><hr /></td>
    </tr>    
    <tr>
    	<td colspan="8"><div class="adjoined-bottom">
		<div class="grid-container">
			<div class="grid-width-100" >
				 <textarea id="editor" name="doc_html" required> <?php echo $doc_html;?></textarea>
			</div>
		</div>
	</div></td>
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
            <input name="orden" type="hidden" maxlength="3" style="width:60px" value="<?php echo $orden;?>"/>
</div>         
  </form>
  <script type="text/javascript">


var input03  = new Spry.Widget.ValidationTextField("input03", "none", {validateOn:["blur", "change"]});

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false});

</script>

<script>
	initSample();
</script>