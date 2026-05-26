<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
	$Nmenu = '483';
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$tabla = "prod_asignacion";
	$bd = new DataBase();
	$archivo = "prod_asignacion";
	$titulo = " Asignación de Productos ";
	$vinculo = "inicio.php?area=formularios/add_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."&archivo=$archivo";
?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){ 
	var Nmenu       = $("#Nmenu").val();
	var mod         = $("#mod").val();
    var archivo     = $("#archivo").val();
	var filtro      = $("#paciFiltro").val();
	var ficha       = $("#stdID").val();
	var error = 0;
    var errorMessage = ' Debe Seleccionar Un Campo ';

	if(error == 0){
	var contenido = "listar";
	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	ajax=nuevoAjax();
			ajax.open("POST", "ajax/Add_prod_asignacion.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0' onclick='Add_filtroX()'>");
				}
			}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var extras = "&fec_desde="+$('#fecha01').val()+"&fec_hasta="+$('#fecha02').val()+"&tipo="+$('#tipo').val()+"&cliente="+$('#cliente').val()+"&ubicacion="+$('#ubicacion').val();
		ajax.send("Nmenu="+Nmenu+"&mod="+mod+"&archivo="+archivo+"&filtro="+filtro+"&ficha="+ficha+extras);
	}else{
		 	alert(errorMessage);
	}
}

function ActivarUbicacion(codigo){
	if(codigo!='TODOS'){
		var valor = "ajax/Add_ubicacion_asignacion.php";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById('ubicacion').innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+codigo);
	}else{
		document.getElementById('ubicacion').innerHTML = '<option value="TODOS">TODOS</option>';
	}
}
</script>
<div align="center" class="etiqueta_title"> Consulta <?php echo $titulo;?></div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
<fieldset>
<legend>Filtros:</legend>
	<table width="100%">
		<tr>
			<td width="10%">Fecha Desde:</td>
			<td width="14%"><input type="text" name="fec_desde" id="fecha01" size="10" value="<?php echo date('01-m-Y');?>"></td>
			<td width="10%">Fecha Hasta:</td>
			<td width="14%"><input type="text" name="fec_hasta" id="fecha02" size="10" value="<?php echo date('d-m-Y');?>"></td>
            <td width="10%">Tipo:</td>
			<td width="14%">
                <select id="tipo" style="width:120px;">
                    <option value="TODOS">TODOS</option>
                    <option value="ASIGNACION">ASIGNACION</option>
                    <option value="DEVOLUCION">DEVOLUCION</option>
                </select>
            </td>
            <td width="28%">
				<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
				<input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
				<input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
				<input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
				<input type="hidden" name="r_cliente" id="r_cliente" value="<?php echo $_SESSION['r_cliente'];?>"/>
				<input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
			</td>
		</tr>
        <tr>
            <td>Cliente:</td>
            <td>
                <select id="cliente" style="width:150px;" onchange="ActivarUbicacion(this.value)">
                    <option value="TODOS">TODOS</option>
                    <?php
                        $sql = "SELECT codigo, nombre FROM clientes WHERE `status` = 'T' ORDER BY nombre ASC";
                        $query = $bd->consultar($sql);
                        while($datos=$bd->obtener_fila($query,0)){
                            echo '<option value="'.$datos[0].'">'.$datos[1].'</option>';
                        }
                    ?>
                </select>
            </td>
            <td>Ubicación:</td>
            <td>
                <select id="ubicacion" style="width:150px;">
                    <option value="TODOS">TODOS</option>
                </select>
            </td>
            <td>Trabajador:</td>
            <td id="select01">
                <select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
                    <option value="TODOS"> TODOS</option>
                    <option value="codigo"><?php echo $leng['ficha'];?></option>
                    <option value="cedula"><?php echo $leng['ci'];?></option>
                    <option value="trabajador"><?php echo $leng['trabajador'];?></option>
                </select>
            </td>
            <td><input id="stdName" type="text" size="22" disabled="disabled" placeholder="..." />
	            <input type="hidden" name="trabajador" id="stdID" value=""/>
            </td>
            <td id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0" onclick=" Add_filtroX()"  /></td>
        </tr>
	</table>
</fieldset>
</form>
<div id="listar" class="listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
			  <th width="8%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
            <th width="12%" class="etiqueta">Tipo</th>
            <th width="15%" class="etiqueta">Ubicacion</th>
			<th width="8%" class="etiqueta"><?php echo $leng['ficha'];?></th>
            <th width="20%" class="etiqueta"><?php echo $leng['trabajador'];?></th>
            <th width="25%" class="etiqueta">Descripcion</th>
		    <th width="4%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Detalle" title="Detalle Registro" width="20px" height="20px" border="null"/></a></th>
		</tr>
    <?php
     echo '<input type="hidden" name="tabla" id="tabla" value="'.$tabla.'"/>';
	?>
    </table>
</div>
<script type="text/javascript">
$(document).ready(function(){
    Add_filtroX();
});
	var fecha01 = new Spry.Widget.ValidationTextField("fecha01", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
		validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});
	var fecha02 = new Spry.Widget.ValidationTextField("fecha02", "date", {format:"dd-mm-yyyy", hint:"DD-MM-AAAA",
		validateOn:["blur", "change"], useCharacterMasking:true, isRequired:false});

	r_cliente = $("#r_cliente").val();
	r_rol     = $("#r_rol").val();
	usuario   = $("#usuario").val();
	filtroValue = $("#paciFiltro").val();

    new Autocomplete("stdName", function() {
        this.setValue = function(id) {
            document.getElementById("stdID").value = id; 
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
          return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
</script>
