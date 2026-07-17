<?php
$codigo = $_SESSION['usuario_cod'];
$sql  = " SELECT men_usuarios.asistencia_orden  FROM men_usuarios
           WHERE men_usuarios.codigo = '$codigo' ";

$query = $bd->consultar($sql);
$datos = $bd->obtener_fila($query,0);
$proced      = "p_usuario"; 
$metodo      = "nomina"; 
?>
<form action="autentificacion/sc_usuarios.php" method="post" name="mod_usuarios" id="mod_usuarios"> 
     <table width="80%" align="center">
       <tr valign="top">                    
		     <td height="23" colspan="2" class="etiqueta_title" align="center">MODIFICAR <?php echo $titulo;?></td>
         </tr>
         <tr><td height="8" colspan="2" align="center"><hr></td></tr>			 
      <tr>

    <tr>
      <td class="etiqueta">Asistencia Orden: </td>
      	<td id="select_2_01"><select name="as_orden" style="width:200px">
                                     <option value="<?php echo $datos[0];?>"><?php  echo Asistencia_orden($datos[0]);?></option>
						             <option value="`asistencia`.`cod_ficha`"> Ficha </option>
                                     <option value="`ficha`.`cedula`"> Cedula </option>
                                     <option value="trabajador"> Trabajador </option>
                                     <option value="cliente"> Cliente </option>
                                     <option value="ubicacion"> Ubicacion </option>
 
        
        </select><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>       	        	    
	 </tr>
	 <tr> 
            <td height="8" colspan="2" align="center"><hr></td>
       </tr>	
  </table>
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
           <input name="proced" type="hidden"  value="<?php echo $proced;?>" />
		    <input name="metodo" type="hidden"  value="<?php echo $metodo;?>"/>
		    <input name="codigo" type="hidden"  value="<?php echo $codigo;?>" />
            <input name="usuario" type="hidden"  value="<?php echo $usuario;?>" />
	        <input name="cedula" type="hidden"  value=""/>
            <input name="nombre" type="hidden"  value=""/>
            <input name="apellido" type="hidden"  value=""/>
            <input name="email" type="hidden"  value=""/>
            <input name="perfil" type="hidden"  value=""/>
            <input name="status" type="hidden"  value=""/>
            <input name="login" type="hidden"  value=""/>
            <input name="passOLD" type="hidden"  value=""/>
            <input name="password1" type="hidden"  value=""/>
            <input name="password2" type="hidden"  value=""/>
            <input name="r_cliente" type="hidden"  value=""/>
            <input name="r_rol" type="hidden"  value=""/>
            
            <input name="check" type="hidden"  value="N"/>
			<input name="href" type="hidden" value="../inicio.php?area=formularios/index&mod=<?php echo $mod;?>&Nmenu=000"/>
</div>  
</form>
</body>
</html>
<script type="text/javascript">
var select_2_01 = new Spry.Widget.ValidationSelect("select_2_01", {validateOn:["blur", "change"]});
</script>