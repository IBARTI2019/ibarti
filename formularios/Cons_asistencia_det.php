<script language="JavaScript" type="text/javascript">
	function Actualizar01(idX, campo01) {
		var Contenedor = "ubicacionX" + campo01 + "";
		var usuario = document.getElementById('usuario').value;
		var valor = "ajax/add_ubicacion.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById(Contenedor).innerHTML = ajax.responseText;
				// window.location.reload();
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + idX + "&usuario=" + usuario + "&i=" + campo01 + "");
	}

	function Actualizar02(idX) {
		var Contenedor = "ubicacionX";

		var valor = "ajax/add_ubicacion.php";
		var usuario = document.getElementById('usuario').value;
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById(Contenedor).innerHTML = ajax.responseText;
				//window.location.href=""+href+"";
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + idX + "&usuario=" + usuario + "&i=");
	}

	function ActualizarClasif(auto, codigo) {
		var Contenedor = "clasif_asistenciaX" + auto + "";;

		var valor = "ajax/add_clasif_asistencia.php";
		var usuario = document.getElementById('usuario').value;
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById(Contenedor).innerHTML = ajax.responseText;
				//window.location.href=""+href+"";
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "&usuario=" + usuario + "&i=" + auto + "");
	}

	function Concepto(auto, ubicacion) {
		var Contenedor = "conceptoX" + auto + "";
		var valor = "ajax/Add_as_concepto.php";
		var fecha = document.getElementById('fec_diaria').value;
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById(Contenedor).innerHTML = ajax.responseText;
				//window.location.href=""+href+"";
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("auto=" + auto + "&fecha=" + fecha + "&ubicacion=" + ubicacion + "");
	}

	function Replicar() {
		if (confirm("� Esta Seguro Replicar La Informacion Con EL Dia Anterior ?")) {
			var valor = "scripts/sc_asistencia_proc.php";
			var apertura = document.getElementById("apertura").value;
			var rol = document.getElementById("rol").value;
			var contracto = document.getElementById("contracto").value;
			var usuario = document.getElementById("usuario").value;

			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Contendor01").innerHTML = ajax.responseText;
					setInterval(alert("" + document.getElementById("mensaje_aj").value + ""), Reload(), 1000);
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("apertura=" + apertura + "&rol=" + rol + "&contracto=" + contracto + "&usuario=" + usuario + "&metodo=replicar&proced=p_asistecia_proc&href=");
		} else {
			return false;
		}
	}

	function ValidarSubmit(auto) {
		var apertura = document.getElementById("apertura").value;
		var contracto = document.getElementById("contracto").value;
		var usuario = document.getElementById("usuario").value;
		var proced = document.getElementById("proced").value;
		var trab = document.getElementById("trabajadores" + auto + "").value;
		var cliente = document.getElementById("cliente" + auto + "").value;
		var ubicacion = document.getElementById("ubicacion" + auto + "").value;
		var ubicacion_old = document.getElementById("ubicacion_old" + auto + "").value;
		var concepto = document.getElementById("concepto" + auto + "").value;
		var clasif_asistencia = document.getElementById("clasif_asistencia" + auto + "").value;
		var concepto_old = document.getElementById("concepto_old" + auto + "").value;
		var horaD = document.getElementById("horaD" + auto + "").value;
		var horaN = document.getElementById("horaN" + auto + "").value;
		var vale = document.getElementById("vale" + auto + "").value;
		var feriado = document.getElementById("feriado" + auto + "").value;
		var NL = document.getElementById("NL" + auto + "").value;
		var campo01 = 1;
		//alert(concepto+concepto_old);

		var errorMessage = 'Debe Seleccionar Todos Los Campos';
		if (ubicacion == '') {
			var campo01 = campo01 + 1;
		}
		if (clasif_asistencia == '') {
			var campo01 = campo01 + 1;
		}

		if (campo01 == 1) {
			var valor = "scripts/sc_asistencia.php";
			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Contendor01").innerHTML = ajax.responseText;
					//window.location.href=""+href+"";
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			ajax.send("apertura=" + apertura + "&contracto=" + contracto + "&trabajador=" + trab + "&cliente=" + cliente + "&ubicacion=" + ubicacion + "&ubicacion_old=" + ubicacion_old + "&concepto=" + concepto + "&concepto_old=" + concepto_old + "&horaD=" + horaD + "&horaN=" + horaN + "&vale=" + vale + "&feriado=" + feriado + "&NL=" + NL + "&href=&usuario=" + usuario + "&proced=" + proced + "&metodo=modificar");

		} else {
			alert(errorMessage);
		}
	}

	function Ingresar() {
		var Nmenu = document.getElementById("Nmenu").value;
		var mod = document.getElementById("mod").value;
		var apertura = document.getElementById("apertura").value;
		var contracto = document.getElementById("contracto").value;
		var usuario = document.getElementById("usuario").value;
		var proced = document.getElementById("proced").value;
		var trab = document.getElementById("trabajador").value;
		var cliente = document.getElementById("cliente").value;
		var ubicacion = document.getElementById("ubicacion").value;
		var concepto = document.getElementById("concepto").value;
		var clasif_asistencia = document.getElementById("clasif_asistencia").value;

		var feriado = document.getElementById("feriado").value;
		var NL = document.getElementById("NL").value;

		var horaD = document.getElementById("horaD").value;
		var horaN = document.getElementById("horaN").value;
		var vale = document.getElementById("vale").value;
		var campo01 = 1;
		var errorMessage = 'Debe Seleccionar Todos Los Campos';



		if (trab == '') {
			var campo01 = campo01 + 1;
		}
		if (cliente == '') {
			var campo01 = campo01 + 1;
		}
		if (ubicacion == '') {
			var campo01 = campo01 + 1;
		}
		if (concepto == '') {
			var campo01 = campo01 + 1;
		}
		if (clasif_asistencia == '') {
			var campo01 = campo01 + 1;
		}

		if (campo01 == 1) {
			var valor = "scripts/sc_asistencia.php";
			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Contendor01").innerHTML = ajax.responseText;
					Asistencia_Det();
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			ajax.send("apertura=" + apertura + "&contracto=" + contracto + "&trabajador=" + trab + "&trabajador_old=&cliente=" + cliente + "&ubicacion=" + ubicacion + "&ubicacion_old=&concepto=" + concepto + "&clasif_asistencia=" + clasif_asistencia + "&concepto_old=&horaD=" + horaD + "&horaN=" + horaN + "&vale=" + vale + "&feriado=" + feriado + "&NL=" + NL + "&href=&usuario=" + usuario + "&proced=" + proced + "&metodo=agregar");
		} else {
			alert(errorMessage);
		}
	}

	function Borrar_Campo(auto) {

		if (confirm("� Esta Seguro De Borrar Este Registro?")) {
			var apertura = document.getElementById("apertura").value;
			var usuario = document.getElementById("usuario").value;
			var trab = document.getElementById("trabajadores" + auto + "").value;
			var ubicacion_old = document.getElementById("ubicacion_old" + auto + "").value;
			var concepto_old = document.getElementById("concepto_old" + auto + "").value;
			var proced = document.getElementById("proced").value;
			var valor = "scripts/sc_asistencia.php";
			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Contendor01").innerHTML = ajax.responseText;
					// window.location.reload();
					Asistencia_Det();
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("apertura=" + apertura + "&contracto=''&trabajador=" + trab + "&cliente=''&ubicacion=''&ubicacion_old=" + ubicacion_old + "&concepto=" + concepto_old + "&concepto_old=" + concepto_old + "&horaD=''&horaN=''&vale=''&feriado=''&NL=''&href=''&usuario=" + usuario + "&proced=" + proced + "&metodo=borrar");
		} else {
			return false;
		}
	}

	function Asistencia_Det() {

		var Nmenu = document.getElementById("Nmenu").value;
		var mod = document.getElementById("mod").value;
		var apertura = document.getElementById("apertura").value;
		var contrato = document.getElementById("contracto").value;
		var rol = document.getElementById("rol").value;
		var usuario = document.getElementById("usuario").value;

		var valor = "ajax/Add_asistencia_det.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById("contenedor_listar").innerHTML = ajax.responseText;

			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("Nmenu=" + Nmenu + "&mod=" + mod + "&apertura=" + apertura + "&contrato=" + contrato + "&rol=" + rol + "&usuario=" + usuario + "");
	}

	function CerrarDia() {
		if (confirm("� Esta Seguro De Cerrar Este Fecha?")) {

			var valor = "scripts/sc_asistencia_proc.php";
			var apertura = document.getElementById("apertura").value;
			var fec_diaria = document.getElementById("fec_diaria").value;
			var rol = document.getElementById("rol").value;
			var contracto = document.getElementById("contracto").value;
			var usuario = document.getElementById("usuario").value;

			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 1) {
					document.getElementById(contenido).innerHTML = '<img src="imagenes/loading.gif" />';
					ajax.responseText;
				}
				if (ajax.readyState == 4) {
					document.getElementById("Contendor01").innerHTML = ajax.responseText;
					setInterval(alert("" + document.getElementById("mensaje_aj").value + ""), Reload(), 1000);
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("apertura=" + apertura + "&rol=" + rol + "&contracto=" + contracto + "&fec_diaria=" + fec_diaria + "&usuario=" + usuario + "&metodo=cerrar_as&proced=p_asistecia_proc&href=");
		} else {
			return false;
		}
	}

	function TrabReportar() {
		var valor = "scripts/sc_asistencia_proc.php";
		var fec_diaria = document.getElementById("fec_diaria").value;
		var apertura = document.getElementById("apertura").value;
		var rol = document.getElementById("rol").value;
		var contracto = document.getElementById("contracto").value;
		var usuario = document.getElementById("usuario").value;

		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById("Contendor01").innerHTML = ajax.responseText;
				setInterval(alert("" + document.getElementById("mensaje_aj").value + ""), 500);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("apertura=" + apertura + "&fec_diaria=" + fec_diaria + "&rol=" + rol + "&contracto=" + contracto + "&usuario=" + usuario + "&metodo=trab_reportar&proced=&href=");
	}

	function FiltarRol(ValorN) {
		var contracto = document.getElementById("contracto").value;
		var Nmenu = document.getElementById("Nmenu").value;
		var mod = document.getElementById("mod").value;
		var href = "inicio.php?area=formularios/Cons_asistencia&Nmenu=" + Nmenu + "&mod=" + mod + "&co_cont=" + contracto + "&rol=" + ValorN + "";
		window.location.href = "" + href + "";
		//	alert(href);
	}

	function FiltarNomina(ValorN) {
		var rol = document.getElementById("rol").value;
		var Nmenu = document.getElementById("Nmenu").value;
		var mod = document.getElementById("mod").value;
		var href = "inicio.php?area=formularios/Cons_asistencia&Nmenu=" + Nmenu + "&mod=" + mod + "&co_cont=" + ValorN + "&rol=" + rol + "";
		window.location.href = "" + href + "";
		//alert(href);
	}
</script>
<?php
$Nmenu = '405';
$mod  = $_GET['mod'];
require_once('autentificacion/aut_verifica_menu.php');
$co_cont     = $_POST['co_cont'];
$cod_rol     = $_POST['rol'];

$href = "formularios/Cons_asistencia&Nmenu=$Nmenu&co_cont=$co_cont&rol=$cod_rol";

if (!isset($co_cont) || !isset($cod_rol)) {
	exit();
}

$bd = new DataBase();
$sql04 = "SELECT Min(asistencia_apertura.fec_diaria) AS fec_diaria, Dia_semana(Min(asistencia_apertura.fec_diaria)) AS dia,
	          asistencia_apertura.fec_cierre,
					  asistencia_apertura.codigo, contractos.descripcion AS contracto, roles.descripcion AS rol
				FROM asistencia_apertura , asistencia_cierre , contractos, roles
			   WHERE asistencia_apertura.`status` = 'T'
				 AND asistencia_apertura.cod_contracto = '$co_cont'
				 AND asistencia_apertura.cod_contracto = asistencia_cierre.cod_contracto
				 AND asistencia_cierre.cod_rol = '$cod_rol'
				 AND asistencia_apertura.codigo = asistencia_cierre.cod_as_apertura
				 AND asistencia_cierre.`status` = 'T'
				 AND asistencia_apertura.cod_contracto = contractos.codigo
				 AND roles.codigo = '$cod_rol'";
$query04 = $bd->consultar($sql04);

$row04 = $bd->obtener_fila($query04, 0);
$fec_nomina  = $row04['fec_cierre'];
$fec_diaria  = $row04['fec_diaria'];
$dia         = $row04['dia'];
$contracto   = $row04['contracto'];
$roles   = $row04['rol'];
$cod_apertura = $row04['codigo'];
if ($fec_diaria == '') {
	mensajeria("No Hay Fecha Activa Para Este Contrato");
	exit();
}

$fecha_N = explode("-", $fec_diaria);
$year2   = $fecha_N[0];
$mes2    = $fecha_N[1];
$dia2    = $fecha_N[2];
$fecha_x    = mktime(0, 0, 0, $mes2, $dia2, $year2);


//////  SQL CLIENTES Y NOMINA    //////////

$SQL_TRAB = "SELECT ficha.cod_ficha, CONCAT( ficha.apellidos,' ', ficha.nombres) AS nombres,
		                     ficha.cedula
                       FROM  ficha , control,  trab_roles
                       WHERE ficha.cod_ficha_status = ficha_activo
                         AND ficha.cod_ficha     = trab_roles.cod_ficha
                         AND trab_roles.cod_rol  =  '$cod_rol'
						             AND ficha.cod_contracto = '$co_cont'
						            AND '$fec_diaria' >= ficha.fec_ingreso
					ORDER BY 2 ASC ";

// $optionN = '<option value="'.$region.'">'.$row02[1].'</option><option value="TODAS">TODAS</option>';

$sql05 = " SELECT men_usuarios.asistencia_orden FROM  men_usuarios
                WHERE men_usuarios.codigo = '$usuario'";
$query05 = $bd->consultar($sql05);
$row05   = $bd->obtener_fila($query05, 0);
$orden   = $row05[0];

$SQL_PAG = "SELECT
asistencia.cod_ficha,
ficha.cedula,
CONCAT( ficha.apellidos, ' ', ficha.nombres ) trabajador,
asistencia.cod_cliente,
clientes.nombre cliente,
asistencia.cod_ubicacion,
clientes_ubicacion.descripcion ubicacion,
asistencia.cod_concepto,
conceptos.descripcion concepto,
IF
( ISNULL( asistencia_clasif.descripcion ), '9999', asistencia.cod_asistencia_clasif ) cod_asistencia_clasif,
IF
( ISNULL( asistencia_clasif.descripcion ), 'N/A', asistencia_clasif.descripcion ) asistencia_clasif,
conceptos.abrev,
asistencia.hora_extra hora_extra_d,
asistencia.hora_extra_n,
asistencia.vale,
asistencia.feriado,
asistencia.no_laboral AS NL 
FROM
asistencia
LEFT JOIN asistencia_clasif ON asistencia_clasif.codigo = asistencia.cod_asistencia_clasif,
ficha,
trab_roles,
clientes,
clientes_ubicacion,
conceptos 
WHERE
asistencia.cod_as_apertura = '$cod_apertura' 
AND asistencia.cod_ficha = ficha.cod_ficha 
AND ficha.cod_ficha = trab_roles.cod_ficha 
AND asistencia.cod_cliente = clientes.codigo 
AND asistencia.cod_ubicacion = clientes_ubicacion.codigo 
AND asistencia.cod_concepto = conceptos.codigo 
AND trab_roles.cod_rol = '$cod_rol' UNION
SELECT
pctd.cod_ficha,
f.cedula,
CONCAT( f.apellidos, ' ', f.nombres ) trabajador,
pctd.cod_cliente,
c.nombre cliente,
pctd.cod_ubicacion,
cu.descripcion ubicacion,
IF
( cc.asist_diaria = 'F', cc.codigo, control.concepto_rep ) cod_concepto,
IF
( cc.asist_diaria = 'F', cc.descripcion, ccc.descripcion ) concepto,
'9999' cod_asistencia_clasif,
'N/A' asistencia_clasif,
IF
( cc.asist_diaria = 'F', cc.abrev, ccc.abrev ) concepto,
0 hora_extra_d,
0 hora_extra_n,
0 vale,
0 feriado,
0 NL 
FROM
planif_clientes_trab_det pctd,
ficha f,
trab_roles,
clientes c,
clientes_ubicacion cu,
turno t,
horarios h,
conceptos cc,
control,
conceptos ccc 
WHERE
pctd.fecha = '$fec_diaria' 
AND pctd.cod_ficha = f.cod_ficha 
AND pctd.cod_cliente = c.codigo 
AND pctd.cod_ubicacion = cu.codigo 
AND pctd.cod_turno = t.codigo 
AND t.cod_horario = h.codigo 
AND h.cod_concepto = cc.codigo 
AND f.cod_contracto =  '$co_cont' 
AND f.cod_ficha_status = control.ficha_activo
AND f.cod_ficha = trab_roles.cod_ficha 
AND trab_roles.cod_rol = '$cod_rol'  
AND control.concepto_rep = ccc.codigo
AND pctd.cod_ficha NOT IN ( SELECT cod_ficha FROM asistencia WHERE asistencia.cod_as_apertura = '$cod_apertura' )
ORDER BY $orden ASC";

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

$sql_conceptos = " SELECT conceptos.codigo, conceptos.descripcion, conceptos.abrev
				 FROM conceptos
				WHERE conceptos.`status` = 'T'
				  AND conceptos.asist_diaria = 'T'
			 ORDER BY 3 ASC ";

?>
<div align="center" class="etiqueta_title"> Asistencia <?php echo $leng["nomina"] . ' Fecha Activa: ' . conversion($fec_diaria) . ' (' . $dia . ') , ' . $row04['contracto']; ?></div>
<div id="Contendor01" class="mensaje"></div>
<hr />
<table width="98%">
	<tr>
		<td width="12%" class="etiqueta"><?php echo $leng["nomina"]; ?>: </td>
		<td width="25%"><select id="nomina" style="width:200px;" onchange="FiltarNomina(this.value)">
				<option value="<?php echo $co_cont ?>"><?php echo $contracto; ?></option>
				<?php
				$sql03 = "SELECT contractos.codigo, contractos.descripcion AS contracto, asistencia_apertura.fec_diaria
                            FROM usuario_roles , trab_roles, ficha, contractos , asistencia_apertura, asistencia_cierre
						   WHERE usuario_roles.cod_usuario = '$usuario'
							 AND usuario_roles.cod_rol = trab_roles.cod_rol
							 AND trab_roles.cod_ficha = ficha.cod_ficha
							 AND ficha.cod_contracto = contractos.codigo
						 	 AND asistencia_apertura.`status` = 'T'
							 AND contractos.codigo = asistencia_apertura.cod_contracto
							 AND asistencia_apertura.codigo = asistencia_cierre.cod_as_apertura
							 AND usuario_roles.cod_rol = asistencia_cierre.cod_rol
							 AND contractos.codigo = asistencia_cierre.cod_contracto
							 AND asistencia_cierre.`status` = 'T'
							 AND contractos.codigo <> '$co_cont'
						GROUP BY contractos.codigo
					    ORDER BY 2 ASC";
				$query03 = $bd->consultar($sql03);
				while ($row03 = $bd->obtener_fila($query03, 0)) {
					echo '<option value="' . $row03[0] . '">' . $row03[1] . '</option>';
				} ?>
			</select></td>
		<td width="12%" class="etiqueta"><?php echo $leng["rol"]; ?>: </td>
		<td width="25%"><select id="roles" style="width:200px;" onchange="FiltarRol(this.value)">
				<?php echo '<option value="' . $cod_rol . '">' . $roles . '</option>';
				$sql03 = "SELECT DISTINCT roles.codigo, roles.descripcion AS rol
               FROM asistencia_apertura , asistencia_cierre , usuario_roles,  roles
              WHERE asistencia_apertura.`status` = 'T'
			    AND asistencia_apertura.cod_contracto = '$co_cont'
			    AND asistencia_apertura.codigo = asistencia_cierre.cod_as_apertura
				AND asistencia_apertura.cod_contracto = asistencia_cierre.cod_contracto
				AND usuario_roles.cod_usuario = '$usuario'
				AND usuario_roles.cod_rol = roles.codigo
				AND asistencia_cierre.cod_rol = roles.codigo
           ORDER BY 2 ASC";
				$query03 = $bd->consultar($sql03);
				while ($row03 = $bd->obtener_fila($query03, 0)) {
					echo '<option value="' . $row03[0] . '">' . $row03[1] . '</option>';
				} ?></select>&nbsp;&nbsp;</td>
		<td width="26%">
			<div align="right"><span class="art-button-wrapper">
					<span class="art-button-l"> </span>
					<span class="art-button-r"> </span>
					<input type="button" id="Dia_Anterior" value="Replicar Dia Anterior" class="readon art-button" onclick="Replicar()" />
				</span><br /></div>
		</td>
	</tr>
</table>
<hr />
<div class="listar">
	<form id="asistencia_01" name="asistencia_01" action="scripts/sc_asistencia.php" method="post">
		<div id="contenedor_listar">
			<table width="100%" border="0" align="center">
				<tr class="fondo00">
					<th width="36%" class="etiqueta"><?php echo $leng["trabajador"]; ?></th>
					<th width="20%" class="etiqueta"><?php echo $leng["cliente"]; ?></th>
					<th width="16%" class="etiqueta"><?php echo $leng["ubicacion"]; ?></th>
					<th width="8%" class="etiqueta"><?php echo $leng["concepto"]; ?></th>
					<th width="8%" class="etiqueta">Clasificación <br> Asistencia</th>
					<th width="5%" class="etiqueta">Horas<br />Extras<br />Diurna</th>
					<th width="5%" class="etiqueta">Horas<br />Extras<br />Noturna</th>
					<th width="5%" class="etiqueta">Vale</th>
					<th width="5%" class="img"><img src="imagenes/loading2.gif" width="40px" height="40px" /></th>
				</tr><?php echo '<td><select name="trabajador" id="trabajador" style="width:210px;">
							   <option value="">seleccione...</option>';
						$query03 = $bd->consultar($SQL_TRAB);
						while ($row03 = $bd->obtener_fila($query03, 0)) {
							echo '<option value="' . $row03[0] . '">' . $row03[1] . '&nbsp;(' . $row03[0] . ')</option>';
						}
						echo '</select></td>
				<td><select name="cliente" id="cliente" style="width:160px;"
							onchange="Actualizar02(this.value)">
						   <option value="">Seleccione...</option>';

						$query03 = $bd->consultar($sql_cliente);
						while ($row03 = $bd->obtener_fila($query03, 0)) {
							echo '<option value="' . $row03[0] . '">' . $row03[1] . '</option>';
						}
						echo '</select></td>
			  <td id="ubicacionX"><select name="ubicacion" id="ubicacion" style="width:120px;"                                          onchange="spryValidarSelect(this.id), Concepto(\'\', this.value)">
						   <option value="">Seleccione</option>';

						echo '</select></td>
			  <td id="conceptoX"><select name="concepto" id="concepto" style="width:55px" onchange="ActualizarClasif(\'\', this.value)"><option value="">Selec...</option>                  </select></td>
			  <td id="clasif_asistenciaX"><select name="clasif_asistencia" id="clasif_asistencia" style="width:120px"><option value="">Seleccione</option>                 </select></td>
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
			    <td align="center" class="imgLink"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" id="' . $i . '" onclick="ValidarSubmit(this.id)" />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro"  width="20px" height="20px" id="' . $i . '" onclick="Borrar_Campo(this.id)"/> </td></tr>';
						} ?><tr>
					<td colspan="6"><input type="hidden" id="apertura" name="apertura" value="<?php echo $cod_apertura; ?>" /> <input type="hidden" id="fec_diaria" name="fec_diaria" value="<?php echo $fec_diaria; ?>" /> <input type="hidden" id="contracto" name="contracto" value="<?php echo $co_cont; ?>" /> <input type="hidden" id="Nmenu" name="Nmenu" value="<?php echo $Nmenu; ?>" /> <input type="hidden" id="mod" name="mod" value="<?php echo $mod; ?>" /> <input type="hidden" id="rol" name="rol" value="<?php echo $cod_rol; ?>" /> <input type="hidden" name="href" value="../inicio.php?area=<?php echo $href; ?>" /> <input type="hidden" name="metodo" value="agregar" /> <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" /> <input type="hidden" id="i" value="<?php echo $i; ?>" /> <input type="hidden" name="ubicacion_old" value="" /> <input type="hidden" name="concepto_old" value="" /><input type="hidden" name="proced" id="proced" value="p_asistencia" /></td>
				</tr>
			</table>
		</div>
	</form>
</div>
<div align="center"><br />
	<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<input type="button" name="salir" id="salir" value="Salir" onclick="Vinculo('inicio.php?area=formularios/index')" class="readon art-button">
	</span>&nbsp;
	<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<input type="button" name="cerrar" id="cerrar" value="Cerrar Dia" onclick="CerrarDia()" class="readon art-button">
	</span>&nbsp;
	<span class="art-button-wrapper">
		<span class="art-button-l"> </span>
		<span class="art-button-r"> </span>
		<input type="button" name="trab_reportar" id="trab_reportar" value="Trab. Por Reportar" onclick="TrabReportar()" class="readon art-button">
	</span>
</div>
<script language="javascript" type="text/javascript">
	var trabajadores = new Spry.Widget.ValidationSelect('trabajador', {
		validateOn: ["blur", "change"]
	});
	var cliente = new Spry.Widget.ValidationSelect('cliente', {
		validateOn: ["blur", "change"]
	});
	var concepto = new Spry.Widget.ValidationSelect('concepto', {
		validateOn: ["blur", "change"]
	});
	var horaD = new Spry.Widget.ValidationTextField("horaD", "currency", {
		format: "comma_dot",
		validateOn: ["blur"],
		useCharacterMasking: true,
		minValue: "0",
		maxValue: "24",
		isRequired: false
	});
	var horaN = new Spry.Widget.ValidationTextField("horaN", "currency", {
		format: "comma_dot",
		validateOn: ["blur"],
		useCharacterMasking: true,
		minValue: "0",
		maxValue: "24",
		isRequired: false
	});
	var Vvale = new Spry.Widget.ValidationTextField("vale", "currency", {
		format: "comma_dot",
		validateOn: ["blur"],
		useCharacterMasking: true,
		minValue: "0",
		maxValue: "10000",
		isRequired: false
	});

	function spryHora(ValorX) {
		var horaN = new Spry.Widget.ValidationTextField(ValorX, "currency", {
			format: "comma_dot",
			validateOn: ["blur"],
			useCharacterMasking: true,
			minValue: "0",
			maxValue: "24",
			isRequired: false
		});
	}

	function spryVale(ValorX) {
		var Vvale = new Spry.Widget.ValidationTextField(ValorX, "currency", {
			format: "comma_dot",
			validateOn: ["blur"],
			useCharacterMasking: true,
			minValue: "0",
			maxValue: "10000",
			isRequired: false
		});
	}

	function spryValidarSelect(ValorN) {
		var ValorN = new Spry.Widget.ValidationSelect(ValorN, {
			validateOn: ["blur", "change"]
		});
	}
</script>