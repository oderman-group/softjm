<?php include("sesion.php");?>
<?php
$idPagina = 99;
$paginaActual['pag_nombre'] = "Editar soporte productos";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM soporte_productos WHERE sop_id='".$_GET["id"]."'",$conexion));
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
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="44">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">

                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" style="font-weight:bold;" value="<?=$resultadoD['sop_nombre'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
                                        <label class="control-label">Nivel</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="nivel">
                                                <option value=""></option>
                                                <?php
												$array = array("","Producto","Super categoria","Categoría","Tema","Sub Tema");
												$long = count($array);
												$i=1;
												while($i<=$long){
													if($i==$resultadoD['sop_nivel'])
														echo '<option value="'.$i.'" selected>'.$array[$i].'</option>';
													else
														echo '<option value="'.$i.'">'.$array[$i].'</option>';	
													$i++;
												}
												?>
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
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['sop_padre']==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                                <img src="files/soporte/<?=$resultadoD['sop_imagen'];?>" width="100">
                               <div class="control-group">
									<label class="control-label">Imagen</label>
									<div class="controls">
										<input type="file" class="span4" name="imagen">
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Video</label>
									<div class="controls">
										<input type="text" class="span4" name="video" value="<?=$resultadoD['sop_video'];?>">
									</div>
								</div>
                               
                                <div class="control-group">
									<label class="control-label">Descripcion</label>
									<div class="controls">
										<textarea rows="15" cols="80" style="width: 80%" class="tinymce-simple" name="descripcion"><?=$resultadoD['sop_descripcion'];?></textarea>
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