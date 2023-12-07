<?php 
include("sesion.php");
$idPagina = 113;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM proyectos_tareas WHERE ptar_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
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
						<li><a href="proyectos.php">Proyectos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li><a href="proyectos-tareas.php?proy=<?=$_GET["proy"];?>">Tareas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_update/proyectos-tareas-actualizar.php">

							<input type="hidden" name="ptar_id_proyecto" value="<?=$_GET["proy"];?>">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">
								
								<div class="control-group">
									<label class="control-label">Titulo de la tarea</label>
									<div class="controls">
										<input type="text" class="span10" name="ptar_titulo" value="<?=$resultadoD["ptar_titulo"];?>" required>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<textarea rows="8" cols="80" style="width: 80%" class="tinymce-simple" name="ptar_descripcion"><?=$resultadoD["ptar_descripcion"];?></textarea>
									</div>
								</div>
								
                               <div class="control-group">
									<label class="control-label">Responsable principal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ptar_responsable">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_bloqueado=0");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD["ptar_responsable"]==$resOp[0]){echo "selected";}?>><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
   
                               <div class="control-group">
									<label class="control-label">Fecha inicio</label>
									<div class="controls">
										<input type="date" class="span3" name="ptar_inicio" value="<?=$resultadoD["ptar_inicio"];?>" required>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha de entrega (Ideal)</label>
									<div class="controls">
										<input type="date" class="span3" name="ptar_fin" value="<?=$resultadoD["ptar_fin"];?>" required>
									</div>
								</div>
                                
                               
                               <div class="control-group">
									<label class="control-label">Avance</label>
									<div class="controls">
										<input type="text" class="span3" value="<?=$resultadoD["ptar_avance"];?>" name="ptar_avance">
									</div>
								</div>
                               
                               

                              
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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