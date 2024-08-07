<link rel="stylesheet" href="css/modal.css" type="text/css" media="screen" />
<!-- <script type="text/javascript" src="funciones/modal.js"></script> -->
<style>
	/* Google Maps */

	.label {
		box-sizing: border-box;
		background: #05F24C;
		box-shadow: 2px 2px 4px #333;
		border: 5px solid #346FF7;
		height: 20px;
		width: 20px;
		border-radius: 10px;
		-webkit-animation: pulse 1s ease 1s 3;
		-moz-animation: pulse 1s ease 1s 3;
		animation: pulse 1s ease 1s 3;
	}

	.autocomplete-input-container {
		position: absolute;
		z-index: 1;
		width: 100%;
	}

	.autocomplete-input {
		text-align: center;
	}

	#my-input-autocomplete {
		box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
		font-size: 15px;
		border-radius: 3px;
		border: 0;
		margin-top: 10px;
		width: 290px;
		height: 40px;
		text-overflow: ellipsis;
		padding: 0 1em;
	}

	#my-input-autocomplete:focus {
		outline: none;
	}

	.autocomplete-results {
		margin: 0 auto;
		right: 0;
		left: 0;
		position: absolute;
		display: none;
		background-color: white;
		width: 80%;
		padding: 0;
		list-style-type: none;
		margin: 0 auto;
		border: 1px solid #d2d2d2;
		border-top: 0;
		box-sizing: border-box;
	}

	.autocomplete-item {
		padding: 5px 5px 5px 35px;
		height: 26px;
		line-height: 26px;
		border-top: 1px solid #d9d9d9;
		position: relative;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
	}

	.autocomplete-icon {
		display: block;
		position: absolute;
		top: 7px;
		bottom: 0;
		left: 8px;
		width: 20px;
		height: 20px;
		background-repeat: no-repeat;
		background-position: center center;
	}

	.autocomplete-icon.icon-localities {
		background-image: url(https://images.woosmap.com/icons/locality.svg);
	}

	.autocomplete-item:hover .autocomplete-icon.icon-localities {
		background-image: url(https://images.woosmap.com/icons/locality-selected.svg);
	}

	.autocomplete-item:hover {
		background-color: #f2f2f2;
		cursor: pointer;
	}

	.autocomplete-results::after {
		content: "";
		padding: 1px 1px 1px 0;
		height: 18px;
		box-sizing: border-box;
		text-align: right;
		display: block;
		background-image: url(https://maps.gstatic.com/mapfiles/api-3/images/powered-by-google-on-white3_hdpi.png);
		background-position: right;
		background-repeat: no-repeat;
		background-size: 120px 14px
	}
</style>
<script type="text/javascript" src="packages/precliente/cliente/controllers/clienteCtrl.js"></script>
<?php
$Nmenu = '476';
if (isset($_SESSION['usuario_cod'])) {
	require_once('autentificacion/aut_verifica_menu.php');
	$us = $_SESSION['usuario_cod'];
} else {
	$us = $_POST['usuario'];
}
?>
<div id="Cont_precliente">
</div>
<input name="usuario" id="usuario" type="hidden" value="<?php echo $us; ?>" />


<div id="myModalMap" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<span class="close" onclick="closeModalMap()">&times;</span>
			<span>Mapa de Dirección</span>
		</div>
		<div class="modal-body">
			<div id="modal_contenido" style="height: 600px;">
				<!-- Search input -->
				<div class="autocomplete-input-container">
					<div class="autocomplete-input">
						<textarea cols="100" rows="4" id="my-input-autocomplete" placeholder="Busca una dirección" autocomplete="off" role="combobox"></textarea>
					</div>
					<ul class="autocomplete-results">
					</ul>
				</div>
				<!-- Google map -->
				<div id="mapCliente" style="height: 90%;"></div>
				<span class="art-button-wrapper" id="buttonSave" style="margin-bottom: 20px; height: 40px !important;">
					<span class="art-button-l"> </span>
					<span class="art-button-r"> </span>
					<input type="button" id="volver" value="Guardar" class="readon art-button" onclick="saveLatLng()" />
				</span>
			</div>
		</div>
	</div>
</div>