	
$(function() {
	Cons_upm_inicio(()=>{

		llenar_region();
		llenar_estado();
		llenar_ciudad('TODOS');
		llenar_cliente('TODOS');
		llenar_ubicacion('TODOS');
		llenar_puesto('TODOS','TODOS');
		
	});
});

function Cons_upm_inicio(callback) {
	var parametros = { }
	
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Cons_inicio.php',
		type:  'post',
		success:  function (response) {
			$("#form_reportes").html(response);

			callback();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

function llenar_cliente(region){
	$('#cliente').html('');
	var parametros = { 'region':region};
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_cliente.php',
		type:  'post',
		success:  function (response) {
			
			$('#cliente').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}
function llenar_region(){
	$('#region').html('');
	var parametros = { };
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_region.php',
		type:  'post',
		success:  function (response) {
			
			$('#region').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}
function llenar_estado(){
	$('#estado').html('');
	var parametros = { };
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_estados.php',
		type:  'post',
		success:  function (response) {
			
			$('#estado').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}
function llenar_ciudad(estado){
	$('#ciudad').html('');
	var parametros = { 'estado':estado};
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_ciudades.php',
		type:  'post',
		success:  function (response) {
			$('#ciudad').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}

function llenar_puesto(cliente,ubicacion){
	
	$('#puesto').html('');
	var parametros = { 'cliente':cliente, 'ubicacion':ubicacion};
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_puesto.php',
		type:  'post',
		success:  function (response) {
			$('#puesto').html(response);			
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}
function llenar_estatu(){
	$('#estatu').html('');
	var parametros = { };
	$.ajax({
		data:  parametros,
		url:   'packages/clientes_rp/views/Get_estatus.php',
		type:  'post',
		success:  function (response) {
			
			$('#estatu').html(response);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
}

function llenar_ubicacion(cliente){
   // var cliente =$('#empresa').val();
   $('#ubicacion').html('');
	var estado = 'TODOS';//$('#estado').val();
	var ciudad = 'TODOS';//$('#ciudad').val();

   var parametros = {'cliente':cliente ,'estado':estado,'ciudad':ciudad};
   if(cliente!='TODOS'){
   	$.ajax({
   		data:  parametros,
   		url:   'packages/clientes_rp/views/Get_ubicacion.php',
   		type:  'post',
   		success:  function (response) {
   			var datos = JSON.parse(response);
			
   			$('#ubicacion').append('<option value="TODOS">selecione...</option>');
   			datos.forEach((res,i)=>{
   				$('#ubicacion').append("<option value='"+ res[0] + "'>"+res[1]+"</option>");

   			});
   			seleccion = datos;


					
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);}
				});
   }else{
   	$('#ubicacion').append('<option value= "TODOS">TODOS</option>');
   }
}

var seleccion ;
function rellenar(valor,ubicacion){
	llenar_puesto(seleccion[valor - 1].cod_cliente,ubicacion);
}

function reportes(tipo) {
	var faltante = "";
	var ubicacion = $('#ubicacion option:selected').index();
	var indice = ubicacion - 1;
	
	var estado = $('#estado').val();
	var ciudad = $('#ciudad').val();
	var cliente = $('#cliente').val();

	if (cliente!='TODOS'){
		if((indice+1)>0){
			$('#region').val(seleccion[indice].cod_region);

		if(seleccion[indice].cod_estado != estado){
			faltante+='\nFalta Estado:'+ seleccion[indice].estado;
		}
		if(seleccion[indice].cod_ciudad != ciudad){
			faltante+='\nFalta Ciudad:'+ seleccion[indice].ciudad;
		}
		}
		
	
	}
	
	
	if(faltante==''){
		$('#reporte').val(tipo);
		$('#procesar').click();
		toastr.success('PROCESANDO...');
	}else{
		$('#reporte').val(tipo);
		$('#procesar').click();
		toastr.warning(faltante,'ERROR',{timeOut:0});
	}
}