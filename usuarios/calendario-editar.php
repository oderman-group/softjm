<?php include("sesion.php");?>
<?php
$idPagina = 117;
$paginaActual['pag_nombre'] = "Editar evento";
?>
<?php include("includes/verificar-paginas.php");?>
<?php
include("includes/head.php");
$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM agenda WHERE age_id='".$_GET["id"]."' AND age_id_empresa={$_SESSION['dataAdicional']['id_empresa']}");
$resultadoD = mysqli_fetch_array($consulta);
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
						<li><a href="calendario.php">Mi calendario</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			
			<p><a href="calendario-evento-eliminar.php?get=37&id=<?=$_GET["id"];?>" class="btn btn-danger" onClick="if(!confirm('Desea eliminar el registro?')){return false;}"><i class="icon-trash"></i> Eliminar</a></p>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/calendario-actualizar.php">
                            <input type="hidden" name="idSql" value="53">
							<input type="hidden" name="id" value="<?=$_GET["id"];?>">
                               
                               
                               <div class="control-group">
									<label class="control-label">Asunto</label>
									<div class="controls">
										<input type="text" class="span10" name="evento" required value="<?=$resultadoD["age_evento"];?>">
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span4" name="fecha" required value="<?=$resultadoD["age_fecha"];?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Hora inicio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="inicio">
											<option value=""></option>
                                            <?php
											$hora = 1;
											$minuto = 0;
											$i = 0;
											while($hora<24){
												if($i%2==0){
													$minuto = 0;
													if($i>0){$hora++;}
												}else{
													$minuto = 30;
												}
											?>
                                            	<option value="<?=$i;?>" <?php if($i==$resultadoD["age_inicio"]){echo "selected";}?>><?=$hora.":".$minuto;?></option>
                                            <?php
												$i++;
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Hora fin</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="fin">
											<option value=""></option>
                                            <?php
											$hora = 1;
											$minuto = 0;
											$i=0;
											while($hora<24){
												if($i%2==0){
													$minuto = 0;
													if($i>0){$hora++;}
												}else{
													$minuto = 30;
												}
											?>
                                            	<option value="<?=$i;?>" <?php if($i==$resultadoD["age_fin"]){echo "selected";}?>><?=$hora.":".$minuto;?></option>
                                            <?php
												$i++;
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Lugar</label>
									<div class="controls">
										<input type="text" class="span10" name="lugar" value="<?=$resultadoD["age_lugar"];?>">
									</div>
								</div>
                                
								<div class="control-group">
									<label class="control-label">Notas</label>
									<div class="controls">
										<input type="text" class="span12" name="notas" value="<?=$resultadoD["age_notas"];?>">
									</div>
								</div>

                                <div class="control-group">
									<label class="control-label">Cliente invitado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value="0"></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$resultadoD["age_cliente"]."' AND cli_id_empresa={$_SESSION['dataAdicional']['id_empresa']}");
											while($resOp = mysqli_fetch_array($conOp)){
												if($datosUsuarioActual[3]!=1){
													$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'");
													$numZ = mysqli_num_rows($consultaZonas);
													
													$consultaClientes=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$resOp['cli_id']."'");
													$numCliente = mysqli_num_rows($consultaClientes);
									
													if($numZ == 0 and $numCliente == 0) continue;
												}
											?>
                                            	<option value="<?=$resOp[0];?>" selected><?=$resOp[1]." (".$resOp['cli_email'].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
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