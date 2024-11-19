<?php
	$mensaje = "";
	
	 echo '<input type="hidden" id="mensaje_aj" value="'.$mensaje.'"/>';						   
     echo '<script language="javascript">
	       location.href="'.$href.'";
	       </script>';		    
/*
	if (mysql_errno($cnn)==0){
	commit();	
	echo $mensaje;
	 echo '<input type="hidden" id="mensaje_aj" value="'.$mensaje.'"/>';						   
     echo '<script language="javascript">
	       location.href="'.$href.'";
	       </script>';		    

	 }else{

	 	$Nerror = mysql_errno($cnn);
		$Merror = mysql_error($cnn);
	
		rollback();
		mensajeria("".Errror_Ms($Nerror, $Merror)."");
	 echo '<input type="hidden" id="mensaje_aj" value="'.Errror_Ms($Nerror, $Merror).'"/>';
	 echo '<script language="javascript">
			   location.href="'.$href.'";
			   </script>';	
	 }
mysql_close($cnn);
*/
?>
