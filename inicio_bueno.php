<?php 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

// require_once("autentificacion/aut_autentifica.php");
if(isset($_GET['mod'])){
	 $_SESSION['menu'] = $_GET['mod'];
	 $modulo  = $_SESSION['menu'];	
 }

 else{
	 $modulo  = $_SESSION['menu'];	
 }

require_once('autentificacion/aut_config.inc.php');
include_once('funciones/funciones.php');
require_once(class_bd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Expires" content="0">

<meta http-equiv="Last-Modified" content="0">

<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">

<meta http-equiv="Pragma" content="no-cache">
    <title>SISTEMA DE ASISTENCIA </title>

    <link rel="stylesheet" type="text/css"  href="style.css" media="screen" />

<link rel="stylesheet" type="text/css" href="css/template_css.css">
<link rel="stylesheet" type="text/css" href="css/style.css"> 
<link rel="stylesheet" type="text/css" href="css/style2.css"> 

<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="script.js"></script>


<link rel="stylesheet" type="text/css" href="css/editor.css">

<link rel="stylesheet" type="text/css" href="spry/widgets/tabbedpanels/SpryTabbedPanels.css"/>
<link rel="stylesheet" type="text/css" href="css/validation.css">
<link href="spry/widgets/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/textareavalidation/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/checkboxvalidation/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/radiovalidation/SpryValidationRadio.css" rel="stylesheet" type="text/css" />

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

<script type="text/javascript" src="ajax/ajax.js"></script>
<script type="text/javascript" src="funciones/funciones.js"></script>


</head>
<body>
<div id="art-main">
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
                    <div class="art-logo">
                     <h1 id="name-text" class="art-logo-name"><a href="#">SISTEMA DE ASISTENCIA</a></h1>
                     <h2 id="slogan-text" class="art-logo-text">VALENCIA VENEZUELA</h2>
                    </div>
                </div>
                <div class="art-nav">
                	<div class="l"></div>
                	<div class="r"></div>
                	<ul class="art-menu">
		<?php  include_once('autentificacion/menu.php');?>
                  	</ul>
                </div>
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                    <!--
                    
                        <div class="art-layout-cell art-sidebar1">    
                          
	                      <div class="cleared"></div>
                        </div>
                        -->
                        
                        <div class="art-layout-cell art-content">
                          <div class="art-post">
                              <div class="art-post-body">
              <div class="art-post-inner art-article">

              <div id="Contenido" align="center">
				<?php  $incluye = $_GET['area'].'.php';
				if (isset($_GET['area'])){
				 	include_once($incluye);
				 }else{
				 
				}?>
             </div>
	</div></td>
	</tr>
</table>			

            </div>

              <div class="cleared"></div>
              </div>                         
                          
                          		<div class="cleared"></div>
                              </div>
                          </div>
                          <div class="art-post"></div>
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
                    
                    <?php include_once("plantillas/pl_footer.php"); ?>
                        <div class="art-footer-text">
						<p>Copyright &copy; 2012. All Rights Reserved.</p>
                        </div>
                		<div class="cleared"></div>
                    </div>
                </div>
        		<div class="cleared"></div>
            </div>
        </div>

    </div>     
    
    
</body>
</html>
<?php 

/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

// require_once("autentificacion/aut_autentifica.php");
if(isset($_GET['mod'])){
	 $_SESSION['menu'] = $_GET['mod'];
	 $modulo  = $_SESSION['menu'];	
 }

 else{
	 $modulo  = $_SESSION['menu'];	
 }

require_once('autentificacion/aut_config.inc.php');
include_once('funciones/funciones.php');
require_once(class_bd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="css/template_css.css">
<link rel="stylesheet" type="text/css" href="css/editor.css">
 
<link rel="stylesheet" type="text/css" href="css/style2.css">  

<link rel="stylesheet" type="text/css" href="spry/widgets/tabbedpanels/SpryTabbedPanels.css"/>
<link rel="stylesheet" type="text/css" href="css/validation.css">
<link href="spry/widgets/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/textareavalidation/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/checkboxvalidation/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
<link href="spry/widgets/radiovalidation/SpryValidationRadio.css" rel="stylesheet" type="text/css" />

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

<script type="text/javascript" src="ajax/ajax.js"></script>
<script type="text/javascript" src="funciones/funciones.js"></script>

</head>
<body class="body_bg">
<div id="bgr">

<div id="wrapper_01">
<div id="tophead">
<!--
<div id="logo">
</div>
-->
<!-- END: LOGO -->
<!-- BEGIN: flashheader -->

<div id="ol-flashheader">
  <?php include_once('plantillas/pl_cabecera.php');?>
</div>
<!-- END: flashheader -->
<!-- BEGIN: Date -->
<?php 



$date = date("d").'-'.date("m").'-'.date("Y");
////////////            AQUI OJO          //////

echo'
<div id="date-format">
 <p>'.$_SESSION['usuarioN'].'</p>'.$date.' 
</div>';
?>
<!-- END: Date -->
</div>
<div id="topcol">
<div id="topmenu">
<table cellspacing="0" cellpadding="0" style="float: left;" width="98%">
<tr>
	<td>
		<?php  include_once('autentificacion/menu.php');?>
	</td>
	<td  class="mensaje" align="right"><?php  
	if(isset($_GET['Nmenu'])){	
	include_once("autentificacion/Modulo_Proceso.php");	 
		echo $Modulo_X.',&nbsp;'.$Menu_X;}
	?>
   
   </td>
</tr>
</table>
</div>
</div>
<div id="wrapper_02">
<div id="holder">
<!--pathway start-->
<div class="path">
<!--
You are here: <jdoc:include type="module" name="breadcrumbs" />
-->
</div>
<!--pathway end-->

<div id="Contenido">	 
				<?php  $incluye = $_GET['area'].'.php';
				if (isset($_GET['area'])){
				 	include_once($incluye);
				 }else{
				 
				}?>
  </div>
<!--footer start-->
<div id="footer">
<div id="footer_in">
<div>
<div style="text-align: center; padding: 18px 0 0;">
<?php include_once('plantillas/pl_footer.php');?>
</div>
</div>
</div>
</div>
<!--footer end-->
</div>
</div>
</div>
</div>
<!--
<jdoc:include type="modules" name="debug" />
-->
</body>
</html>
*/?>