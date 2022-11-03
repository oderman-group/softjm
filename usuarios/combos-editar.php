<?php include("sesion.php");

$idPagina = 175;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consultaCombos=$conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consultaCombos, MYSQLI_BOTH);
?>
<!-- styles -->
<link href="css/chosen.css" rel="stylesheet">
<!--============ javascript ===========-->
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
			$consultaCotizProducto=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_combo='".$_GET["id"]."'");
			$combosCotizacion = $consultaCotizProducto->num_rows;
			if($combosCotizacion>0){
				$msjCombo = "";
				$msjCombo = "Este combo se encuentra incluído en <b>".$combosCotizacion."</b> cotizaciones.";
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
										<input type="text" class="span8" name="nombre" value="<?=$resultadoD['combo_nombre'];?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Imagen</label>
									<div class="controls">
										<input type="file" class="span8" name="foto">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" name="descripcion"><?=$resultadoD['combo_descripcion'];?></textarea>
									</div>
								</div>
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria 
												ORDER BY prod_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													$consultaNumCombosProductos=$conexionBdPrincipal->query("SELECT * FROM combos_productos WHERE copp_producto='".$resOp[0]."' AND copp_combo='".$resultadoD['combo_id']."'");
													$productoN = $consultaNumCombosProductos->num_rows;
												?>
													<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_id'].". ".$resOp['prod_nombre']." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								<div class="control-group">
									<label class="control-label">Descuento</label>
									<div class="controls">
										<input type="text" class="span2" name="dcto" value="<?=$resultadoD['combo_descuento'];?>">
									</div>
								</div>


								<!--
								<div class="control-group">
									<label class="control-label">Descuento Dealer</label>
									<div class="controls">
										<input type="text" class="span2" name="dctoDealer" value="<?=$resultadoD['combo_descuento_dealer'];?>">
									</div>
								</div>-->

								<input type="hidden" class="span2" name="dctoDealer" value="<?=$resultadoD['combo_descuento_dealer'];?>">

                                   
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="estado">
											<option value="0"></option>
											<option value="1" <?php if($resultadoD['combo_estado']==1)echo "selected";?>>Activo</option>
											<option value="0" <?php if($resultadoD['combo_estado']==0)echo "selected";?>>Inactivo</option>
                                    	</select>
                                    </div>
                               </div>

							   <div class="control-group">
									<label class="control-label">Descuento Máximo en Cotización</label>
									<div class="controls">
										<input type="text" class="span2" name="descuentoMax" value="<?=$resultadoD['combo_descuento_maximo'];?>">
									</div>
								</div>
 
								<div class="form-actions">
									
									<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15 or $_SESSION["id"]==17 or $_SESSION["id"]==13 or $_SESSION["id"]==57){?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
									
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>
                              
                                

						</div>
					</div>
				</div>
			</div>
			
			
			<div class="row-fluid">
				<div class="span12">
					
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
                                <th>Precio original</th>
                                <th>Precio lista</th>
                                <th>SUBTOTAL</th>
                                <th style="color: darkblue; border-left: solid;">Precio dealer</th>
                                <th style="color: darkblue;">SUBTOTAL DEALER</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$editarCantidad = 'disabled';
							if ($datosUsuarioActual['usr_tipo'] == 1 ) {
								$editarCantidad = '';
							}

							$no = 1;
							$totalDealer=0;
							$total=0;
							$totalCantidad=0;
							$productos = $conexionBdPrincipal->query("SELECT * FROM productos 
							INNER JOIN productos_categorias ON catp_id=prod_categoria
							INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$_GET["id"]."'
							ORDER BY copp_id");
							while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){

								$utilidadDealer = $prod['prod_descuento2'] / 100;
								$precioDealer = $prod['prod_costo'] + ($prod['prod_costo'] * $utilidadDealer);
								$subtotalDealer = ($precioDealer * $prod['copp_cantidad']);
								$totalDealer +=$subtotalDealer;

									
								$subtotal = ($prod['prod_precio'] * $prod['copp_cantidad']);
								$total +=$subtotal;
								
								$totalCantidad += $prod['copp_cantidad'];

								
							?>
							<tr>
								<td><?=$no;?></td>
                                <td>
									<a href="sql.php?get=55&idItem=<?=$prod['copp_id'];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<a href="productos-editar.php?id=<?=$prod['prod_id'];?>" target="_blank"><?=$prod['prod_id']." - ".$prod['prod_nombre'];?></a>
								</td>
                                <td><input type="number" title="copp_cantidad" name="<?=$prod['copp_id'];?>" value="<?=$prod['copp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;" <?=$editarCantidad;?>></td>
                                <td>$<?=number_format($prod['copp_precio'],0,",",".");?></td>
                                <td>$<?=number_format($prod['prod_precio'],0,",",".");?></td>
								<td>$<?=number_format($subtotal,0,",",".");?></td>
								<td style="color: darkblue; border-left: solid;">$<?=number_format($precioDealer,0,",",".");?></td>
								<td style="color: darkblue;">$<?=number_format($subtotalDealer,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							$descuento = ($total * ($resultadoD['combo_descuento']/100));
							$totalNeto = ($total - $descuento);

							$descuentoDealer = ($totalDealer * ($resultadoD['combo_descuento']/100));
							$totalNetoDealer = ($totalDealer - $descuentoDealer);

							//$descuentoDealer = ($total * ($resultadoD['combo_descuento_dealer']/100));
							//$totalNetoDealer = ($total - $descuentoDealer);	
							?>
	
							</tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="5">SUBTOTAL</td>
									<td>$<?=number_format($total,0,",",".");?></td>

									<td style="text-align: right; color: darkblue; border-left: solid;">SUBTOTAL DEALER</td>
									<td style="color: darkblue;">$<?=number_format($totalDealer,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="5">DESCUENTO</td>
									<td>$<?=number_format($descuento,0,",",".");?></td>

									<td style="text-align: right; color: darkblue; border-left: solid;">DESCUENTO DEALER</td>
									<td style="color: darkblue;">$<?=number_format($descuentoDealer,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="5">TOTAL NETO</td>
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

							</form>
							
						</div>
					</div>
				</div>
			</div>			
            

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>