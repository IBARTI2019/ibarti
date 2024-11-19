<?php
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require_once("../".class_bd);
require_once('../bd/class_mysql2.php');

$bd   = new DataBase();
$bd2  = new DataBase2();

	   $sql01 =	" SELECT ficha_huella.cedula, ficha_huella.huella,
                         ficha_huella.cod_us_ing, ficha_huella.fec_us_ing,
                         ficha_huella.cod_us_mod, ficha_huella.fec_us_mod
                    FROM ficha_huella ORDER BY 1 DESC ";	

?><table width="99%" border="0" align="center"><tr class="fondo02">
			<td width="20%" id="input01_3"><span class="etiqueta">Cedula:</span><br /><input type="text" id="cedula" name="cedula" 
              style="width:150px" /><input type="hidden" id="cedula_old" name="cedula_old"/></td>
            <td width="5%" class="etiqueta"><img src="imagenes/buscar.bmp" onclick="BuscarDatos('cedula')" width="22px" height="22px"  class="imgLink"/></td>                                   
		
            <td width="35%" class="etiqueta" id="input02_3"> Huella:<br /><input type="text" id="huella" name="huella" 
                          style="width:350px" maxlength="64"/><input type="hidden" id="huella_old" name="huella_old"/></td>
			      <td width="5%" class="etiqueta"><img src="imagenes/buscar.bmp" onclick="BuscarDatos('huella')" width="22px" height="22px" class="imgLink" /></td>
				  <td width="10%"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button"  name="submit" id="submit" value="Ingresar"  class="readon art-button" 
                           onclick=" validarCedula('agregar','')"/>
             </span></td>
			 <td width="25%" class="etiqueta">Huellas Nuevas: <br /><select name="huella_new" id="huella_new" style="width:180px;" onchange="huellaX(this.value)"><option value="TODOS">TODOS</option><?php 
					
		$sql_ch = "	SELECT v_ch_huella.huella_asc, v_ch_huella.fecha FROM v_ch_huella ORDER BY fecha DESC ";
		$query_ch  = $bd2->consultar($sql_ch) or die ("error ch");
		while ($datos_ch=$bd2->obtener_fila($query_ch,0)){

					$huella = $datos_ch[0];
					$sql02 = " SELECT COUNT(ficha_huella.huella) 
								 FROM ficha_huella
								WHERE ficha_huella.huella = '$huella' ";
					$query02 = $bd->consultar($sql02); 		   
					$row02=$bd->obtener_fila($query02,0);
					if($row02[0] == 0){
						echo '<option value="'.$datos_ch[0].'">'.$datos_ch[1].'('.$datos_ch[0].')</option>';						   									
						}
			   }?></select></td>     
                
 		</tr><?php       
        $query = $bd->consultar($sql01); 		   
        $i =0;
        $valor = 0;
  		while($datos=$bd->obtener_fila($query,0)){	
		$i++;
			if ($valor == 0){
				$fondo = 'fondo01';
				$valor = 1;
			}else{
				 $fondo = 'fonddo02';
				 $valor = 0;
			}
			
			$modificar = 	 "'modificar', '".$i."'";				 
			$borrar    = 	 "'eliminar', '".$i."'";
        echo '<tr class="'.$fondo.'"> 
                  <td colspan="2"><input type="text" id="cedula'.$i.'" style="width:150px" maxlength="20" 
				             value="'.$datos['cedula'].'"/><input type="hidden" id="cedula_old'.$i.'"
				             value="'.$datos['cedula'].'"/>
				  </td> 
                  <td td colspan="3"><input type="text" id="huella'.$i.'" style="width:450px" maxlength="120" 
				             value="'.$datos['huella'].'"/><input type="hidden" id="huella_old'.$i.'" maxlength="64" value="'.$datos['huella'].'"/></td>
		  </td><td align="center" colspan="2"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null" onclick="ValidarSubmit('.$modificar.')" class="imgLink"/><img src="imagenes/borrar.bmp" alt="Detalle" title="Borrar Registro" width="20px" height="20px" border="null" 
			   onclick="Borrar('.$borrar.')" class="imgLink" /></td> 								
	</tr>';
        } mysql_free_result($query);?></table>