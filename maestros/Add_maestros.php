<?php
$metodo = $_GET['metodo'];
$titulo = $_GET['titulo'];
$tabla   = $_GET['tb'];
$archivo = $_GET['archivo'];
$archivo2 = "../inicio.php?area=maestros/Cons_$archivo&Nmenu=" . $_GET['Nmenu'] . "&mod=" . $_GET['mod'] . "";

if ($metodo == 'modificar') {
  $codigo = $_GET['codigo'];
  $bd = new DataBase();
  if ($tabla == 'cargos') {
    $sql = " SELECT $tabla.codigo, $tabla.descripcion,
    $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
    $tabla.status, $tabla.planificable, $tabla.cod_tipo, tipos_cargo.descripcion tipo
    FROM $tabla, tipos_cargo 
    WHERE $tabla.cod_tipo = tipos_cargo.codigo 
    AND $tabla.codigo = '$codigo' ";
    $sql_tipos = "SELECT codigo, descripcion FROM tipos_cargo WHERE status = 'T';";
    $query_tipos = $bd->consultar($sql_tipos);
  } else {
    if ($tabla == 'documentos' || $tabla == 'documentos_cl') {
      $sql = " SELECT $tabla.codigo, $tabla.descripcion, $tabla.orden,
	                $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
				    $tabla.status
             FROM $tabla WHERE codigo = '$codigo' ";
    } else if ($tabla == 'ficha_egreso_motivo') {
      $sql = " SELECT $tabla.codigo, $tabla.motivo, $tabla.descripcion,
	                $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	$tabla.status
             FROM $tabla WHERE codigo = '$codigo' ";
    } else if ($tabla == 'nov_tipo') {
      $sql = " SELECT $tabla.codigo, $tabla.kanban, $tabla.descripcion,
                  $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	$tabla.status
              FROM $tabla WHERE codigo = '$codigo' ";
    } else if ($tabla == 'nov_status_kanban') {
      $sql = " SELECT $tabla.codigo, $tabla.color, $tabla.descripcion,
          $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	$tabla.status, $tabla.inicial, $tabla.anula_vencimiento
          FROM $tabla WHERE codigo = '$codigo' ";
    } else if ($tabla == 'ruta_de_ventas') {
      $sql = " SELECT $tabla.codigo, $tabla.descripcion,$tabla.orden,$tabla.descripcion_global,
	                $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
				    $tabla.status
             FROM $tabla WHERE codigo = '$codigo' ";
    } else {
      $sql = " SELECT $tabla.codigo, $tabla.descripcion,
	                $tabla.campo01, $tabla.campo02, $tabla.campo03, $tabla.campo04,	               
				    $tabla.status
             FROM $tabla WHERE codigo = '$codigo' ";
    }
  }
  $query = $bd->consultar($sql);
  $result = $bd->obtener_fila($query, 0);
  
  $codigo_onblur = "";
  $descripcion = $result['descripcion'];
  $descripcionglobal= $result['descripcion_global'];
  $campo01     = $result['campo01'];
  $campo02     = $result['campo02'];
  $campo03     = $result['campo03'];
  $campo04     = $result['campo04'];
  $status      = $result['status'];
  $kanban = 'F';
  if ($tabla == 'nov_tipo') {
    $kanban      = $result['kanban'];
  }
  if ($tabla == 'cargos') {
    $planificable      = $result['planificable'];
  }
  if ($tabla == 'documentos' || $tabla == 'documentos_cl' || $tabla == 'ruta_de_ventas') {
    $orden      = $result['orden'];
  }
  if ($tabla == 'ficha_egreso_motivo') {
    $motivo      = $result['motivo'];
  }
  if ($tabla == 'nov_status_kanban') {
    $color      = $result['color'];
    $inicial = $result['inicial'];
    $anula_vencimiento = $result['anula_vencimiento'];
  }
  $readonly = 'readonly="readonly"';
} else {
  $codigo="";
  if ($tabla == 'ruta_de_ventas') {
    $sql_tipos = "SELECT max(codigo) as Codigo FROM ruta_de_ventas WHERE codigo > 0 ;";
    $query_tipos = $bd->consultar($sql_tipos);
    $result = $bd->obtener_fila($query_tipos, 0);
    $codigo = $result['Codigo'] + 1;

  }
  if ($tabla == 'cargos') {
    $sql_tipos = "SELECT codigo, descripcion FROM tipos_cargo WHERE status = 'T';";
    $query_tipos = $bd->consultar($sql_tipos);
  }
  $readonly = '';
  $orden = '';
  $codigo_onblur = "Add_ajax_maestros(this.value, 'ajax/validar_maestros.php', 'Contenedor', '$tabla')";
  $codigo_orden = "Add_ajax_maestros(this.value, 'ajax/validar_orden.php', 'Contenedor', '$tabla')";
 
  $descripcion = '';
 
  $kanban = 'F';
  $color = '';
  $inicial = 'F';
  $anula_vencimiento = 'F';
  $campo01     = '';
  $campo02     = '';
  $campo03     = '';
  $campo04     = '';
  $status      = 'T';
}
?>
<div id="Contenedor" class="mensaje"></div>
<input type="hidden" name="codigos" value="<?php echo $codigo; ?>">
<fieldset class="fieldset">
  <legend>DATOS BASICOS <?php echo $titulo; ?> </legend>
  <table width="80%" align="center">
    <tr>
      <td class="etiqueta">C&oacute;digo:</td>
      <td id="input01"><input type="text" name="codigo" maxlength="11" style="width:120px" value="<?php echo $codigo; ?> "  readonly/>
        Activo: <input name="activo" type="checkbox" <?php echo statusCheck("$status"); ?> value="T" /> 
        <?php
        if ($tabla == 'cargos') {
          echo 'Planificable: <input name="planificable" type="checkbox" ' . statusCheck("$planificable") . ' value="T"/>';
        }
        if ($tabla == 'nov_tipo') {
          echo 'Kanban: <input name="kanban" type="checkbox" '. statusCheck("$kanban") .' value="T" />';
        }
        if ($tabla == 'nov_status_kanban') {
          echo 'Inicial por defecto: <input name="inicial" type="checkbox" '. statusCheck("$inicial") .' value="T" />';
          echo 'Anula vencimiento: <input name="anula_vencimiento" type="checkbox" '. statusCheck("$anula_vencimiento") .' value="T" />';
        }
        ?>
        <br />
        <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
    <tr>
      <td class="etiqueta">Descripci&oacute;n: </td>
      <td id="input02"><input type="text" name="descripcion" maxlength="100" style="width:300px" value="<?php echo $descripcion; ?>"  /><br />
        <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>
    </tr>
   
    <?php
    if ($tabla == 'ficha_egreso_motivo') {
      echo  '<tr>
          <td class="etiqueta">Motivo:</td>
          <td id="radio01_5" class="texto">
            Renuncia <input type="radio" name="motivo" value="R" style="width:auto"' .  CheckX($motivo, 'R') . ' />
            Despido <input type="radio" name="motivo" value="D" style="width:auto"' .  CheckX($motivo, 'D') . ' /><br />
            <span class="radioRequiredMsg">Debe seleccionar un Campo.</span>
          </td>
        </tr>';
    }
    if ($tabla == 'cargos') {
      echo '<tr>
            <td class="etiqueta">Tipo:</td> 
            <td>
            <select id="tipo" name="tipo" style="width:200px" required>
            <option value="' . $result["cod_tipo"] . '">' . $result["tipo"] . ' </option>';
      while ($datos = $bd->obtener_fila($query_tipos)) {
        if ($datos[0] != $result["cod_tipo"]) {
          echo '<option value="' . $datos[0] . '">' . $datos[1] . ' </option>';
        }
      }
      echo '</select>
            </td>    
          </tr>';
    }
    if ($tabla == 'ruta_de_ventas' ) {
    echo   '<tr>
            <td class="etiqueta">Descripcion Global: </td>
            <td id="input03"><input type="text" name="descripcionglobal" maxlength="100" style="width:300px" value="'.$descripcionglobal.'" /><br />
             <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
            </td>
           </tr>' ;
     
    }
      
    if ($tabla == 'documentos' || $tabla == 'documentos_cl' || $tabla == 'ruta_de_ventas' ) {
      echo '<tr>
      <td class="etiqueta">Orden:</td> 
      <td  id="input04" > <input type="number" name="orden" style="width:50px" value="' . $orden . '"  />
      <span class="textfieldRequiredMsg">El Campo es Requerido...</span>
      </td>    
      </tr>';
    }
    if ($tabla == 'nov_status_kanban') {
      echo '<tr>
            <td class="etiqueta">Color:</td> 
            <td>
              <input name="color"  type="color" value="'.$color.'" >
            </td>    
          </tr>';
    }
    ?>
    <tr>
      <td height="8" colspan="2" align="center">
        <hr>
      </td>
    </tr>
  </table>
  <?php
  if ($tabla == 'asistencia_clasif') {
  ?>
    <legend>CONCEPTOS ASOCIADOS</legend>
    <table width="80%" align="center" class="tabla_sistema">
      <tr>
        <th class="etiqueta">C&oacute;digo</th>
        <th class="etiqueta">Abrev</th>
        <th class="etiqueta">Descripción</th>
        <th class="etiqueta">Check</th>
      </tr>
      <?php
      $sql_conceptos_asist_diario = "SELECT
        conceptos.codigo,
        conceptos.descripcion,
        conceptos.abrev,
        IF (
          ISNULL(
            asistencia_clasif_concepto.cod_concepto
          ),
          'NO',
          'SI'
        ) existe
      FROM
        conceptos
      LEFT JOIN asistencia_clasif_concepto ON conceptos.codigo = asistencia_clasif_concepto.cod_concepto
      AND asistencia_clasif_concepto.cod_asistencia_clasif = '$codigo'
      WHERE
        conceptos.`status` = 'T'
      AND conceptos.asist_diaria = 'T'
      ORDER BY 3 ASC";

      $query = $bd->consultar($sql_conceptos_asist_diario);

      while ($datos = $bd->obtener_fila($query, 0)) {
        if ($datos[3] == 'NO') {
          $checkX   = '';
        } else {
          $checkX        = 'checked="checked"';
        }
        echo '<tr>
          <td> ' . $datos[0] . '</td>
          <td> ' . $datos[1] . '</td>
          <td> ' . $datos[2] . '</td>
          <td><input type="checkbox" name="conceptos[]" value="' . $datos[0] . '" style="width:auto" ' . $checkX . '/></td>
        </tr>';
      }

      ?>
    </table>
  <?php
  }
  ?>
  <div align="center">
    <span class="art-button-wrapper">
      <span class="art-button-l"> </span>
      <span class="art-button-r"> </span>
      <input type="submit" name="salvar" id="salvar" value="Guardar" class="readon art-button" />
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
  </div>
  <input name="metodo" id="metodo" type="hidden" value="<?php echo $metodo; ?>" />
  <input name="tabla" id="tabla" type="hidden" value="<?php echo $tabla; ?>" />
  <input name="usuario" id="usuario" type="hidden" value="<?php echo $usuario; ?>" />
  <input name="href" type="hidden" value="<?php echo $archivo2; ?>" />
</fieldset>
<script type="text/javascript">
  var input01 = new Spry.Widget.ValidationTextField("input01", "none", {
    validateOn: ["blur", "change"]
  });
  var input02 = new Spry.Widget.ValidationTextField("input02", "none", {
    validateOn: ["blur", "change"]
  });
  
  var radio01_5 = new Spry.Widget.ValidationRadio("radio01_5", {
    validateOn: ["change", "blur"]
  });
</script>