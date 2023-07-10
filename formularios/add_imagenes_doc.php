<?php 
$ci           = $_GET["ci"]; 
$doc          = $_GET["doc"];
$ficha        = $_GET["ficha"]; 

$sql      = "SELECT control.url_doc, IFNULL(ficha_documentos.link, 0) link 
               FROM control LEFT JOIN ficha_documentos 
			                       ON ficha_documentos.cod_ficha = '$ficha' 
								  AND ficha_documentos.cod_documento = '$doc' ";			  
$query01  = $bd->consultar($sql);
$row01    = mysql_fetch_row($query01);
$url      = $row01[0];		
$link     = $row01[1];		

$titulo     = "Subir Imagen ";
//$img_src    = $url.$link;		
$img_src    = $link;
$directorio = "../imagenes/documentos/";
$url_new    = "imagenes/documentos/";
if(file_exists($img_src)){ 
//if (!getimagesize($img_src))  {
	
 }else{
	$img_src = "imagenes/img-no-disponible.png"; 
	 }
?>
<script type="text/javascript" src="upload/functions.js"></script>
<link rel="stylesheet" href="jQueryUpLoadyPHP/css/estilos.css" type="text/css" />
<link rel="stylesheet" href="upload/upload.css" type="text/css" />
<!-- <script src="Jcrop/js/jquery.min.js"></script> -->
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div><hr />
<div id="Contenedor01" class="mensaje"></div>

<form enctype="multipart/form-data" class="formulario">
	<table width="100%">
    	<tr><td width="100%"><div id="contenedorImagen"><img id="fotografia" class="fotografia" src="<?php echo $img_src?>"></div>
                      <input name="images" type="file" id="imagen"   value="Subir Imagen"/><br/>
                <span class="art-button-wrapper" id="imgMostrar"  style="display:none">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span> 
                      <input type="button" id="subir_img" value="Subir imagen"class="readon art-button" 
                             onClick="subirImagenS3()" /></span>
                <br/>
                 <br/>
                    <!--div para visualizar mensajes-->
    <div class="messages"></div><br /><br />
    <!--div para visualizar en el caso de imagen-->
    <div class="showImage"></div>
        	</td></tr>
	</table>	
        <input name="ficha" id="ficha" type="hidden"  value="<?php echo $ficha;?>" />
        <input name="ci" id="ci" type="hidden"  value="<?php echo $ci;?>" />
		<input name="doc" id="doc" type="hidden"  value="<?php echo $doc;?>" />   
   		<input name="url_new" id="url_new" type="hidden"  value="<?php echo $url_new;?>" />   
 </form>