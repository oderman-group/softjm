<?php include("sesion.php");

$idPagina = 175;

include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';

$consultaCombos=$conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consultaCombos, MYSQLI_BOTH);

$disabled="disabled";
if(Modulos::validarRol([388], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
	$disabled="";
}
?>
<!-- styles -->
<link href="css/chosen.css" rel="stylesheet">
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
<style>
select#product-select.select2-hidden-accessible {
  position: absolute !important;
  width: 1px !important;
  height: 1px !important;
  padding: 0 !important;
  margin: -1px !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  border: 0 !important;
}
#product-select + .select2-container {
  width: 100% !important;
  max-width: 100%;
  display: inline-block !important;
}
#product-select + .select2-container .select2-selection--multiple {
  min-height: 38px;
}
#product-select + .select2-container .select2-search--inline .select2-search__field {
  width: 12em !important;
  min-width: 12em !important;
  margin-top: 4px;
}
</style><!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="../assets-login/plugins/select2/js/select2.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/funciones-js.php");?>

<?php include("includes/texto-editor.php");?>

<script type="text/javascript">
  function productos(enviada){
  	  var campo = enviada.title;
	  var producto = enviada.name;
	  var proceso = 7;
	  var valor = enviada.value;
	  $('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#resp').empty().hide().html(data).show(1);
				   }
			   });
}	
</script>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="combos.php">Combos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			
			<?php
			$combosCotizacion = Combo::numeroCotizacionesCombo($_GET["id"], $conexionBdPrincipal);

			if($combosCotizacion > 0){
				$msjCombo = "";
				$msjCombo = "Este combo se encuentra incluído en <b>".$combosCotizacion."</b> documentos.";
				$colorCombo = 'gold';
			?>	
				<p style="color: black; background-color: <?=$colorCombo;?>; padding: 10px; font-weight: bold;"><?=$msjCombo;?></p>
			<?php }?>
			
			<div class="row-fluid">
				<div class="span3">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3>IMAGEN<h3>
						</div>
						<?php if($resultadoD['combo_imagen']!=""){?>		
							<p align="center"><img src="files/combos/<?=$resultadoD['combo_imagen'];?>" width="80"></p>
						<?php }?>		
					</div>	
				</div>
						
				<div class="span9">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/combos-actualizar.php" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" value="<?=$resultadoD['combo_nombre'];?>" <?=$disabled;?>>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Imagen</label>
									<div class="controls">
										<input type="file" class="span8" name="foto" <?=$disabled;?>>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" name="descripcion" <?=$disabled;?>><?=$resultadoD['combo_descripcion'];?></textarea>
									</div>
								</div>
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escriba para buscar productos..." class="span10" tabindex="2" name="producto[]" multiple id="product-select" data-ajax-url="<?= htmlspecialchars(REDIRECT_ROUTE . '/usuarios/ajax/ajax-buscar-productos.php', ENT_QUOTES, 'UTF-8'); ?>" <?=$disabled;?>>
												<option value=""></option>
												<?php
            									$consultaProductos = $conexionBdPrincipal->query("SELECT * FROM combos_productos 
												INNER JOIN productos ON prod_id=copp_producto AND prod_id_empresa='".$idEmpresa."'
												WHERE copp_combo='".$resultadoD['combo_id']."' ORDER BY prod_nombre");

												while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
												?>
													<option selected value="<?= $resProducto['prod_id']; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['prod_existencias'] . "]"; ?></option>
												<?php
													}
												?>
											</select>
											<?php if ($disabled === '') { ?>
											<span class="help-block" style="margin-top:6px;">Para agregar: haga clic y escriba el nombre o código. Para quitar: use la X del producto. Luego pulse <b>Guardar cambios</b>.</span>
											<?php } else { ?>
											<span class="help-block" style="margin-top:6px;color:#a94442;">No tiene permiso para editar productos del combo (página 388: Permiso editar valores combos).</span>
											<?php } ?>
										</div>
								   </div>
								
								<div class="control-group">
									<label class="control-label">Descuento</label>
									<div class="controls">
										<input type="text" class="span2" name="dcto" value="<?=$resultadoD['combo_descuento'];?>" <?=$disabled;?>>
									</div>
								</div>


								<!--
								<div class="control-group">
									<label class="control-label">Descuento Dealer</label>
									<div class="controls">
										<input type="text" class="span2" name="dctoDealer" value="<?=$resultadoD['combo_descuento_dealer'];?>" <?=$disabled;?>>
									</div>
								</div>-->

								<input type="hidden" class="span2" name="dctoDealer" value="<?=$resultadoD['combo_descuento_dealer'];?>" <?=$disabled;?>>

                                   
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="estado" <?=$disabled;?>>
											<option value="0"></option>
											<option value="1" <?php if($resultadoD['combo_estado']==1)echo "selected";?>>Activo</option>
											<option value="0" <?php if($resultadoD['combo_estado']==0)echo "selected";?>>Inactivo</option>
                                    	</select>
                                    </div>
                               </div>

							   <div class="control-group">
									<label class="control-label">Descuento Máximo en Cotización</label>
									<div class="controls">
										<input type="text" class="span2" name="descuentoMax" value="<?=$resultadoD['combo_descuento_maximo'];?>" <?=$disabled;?>>
									</div>
								</div>
 
								<div class="form-actions">

									<?php if(Modulos::validarRol([217], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
									
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>
                              
                                </form>

						</div>
					</div>
				</div>
			</div>
			
			
			<div class="row-fluid">
				<div class="span12">

					<div class="row-fluid">
						<div class="span12">
							<div class="hero-unit">
								<h2>Valores originales VS valores actuales</h2>
								<p>
									Los valores marcados como <b>(original)</b> se refiere al momento cuando el combo fue creado por primera vez. Si desea actualizarlos con los valores actuales de los productos haga click sobre el ícono de sincronización <i class="icon-refresh "></i>, que está ubicado al lado de cada producto.
								</p>
							</div>
						</div>
					</div>

					<span id="resp"></span>
					
					<div class="content-widgets light-gray" id="productos">
						<div class="widget-head green">
							<h3>PRODUCTOS</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio (original)</th>
								<th>SUBTOTAL (Original)</th>
                                <th style="border-left: solid;">Precio lista (Actual)</th>
                                <th>SUBTOTAL (Actual)</th>
                                <th style="color: darkblue; border-left: solid;">Precio dealer</th>
                                <th style="color: darkblue;">SUBTOTAL DEALER</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$no = 1;
							$totalDealer=0;
							$total=0;
							$totalCantidad=0;
							$totalOriginal = 0;
							$subtotalOriginal = 0;
							$productos = $conexionBdPrincipal->query("SELECT * FROM productos 
							INNER JOIN productos_categorias ON catp_id=prod_categoria
							INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$_GET["id"]."'
							ORDER BY copp_id");
							while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){

								$prodCosto = (float) $prod['prod_costo'];
								$prodPrecio = (float) $prod['prod_precio'];
								$coppPrecio = (float) $prod['copp_precio'];
								$coppCantidad = (float) $prod['copp_cantidad'];
								$utilidadDealer = ((float) $prod['prod_descuento2']) / 100;
								$precioDealer = $prodCosto + ($prodCosto * $utilidadDealer);
								$subtotalDealer = $precioDealer * $coppCantidad;
								$totalDealer += $subtotalDealer;

									
								$subtotal = $prodPrecio * $coppCantidad;
								$subtotalOriginal = $coppPrecio * $coppCantidad;
								$total += $subtotal;
								$totalOriginal += $subtotalOriginal;
								
								$totalCantidad += $coppCantidad;

								
							?>
							<tr>
								<td><?=$no;?></td>
                                <td>
									<?php if (Modulos::validarRol([305], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="bd_delete/combo-productos-eliminar.php?get=55&idItem=<?=$prod['copp_id'];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<?php } ?>
									<?php if (Modulos::validarRol([418], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $coppPrecio <> $prodPrecio) {?>
										<a href="bd_update/combo-productos-sincronizar.php?idItem=<?=$prod['copp_id'];?>&nuevoPrecio=<?=$prod['prod_precio'];?>" onClick="if(!confirm('Desea sincronizar este producto con los valores actuales?')){return false;}"><i class="icon-refresh "></i></a>
									<?php } ?>
									<?php if (Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="productos-editar.php?id=<?=$prod['prod_id'];?>" target="_blank"><?=$prod['prod_id']." - ".$prod['prod_nombre']." <b>(Quedan ".$prod['prod_existencias']." unds.)</b>";?></a>
									<?php } else {?>
									<span><?=$prod['prod_id']." - ".$prod['prod_nombre'];?></span>
									<?php } ?>
								</td>
                                <td><input type="number" title="copp_cantidad" name="<?=$prod['copp_id'];?>" value="<?=$prod['copp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;" <?=$disabled;?> translate="no"></td>
                                <td>$<?=number_format($coppPrecio,0,",",".");?></td>
								<td>$<?=number_format($subtotalOriginal,0,",",".");?></td>
                                <td style="border-left: solid;">$<?=number_format($prodPrecio,0,",",".");?></td>
								<td>$<?=number_format($subtotal,0,",",".");?></td>
								<td style="color: darkblue; border-left: solid;">$<?=number_format($precioDealer,0,",",".");?></td>
								<td style="color: darkblue;">$<?=number_format($subtotalDealer,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							$descuentoCombo = (float) $resultadoD['combo_descuento'];
							$descuento = $total * ($descuentoCombo / 100);
							$totalNeto = $total - $descuento;

							$descuentoDealer = $totalDealer * ($descuentoCombo / 100);
							$totalNetoDealer = $totalDealer - $descuentoDealer;

							//$descuentoDealer = ($total * ($resultadoD['combo_descuento_dealer']/100));
							//$totalNetoDealer = ($total - $descuentoDealer);	
							?>
	
							</tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="4">SUBTOTAL ORIGINAL</td>
									<td>$<?=number_format($totalOriginal,0,",",".");?></td>
									<td style="text-align: right; border-left: solid;">SUBTOTAL ACTUAL</td>
									<td>$<?=number_format($total,0,",",".");?></td>

									<td style="text-align: right; color: darkblue; border-left: solid;">SUBTOTAL DEALER</td>
									<td style="color: darkblue;">$<?=number_format($totalDealer,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="6">DESCUENTO</td>
									<td>$<?=number_format($descuento,0,",",".");?></td>

									<td style="text-align: right; color: darkblue; border-left: solid;">DESCUENTO DEALER</td>
									<td style="color: darkblue;">$<?=number_format($descuentoDealer,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="6">TOTAL NETO</td>
									<td>$<?=number_format($totalNeto,0,",",".");?></td>

									<td style="text-align: right; color: darkblue; border-left: solid;">TOTAL NETO DEALER</td>
									<td style="color: darkblue;">$<?=number_format($totalNetoDealer,0,",",".");?></td>
								</tr>

								<!--	
								<tr style="font-weight: bold; font-size: 16px; color: blue;">
									<td style="text-align: right;" colspan="5">DESCUENTO DEALER</td>
									<td>$<?=number_format($descuentoDealer,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px; color: blue;">
									<td style="text-align: right;" colspan="5">TOTAL NETO DEALER</td>
									<td>$<?=number_format($totalNetoDealer,0,",",".");?></td>
								</tr>
							-->

							</tfoot>	
								
							</table>
							
						</div>
					</div>
				</div>
			</div>			
            

		</div>
	</div>
	<?php include("includes/pie.php");?>
	<script>
	window.__defineBak = window.define;
	window.define = undefined;
	</script>
	<script src="../assets-login/plugins/select2/js/select2.js"></script>
	<script>
	window.define = window.__defineBak;
	(function ($) {
		var $el = $("#product-select");
		if (!$el.length) {
			return;
		}
		if (typeof $.fn.select2 !== "function") {
			console.error("Select2 no disponible en combos-editar");
			return;
		}

		if ($el.hasClass("select2-hidden-accessible")) {
			$el.select2("destroy");
		}

		var ajaxUrl = $el.attr("data-ajax-url") || "ajax/ajax-buscar-productos.php";
		var opts = {
			placeholder: "Escriba para buscar productos...",
			multiple: true,
			width: "100%",
			closeOnSelect: false,
			allowClear: true
		};

		if ($el.prop("disabled")) {
			opts.disabled = true;
			$el.select2(opts);
			return;
		}

		opts.minimumInputLength = 1;
		opts.language = {
			inputTooShort: function () { return "Escriba al menos 1 caracter para buscar"; },
			noResults: function () { return "Sin resultados"; },
			searching: function () { return "Buscando..."; }
		};
		opts.ajax = {
			url: ajaxUrl,
			dataType: "json",
			delay: 250,
			data: function (params) {
				return { term: params.term || "" };
			},
			processResults: function (data) {
				if (!data || !$.isArray(data)) {
					return { results: [] };
				}
				return {
					results: $.map(data, function (item) {
						return { id: String(item.id), text: item.text };
					})
				};
			}
		};

		$el.select2(opts);

		$el.on("select2:open", function () {
			setTimeout(function () {
				$(".select2-container--open .select2-search__field")
					.css({ width: "200px", minWidth: "200px" })
					.trigger("focus");
			}, 0);
		});
	})(jQuery);
	</script>
</div>
</body>
</html>
