<?php 
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod'].""; 

if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
	$sql = " SELECT $tabla.id, $tabla.descripcion,
	                $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
				    $tabla.status
	           FROM $tabla WHERE id = '$codigo' ";
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);
	  	   
	$codigo      = $result['id'];
	$descripcion = $result['descripcion'];
	$campo01     =  $result['campo01'];
	$campo02     =  $result['campo02'];
	$campo03     =  $result['campo03'];
	$campo04     =  $result['campo04'];
	$status      = $result['status'];
	}else{
	$codigo      = '';		
	$descripcion = '';
	$campo01     = '';
	$campo02     = '';
	$campo03     = '';
	$campo04     = '';
	$status      = 'T';
	}
?>
  <fieldset class="fieldset">
  <legend>DATOS BASICOS <?php echo $titulo;?> </legend>
     <table width="80%" align="center">
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="input01"><input type="text" name="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo;?>" />
        Activo: <input name="activo" type="checkbox"  <?php echo statusCheck("$status");?> value="T" />
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
	 </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n: </td>
      <td id="input02"><input type="text" name="descripcion" maxlength="60" style="width:250px" 
                              value="<?php echo $descripcion;?>"/>
		   <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>	
	 <tr> 
         <td height="8" colspan="2" align="center"><hr></td>
     </tr>	
     <tr> 
         <td colspan="2" align="center">
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
         </td>
   </tr>
  </table>
  		    <input name="metodo" id="metodo" type="hidden"  value="<?php echo $metodo;?>" />
            <input name="tabla" id="tabla" type="hidden"  value="<?php echo $tabla;?>" />            
            <input name="usuario" id="usuario" type="hidden"  value="<?php echo $usuario;?>" />            
	        <input name="href" type="hidden" value="<?php echo $archivo2;?>"/>		   			
  </fieldset>

  <script type="text/javascript">
var input01  = new Spry.Widget.ValidationTextField("input01", "none", {validateOn:["blur", "change"]});
var input02  = new Spry.Widget.ValidationTextField("input02", "none", {validateOn:["blur", "change"]});
</script>