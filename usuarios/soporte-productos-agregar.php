<?php include("sesion.php");?>
<?php
$idPagina = 98;
$paginaActual['pag_nombre'] = "Agregar soporte productos";
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
						
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
                        <li><a href="soporte-productos.php">Soporte productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/soporte-productos-guardar.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="43">

                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" style="font-weight:bold;">
									</div>
								</div>
                                
                                <div class="control-group">
                                        <label class="control-label">Nivel</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="nivel">
                                                <option value=""></option>
                                                <option value="1">Producto</option>
                                                <option value="2">Super categoria</option>
                                                <option value="3">Categoría</option>
                                                <option value="4">Tema</option>
                                                <option value="5">Sub Tema</option>
                                            </select>
                                        </div>
                                   </div>
                                
                                <div class="control-group">
									<label class="control-label">Padre</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="padre">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM soporte_productos",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1]." (Niv. ".$resOp['sop_nivel'].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                               <div class="control-group">
									<label class="control-label">Imagen</label>
									<div class="controls">
										<input type="file" class="span4" name="imagen">
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Video</label>
									<div class="controls">
										<input type="text" class="span4" name="video">
									</div>
								</div>
                               
                                <div class="control-group">
									<label class="control-label">Descripcion</label>
									<div class="controls">
										<textarea rows="15" cols="80" style="width: 80%" class="tinymce-simple" name="descripcion"></textarea>
									</div>
								</div>
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-money"></i> Guardar cambios</button>
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