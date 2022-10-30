<?php include("sesion.php");?>
<?php
$idPagina = 4;
$paginaActual['pag_nombre'] = "Enviar mensaje";
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
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="32">
							<fieldset class="default">
								<legend>Grupo de clientes</legend>
                                    <span style="color:#F00; font-weight:bold;">ESCOJA LAS OPCIONES PARA UN SOLO GRUPO.</span>
                                    
                                    <div class="control-group">
                                        <label class="control-label">Zonas</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="zonas[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysql_query("SELECT * FROM localidad_departamentos",$conexion);
                                                while($resOp = mysql_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp['dep_id'];?>"><?=$resOp['dep_nombre'];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
                                   
                                   <div class="control-group">
                                        <label class="control-label">Tipos</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="tipos[]">
                                                <option value=""></option>
                                                <option value="1">Prospectos</option>
                                                <option value="2">Clientes</option>
                                            </select>
                                        </div>
                                   </div>
                                   
                                   <div class="control-group">
                                        <label class="control-label">Grupos</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="grupos[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysql_query("SELECT * FROM dealer",$conexion);
                                                while($resOp = mysql_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
								
									<div class="control-group">
                                        <label class="control-label">Clientes</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span10" multiple tabindex="2" name="clientes[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysql_query("SELECT * FROM clientes",$conexion);
                                                while($resOp = mysql_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>"><?=$resOp[1]."(".$resOp['cli_email'].")";?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
                                   
                               </fieldset>
                               
                               <div class="control-group">
									<label class="control-label">Asunto</label>
									<div class="controls">
										<input type="text" class="span8" name="asunto">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Adjuntar imagén del boletín</label>
									<div class="controls">
										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input span4">
													<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
												</div>
												<span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
												<input type="file" name="boletin"/>
												</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
											</div>
										</div>
									</div>
								</div>
                               
                                <div class="control-group">
									<label class="control-label">Mensaje</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="mensaje"></textarea>
									</div>
								</div>
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar Mensaje</button>
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