$(function() {
    // Imprimimos el valor de la variable
  llenar_clasificacion_check();
  llenar_tipo_check('TODOS');
  });

function llenar_clasificacion_check(){
    var parametros ={};
    $.ajax({
		data:  parametros,
		url:   'packages/nov_check_rp/views/llenar_clasif.php',
		type:  'post',
		success:  function (response) {
            
            $('#clasificacion').html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

function llenar_tipo_check(clasif){

    var parametros ={clasificacion:clasif,inicial:'TODOS'};
    $.ajax({
		data:  parametros,
		url:   'ajax/Add_novedades_tipo.php',
		type:  'post',
		success:  function (response) {
            
            $('#tipo').html(response);

            
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}
function enviar_pdf(){
	if($('#tipo').val()=='TODOS' || $('#clasificacion').val()=='TODOS'){
		toastr.error('Debe Seleccionar Clasificación y Tipo');
	}else{
		$('#reporte').val('pdf');
		$('#tipos').val($('#tipo option:selected').text());
		$('#procesar').click();
	}

}

function enviar_excel(){
	$('#reporte').val('excel');
	$('#tipos').val($('#tipo option:selected').text());
	$('#procesar').click();
}

