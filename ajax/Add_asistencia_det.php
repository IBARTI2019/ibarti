<?php
$Nmenu        = $_POST['Nmenu'];
$mod          = $_POST['mod'];
$cod_apertura = $_POST['apertura'];
$co_cont      = $_POST['contrato'];
$cod_rol      = $_POST['rol'];
$usuario      = $_POST['usuario'];
$href         = "formularios/Cons_asistencia&Nmenu=$Nmenu&co_cont=$co_cont&rol=$cod_rol";
define("SPECIALCONSTANT", true);
include_once('../funciones/funciones.php');
require("../autentificacion/aut_config.inc.php");
require "../" . class_bdI;
require "../" . Leng;
$bd = new DataBase();

//////  SQL CLIENTES Y NOMINA    //////////

$SQL_TRAB = "SELECT ficha.cod_ficha, CONCAT( ficha.apellidos,' ', ficha.nombres) AS nombres,
		                     ficha.cedula
                       FROM  ficha , control, trab_roles
                       WHERE ficha.cod_ficha_status = ficha_activo
                         AND ficha.cod_ficha     = trab_roles.cod_ficha
                         AND trab_roles.cod_rol  =  '$cod_rol'
						 AND ficha.cod_contracto = '$co_cont'
					ORDER BY 2 ASC ";



$sql05 = " SELECT men_usuarios.asistencia_orden, asistencia_apertura.fec_diaria
	             FROM men_usuarios, asistencia_apertura
                WHERE men_usuarios.codigo = '$usuario'
				  AND asistencia_apertura.codigo = '$cod_apertura'
				  AND asistencia_apertura.cod_contracto = '$co_cont' ";
$query05 = $bd->consultar($sql05);
$row05   = $bd->obtener_fila($query05, 0);
$orden   = $row05[0];
$fec_diaria = $row05[1];

$SQL_PAG = SQL_AsistenciaDet($cod_apertura, $fec_diaria, $co_cont, $cod_rol, $orden);

// TODO LOS CLIENTES
$sql_cliente = "SELECT clientes_ubicacion.cod_cliente, clientes.nombre AS cliente
				      FROM usuario_clientes ,  clientes_ubicacion , clientes
			         WHERE usuario_clientes.cod_usuario = '$usuario'
				       AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo
				       AND clientes_ubicacion.`status` = 'T'
				       AND clientes_ubicacion.cod_cliente = clientes.codigo
				       AND clientes.`status` = 'T'
			      GROUP BY clientes_ubicacion.cod_cliente
			      ORDER BY 2 ASC";

$sql_conceptos = "SELECT conceptos.codigo, conceptos.descripcion, conceptos.abrev
						FROM conceptos
					   WHERE conceptos.`status` = 'T'
						 AND conceptos.asist_diaria = 'T'
					ORDER BY 3 ASC";

?>
<?php echo PropuestaBadgeCSS(); ?>
<?php echo AsistenciaGridCSS(); ?>
<div class="asistencia-resumen"><?php echo PropuestaResumenHTML($bd, $SQL_PAG); ?></div>
<div class="asistencia-tabla-wrap">
<table width="100%" border="0" align="center">
	<tr class="fondo00">
		<th width="26%" class="etiqueta"><?php echo $leng["trabajador"]; ?></th>
		<th width="13%" class="etiqueta">Estado</th>
		<th width="18%" class="etiqueta"><?php echo $leng["cliente"]; ?></th>
		<th width="14%" class="etiqueta"><?php echo $leng["ubicacion"]; ?></th>
		<th width="6%" class="etiqueta"><?php echo $leng["concepto"]; ?></th>
		<th width="6%" class="etiqueta">Clasificación <br> Asistencia</th>
		<th width="5%" class="etiqueta">Horas<br />Extras<br />Diurna</th>
		<th width="5%" class="etiqueta">Horas<br />Extras<br />Noturna</th>
		<th width="5%" class="etiqueta">Vale</th>
		<th width="6%" class="img"><img src="imagenes/loading2.gif" width="40px" height="40px" /></th>
	</tr><?php echo '<td><select name="trabajador" id="trabajador" style="width:210px;">
							   <option value="">seleccione...</option>';
			$query03 = $bd->consultar($SQL_TRAB);
			while ($row03 = $bd->obtener_fila($query03, 0)) {
				echo '<option value="' . $row03[0] . '">' . $row03[1] . '&nbsp;(' . $row03[0] . ')</option>';
			}
			echo '</select></td>
				<td align="center">-</td>
				<td><select name="cliente" id="cliente" style="width:160px;"
							onchange="Actualizar02(this.value)">
						   <option value="">Seleccione...</option>';

			$query03 = $bd->consultar($sql_cliente);
			while ($row03 = $bd->obtener_fila($query03, 0)) {
				echo '<option value="' . $row03[0] . '">' . $row03[1] . '</option>';
			}
			echo '</select></td>
			  <td id="ubicacionX"><select name="ubicacion" id="ubicacion" style="width:120px;"                                          onchange="spryValidarSelect(this.id), Concepto(\'\', this.value)">
						   <option value="">seleccione...</option>';

			echo '</select></td>
			  <td id="conceptoX"><select name="concepto" id="concepto" style="width:55px" onchange="ActualizarClasif(\'\', this.value)"><option value="">Selec...</option>                  </select></td>
			  <td id="clasif_asistenciaX"><select name="clasif_asistencia" id="clasif_asistencia" style="width:120px"><option value="">Seleccione</option>                </select></td>
			  <td><input value="0.00" type="text" name="horaD" id="horaD" style="width:40px" maxlength="5"/></td>
  			  <td><input value="0.00" type="text" name="horaN" id="horaN" style="width:40px" maxlength="5"/></td>
  			  <td><input type="text" name="vale"  id="vale" style="width:40px" value="0" maxlength="8"/></td>'; ?>
	<td align="center"><span class="art-button-wrapper">
			<span class="art-button-l"> </span>
			<span class="art-button-r"> </span>
			<input type="button" name="submit" id="submit" value="Ingresar" class="readon art-button" onclick="Ingresar()" />
		</span></td>
	</tr><?php
			$query = $bd->consultar($SQL_PAG);
			$valor = 1;
			$i     = 0;
			while ($datos = $bd->obtener_fila($query, 0)) {
				$i++;
				if ($valor == 0) {
					$fondo = 'fondo01';
					$valor = 1;
				} else {
					$fondo = 'fondo02';
					$valor = 0;
				}
				$fechaX  = conversion($fecha);
				$campo_id = $datos[0];
				echo '<tr class="' . $fondo . '">
		                 <td class="texto">' . $datos["cod_ficha"] . " - " . longitud($datos["trabajador"]) . '<input type="hidden"
						 id="trabajadores' . $i . '" value="' . $datos["cod_ficha"] . '"/></td>
				  <td align="center">' . PropuestaBadgeHTML($datos['prop_modo'], $datos['prop_observacion']) . '</td>
				  <td> <select id="cliente' . $i . '" style="width:160px;"
						        onchange="Actualizar01(this.value, ' . $i . ')">
							   <option value="' . $datos["cod_cliente"] . '">' . $datos["cliente"] . '</option>';

				$query03 = $bd->consultar($sql_cliente);
				while ($row03 = $bd->obtener_fila($query03, 0)) {
					echo '<option value="' . $row03[0] . '">' . $row03[1] . '</option>';
				}
				echo '</select> </td>
				  <td id="ubicacionX' . $i . '"><select id="ubicacion' . $i . '" style="width:120px;" onchange="spryValidarSelect(this.id), Concepto_det(' . $i . ', this.value)">
							   <option value="' . $datos["cod_ubicacion"] . '">' . $datos["ubicacion"] . '</option>';
				$sql_ubicacion = "SELECT usuario_clientes.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion
                            FROM usuario_clientes , clientes_ubicacion
                           WHERE usuario_clientes.cod_usuario = '$usuario'
                             AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo
                             AND clientes_ubicacion.cod_cliente = '" . $datos["cod_cliente"] . "'
                             AND clientes_ubicacion.`status` = 'T'
                        ORDER BY 2 ASC ";
				$query06 = $bd->consultar($sql_ubicacion);
				while ($row06 = $bd->obtener_fila($query06, 0)) {
					echo '<option value="' . $row06[0] . '">' . $row06[1] . '</option>';
				}
				echo '</select></td>
           	      <td id="conceptoX' . $i . '"><select id="concepto' . $i . '" style="width:55px;" onchange="spryValidarSelect(this.id), ActualizarClasif(' . $i . ',this.value)">								   
					 <option value="' . $datos["cod_concepto"] . '">' . $datos["abrev"] . Feriado_as($datos["feriado"], "FER") . Feriado_as($datos["NL"], "NL") . '</option>';
				$query04 = $bd->consultar($sql_conceptos);
				while ($row04 = $bd->obtener_fila($query04, 0)) {
					echo '<option value="' . $row04[0] . '">' . $row04[2] . Feriado_as($datos["feriado"], "FER") . Feriado_as($datos["NL"], "NL") . '</option>';
				}
				echo '</select><br/><input type="hidden" style="width:0px" id="feriado' . $i . '" name="feriado' . $i . '" value="' . $datos["feriado"] . '" /><input type="hidden" style="width:0px"  id="NL' . $i . '" name="NL' . $i . '" value="' . $datos["NL"] . '" /></td>
							<td id="clasif_asistenciaX' . $i . '"><select name="clasif_asistencia" id="clasif_asistencia' . $i . '" style="width:120px">
							<option value="' . $datos["cod_asistencia_clasif"] . '">' . $datos["asistencia_clasif"] . '</option>                  </select></td>
							<td><input type="text" id="horaD' . $i . '" style="width:40px" value="' . $datos["hora_extra_d"] . '" maxlength="3"
				      onfocus="spryHora(this.id)" /><input type="hidden" id="ubicacion_old' . $i . '"  value="' . $datos["cod_ubicacion"] . '"/><input type="hidden" id="concepto_old' . $i . '"  value="' . $datos["cod_concepto"] . '"/></td>
				<td><input type="text" id="horaN' . $i . '" style="width:40px" value="' . $datos["hora_extra_n"] . '" maxlength="3"
				     onfocus="spryHora(this.id)" /></td>
	   		    <td><input type="text" id="vale' . $i . '" style="width:40px" value="' . $datos["vale"] . '" maxlength="7"
				     onfocus="spryVale(this.id)" /></td>
			    <td align="center" class="imgLink"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="26px" height="26px" id="' . $i . '" onclick="ValidarSubmit(this.id)" />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro"  width="26px" height="26px" id="' . $i . '" onclick="Borrar_Campo(this.id)"/> </td></tr>';
			} ?><tr>
		<td colspan="6"><input type="hidden" id="apertura" name="apertura" value="<?php echo $cod_apertura; ?>" /> <input type="hidden" id="fec_diaria" name="fec_diaria" value="<?php echo $fec_diaria; ?>" /> <input type="hidden" id="contracto" name="contracto" value="<?php echo $co_cont; ?>" /> <input type="hidden" id="Nmenu" name="Nmenu" value="<?php echo $Nmenu; ?>" /> <input type="hidden" id="mod" name="mod" value="<?php echo $mod; ?>" /> <input type="hidden" id="rol" name="rol" value="<?php echo $cod_rol; ?>" /> <input type="hidden" name="href" value="../inicio.php?area=<?php echo $href; ?>" /> <input type="hidden" name="metodo" value="agregar" /> <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" /> <input type="hidden" id="i" value="<?php echo $i; ?>" /> <input type="hidden" name="ubicacion_old" value="" /> <input type="hidden" name="concepto_old" value="" /><input type="hidden" name="proced" id="proced" value="p_asistencia" /></td>
	</tr>
</table>
</div>