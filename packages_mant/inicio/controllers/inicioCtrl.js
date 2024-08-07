var us   = '';
var l    = '';
var t    = '';
var code = '';
var codigo;
var correo;

$(function() {
	Cons_inicio();

});

function Cons_inicio(){

	var error        = 0;
	var errorMessage = ' ';
	if(error == 0){
		var parametros = { };
		$.ajax({
			data:  parametros,
			url:   'packages_mant/inicio/views/Cons_inicio.php',
			type:  'post',
			success:  function (response) {
				$("#Cont_inicio").html(response);
				captcha();
				$("#log_us").val("");
				$("#log_p").val("");

			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}else{
		toastr.error(errorMessage);
	}
}

function captcha(){

	var alpha = new Array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	var alpha02 = new Array('i','b', 'a','r', 't', 'i' );
	var i;
	for (i=0;i<6;i++){
		var a = alpha02[Math.floor(Math.random() * alpha02.length)];
		var b = alpha[Math.floor(Math.random() * alpha.length)];
		var c = alpha[Math.floor(Math.random() * alpha.length)];
		var d = alpha[Math.floor(Math.random() * alpha.length)];
		var e = alpha[Math.floor(Math.random() * alpha.length)];
	}
	//  var code = a + ' ' + b + ' ' + c + ' ' + d + ' ' + e;
	code = ' '+ a + ' ' + b + ' ' + c + ' ' + d ;
	// $("#captcha").text(code);
	$("#log_cap").val('');

	$("#captcha_contenedor").html('<canvas id="captcha" width="120" height="30">Your browser does not support the HTML5 canvas tag.</canvas>');
	CreaIMG(code);
	

}

function removeSpaces(valorX){


	return valorX.split(' ').join('');
}
// 	ctx.textAlign="center";
//	ctx.textBaseline="middle";
function CreaIMG(texto) {


	var c = document.getElementById("captcha");
	var ctx = c.getContext("2d");

	ctx.beginPath();
	ctx.rect(10, 40, 50, 50);
	ctx.fillStyle = "#FF0000";
	ctx.fill();
	ctx.closePath();

	ctx.shadowColor = "rgb(190, 190, 190)";
	ctx.shadowOffsetX = 10;
	ctx.shadowOffsetY = 10
	ctx.shadowBlur = 10;
	ctx.font = "25px arial";
	ctx.textAlign = "start";
	var gradient = ctx.createLinearGradient(0, 0, 50, 10);
	gradient.addColorStop(0, "rgb(255, 0, 128)");
	gradient.addColorStop(1, "rgb(255, 153, 51)");
	ctx.fillStyle = gradient;
	ctx.fillText(texto,5, 20);

	ctx.beginPath();
	ctx.lineCap="round";
	ctx.lineWidth = 2;
	ctx.moveTo(4,15);
	ctx.lineTo(110,20);
	ctx.stroke();
	ctx.closePath();

	ctx.beginPath();
	ctx.moveTo(10, 15);
	ctx.lineTo(110, 5);
	ctx.lineWidth = 1;
	ctx.strokeStyle = '#ff0000';
	ctx.stroke();
	ctx.closePath();
}

function Ver_log(){
	l        = $("#log_us").val();
	var p    = $("#log_p").val();
	var cap  = $("#log_cap").val();
	var error = 0;
	var errorMessage = "";

	if(l.length <4){
		var error = 1;
		var errorMessage = "Debe Ingresar un login Valido";
	}

	if((p.length <2) && (error == 0)){
		var error = 1;
		var errorMessage = "Debe Ingresar una Contraseña";
	}

	if((removeSpaces(code) != cap ) && (error == 0)){
		var error = 1;
		var errorMessage = "Captcha Invalidad";
		captcha();
	}

	if (error == 0) {

		var parametros = {l:l,p:p,metodo:"verificar_us",proced:"p_api_usuario"}
		$.ajax({
			data:  parametros,
			url:   'packages_mant/inicio/modelo/inicio.php',
			type:  'post',
			success:  function (response) {
				console.log(response);
				var resp = JSON.parse(response);
				if((resp.error == true) && (resp.error== "VENC")) {
					captcha();
					$("#log_us").val('');
					$("#log_p").val('');
					r = confirm("Contraseña Vencida, ¿Desea Actualizar Contraseña?");
					if (r == true) {

						$('#Modal_lg').modal("show");
						$("#modal_titulo").text("Actualizar Contraseña");
						var parametros = {l:l};
						$.ajax({
							data:  parametros,
							url:   'packages/sistema/inicio/views/Act_password.php',
							type:  'post',
							success:  function (response) {
								$("#cont_modal").html(response);
							},
							error: function (xhr, ajaxOptions, thrownError) {
								alert(xhr.status);
								alert(thrownError);}
							});
					}

				}else if ((resp.error=="TRUE") || (resp.error==true)) {
					toastr.success(resp.mensaje);
					captcha();
					$("#log_us").val('');
					$("#log_p").val('');
				}else {
					window.location.href = "inicio.php?area=formularios/index&mod=000&Nmenu=000";
					sessionStorage.setItem('condi',"F");
					sessionStorage.setItem('numero',0);
					localStorage.setItem('userIbartiKanban', resp.codigo);
					localStorage.setItem('admin_kanban', resp.admin_kanban);
					localStorage.setItem('admin_rrhh', resp.admin_rrhh);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}else{
		toastr.error(errorMessage);
	}
}

function transformar_email(email){
	var email_c = "";
	var s = false;
	for (var i = 0; i < (email.length ) ; i++) {
		if (i==0){
			email_c+= email[i];

		}
		else{
			if(email[i]=='@'){
				s = true;
				email_c+= email[i];		
			}
			else{
				if(s){
					email_c+= email[i];
				}else{
					email_c+= '*';

				}
			}

		}
	}
	return email_c;

}


function comprobar_email(){
	var actual = $('#email').val();
	//console.log(actual)
	actual = actual.toLowerCase();
	correo = correo.toLowerCase();
	if (correo==actual){
		return true;
	}else{
		return false;
	}
}

////////////////////////////////////////
var codigo_usuario;
var old_c;
function comprobar_usuario(){
	var usuario = $('#user').val();
	var parametros = {'usuario':usuario};
	var email_mod;
	var email;
	
	$.ajax({
		data:  parametros,
		url:   'packages_mant/inicio/views/get_data_user.php',
		type:  'post',
		beforeSend: function(){
			$("#cargas").html('<img src="imagenes/carga.gif" width="24px">');
		},
		success:  function (response) {
			//console.log(response);
			$("#cargas").html('<img src="imagenes/buscar.gif" onclick="comprobar_usuario()"  width="24px" title="Verificar usuario">');	
			var data = JSON.parse(response);
			if (data != null){
				correo = data['email'];
				codigo_usuario = data['codigo'];
				old_c = data['pass'];

				email_mod = transformar_email(correo);

				$('#correo').html(email_mod);
				$("#fase1").hide();
				$("#fase2").show();
			}else{
				toastr.warning('USUARIO NO ENCONTRADO');
			}
			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

function codigo_correo(){
	var alpha = new Array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	var alpha02 = new Array('i','b', 'a','r', 't', 'i' );
	var i;
	for (i=0;i<6;i++){
		var a = alpha02[Math.floor(Math.random() * alpha02.length)];
		var b = alpha[Math.floor(Math.random() * alpha.length)];
		var c = alpha[Math.floor(Math.random() * alpha.length)];
		var d = alpha[Math.floor(Math.random() * alpha.length)];
		var e = alpha[Math.floor(Math.random() * alpha.length)];
	}
	//  var code = a + ' ' + b + ' ' + c + ' ' + d + ' ' + e;
	var codes =  a + b + c + d + e;
	//console.log(codes);
	return codes;
	
}

function enviar_mail(){
	//console.log(old_c)
	var nombre = $('#user').val();
	codigo = codigo_correo();

	//console.log(codigo)
	var parametros = {"envio":correo,
	"nombre":nombre,
	"codigo":codigo};
	if(comprobar_email()){
		$.ajax({
			data:  parametros,
			url:   'packages_mant/inicio/views/enviar_correo.php',
			type:  'post',
			beforeSend: function(){
				$('#fase2').hide();
				$('#cargando').show();
			},
			success:  function (response) {
				var resp = JSON.parse(response);
				if((typeof resp.error == "undefined") || (resp.error == false)){
					toastr.success(resp.mensaje);
					$("#fase2").hide();
					$("#fase3").show();
					$('#cargando').hide();
				}else{
					$('#fase2').show();
					toastr.error(resp.mensaje);
				}

			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);}
			});
	}else{
		toastr.error('Correo invalido');
	}
}

function cambiar_clave(){
	var new_p = $('#new').val();
	var new_pv = $('#new-verif').val();
	
	if(new_p == new_pv){
		var parametros =  {'cod':codigo_usuario,'old':old_c,'new':new_p};
		$.ajax({
			data:  parametros,
			url:   'packages_mant/inicio/views/set_data_user.php',
			type:  'post',
			success:  function (response) {
				//console.log(data);
				//console.log(response);
				var resp = response.replace("\r\n\r\n","");
				if(resp == "true"){
				//console.log('CAMBIADO CORRECTAMENTE');
				toastr.success('Su clave fue actualizada con exito');
				Cons_inicio();
			}else{
				toastr.error('Se ha producido un error',response);
				//console.log('NO SE PUEDO CAMBIAR');
			}
			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
	}else{
		toastr.warning('Las claves ingresadas son distintas!..');
	//console.log(old_c)
}


}

function verificar_codigo(){
	var codigo_verifica = $('#cod').val();
	if(codigo_verifica == codigo){
		//return true;
		$("#fase3").hide();
		$("#fase4").show();
		
	}else{
		toastr.warning("Codigo Invalido!..");
	}
}

function recuperar_pass(){
	var parametros ={};
	$.ajax({
		data:  parametros,
		url:   'packages_mant/inicio/views/Cons_recupera.php',
		type:  'post',
		success:  function (response) {
			$("#Cont_inicio").html(response);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.status);
			alert(thrownError);}
		});
}

//////////////////////