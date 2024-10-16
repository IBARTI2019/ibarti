<!DOCTYPE html>
<html>
<head>
	<title>IBARTI</title>
</head>
<body>
    <!-- Cabecera -->
    <div style="width=: 100%;
    font-size: 0.8em;
    top: 0.5cm;
    left: 1cm;
    right: 1cm;
    clear: both;">
  <table  style="border=1 0px solid #1B5E20;   width: 100%;">
    <tbody>
    <tr>
        <!-- Logo de la empresa que esta en la cabecera-->
        <td style="width: 25%;">
            <img style="width: 140px;" src="../../../../<?php echo LogoIbarti?>" >
        </td>
        <td style="  
        width: 50%; 
                text-transform: uppercase;
                text-align: center;
                vertical-align: middle;
                font-size: 16px;
                font-style: italic;
                color: #1B5E20; "
        >
                <span>Planificacion de servicio Resumen</span>
        </td>
        <!-- Fecha y Hora alineada a la derecha en la cabecera -->
        <td style="width: 25%; text-align: left; font-size: 12px; vertical-align: top; text-transform: lowercase;">
            <?php
            date_default_timezone_set('America/Caracas');
            echo date("d/m/Y, h:i:s a");?>
        </td>
       
    </tr>


  </tbody>
</table>
       </div>
