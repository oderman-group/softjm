<?php include("sesion.php");?>
<?php
$idPagina = 132;
$paginaActual['pag_nombre'] = "Editar factura compra extranjera #".$_GET["id"];
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>

<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM facturas 
INNER JOIN proveedores ON prov_id=factura_proveedor
WHERE factura_id='".$_GET["id"]."'",$conexion));
?>


<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->

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
<script type="text/javascript">
    /*====TAGS INPUT====*/
    $(function () {
        $('#tags_1').tagsInput({
            width: 'auto'
        });
        $('#tags_2').tagsInput({
            width: 'auto',
            onChange: function (elem, elem_tags) {
                var languages = ['php', 'ruby', 'javascript'];
                $('.tag', elem_tags).each(function () {
                    if ($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0) $(this).css('background-color', 'yellow');
                });
            }
        });
    });
    /*====Select Box====*/
    $(function () {
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
            allow_single_deselect: true
        });
    });
    /*====Color Picker====*/
    $(function () {
        $('.colorpicker').colorpicker({
            format: 'hex'
        });
        $('.pick-color').colorpicker();
    });
    /*====DATE Time Picker====*/
    $(function () {
        $('#datetimepicker1').datetimepicker({
            language: 'pt-BR'
        });
    });
    $(function () {
        $('#datetimepicker3').datetimepicker({
            pickDate: false
        });
    });
    $(function () {
        $('#datetimepicker4').datetimepicker({
            pickTime: false
        });
    });
    /*DATE RANGE PICKER*/
    $(function () {
        $('#reportrange').daterangepicker({
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({
                    days: -6
                }), 'today'],
                'Last 30 Days': [Date.today().add({
                    days: -29
                }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: 'left',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: '01/01/2012',
            maxDate: '12/31/2013',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },
        function (start, end) {
            $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
    });
    $(function () {
        $('#reservation').daterangepicker();
    });
</script>
<?php include("texto-editor.php");?>




	<script type="text/javascript">
	  function productos(enviada){
		  var idRemision = <?=$_GET['id'];?>;

		  var campo = enviada.title;
		  var producto = enviada.name;
		  var proceso = 9;
		  var valor = enviada.value;
		  $('#resp').empty().hide().html("Esperando...").show(1);
			datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&idRemision="+(idRemision);
				   $.ajax({
					   type: "POST",
					   url: "ajax-productos.php",
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
	<?php include("encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
						
                        <ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="facturas.php">Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <?php include("notificaciones.php");?>
			
			
			<p>
				<a href="fce-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<a href="#reportes/formato-remision-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
				
				<!--
				<a href="sql.php?get=44&id=<?=$_GET["id"];?>" class="btn btn-warning" onClick="if(!confirm('Desea Enviar este mensaje al correo del contacto?')){return false;}"><i class="icon-envelope"></i> Enviar por correo</a>
				-->
			</p>	
								
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="85">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
							<input type="hidden" name="monedaActual" value="<?=$resultadoD['factura_moneda'];?>">
							<input type="hidden" name="fce" value="<?=$resultadoD['factura_extranjera'];?>">
							<input type="hidden" name="preferencia" value="<?=$resultadoD['factura_preferencia'];?>">
                            	   
								
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									
								</div>
								

								<div class="control-group">
									<label class="control-label">Concepto</label>
									<div class="controls">
										<input type="text" class="span8" name="concepto" value="<?=$resultadoD['factura_concepto'];?>" required>
									</div>
								</div>
								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
											 <option value=""></option>
											 <?php
											 $conOp = mysql_query("SELECT * FROM proveedores",$conexion);
											 while($resOp = mysql_fetch_array($conOp)){
											 ?>
												 <option value="<?=$resOp[0];?>" <?php if($resultadoD['factura_proveedor']==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
									
											<a href="proveedores-editar.php?id=<?=$resultadoD['factura_proveedor'];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
									
									
								</div>
 
								
                               
								<div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=$resultadoD['factura_fecha_propuesta'];?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=$resultadoD['factura_fecha_vencimiento'];?>">
									</div>
								</div>
								
								
								
								<div class="control-group">
									<label class="control-label">Forma de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['factura_forma_pago']==1)echo "selected";?>>Contado</option>
                                            <option value="2" <?php if($resultadoD['factura_forma_pago']==2)echo "selected";?>>Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['factura_moneda']==1)echo "selected";?>>USD</option>
                                            <option value="2" <?php if($resultadoD['factura_moneda']==2)echo "selected";?>>EURO</option>
                                    	</select>
                                    </div>
                               </div>


							   <div class="control-group">
									<label class="control-label">TRM USD</label>
									<div class="controls">
										<input type="text" class="span2" name="trmUsd" value="<?=$resultadoD['factura_trm_usd'];?>">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">TRM EURO</label>
									<div class="controls">
										<input type="text" class="span2" name="trmEuro" value="<?=$resultadoD['factura_trm_euro'];?>">
									</div>
								</div>

								<hr>

								<div class="control-group">
									<label class="control-label">FLETE TRM USD</label>
									<div class="controls">
										<input type="text" class="span2" name="trmUsdFlete" value="<?=$resultadoD['factura_trm_usd_flete'];?>">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">FLETE TRM EURO</label>
									<div class="controls">
										<input type="text" class="span2" name="trmEuroFlete" value="<?=$resultadoD['factura_trm_euro_flete'];?>">
									</div>
								</div>	
								
								
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php

												$conOp = mysql_query("SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria 
												WHERE prod_id=prod_id
												ORDER BY prod_nombre",$conexion);
												while($resOp = mysql_fetch_array($conOp)){
													$productoN = mysql_num_rows(mysql_query("SELECT * FROM cotizacion_productos 
													WHERE czpp_producto='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['factura_id']."' AND czpp_tipo=4",$conexion));
												?>
													<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_id'].". ".$resOp['prod_referencia']." ".$resOp['prod_nombre']." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								
								
									

							   	
								

								<div class="control-group">
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas"><?=$resultadoD['factura_observaciones'];?></textarea>
										</div>
									</div>

								
                               <div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									
								</div>
								
						</div>
					</div>
				</div>
			</div>
			
			<!-- LISTADO DE LO QUE SE ESTÁ COTIZANDO -->	
			<div class="row-fluid">
				<div class="span12">
					
					<span id="resp"></span>
					
					<div class="content-widgets light-gray" id="productos">
						<div class="widget-head orange">
							<h3>PRODUCTOS</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
								<th>Orden</th>
                                <th>Artículo</th>
                                <th>Cant.</th> 
                                <th>Valor Unitario (<?=$monedasExt[$resultadoD['factura_moneda']];?>)</th>
								<th>Valor Unitario (COP)</th>
                                <th>SUBTOTAL (<?=$monedasExt[$resultadoD['factura_moneda']];?>)</th>
								<th>SUBTOTAL (COP)</th>
							</tr>
							</thead>
							<tbody>
								
							
								
							<!-- PRODUCTOS -->	
                            <?php
							$no = 1;
							$productos = mysql_query("SELECT * FROM productos 
							INNER JOIN productos_categorias ON catp_id=prod_categoria
							INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='".$_GET["id"]."' AND czpp_tipo=4
							ORDER BY prod_no_inventariable DESC, czpp_orden",$conexion);
							while($prod = mysql_fetch_array($productos)){
								$dcto = 0;
								$valorTotal = 0;

								$valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

								if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
									$dcto = ($valorTotal * ($prod['czpp_descuento']/100));
									$totalDescuento += $dcto;	
								}

								

								$subtotal +=$valorTotal;
								
								
								$totalCantidad += $prod['czpp_cantidad'];

								$colorPNI = '';

								//Moneda a multiplicar
								if($resultadoD['factura_moneda'] == 1){
									if ($prod['prod_no_inventariable'] == 1) {
										$colorPNI = 'yellow';
										$trmMult = $resultadoD['factura_trm_usd_flete'];
									}else{
										$trmMult = $resultadoD['factura_trm_usd'];
									}
									
								}else{
									
									if ($prod['prod_no_inventariable'] == 1) {
										$colorPNI = 'yellow';
										$trmMult = $resultadoD['factura_trm_euro_flete'];
									}else{
										$trmMult = $resultadoD['factura_trm_euro'];
									}
								}
							?>
							<tr>
								<td><?=$no;?></td>
								<td><input type="number" title="czpp_orden" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_orden'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td style="background-color: <?= $colorPNI; ?>;">
									<a href="sql.php?get=43&idItem=<?=$prod['czpp_id'];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<a href="productos-editar.php?id=<?=$prod['prod_id'];?>" target="_blank"><?=$prod['prod_nombre'];?></a>
								</td>
                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;"></td>

								<td>$<?=number_format(($prod['czpp_valor']*$trmMult),0,",",".");?></td>
                                
								
                                <td><?=$simbolosMonedas[$resultadoD['factura_moneda']];?><?=number_format($valorTotal,0,",",".");?></td>

								<td><?=$simbolosMonedas[$resultadoD['factura_moneda']];?><?=number_format(($valorTotal*$trmMult),0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							?>	
								
							</tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="6">TOTAL + FLETE (<?=$monedasExt[$resultadoD['factura_moneda']];?>)</td>
									<td><?=number_format($subtotal,0,",",".");?></td>
									<td>&nbsp;</td>
								</tr>
								<!--
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="6">TOTAL (COP)</td>
									<td><?=number_format(($subtotal*$trmMult),0,",",".");?></td>
									<td>&nbsp;</td>
								</tr>-->
							</tfoot>	
								
							</table>
							
							
								<div class="form-actions">
									
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									
									
										
									<a href="#reportes/formato-remision-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php include("pie.php");?>
</div>
</body>
</html>