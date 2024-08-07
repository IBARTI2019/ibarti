<?php
$sql01 =	"SELECT clientes_ub_alcance.cod_sub_linea cod_producto, prod_sub_lineas.descripcion producto, clientes_ub_alcance.cantidad,
				 clientes_ub_alcance.dias,  clientes_ub_alcance.vencimiento
                   FROM clientes_ub_alcance, prod_sub_lineas
                  WHERE clientes_ub_alcance.cod_sub_linea = prod_sub_lineas.codigo
				  AND clientes_ub_alcance.cod_cl_ubicacion = '$codigo'";
?>
<script language="javascript">
    function agregarAlcance(auto, metodo) {
        var cod_producto = document.getElementById("stdID" + auto + "").value;
		var codigo = document.getElementById("codigo_ubic").value;
		var cantidad = document.getElementById("cantidad").value;
        var errorMessage = 'Debe Ingresar minimo 1 producto ';
        var campo01 = 0;
       
        if (!cod_producto) {
            campo01++;
		}
		if (cantidad == 0) {
            campo01++;
        }
        if (campo01 == 0) {
            //var href = "inicio.php?area=maestros/Cons_Servicio_Tipo";
            var valor = "scripts/sc_cl_ubic_validar_alcance.php";
            ajax = nuevoAjax();
            ajax.open("POST", valor, true);
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4) {
                    // document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
                    //window.location.href=""+href+"";
                    if (ajax.responseText == 0) {
                        ValidarAlcance(auto, metodo);
                    } else {
                        alert("Ya Existe este Registro (" + cod_producto + ")");
                    }
                }
            }
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.send("cod_producto=" + cod_producto + "&codigo=" + codigo + "");
        } else {
            alert(errorMessage);
        }
    }

	function ValidarAlcance(auto, metodo) {
		var cod_ubic = document.getElementById("codigo_ubic").value;
		var usuario = document.getElementById("usuario").value;
        var cod_producto = document.getElementById("stdID" + auto + "").value;
		var cantidad = document.getElementById("cantidad" + auto + "").value;
		var dias = document.getElementById("dias" + auto + "").value;
		var vencimiento = Status($('input:checkbox[id=vencimiento'+auto+']:checked').val());
		
		var valor = "scripts/sc_cl_ubic_alcance.php";
		var proced = "p_cl_ubic_alcance";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
				if ((metodo == "agregar") || (metodo == "eliminar")) {
					ActualizarDet(cod_ubic);
					if(metodo == "agregar"){
						$("#stdID").val("");
						$("#codigo_producto").val("");
						$("#cantidad").val(0)
					}
				}else{
					toastr.success('Actualizado con exito');
				}
				//window.location.href=""+href+"";
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + cod_ubic + "&cod_producto=" + cod_producto + "&cantidad=" + cantidad + "&dias=" + dias + "&vencimiento=" + vencimiento + "&usuario=" + usuario + "&href=''&metodo=" + metodo + "&proced=" + proced + "");
	}

	function BorrarAlcance(auto, metodo) {
		if (confirm("� Esta Seguro Eliminar Este Registro ")) {
			var cod_ubic = document.getElementById("codigo_ubic").value;
            var cod_producto = document.getElementById("codigo_producto" + auto + "").value;
            
			var cantidad = document.getElementById("cantidad" + auto + "").value;
			var dias = document.getElementById("dias" + auto + "").value;
			var vencimiento = Status($('input:checkbox[id=vencimiento'+auto+']:checked').val());
			var ususario = "";
            if (confirm("� policia " +cod_producto)) {
              alert("polciia") ;   
            }
			var valor = "scripts/sc_cl_ubic_alcance.php";
			var proced = "p_cl_ubic_alcance";

			ajax = nuevoAjax();
			ajax.open("POST", valor, true);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4) {
					document.getElementById("Cont_mensaje").innerHTML = ajax.responseText;
					ActualizarDet(cod_ubic);
					//window.location.href=""+href+"";
				}
			}
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("codigo=" + cod_ubic + "&cod_producto=" + cod_producto + "&cantidad=" + cantidad + "&dias=" + dias + "&vencimiento=" + vencimiento + "&usuario=" + usuario + "&href=''&metodo=" + metodo + "&proced=" + proced + "");
		} else {
			alert(errorMessage);
		}
	}

	function ActualizarDet(codigo) {
        var valor = "ajax/Add_cl_ubic_alcance.php";
		ajax = nuevoAjax();
		ajax.open("POST", valor, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4) {
				toastr.success('Actualizado con exito'+codigo);
				document.getElementById("Contenedor03").innerHTML = ajax.responseText;
			}
		}
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("codigo=" + codigo + "");
	}
</script>
<div align="center" class="etiqueta_title"> ALCANCE</div>
<hr />
<div id="Cont_mensaje" class="mensaje"></div>
<div>
     <table width="80%" border="0" align="center">
		<tr class="fondo01">
			<!-- <th width="20%" style="display:none" class="etiqueta">Codigo Ubicacion</th> -->
			<th width="60%" class="etiqueta">Sub Linea
            <input type="hidden" name="producto" id="stdID" value=""/></th>
            <th width="14%" class="etiqueta">Cantidad</th>
			<th width="14%" class="etiqueta">Dias para Reponer</th>
			<th width="4%" class="etiqueta">Aplica Reposicion</th>
			<th width="8%"><img src="imagenes/loading2.gif" alt="Agregar Registro" width="40" height="40" title="Agregar Registro" border="null" class="imgLink" /></th>
		</tr>
		<tr class="fondo02">
        <td>       
        <input type="text" id="codigo_producto" tabindex="1" value="" placeholder="Ingrese dato de la sublinea" required style="width:450px"/>
	</td>
      <td>
       <input type="number" id="cantidad" style="width:100px" value="1" min="0"  required placeholder="">
		</td>	
		<td>
       <input type="number" id="dias" style="width:100px" value="1" min="0"  required placeholder="">
		</td>	
		<td>
       <input type="checkbox" id="vencimiento" checked="true" style="width:50px">
		</td>	
		<td><span class="art-button-wrapper">
					<span class="art-button-l"> </span>
					<span class="art-button-r"> </span>
					<input type="button" name="submit" id="submit" value="Agregar" class="readon art-button" onclick="agregarAlcance('', 'agregar')" />
				</span></td>
		</tr>
		</table>
		<table id="Contenedor03" width="80%" border="0" align="center">
		<tr>
			<td colspan="5" class="etiqueta_title">Listado</td>
		</tr>
		<?php
		$query = $bd->consultar($sql01);
		$i = 0;
		$valor = 0;
		while ($datos = $bd->obtener_fila($query, 0)) {
			$i++;
			if ($valor == 0) {
				$fondo = 'fondo01';
				$valor = 1;
			} else {
				$fondo = 'fonddo02';
				$valor = 0;
			}
			$modificar = 	 "'" . $i . "', 'modificar'";
			$borrar    = 	 "'" . $i . "', 'eliminar' ";
			echo '<tr class="' . $fondo . '">
				  <td>     
                  <input type="text" id="codigo_producto'.$i.'" value="'.$datos['producto'].'" disabled  style="width:450px"/>
                  <input type="hidden" name="producto" id="stdID'.$i.'" value="'.$datos['cod_producto'].'"/>
                </td>
                <td>
                 <input type="number" id="cantidad'.$i.'" style="width:100px"  value="'.$datos['cantidad'].'" min="1">
			   </td>
			   <td>
			   <input type="number" id="dias'.$i.'" style="width:100px"  value="'.$datos['dias'].'" min="0">
			 </td>
			 <td>
                 <input type="checkbox" id="vencimiento'.$i.'" style="width:50px" '.statusCheck($datos["vencimiento"]).'/>
			   </td>  
			   <td align="center"><img src="imagenes/actualizar.bmp" alt="Actualizar" title="Actualizar Registro" border="null" width="20px" height="20px" class="imgLink" onclick="ValidarAlcance('.$modificar.')" />&nbsp;
		  <img src="imagenes/borrar.bmp" alt="Detalle" title="Borrar Registro" width="25" height="25" border="null"
			   onclick="BorrarAlcance(' . $borrar . ')" class="imgLink" />
		  </td>
	</tr>';
		} ?>
	</table>
</div>
<div align="center">
	<input name="metodo" type="hidden" value="<?php echo $metodo; ?>" />
	<input type="hidden" name="usuario" value="<?php echo $usuario; ?>" />

	<input type="hidden" id="cod_ubic_alcance" name="codigo" value="<?php echo $codigo; ?>" />
	<input type="hidden" id="i" value="<?php echo $i; ?>" />
</div>
<br />
<br />

<script language="JavaScript" type="text/javascript">
const autoCompletejs = new autoComplete({
  data: {
    src: async function () {
      // Loading placeholder text
      const query = document.querySelector("#codigo_producto").value;
      // Fetch External Data Source
	  const source = await fetch("packages/cliente/cl_ubicacion/views/alcanceGET.php?q="+query+"");
      const data = await source.json();
      // Returns Fetched data
      return data;
    },
    key: ["descripcionFull"],
  },
  sort: function (a, b) {
    if (a.match < b.match) {
      return -1;
    }
    if (a.match > b.match) {
      return 1;
    }
    return 0;
  },
  query: {
    manipulate: function (query) {
      return query.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    },
  },
  trigger: {
    event: ["input","focusin", "focusout"],
    condition: function (query) {
      return !!query.replace(/ /g, "").length && query !== "hamburger";
    },
  },
  selector: "#codigo_producto",
  debounce: 0,
  searchEngine: "strict",
  highlight: true,
  maxResults: 10,
  resultsList: {
    render: true,
    container: function (source) {
	  source.setAttribute("id", "autoComplete_list");
    },
    element: "ul",
    destination: document.querySelector("#codigo_producto"),
    position: "afterend",
  },
  resultItem: {
    content: function (data, source) {
      	source.innerHTML = data.match;
    },
    element: "li",
  },
  noResults: function () {
	
  },
  onSelection: function (feedback) {
	document.querySelector("#codigo_producto").blur();
    const selection = feedback.selection.value.descripcionFull;
    // Clear Input
    document.querySelector("#codigo_producto").value = "";
    // Change placeholder with the selected value
    document.querySelector("#codigo_producto").setAttribute("placeholder", selection);
	$("#stdID").val(feedback.selection.value.codigo);
  },
}); 

  </script>