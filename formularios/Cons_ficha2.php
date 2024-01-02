<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>
<?php
	$Nmenu = '410';
	$mod   = $_GET['mod'];
	require_once('autentificacion/aut_verifica_menu.php');
	require_once('sql/sql_report_t.php');
	$tabla = "ficha";
	$bd = new DataBase();
	$archivo = "ficha2";
	$titulo = " FICHA RRHH ";
	$vinculo = "inicio.php?area=pestanas/add_$archivo&Nmenu=$Nmenu&mod=$mod";
	$usuario = $_SESSION['usuario_cod'];

	$sql01 = " SELECT ficha_status.codigo, ficha_status.descripcion
				 FROM control , ficha_status
				WHERE control.ficha_activo = ficha_status.codigo ";

	$query01    = $bd->consultar($sql01);
    $row02      = $bd->obtener_fila($query01,0);
	$cod_status = $row02[0];
    $status     = $row02[1];

	   if($b_add == "true"){
		   $r_add = '<a href="inicio.php?area=formularios/Bus_ficha_add2&Nmenu='.$Nmenu.'&mod='.$mod.'"><img src="imagenes/nuevo.bmp" alt="Agregar" title="Agregar Registro" width="22px" height="22px" border="null" class="imgLink"/></a>';
		}else{
        $r_add = '<img src="imagenes/loading2.gif" alt="Consultar Registro" title="Consultar Registro" title="Consultar Registro"
                                                width="20px" height="20px" border="null"/>';
       }


?>
<script language="JavaScript" type="text/javascript">
function Add_filtroX(){  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	var Nmenu    = $("#Nmenu").val();
	var mod      = $("#mod").val();
	var r_rol    = $("#r_rol").val();
	var rol      = $("#rol").val();
	var status   = $("#status").val();
	var usuario  = $("#usuario").val();
	var filtro   = $("#paciFiltro").val();
	var ficha    = $("#stdID").val();
	var b_cons   = $("#b_cons").val();
	var b_add    = $("#b_add").val();
	var b_mod    = $("#b_mod").val();
	var b_eli    = $("#b_eli").val();
	r_cliente = $("#r_cliente").val();
	var error = 0;
    var errorMessage = ' ';

	if( rol == ""){
    var errorMessage = errorMessage + ' \n El Campo rol Es Requerido ';
	var error      = error+1;
	}

	if(error == 0){
	var contenido = "listar";
	 $("#img_actualizar").remove();
	 $("#listar").html("<img src='imagenes/loading.gif' /> Procesando, espere por favor...");
	ajax=nuevoAjax();
			ajax.open("POST", "ajax/Add_fic_ficha2.php", true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4){
		        document.getElementById(contenido).innerHTML = ajax.responseText;
				$("#cont_img").html("<img class='imgLink' id='img_actualizar' src='imagenes/actualizar.png' border='0'                               onclick='Add_filtroX()'>");
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("Nmenu="+Nmenu+"&mod="+mod+"&r_rol="+r_rol+"&rol="+rol+"&r_cliente="+r_cliente+"&status="+status+"&usuario="+usuario+"&filtro="+filtro+"&ficha="+ficha+"&b_cons="+b_cons+"&b_add="+b_add+"&b_mod="+b_mod+"&b_eli="+b_eli+"");

	}else{
		 	 alert(errorMessage);
	}
}
</script>
<div align="center" class="etiqueta_title"> CONSULTA <?php echo $titulo;?> </div> <hr />
<div id="Contenedor01"></div>
<fieldset>
<legend>Filtros:</legend>
	<table width="100%">
		<tr><td width="10%"><?php echo $leng["rol"];?>: </td>
			<td width="14%"><select name="rol" id="rol" style="width:120px;" required>
					<?php
					echo $select_rol;
				$query01 = $bd->consultar($sql_rol);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?></select></td>
            <td width="10%">Status: </td>
			<td width="14%"><select  name="status" id="status" style="width:120px;">
					<option value="<?php echo $cod_status?>"><?php echo $status; ?></option>
					<?php
				$sql01 = "SELECT ficha_status.codigo, ficha_status.descripcion
                            FROM ficha_status
                           WHERE ficha_status.`status` = 'T'
						     AND ficha_status.codigo <> '$cod_status'
                           ORDER BY 2 ASC";
	   			$query01 = $bd->consultar($sql01);
		 		while($row01=$bd->obtener_fila($query01,0)){
					 echo '<option value="'.$row01[0].'">'.$row01[1].'</option>';
			   }?><option value="TODOS">TODOS</option></select></td>
               <td width="10%">Filtro:</td>
		<td width="14%" id="select01">
			<select id="paciFiltro" onchange="EstadoFiltro(this.value)" style="width:120px">
				<option value="TODOS"> TODOS</option>
				<option value="codigo"> <?php echo $leng['ficha']?> </option>
				<option value="cedula"><?php echo $leng["ci"];?> </option>
				<option value="trabajador"><?php echo $leng["trabajador"];?> </option>
                <option value="nombres"> Nombre </option>
                <option value="apellidos"> Apellido </option>
		</select></td>
	  <td td width="10%"><?php echo $leng["trabajador"];?>:</td>
      <td colspan="14%"><input  id="stdName" type="text" size="22" disabled="disabled" />
	      <input type="hidden" name="trabajador" id="stdID" value=""/></td>
             <td id="cont_img" width="4%"><img class="imgLink" id="img_actualizar" src="imagenes/actualizar.png" border="0"
                                        onclick=" Add_filtroX()" ></td>
			<td width="1%">&nbsp;<input type="hidden" name="Nmenu" id="Nmenu" value="<?php echo $Nmenu;?>" />
            <input type="hidden" name="mod" id="mod" value="<?php echo $mod;?>" />
          <input type="hidden" name="r_rol" id="r_rol" value="<?php echo $_SESSION['r_rol'];?>"/>
             <input type="hidden" name="r_cliente" id="r_cliente" valuee="<?php echo $_SESSION['r_cliente'];?>"/>
             <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['usuario_cod'];?>"/>
             </td>
</tr>
</table>
</fieldset>
<div id="listar"><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="22%" class="etiqueta"><?php echo $leng["rol"];?></th>
			<th width="10%" class="etiqueta"><?php echo $leng["trabajador"];?></th>
    		<th width="10%" class="etiqueta"><?php echo $leng["ci"];?></th>
			<th width="30%" class="etiqueta">Nombre</th>
  			<th width="10%" class="etiqueta">Fecha Ult. <br />Actualizacion</th>
            <th width="10%" class="etiqueta">Status</th>
		    <th width="8%" align="center"><?php echo $r_add; ?></th>
		</tr>
    <?php


	$usuario = $_SESSION['usuario_cod'];
	$valor = 0;

	$FROM = "  FROM ficha, ficha_status, control, trab_roles, roles ";
	$WHERE  = " WHERE ficha.cod_ficha = trab_roles.cod_ficha
                  AND trab_roles.cod_rol = roles.codigo
                  AND ficha.cod_ficha_status = ficha_status.codigo
                  AND ficha.cod_ficha_status = control.ficha_activo
                  AND ficha.fec_us_mod  BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND CURDATE()";

	if($_SESSION['r_rol'] == "T"){
		$FROM   .= "  ,usuario_roles ";
		$WHERE  .= " AND usuario_roles.cod_usuario = '$usuario'  AND trab_roles.cod_rol = usuario_roles.cod_rol ";
	}

	if($_SESSION['r_cliente'] == "T"){
		$FROM   .= " ,usuario_clientes ";
		$WHERE  .= "   AND usuario_clientes.cod_usuario =  '$usuario' AND ficha.cod_ubicacion  = usuario_clientes.cod_ubicacion ";
	}

	$sql = " SELECT ficha.cod_ficha, ficha.cedula,
	                CONCAT(ficha.apellidos, ' ',ficha.nombres) AS nombres,  roles.descripcion AS rol,
					ficha.fec_us_mod, ficha_status.descripcion AS status
			  $FROM
              $WHERE
		      ORDER BY ficha.fec_us_mod DESC ";


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

	   if($b_cons == "true"){
		   $r_cons = '<a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=consultar"><img src="imagenes/consultar.png" alt="Consultar" title="Consultar Registro" width="20" height="20" border="null"/></a>';
		}
	   if($b_mod == "true"){
		   $r_mod = '<a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20" height="20" border="null"/>';
		}
	   if($b_eli == "true"){
		   $r_eli = '<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/>';
		}

        echo '<tr class="'.$fondo.'">
                  <td>'.longitud($datos["rol"]).'</td>
			      <td>'.$datos["cod_ficha"].'</td>
				  <td>'.$datos["cedula"].'</td>
                  <td>'.longitud($datos["nombres"]).'</td>
				  <td>'.$datos["fec_us_mod"].'</td>
				  <td>'.$datos["status"].'</td>
				  <td align="center">'.$r_cons.'&nbsp;'.$r_mod.'&nbsp;'.$r_eli.'</td>
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
          return "autocompletar/tb/trabajador.php?q="+this.text.value +"&filtro="+filtroValue+"&r_cliente="+r_cliente+"&r_rol="+r_rol+"&usuario="+usuario+""});
</script>
