<?php include("sesion.php");?>
<?php
$idPagina = 122;
$paginaActual['pag_nombre'] = "Filtro productos";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
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
						
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" name="formProductos" method="post" action="reportes/productos.php" target="_blank">  
								
                               <div class="control-group">
                                        <label class="control-label">Grupo 1</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="grupos1[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_categorias WHERE catp_grupo=1");
                                                while($resOp = mysqli_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
								
								<div class="control-group">
                                        <label class="control-label">Grupo 2</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="grupos2[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_categorias WHERE catp_grupo=2");
                                                while($resOp = mysqli_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
								
								<div class="control-group">
                                        <label class="control-label">Campos a incluir</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="campos[]">
                                                <option value=""></option>
												<option value="prod_id">ID</option>
												<option value="prod_referencia">Código</option>
												<option value="prod_nombre">Nombre</option>
												<option value="prod_grupo1">Grupo 1</option>
												<option value="prod_categoria">Grupo 2</option>
												<option value="prod_costo">Costo COP</option>
												<option value="prod_costo_dolar">Costo USD</option>
												<option value="prod_utilidad">Utilidad<option>
												<option value="prod_descuento2">Utilidad Dealer</option>
												<option value="prod_descuento_web">Descuento Web</option>
												<option value="prod_precio">Precio lista</option>
                                                <option value="precio_usd">Precio lista USD</option>
												<option value="precioDealer">Precio dealer</option>
												<option value="precioWeb">Precio web</option>
												<option value="prod_existencias">Existencias<option>
												<option value="prod_descuento1">Dcto. Máximo<option>
												
                                            </select>
                                        </div>
                                   </div>
                                
                                <div class="control-group">
									<label class="control-label">Ordenar por</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="orden">
											<option value="prod_id"></option>
                                            <option value="prod_nombre">Nombre</option>
                                            <option value="prod_grupo1">Grupo 1</option>
                                            <option value="prod_categoria">Grupo 2</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Forma de orden</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaOrden">
											<option value="ASC"></option>
                                            <option value="ASC">Ascendente</option>
                                            <option value="DESC">Descendente</option>
                                    	</select>
                                    </div>
                               </div>
                               
                              
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
									<a href="#" onClick="enviar_formulario();" type="submit" class="btn btn-success"><i class="icon-download"></i> Exportar excel</a>
								</div>
								
								<script>
								function enviar_formulario(){
									formProductos.setAttribute('action', 'reportes/productos-excel.php');
								    document.formProductos.submit()
								}
								</script>
                              
                                

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