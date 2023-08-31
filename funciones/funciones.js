function Status(valor) {
	var result = "F";
	if ((valor == "T") || (valor == "TRUE") || (valor == "on")) {
		result = "T";
	}
	return result;
}
function Fondo(ids, eventos) {
	var evento = eventos;
	var id = ids;
	if (evento == 'over') {
		Spry.Utils.addClassName('' + id + '', 'fondo');
	}
	if (evento == 'out') {
		Spry.Utils.removeClassName('' + id + '', 'fondo');
	}
}

function Fondos(campoId, evento, classNew, classOld) {
	if (evento == 'A') {
		Spry.Utils.removeClassName('' + campoId + '', classOld);
		Spry.Utils.addClassName('' + campoId + '', classNew);
	}
	if (evento == 'D') {
		Spry.Utils.removeClassName('' + campoId + '', classNew);
		Spry.Utils.addClassName('' + campoId + '', classOld);
	}
}

function Vinculo(vinculos) {
	var vinculo = vinculos;
	window.location.href = "" + vinculo + "";
}

function bloquearCampo(varible, campo) {
	if (varible == "Focus") {
		document.getElementById(campo).readOnly = true;
	}
	if (varible == "Blur") {
		document.getElementById(campo).readOnly = false;
	}
}

function Popup(url, width, height) {
	window.open("" + url + "", "target=blank", "toolbar=null,scrollbars=YES,location=null,statusbar=null,menubar=yes,      resizable=null,width=" + width + ",height=" + height + ",left=80,top="); void (null);
}

function Popup3(url, width, height) {
	window.open("" + url + "", "target=blank", "toolbar=null,scrollbars=YES,location=null,statusbar=null,menubar=null,      resizable=null,width=" + width + ",height=" + height + ",left=80,top="); void (null);
}

function Popup2(direccion) {
	var url = direccion;
	window.open("" + url + "", "toolbar=null,scrollbars=YES,location=null,statusbar=null,menubar=yes,      resizable=null,width=450,height=600,left=,top="); void (null);
}

function borrarElement(contenedor, elementoId) {
	RemoveElement = document.getElementById(contenedor);
	RemoveElement.removeChild(document.getElementById(elementoId));

}

function Reload() {
	window.location.reload();
}

function ActivarElemento(CampoID, tipo) {
	var Activando = document.getElementById(CampoID);
	if (tipo == 'Activar') {
		Activando.style.display = "none";
	}
	if (tipo == 'Desactivar') {
		Activando.style.display = "";
	}
}

function VolverA() {
	history.back();
}
function Centrar() {
	iz = (screen.width - document.body.clientWidth) / 2;
	de = (screen.height - document.body.clientHeight) / 2;
	moveTo(iz, de);
}


function eliminarReg(Campo_id, Metodos, Archivos) {

	var id = Campo_id;
	var metodo = Metodos;
	var archivo = Archivos

	ajax = nuevoAjax();
	ajax.open("POST", archivo, true);
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4) {
			window.location.reload();
		}
	}
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("id=" + id + "&metodo=" + metodo + "&archivo=" + metodo + "")
}

function imprimirDoc() {  // CARGAR EL MODULO DE AGREGAR//
	// <div id="contenorP" align="center"><img id="printer" src="../imagenes/printer.png" onclick="imprimirDoc('contenorP', 'printer')" /></div>
	borrarElement('ContenedorImpresora', 'Impresora');
	window.print();
}
function Nifty02() {
	if (!NiftyCheck())
		return;
	Rounded("div#nifty01", "top", "transparent", "#888888", "border #C0C0C0");
	Rounded("div#nifty01", "bottom", "transparent", "#FFFFFF", "small border #C0C0C0");
	Rounded("div#nifty02", "top", "transparent", "#888888", "border #C0C0C0");
	Rounded("div#nifty02", "bottom", "transparent", "#FFFFFF", "small border #C0C0C0");
}

function OcultarCampo(campo) {

	var checkCont = document.getElementById(campo);
	//var checkCont = document.getElementById("hijo_cont");
	//var hijo = document.getElementById(campo);
	if (checkCont.style.display == "none") {
		checkCont.style.display = "";
		//hijo.disabled=false;

	}
	else {
		checkCont.style.display = "none";
		//hijo.value="";
		//hijo.disabled = true;
	}
}

function EstadoFiltro(valor) {
	filtroId = document.getElementById("paciFiltro");
	filtroIndice = filtroId.selectedIndex;
	filtroValue = filtroId.options[filtroIndice].value;

	var validar = document.getElementById("stdName");
	if (filtroValue == '') {
		validar.disabled = true;
	} else {
		validar.disabled = "";
	}
}
function Salir01(idX) { // CARGAR EL MODULO DE AGREGAR //
    numX=1;
	if (confirm("�Esta Seguro de Cerrar")) {
		document.getElementById('table'+idX).remove();
		document.getElementById('incremento').value = numX -1;
	}
}
function Borrar01(idX) {  // CARGAR EL MODULO DE AGREGAR //
	if (confirm("�Esta Seguro De Borrar Este Registro")) {
		var tabla = document.getElementById("tabla").value;
		var valor = "sc_maestros/sc_maestros.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById("Contenedor01").innerHTML = ajax.responseText;
				setTimeout(alert("" + document.getElementById("mensaje_aj")?.value + ""), Reload(), 1000);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + idX + "&metodo=borrar&tabla=" + tabla + "&activo=f&descipcion=");
	}
}
function Procesar01(cod_prod,idX) {  // CARGAR EL MODULO DE AGREGAR //
	
    var idean='';
    if (confirm("�Desea Procesar listado EANS")) {
		var tabla = "vectoreans";
		var valor = "sc_maestros/sc_maestros_auxvector.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById("Contenedor01").innerHTML = ajax.responseText;
				// setTimeout(alert("" + document.getElementById("mensaje_aj").value + ""), Reload(), 1000);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + cod_prod + "&codigoean=" + idean + "&metodo=agregarean&tabla=" + tabla + "");
	} 

    document.getElementById('table' + cod_prod).remove();
    document.getElementById('botong').remove();
    document.getElementById('botons').remove();
    document.getElementById("boton").type="button";
    document.getElementById("boton_"+idX).value="EANS"; 
    
    
    
    
    
}

function showHint(cod_prod){
	var numX     =1;
    var  metodo="buscar";
    var esans="";
    var producto  = document.getElementById('producto_'+numX+'').value;
    console.log("policia"+producto);
    var cantidad= Number(document.getElementById('cantidad_'+numX+'').value);
	if(numX != ''){
		var valor = "ajax/Add_prod_dotacion_det_clientes_modal.php";
		var contenido = "Contenedor01_"+numX+"";
		ajax=nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4){
				document.getElementById(contenido).innerHTML = ajax.responseText;
				document.getElementById('incremento').value = numX;
				spryN(numX);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo="+producto+"&numero="+numX+"&tieneeans="+esans+"&metodo="+metodo+"&cantidad="+cantidad+"&comodin="+cod_prod+"");
	}else{
		alert("Falta Codificacion ");
	}
   document.getElementById(producto).remove();
    document.getElementById('botong').remove();
    document.getElementById('botons').remove();
}


function Clickup(idX,idean,cantidad) {
	
	var cod_prod=idX;
    var cantidadaux= Number(cantidad);
    
	if (cod_prod != '') {
		var parametros = { "codigo": cod_prod};
		$.ajax({
			data: parametros,
			url: 'packages/cliente/cl_ubicacion/views/CantidadEANS_clientes.php',
			type: 'post',
			success: function (response) {
				var resp = JSON.parse(response);
				if(resp.error){
					toast.danger(resp.mensaje);
				}else{
                    
					if(resp.length < cantidadaux){
                        ClickupVector(idX,idean,cantidad);                        
					}else{	
						  alert("La cantidad no puede ser Menor que EANS");
						}
					
				}
			}
		 });
	
    }
}

function ClickupVector(idX,idean,cantidad) {  // CARGAR EL MODULO DE AGREGAR //
       
        var tabla = "vectoreans";
		var valor = "sc_maestros/sc_maestros_auxvector.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById("Contenedor01").innerHTML = ajax.responseText;
				// setTimeout(alert("" + document.getElementById("mensaje_aj").value + ""), Reload(), 1000);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + idX + "&codigoean=" + idean + "&metodo=vector&tabla=" + tabla + "");  
 

}
function Borrar02(codigo, codigo2) {  // CARGAR EL MODULO DE AGREGAR//

	if (confirm("�Esta Seguro De Borrar Este Registro")) {
		var tabla = document.getElementById("tabla").value;
		var valor = "sc_maestros/sc_maestros.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById("Contenedor01").innerHTML = ajax.responseText;
				setInterval(alert("" + document.getElementById("mensaje_aj").value + ""), Reload(), 1000);
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "&campoXV=" + codigo2 + "&campoXR=codigo_examen&metodo=Borrar2&tabla=" + tabla + "");
	}
}


function Add_ajax01(codigo, archivo, contenido) {  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	if (codigo != '') {

		ajax = nuevoAjax();
		ajax.open("POST", archivo, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "");
	} else {
		alert("Debe de Seleccionar Un Campo ");
	}
}

function Add_ajax02(codigo, codigo2, archivo, contenido) {  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO//

	if (codigo != '') {
		ajax = nuevoAjax();
		ajax.open("POST", archivo, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "&codigo2=" + codigo2 + "");
	} else {
		alert("Debe de Seleccionar Un Campo ");
	}
}
function Add_ajax_maestros(codigo, archivo, contenido, tb) {  // CARGAR  ARCHIVO DE AJAX CON UN PARAMETRO Y TABLAS//
	
	if (codigo != '') {
      
		ajax = nuevoAjax();
		ajax.open("POST", archivo, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "&tb=" + tb + "");
	} else {
		alert("Debe de Seleccionar Un Campo ");
	}
}
function procesar() {
	alert("Haz hecho clic en el botón");
}
/*
$cod_cliente  = $_POST['codigo'];
$usuario      = $_POST['usuario'];
$name         = $_POST['name'];
$tamano       = $_POST['tamano'];
$evento       = $_POST['evento'];
*/

function Filtrar_select(idX, name, archivo, contenedor, px, evento) {

	if (idX != "") {
		ajax = nuevoAjax();
		ajax.open("POST", archivo, true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenedor).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + idX + "&name=" + name + "&usuario=9999&tamano=" + px + "&evento=" + evento + "");
	}
}

function Add_Cl_Ubic(valor, contenido, activar, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
    
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Un Cliente ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_cl_ubic2.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				if (activar == "T") {
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "");
	} else {
		alert(errorMessage);
	}
}
function Add_Cl_Alcance(valor, contenido, activar, tamano) {  
	var error = 0;
	var errorMessage = ' ';
  
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Una Ubicacion ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_cl_ubic3.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				if (activar == "T") {
				}
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "");
	} else {
		alert(errorMessage);
	}
}
function Add_Ub_puesto(valor, contenido, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Una Ubicacion ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_ub_puesto.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano);
	} else {
		alert(errorMessage);
	}
}

function Add_Trab_Pl(ficha, fecha_desde, fecha_hasta, contenido, tamano) {  // CARGAR  LOS CLIENTES EN LOS QUE EL TABAJADOR ESTE PLANIFICADO //
	var error = 0;
	var errorMessage = ' ';
	if (ficha == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Un Trabajador ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_Trab_Pl.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				console.log(ajax.responseText);
				document.getElementById(contenido).innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + ficha + "&tamano=" + tamano + "&fecha_desde=" + fecha_desde + "&fecha_hasta=" + fecha_hasta + "");
	} else {
		alert(errorMessage);
	}
}

function Add_Sub_Linea(valor, contenido, activar, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Una Linea ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_sub_linea2.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				/*
				if(activar == "T"){
				Add_filtroX();
				}	*/
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "");
	} else {
		alert(errorMessage);
	}
}

function Add_Prod_Filtro(valor, contenido, activar, tamano) {  // CARGAR  UBICACION DE CLIENTE  Y tama�o  //
	var error = 0;
	var errorMessage = ' ';
	if (valor == '') {
		var error = error + 1;
		errorMessage = errorMessage + ' \n Debe Seleccionar Una Linea ';
	}
	if (error == 0) {
		ajax = nuevoAjax();
		ajax.open("POST", "ajax/Add_prod_filtro.php", true);
		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				document.getElementById(contenido).innerHTML = ajax.responseText;
				/*  if(activar == "T"){
					Add_filtroX();
					} */
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + valor + "&tamano=" + tamano + "&activar=" + activar + "");
	} else {
		alert(errorMessage);
	}
}

function fechaValida(fecha) {

	if (fecha != undefined && fecha.value != "") {
		var fechaArr = fecha.split('-');
		var dia = fechaArr[0];
		var mes = fechaArr[1];
		var anio = fechaArr[2];

		switch (mes) {
			case "01":
			case "03":
			case "05":
			case "07":
			case "08":
			case "10":
			case "12":
				numDias = 31;
				break;
			case "04":
			case "06":
			case "09":
			case "11":
				numDias = 30;
				break;
			case "02":
				if (comprobarSiBisisesto(anio)) { numDias = 29 } else { numDias = 28 };
				break;
			default:
				return false;
		}

		if (dia > numDias || dia == 0) {
			return false;
		}
		return true;
	}
}

function comprobarSiBisisesto(anio) {
	if ((anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
		return true;
	}
	else {
		return false;
	}
}
