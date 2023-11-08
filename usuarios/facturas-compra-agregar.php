<?php include("sesion.php");?>
<?php
$idPagina = 129;
$paginaActual['pag_nombre'] = "Agregar factura de compra";
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
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
						<li><a href="facturas.php">Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/facturas-compra-guardar.php">
                            <input type="hidden" name="idSql" value="84">
                            <input type="hidden" name="fce" value="0">  

							<div class="control-group">
									<label class="control-label">Concepto</label>
									<div class="controls">
										<input type="text" class="span8" name="concepto" required>
									</div>
								</div>
							
								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
											 <option value=""></option>
											 <?php
											 $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
											 while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											 ?>
												 <option value="<?=$resOp[0];?>"><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label">Forma de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago">
											<option value=""></option>
                                            <option value="1" <?php if($clienteInfo['cli_credito']!=1)echo "selected";?>>Contado</option>
                                            <option value="2" <?php if($clienteInfo['cli_credito']==1)echo "selected";?>>Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" selected>COP</option>
                                            <option value="2">USD</option>
                                    	</select>
                                    </div>
							   </div>

                               <div class="control-group">
									<label class="control-label">Qué es esto?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="preferencia">
											<option value="0"></option>
                                            <option value="1">Costo de nacionalización</option>
                                            <option value="2">Fletes</option>
                                            <option value="3" selected>N/A</option>
                                    	</select>
                                    </div>
							   </div>


								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php
												$filtroProd = '';
												if(is_numeric($_GET["prov"])){ $filtroProd .=" AND prod_proveedor='".$_GET["prov"]."'";}

												$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria
												WHERE prod_id=prod_id AND prod_id_empresa='".$idEmpresa."' $filtroProd
												ORDER BY prod_nombre
												");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_nombre']." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>	
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-arrow-right"></i> Continuar</button>
								</div>

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