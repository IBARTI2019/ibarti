<?php
include_once "../funciones/funciones.php";
require "../autentificacion/aut_config.inc.php";
require "../".class_bd;
require "../".Leng;
$bd = new DataBase();


$region          = $_POST['region'];
$estado          = $_POST['estado'];
$cliente          = $_POST['estado'];
$ubicacion          = $_POST['ubicacion'];
$rol             = $_POST['rol'];
$contrato        = $_POST['contrato'];
$carnet_vencido  = $_POST['carnet_vencido'];
$foto            = $_POST['foto'];

$trabajador      = $_POST['trabajador'];

		$where = " WHERE v_ficha.cod_ficha_status = control.ficha_activo ";

	if($region != "TODOS"){
		$where .= " AND v_ficha.cod_region = '$region' ";
	}

	if($estado != "TODOS"){
		$where .= " AND v_ficha.cod_estado = '$estado' ";  // cambie AND asistencia.co_cont = '$contracto'
	}

	if($cliente != "TODOS"){
		$where .= " AND v_ficha.cod_cliente = '$cliente' ";
	}
	
	if($ubicacion != "TODOS"){
		$where .= " AND v_ficha.cod_ubicacion = '$ubicacion' ";
	}

	if($rol != "TODOS"){
		$where .= " AND v_ficha.cod_rol = '$rol' ";
	}

	if($contrato != "TODOS"){
		$where  .= " AND v_ficha.cod_contracto = '$contrato' ";
	}

	if($carnet_vencido != "TODOS"){
		$where .= " AND v_ficha.fec_carnet < '$date' ";
	}

	if($trabajador != NULL){
		$where  .= " AND v_ficha.cod_ficha = '$trabajador' ";
	}
	// QUERY A MOSTRAR //
	    $sql = "SELECT v_ficha.region, v_ficha.rol,
                       v_ficha.estado, v_ficha.ciudad,
                       v_ficha.contracto, v_ficha.cargo,
					   v_ficha.cod_ficha,
                       v_ficha.cedula, v_ficha.ap_nombre,
                       v_ficha.fec_carnet
                  FROM v_ficha, control
                  $where
              ORDER BY 2 ASC ";
?><table width="100%" border="0" align="center">
		<tr class="fondo00">
            <th width="20%" class="etiqueta"><?php echo $leng['rol']?></th>
            <th width="20%" class="etiqueta">Cargo</th>
            <th width="8%" class="etiqueta"><?php echo $leng['ficha']?></th>
            <th width="10%" class="etiqueta"><?php echo $leng['ci']?></th>
            <th width="24%" class="etiqueta"><?php echo $leng['trabajador']?></th>
            <th width="8%" class="etiqueta">Fec. Venc </th>
            <th width="10%" class="etiqueta">Foto</th>
  	</tr>
    <?php
	$valor = 0;
   $query = $bd->consultar($sql);

		while ($datos=$bd->obtener_fila($query,0)){
		if ($valor == 0){
			$fondo = 'fondo01';
		$valor = 1;
		}else{
			$fondo = 'fondo02';
			$valor = 0;
		}

	$filename = "../imagenes/fotos/".$datos["cedula"].".jpg";

	if($foto == "TODOS"){
		$imprimir = "SI";

	}elseif($foto == "S"){
	  if (file_exists($filename)) {
 		$imprimir = "SI";
		}else {
		$imprimir = "NO";
		}

	}elseif($foto == "N"){
	  if (file_exists($filename)) {
 		$imprimir = "NO";
		}else{
		$imprimir = "SI";
		}
	}else{
		$imprimir = "NO";
	}

		if($imprimir == "SI"){
        echo '<tr class="'.$fondo.'">
			      <td class="texto">'.$datos["rol"].'</td>
				  <td class="texto">'.$datos["cargo"].'</td>
				  <td class="texto">'.$datos["cod_ficha"].'</td>
				  <td class="texto">'.$datos["cedula"].'</td>
				  <td class="texto">'.longitud($datos["ap_nombre"]).'</td>
				  <td class="texto">'.$datos["fec_carnet"].'</td>';
				$filename = "imagenes/fotos/".$datos[7].".jpg";
		  echo "<td><a href='".$filename."'><img src='".$filename."?nocache=".time()."' border='0' width='45' height='60' alt='SIN FOTO' /></a></td>";
          echo  '</tr>';
        	}
		};?>
    </table>
