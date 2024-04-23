<link rel="stylesheet" href="css/planif_integral.css" type="text/css" media="screen" />
<script type="text/javascript" src="jquery.blockUI.js"></script>
<?php
	$Nmenu = '4401';
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');

	$bd      = new DataBase();
  $proced  = "p_planif_integral";
	$usuario = $_SESSION['usuario_cod'];
	$archivo = "reportes/rp_op_planif_cl_vs_trab_det.php?Nmenu=$Nmenu&mod=$mod";

	$sql01 = " SELECT pl_cliente_apertura.codigo periodo,  DATE_FORMAT(LAST_DAY(CONCAT(pl_cliente_apertura.codigo,'-01')), '%d') d_max
	             FROM pl_cliente_apertura ORDER BY 1 DESC  LIMIT 0 ,1;";
	$query01 = $bd->consultar($sql01);
	$row =$bd->obtener_fila($query01,0);
	$periodo = $row[0];
	$d_max = $row[1];

	$titulo = " Planificacion Integral ".$leng['cliente']." y ".$leng['trabajador']." ";

  $sql    = "$SELECT $proced('$periodo', '$usuario')";
  $query = $bd->consultar($sql);

$sql = "SELECT plan_integral.*, clientes.nombre cliente, clientes.abrev
					FROM plan_integral ,   clientes
				 WHERE plan_integral.periodo = '$periodo'
					 AND plan_integral.cod_cliente = clientes.codigo
					 AND plan_integral.tipo = 'cli'";
 $query = $bd->consultar($sql);


$sql_periodo = "SELECT pl_cliente_apertura.codigo FROM pl_cliente_apertura   ORDER BY 1 DESC ";
?>
<script language="JavaScript" type="text/javascript">

// FILTRO PRINCIPAL

$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //
		var error = 0;
    var errorMessage = ' ';

	  var periodo     =$("#periodo").val();
		var usuario     = $("#usuario").val();
		var region      = $("#region").val();
		var proced  = "p_planif_integral";

		$("#cp_cliente").hide();
		$("#b_volver").hide();

	if(error == 0){

	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	var contenido = "listar";
	var parametros = { 	"codigo": periodo, 						"region": region,
						          "usuario": usuario,           "proced" : proced		};
				$.ajax({
						data:  parametros,
						url:   'ajax/Add_planif_integral.php',
						type:  'post',
						success:  function (response) {
							$("#cliente").val("TODOS");
							$("#ubicacion").val("TODOS");
							$("#contenido01").html(response);

								tp1.showPanel(0);
								$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0' onclick='Add_filtroX()'>");
						},
					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}

				});

		}else{
			alert(errorMessage);
		}
}

function Planif_Cl(periodo, cliente, desc_cl){

	var usuario      = $("#usuario").val();
  var error        = 0;
  var errorMessage = ' ';

  if( cliente == ""){
    var errorMessage = errorMessage + ' \n El Campo Cliente Es Requerido ';
    var error      = error+1;
  }

  if(error == 0){
    var contenido = "listar";
    var parametros = { "codigo" : periodo,               "cliente": cliente,
                       "usuario": usuario        };
      $.ajax({
          data:  parametros,
          url:   'ajax/Add_planif_integral_ubic.php',
          type:  'post',
          success:  function (response) {

							$("#texto_titulo_integ").text(desc_cl.toUpperCase());
							$("#titulo_integ").val(desc_cl.toUpperCase());

							$("#b_volver").show();

							$("#cliente").val(cliente);
							$("#ubicacion").val("TODOS");

						//	Planif_ubic_horario(periodo, cliente, 'TODOS', '');
              $("#contenido02").html(response);
							tp1.showPanel(1);
          },
          error: function (xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);}
            });
  }else{
    alert(errorMessage);
  }
}

function Planif_ubic_horario(periodo, cliente, ubicacion, title){
	var usuario      = $("#usuario").val();
  var error        = 0;
  var errorMessage = ' ';

  if(error == 0){
    var contenido = "listar";
    var parametros = {"codigo" : periodo,           "cliente": cliente,
	         						"ubicacion": ubicacion,       "usuario": usuario
										 };
      $.ajax({
          data:  parametros,
          url:   'ajax/Add_planif_integral_horario.php',
          type:  'post',
          	success:  function (response) {

							if(title != ""){
								$("#texto_titulo_integ").html(title);
								$("#titulo_integ").val(title);
							}


              $("#contenido03").html(response);
							if(ubicacion != "TODOS"){
								tp1.showPanel(2);

								 $("#ubicacion").val(ubicacion);
						}
          },
          error: function (xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);}
            });
  }else{
    alert(errorMessage);
  }
}

// Modificar planificacion  por turno y horario
function Planif_mod(pag, turno, horario, dia){
	var periodo   = $("#periodo").val();
	var cliente   = $("#cliente").val();
	var ubicacion = $("#ubicacion").val();
	var usuario      = $("#usuario").val();

	var desc = 'Cliente : '+$("#cliente").val()+' Ubicacion: '+$("#ubicacion").val()+'';
	var error        = 0;
  var errorMessage = ' ';

  if(error == 0){
    var contenido = "listar";
    var parametros = { "codigo"    : periodo,        "cliente" : cliente,
	                     "ubicacion" : ubicacion,      "usuario" : usuario,
											 "turno"     : turno ,         "horario" : horario,
											 "dia"       : dia};
      $.ajax({
          data:  parametros,
          url:   'ajax/Add_planif_integral_mod.php',
          type:  'post',
          success:  function (response) {

						ModalOpen();
						$("#selec_dia").val(dia);
						$("#selec_turno").val(turno);
						$("#selec_horario").val(horario);
	           $("#contenido_mod").html(response);
						iniciar_tab(pag);
          },
          error: function (xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);}
            });
  }else{
    alert(errorMessage);
  }
}

function ModalOpen(){
 $("#myModal").show();
}

function CloseModal(){
 	$("#myModal").hide();
}

 function iniciar_tab(pag){
	$(".tablinks").eq(pag).addClass("active")
	$(".tabcontent").hide();
 	$(".tabcontent").eq(pag).show();
 }

 function openTap(index) {
	$(".tablinks" ).removeClass( "active" );
	$(".tablinks").eq(index).addClass("active")
 	$(".tabcontent").hide();
 	$(".tabcontent").eq(index).show();
 }

 function PlanClienteEvento(cod, metodo){
	 var usuario    = $("#usuario").val();
	 var cliente    = $("#cliente").val();
 	var ubicacion   = $("#ubicacion").val();
   var Contenedor = "tab_cont01";
 	 var valor  = "ajax/Add_pl_cliente_mod.php";

	 ajax=nuevoAjax();
	 ajax.open("POST", valor, true);
	 ajax.onreadystatechange=function()
	 {
	 	if (ajax.readyState==4){
	 	document.getElementById(Contenedor).innerHTML = ajax.responseText;
	 	}
	 }
	 ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	 ajax.send("codigo="+cod+"&usuario="+usuario+"&metodo="+metodo+"");

 }

function PlanClienteBorrar(cod, fecha_desde, fecha_hasta){
	var usuario    = $("#usuario").val();
	var cliente    = $("#cliente").val();
 	var ubicacion  = $("#ubicacion").val();
 	var proced     = "p_pl_cliente";

	if (confirm("� Esta Seguro De Borrar Este Registro?")) {
		var valor = "scripts/sc_pl_cliente.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4){
			document.getElementById("Mensaje").innerHTML = ajax.responseText;
				Regenerar();
				Planif_mod(0);
			}
		}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	 ajax.send("codigo="+cod+"&periodo=''&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cliente=''&ubicacion=''&cargo=''&turno=''&excepcion=''&cantidad=''&usuario="+usuario+"&proced="+proced+"&metodo=borrar");

			} else{
				return false;
		 }
}


// ACTUALIZAR O INGRESAR PLANIFICACION DE CLIENTE
function ActualizarCl(){

	var Nmenu         = "";
  var mod           = "";
	var proced        = "p_pl_cliente";
	var metodo        = $("#metodo").val();
	var usuario       = $("#usuario").val();
	var fecha_desde  = $("#fecha_desde").val();
	var fecha_hasta  = $("#fecha_hasta").val();

	var codigo       = $("#codigo").val();
	var periodo      = $("#periodo").val();
	var cliente      = $("#cliente").val();
	var ubicacion    = $("#ubicacion").val();
	var cargo        = $("#cargo").val();
	var turno        = $("#turno").val();
  var excepcion      = $('input[name=excepcion]:checked', '#form_mod').val();
	var cantidad     = $("#cantidad").val();

	var error = false;
  var errorMessage = '';

	if( (fecha_desde ==  "") || (fecha_hasta == "")){
		var errorMessage = errorMessage + 'Campos De Fecha Incorrectas  \n ';
		var error = true;
 }

  if(cliente=='') {
	 var error = true;
	  var errorMessage =  errorMessage + 'Debe Seleccionar el Cliente \n';
	}
	if((ubicacion=='' ) ||(ubicacion=='TODOS' ))  {
	 var error = true;
	 var errorMessage =  errorMessage + 'Debe Seleccionar el Ubicacion \n';
	}

	if (typeof(excepcion) == "undefined"){
		 var errorMessage =  errorMessage + 'Debe Seleccionar Una Excepcion \n';
	 	 var error = true;
	}

	if((cantidad =='') || (cantidad ==0)) {
	 var error = true;
	 var errorMessage =  errorMessage + 'Debe Ingresar Una Cantidad \n';
	}

	if(error == false){

	var valor = "scripts/sc_pl_cliente.php";
		ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			document.getElementById("Mensaje").innerHTML = ajax.responseText;

	  	$("#turno").val("");
   		$("#cantidad").val(0);
			Regenerar();
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send("codigo="+codigo+"&periodo="+periodo+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&cliente="+cliente+"&ubicacion="+ubicacion+"&cargo="+cargo+"&turno="+turno+"&excepcion="+excepcion+"&cantidad="+cantidad+"&usuario="+usuario+"&proced="+proced+"&metodo="+metodo+"");
	 }else{
		 alert(errorMessage);
	 }
}

// Boton cancelar Planificacion de  cliente
function FiltrarCl(){
	Planif_mod(0, $("#selec_turno").val(),$("#selec_horario").val(),$("#selec_dia").val());

}

///////   CARGAR PLANIFICACION DE TRABAJADOR (INGRESAR O MODIFICAR)s
function PlanTrabajadorEvento(cod, metodo){
	var usuario    = $("#usuario").val();
	var cliente    = $("#cliente").val();
	var ubicacion  = $("#ubicacion").val();
	var r_rol      = $("#r_rol").val();
	var r_cliente  = $("#r_cliente").val();

	 var Contenedor = "tab_cont02";
	 var valor  = "ajax/Add_planif_integral_trab_mod.php";

		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
			document.getElementById(Contenedor).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+cod+"&usuario="+usuario+"&cod_cliente="+cliente+"&cod_ubicacion="+ubicacion+"&r_rol="+r_rol+"&r_cliente="+r_cliente+"&metodo="+metodo+"");

}

// BORRAR  PLANIFICACION DE TRABAJADOR
function PlanTrabajadorBorrar(cod, fecha_desde, fecha_hasta){
	var proced       = "p_pl_trabajador";
	var usuario      = $("#usuario").val();

	if (confirm("� Esta Seguro De Borrar Este Registro?")) {
	 	var valor = "scripts/sc_planif_trabajador.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4){
				document.getElementById("Mensaje").innerHTML = ajax.responseText;
					Planif_mod(1, $("#selec_turno").val(),$("#selec_horario").val(),$("#selec_dia").val());
			}
		}

	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send("codigo="+cod+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&trabajador=''&cliente=''&ubicacion=''&rotacion=''&rotacion_n=''&excepcion=''&usuario="+usuario+"&proced="+proced+"&metodo=borrar");
     } else{
       return false;
    }

}

// ACTUALIZAR O INGRESAR PLANIFICACION DE TRABAJADOR
function ActualizarTrab(){
		var Nmenu         = "";
	  var mod           = "";
		var proced        = "p_pl_trabajador";
		var usuario       = $("#usuario").val();
		var fecha_desde  = $("#trab_fecha_desde").val();
		var fecha_hasta  = $("#trab_fecha_hasta").val();

		var codigo       = $("#trab_codigo").val();
		var periodo      = $("#periodo").val();
		var trabajador  =  $("#trab_trabajador").val();
		var cliente      = $("#trab_cliente").val();
		var ubicacion    = $("#trab_ubicacion").val();
		var rotacion     = $("#trab_rotacion").val();
		var rotacion_n    = $("#trab_rotacion_n").val();
	  var excepcion     = $('input[name=trab_excepcion]:checked', '#form_trab_mod').val();
		var metodo     = $("#trab_metodo").val();

		var error = false;
	  var errorMessage = '';

		if( (fecha_desde ==  "") || (fecha_hasta == "")){
				var errorMessage = errorMessage +'Campos De Fecha Incorrectas \n ';
				var error = true;
		 }

		if((trabajador == '') || (trabajador == null)) {
		 var error = true;
		errorMessage = errorMessage + 'Debe Seleccionar un Trabajador \n';
		}
	  if(cliente=='') {
		 	var error = true;
		  var errorMessage = errorMessage + 'Debe Seleccionar el Cliente \n ';
		}
		if((ubicacion=='' ) ||(ubicacion=='TODOS' ))  {
		 var error = true;
		 var errorMessage = errorMessage + 'Debe Seleccionar el Ubicacion \n';
		}
		if(rotacion=='') {
		 var error = true;
		 var errorMessage = errorMessage + 'Debe Seleccionar Una Rotacion \n';
		}
		if(rotacion_n=='') {
		 var error = true;
		 var errorMessage = errorMessage + 'Debe Seleccionar una Posicion de Rotacion \n';
		}
		if (typeof(excepcion) == "undefined"){
			 var errorMessage = errorMessage +  'Debe Seleccionar Una Excepcion';
		 	 var error = true;
		}

		if(error == false){

		var valor = "scripts/sc_planif_trabajador.php";
			ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4){
				document.getElementById("Mensaje").innerHTML = ajax.responseText;
				Planif_mod(1, $("#selec_turno").val(),$("#selec_horario").val(),$("#selec_dia").val());
				Regenerar();
	   		}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	  ajax.send("codigo="+codigo+"&periodo="+periodo+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&trabajador="+trabajador+"&cliente="+cliente+"&ubicacion="+ubicacion+"&rotacion="+rotacion+"&rotacion_n="+rotacion_n+"&excepcion="+excepcion+"&usuario="+usuario+"&proced="+proced+"&metodo="+metodo+"");
		 }else{
		alert(errorMessage);
		 }
}
// CANCELAR  PLANIFICACION DE TRABAJADOR
function CancelarPlanifTrab(){
	Planif_mod(1, $("#selec_turno").val(),$("#selec_horario").val(),$("#selec_dia").val());
}

// FILTRAR ROTACION DE  PLANIFICACION DE TRABAJADOR
function Filtrar_rotacion(idX, name, Contenedor, px, evento){
	if(idX !=""){
		var valor  = "ajax/Add_rotacion_trab_n.php";
		var usuario    = $("#usuario").val();

		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
			document.getElementById(Contenedor).innerHTML = ajax.responseText;

			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+idX+"&name="+name+"&usuario="+usuario+"&tamano="+px+"&evento="+evento+"");
	}
}

// BUSCAR TRABAJADOR
function BuscarTrab(){
	var usuario         = $("#usuario").val();
	var filtroTrab      = $("#filtroTrab").val();

var errorMessage = "";
 var error = false;
	if (filtroTrab.length < 3 ){
		 var errorMessage =  errorMessage + 'Debe Ingrear Minimo 3 Caracteres Para Filtrar \n';
	 	 var error = true;
	}

	if(error == false){

		var Contenedor = "trab_trabajadorX";
		var valor  = "ajax/Add_select_trabajador.php";

		 ajax=nuevoAjax();
		 ajax.open("POST", valor, true);
		 ajax.onreadystatechange=function()
		 {
			 if (ajax.readyState==4){
			 document.getElementById(Contenedor).innerHTML = ajax.responseText;
			 }
		 }
		 ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		 ajax.send("filtro="+filtroTrab+"&usuario="+usuario+"&tamano=220px&evento=''&name=trab_trabajador");

	}else{
		alert(errorMessage);
	}
}

//  REGENERAR PLANIFICACION INTEGRAL
function Regenerar(){

	var codigo       = $("#codigo").val();
	var periodo      = $("#periodo").val();
	var cliente      = $("#cliente").val();
	var ubicacion    = $("#ubicacion").val();
	var titulo       = $("#titulo_integ").val();


	Add_filtroX();
	setTimeout(function(){
		Planif_Cl(periodo, cliente, desc_cl);

	}, 600);
	setTimeout(function(){
		Planif_ubic_horario(periodo, cliente, ubicacion, title);
	}, 1500);

	setTimeout(function(){
		$("#cliente").val(cliente);
		$("#ubicacion").val(ubicacion);
		$("#titulo_integ").val(titulo);
		$("#texto_titulo_integ").text(titulo);
	}, 2000);


	setTimeout(function(){
	 	document.getElementById("Mensaje").innerHTML = "";
	}, 10000);

}

</script><div align="center" class="etiqueta_title">
   <?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<div id="Mensaje" class="mensaje"></div>
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="CloseModal()" >&times;</span>
      MODIFICAR PLANIFICACION INTEGRAL<br/> <span id="plan_title">Titulo Detalle</span>
    </div>
    <div class="modal-body">
			<div id="contenido_mod">Contenido</div>
    </div>
    </div>
</div>

<hr /><table width="100%" class="etiqueta">
		<tr><td width="10%">Periodo:</td>
		    <td width="10%"><select name="periodo" id="periodo" style="width:80px;" onchange="Add_filtroX()">
						<?php
						$query01 = $bd->consultar($sql_periodo);
					while($row01=$bd->obtener_fila($query01,0)){
						 echo '<option value="'.$row01[0].'">'.$row01[0].'</option>';
					 }?></select>
				</td>
					<td width="42%"><span id="texto_titulo_integ"></span>
					<td width="8%">Region:</td>
					<td width="14%"><select name="region" id="region" style="width:160px;">
							<option value="TODOS">TODOS</option>
							<?php
			   			$query01 = $bd->consultar($sql_region);
				 		while($row01=$bd->obtener_fila($query01,0)){
							 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
					   }?></select></td>

					<td width="10%"><span id="b_volver" style="display: none;">
						<span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button"  name="volver"  id="volver" onClick="Add_filtroX()" value="Volver" class="readon art-button" />
                </span></span><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>" />
<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
 <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>

<input type="hidden" name="cliente" id="cliente" value="TODOS" />
<input type="hidden" name="ubicacion" id="ubicacion" value="TODOS" />
<input type="hidden" name="selec_turno" id="selec_turno" value="" />
<input type="hidden" name="selec_horario" id="selec_horario" value="" />
<input type="hidden" name="selec_dia" id="selec_dia" value="" />
<input type="hidden" name="titulo_integ" id="titulo_integ" value="" />
<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" /></td>
      <td width="6%" id="cont_img" class="imgLink"><img id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                        onclick=" Add_filtroX()" title="Actulizar Planificacion" ></td>
      </tr>
</table>

<div class="TabbedPanels" id="tp1">
 <ul class="TabbedPanelsTabGroup">
	<li class="TabbedPanelsTab">Planificacion De Cliente</li>
	<li class="TabbedPanelsTab" >PLanificacion Por Ubicacion</li>
	<li class="TabbedPanelsTab">PLanificacion Ubicacion y Horarios</li>
 </ul>
	<div class="TabbedPanelsContentGroup">
		 <div class="TabbedPanelsContent">
			 <div id="contenido01" class="listar2">

		<table class="tabla_planif">
				<tr class="t_titulo f_titulo">
					<td rowspan="3" colspan="2" class="td_width"><?php echo $leng["cliente"];?></td>
				<?php
						for ($i=1; $i <= $d_max; $i++) {
							echo '<td colspan="2" >'.str_pad((int) $i, 2, "0", STR_PAD_LEFT).' - '.Semana(date("w", strtotime(date("Y-m-$i"))),'c').'</td>';
						} ?>
				</tr>

				<tr class="t_sub_titulo f_sub_titulo">
					<?php
					for ($i=1; $i <= $d_max; $i++) {
						echo	'<td colspan="2">TRABAJADORES</td>';
					} ?>
				</tr>
				<tr class="t_sub_titulo f_sub_titulo">
					<?php
					for ($i=1; $i <= $d_max; $i++) {
						echo	'<td >Solicitado</td>
									<td>Cubierto</td>';
					} ?>
				</tr>
				<?php
				 while ($datos=$bd->obtener_fila($query,0)){
					echo '<tr class="t_contenido imgLink" title="'.$datos["cliente"].'"
		                onclick="Planif_Cl(\''.$datos["periodo"].'\', \''.$datos["cod_cliente"].'\', \''.$datos["cliente"].'\')">
					     <td colspan="2">'.longitudMin($datos["cliente"]).'</td>';

					for ($i=1; $i <= $d_max; $i++) {
					 	$p_cl =   "c".str_pad((int) $i, 2, "0", STR_PAD_LEFT)."";
						$p_tab =  "t".str_pad((int) $i, 2, "0", STR_PAD_LEFT)."";

						echo '<td>'.$datos["$p_cl"].'</td>
							 		<td class="'.fondo_cal($datos["$p_cl"], $datos["$p_tab"]).'">'.$datos["$p_tab"].'</td>';
						}

					 echo '</tr>';
				 }?>
		</table>
		 </div>
	 </div>
		 <div class="TabbedPanelsContent">
			 <div id="contenido02" class="listar2"></div>
		 </div>
		 <div class="TabbedPanelsContent">
			 <div id="contenido03" class="listar2"></div>
		 </div>
	 </div>
	</div>


<script language="JavaScript" type="text/javascript">
	var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab:0});
	var TabbedPanels = new Spry.Widget.TabbedPanels("TabbedPanels");

//	dragElement(document.getElementById(("mydiv")));
	</script>
