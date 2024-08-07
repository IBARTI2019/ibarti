<link rel="stylesheet" href="css/modal_planif.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="latest/stylesheets/autocomplete.css" />

<?php
$Nmenu = '720';
if(isset($_SESSION['usuario_cod'])){
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
}else{
	$us = $_POST['usuario'];
}
?>

<div id="myModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="CloseModal()" >&times;</span>
			<span id="modal_titulo"></span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido">
				<br>
				<div align="center" class="etiqueta_title">Ingrese la Descripcion(Motivo de la Anulación)</div>
				<br>
				<div align="center"><textarea id="ped_descripcion_anular" required  cols="70" rows="3"></textarea></div>
				<br>
				<hr />
				<div align="center">
					<span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" title="Anular Ajuste" class="readon art-button"  value="Anular" onclick="anular()" />
					</span>
					<span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" title="Cancelar" class="readon art-button"  value="Cancelar" 
						onclick="CloseModal()" />
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="eanModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="eanCloseModal()" >&times;</span>
			<span id="modal_titulo"></span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido">
				<br>
				<div align="center" class="etiqueta_title">Listado de Eans <span id="span_cant_ing">(<span id="cant_ing"></span>)</span></div>
				<br>
				<hr />
				<table id="listar_eans">
						<thead>
						    <tr class="fondo00">
						      <th>Codigo EAN</th>
						      <th></th>
						    </tr>
						 </thead>
						 <tbody id="listar_eans">
						 </tbody>
				</table>
				<div align="center">
					<span class="art-button-wrapper" id="boton_guardar_eans">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" title="Anular Ajuste" id="boton_eans" class="readon art-button" value="Procesar"/>
					</span>
					<span class="art-button-wrapper">
						<span class="art-button-l"> </span>
						<span class="art-button-r"> </span>
						<input type="button" title="Cancelar" class="readon art-button"  value="Cerrar" 
						onclick="eanCloseModal()" />
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="Cont_ajuste"></div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us;?>" />
<script type="text/javascript" src="funciones/modal.js"></script>
<script type="text/javascript" src="packages/inventario/ajuste/controllers/ajusteCtrl.js"></script>
<script type="text/javascript" src="latest/scripts/autocomplete.js"></script>



