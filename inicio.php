<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

session_start();

if ((!$_SESSION['usuario_cod']) or ($_SESSION['captcha'] == "")) {
    echo '<script language="javascript">
    alert("Acceso Denegado, No puede Ingresar a Esta Pagina ...");
    </script>';
    require "autentificacion/aut_logout02.php";
}

if (isset($_GET['mod'])) {
    $_SESSION['menu'] = $_GET['mod'];
    $modulo  = $_SESSION['menu'];
} else {
    $modulo  = $_SESSION['menu'];
}
define("SPECIALCONSTANT", true);
require_once 'autentificacion/aut_config.inc.php';
require_once class_bdI;
include_once 'funciones/funciones.php';
require Leng;

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// ini_set('display_errors', 'On');
// ini_set('display_errors', 1);
//require('autentificacion/aut_autentifica02.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang='es' xml:lang='es'>

<head>


    <!--
    Created by Artisteer v3.0.0.32906
    Base template (without user's data) checked by http://validator.w3.org : "This page is valid XHTML 1.0 Transitional"
-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Ibarti</title>
    <meta http-equiv="Expires" content="0">

    <meta http-equiv="Last-Modified" content="0">

    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">

    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" href="style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="style.ie7.css" type="text/css" media="screen" /><![endif]-->
    <!--<link rel="stylesheet" type="text/css" href="css/template_css.css"> -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/style2.css">

    <script type="text/javascript" src="jquery.js"></script>
    <!--
<script type="text/javascript" src="script.js"></script>

-->
    <link rel="stylesheet" type="text/css" href="spry/widgets/tabbedpanels/SpryTabbedPanels.css" />
    <link rel="stylesheet" type="text/css" href="css/validation.css">
    <link href="spry/widgets/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <link href="spry/widgets/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
    <link href="spry/widgets/textareavalidation/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
    <link href="spry/widgets/checkboxvalidation/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
    <link href="spry/widgets/radiovalidation/SpryValidationRadio.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="css/calendario.css" />
    <!--CSS PARA CONTROL DE FECHAS BY LUIS-->
    <link rel="stylesheet" href="libs/control_fecha/fechas.css">
    <!-- CSS PARA LAS NOTIFICACIONES -->
    <link rel="stylesheet" type="text/css" href="libs/toastr.css">
    <link rel="stylesheet" type="text/css" href="css/animaciones_notif.css">
    <link rel="stylesheet" type="text/css" href="css/estilos_notificacion.css">
    <script language="JavaScript" type="text/javascript" src="spry/widgets/tabbedpanels/SpryTabbedPanels.js"></script>

    <script language="JavaScript" type="text/javascript" src="spry/includes/xpath.js"></script>
    <script language="JavaScript" type="text/javascript" src="spry/includes/SpryData.js"></script>
    <script language="JavaScript" type="text/javascript" src="spry/includes/SpryPagedView.js"></script>

    <script type="text/javascript" src="spry/widgets/textfieldvalidation/SpryValidationTextField.js"></script>
    <script type="text/javascript" src="spry/widgets/selectvalidation/SpryValidationSelect.js"></script>
    <script type="text/javascript" src="spry/widgets/textareavalidation/SpryValidationTextarea.js"></script>
    <script type="text/javascript" src="spry/widgets/checkboxvalidation/SpryValidationCheckbox.js"></script>
    <script type="text/javascript" src="spry/widgets/radiovalidation/SpryValidationRadio.js"></script>
    <script src="spry/includes/SpryEffects.js" type="text/javascript"></script>
    <!--<script type="text/javascript" src="libs/vue.js"></script>-->
    <script type="text/javascript" src="ajax/ajax.js"></script>
    <script type="text/javascript" src="funciones/funciones.js"></script>

    <script type="text/javascript" src="calendarioJS/fechas.js"></script>
    <script type="text/javascript" src="calendarioJS/calendario.js"></script>
    <!--SCRIPT DE CONTROL DE FECHAS BY:LUIS-->
    <script src="libs/control_fecha/fecha.js"></script>


    <script src="jquery-2.1.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="libs/d3.min.js"></script>
    <script type="text/javascript" src="libs/moment.js"></script>
    <!-- SCRIPTS PARA LAS NOTIFICACIONES -->
    <script type="text/javascript" src="libs/toastr.js"></script>
    <script src="packages/notificaciones/controllers/notificacionesCtrl.js"></script>
</head>

<body>
    <div class="toast_container">
        <ul id='llenar'></ul>
    </div>
    <div class='triangulo'></div>
    <!--////CAPA DE VENTANA DE SELECCION DE FECHAS--->
    <div id="ventanaSeleccionFechas"></div>
    <!--////CAPA DE CALENDARIO --->
    <DIV id="ventanaCalendario"></DIV>
    <div id="art-main">
        <div style="top:10px; right:10px;position:fixed;word-wrap: break-word;"><img src="imagenes/up.png" onclick="subir()" style="float:right;" width="40px" alt=""></div>
        <div class="art-sheet">
            <div class="art-sheet-tl"></div>
            <div class="art-sheet-tr"></div>
            <div class="art-sheet-bl"></div>
            <div class="art-sheet-br"></div>
            <div class="art-sheet-tc"></div>
            <div class="art-sheet-bc"></div>
            <div class="art-sheet-cl"></div>
            <div class="art-sheet-cr"></div>
            <div class="art-sheet-cc"></div>
            <div class="art-sheet-body">
                <div class="art-header">
                    <div class="art-header-center">
                        <div class="art-header-png"></div>
                    </div>
                    <script type="text/javascript" src="swfobject.js"></script>
                    <div id="art-flash-area">
                        <div id="art-flash-container">
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="992" height="125" id="art-flash-object">
                                <param name="movie" value="container.swf" />
                                <param name="quality" value="high" />
                                <param name="scale" value="default" />
                                <param name="wmode" value="transparent" />
                                <param name="flashvars" value="color1=0xFFFFFF&amp;alpha1=.30&amp;framerate1=24&amp;loop=true&amp;wmode=transparent&amp;clip=images/flash.swf&amp;radius=5&amp;clipx=0&amp;clipy=-61&amp;initalclipw=900&amp;initalcliph=225&amp;clipw=992&amp;cliph=248&amp;width=992&amp;height=125&amp;textblock_width=0&amp;textblock_align=no" />
                                <param name="swfliveconnect" value="true" />
                                <!--[if !IE]>-->
                                <object type="application/x-shockwave-flash" data="container.swf" width="992" height="125">
                                    <param name="quality" value="high" />
                                    <param name="scale" value="default" />
                                    <param name="wmode" value="transparent" />
                                    <param name="flashvars" value="color1=0xFFFFFF&amp;alpha1=.30&amp;framerate1=24&amp;loop=true&amp;wmode=transparent&amp;clip=images/flash.swf&amp;radius=5&amp;clipx=0&amp;clipy=-61&amp;initalclipw=900&amp;initalcliph=225&amp;clipw=992&amp;cliph=248&amp;width=992&amp;height=125&amp;textblock_width=0&amp;textblock_align=no" />
                                    <param name="swfliveconnect" value="true" />
                                    <!--<![endif]-->
                                    <div class="art-flash-alt"><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></div>
                                    <!--[if !IE]>-->
                                </object>
                                <!--<![endif]-->
                            </object>
                        </div>
                    </div>
                    <script type="text/javascript">
                        swfobject.switchOffAutoHideShow();
                        swfobject.registerObject("art-flash-object", "9.0.0", "expressInstall.swf");
                    </script>

                    <div class="art-logo">
                        <h1 id="name-text" class="art-logo-name"><a href="#"><?php echo $_SESSION['cl_abrev'] . ' , ' . $_SESSION['usuarioA'] . ' &nbsp; ' . $_SESSION['usuarioN'] . ' &nbsp; ' . conversion($date) . ''; ?> </a></h1>
                        <h2 id="slogan-text" class="art-logo-text"><?php
                                                                    if (isset($_GET['mod'])) {
                                                                        include_once("autentificacion/Modulo_menu.php");
                                                                        echo $Modulo_X . '<br />' . $Menu_X;
                                                                    }
                                                                    ?>

                        </h2>
                    </div>
                </div>
                <div class="art-nav">
                    <div class="l"></div>
                    <div class="r"></div>
                    <ul class="art-menu">
                        <?php include_once 'autentificacion/menu.php'; ?>
                    </ul>
                </div>
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content">

                            <div class="art-post">
                                <div class="art-post-body">
                                    <div class="art-post-inner art-article">



                                        <div id="Contenido" align="center">
                                            <?php

                                            $view = isset($_GET['area']) ? $_GET['area'] . '.php' : 'index.php';
                                            if (file_exists($view)) {
                                                include_once($view);
                                            } else {
                                                require "autentificacion/aut_logout02.php";
                                            }
                                            ?>

                                        </div>
                                        <!--   cuerpo -->
                                    </div>
                                    <div class="cleared"></div>
                                </div>
                            </div>
                            <div class="cleared"></div>
                        </div>
                    </div>
                </div>
                <div class="cleared"></div>
                <div class="art-footer">
                    <div class="art-footer-t"></div>
                    <div class="art-footer-l"></div>
                    <div class="art-footer-b"></div>
                    <div class="art-footer-r"></div>



                    <div class="art-footer-body">
                        <a href="#" class="art-rss-tag-icon" title="RSS"></a>

                        <div class="art-footer-text">
                            <?php include_once("plantillas/pl_footer.php"); ?>
                            <p>Copyright &copy; 2012. All Rights Reserved.</p>
                        </div>
                        <div class="cleared"></div>
                    </div>
                </div>
                <div class="cleared"></div>
            </div>

        </div>

</body>

</html>

<script type="text/javascript">
    function subir() {
        $('html,body').animate({
            scrollTop: $("#art-main").offset().top
        }, 1000);
    }
    activarNotif();
</script>
<script async src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBJyluUtFc8ntCP8wSAtcCHoU3lJfJcpXo&v=weekly&language=es"></script>