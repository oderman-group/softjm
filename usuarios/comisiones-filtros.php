<?php include("sesion.php");?>
<?php
$idPagina = 168;
$paginaActual['pag_nombre'] = "Filtro de comisiones";
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
							<form class="form-horizontal" method="post" action="reportes/comisiones.php" target="_blank"> 

                               <div class="control-group">
									<label class="control-label">Usuario responsable del registro</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="responsable">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios");
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Vendedor</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="vendedor">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios");
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               
                              
                               
                               <div class="control-group">
									<label class="control-label">Fecha Desde</label>
									<div class="controls">
										<input type="date" class="span3" name="desdeF">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha Hasta</label>
									<div class="controls">
										<input type="date" class="span3" name="hastaF">
									</div>
								</div>
                               
                              
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
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