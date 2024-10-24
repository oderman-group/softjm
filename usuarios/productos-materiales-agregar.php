<?php 
include("sesion.php");

$idPagina = 69;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consultaProducto=mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_soptec WHERE prod_id='".$_GET["pdto"]."'");
$producto = mysqli_fetch_array($consultaProducto, MYSQLI_BOTH);
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
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><b><?=$producto['prod_nombre'];?></b> / <?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="productos.php">Porudctos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li><a href="productos-materiales.php?pdto=<?=$_GET["pdto"];?>"><b><?=$producto['prod_nombre'];?></b> / Materiales</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/productos-materiales-guardar.php" enctype="multipart/form-data">
                            <input type="hidden" name="pdto" value="<?=$_GET["pdto"];?>">
                            
                            	<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Tipo de material</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipo" onChange="material(this)">
											<option value=""></option>
                                            <option value="1">Documento</option>
                                            <option value="2">Video</option>
                                            <option value="3">Software</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <script type="text/javascript">
							    function material(valor){
									if(valor.value==1 || valor.value==3){
										document.getElementById("documento").style.display="block";
										document.getElementById("video").style.display="none";
									}else{
										document.getElementById("documento").style.display="none";
										document.getElementById("video").style.display="block";
									}
								}
                               </script>
                               
                                <div class="control-group" id="documento" style="display:none;">
									<label class="control-label">Escoja Documento/Software</label>
									<div class="controls">
										<input type="file" multiple class="span4" name="documento[]">
									</div>
                                    <span style="color:#FF0000;">Recuerde que el documento no debe pesar más de 1.5MB. Si va a montar un software ejecutable, debe montarlo en una carpeta comprimida(ZIP).</span>
								</div>
                                
                                <div class="control-group" id="video" style="display:none;">
									<label class="control-label">Código de Video (YouTube)</label>
									<div class="controls">
										<input type="text" class="span4" name="video" placeholder="kKkhESMpjYw&t=13s"> <span style="color:#C00;">Mira la imagen de ejemplo.</span>
									</div>
                                    <p style="margin-top:10px;"><img src="files/CodigoYoutube.png"></p>
								</div>
                                
                                
                               
                               <div class="control-group">
									<label class="control-label">Visible</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="activo">
											<option value=""></option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                    	</select>
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
            

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>