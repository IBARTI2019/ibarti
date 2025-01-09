<?php
	$Nmenu = 304;
	require_once('autentificacion/aut_verifica_menu.php');
	$proced      = "p_concepto";
    $metodo      = $_GET['metodo'];
	$archivo     = $_GET['archivo'];
	$mod         = $_GET['mod'];

	$archivo2 = "maestros/Cons_$archivo&Nmenu=".$Nmenu."&mod=$mod&metodo=modificar";

if($metodo == 'modificar'){
	$codigo = $_GET['codigo'];
	$bd = new DataBase();
	$sql = " SELECT conceptos.codigo, conceptos.descripcion AS concepto,
	                conceptos.abrev,
					conceptos.cod_horario, horarios.nombre AS horario,
                    conceptos.asist_perfecta,  conceptos.asist_diaria,
					conceptos.status, conceptos.valor
               FROM conceptos ,  horarios
              WHERE conceptos.cod_horario = horarios.codigo
			    AND conceptos.codigo = '$codigo' ";
	$query = $bd->consultar($sql);
	$result=$bd->obtener_fila($query,0);

	$descipcion     = $result['concepto'];
	$abrev          = $result['abrev'];
	$cod_horario    = $result['cod_horario'];
	$horario        = $result['horario'];
	$asist_diaria   = $result['asist_diaria'];
	$asist_perfecta = $result['asist_perfecta'];

    $valor       = $result['valor'];
	$activo      = $result['status'];

	}else{
	$codigo         = '';
	$descipcion     = '';
	$abrev          = '';
	$cod_horario    = '';
	$horario        = 'Seleccione...';
	$cod_asist_diaria = '';
	$asist_diaria = 'Seleccione...';
	$cod_asist_perfecta = '';
	$asist_perfecta = 'Seleccione...';
	$cod_semana   = '';
	$semana       = ' Seleccione... ';
    $valor        = '';
	$cod_status   = '';
	$activo       = 'T';
	}
?>
<script language="javascript">
    function actualizarEquivalencia(concepto_equivalente) {
        var usuario = $("#usuario").val();
        var concepto = $("#codigo").val();
        var status = 'F';
        console.log('check' + concepto_equivalente);
        if ($('#check' + concepto_equivalente).is(':checked')) {
            status = 'T';
        }
        var parametros = {
            concepto_equivalente: concepto_equivalente, concepto: concepto, estatus: status, usuario: usuario
        };

        $.ajax({
            data: parametros,
            url: 'packages/general/modelo/procesar_equivalencia_concepto.php',
            type: 'post',
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.error) {
                    toastr.error(resp.mensaje);
                } else {
                    toastr.success('Actualizacion Exitosa!..');
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

    }
</script>

<form action="sc_maestros/Concepto.php" method="post" name="Mod" id="Mod">
     <table width="70%" align="center">
         <tr valign="top">
		     <td height="23" colspan="2" class="etiqueta_title" align="center"> Agregar <?php echo $leng['concepto']?> </td>
         </tr>
         <tr>
            <td height="8" colspan="2" align="center"><hr></td>
         </tr>
    <tr>
      <td class="etiqueta" width="25%">C&oacute;digo:</td>
      <td id="input01" width="75%"><input type="text" name="codigo" id="codigo" maxlength="12" style="width:120px"
                                          value="<?php echo $codigo;?>" />
           Activo: <input name="activo" type="checkbox"  <?php echo statusCheck("$activo");?> value="T" /><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n:</td>
      <td id="input02"><input type="text" name="descripcion" maxlength="60" style="width:250px"
                              value="<?php echo $descipcion;?>"/><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 2 Caracteres.</span></td>
    </tr>
    <tr>
      <td class="etiqueta">Abreviatura:</td>
      <td id="input03"><input type="text" name="abrev" maxlength="10" style="width:115px"
                              value="<?php echo $abrev;?>" /><br>
        <span class="textfieldRequiredMsg">La Descripcion es Requerida.</span>
		<span class="textfieldMinCharsMsg">Debe Escribir m�nimo 1 Caracteres.</span></td>
    </tr>
     <tr>
	     <td class="etiqueta">Horario:</td>
	     <td id="select01">
			   <select name="horario" style="width:200px;">
     				   <option value="<?php echo $cod_horario;?>"><?php echo $horario;?></option>
	        <?php  	$sql = " SELECT codigo, nombre FROM horarios
			                  WHERE status = 'T' AND horarios.codigo <> '$cod_horario'
						   ORDER BY 2 ASC ";
		            $query = $bd->consultar($sql);
            		while($datos=$bd->obtener_fila($query,0)){
		    ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?></select><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
    </tr>
 <tr>
    <td class="etiqueta">Asistencia Perfecta:</td>
    <td id="radio01">SI<input type ="radio" name="asist_perfecta"  value = "T"  style="width:auto" <?php echo CheckX($asist_perfecta, "T");?> /> NO<input type = "radio" name="asist_perfecta"  value = "F" style="width:auto" <?php echo CheckX($asist_perfecta, "F");?> /> <span class="radioRequiredMsg">Debe seleccionar un Campo.</span></td>
</tr>

 <tr>
    <td class="etiqueta">Asistencia Diaria:</td>
    <td id="radio02">SI<input type ="radio" name="asist_diaria"  value = "T"  style="width:auto" <?php echo CheckX($asist_diaria, "T");?> /> NO<input type = "radio" name="asist_diaria"  value = "F" style="width:auto" <?php echo CheckX($asist_diaria, "F");?> /> <span class="radioRequiredMsg">Debe seleccionar un Campo.</span></td>
</tr>

	<tr>
      <td class="etiqueta">Valor:</td>
    <td id="input04"><input type="text" name="valor" maxlength="3" style="width:120px" value="<?php echo $valor;?>" /><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido.</span>
        <span class="textfieldMinCharsMsg">Debe Escribir 20 caracteres.</span></td>
	 </tr>
          <tr>
              <td colspan="2" align="center"><hr></td>
         </tr>
  </table>
  <div align="center"> <span class="art-button-wrapper">
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
            <input type="hidden"  name="proced" value="<?php echo $proced;?>"/>
            <input type="hidden"  name="metodo" value="<?php echo $metodo;?>"/>
            <input type="hidden"  name="usuario" value="<?php echo $usuario;?>" />
			<input type="hidden"  name="href" value="../inicio.php?area=<?php echo $archivo2;?>" />
            <input type="hidden"  name="region" value="" />
            <input type="hidden"  name="categoria" value="" /> 	</div>
</form>
<?php
if($metodo == 'modificar' && $asist_diaria == 'T'){
?>
<br><br><br>
<div align="center" class="etiqueta_title">Equivalencias de rotación</div>
<div id="Cont_conceptosR" class="tabla_sistema">
<?php
    $result =array();
    $sql = " SELECT
            conceptos.codigo,
            conceptos.abrev,
            conceptos.descripcion,
            IFNULL(ppc.cod_concepto_equivalente, 'NO') existe,
            ppc.`status`
        FROM
            conceptos
            LEFT JOIN equivalencias_conceptos ppc ON ppc.cod_concepto = '$codigo'
            AND ppc.cod_concepto_equivalente = conceptos.codigo
        WHERE
            conceptos.`status` = 'T' AND conceptos.codigo != '$codigo'
            AND conceptos.asist_diaria = 'T'
            ORDER BY 2 ASC";
      
    $query         = $bd->consultar($sql);

?>

<table width="90%" border="0" align="center">
	<tr>
		</br>
		<td colspan="4">
			</hr>
		</td>
		</br>
	</tr>
	<tr>
		<th width="15%">Código</th>
        <th width="15%">Abreviatura</td>
		<th width="60%">Descripción</td>
		<th width="10%">Check</td>
	</tr>
	<?php
	$i     = 0;
	while ($datos_det = $bd->obtener_fila($query)) {
		$i++;
		$cod_det     = $datos_det['codigo'];
		if ($datos_det['existe'] != 'NO' && $datos_det['status'] == 'T') {
			$check = 'checked="checked"';
		} else {
			$check = '';
		}
		echo '<tr>
  					<td>' . $i . '<input type="hidden" name="cod_det_concepto" id="cod_det_concepto' . $cod_det . '" value="' . $cod_det . '"></td>
                    <td>' . $datos_det["abrev"] . '</td>
					  <td>' . $datos_det["descripcion"] . '</td>
					  <td><input type="checkbox" id="check' . $cod_det . '" value="' . $cod_det . '" name="check" value="T" ' . $check . ' onclick="actualizarEquivalencia(this.value)"></td>
              </td>
  				</tr>';
	}
	?>
</table>
</div>
<?php
}
?>
<br><br>
</body>
</html>
<script language="javascript" type="text/javascript">
var input01 = new Spry.Widget.ValidationTextField("input01", "none", {minChars:2, validateOn:["blur", "change"]});
var input02 = new Spry.Widget.ValidationTextField("input02", "none", {minChars:2, validateOn:["blur", "change"]});
var input03 = new Spry.Widget.ValidationTextField("input03", "none", {minChars:1, validateOn:["blur", "change"]});
var input04 = new Spry.Widget.ValidationTextField("input04", "real", {validateOn:["blur"], useCharacterMasking:true});

var select01 = new Spry.Widget.ValidationSelect("select01", {validateOn:["blur", "change"]});

var radio01 = new Spry.Widget.ValidationRadio("radio01", { validateOn:["change", "blur"]});
var radio02 = new Spry.Widget.ValidationRadio("radio02", { validateOn:["change", "blur"]});
</script>
