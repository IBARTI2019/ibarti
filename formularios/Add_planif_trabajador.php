<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>

<script language="JavaScript" type="text/javascript">

function Borrar_Campo(cod, fecha_desde, fecha_hasta){
	var proced       = $("#proced").val();
	var usuario      = $("#usuario").val();


	if (confirm("� Esta Seguro De Borrar Este Registro?")) {
	 	var valor = "scripts/sc_planif_trabajador.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4){
				document.getElementById("Mensaje").innerHTML = ajax.responseText;
				Planificacion_Det();
			}
		}

	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send("codigo="+cod+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&trabajador=''&cliente=''&ubicacion=''&rotacion=''&rotacion_n=''&excepcion=''&usuario="+usuario+"&proced="+proced+"&metodo=borrar");
     } else{
       return false;
    }
}

function Planificacion_Det(){
	var usuario    = $("#usuario").val();
	var codigo     = $("#stdID").val();
	var valor      = "ajax/Add_pl_trabajador.php";

	ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
			document.getElementById("listarContenido").innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("trab="+codigo+"&usuario="+usuario+"");
}

function Filtrar_ubicacion(idX,  name, Contenedor, px, evento){

	var periodo   = $("#periodo").val();
	var valor     = "ajax/Add_select_ubicacion_periodoCL.php";
	var usuario   = $("#usuario").val();

		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
			document.getElementById(Contenedor).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+idX+"&periodo="+periodo+"&usuario="+usuario+"&name="+name+"&tamano="+px+"&evento="+evento+"");
}


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

function Filtrar(){
	var trab      = $("#stdID").val();
	var error = false;
  var errorMessage = '';

	if(trab == '') {
	  var error = true;
	  var errorMessage = 'Debe Seleccionar Un Trabajador';
	}
	if(error == false){
		Planificacion_Det();
	}else{
		alert(errorMessage);
	}

}


function Metodo(cod, metodo){

	var usuario    = $("#usuario").val();
	var Contenedor = "listarContenido";
	var valor  = "ajax/Add_pl_trabajador_mod.php";

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

function Actualizar(){

	var Nmenu         = $("#Nmenu").val();
  var mod           = $("#mod").val();
	var proced        = $("#proced").val();
	var usuario       = $("#usuario").val();
	var fecha_desde  = $("#fecha_desde").val();
	var fecha_hasta  = $("#fecha_hasta").val();

	var codigo       = $("#codigo").val();
	var periodo      = $("#periodo").val();
	var trabajador  =  $("#stdID").val();
	var cliente      = $("#cliente").val();
	var ubicacion    = $("#ubicacion").val();
	var rotacion     = $("#rotacion").val();
	var rotacion_n    = $("#rotacion_n").val();
  var excepcion     = $('input[name=excepcion]:checked', '#form_mod').val();
	var metodo     = $("#metodo").val();


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
			Planificacion_Det();

   		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  ajax.send("codigo="+codigo+"&periodo="+periodo+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&trabajador="+trabajador+"&cliente="+cliente+"&ubicacion="+ubicacion+"&rotacion="+rotacion+"&rotacion_n="+rotacion_n+"&excepcion="+excepcion+"&usuario="+usuario+"&proced="+proced+"&metodo="+metodo+"");
	 }else{
	alert(errorMessage);
	 }
}

function Calendario(){
	var periodo      = $("#periodo").val();
	var trabajador  =  $("#stdID").val();
	var usuario       = $("#usuario").val();
	var error = false;
	var errorMessage = "";

	if((trabajador == '') || (trabajador == null)) {
	var error = true;
	var errorMessage = errorMessage + 'Debe Seleccionar un Trabajador \n';
	}

	if(error == false){
		var valor = "ajax/Add_planif_trab_calendario.php";
		ajax=nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){

					document.getElementById("listarContenido").innerHTML = ajax.responseText;
		   	}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		  ajax.send("codigo="+periodo+"&trabajador="+trabajador+"&usuario="+usuario+"");

	}else{
		alert(errorMessage);
	}

}
</script>
<?php
	$Nmenu   = '404';
	$mod     = $_GET['mod'];
	$metodo  = $_GET['metodo'];
	$proced  = "p_pl_trabajador";

	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');

	$href= "formularios/Cons_pl_cliente&Nmenu";

if($metodo == 'modificar'){

	$cod_cliente   = $_GET['cod_cliente'];
	$cod_ubicacion = $_GET['cod_ubicacion'];
}else{
	$cod_cliente   = "";
	$cod_ubicacion = "";
}

$bd = new DataBase();
	$sql04 = "SELECT pl_trab_apertura.codigo, pl_trab_apertura.fecha_inicio,
	                 pl_trab_apertura.fecha_fin, IFNULL(clientes.nombre, 'Seleccione...' ) cliente,
                                               IFNULL(clientes_ubicacion.descripcion , 'Seleccione...') ubicacion
					    FROM pl_trab_apertura  LEFT JOIN clientes ON clientes.codigo = '$cod_cliente'
              LEFT JOIN clientes_ubicacion ON clientes_ubicacion.codigo = '$cod_ubicacion'
             WHERE pl_trab_apertura.`status` = 'T'";

 	$query04 = $bd->consultar($sql04);

	 $row04=$bd->obtener_fila($query04,0);
	 $periodo     = $row04[0];
	 $fec_inicio  = $row04[1];
	 $fec_fin     = $row04[2];
	 $cliente     = $row04[3];
 	 $ubicacion   = $row04[4];

$SQL_PAG = "SELECT pl_cliente.*, cargos.descripcion cargo, turno.descripcion turno
		          FROM pl_cliente_apertura, pl_cliente, cargos, turno
					   WHERE pl_cliente_apertura.`status` = 'T'
		           AND pl_cliente.cod_cliente   = '$cod_cliente'
							 AND pl_cliente.cod_ubicacion = '$cod_ubicacion'
		           AND pl_cliente.cod_cargo = cargos.codigo
		           AND pl_cliente.cod_turno = turno.codigo
		     		 ORDER BY 1 ASC ";

	$sql_cliente = "SELECT clientes_ubicacion.cod_cliente, clientes.nombre AS cliente
				      FROM usuario_clientes ,  clientes_ubicacion , clientes
			         WHERE usuario_clientes.cod_usuario = '$usuario'
				       AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo
				       AND clientes_ubicacion.`status` = 'T'
				       AND clientes_ubicacion.cod_cliente = clientes.codigo
							  AND clientes_ubicacion.cod_cliente != '$cod_cliente'
				       AND clientes.`status` = 'T'
			      GROUP BY clientes_ubicacion.cod_cliente
			      ORDER BY 2 ASC";

	$sql_ubicacion = "SELECT usuario_clientes.cod_ubicacion, clientes_ubicacion.descripcion AS ubicacion
											FROM usuario_clientes , clientes_ubicacion
										 WHERE usuario_clientes.cod_usuario = '$usuario'
											 AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo
											 AND clientes_ubicacion.cod_cliente = '$cod_cliente'
											 AND clientes_ubicacion.codigo != '$cod_ubicacion'
											 AND clientes_ubicacion.`status` = 'T'
									ORDER BY 2 ASC ";



$title = "Planificacion de ".$leng['trabajador'].", Periodo ($periodo), Desde :".conversion($fec_inicio).", Hasta ".conversion($fec_fin)."";
		?>
<div align="center" class="etiqueta_title"> <?php echo $title?></div>
<div id="Mensaje" class="mensaje"></div>
<hr />

<form id="form_01" name="form_01" action="" method="post">
	<table width="100%">
		<tr class="fondo00">
	 		<td width="20%" class="etiqueta">Filtro Trab.:</td>

			<td  id="select01">
				<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:160px">
					<option value="TODOS"> Seleccione...</option>
					<option value="codigo"> C&oacute;digo </option>
					<option value="cedula"> C&eacute;dula </option>
					<option value="trabajador"> Trabajador </option>
					<option value="nombres"> Nombre </option>
					<option value="apellidos"> Apellido </option>
				 </select></td>
				 <td width="20%" class="etiqueta"><?php echo $leng["trabajador"];?>:</td>
				<td ><input  id="stdName" type="text" style="width:170px" disabled="disabled" />
				<input type="hidden" name="trabajador" id="stdID" value=""/>
			 </td>
			 <td align="center">
				 <img src="imagenes/nuevo.bmp" alt="Agregar" title="Agregar Registro" onClick="Metodo('','agregar')" class="imgLink" width="20px" height="20px" border="null"/>
         <img src="imagenes/detalle.bmp" alt="Detalle" title="Detalle Registro" onclick="Filtrar()" class="imgLink" width="20px" height="20px" border="null"/>
				 <img src="imagenes/calendario.gif" onClick="Calendario()"  alt="Calendario" title="Cargar Calendario" class="imgLink">
			 <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>"/><input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>"/><input type="hidden" name="proced" id="proced" value="<?php echo $proced ;?>"/><input type="hidden" name="periodo" id="periodo" value="<?php echo $periodo;?>"/><input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario;?>"/><input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/><input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
		 </span></td>

	 </tr>
</table>
</form>
<div id="listarContenido"><table width="100%" border="0" align="center">
	<?php
	  $query = $bd->consultar($SQL_PAG);
		$valor = 1;
		$i     = 0;

		while ($datos=$bd->obtener_fila($query,0)){
		$i++;
		if ($valor == 0){
			$fondo = 'fondo01';
			$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo'<tr class="'.$fondo.'">
						<td width="23%" class="texto">'.longitud($datos["cargo"]).'</td>
						<td width="23%" class="texto">'.longitud($datos["turno"]).'</td>
						<td width="14%" class="texto">'.valorF($datos["excepcion"]).'</td>
						<td width="12%" class="texto">'.$datos["fecha_inicio"].'</td>
						<td width="12%" class="texto">'.$datos["fecha_fin"].'</td>
						<td width="10%" class="texto">'.$datos["cantidad"].'</td>
						<td width="6%" align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Modificar Registro"
						    border="null" width="20px" height="20px" class="imgLink"  onclick="Modificar(\''.$datos["codigo"].'\')"
							  />&nbsp;<img src="imagenes/borrar.bmp" alt="Borrar" title="Borrar Registro"  class="imgLink"
							  width="20px" height="20px" onclick="Borrar_Campo(\''.$datos["codigo"].'\', \''.$datos["fecha_inicio"].'\', \''.$datos["fecha_fin"].'\')"/></td>
					</tr>'; }?>

	</table></div>
<div align="center"><br/>
	<span class="art-button-wrapper">
				 <span class="art-button-l"> </span>
				 <span class="art-button-r"> </span>
		 <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
		 </span>
		</div>


		<script type="text/javascript">
			r_cliente = $("#r_cliente").val();
			r_rol     = $("#r_rol").val();
			usuario   = $("#usuario").val();
			filtroValue = $("#paciFiltro").val();

		    new Autocomplete("stdName", function() {
		        this.setValue = function(id) {
		          document.getElementById("stdID").value = id;
						}
		        if (this.isModified) this.setValue("");
		        if (this.value.length < 3) return ;
		          return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+"";
						});
		</script>
