<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<link rel="stylesheet" type="text/css" href="css/horarios.css"/>
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>

<?php
	$Nmenu = '446';
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
//	$bd = new DataBase();
	$archivo = "planif_trab_calendario";
	$titulo  = " Planificacion De ".$leng['trabajador']."  Calendario ";
	$vinculo = "../inicio.php?area=formularios/Cons_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."";

	$metodo   = "agregar";
	$proced   = "p_planif_trabajador";
	$cod_ficha = '';

?>
<script language="JavaScript" type="text/javascript">

function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var trabajador  = $( "#stdID").val();
	var fecha_desde = $( "#fecha_desde").val();
	var metodo      = "agregar";
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();

	var error = 0;
    var errorMessage = ' ';

	if( fechaValida(fecha_desde) !=  true) {
    	var errorMessage = ' Campos De Fecha Incorrectas ';
		 var error = error+1;
		}else if(trabajador == ""){
    		var errorMessage = ' Selecciones un Trabajador ';
	 		var error = error+1;
	}

	if(error == 0){
	  var parametros = {
						"trabajador":trabajador,
						"fecha_desde" : fecha_desde,
						"trabajador" : trabajador,

						"metodo":metodo,
						"Nmenu" : Nmenu,
						"mod" : mod,
						"archivo": archivo
				};
				$.ajax({
						data:  parametros,
						url:   'ajax/Add_planif_trab_calendario.php',
						type:  'post',
						beforeSend: function () {
							 $("#img_actualizar").remove();
							 $("#listarX").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
							},
						success:  function (response) {
							$("#listarX").html(response);
							$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
						    $("#cont_img2").show();
						},

					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}

				});

		}else{
			alert(errorMessage);
		    $("#cont_img2").hide();
		}
}

function Add_filtroX2(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var trabajador  = $( "#stdID").val();
	var fecha_desde = $( "#fecha_desde").val();
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();
	var metodo      = "imprimir";

	var error = 0;
    var errorMessage = ' ';

	if( fechaValida(fecha_desde) !=  true) {
    	var errorMessage = ' Campos De Fecha Incorrectas ';
		 var error = error+1;
		}else if(trabajador == ""){
    		var errorMessage = ' Selecciones un Trabajador ';
	 		var error = error+1;
	}

	if(error == 0){
	document.getElementById("form_reportes").submit();
	}
}

function Add_ajax_x(codigo, horario, cliente, ubicacion, trabajador, nivel, metodo){  // CODIGO = FECHA//

	var usuario     = $( "#usuario").val();
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var proced      = "p_planif_trab_diario";
//	alert(usuario);

	var error = 0;
    var errorMessage = ' ';

		if(error == 0){
			alert("p_planif_trab_diario");
		  var parametros = {
						"codigo":codigo,
						"horario" :horario,
						"cliente" : cliente,
						"ubicacion" : ubicacion,
						"trabajador" :trabajador,
						"nivel" : nivel,
						"usuario" : usuario,

						"Nmenu" : Nmenu,
						"mod" : mod,
						"metodo": metodo,
						"proced": "p_planif_trab_diario"
				};
				$.ajax({
						data:  parametros,
						url:   'scripts/sc_planif_trab_calendario.php',
						type:  'post',

						success:  function (response) {
						$("#Contenedor01").html(response);
						Add_filtroX();
						},

					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}

				});

		}else{
			alert(errorMessage);
		}
}

function Mod_filtroX(fecha_X, horario, cliente, ubicacion, trabajador){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //

	var metodo      = "modificar";
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();
	var usuario     = $( "#usuario").val();

	var error = 0;
    var errorMessage = ' ';

	if( fecha_X ==  "") {
    	var errorMessage = ' Campos De Fecha Incorrectas ';
		 var error = error+1;
		}else if(trabajador == ""){
    		var errorMessage = ' Selecciones un Trabajador ';
	 		var error = error+1;
	}

	if(error == 0){
	  var parametros = {
						"trabajador":trabajador,
						"fecha" : fecha_X,
						"cliente" : cliente,
 					    "ubicacion" : ubicacion,
 					    "horario" : ubicacion,
						"usuario" : usuario,

						"metodo":metodo,
						"Nmenu" : Nmenu,
						"mod" : mod,
						"archivo": archivo
				};
				$.ajax({
						data:  parametros,
						url:   'ajax/Add_planif_trab_calendario_mod.php',
						type:  'post',
						beforeSend: function () {
							 $("#img_actualizar").remove();
							 $("#listarX").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
							},
						success:  function (response) {
							$("#listarX").html(response);
							$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
						    $("#cont_img2").show();
						},

					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}

				});

		}else{
			alert(errorMessage);
		    $("#cont_img2").hide();
		}
}

function ActualizarTrab(fecha, ficha, nivel){

	var cliente     = $( "#cliente").val();
	var ubicacion   = $( "#ubicacion").val();
	var horario     = $( "#horario").val();

	var metodo      = "modificar";
	var Nmenu       = $( "#Nmenu").val();
	var mod         = $( "#mod").val();
	var archivo     = $( "#archivo").val();
	var usuario     = $( "#usuario").val();

	var error = 0;
    var errorMessage = ' ';

	if( fecha ==  "") {
    	var errorMessage = ' Campos De Fecha Incorrectas ';
		 var error = error+1;
		}else if(ficha == ""){
    		var errorMessage = ' Selecciones un Trabajador ';
	 		var error = error+1;
	}

	if(error == 0){
	  var parametros = {
						"codigo" : fecha,
						"trabajador":ficha,
						"nivel" : nivel,
						"cliente" : cliente,
 					    "ubicacion" : ubicacion,
 					    "horario" : horario,
						"usuario" : usuario,
						"proced": "p_planif_trab_diario",

						"metodo":metodo,
						"Nmenu" : Nmenu,
						"mod" : mod,
						"archivo": archivo
				};
				$.ajax({
						data:  parametros,
			             url:   'scripts/sc_planif_trab_calendario.php',
						type:  'post',

						success:  function (response) {
						// $("#listarX").html(response);
						 Add_filtroX();


						},

					error: function (xhr, ajaxOptions, thrownError) {
       				alert(xhr.status);
        			alert(thrownError);}
				});

		}else{
			alert(errorMessage);
		    $("#cont_img2").hide();
		}
}
</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="reportes/rp_<?php echo $archivo;?>.php" method="post" target="_blank">
<fieldset>
<legend>Filtros:</legend>
	<table width="100%" border="1">
		<tr><td width="10%">Fecha Desde:</td>
		 <td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="10" onClick="javascript:muestraCalendario('form_reportes', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onClick="javascript:muestraCalendario('form_reportes', 'fecha_desde');" border="0" width="17px"></td>

		<td width="10%">Filtro:</td>
		<td width="14%"><select id="paciFiltro" onChange="EstadoFiltro(this.value)" style="width:120px">
				<option value="">Seleccione...</option>
				<option value="codigo"><?php echo $leng['ficha']?></option>
				<option value="cedula"><?php echo $leng['ci']?></option>
				<option value="trabajador"><?php echo $leng['trabajador']?></option>
                <option value="apellidos">Apellido</option>
                <option value="nombres">Nombre</option>
		</select><br />
       	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></td>
	  <td width="10%"><?php echo $leng['trabajador']?>:</td>
      <td width="32%"><input name="trabajador" id="stdName" type="text" style="width:300px" disabled="disabled" value=""/>
	        <span id="input01"><input type="hidden" name="trabajador" id="stdID" value="<?php echo $cod_ficha;?>"/>	<br />
            <span class="textfieldRequiredMsg">Debe De Seleccionar Un Campo De la Lista.</span>
            <span class="textfieldInvalidFormatMsg">El Formato es Invalido</span> </span></td>
        <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" Add_filtroX()" alt="Consultar"></td>
        <td width="4%" id="cont_img2" style="display:none" ><img src="imagenes/printer.png" class="imgLink" width="25px" height="25px" onclick=" Add_filtroX2()" /></td>
		</tr>
      <tr><td colspan="4"><input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo;?>" />
            <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
            <input type="hidden" name="metodo" id="metodo" value="<?php echo $metodo;?>" />
            <input type="hidden" name="proced" id="proced" value="<?php echo $proced;?>" />
            <input type="hidden" name="href" id="href" value="<?php echo $vinculo;?>"/>
            <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
            <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td>
      </tr>
</table>
<div id="Contenedor01" class="mensaje"></div>
<div id="listarX">
</div>
<div align="center"><br/>
        <span class="art-button-wrapper">
            <span class="art-button-l"> </span>
            <span class="art-button-r"> </span>
        <input type="button" name="salir" id="salir" value="Salir" onClick="Vinculo('inicio.php?area=formularios/index')"
               class="readon art-button">
        </span>&nbsp;
</div>
</form>
<script language="javascript">

		r_cliente = $("#r_cliente").val();
	r_rol     = $("#r_rol").val();
	usuario   = $("#usuario").val();
	filtroValue = $("#paciFiltro").val();

    new Autocomplete("stdName", function() {
        this.setValue = function(id) {
            document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
          return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
</script>
