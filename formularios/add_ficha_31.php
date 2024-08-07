<?php 
// require_once('autentificacion/aut_verifica_menu.php');
$archivo = "pestanas/add_ficha&Nmenu=".$Nmenu."&codigo=".$codigo."&mod=$mod&pagina=0&metodo=modificar";
	$proced      = "p_fichas"; 
if($metodo == 'modificar'){
	$bd = new DataBase();
	$sql = " SELECT v_ficha.cod_ficha, v_ficha.cedula,
					v_ficha.nombres, v_ficha.fec_nacimiento,
					v_ficha.sexo, v_ficha.telefono,
					v_ficha.direccion, v_ficha.fec_psic,
					v_ficha.psic_apto, v_ficha.fec_pol,
					v_ficha.pol_apto, v_ficha.cod_cargo,
	                v_ficha.cod_estado, v_ficha.estado, 
                    v_ficha.cod_ciudad, v_ficha.ciudad,					
					v_ficha.cod_t_camisas, v_ficha.t_camisas,
                    v_ficha.cod_t_pantalon, v_ficha.t_pantalon,
                    v_ficha.cod_n_zapatos, v_ficha.n_zapatos,
					v_ficha.cargo, v_ficha.cod_contracto,
					v_ficha.contracto, v_ficha.cod_n_contracto,
					v_ficha.n_contracto,v_ficha.cod_rol,
					v_ficha.rol, v_ficha.cod_region,
					v_ficha.region, v_ficha.cod_banco,
					v_ficha.banco, v_ficha.cta_banco,
					v_ficha.carnet, v_ficha.fec_carnet,
					v_ficha.fec_ingreso, v_ficha.fec_profit,
					v_ficha.fec_contracto,
					v_ficha.cod_us_ing, v_ficha.fec_us_ing,
					v_ficha.cod_us_mod, v_ficha.fec_us_mod,
					v_ficha.campo01, v_ficha.campo02,
					v_ficha.campo03, v_ficha.campo04,
					v_ficha.cod_ficha_status, v_ficha.`status`
			   FROM v_ficha  
			  WHERE v_ficha.cod_ficha = '$codigo'";
	$query  = $bd->consultar($sql);
	$result = $bd->obtener_fila($query,0);

	$cedula         = $result['cedula'];
	$cod_ficha      = $codigo;
	$nombres        = $result['nombres'];
	$fec_nacimiento = conversion($result['fec_nacimiento']);
	$carnet         = $result['carnet'];
	$fec_venc_carnet = conversion($result['fec_carnet']);
	$fec_ingreso    = conversion($result['fec_ingreso']);
	$sexo           = $result['sexo'];
	$telefono       = $result['telefono'];
	$direccion      = $result['direccion'];
	$cod_estado     = $result['cod_estado'];
	$estado         = $result['estado'];		
	$cod_ciudad     = $result['cod_ciudad'];
	$ciudad         = $result['ciudad'];
	$cod_cargo      = $result['cod_cargo'];
	$cargo          = $result['cargo'];		
    $cod_contracto  = $result['cod_contracto'];
	$contracto      = $result['contracto'];		
	$fec_venc_contracto = conversion($result['fec_contracto']);
	$cod_n_contracto  = $result['cod_n_contracto'];
	$n_contracto    = $result['n_contracto'];
	$cod_rol        = $result['cod_rol'];
	$rol            = $result['rol'];
	$cod_region     = $result['cod_region'];	
	$region         = $result['region'];
	$cod_banco      = $result['cod_banco'];
	$banco          = $result['banco'];
	$cta_banco      = $result['cta_banco'];
	
	$fec_psic       = conversion($result['fec_psic']);
	$psic_apto      = $result['psic_apto'];
	$fec_pol        = conversion($result['fec_pol']);
	$pol_apto       = $result['pol_apto'];
	$cod_status     = $result['cod_ficha_status'];
	$status         = $result['status'];	
	$fec_us_ing     = 
	
	$fec_profit     = conversion($result['fec_profit']);
	$fec_us_ing     = conversion($result['fec_us_ing']);
	$fec_us_mod     = conversion($result['fec_us_mod']);

	// Dotacion  
	$cod_t_pantalon = $result['cod_t_pantalon'];
	$t_pantalon     = $result['t_pantalon'];
	$cod_t_camisa   = $result['cod_t_camisas'];
	$t_camisa       = $result['t_camisas'];
	$cod_n_zapato   = $result['cod_n_zapatos'];
	$n_zapato       = $result['n_zapatos'];

// PARTE adiccional	
	$campo01    = $result["campo01"];
	$campo02    = $result["campo02"];
	$campo03    = $result["campo03"];
	$campo04    = $result["campo04"];

	}else{
// DEBE SELECCIONAR UNA FICHA
	}
$sql_ciudad = " SELECT codigo, descripcion FROM ciudades WHERE cod_estado = '$cod_estado'
			       AND codigo <> '$cod_ciudad' ORDER BY descripcion ASC ";
	?>
<form action="scripts/sc_ficha.php" method="post" name="add" id="add" enctype="multipart/form-data"> 
  <fieldset class="fieldset">
  <legend>Modificar Datos B&aacute;sicos Trabajadores </legend>
     <table width="80%" align="center">
   <tr>
      <td class="etiqueta">&nbsp;</td>
      <td>&nbsp;</td>
      <td rowspan="10" align="center">
	  <?php 
	
	    $filename = "imagenes/fotos/".$cedula.".jpg";	
	 //   $filename = "imagenes/fotos/".$codigo.".jpg";	 

	  if (file_exists($filename)) {
 		   echo '<img src="'.$filename.'?nocache='.time().'" width="110px" height="130px" />';
		} else {
		   echo '<img src="imagenes/img_no_disp.png" width="110px" height="130px"/>';
		} ?>      
     </td>
</tr>
 
    <tr>
      <td class="etiqueta">cedula:</td>
      <td id="input01"><input type="text" name="cedula" maxlength="12" style="width:120px" readonly="readonly" value="<?php echo $cedula;?>"/>
      <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>		      
    <tr>
      <td class="etiqueta">N. Ficha:</td>
      <td id="input02"><input type="text" name="cod_ficha" maxlength="12" style="width:120px"  readonly="readonly" value="<?php echo $cod_ficha;?>"/>
      <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/> <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 2 caracteres.</span></td>
	 </tr>
    </tr>
    <tr>
      <td class="etiqueta">Apellidos y Nombres:</td>
      <td id="input03"><input type="text" name="nombres" maxlength="60" style="width:250px"
                              value="<?php echo $nombres;?>"/>
        <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/> <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
     </tr>		 
	 <tr>
      <td class="etiqueta">Fecha de Nacimiento:</td>
    <td id="fecha02">
          	<input type="text" name="fecha_nac" value="<?php echo $fec_nacimiento;?>" />
            <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Fotos Carnet (jpeg):</td>
      <td id="input07"><input type="file" name="file" id="file" style="width:355px"/>
        <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/> <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 4 caracteres.</span></td>
	 </tr>		  
    <tr>
      <td class="etiqueta">Posee Carnet:</td>
      <td id="radio01" class="texto">SI	
            <input type = "radio" name="carnet"  value = "S" style="width:auto" <?php echo CheckX($carnet, 'S') ?> />
          NO<input type = "radio" name="carnet"  value = "N" style="width:auto" <?php echo CheckX($carnet, 'N') ?> />															 	         <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/>
            <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
        </td>
    </tr>
    <tr>
      <td class="etiqueta">Fecha Vencimiento Carnet:</td>
      <td id="fecha04">
          	<input type="text" name="fec_venc_carnet" value="<?php echo $fec_venc_carnet;?>"/>
            <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Fecha de Ingreso:</td>
      <td id="fecha01">
          	<input type="text" name="fec_ingreso" value="<?php echo $fec_ingreso;?>"/>
            <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Sexo:</td>
      <td id="radio02" class="texto">
	  <img src="imagenes/femenino.gif" width="25" height="15" />
            <input type = "radio" name="sexo"  value = "F" style="width:auto" <?php echo CheckX($sexo, 'F');?> />
            <img src="imagenes/masculino.gif" width="25" height="15" />
            <input type = "radio" name="sexo"  value = "M" style="width:auto" <?php echo CheckX($sexo, 'M');?> />															 	         <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/>
            <span class="radioRequiredMsg">Debe seleccionar un Sexo.</span>
        </td>
    </tr>
    <tr>
      <td class="etiqueta">Tel&eacute;fono:</td>
      <td id="telefono01">
      <input type="texto" name="telefono" style="width:300px" value="<?php echo $telefono;?>" maxlength="55"/>
      <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br /> 
      <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir m&aacute;s de 11 caracteres.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Direcci&oacute;n De Habitaci&oacute;n:</td>
      <td id="textarea01"><textarea  name="direccion" cols="45" rows="3"><?php echo $direccion;?></textarea>
        <span id="Counterror_mess1" class="texto">&nbsp;</span> 
        <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
        <span class="textareaRequiredMsg">El Campo es Requerido.</span>
        <span class="textareaMinCharsMsg">Debe Escribir mas de 10 caracteres.</span>
        <span class="textareaMaxCharsMsg">El maximo de caracteres permitidos es 255.</span></td>
    </tr>   
    <tr>
      <td class="etiqueta">Estados:</td>
      	<td id="select01"><select name="estado" style="width:250px" onchange="Add_ajax01(this.value, 'ajax/Add_ciudad.php', 'ciudad')">
							<option value="<?php echo $cod_estado;?>"><?php echo $estado;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM estados, control WHERE status = 'T' 
                                AND control.cod_pais = estados.cod_pais
                                AND codigo <> '$cod_estado'
                           ORDER BY 2 ASC ";
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
      <td class="etiqueta">Ciudades:</td>
      	<td id="ciudad"><select name="ciudad" style="width:250px">
						<option value="<?php echo $cod_ciudad;?>"><?php echo $ciudad;?></option>
          <?php     $query = $bd->consultar($sql_ciudad);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
   <tr>
      <td class="etiqueta">Cargo:</td>
      	<td id="select02"><select name="cargo" style="width:220px">
							<option value="<?php echo $cod_cargo;?>"><?php echo $cargo; ?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM cargos 
		                      WHERE status = 'T' AND codigo <> '$cod_cargo' ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){	
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>		  	  
        </select>        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	</tr>
   <tr>
      <td class="etiqueta">Contrato:</td>
      	<td id="select03"><select name="contracto" style="width:220px">
							<option value="<?php echo $cod_contracto;?>"><?php echo $contracto; ?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM contractos 
		                      WHERE status = 'T' AND codigo <> '$cod_contracto' ORDER BY 2 ASC ";
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
      <td class="etiqueta">Numeros Contracto:</td>
      	<td id="select04"><select name="n_contracto" style="width:220px">
							<option value="<?php echo $cod_n_contracto;?>"><?php echo $n_contracto; ?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM ficha_n_contracto 
		                      WHERE status = 'T' AND codigo <> '$cod_n_contracto' ORDER BY 2 ASC ";
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
      <td class="etiqueta">Fecha Venc. Contracto:</td>
    <td id="fecha05">
          	<input type="text" name="fec_venc_contracto" value="<?php echo $fec_venc_contracto;?>" />
            <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/><br />
            <span class="textfieldRequiredMsg">La Fecha Es Requerida.</span>
            <span class="textfieldInvalidFormatMsg">El Formato Es Invalido</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Roles:</td>
      	<td id="select05"><select name="rol" style="width:220px">
							<option value="<?php echo $cod_rol;?>"><?php echo $rol;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM roles 
		                      WHERE status = 'T' AND codigo <> '$cod_rol' ORDER BY 2 ASC ";
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
      <td class="etiqueta">Region:</td>
      	<td id="select06"><select name="region" style="width:220px">
							<option value="<?php echo $cod_region;?>"><?php echo $region;?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM regiones 
		                      WHERE status = 'T' AND codigo <> '$cod_region' ORDER BY 2 ASC ";
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
      <td class="etiqueta">Banco:</td>
      	<td id="select07"><select name="banco" style="width:220px">
							<option value="<?php echo $cod_banco; ?>"><?php echo $banco; ?></option>
          <?php  	$sql = " SELECT codigo, descripcion FROM bancos WHERE status = 'T' 
		                        AND codigo <> '$cod_banco' ORDER BY 2 ASC ";
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
      <td class="etiqueta">Cta. Banco:</td>
      <td id="input05"><input type="text" name="cta_banco" maxlength="20" style="width:200px" 
                              value="<?php echo $cta_banco;?>" />
        <img src="imagenes/ok.gif" alt="Valido" class="validMsg" border="0"/> <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span> 
        <span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span></td>
	 </tr>		 	  
    <tr>
      <td class="etiqueta">Fecha Elaboracion Examen Psicologico:</td>
      <td>
			<span id="fecha03">	
          	<input type="text" name="fec_psi" value="<?php echo $fec_psic;?>" /> 
			</span>
	  </td>
    </tr>             
    <tr>
      <td class="etiqueta">Examen Psicologico:</td>
      <td class="texto">
			<span id="radio02">&nbsp;&nbsp; Apto             
			<input name="psi_apto" type="radio" value="S" style="width:auto" <?php echo CheckX($psic_apto, 'S');?> 
                   disabled="disabled"/>&nbsp;&nbsp; No Apto 
			<input name="psi_apto" type="radio" value="N" style="width:auto" <?php echo CheckX($psic_apto, 'N');?> 
                   disabled="disabled"/>&nbsp;&nbsp; Condicional
			<input name="psi_apto" type="radio" value="C" style="width:auto" <?php echo CheckX($psic_apto, 'C');?> 
                   disabled="disabled" />&nbsp;&nbsp; Indefinido
            <input name="psi_apto" type="radio" value="I" style="width:auto" <?php echo CheckX($psic_apto, 'I');?> 
                   disabled="disabled" />        
      			</span>
            <input type="hidden" name="psi_apto" value="<?php echo $psic_apto;?>" />
	  </td>
    </tr>                 
	<tr>
      <td class="etiqueta">Fecha de Chequeo Policial:</td>
      <td>
		  <span id="fecha04">
          	<input type="text" name="fec_pol" value="<?php echo $fec_pol;?>" readonly="true"/> 
			</span>
      </td>
    </tr>	
	<tr>
      <td class="etiqueta">Chequeo Policial:</td>
      <td class="texto">
			<span id="radio03">&nbsp;&nbsp; Apto
			<input name="pol_apto" type="radio"  value="S" style="width:auto" <?php echo CheckX($pol_apto, 'S');?> 
                   disabled="disabled"/>&nbsp;&nbsp; No Apto 
			<input name="pol_apto"  type="radio" value="N" style="width:auto" <?php echo CheckX($pol_apto, 'N');?> 
                   disabled="disabled" />&nbsp;&nbsp; Indefinido
			<input name="pol_apto"  type="radio" value="I" style="width:auto" <?php echo CheckX($pol_apto, 'I');?> 
                   disabled="disabled" />
			<span class="radioRequiredMsg">Seleccione...</span>
			</span>
            <input type="hidden" name="pol_apto" value="<?php echo $pol_apto;?>" />
      </td>
    </tr>	    
    <tr>
      <td class="etiqueta">Fecha Sistema De Integraci&oacute;n:</td>
      <td id="fecha05">
	      	<input type="text" name="fec_profit" value="<?php echo $fec_profit;?>" readonly="true"/>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Fecha De Sistema:</td>
      <td id="fecha06">
          	<input type="text" name="fecha_sistema" value="<?php echo $fec_us_ing;?>" readonly="true" />
       </td>
    </tr>
    <tr>
      <td class="etiqueta">Fecha De Ultima Actualizacion:</td>
      <td id="fecha07">
          	<input type="text" name="fec_act" value="<?php echo $fec_us_mod;?>" readonly="true" />
       </td>
    </tr>	
	<tr>
	     <td height="20" class="etiqueta">Status:</td>
	     <td  id="select10">		    
			   <select name="status" style="width:200px;">
			   		   <option value="<?php echo $cod_status;?>"><?php echo $status;?></option>  	  
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
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
		    <input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
            <input name="pestana" type="hidden"  value="ficha" />
		    <input name="codigo" type="hidden"  value="<?php echo $codigo;?>" />
	        <input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>"/>
			<input type="hidden"  name="usuario" id="usuario" value="<?php echo $usuario;?>"/>		
            <input name="proced" id="proced" type="hidden"  value="<?php echo $proced;?>" /> 
</div>
  </fieldset>
</form>
<script type="text/javascript">

var input01 = new Spry.Widget.ValidationTextField("input01", "none", {minChars:4, validateOn:["blur", "change"]});
var input02 = new Spry.Widget.ValidationTextField("input02", "none", {minChars:2, validateOn:["blur", "change"]});
var input03 = new Spry.Widget.ValidationTextField("input03", "none", {minChars:3, validateOn:["blur", "change"], isRequired:false});
//var input04 = new Spry.Widget.ValidationTextField("input04", "none", {minChars:4, validateOn:["blur", "change"], isRequired:false}); 
var input05 = new Spry.Widget.ValidationTextField("input05", "integer", {minChars:20, validateOn:["blur", "change"],isRequired:false});

var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
    validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});
	
var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
    validateOn:["blur", "change"], useCharacterMasking:true});
var fecha03 = new Spry.Widget.ValidationTextField("fecha03", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
    validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});
var fecha04 = new Spry.Widget.ValidationTextField("fecha04", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
    validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});		

var fecha05 = new Spry.Widget.ValidationTextField("fecha05", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA", 
    validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});			

var radio01 = new Spry.Widget.ValidationRadio("radio01", { validateOn:["change", "blur"]});
var radio02 = new Spry.Widget.ValidationRadio("radio02", { validateOn:["change", "blur"]});

var telefono01  = new Spry.Widget.ValidationTextField("telefono01", "none", {minChars:11, validateOn:["blur", "change"]});

var textarea01 = new Spry.Widget.ValidationTextarea("textarea01", {maxChars:255, validateOn:["blur", "change"], counterType:"chars_count", counterId:"Counterror_mess1", useCharacterMasking:false});

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});
var select02 = new Spry.Widget.ValidationSelect("select02", {validateOn:["blur", "change"]});
var select03 = new Spry.Widget.ValidationSelect("select03", {validateOn:["blur", "change"]});
var select04 = new Spry.Widget.ValidationSelect("select04", {validateOn:["blur", "change"]});
var select05 = new Spry.Widget.ValidationSelect("select05", {validateOn:["blur", "change"]});
var select06 = new Spry.Widget.ValidationSelect("select06", {validateOn:["blur", "change"]});
var select07 = new Spry.Widget.ValidationSelect("select07", {validateOn:["blur", "change"]});

var select10 = new Spry.Widget.ValidationSelect("select10", {validateOn:["blur", "change"]});
</script>