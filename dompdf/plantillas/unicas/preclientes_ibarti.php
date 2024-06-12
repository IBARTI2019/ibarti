<?php
require('../'.PlantillaDOM.'/header_ibarti.php');
include('../'.pagDomPdf.'/paginacion_ibarti.php');

?>
<!-- FOTO y Datos Principales -->
<div>
	<table>
		<tbody>
			<tr>
                <!-- Nombres y Apellidos del Trabajador -->
            <td colspan="1" id="nombre_cabecera" style="text-align: center; width: 100%">
                <span>
                <?php        
                    echo $precliente['nombre'];
                ?>
                </span>
			</td>
            </tr>
		</tbody>
		</table>
</div>
    <!-- Tabla datos Básicos -->
<div>
        <table>
		<tbody>
            <tr>
            <td class="titulos"  colspan="2">
                <h4>DATOS PRINCIPALES</h4>
			</td>
            </tr>
             <tr class="odd_row">
            <td>
                <span class="etiqueta"><b>Codigo:</b> </span><span class="texto"><?php echo $precliente['codigo'];?></span>
			</td>
            <td>
                <span class="etiqueta"><b>Abreviatura:</b> </span><span class="texto"><?php echo $precliente['abrev'];?></span>
			</td>
            </tr>
             <tr>
            <td>
                <span class="etiqueta"><b>Rif:</b> </span><span class="texto"><?php echo $precliente['rif'];?></span>
			</td>
            <td>
                <span class="etiqueta"><b>Nit:</b> </span><span class="texto"><?php echo $precliente['nit'];?>
                </span>
                 </td>
            </tr>
             <tr class="odd_row">
            <td>
            <span class="etiqueta"><b>Juridico:</b> </span><span class="texto"><?php echo ($precliente['juridico']=="T")?"SI":"NO";?></span>
			</td>
            <td>
            <span class="etiqueta"><b>Contribuyente:</b> </span><span class="texto"><?php echo ($precliente['juridico']=="T")?"SI":"NO";?></span>
			</td>
            </tr>
             <tr>
            <td>
            <span class="etiqueta"><b>Tipo de Empresa:</b> </span><span class="texto"><?php echo $precliente['cl_tipo'];?></span>
                
			</td>
            <td>
            <span class="etiqueta"><b>Telefono:</b> </span><span class="texto"><?php echo $precliente['telefono'];?></span>
                
			</td>
            </tr>
             <tr class="odd_row">
            <td>
                <span class="etiqueta"><b>Region:</b> </span><span class="texto"><?php echo $precliente['region'];?></span>
			</td>
            <td>
                <span class="etiqueta"><b>vendedor:</b> </span><span class="texto"><?php echo $precliente['vendedor'];?></span>
			</td>
            </tr>
             <tr>
             <td>
                <span class="etiqueta"><b>Email:</b> </span><span class="texto"><?php echo $precliente['email'];?></span>
			</td>
            <td>
            <span class="etiqueta"><b>Fax:</b> </span><span class="texto"><?php echo $precliente['fax'];?></span>
			</td>
            </tr>
            <tr class="odd_row">
            <td>
            <span class="etiqueta"><b>Website:</b> </span><span class="texto"><?php echo $precliente['website'];?></span>
            </td>
            <td colspan="1">
            <span class="etiqueta"><b>Observacion:</b> </span><span class="texto"><?php echo $precliente['observacion'];?></span>
			</td>
            </tr>
            <tr >
                <td>
                <span class="etiqueta"><b>Venta cerrada:</b> </span><span class="texto"><?php echo $precliente['venta_cerrada'];?></span>
                </td>
                <td>
                <span class="etiqueta"><b>Fecha de cierre de venta:</b> </span><span class="texto"><?php echo $precliente['fec_venta_cerrada'];?></span>
                </td>
            </tr>
            <tr class="odd_row">
                <td colspan="2"  style="vertical-align: top;">
                <span class="etiqueta"><b>Usuario de cierre de venta:</b> </span><span class="texto"><?php echo $precliente['usuario_venta_cerrada'];?></span>
                </td>
            </tr>
            <tr>
            <td colspan="2"  style="vertical-align: top;">
            <span class="etiqueta"><b>Dirección:</b> </span><span class="texto"><?php echo $precliente['direccion'];?></span>
			</td>
            </tr>
            
		</tbody>
		</table>
</div>

<div>
        <table>
        <tbody>
            <tr>
            <td class="titulos" colspan="2">
                <h4>DATOS DE RUTA DE VENTA</h4>
			</td>
            </tr>
            <tr>
             <td colspan="2">          
                <table width="100%">
                <tr class='odd_row'>
                    <td  style="font-weight: bold;" width="20%" >Ruta</td>
                    <td  style="font-weight: bold;" width="20%">Sub Ruta</td>
                    <td  style="font-weight: bold;" width="30%">Comentario</td>
                    <td  style="font-weight: bold;" width="15%">Fecha</td>
                    <td  style="font-weight: bold;" width="15%">Usuario</td>
                </tr>
                <?php
                $i=0;
                while ($datos = $bd->obtener_fila($queryruta)){
                    if($i%2==0){
                        echo "<tr >";
                    }else{
                        echo "<tr class='odd_row'>";
                    }
                    ?>
                    <td width="20%"><?php echo $datos['ruta'];?></td>
                    <td width="20%"><?php echo $datos['subruta'];?></td>
                    <td width="30%"><?php echo $datos['comentario'];?></td>
                    <td width="15%"><?php echo $datos['fecha'];?></td>
                    <td width="15%"><?php echo $datos['usuario'];?></td>
                    <?php
                   }
                   echo "</tr>";
                ?>
                </table>
			</td>
            </tr>
        </tbody>
        </table>
                   
        </div>
    </div>
    <div>
        <table>
		<tbody>
            <tr>
            <td class="titulos"  colspan="2">
                <h4>DATOS ADICIONALES</h4>
			</td>
            </tr>
             <tr class="odd_row">
            <td>
                <span class="etiqueta"> <b>Limite Credito:</b> </span><span class="texto"><?php echo $precliente['limite_cred'];?></span>
			</td>
            <td>
                <span class="etiqueta"> <b>Dias Plazo Pago:</b> </span><span class="texto"><?php echo $precliente['plazo_pago'];?></span>
			</td>
            </tr>
             <tr>
            <td>
                <span class="etiqueta"> <b>Descuento Pronto Pago:</b> </span><span class="texto"><?php echo $precliente['desc_p_pago'];?></span>
			</td>
            <td>
                <span class="etiqueta"> <b>Descuento Global:</b> </span><span class="texto"><?php echo $precliente['desc_global'];?>
                </span>
                 </td>
            </tr>
            <tr class="odd_row">
            <td colspan="2"  style="vertical-align: top;">
            <span class="etiqueta"> <b>Dirección de Entrega:</b> </span><span class="texto"><?php echo $precliente['dir_entrega'];?></span>
			</td>
            </tr>
            <tr>
            <td colspan="2"  style="vertical-align: top;">
            <span class="etiqueta"> <b>Dias de Visitas:</b> </span>
            <span class="texto">
            <?php
            echo $precliente['lunes']=="T"?"| Lunes | ":"";
            echo $precliente['martes']=="T"?"Martes | ":"";
            echo $precliente['miercoles']=="T"?"Miercoles | ":"";
            echo $precliente['jueves']=="T"?"Jueves | ":"";
            echo $precliente['viernes']=="T"?"Viernes | ":"";
            ?>
			</td>
            </tr>
             <tr class="odd_row">
            <td>
                <span class="etiqueta"> <b>Campo adiccional 01:</b> </span><span class="texto"><?php echo $precliente['campo01'];?></span>
			</td>
            <td>
                <span class="etiqueta"> <b>Campo adiccional 02:</b> </span><span class="texto"><?php echo $precliente['campo02'];?></span>
			</td>
            </tr>
             <tr>
            <td>
                <span class="etiqueta"> <b>Campo adiccional 03:</b> </span><span class="texto"><?php echo $precliente['campo03'];?></span>
			</td>
            <td>
                <span class="etiqueta"> <b>Campo adiccional 04:</b> </span><span class="texto"><?php echo $precliente['campo04'];?></span>
			</td>
            </tr>
            
		</tbody>
		</table>
</div>
<!-- Aqui se cierra la conexion a la base de datos y libera el resultado de la conslta-->
<?php
if($bd->isConnected()){
    $bd->liberar();
}
?>
</body>
</html> 