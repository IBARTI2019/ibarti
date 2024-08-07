<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
	$Nmenu     = 444;
	$mod       = $_GET['mod'];
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$bd = new DataBase();
	$archivo  = "novedades2";
	$vinculo  = "inicio.php?area=formularios/Add_$archivo&Nmenu=$Nmenu&mod=$mod";
	$titulo   = "NOVEDADES REPUESTA";
?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	var Nmenu       = $("#Nmenu").val();
	var mod         = $("#mod").val();
    var archivo     = $("#archivo").val();
	var fecha_desde = $("#fecha_desde").val();
	var fecha_hasta = $("#fecha_hasta").val();
	var novedad     = $("#novedad").val();
	var clasif      = $("#clasif").val();
    var	cliente     = $("#cliente").val();
    var	ubicacion   = $("#ubicacion").val();
	var status      = $("#status").val();
	var perfil      = $("#perfil").val();
	var tipo      = 'respuesta';
	var ficha = $('#stdID').val();
	var error = 0;
    var errorMessage = ' ';
	 if( fechaValida(fecha_desde) !=  true || fechaValida(fecha_hasta) != true){
    var errorMessage = ' Campos De Fecha Incorrectas ';
	 var error = error+1;
	}

	if( cliente == ""){
    var errorMessage = ' El Campo Cliente Es Requerido ';
	var error      = error+1;
	}

	if(error == 0){
	var contenido = "listar";
	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	ajax=nuevoAjax();
			ajax.open("POST", "ajax/novedades.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("Nmenu="+Nmenu+"&mod="+mod+"&archivo="+archivo+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"&novedad="+novedad+"&clasif="+clasif+"&cliente="+cliente+"&ubicacion="+ubicacion+"&status="+status+"&perfil="+perfil+"&tipo="+tipo+"&ficha="+ficha);
	}else{
		 alert(errorMessage);
	}
}</script>
<div align="center" class="etiqueta_title"> CONSULTA <?php echo $titulo;?> </div> <hr />
<div id="Contenedor01"></div>
<form name="form_consulta" id="form_consulta" action="<?php echo $archivo;?>" method="post" target="_blank">
<fieldset>
<legend>Filtros:</legend>
	<table width="100%" class="etiqueta">
		<tr>
         <td width="10%">Fecha Desde: </td>
        <td width="14%" id="fecha01"><input type="text" name="fecha_desde" id="fecha_desde" size="9"  onclick="javascript:muestraCalendario('form_consulta', 'fecha_desde');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_consulta', 'fecha_desde');" border="0" width="17px"></td>
        <td width="10%">Fec. Hasta:</td>
		 <td width="14%" id="fecha02"><input type="text" name="fecha_hasta" id="fecha_hasta"  size="9"  onclick="javascript:muestraCalendario('form_consulta', 'fecha_hasta');">&nbsp;<img src="imagenes/icono-calendario.gif" onclick="javascript:muestraCalendario('form_consulta', 'fecha_hasta');" border="0" width="17px"></td>

        <td width="10%">Novedades: </td>
			<td width="14%"><select  name="novedad" id="novedad" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
	   			$query01 = $bd->consultar($sql_nov_novedad);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			<td width="10%">Clasificacion: </td>
			<td width="14%"><select  name="clasif" id="clasif" style="width:120px;">
					<option value="TODOS">TODOS</option>
					<?php
	   			$query01 = $bd->consultar($sql_nov_clasif2);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>

			   
			 <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                                onclick=" Add_filtroX()" ></td>
            </tr>
          
           <tr>
           <td><?php echo $leng["cliente"];?>:</td>
			<td><select name="cliente" id="cliente" style="width:120px;" onchange="Add_Cl_Ubic(this.value, 'contenido_ubic', 'T', '120')"  required>
				<?php
				echo $select_cl;
	   			$query01 = $bd->consultar($sql_cliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			<td><?php echo $leng["ubicacion"];?>: </td>
			<td id="contenido_ubic"><select name="ubicacion" id="ubicacion" style="width:120px;">
					                        <option value="TODOS">TODOS</option>
                                    </select></td>

           	<td>Status: </td>
            <td><select name="status" id="status" style="width:120px;">
                <option value="TODOS">TODOS</option>
                <?php

            $query01 = $bd->consultar($sql_nov_status);
            while($row01=$bd->obtener_fila($query01,0)){
                 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
           }?></select></td>
        <input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
        <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
        <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
        <input type="hidden" name="perfil" id="perfil" value="<?php echo $_SESSION['cod_perfil'];?>" />
		
		<td td width="10%"><?php echo $leng["trabajador"]."(Ficha)";?>:</td>
		<td ><input  id="stdName" type="text" onclick="vaciar()" size="22"  />
		<input type="hidden" name="trabajador" id="stdID" value=""/></td>
        </tr>
</table>
</fieldset>
</form>
<div id="listar"><table width="100%" border="0" align="center" >
		<tr class="fondo00">
			<th width="6%" class="etiqueta">Codigo</th>
            <th width="6%" class="etiqueta">Fecha</th>
			<th width="18%" class="etiqueta">Novedad</th>
            <th width="20%" class="etiqueta"><?php echo $leng["trabajador"];?></th>
			<th width="18%" class="etiqueta">Clasificacion</th>
  			<th width="22%" class="etiqueta"><?php echo $leng["cliente"];?></th>
            <th width="12%" class="etiqueta">Status</th>
		    <th width="6%" align="center"><!--<a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null"/></a>--></th>
		</tr>
<?php
$sql = " SELECT nov_procesos.codigo,  nov_procesos.fec_us_ing,
				novedades.descripcion AS novedades,  ficha.cod_ficha,
				CONCAT(ficha.nombres, ' ',ficha.apellidos) AS trabajador, nov_clasif.descripcion AS clasif,
				clientes.nombre AS cliente,  nov_status.descripcion  AS status
		   FROM nov_procesos, novedades, clientes, nov_clasif,
				nov_status, ficha, nov_perfiles,
				clientes_ubicacion, nov_tipo
		  WHERE nov_procesos.fec_us_ing = CURDATE()
			AND nov_procesos.cod_novedad = novedades.codigo
			AND novedades.cod_nov_clasif = nov_clasif.codigo
			AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
			AND nov_perfiles.cod_perfil = '".$_SESSION['cod_perfil']."'
			AND nov_perfiles.respuesta = 'T'
			AND nov_perfiles.status = 'T'
			AND nov_procesos.cod_ubicacion = clientes_ubicacion.codigo
            AND clientes_ubicacion.cod_cliente = clientes.codigo
			AND nov_procesos.cod_nov_status = nov_status.codigo
			AND nov_procesos.cod_ficha = ficha.cod_ficha
			AND novedades.cod_nov_tipo = nov_tipo.codigo
			AND nov_tipo.kanban = 'F'
	   ORDER BY 2 DESC ";
		      $query = $bd->consultar($sql);

				 $valor = 0;
	    while($row02=$bd->obtener_fila($query,0)){

		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}
		echo'<tr class="'.$fondo.'">
				<td class="texto">'.$row02["codigo"].'</td>
				<td class="texto">'.Conversion($row02["fec_us_ing"]).'</td>
				<td class="texto">'.longitudMin($row02["novedades"]).'</td>
				<td class="texto">'.longitud($row02["trabajador"]).'</td>
				<td class="texto">'.longitudMin($row02["clasif"]).'</td>
				<td class="texto">'.longitud($row02["cliente"]).'</td>
				<td class="texto">'.longitudMin($row02["status"]).'</td>
				<td class="texto"><a href="inicio.php?area=formularios/Add_'.$archivo.'&codigo='.$row02['codigo'].'&Nmenu='.$Nmenu.'&mod='.$mod.'&metodo=modificar"><img src="imagenes/detalle.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null"/></a></td>
				</td></tr>';
		}?>
    </table>
</div>
<script>
new Autocomplete("stdName", function() {
							this.setValue = function(id) {
            document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return;
		return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro=codigo"});
		
		function vaciar(){
			$('#stdName').val('');
			$('#stdID').val('');
		}
</script>