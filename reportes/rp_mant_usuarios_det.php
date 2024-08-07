<?php
define("SPECIALCONSTANT",true);
session_start();
$Nmenu   = 510;
require("../autentificacion/aut_config.inc.php");
include_once('../'.Funcion);
require_once("../".class_bdI);
require_once("../".Leng);
$bd = new DataBase();

$perfil           = $_POST['perfil'];
$status          = $_POST['status'];

$reporte         = $_POST['reporte'];
$trabajador      = $_POST['trabajador'];

$archivo         = "rp_mant_usuarios_".$fecha."";
$titulo          = "  REPORTE USUARIOS \n";

if(isset($reporte)){

		$where = " WHERE men_usuarios.cod_perfil = men_perfiles.codigo ";


	if($perfil != "TODOS"){
		$where .= " AND men_perfiles.codigo = '$perfil' ";
	}

	if($status != "TODOS"){
		$where .= " AND men_usuarios.status = '$status' ";
	}
	if($trabajador != NULL){
		$where  .= " AND men_usuarios.`status` = '$trabajador' ";
	}
	// QUERY A MOSTRAR //
	    $sql = "SELECT men_usuarios.cedula, men_usuarios.apellido,
		               men_usuarios.nombre, men_usuarios.login,
                       men_perfiles.descripcion AS perfil, men_usuarios.email,
                       men_usuarios.fec_mod_pass, men_usuarios.`status`
                  FROM men_usuarios , men_perfiles
					   $where
			  ORDER BY 2 ASC ";

	if($reporte== 'excel'){
		echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition:  filename=\"rp_$archivo.xls\";");

		$query01  = $bd->consultar($sql);

	 echo "<table border=1>";
 	 echo "<tr><th> ".$leng['ci']." </th><th> Apellido </th><th> Nombre </th><th> Login </th> <th> Perfil </th><th> ".$leng['correo']." </th><th> Fecha Modif. Passwords </th><th> Status </th></tr>";

		while ($row01 = $bd->obtener_num($query01)){
		 echo "<tr><td>".$row01[0]."</td><td>".$row01[1]."</td><td>".$row01[2]."</td><td>".$row01[3]."</td>
		           <td>".$row01[4]."</td><td>".$row01[5]."</td><td>".$row01[6]."</td><td>".statuscal($row01[7])."</td>
			   </tr>";
		}
		 echo "</table>";
	}

	if($reporte == 'pdf'){

		require_once('../'.ConfigDomPdf);

		$dompdf= new DOMPDF();

		$query  = $bd->consultar($sql);

		ob_start();

		require('../'.PlantillaDOM.'/header_ibarti_2.php');
		include('../'.pagDomPdf.'/paginacion_ibarti.php');

		echo "<br><div>
        <table>
		<tbody>
            <tr style='background-color: #4CAF50;'>
            <th width='10%'>".$leng['ci']."</th>
            <th width='23%'>Apellido</th>
            <th width='23%'>Nombre</th>
            <th width='14%'>Login</th>
            <th width='20%'>Perfil</th>
            <th width='10%'>Status</th>
            </tr>";

            $f=0;
    while ($row = $bd->obtener_num($query)){
    	 if ($f%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='class= odd_row'>";
            }
    echo   "<td width='10%'>".$row[0]."</td>
            <td width='23%'>".$row[1]."</td>
            <td width='23%'>".$row[2]."</td>
            <td width='14%'>".$row[3]."</td>
            <td width='20%'>".$row[4]."</td>
            <td width='10%'>".statuscal($row[7])."</td></tr>";

             $f++;
         }

    echo "</tbody>
        </table>
		</div>
		</body>
		</html>";

		    $dompdf->load_html(ob_get_clean(),'UTF-8');
		    $dompdf->set_paper ('letter','landscape');
		    $dompdf->render();
		    $dompdf->stream($archivo, array('Attachment' => 0));
	}
}