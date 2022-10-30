<?php include("sesion.php");?>
<?php
$idPagina = 153;
$paginaActual['pag_nombre'] = "Condicionar productos";
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
<?php include("includes/funciones-js.php");?>

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
						<li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
   
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> Descuento máximo</h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="56">
                            	 
								
								<div class="control-group">
									<label class="control-label">SI</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="campo">
											<option value=""></option>
											<option value="prod_costo">El costo ($)</option>
											<option value="prod_utilidad">La utilidad (%)</option>
                                    	</select>
                                    </div>
                                </div>
								
								<div class="control-group">
									<label class="control-label">ES</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="es">
											<option value=""></option>
											<option value=">">Mayor</option>
											<option value="<">Menor</option>
											<option value="=">Igual</option>
											<option value="!=">Diferente</option>
											<option value=">=">Mayor o igual</option>
											<option value="<=">Menor o igual</option>
                                    	</select>
                                    </div>
                                </div>
								
	
								<div class="control-group">
									<label class="control-label">Valor </label>
									<div class="controls">
										<input type="text" class="span4" name="valor">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Aplicar descuento de (%)</label>
									<div class="controls">
										<input type="text" class="span2" name="dcto">
									</div>
								</div>
								
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Aplicar descuentos</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>
                                

						</div>
					</div>
				</div>
			</div>
				
				
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head green">
							<h3> Comisiones de venta</h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="57">
                            	 
								
								<div class="control-group">
									<label class="control-label">SI</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="campo">
											<option value=""></option>
											<option value="prod_costo">El costo ($)</option>
											<option value="prod_utilidad">La utilidad (%)</option>
                                    	</select>
                                    </div>
                                </div>
								
								<div class="control-group">
									<label class="control-label">ES</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="es">
											<option value=""></option>
											<option value=">">Mayor</option>
											<option value="<">Menor</option>
											<option value="=">Igual</option>
											<option value="!=">Diferente</option>
											<option value=">=">Mayor o igual</option>
											<option value="<=">Menor o igual</option>
                                    	</select>
                                    </div>
                                </div>
								
	
								<div class="control-group">
									<label class="control-label">Valor </label>
									<div class="controls">
										<input type="text" class="span4" name="valor">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Aplicar comision de (%)</label>
									<div class="controls">
										<input type="text" class="span2" name="comision">
									</div>
								</div>
								
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Aplicar comisión</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
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