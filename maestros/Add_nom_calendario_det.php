<?php
	$Nmenu = '339';
	require_once('autentificacion/aut_verifica_menu.php');
	$tabla = "vendedores";
	$bd = new DataBase();
	$codigo = $_GET["codigo"];
	$cod_det = $_GET["cod_det"];
	$proced = "p_nom_calendario_det";
	$proced2 = "p_nom_calendario";

	$archivo = "nom_calendario";
	$titulo = " CALENDARIO ";
	$vinculo = "inicio.php?area=maestros/Add_$archivo&Nmenu=$Nmenu&mod=".$_GET['mod']."&archivo=$archivo";
	$vinculo2 = "inicio.php?area=maestros/Add_".$archivo."_det&Nmenu=$Nmenu&mod=".$_GET['mod']."&archivo=$archivo";

		$sql = " SELECT nom_calendario.descripcion, nom_calendario.tipo,
                        men_usuarios.nombre, men_usuarios.apellido, nom_calendario.fec_us_mod
                   FROM nom_calendario LEFT JOIN men_usuarios ON nom_calendario.cod_us_mod = men_usuarios.codigo

                  WHERE nom_calendario.codigo = '$codigo'  ";
   $query   = $bd->consultar($sql);
   $datos   = $bd->obtener_fila($query,0);
   $titulo  = $datos[0];
   $tipo    = $datos[1];
   $us_mod  = $datos[2].' '.$datos[3].' , '. $datos[4];

	if($tipo =="FIJO"){
    	$display = 'style="display:none"';
	}else{
	    $display = '';
	}

	// Los feriados FIJOS se repiten todos los años en el mismo día/mes: el año con que
	// fueron guardados no tiene significado, así que para precargar el picker se
	// remapean al año actual (CURDATE()) — de lo contrario, un feriado fijo guardado
	// en un año anterior se vería "sin marcar" al abrir esta pantalla en un año nuevo,
	// aunque el motor de asistencia sí lo siga reconociendo como feriado todos los años.
	// La rama del calendario enlazado ($cod_det) siempre es FIJO por construcción (el
	// dropdown "Calendario Fijo" solo ofrece calendarios tipo FIJO), así que se remapea
	// siempre; la rama propia ($codigo) solo se remapea si este calendario es FIJO.
	$sql2 = " SELECT DATE_FORMAT(
	                    IF('$tipo' = 'FIJO',
	                       STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(nom_calendario_det.fecha), '-', DAY(nom_calendario_det.fecha)), '%Y-%m-%d'),
	                       nom_calendario_det.fecha),
	                    '%m/%d/%Y') fecha
	            FROM nom_calendario_det
			       WHERE nom_calendario_det.cod_calendario = '$codigo'
						 UNION
						 SELECT DATE_FORMAT(
						          STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(nom_calendario_det.fecha), '-', DAY(nom_calendario_det.fecha)), '%Y-%m-%d'),
						          '%m/%d/%Y') fecha
	            FROM nom_calendario_det
				      WHERE nom_calendario_det.cod_calendario = '$cod_det'
	 			      ORDER BY 1 ASC";
    $query2  = $bd->consultar($sql2);



	$sql3 = " SELECT COUNT(nom_calendario_det.fecha) AS cantidad
                FROM nom_calendario_det
			   WHERE nom_calendario_det.cod_calendario = '$codigo' ";
    $query3  = $bd->consultar($sql3);
	$datos3   = $bd->obtener_fila($query3,0);

	$fecha_cantidad = $datos3[0];
 $fecha_matris = " ";
		while($datos=$bd->obtener_fila($query2,0)){

			$fecha_matris .= "'".$datos[0]."',";
		}

$sql4 = " SELECT COUNT(a.codigo), IFNULL(a.codigo, '') AS codigo, IFNULL(a.descripcion, 'Seleccione...') AS descripcion
            FROM nom_calendario, nom_calendario AS a
           WHERE nom_calendario.codigo = '$codigo'
             AND nom_calendario.cod_calendario_NL = a.codigo ";

    $query4  = $bd->consultar($sql4);
	$datos4   = $bd->obtener_fila($query4,0);


	$cod_calendario = $datos4[1];
	$calendario     = $datos4[2];

$sql_calend = " SELECT nom_calendario.codigo, nom_calendario.descripcion
                  FROM nom_calendario
                 WHERE nom_calendario.tipo = 'FIJO'
                   AND nom_calendario.`status` = 'T' ";

//	print_r($fecha_matris);
?>
<script type="text/javascript" src="multidatespicker/js/jquery-1.11.1.js"></script>
<script type="text/javascript" src="multidatespicker/js/jquery-ui-1.11.1.js"></script>
<script type="text/javascript" src="multidatespicker/jquery-ui.multidatespicker.js"></script>
<link rel="stylesheet" type="text/css" href="multidatespicker/css/mdp.css">


      <script type="text/javascript">
			// Traducción al español

			$(function() {

				$.datepicker.regional['es'] = {
						closeText: 'Cerrar',
						prevText: '<Ant',
						nextText: 'Sig>',
						currentText: 'Hoy',
						monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
						monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
						dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
						dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
						dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
						weekHeader: 'Sm',
						dateFormat: 'dd/mm/yy',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''
				};

$.datepicker.setDefaults($.datepicker.regional['es']);

				// Run Demos
				$('.demo .code').each(function() {
					eval($(this).attr('title','NEW: edit this code and test it!').text());
					this.contentEditable = true;
				}).focus(function() {
					if(!$(this).next().hasClass('test'))

						$(this)
							.after('<button class="test">test</button>')
							.next('.test').click(function() {
								$(this).closest('.demo').find('.hasDatepicker').multiDatesPicker('destroy');
								eval($(this).prev().text());
								$(this).remove();
							});
				});


			});


function Guardar(){
	var codigo     =  $("#codigo").val();
	var calendario_Fijo = $("#calendario_Fijo").val();
	var tipo       = $("#tipo").val();
  var fechas     = $('#full-year').multiDatesPicker('getDates', 'string');
	var usuario    = $("#usuario").val();
	var proced     = $("#proced").val();
	var proced2    = $("#proced2").val();

	var campo01    = 1;
	 //alert(concepto+concepto_old);
	 var errorMessage = 'Debe Seleccionar Todo Los Campos';

     if(codigo =='') {
	 var campo01 = campo01+1;
     }

     if(fechas =='') {
	 	 var campo01 = campo01+1;
     }

	 if(tipo == 'VAR'){
		 if(calendario_Fijo =='') {
		 var campo01 = campo01+1;
		 }
	 }
	if(campo01 == 1){
		var valor = "sc_maestros/sc_nom_calendario_det.php";
		ajax=nuevoAjax();
	ajax.open("POST", valor, true);
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4){
		document.getElementById("Contenedor01").innerHTML = ajax.responseText;
			setInterval(alert(""+document.getElementById("mensaje_aj").value+""), 1000);
		//window.location.href=""+href+"";
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
ajax.send("codigo="+codigo+"&tipo="+tipo+"&calendario_Fijo="+calendario_Fijo+"&fechas="+fechas+"&href=&usuario="+usuario+"&proced="+proced+"&proced2="+proced2+"&metodo=modificar");

	 }else{
	alert(errorMessage);
	 }
}

/*
//$(document).ready(function(){

	$.each(json, function(i,item){
alert(json[i].fecha);
//		document.write("<br>"+i+" - "+miJSON[i].valor+" - "+miJSON[i].color+" - "+miJSON[i].caracteristica.tipo+" - "+miJSON[i].caracteristica.ref);

	});

// })
*/

/*
					$('#full-year').multiDatesPicker({
						addDates: ['01/03/2015', "02/02/2015"],
							numberOfMonths: [3,4],
						defaultDate: '1/1/'+y
					});
*/
function Feriado(cod_det){

	var codigo     =  $("#codigo").val();
 var errorMessage = 'Debe Seleccionar Un Campo';
	var campo01    = 1;
   if(codigo =='') {
	 	 	var campo01 = campo01+1;
   }

	if(campo01 == 1){

		var valor = "ajax/Add_nom_feriados_fecha_det.php";

		$.ajax({
		type: "POST", dataType: "json", url: valor, data: "action=''&codigo="+codigo+"&cod_det="+cod_det,
		complete: function(data){

 var content = JSON.parse(data.responseText);
// alert(content.length);
var dateArray = new Array();
	for (var i in content){
		dateArray[""+i+""] = new Date(content[i].fecha);
 }


// alert(dateArray);


$('#full-year').multiDatesPicker('resetDates');

$('#full-year').multiDatesPicker({
//		addDates: ['01/03/2015', "02/02/2015"],

 addDates: dateArray,

	numberOfMonths: [3,4],
	defaultDate: '1/1/'+y
});


		}
		});


// alert(typeof(json));

	 }else{
	alert(errorMessage);
	 }
}

</script>
<div align="center" class="etiqueta_title"><?php echo $titulo;?> </div> <hr />
<?php if ($tipo == "FIJO") { ?>
<div align="center" class="texto" style="color:#5a6472; font-size:12px; margin-bottom:6px;">
    Este calendario es <b>Fijo</b>: el año que selecciones abajo no importa, cada feriado se aplicará todos los años en el mismo día y mes.
</div>
<?php } ?>
<div align="center" class="texto" <?php echo $display;?>> Calendario Fijo : <select name="calendario_Fijo" id="calendario_Fijo"
	                                                                                  onchange="Feriado(this.value)" style="width:250px" value="0">
							<option value="<?php echo $cod_calendario;?>"><?php echo $calendario;?></option>
          <?php
		            $query = $bd->consultar($sql_calend);
            		while($datos=$bd->obtener_fila($query,0)){
		  ?>
          <option value="<?php echo $datos[0];?>"><?php echo $datos[1];?></option>
          <?php }?>
        </select>
        <img src="imagenes/ok.gif" alt="Valida" class="validMsg" border="0"/><br />
        	<span class="selectRequiredMsg">Debe Seleccionar Un Campo.</span></div> <hr />
<div id="Contenedor01"></div>
<div id="demos">
				<ul id="demos-list">
				<li class="demo full-row">
						<div id="full-year" class="box"></div>
						<div class="code-box"></div>
					</li>
				</ul>
				<div class="clear"></div>
		</div>

<div align="center">
<span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" name="salvar"  id="salvar" value="Guardar" onclick="Guardar()" class="readon art-button" />
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="reset" id="limpiar" value="Restablecer" class="readon art-button" />
                </span>&nbsp;
             <span class="art-button-wrapper">
                    <span class="art-button-l"> </span>
                    <span class="art-button-r"> </span>
                <input type="button" id="volver" value="Volver" onClick="history.back(-1);" class="readon art-button" />
                </span>
		    <input name="metodo" type="hidden"  value="<?php echo $metodo;?>" />
            <input name="pestana" type="hidden"  value="ficha" />
		    <input name="codigo" id="codigo" type="hidden"  value="<?php echo $codigo;?>" />
	        <input name="href" type="hidden" value="../inicio.php?area=<?php echo $archivo ?>"/>
			<input type="hidden"  name="usuario" id="usuario" value="<?php echo $usuario;?>"/>
            <input name="proced" id="proced" type="hidden"  value="<?php echo $proced;?>" />
            <input name="proced2" id="proced2" type="hidden"  value="<?php echo $proced2;?>" />
            <input name="fecha_cantidad" id="fecha_cantidad" type="hidden"  value="<?php echo $fecha_cantidad;?>" />
           <input name="tipo" id="tipo" type="hidden"  value="<?php echo $tipo;?>" />
</div>
<div class="texto" id="right">Usuario Modifico: ( <?php echo $us_mod;?>)</div>
<script type="text/javascript">
	var today = new Date();
	var y = today.getFullYear();

	if(document.getElementById("fecha_cantidad").value == 0){
		$('#full-year').multiDatesPicker({
		numberOfMonths: [3,4],
		defaultDate: '1/1/'+y
	});
	}else{
	$('#full-year').multiDatesPicker({
//		addDates: ['01/03/2015', "02/02/2015"],
        addDates:[<?php echo $fecha_matris;?>],
		numberOfMonths: [3,4],
		defaultDate: '1/1/'+y
	});
	}
</script>
