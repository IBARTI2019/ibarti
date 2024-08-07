<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
	$Nmenu = '452';
	require_once('autentificacion/aut_verifica_menu.php'); 
	require_once('sql/sql_report_t.php');
	$tabla = "control_armamento";
	$bd = new DataBase();
	$archivo = "prod_movimiento";
	$titulo = " CONTROL MOVIMIENTO DE INVENTARIO ";
	$vinculo = "inicio.php?area=pestanas/add_$archivo&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod']."&archivo=$archivo";

	$cod_linea     = "TODOS";
    $linea         = "TODOS";

	$cod_sub_linea = "TODOS";
    $sub_linea     = "TODOS";

	$cod_tipo_mov    = "TODOS";
    $tipo_mov        = "TODOS";

	$cod_estado    = "TODOS";
    $estado        = "TODOS";


?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	var Nmenu      = $("#Nmenu").val();
	var mod        = $("#mod").val();
	var tb         = $("#tabla").val();
	var linea      = $("#linea").val();
	var sub_linea  = $("#sub_linea").val();
	var tipo_mov   = $("#tipo_mov").val();
	var estado     = $("#estado").val();
	var filtro    = $("paciFiltro").val();
	var producto  = $("#stdID").val();
	var error      = 0;
    var errorMessage = ' ';
	if(error == 0){
	var contenido = "listar";
	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	ajax=nuevoAjax();
			ajax.open("POST", "ajax/Add_prod_movimiento.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("Nmenu="+Nmenu+"&mod="+mod+"$tb="+tb+"&linea="+linea+"&sub_linea="+sub_linea+"&tipo_mov="+tipo_mov+"&estado="+estado+"&filtro="+filtro+"&producto="+producto+"");
	}else{
		 	 alert(errorMessage);
	}
}
</script>
<div align="center" class="etiqueta_title">CONSULTA <?php echo $titulo;?></div>
<div id="Contenedor01"></div>
<fieldset>
<legend>Filtros:</legend>
	<table width="100%">
		<tr><td width="10%">Linea: </td>
			<td width="14%"><select name="linea" id="linea" style="width:120px;"
                                    onchange="Add_Sub_Linea(this.value, 'contenido_sub_linea', 'T', '120')">
					<option value="<?php echo $cod_linea?>"><?php echo $linea; ?></option>
					<?php
	   			$query01 = $bd->consultar($sql_linea);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
            <td width="10%">Sub Linea: </td>
			<td width="14%" id="contenido_sub_linea"><select name="sub_linea" id="sub_linea" style="width:120px;">
                                     <option value="<?php echo $cod_sub_linea?>"><?php echo $sub_linea; ?></option>
                            </select></td>
           <td width="12%">Tipo Mov.:</td>
			<td width="14%"><select  name="tipo_mov" id="tipo_mov" style="width:110px;">
					<option value="<?php echo $cod_tipo_mov?>"><?php echo $tipo_mov; ?></option>
					<?php
	   			$query01 = $bd->consultar($sql_tipo_mov);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
        <td width="10%"><?php echo $leng['estado'];?>: </td>
			<td width="14%"><select  name="estado" id="estado" style="width:120px;">
					<option value="<?php echo $cod_estado?>"><?php echo $estado; ?></option>
					<?php
	   			$query01 = $bd->consultar($sql_estado);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
			 <td width="4%" id="cont_img"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                                onclick=" Add_filtroX()"  /></td>
             <td><input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
                 <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
                 <input type="hidden" name="archivo" id="archivo" value="<?php echo $archivo;?>" />
                 <input type="hidden" name="tabla" id="tabla" value="<?php echo $tabla;?>"/></td>
            </tr>
     <tr>
     <td>Filtro Prod.:</td>
<td id="select01">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
				<option value="TODOS"> TODOS</option>
				<option value="codigo">codigo</option>
				<option value="serial">Serial</option>
                <option value="descripcion">Descripcion</option>
		</select></td>
	 <td>Producto:</td>

      <td colspan="4"><input  id="stdName" type="text" style="width:180px" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value=""/></td>
       </tr>
</table>
</fieldset>
<div id="listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
           <th width="8%" class="etiqueta">Codigo</th>
            <th width="8%" class="etiqueta">Fecha</th>
            <th width="10%" class="etiqueta">Producto</th>
			<th width="10%" class="etiqueta">Serial</th>
  			<th width="16%" class="etiqueta"><?php echo $leng['estado'];?></th>
            <th width="20%" class="etiqueta"><?php echo $leng['cliente'];?></th>
             <th width="15%" class="etiqueta">Tipo Movimiento</th>
		    <th width="5%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Agregar Registro" title="Agregar Registro" width="20px" height="20px" border="null"/></a></th>
		</tr>
    <?php
	$usuario = $_SESSION['usuario_cod'];
	$valor = 0;
	$sql = " SELECT prod_movimiento.codigo, prod_movimiento.fecha,
	                productos.item AS serial,
				    prod_movimiento.cod_producto, productos.descripcion AS producto,
				    prod_mov_tipo.descripcion AS mov_tipo, estados.descripcion AS estado,
					clientes.nombre AS cliente, clientes_ubicacion.descripcion AS ubicacion,
	   			    prod_movimiento.`status`
			   FROM prod_movimiento, productos, clientes_ubicacion, estados, clientes, control, prod_mov_tipo
			  WHERE prod_movimiento.cod_producto = productos.codigo
			    AND prod_movimiento.cod_cliente = clientes.codigo
			    AND prod_movimiento.cod_ubicacion = clientes_ubicacion.codigo
			    AND clientes_ubicacion.cod_estado = estados.codigo
			    AND prod_movimiento.cod_mov_tipo =  prod_mov_tipo.codigo
				AND prod_movimiento.fecha  BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()
		   ORDER BY 2 DESC ";

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
	//</a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/>
	   $Borrar = "Borrar01('".$datos[0]."')";
        echo '<tr class="'.$fondo.'">
				  <td class="texo">'.longitud($datos["codigo"]).'</td>
				  <td class="texo">'.conversion($datos["fecha"]).'</td>
				  <td class="texo">'.longitudMin($datos["producto"]).'</td>
                  <td class="texo">'.longitudMin($datos["serial"]).'</td>
				  <td class="texo">'.longitudMin($datos["estado"]).'</td>
				  <td class="texo">'.longitudMin($datos["cliente"]).'</td>
				  <td class="texo">'.longitudMin($datos["mov_tipo"]).'</td>
				  <td align="center"><a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/></td>
            </tr>';
        } ?></table>
</div>
<script type="text/javascript">
	filtroId = document.getElementById("paciFiltro");
	filtroIndice = filtroId.selectedIndex;
	filtroValue  = filtroId.options[filtroIndice].value;

    new Autocomplete("stdName", function() {
        this.setValue = function(id) {
            document.getElementById("stdID").value = id; // document.getElementsByName("stdID")[0].value = id;
        }
        if (this.isModified) this.setValue("");
        if (this.value.length < 1) return ;
          return "autocompletar/tb/producto.php?q="+this.text.value +"&filtro="+filtroValue+""});
</script>
