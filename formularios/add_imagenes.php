<?php 
$ci           = $_GET["ci"]; 
$img_nomb     = $usuario;
$img_nomb_dst = $ci;
$imgURL = "imagenes/temp/";
$ext    = "jpg"; 
$tipo   = $_GET["tipo"];
$titulo = "Subir Imagen ";

if( $tipo == "01"){
$dst_w  = "200";
$dst_h  = "240";	
$imgDST = "imagenes/fotos/";
$img_src = "imagenes/foto.jpg";
}elseif ( $tipo == "02"){
  $imgDST = "imagenes/cedula/";
  $img_src =  "imagenes/cedula.jpg";
$dst_w  = "410";
$dst_h  = "280";
	}else{
	 exit;
	}
?>

<link rel="stylesheet" href="jQueryUpLoadyPHP/css/estilos.css" type="text/css" />
<!-- <script src="Jcrop/js/jquery.min.js"></script> -->
<script src="jQueryUpLoadyPHP/js/AjaxUpload.2.0.min.js"></script>
<script>
    $(function() {
    	// Botón para subir la firma
	   var img_url = $('#x1').val;
		var btn_firma = $('#addImage'), interval;
			new AjaxUpload('#addImage', {
				action: 'jQueryUpLoadyPHP/includes/uploadFile.php?valor=<?php echo $img_nomb;?>&imgURL=../../<?php echo $imgURL;?>',
				onSubmit : function(file , ext){
					if (! (ext && /^(jpg|png)$/.test(ext))){
						// extensiones permitidas
						alert('Sólo se permiten Imagenes .jpg o .png');
						// cancela upload
						return false;
					} else {
						document.getElementById('ext').value = ext;
						$('#loaderAjax').show();
						btn_firma.text('Espere por favor');
						this.disable();
					}
				},
				onComplete: function(file, response){
					// alert(response);
					console.log(response);
					btn_firma.text('Cambiar Imagen');
					
					respuesta = $.parseJSON(response);

					if(respuesta.respuesta == 'done'){
						 prueba();							
						 	
					}
					else{
						alert(respuesta.mensaje);
					}
						
					$('#loaderAjax').hide();	
					
					this.enable();	
				}
		});
    });
</script>
<script type="text/javascript">
  function prueba(){
//	  alert("hola");
	  	var ci    = document.getElementById('cedula').value;
		var tipo  = document.getElementById('tipo').value;
	  
	  	window.location.href="inicio.php?area=formularios/add_imagenes2&ci="+ci+"&tipo="+tipo+"";
 };  

</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div><hr />
<div id="Contenedor01" class="mensaje"></div>
<table width="100%">
    <tr><td width="100%"><div id="contenedorImagen">
                    <img id="fotografia" class="fotografia" src="<?php echo $img_src;?>"></div>
                <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span> 
                      <input type="button" id="addImage"  value="Subir Imagen" class="readon art-button" />	
                </span>
                <div class="loaderAjax" id="loaderAjax">
                    <img src="">
                    <span>Publicando Fotografía...</span>
                </div></td></tr>z
</table>	    
    <div class="inline-labels">
    <input type="hidden" size="4" id="x1" name="x1" /> 
    <input type="hidden" size="4" id="y1" name="y1" />
    <input type="hidden" size="4" id="x2" name="x2" />
    <input type="hidden" size="4" id="y2" name="y2" />
    <input type="hidden" size="4" id="w" name="w" />
    <input type="hidden" size="4" id="h" name="h" />
    <input type="hidden"  id="imgURL" name="imgURL" value="<?php echo $imgURL;?>" />  
    <input type="hidden"  id="imgDST" name="imgDST" value="<?php echo $imgDST;?>" /> 
    <input type="hidden" id="dst_w" name="dst_w" value="<?php echo $dst_w;?>" />  
    <input type="hidden" id="dst_h" name="dst_h" value="<?php echo $dst_h;?>" /> 
    <input type="hidden" id="img_nomb" name="img_nomb" value="<?php echo $img_nomb;?>" />
    <input type="hidden" id="img_nomb_dst" name="img_nomb_dst" value="<?php echo $img_nomb_dst;?>"/>
    <input type="hidden" id="ext" name="ext" value="<?php echo $ext;?>"/>
    <input type="hidden" id="subida" name="subida" value="NO"/>  
    <input type="hidden" id="cedula" name="cedula" value="<?php echo $ci;?>"/>  
    <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo;?>"/>  
       
    </div>