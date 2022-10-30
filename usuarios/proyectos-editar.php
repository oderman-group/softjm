<?php include("sesion.php");?>
<?php
$idPagina = 111;
$paginaActual['pag_nombre'] = "Editar proyecto";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM proyectos 
WHERE proy_id='".$_GET["id"]."'",$conexion));
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
						<li><a href="proyectos.php">Proyectos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p>
				<a href="proyectos-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<a href="proyectos-tareas.php?proy=<?=$_GET["id"];?>" class="btn btn-info"><i class="icon-list"></i> Ver tareas</a>
			</p>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="49">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            	   
                                

                               <div class="control-group">
									<label class="control-label">Titulo del proyecto</label>
									<div class="controls">
										<input type="text" class="span12" name="titulo" value="<?=$resultadoD["proy_titulo"];?>" required>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<textarea rows="10" cols="80" style="width: 80%" class="tinymce-simple" name="descripcion"><?=$resultadoD["proy_descripcion"];?></textarea>
									</div>
								</div>
								
                               <div class="control-group">
									<label class="control-label">Responsable principal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="responsable" onChange="clientes(this)">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM usuarios WHERE usr_bloqueado=0",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD["proy_responsable_principal"]==$resOp[0]){echo "selected";}?> ><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               
                               
                               
                               <div class="control-group">
									<label class="control-label">Fecha inicio</label>
									<div class="controls">
										<input type="date" class="span3" name="inicio" value="<?=$resultadoD["proy_inicio"];?>" required>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha de entrega (Ideal)</label>
									<div class="controls">
										<input type="date" class="span3" name="fin" value="<?=$resultadoD["proy_fin"];?>" required>
									</div>
								</div>
                                
                               
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="estado">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD["proy_estado"]==1){echo "selected";}?> >En espera</option>
                                            <option value="2" <?php if($resultadoD["proy_estado"]==2){echo "selected";}?>>En proceso</option>
                                            <option value="3" <?php if($resultadoD["proy_estado"]==3){echo "selected";}?>>Completado</option>
                                    	</select>
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