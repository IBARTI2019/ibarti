
<script language="javascript">
$("#cl_contratacion_det_form").on('submit', function(evt){
	 evt.preventDefault();
	
});
	</script>
	<?php
	require "../modelo/contratacion_modelo.php";
	require "../../../../".Leng;
	$contratacion = new Contratacion;
	$codigo     = $_POST['contratacion'];
	$ubicacion=$_POST['ubicacion'];;
	$cliente    = $_POST['cliente'];
	$cont_det   = $contratacion->get_cont_detcontrataccion($cliente,$ubicacion);
	
	?><form id="cl_contratacion_det_form" name="cl_contratacion_det_form" method="post">
		 <table width="100%" border="0" align="center">
    		<tr>
						<th width="20%"><?php echo $leng["ubicacion"];?></th>
						<th width="20%">Puesto Trabajo</th>
				    <th width="20%">Turno</th>
            <th width="20%">Cargo </th>
            <th width="12%">Cantidad</th>
				   
        <?php
        $i     = 0;
        foreach ($cont_det as $datos) {
            	$i++;
             $cod_det     = $datos['codigo'];
			 echo '<tr>
			 <td>'.$datos["ubicacion"].'</td>
			 <td>'.$datos["puesto"].'</td>
			 <td>'.$datos["turno"].'</td>
			 <td>'.$datos["cargo"].'</td>
			 <td>'.$datos["cantidad"].'</td>
			</tr>';
		}	
         ?></table>
  </form>
