<?php include("sesion.php");?>
<?php
$idPagina = 96;
$paginaActual['pag_nombre'] = "Agregar factura rápida";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
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
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/texto-editor.php");?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
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
                        <li><a href="facturacion.php">Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="11"> <?php //el codigo 11 no se encontro en el archivo sql ?> 
                            <input type="hidden" name="observacion" value="">
                            <input type="hidden" name="influyente" value="0">
                            <input type="hidden" name="descuento" value="0">
                            <input type="hidden" name="numFisica" value="">
                            <input type="hidden" name="fechaFactura" value="<?=date("Y-m-d");?>">
                            <input type="hidden" name="fechaVencimiento" value="<?=date("Y-m-d");?>">
                                
                                <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
												if($datosUsuarioActual[3]!=1){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'",$conexion));
													if($numZ==0) continue;
												}
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if(isset($_GET["cte"]) and $_GET["cte"]!="" and $_GET["cte"]==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" class="span6" name="descripcion">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Valor ($)</label>
									<div class="controls">
										<input type="number" class="span3" name="valor">
                                        <span style="color:#F00;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Tipo de transacción</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="tipo">
											<option value=""></option>
                                            <option value="1">Venta (Ingreso)</option>
                                            <option value="2">Compra (Egreso)</option>
                                    	</select>
                                    </div>
                               </div>
                               
							   <script type="text/javascript">
							   function mostrarMP(datos){
									var valor = datos.value;
									var div = document.getElementById("medioPago");
									if(valor==1){
										div.style.display="block";
									}else{
										div.style.display="none";
									}
								}
							   </script>
                               
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="estado" onChange="mostrarMP(this)">
											<option value=""></option>
                                            <option value="1">Pagada</option>
                                            <option value="2">Por pagar</option>
                                            <option value="3">Anulada</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div id="medioPago" style="display:block;">
                                   <fieldset class="default">
                                   	<legend>Datos del pago</legend>
                                   <h6><span style="color:#F00; font-weight:bold">Escoja un Medio de pago y adjunte el comprobante SOLO SI esta factura fue pagada en su TOTALIDAD. Automáticamente también se creará el abono a esta factura.</span></h6>
                                   <div class="control-group">
                                        <label class="control-label">Medio de pago</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="medio">
                                                <option value=""></option>
                                                <option value="1">Consignación</option>
                                                <option value="2">Transferencia</option>
                                                <option value="3">P. Web</option>
                                                <option value="4">Efectivo</option>
                                                <option value="5">Cheque</option>
                                                <option value="6">Efecty</option>
                                                <option value="7">Gana</option>
                                                <option value="8">Otro medio</option>
                                            </select>
                                        </div>
                                   </div>
                                   <div class="control-group">
									<label class="control-label">Comprobante</label>
									<div class="controls">
										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input span3">
													<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
												</div>
												<span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
												<input type="file" name="archivo"/>
												</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
											</div>
										</div>
									</div>
								</div>
                                </fieldset>
                               </div>
                              
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-money"></i> Generar factura</button>
								</div>
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