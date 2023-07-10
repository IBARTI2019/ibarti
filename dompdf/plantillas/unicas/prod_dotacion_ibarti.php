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
                INFORMACIÓN DOTACIÓN
            </td>
        </tr>
             <tr>
            <td>
                <span class="etiqueta">Código: </span><span class="texto"><?php echo $row['codigo'];?></span>
			</td>
            <td>
                <span class="etiqueta">Fecha: </span><span class="texto"><?php echo $row['fec_dotacion'];?></span>
			</td>
             <td><span class="etiqueta">N. <?php echo $leng['ficha'];?>: </span>
             <span class="texto"><?php echo $row['cod_ficha'];?></span>
             </td>
            <td><span class="etiqueta"><?php echo $leng['ci'];?>: </span><span class="texto"><?php echo $row['cedula'];?></span></td>
        
            <td><span class="etiqueta"><?php echo $leng['rol'];?>: </span>
            <span class="texto"><?php echo $row['rol'];?></span></td>
            <td width="5%" rowspan="2" style="text-align: center; vertical-align: top;">
                <img src="../imagenes/logo.png" width="40">
            </td>
            </tr>
            <tr>
            <td colspan="2">
                <span class="etiqueta" ><?php echo $leng['trabajador'];?>: </span>
                <span class="texto" ><?php echo $row['trabajador'];?></span>
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
            <td width='20%'><span class='etiqueta'>Linea</span></td>
            <td width='20%'><span class='etiqueta'>Sub Linea</span></td>
            <td width='40%'><span class='etiqueta'>Producto</span></td>
            <td width='10%'><span class='etiqueta'>Cantidad</span></td>
            <td width='10%'><span class='etiqueta'>Ok</span></td>
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
             <span class="texto"><?php echo $producto['cantidad'];?></span>
                       
            </td>
            <td>
                <span class="texto"><input type="checkbox"></span>
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
                <span class="firma">Revisado Por</span><br><br>
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
             <tr>
             <td colspan="3" style="text-align: center;">
                <span class="nota"><?php echo $row['nota_unif'];?></span>
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