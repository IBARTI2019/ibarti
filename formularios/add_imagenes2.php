<?php 
$ci           = $_GET["ci"]; 
$img_nomb     = $usuario;
$img_nomb_dst = $ci;
$imgURL = "imagenes/temp/";
$ext    = "jpg"; 
$tipo   = $_GET["tipo"];
$subida = "SI";
$titulo = " Guardar Imagen Y Recortar ";

if( $tipo == "01"){
$dst_w  = "200";
$dst_h  = "240";  
$imgDST = "imagenes/fotos/";
$img_src = "imagenes/temp/$usuario.jpg";

}elseif ( $tipo == "02"){
  $imgDST = "imagenes/cedula/";
  $img_src = "imagenes/temp/$usuario.jpg";
$dst_w  = "410";
$dst_h  = "280";
  }else{
   exit;
  }

?>
<script src="Jcrop/js/jquery.min.js"></script>
<script src="Jcrop/js/jquery.Jcrop.js"></script>

<script type="text/javascript">
    
  jQuery(function($){
    // Create variables (in this scope) to hold the API and image size
    var jcrop_api,
 
       xsize = document.getElementById('dst_w').value, 
      ysize = document.getElementById('dst_h').value ;
     // alert(xsize);
     // alert(size); 
    
    console.log('init',[xsize,ysize]);
    $('#fotografia').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
     onRelease:  clearCoords,
      aspectRatio: xsize / ysize
    },function(){
      // Use the API to get the real image size
   //   var bounds = this.getBounds();
    //  boundx = bounds[0];
     // boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;

      // Move the preview into the jcrop container for css positioning
   //    $preview.appendTo(jcrop_api.ui.holder);
    });
  
  
    $('#coords').on('change','input',function(e){
      var x1 = $('#x1').val(),
          x2 = $('#x2').val(),
          y1 = $('#y1').val(),
          y2 = $('#y2').val();
      jcrop_api.setSelect([x1,y1,x2,y2]);
    });

    function updatePreview(c)
    {
  
  $('#x1').val(c.x);
    $('#y1').val(c.y);
    $('#x2').val(c.x2);
    $('#y2').val(c.y2);
    $('#w').val(c.w);
    $('#h').val(c.h);   
    };

  function clearCoords(){
    $('#x1').val('');
    $('#y1').val('');
    $('#x2').val('');
    $('#y2').val('');
    $('#w').val('');
    $('#h').val('');  
  };

  }); 
  
      
  function SaveImg(){
  var Contenedor = "Contenedor01";
  var valor   = "jQueryUpLoadyPHP/includes/red_img.php"; 


  var ext      = document.getElementById('ext').value;
  var img_nomb  = document.getElementById('img_nomb').value;
  var img_nomb_dst  = document.getElementById('img_nomb_dst').value;
  var imgURL  = document.getElementById('imgURL').value;
  var imgDST  = document.getElementById('imgDST').value;
    var imgURL   = imgURL+img_nomb+"."+ext; 
    var imgDST   = imgDST+img_nomb_dst+"."+ext;
  var dst_w   = document.getElementById('dst_w').value;   
  var dst_h   = document.getElementById('dst_h').value;
  var x1      = document.getElementById('x1').value;
  var x2      = document.getElementById('x2').value;
  var y1      = document.getElementById('y1').value;
  var y2      = document.getElementById('y2').value;
  var w      = document.getElementById('w').value;
  var h      = document.getElementById('h').value;


  var subida  = document.getElementById('subida').value;
  
  var campo01 = 1; 
    var errorMessage = 'Debe Seleccionar Todo Los Campos';

     if(subida!='SI') {
     var errorMessage = 'Debe Subir Una Imagen';   
   var campo01 = campo01+1; 
  }  


  if(campo01 == 1){

  
  ajax=nuevoAjax();
  
    ajax.open("POST", valor, true);
    ajax.onreadystatechange=function()  
    { 
      if (ajax.readyState==4){          
      document.getElementById(Contenedor).innerHTML = ajax.responseText;
      Mensaje();
      
      }
    } 
    // alert("imgURL="+imgURL+"&imgDST="+imgDST+"&dst_w="+dst_w+"&dst_h="+dst_h+"&x1="+x1+"&x2="+x2+"&y1="+y1+"&y2="+y2+"&w="+w+"&h="+h+"");
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");     
  ajax.send("imgURL="+imgURL+"&imgDST="+imgDST+"&dst_w="+dst_w+"&dst_h="+dst_h+"&x1="+x1+"&x2="+x2+"&y1="+y1+"&y2="+y2+"&w="+w+"&h="+h+"");
  }else{
  alert(errorMessage);
   }
}    

  function Mensaje(){
      
   var valor = document.getElementById('mensaje').value;
    var valor = document.getElementById('mensaje').value;
//   var imgDST = document.getElementById('imgDST').value;
//   var img_nomb_dst = document.getElementById('img_nomb_dst').value;
//   var ext          = "jpg";
//   var src          = ""+imgDST+img_nomb_dst+"."+ext;    
   alert(valor);
   window.history.go(-2);

  } 
</script>
<link rel="stylesheet" href="Jcrop/css/jquery.Jcrop.css" type="text/css" />
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div><hr />
<div id="Contenedor01" class="mensaje"></div>
<form action="scripts/sc_ficha.php" method="post" name="add" id="add" enctype="multipart/form-data"> 
<table width="100%" align="center">
    <tr><td width="100%"><div id="contenedorImagen">
                    <img id="fotografia" class="fotografia" src="<?php echo $img_src.'?nocache='.time().'';?>"></div>
               <div align="center"><span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span> 
                      <input type="button" id="saveImg" onClick="SaveImg()" value="Guardar Imagen" class="readon art-button" /> 
                </span></div></tr>

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
    <input type="hidden" id="subida" name="subida" value="<?php echo $subida;?>"/>  
    </div>
    </form>