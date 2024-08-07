
<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
	$Nmenu = '720';
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$tabla = "prod_dotacion_clientes";
	$bd = new DataBase();
	$archivo = "prod_dotacion_clientes";
	$titulo = " Dotacion  ".$leng['ubicacion']."";
	$vinculo = "inicio.php?area=pestanas/add_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."&archivo=$archivo";
?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO //
	var Nmenu       = $("#Nmenu").val();
	var mod         = $("#mod").val();
    var archivo     = $("#archivo").val();
	var rol         = $("#rol").val();
	var periodo     = $("#periodo").val();
	var filtro      = $("#paciFiltro").val();
	var ficha       = $("#stdID").val();
	var error = 0;
    var errorMessage = ' Debe Seleccionar Un Campo ';

	if( rol == ""){
    var errorMessage = ' \n Debe seleccionar el Rol ';
	var error      = error+1;
	}

	if(error == 0){
	var contenido = "listar";
	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	ajax=nuevoAjax();
			ajax.open("POST", "ajax/Add_prod_dotacion_clientes.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				}
			}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("Nmenu="+Nmenu+"&mod="+mod+"&archivo="+archivo+"&rol="+rol+"&periodo="+periodo+"&filtro="+filtro+"&ficha="+ficha+"");
	}else{
		 	alert(errorMessage);
	}
}</script>
<div align="center" class="etiqueta_title"> Consulta <?php echo $titulo;?></div>
<div id="Contenedor01"></div>
<form name="form_reportes" id="form_reportes" action="<?php echo $archivo;?>"  method="post" target="_blank">
<fieldset>
<legend>Filtros:</legend>
	<table width="100%">
		<tr><td width="10%">Periodo: </td>
			<td width="14%"><select  name="periodo" id="periodo" style="width:120px;">
					<?php
			  $sql01   = "SELECT DISTINCT prod_dotacion_clientes.periodo
			                FROM prod_dotacion_clientes
                        ORDER BY 1 DESC ";
	   			$query01 = $bd->consultar($sql01);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[0].'</option>';
			   }?><option value="TODOS">TODOS</option></select></td>
			<td width="10%"><?php echo $leng['rol'];?>: </td>
			<td width="14%"><select  name="rol" id="rol" style="width:120px;" required>
					<?php
					echo $select_cl;
	   			$query01 = $bd->consultar($sql_cliente);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
               <td width="10%">Filtro:</td>
		<td width="14%" id="select01">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
				<option value="TODOS"> TODOS</option>
				<option value="codigo"><?php echo $leng['ubicacion'];?></option>
				<option value="nombre">Nombre de la Ubicacion</option>
               
		</select></td>
	  <td td width="10%"><?php echo $leng['ubicacion'];?>:</td>
      <td colspan="14%"><input  id="stdName" type="text" size="22" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value=""/></td>
               <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                                onclick=" Add_filtroX()"  /></td>
			 <td><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
        <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
        <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
         <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
            <input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/></td>

        </tr>
</table>
</fieldset>
</form>
<div id="listar" class="listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
			  <th width="8%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
            <th width="20%" class="etiqueta"><?php echo $leng['rol'];?></th>
			<th width="8%" class="etiqueta"><?php echo $leng['cliente'];?></th>
            <th width="26%" class="etiqueta"><?php echo $leng['ubicacion'];?></th>
            <th width="26%" class="etiqueta">Descripcion</th>
		    <th width="4%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Detalle" title="Detalle Registro" width="20px" height="20px" border="null"/></a></th>
		</tr>
    <?php
	$usuario = $_SESSION['usuario_cod'];
	$valor = 0;
    //cambiar query por prod_dotacion_clientes
	$sql = "SELECT prod_dotacion_clientes.codigo, prod_dotacion_clientes.fec_dotacion,
	                clientes_ubicacion.cod_cliente,clientes_ubicacion.descripcion AS trabajador,
					prod_dotacion_clientes.descripcion
               FROM clientes_ubicacion , prod_dotacion_clientes
              WHERE clientes_ubicacion.codigo = prod_dotacion_clientes.cod_ubicacion
			    AND prod_dotacion_clientes.fec_us_mod BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()
		   ORDER BY 1 DESC";
    //cambiar query
   $query = $bd->consultar($sql);

		while ($datos=$bd->obtener_fila($query,0)){
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}

	// $Modificar = "Add_Mod01('".$datos[0]."', 'modificar')";
	   $Borrar = "Borrar01('".$datos[0]."')";
        echo '<tr class="'.$fondo.'">
                  <td class="texto">'.$datos["codigo"].'</td>
				  <td class="texto">'.longitudMin($datos["fec_dotacion"]).'</td>
				  <td class="texto">'.longitud($datos["cod_cliente"]).'</td>
                  <td class="texto">'.$datos["trabajador"].'</td>
				  <td class="texto">'.longitud($datos["trabajador"]).'</td>
				  <td class="texto">'.longitud($datos["descripcion"]).'</td>
			      <td align="center"><a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/></td>
            </tr>';
        }
     echo '<input type="hidden" name="tabla" id="tabla" value="'.$tabla.'"/>';
	?>
    </table>
</div>
<script type="text/javascript">
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
          return "autocompletar/tb/ubicaciones.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
</script>
