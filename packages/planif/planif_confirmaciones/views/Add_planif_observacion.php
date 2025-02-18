<script language="javascript">
  $("#add_planif_ap_ing").on('submit', function(evt) {
    evt.preventDefault();
 
  });
</script>

<?php
require "../modelo/confirmaciones_modelo.php";
require "../../../../" . Leng;

$confirmaciones   = new Confirmaciones;
$codigo     = $_POST['codigo'];
$edit     = $_POST['edit'];
$confirmado     = $_POST['confirmado'];
$result  =  $confirmaciones->get_data_base($codigo);

echo '
<br>
<table width="100%" border="0" align="center">
			<tr>
                <td align="left">'.$leng["trabajador"].': '.$result["ap_nombre"].'  ('.$result['cod_ficha'].') </td>
				<td align="left">Cargo: '.$result["cargo"].'</td>
				<td align="left">Hora de entrada: '.$result["hora_entrada"].'</td>
			</tr>
            <tr>
       			<td align="left">'.$leng["cliente"].': '.$result["cliente"].'</td>
				<td align="left" colspan="2">'.$leng["ubicacion"].': '.$result["ubicacion"].'</td>
            </tr>
</table>
<br>
<br>';

echo '<table width="100%">
<tr align="center">
    <td>
            <label> Observación: </label> <br><br>
        <div align="center">
            <textarea name="observacion" id="observacion'.$codigo.'" cols="120" rows="6">'.$result["observacion"].'</textarea>
        </div>
    </td>
</tr>';

if($confirmado == false){
    echo '<tr align="center">

        <td>
            <br>
            <br>
            <div align="center">
                <span class="art-button-wrapper" id="boton_guardar_observacion">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button" value="Guardar" class="readon art-button" onclick="savePlanifObservation('.$codigo.','.$edit.')" />
                </span>&nbsp;
                <img id="loading_observacion" src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px" style="display: none;">
            </div>
            <br>
            <br>
        </td>
    </tr>'; 
}

echo '</table>';
