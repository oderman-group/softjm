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
<?php include("includes/overlay.php");?>
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
						<li><a href="calendario.php">Mi calendario</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<?php
			if( Modulos::validarRol(['118'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) {
			?>
				<p><a href="bd_delete/calendario-evento-eliminar.php?get=37&id=<?=$_GET["id"];?>" class="btn btn-danger" onClick="if(!confirm('Desea eliminar el registro?')){return false;}"><i class="icon-trash"></i> Eliminar</a></p>
			<?php }?>			
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post"  id="frmCalendario" action="bd_update/calendario-actualizar.php">
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
										<input type="time" class="span2" name="inicio" id="inicio" value="<?=$resultadoD["age_inicio"]?>" required>										
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Hora fin</label>
									<div class="controls">
										<input type="time" class="span2" name="fin" id="fin" value="<?=$resultadoD["age_fin"]?>" required>										
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
											<option value="0">Escoja una opción</option>
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

							   <div class="control-group">
									<label class="control-label">Desea notificar al cliente?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="enviarCorreo">
											<option value="">Escoje una opción</option>
											<option value="1">Si</option>
											<option value="0">No</option>
                                    	</select>
                                    </div>
                               </div>

                                <div class="control-group hidden">
									<label class="control-label">id_evento_google</label>
									<div class="controls">
										<input type="text" class="span12" name="id_evento_google" value="<?=$resultadoD["age_id_evento_google"];?>">
									</div>
								</div>
                               

                              
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" id="btnCancelar" class="btn btn-danger">Cancelar</button>
								</div>
                              
                                

						</div>
					</div>
				</div>
			</div>
            <script>
				document.getElementById('frmCalendario').addEventListener('submit', function(e) {
					e.preventDefault(); // evita que el formulario se envíe					
			
					const [h1, m1] = this.inicio.value.split(':').map(Number);
					const [h2, m2] = this.fin.value.split(':').map(Number);
					const minutosInicio = h1 * 60 + m1;
					const minutosFin = h2 * 60 + m2;

					if (minutosFin <= minutosInicio) {
						alert('⚠️ La hora de fin debe ser posterior a la hora de inicio.');
						return; // Detiene el envío
					}
					document.getElementById("overlay").style.display = "flex";
 					// Si la validación pasa, enviar el formulario manualmente
					this.submit();
				});

				document.getElementById('btnCancelar').addEventListener('click', function(e) {
					e.preventDefault(); // evita que el formulario se envíe		
					
					window.location.href = 'calendario.php';
					
				});
			</script>

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>