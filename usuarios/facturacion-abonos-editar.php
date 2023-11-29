<?php include("sesion.php");?>
<?php
$idPagina = 94;
$paginaActual['pag_nombre'] = "Editar abonos a Factura";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>

<?php
$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal," SELECT * FROM facturacion_abonos 
INNER JOIN usuarios ON usr_id=fpab_responsable_registro
WHERE fpab_id='".$_GET["id"]."'"));
$usuarioMod = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$resultadoD[9]."'"));
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
						<li><a href="facturacion-abonos.php?fact=<?=$_GET["fact"];?>">Abonos a Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p>
                <a href="facturacion-abonos-agregar.php?fact=<?=$_GET["fact"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/abonos-facturas-actualizar.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="42">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <input type="hidden" name="fact" value="<?=$_GET["fact"];?>">
                            	   
                                
                                <div class="control-group">
									<label class="control-label">Fecha de la factura</label>
									<div class="controls">
										<input type="date" class="span3" name="fecha" value="<?=$resultadoD['fpab_fecha_abono'];?>">
									</div>
								</div>
                                
                                
                                <div class="control-group">
									<label class="control-label">Valor ($)</label>
									<div class="controls">
										<input type="number" class="span3" name="valor" value="<?=$resultadoD['fpab_valor'];?>">
                                        <span style="color:#F00;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
                                
                                
                                <div class="control-group">
									<label class="control-label">Medio de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="medio">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['fpab_medio_pago']==1){echo "selected";}?>>Consignación</option>
                                            <option value="2" <?php if($resultadoD['fpab_medio_pago']==2){echo "selected";}?>>Transferencia</option>
                                            <option value="3" <?php if($resultadoD['fpab_medio_pago']==3){echo "selected";}?>>P. Web</option>
                                            <option value="4" <?php if($resultadoD['fpab_medio_pago']==4){echo "selected";}?>>Efectivo</option>
                                            <option value="5" <?php if($resultadoD['fpab_medio_pago']==5){echo "selected";}?>>Cheque</option>
                                            <option value="6" <?php if($resultadoD['fpab_medio_pago']==6){echo "selected";}?>>Efecty</option>
                                            <option value="7" <?php if($resultadoD['fpab_medio_pago']==7){echo "selected";}?>>Gana</option>
                                            <option value="8" <?php if($resultadoD['fpab_medio_pago']==8){echo "selected";}?>>Otro medio</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <a href="files/comprobantes/<?=$resultadoD['fpab_comprobante'];?>" target="_blank"><?=$resultadoD['fpab_comprobante'];?></a>
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
                                
                               
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="observaciones"><?=$resultadoD['fpab_observaciones'];?></textarea>
									</div>
								</div>
                                <hr>
                                <div class="control-group">
									<label class="control-label">Fecha del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaR" value="<?=$resultadoD['fpab_fecha_registro'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="responsable" value="<?=$resultadoD['usr_nombre'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha última modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaUltima" value="<?=$resultadoD['fpab_fecha_ultima_modificacion'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable de la modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="responsableMod" value="<?=$usuarioMod['usr_nombre'];?>" readonly>
									</div>
								</div>
                               
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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