
<?php
	$Nmenu = '734';
	require_once('autentificacion/aut_verifica_menu.php');
	$tabla = "subruta_de_ventas";
	$bd = new DataBase();
	$archivo = "subrutasventas";
	$titulo = "Sub Ruta de Ventas";
	$vinculo = "inicio.php?area=pestanas_maestro/maestrossubruta&Nmenu=".$_GET['Nmenu']."&mod=".$_GET['mod']."&titulo=$titulo&tb=$tabla&archivo=$archivo";
?>
<div align="center" class="etiqueta_title"> Consulta <?php echo $titulo;?> </div> <hr />
<div id="Contenedor01"></div>
<div id="listar"><table width="90%" border="0" align="center">
		<tr class="fondo00">
			<th width="20%" class="etiqueta">Código</th>
			<th width="20%" class="etiqueta">Ruta</th>
			<th width="50%" class="etiqueta">Descripción</th>
            <th width="10%" class="etiqueta">Status</th>
		    <th width="10%" align="center"><a href="<?php echo $vinculo."&codigo=''&metodo=agregar";?>"><img src="imagenes/nuevo.bmp" alt="Agregar" title="Agregar Registro" width="30px" height="30px" border="null"/></a></th>
		</tr>
    <?php
	$usuario = $_SESSION['usuario_cod'];
	$valor = 0;
	$sql = " SELECT
				subruta_de_ventas.codigo,
				subruta_de_ventas.descripcion,
				subruta_de_ventas.STATUS,
				ruta_de_ventas.descripcion ruta	
			FROM
				subruta_de_ventas,
				ruta_de_ventas 
			WHERE
				subruta_de_ventas.cod_ruta = ruta_de_ventas.codigo 
			ORDER BY
				ruta_de_ventas.orden, subruta_de_ventas.codigo; ";

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
                  <td class="texto">'.$datos[0].'</td>
				  <td class="texto">'.$datos[3].'</td>
                  <td class="texto">'.$datos[1].'</td>
				  <td class="texto">'.statuscal($datos[2]).'</td>
				  <td align="center"><a href="'.$vinculo.'&codigo='.$datos[0].'&metodo=modificar"><img src="imagenes/actualizar.bmp" alt="Modificar" title="Modificar Registro" width="20px" height="20px" border="null"/></a>&nbsp;<img src="imagenes/borrar.bmp"  width="20px" height="20px" title="Borrar Registro" border="null" onclick="'.$Borrar.'" class="imgLink"/></td>
            </tr>';
        }
     echo '<input type="hidden" name="tabla" id="tabla" value="'.$tabla.'"/>';
	?>
    </table>
</div>