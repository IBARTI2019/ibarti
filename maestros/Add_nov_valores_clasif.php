<?php 
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod'].""; 

if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
 	$sql = " SELECT nov_valores_clasif.codigo, nov_valores_clasif.descripcion,
                    nov_valores_clasif.campo01, nov_valores_clasif.campo02,
                    nov_valores_clasif.campo03, nov_valores_clasif.campo04,
                    nov_valores_clasif.cod_us_ing, nov_valores_clasif.fec_us_ing,
                    nov_valores_clasif.cod_us_mod, nov_valores_clasif.fec_us_mod,
                    nov_valores_clasif.`status`
               FROM nov_valores_clasif		    
              WHERE nov_valores_clasif.codigo = '$codigo'";

	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);

	$titulo         = "MODIFICAR DATOS BASICOS $titulo";	  	   

	
	$descripcion    = $result["descripcion"];
	
	$activo         = $result["status"];

	}else{

	$titulo       = "AGREGAR DATOS BASICOS $titulo";	
	$codigo       = "";		
	
	$descripcion  = "";
	
	$activo       = 'T';
	}
?>
<form action="sc_maestros/sc_<?php echo $archivo;?>.php" method="post" name="add" id="add"> 
  <fieldset class="fieldset">
  <legend> <?php echo $titulo;?> </legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="input01"><input type="text" name="codigo" maxlength="11" size="12" value="<?php echo $codigo;?>" />
               Activo: <input name="activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" /><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
	 </tr>
    
    <tr>
      <td class="etiqueta">Descripcion:</td>
      <td id="input03"><input type="text" name="descripcion" maxlength="60" size="40" value="<?php echo $descripcion;?>" /><br />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
      
    </tr>
	 <tr> 
         <td height="8" colspan="2" align="center"><hr></td>
     </tr>	
  </table>

  </fieldset>
<div align="center">
<span class="art-button-wrapper">
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
            <input name="tabla" id="tabla" type="hidden"  value="<?php echo $tabla;?>" />            
            <input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>" />            
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>			   			
</div>   
  </form>
  <script type="text/javascript">
var input01  = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
var input02  = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});
var input03  = new Spry.Widget.ValidationTextField("input03", "none", {validateOn:["blur", "change"]});

var radio01 = new Spry.Widget.ValidationRadio("radio01", { validateOn:["change", "blur"]});
</script>