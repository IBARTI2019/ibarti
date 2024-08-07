<?php 
$bd = new DataBase();
$usuario = $_SESSION['usuario_cod'];

$sql = "SELECT codigo, descripcion FROM men_principal 
WHERE status = 'T' ORDER BY orden ASC";

$query = $bd->consultar($sql);

while ($rowMenu=$bd->obtener_fila($query,0)){

	$menu_id = $rowMenu[0];    

	echo'<li><a  onclick=""><span class="l"></span>
	<span class="r"></span><span class="t">'.$rowMenu[1].'</a></span>';

		// 02 modulos
	if($menu_id == "02"){ 			   
		$sql = "SELECT DISTINCT men_modulos.codigo, men_modulos.descripcion,
		men_modulos.link, men_modulos.orden
		FROM men_modulos ,  men_perfil_menu
		WHERE men_modulos.status = 'T' 
		AND men_perfil_menu.cod_men_perfil = '".$_SESSION['cod_perfil']."'
		AND men_modulos.codigo = men_perfil_menu.cod_menu_modulo
		ORDER BY men_modulos.orden ASC";
		$query02 = $bd->consultar($sql);

		echo '<ul>';	
		while ($rowSubMenu=$bd->obtener_fila($query02,0)){

			echo '<li><a href="'.$rowSubMenu[2].'&mod='.$rowSubMenu[0].'">'.$rowSubMenu[1].'</a></li>';
		}
		echo '</ul>';

	}elseif($menu_id == "07"){ 			   
		$sql = "  SELECT men_menu.codigo, men_menu.descripcion,
		men_menu.link 
		FROM men_menu 
		WHERE codigo BETWEEN 701 AND 799
		ORDER BY men_menu.orden ASC  ";
		$query02 = $bd->consultar($sql);

		echo '<ul>';	
		while ($rowSubMenu=$bd->obtener_fila($query02,0)){

			echo '<li><a href="'.$rowSubMenu[2].'">'.$rowSubMenu[1].'</a></li>';
		}
		echo '</ul>';
		
	}  elseif(isset($_GET["mod"])){				
		$mod = $_GET["mod"];
		echo '<ul>';
		if($menu_id == "03" && $mod == '006'){ 
			$sql = "SELECT men_menu.codigo, men_menu.descripcion, men_menu.link
			FROM nov_perfiles , men_menu
			WHERE nov_perfiles.cod_perfil = '".$_SESSION['cod_perfil']."' 
			AND nov_perfiles.ingreso = 'T' 
			AND nov_perfiles.status = 'T'
			AND men_menu.`status` = 'T'
			AND men_menu.codigo = '435'
			ORDER BY men_menu.orden ASC 
			LIMIT 1";
			$query00 = $bd->consultar($sql);

			$sql = "SELECT men_menu.codigo, men_menu.descripcion, men_menu.link
			FROM nov_perfiles , men_menu
			WHERE nov_perfiles.cod_perfil = '".$_SESSION['cod_perfil']."' 
			AND nov_perfiles.respuesta = 'T'
			AND nov_perfiles.status = 'T' 
			AND men_menu.`status` = 'T'
			AND men_menu.codigo = '444'
			ORDER BY men_menu.orden ASC
			LIMIT 1";
			$query01 = $bd->consultar($sql);
			while ($rowSubMenu=$bd->obtener_fila($query00,0)){
				echo '<li><a href="'.$rowSubMenu[2].'&Nmenu='.$rowSubMenu[0].'&mod='.$mod.'">'.$rowSubMenu[1].'</a></li>';				
			}
			while ($rowSubMenu=$bd->obtener_fila($query01,0)){
				echo '<li><a href="'.$rowSubMenu[2].'&Nmenu='.$rowSubMenu[0].'&mod='.$mod.'">'.$rowSubMenu[1].'</a></li>';				
			}
		}
		$sql = "SELECT men_menu.codigo, men_menu.descripcion, men_menu.link
		FROM men_perfil_menu , men_menu
		WHERE men_perfil_menu.cod_men_perfil = '".$_SESSION['cod_perfil']."' 
		AND men_perfil_menu.cod_men_principal = '$menu_id' 
		AND men_perfil_menu.cod_menu_modulo = '$mod' 
		AND men_perfil_menu.cod_menu = men_menu.codigo
		AND men_menu.`status` = 'T'
		ORDER BY men_menu.orden ASC";
		$query02 = $bd->consultar($sql);

		while ($rowSubMenu=$bd->obtener_fila($query02,0)){
			if($rowSubMenu[2] == 'kanban'){
				echo '<li><a href="'.$rowSubMenu[2].'" target="_blank">'.$rowSubMenu[1].'</a></li>';	
			}else{
				echo '<li><a href="'.$rowSubMenu[2].'&Nmenu='.$rowSubMenu[0].'&mod='.$mod.'">'.$rowSubMenu[1].'</a></li>';	
			}			
		}					
		echo '</ul>';
	}	
	echo '</li>';	
} 


echo '<li><a href="autentificacion/aut_logout.php?"><span class="l"></span>
<span class="r"></span><span class="t">DESCONEXION</a></span></li>';

echo '<li><span class="r"></span><span class="badge-notificacion t imgLink" data-count-notificacion="" id="noti" title="Tiene novedades pendientes">
<img id="opcion" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAI2SURBVFhH7ZZPSFRhFMXH8A+iIQQhKCKI0SZb2CbcmOUmaCmhCUGtJMSFZFC0ScSlCKExO5chSq4Cd240RBAJqkW0KRKFFu1KxBl/985hHsM4Oe/NvEGhHxy+ued+99zBwTeT+M+5JZVKDaL1dDq9yNkquzKwtIOlh5wOr1fUih+W1aNX2u1Q/+K4oSvxwaL7aDezNh9671E8HwfBE9rzT7j3E3VqLBwM9qEX5NTIcvDuolRmxelw9ROq07hD3YWmaDfJyoVGLRf2FNAt2/wL1F/NDwMzo4pwsBbkT8rKhcaALuxz1Mo2v9f8sDC3owiHelj+D44q2QE0krowJ8uhfm5+WJizj6xRMZbTgP6qd012AOaGmg9kOdSz5keB2SuKcag/yB+SFYD5Tc1eWQ71vPlRYLZLMQ7WovxxWQGY22rek+WU8w1Qr8p/LCsA852aL2U51G/MjwKz2TdAWUXt/2VwS3YAzTHrcG7JcqiXfCQa2UXk3DSD8w+qlx2A2YL8C4bzqmzzN82LArPDirGc1/LeysqH5gz6gppVX0LZb70ILHgwkDOCvuMV/6XFwMNMTjSYz3mohYLBagI+e1IJkJHzSC4aBp8ooyTI2UOXFVscDPSgA2WUgzVU3EfB4lEu//axMkLuR9SmNYXhkj+Q4oDsO1pTGC5dRLe5319mxf+bsSLw17mOkidoWlfihUXtKO/piLesK/HDsqfoSLttuT1iO9SuDCztRs/QI5af/Ev37JNIHAOAaMehLX5GzgAAAABJRU5ErkJggg" width="25" >	
</span></li>';


if((isset($_GET['Nmenu'])) and (isset($_GET['mod']))){

	$sql02 = " SELECT men_perfil_menu.consultar, men_perfil_menu.agregar,
	men_perfil_menu.modificar, men_perfil_menu.eliminar
	FROM men_perfil_menu
	WHERE men_perfil_menu.cod_menu = '".$_GET['Nmenu']."'
	AND men_perfil_menu.cod_menu_modulo = '".$_GET['mod']."'
	AND men_perfil_menu.cod_men_perfil = '".$_SESSION['cod_perfil']."' ";

	$query02    = $bd->consultar($sql02);		
	$row02      = $bd->obtener_fila($query02,0);
	$b_cons       = $row02[0]; 
	$b_add        = $row02[1];	
	$b_mod        = $row02[2];	
	$b_eli        = $row02[3];	
	$r_cons = "";
	$r_add  = "";
	$r_mod  = "";
	$r_eli = "";	

	echo '<input type="hidden" id="b_cons" value="'.$b_cons.'"/><input type="hidden" id="b_add"  value="'.$b_add.'"/><input type="hidden" id="b_mod" value="'.$b_mod.'"/><input type="hidden" id="b_eli" value="'.$b_eli.'"/>'; 

}
?>