<?php
// El archivo
// Establecer un ancho y alto máximo

$dst_w = $_POST["dst_w"];
$dst_h = $_POST["dst_h"];
$dst_image2 = $_POST["imgDST"];
$dst_image = "../../".$_POST["imgDST"];
$src_image = "../../".$_POST["imgURL"]; 

$src_x = round($_POST["x1"]);
$src_y = round($_POST["y1"]);
$dst_x = round($_POST["x2"]);
$dst_y = round($_POST["y2"]);

$src_w = round($_POST["w"]);
$src_h = round($_POST["h"]);

//echo $dst_w = round($_POST["w"]),"<br>";
// echo $dst_h = round($_POST["h"]),"<br>";
// list( $src_w,  $src_h) = getimagesize($src_image);
// echo $ancho,"<br>";
// echo $alto,"<br>";

$jpeg_quality = 95;
// Redimensionar

header('Content-type: image/png');
$img_r = imagecreatefrompng($src_image);
$dst_r = imagecreatetruecolor($dst_w, $dst_h);

//imagecopyresampled($dst_r , $img_r , 0 , 0 , 0 ,0, $dst_w ,$dst_h , $src_w , $src_h );
// imagecopyresampled($dst_r , $img_r , 0 , 0 , $src_x , $src_y , $dst_w ,$dst_h ,$dst_w ,$dst_h );
imagecopyresampled($dst_r , $img_r , 0 , 0 , $src_x , $src_y , $dst_w ,$dst_h , $src_w ,$src_h);
// verificar si existe un archivo e  jpg o png

if(imagepng($dst_r, $dst_image) == true){
	
	$mensaje = " IMAGEN GUARDADA CON EXITO ";
	}else{
	$mensaje = " ERROR EN GUARDAR IMAGEN ";	
	}

// Liberar memoria

imagedestroy($dst_r);

echo '<input id="mensaje" type="hidden" value="'.$mensaje.'" />';



?> 