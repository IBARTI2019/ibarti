<?php
mysql_select_db($bd2_cnn,$cnn);

$id = $_GET['codigo'];
$result00 = mysql_query("SELECT descripcion FROM menu 
				  WHERE id = $id", $cnn); 
$row00    = mysql_fetch_array($result00);
?>
<form action="autentificacion/sc_Menu_Modulo.php" method="post" name="mod"> 
  <fieldset class="fieldset">
  <legend>Modificar Menu Modulo:&nbsp;&nbsp;<?php echo $row00[0];?> </legend>
    <table width="800px" border="0" align="center">
      <tr>
	  <?php 
	  ////   CABEZERA
			$query01 = mysql_query("SELECT id, descripcion FROM menu_principal WHERE status = 1
									   AND id != 1
									   AND id != 2
									   AND id != 7
									 ORDER BY orden",$cnn) or die
							      ('<br><h3>Error Consulta # 1:</h3> '.mysql_error().'<br>');
					while($row01=mysql_fetch_array($query01)){
					
	  echo'<td class="etiqueta" width="33%">'.$row01[1].'</td>';

     				}
	 ?>
	  </tr>	
      <tr valign="top">
      <?php 
		$query02 = mysql_query("SELECT id, descripcion FROM menu_principal WHERE status = 1
							   AND id != 1
							   AND id != 2
							   AND id != 7
							 ORDER BY orden",$cnn) or die
						  ('<br><h3>Error Consulta # 1:</h3> '.mysql_error().'<br>');
		while($row02=mysql_fetch_array($query02)){
	    	$campo_id = $row02[0];
			$menu     = $row02[1];
			
		echo'
		<td><table width="100%" border="0" align="center" id="Checkbox'.$campo_id.'">';
        
			$query03 = mysql_query("SELECT id, descripcion FROM menu WHERE id_menu_principal = $campo_id AND status = 1",$cnn) or die
							 ('<br><h3>Error Consulta # 2:</h3> '.mysql_error().'<br>');
					while($row03=mysql_fetch_array($query03))
					{
					
					$campo_id2 = $row03[0];
					$query04=mysql_query("SELECT modulo 
					                        FROM sub_menu 
									  	   WHERE id_menu = $campo_id2 
										     AND modulo  = $id",$cnn);
					
					if (mysql_num_rows($query04)==0){	
					$checkX ='';
					}else{
					$checkX = 'checked="checked"';
					}						
			echo' 		
				<tr>
					<td width="80%" class="texto">'.$row03[1].'</td>
					<td width="15%"><input name="menu[]" type="checkbox" value="'.$row03[0].'" style="width:auto" '.$checkX.'/></td>	
			        <td width="5%">&nbsp;</td>
				</tr>';
					}
			echo'
				<tr>
					<td colspan="3"> 
				    </td>
				</tr>
        </table></td>';
		}?>
      </tr>
    </table>

  </fieldset>
	 <br />
     <div align="center">

      		<input  type="submit" name="salvar"  id="salvar" value="Guardar" class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">	&nbsp;
		    <input type="reset"     id="limpiar"  value="Restablecer" class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">	&nbsp;
		    <input type="button"   id="volver"  value="Volver" onClick="history.back(-1);"  class="button1"
			                       onMouseOver="Fondos (this.id ,'A',  'button1Act', 'button1')" 
	                               onMouseOut="Fondos (this.id ,'D', 'button1Act', 'button1')">
		    <input name="archivo" type="hidden"  value="modificar"/>
		    <input name="id" type="hidden"  value="<?php echo $id;?>" />
	        <input name="href" type="hidden" value="../inicio.php?area=autentificacion/Cons_Menu&Nmenu=<?php echo $_GET['Nmenu']?>"/>	   			
  </div>
</form>	