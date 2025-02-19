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
$asistencia = $_POST['asistencia'];

$result  =  $confirmaciones->get_data_base($codigo);
$observaciones  =  $confirmaciones->get_observaciones();

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
<tr>
    <td>
        <form>
            <label for="observacion_asisto">Observación:</label>
            <select id="observacion_asisto'.$codigo.'" name="observacion_asisto">';
                if($edit == 'true'){
                    if($asistencia == 'true'){
                        echo '<option value="'.$result["cod_observacion_asistencia"].'">'.$result["observacion_asistencia"].'</option>';
                    }else{
                        echo '<option value="'.$result["cod_observacion_asisto"].'">'.$result["observacion_asisto"].'</option>';
                    }
                }else{
                    echo '<option value="">Seleccione</option>';
                }
                foreach ($observaciones as  $observacion) {
                    if($asistencia == 'true'){
                        if($edit == false || $observacion["codigo"] != $result["cod_observacion_asistencia"]){
                            echo '<option value="'.$observacion["codigo"].'">'.$observacion["descripcion"].'</option>';
                        }
                    }else{
                        if($edit == false || $observacion["codigo"] != $result["cod_observacion_asisto"]){
                            echo '<option value="'.$observacion["codigo"].'">'.$observacion["descripcion"].'</option>';
                        }
                    }

                }
            echo '
            </select>
        </form>
    </td>
<tr>
<tr align="center" id="contenedor_observ">
    <td>
        <br>
        <label> Observación adicional: </label> <br><br>
        <div align="center">
            <textarea name="observacion" id="observacion'.$codigo.'" cols="120" rows="6">'.($asistencia == 'true' ? $result["observacion_asistencia_ad"] : $result["observacion"]).'</textarea>
        </div>
    </td>
</tr>';

if($confirmado == false || $confirmado == 'false'){
    echo '<tr align="center">

        <td>
            <br>
            <br>
            <div align="center">
                <span class="art-button-wrapper" id="boton_guardar_observacion">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                    <input type="button" value="Guardar" class="readon art-button" onclick="savePlanifObservation('.$codigo.','.$edit.', '.$asistencia.')" />
                </span>&nbsp;
                <img id="loading_observacion" src="imagenes/loading3.gif" border="null" class="imgLink" width="30px" height="30px" style="display: none;">
            </div>
            <br>
            <br>
        </td>
    </tr>'; 
}

echo '</table>';
