$(function() {
	Cons_upm_inicio();
});

function Cons_upm_inicio(callback) {
	var parametros = { }
	
	$.ajax({
		data:  parametros,
		url:   'packages/ficha_militar/views/Cons_inicio.php',
		type:  'post',
		success:  function (response) {
			$("#contenedor").html(response);
			// llenar_tabla_militar();
			operar('agregar','');
			if(typeof callback == 'function'){
				callback();	
			}
			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}
function llenar_tabla_militar(){
	var parametros = { }
	$("#listar").html('');
	$.ajax({
		data:  parametros,
		url:   'packages/ficha_militar/views/Get_status_militar.php',
		type:  'post',
		success:  function (response) {
			$("#listar").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

function operar(operacion,codigo) {

	var parametros = {'metodo':operacion,'titulo':'FICHA MILITAR','tb': 'ficha_status_militar','codigo':codigo}
	
	$.ajax({
		data:  parametros,
		url:   'packages/general/views/maestros.php',
		type:  'post',
		success:  function (response) {
			$("#contenedor").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}
function eliminar_militar(codigo){
	var parametros = {'codigo':codigo}
	
	$.ajax({
		data:  parametros,
		url:   'packages/ficha_militar/views/delete_ficha_militar.php',
		type:  'post',
		success:  function (response) {
			console.log(response)
			$('#'+codigo).remove();
			//llenar_tabla_militar();

		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}
