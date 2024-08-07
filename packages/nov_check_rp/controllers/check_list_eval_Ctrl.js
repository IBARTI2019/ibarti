$(function() {
    // Imprimimos el valor de la variable
	llenar_clasificacion_check_eval();
  	llenar_tipo_check('TODOS');
  });

function llenar_clasificacion_check_eval(){
    var parametros ={};
    $.ajax({
		data:  parametros,
		url:   'packages/nov_check_rp/views/llenar_clasif_eval.php',
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

    var parametros ={clasificacion:clasif, inicial:'TODOS', campo04: 'P'};
	console.log(parametros);
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


function enviar_excel(){
	$('#reporte').val('excel');
	$('#procesar').click();
}

