<?php
require('../'.PlantillaDOM.'/header_ibarti_2.php');
include('../'.pagDomPdf.'/paginacion_ibarti.php');
?>

<style>
table {
    font-size: 10px;
}

#titulo_header {
    font-size: 13px;
}

.nota{
    font-size: 9px;
}

</style>
<div style="border: 1.5px solid #1B5E20;">
<div>
	<table style="padding-top: 5px;">
		<tbody>
        <tr>
            <td style="padding-bottom: 3px" class="titulos" colspan="6">
                INFORMACIÓN DE LA <?php echo $row['tipo'];?>
            </td>
        </tr>
             <tr>
            <td>
                <span class="etiqueta">Código: </span><span class="texto"><?php echo $row['codigo'];?></span>
			</td>
            <td>
                <span class="etiqueta">Fecha: </span><span class="texto"><?php echo $row['fec_asignacion'];?></span>
			</td>
            <td colspan="2">
                <span class="etiqueta">Cliente: </span><span class="texto"><?php echo $row['cliente'];?></span>
			</td>
            <td>
                <span class="etiqueta">Ubicación: </span><span class="texto"><?php echo $row['ubicacion'];?></span>
			</td>
            <td width="5%" rowspan="2" style="text-align: center; vertical-align: top;">
                <img src="../imagenes/logo.png" width="40">
            </td>
            </tr>
            <tr>
            <td colspan="2">
                <span class="etiqueta" ><?php echo $leng['trabajador'];?>: </span>
                <span class="texto" ><?php echo $row['trabajador'];?> 
                <?php if ($row['cod_ficha'] != 'N/A') { ?>
                    (Ficha: <?php echo $row['cod_ficha'];?>)
                <?php } ?>
            </span>
            </td>
            <td colspan="3"><span class="etiqueta">Descripción: </span>
            <span class="texto"><?php echo $row['descripcion'];?></span></td>
            </tr>
            </tbody>
            </table>
            <table>
            <tbody>
            <?php 
            echo "<tr style='background-color: #4CAF50;'>
            <td width='15%'><span class='etiqueta'>Linea</span></td>
            <td width='15%'><span class='etiqueta'>Sub Linea</span></td>
            <td width='30%'><span class='etiqueta'>Producto</span></td>
            <td width='15%'><span class='etiqueta'>Almacen</span></td>
            <td width='10%'><span class='etiqueta'>Cant</span></td>
            <td width='15%'><span class='etiqueta'>EANs</span></td>
            </tr>";  
            $i=0;
             while ($producto = $bd->obtener_name($queryp))
            { 
                if ($i%2==0){
                echo "<tr>";
            }else{
                echo "<tr class='odd_row'>";
            }?>
            <td>
                <span class="texto"><?php echo $producto['linea'];?></span>
            </td>
            <td>
            <span class="texto"><?php echo $producto['sub_linea'];?></span>
            </td>
            <td>
            <span class="texto"><?php echo $producto['producto'];?></span>
            </td>
            <td>
            <span class="texto"><?php echo $producto['almacen'];?></span>
            </td>
             <td>
             <span class="texto"><?php echo $producto['cantidad'];?></span>
            </td>
            <td>
             <span class="texto" style="word-wrap: break-word;"><?php echo $producto['eans'];?></span>
            </td>
             </tr>
            <?php ++$i;} ?>
		</tbody>
		</table>
</div>
<br>
     <table>
        <tbody>
            <tr >
            <td style="text-align: center;font-size: 9px;">
                <br>
                <span class="firma"><?php echo $row['nombreusuario'];?></span><br>
                <span class="firma">Entregado / Procesado Por</span><br><br>
                <span class="firma"><?php echo $row['cedulausuario'];?></span><br>
                <span class="firma"><?php echo $leng['ci'];?></span><br>
                <br>
                _____________________<br>
                <span class="firma">Firma</span>
              
            </td>
            <td style="text-align: center;font-size: 9px;">
            <br>
                <span class="firma"><?php echo $row['trabajador'];?></span><br>
                <span class="firma">Recibido Por</span><br><br>
                <span class="firma"><?php echo $row['cedula'];?></span><br>
                <span class="firma"><?php echo $leng['ci'];?></span><br><br>
                _____________________<br>
                <span class="firma">Firma</span>
            </td>
            <td style="text-align: center;font-size: 9px;">
                _________________________<br>
                <span class="firma">Verificado Por</span><br><br>
                _____________________<br>
                <span class="firma"><?php echo $leng['ci'];?></span><br><br>
                _____________________<br>
                <span class="firma">Firma</span>
            </td>
             </tr> 
        </tbody>
        </table>
    </div>
<?php
if($bd->isConnected()){
    $bd->liberar();
}?>
</body>
</html>
