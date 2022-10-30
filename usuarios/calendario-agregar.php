<?php include("sesion.php");?>
<?php
$idPagina = 116;
$paginaActual['pag_nombre'] = "Agregar evento";
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
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="52">  
                               
                               
                               <div class="control-group">
									<label class="control-label">Asunto</label>
									<div class="controls">
										<input type="text" class="span10" name="evento" required>
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span4" name="fecha" required>
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
                                            	<option value="<?=$i;?>"><?=$hora.":".$minuto;?></option>
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
                                            	<option value="<?=$i;?>"><?=$hora.":".$minuto;?></option>
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
										<input type="text" class="span10" name="lugar">
									</div>
								</div>
                                
								<div class="control-group">
									<label class="control-label">Notas</label>
									<div class="controls">
										<input type="text" class="span12" name="notas">
									</div>
								</div>


                                <div class="control-group">
									<label class="control-label">Invitar cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value="0"></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
												if($datosUsuarioActual[3]!=1){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'",$conexion));
													
													$numCliente = mysql_num_rows(mysql_query("SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$resOp['cli_id']."'",$conexion));
									
													if($numZ == 0 and $numCliente == 0) continue;
												}
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1]." (".$resOp['cli_email'].")";?></option>
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