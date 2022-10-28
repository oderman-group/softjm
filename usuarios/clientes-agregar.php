<?php include("sesion.php");?>
<?php
$idPagina = 10;
$paginaActual['pag_nombre'] = "Agegar clientes";
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

<script type="text/javascript">
  function clientesVerificar(enviada){
  var usuario = enviada.value;
  var opcion = 1;	  
	  $('#resp').empty().hide().html("esperando...").show(1);
		datos = "usuario="+(usuario)+
				"&opcion="+(opcion);
			   $.ajax({
				   type: "POST",
				   url: "ajax-clientes-verificar.php",
				   data: datos,
				   success: function(data){
				   $('#resp').empty().hide().html(data).show(1);
				   }
			   });

	}
</script>
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
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
                            <input type="hidden" name="idSql" value="5">
                                
                                <fieldset class="default">
                                	<legend>Datos básicos</legend>
									
								<div class="control-group">
									<label class="control-label">Tipo de documento</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoDocumento">
											<option value="1"></option>
											<option value="2">NIT</option>
											<option value="3">Cédula</option>
                                    	</select>
                                    </div>
                               </div>	
									
                                <div class="control-group">
									<label class="control-label">Documento</label>
									<div class="controls">
										<input type="text" class="span4" name="usuario" autocomplete="off" onChange="clientesVerificar(this)">
                                        <span style="color:#F03;">Digite el Documento sin número de verificación.</span>
									</div>
									<span id="resp"></span>
								</div>
									
                                <!--
                                <div class="control-group">
									<label class="control-label">Contraseña (*)</label>
									<div class="controls">
										<input type="password" class="span4" name="clave" autocomplete="off" value="" required>
									</div>
								</div>
								-->
                                
                                <div class="control-group">
									<label class="control-label">Nombre (*)</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" style="text-transform:uppercase;" required>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">SIGLA  (Nombre corto)</label>
									<div class="controls">
										<input type="text" class="span4" name="sigla" style="text-transform:uppercase;">
									</div>
								</div>  
                                
                                <div class="control-group">
									<label class="control-label">Email</label>
									<div class="controls">
										<input type="email" class="span8" name="email" style="text-transform:lowercase;">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Teléfono</label>
									<div class="controls">
										<input type="text" class="span4" name="telefono">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Celular</label>
									<div class="controls">
										<input type="text" class="span4" name="celular" maxlength="10">
                                        <span style="color:#F03;">Este valor sin puntos ni espacios. (3135912073)</span>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Teléfonos complementarios</label>
									<div class="controls">
										<input type="text" class="span4" name="telefonos">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Dirección</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="op1">
											<option value=""></option>
                                            <option value="Calle">Calle</option>
                                            <option value="Carrera">Carrera</option>
                                            <option value="Avenida">Avenida</option>
                                            <option value="Avenida Carrera">Avenida Carrera</option>
                                            <option value="Avenida Calle">Avenida Calle</option>
                                            <option value="Circular">Circular</option>
                                            <option value="Circunvalar">Circunvalar</option>
                                            <option value="Diagonal">Diagonal</option>
                                            <option value="Manzana">Manzana</option>
                                            <option value="Transversal">Transversal</option>
                                            <option value="Vía">Vía</option>
                                    	</select>
                                        
                                        <input type="text" class="span1" name="op2" style="text-transform:uppercase;">
                                        
										<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="op3">
											<option value=""></option>
                                            <option value="Este">Este</option>
                                            <option value="Norte">Norte</option>
                                            <option value="Occidente">Occidente</option>
                                            <option value="Oeste">Oeste</option>
                                            <option value="Oriente">Oriente</option>
                                            <option value="Sur">Sur</option>
                                    	</select>
                                        
                                        <span>#</span>
                                        
                                        <input type="text" class="span1" name="op4" style="text-transform:uppercase;">
                                        
                                        <select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="op5">
											<option value=""></option>
                                            <option value="Este">Este</option>
                                            <option value="Norte">Norte</option>
                                            <option value="Occidente">Occidente</option>
                                            <option value="Oeste">Oeste</option>
                                            <option value="Oriente">Oriente</option>
                                            <option value="Sur">Sur</option>
                                    	</select>
                                        
                                        <span>-</span>
                                        
                                        <input type="text" class="span1" name="op6" style="text-transform:uppercase;">
                                        
                                        <input type="text" class="span2" name="op7" style="text-transform:uppercase;" placeholder="Oficina, Apto...">
									</div>
								</div>   
                               
                               <div class="control-group">
									<label class="control-label">Ciudad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
											<option value="1"></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>"><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               </fieldset>
                               
                               <fieldset class="default">
                                	<legend>Datos comerciales</legend>
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="categoria">
											<option value=""></option>
                                            <option value="1" selected>Prospecto</option>
                                            <option value="2">Cliente</option>
											<option value="3">Dealer</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Nivel</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="nivel">
											<option value=""></option>
                                            <option value="1">Leads (Seguidor o Suscripor)</option>
                                            <option value="2">Interesado (Cotiza o llama)</option>
                                            <option value="3" selected>Prospecto (En proceso)</option>
                                            <option value="4">Cliente A (Compró 1 vez)</option>
                                            <option value="5">Cliente B (Compró 2 veces)</option>
                                            <option value="6">Cliente C (Compró 3 o más veces)</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha que se volvió cliente (En caso de que sea cliente)</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaIngreso">
									</div>
								</div>
                               
								   <!--
                               <div class="control-group">
									<label class="control-label">Referencia de llegada</label>
									<div class="controls">
										<input type="text" class="span4" name="referencia">
									</div>
								</div>
								-->
								   
								 <div class="control-group">
														<label class="control-label">Referencia de llegada</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="referencia">
																<option value=""></option>
																<?php
																for($i=1; $i<=12; $i++){
																	echo '<option value="'.$i.'">'.$referenciaLlegada[$i].'</option>';	
																}
																?>
															</select>
														</div>
												   </div>  
                                
                                <div class="control-group">
									<label class="control-label">Grupos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" multiple tabindex="2" name="grupos[]">
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
                               </fieldset>
                               
                               <div class="control-group">
									<label class="control-label">Crear automáticamente como sucursal principal</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="sucursalP" checked disabled>
                                        <span style="color:#F03;">En caso de que esta sea la sede principal</span>
									</div>
								</div>
                                
                               <div class="control-group">
									<label class="control-label">Crear automáticamente como persona de contacto</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="contactoP" checked>
                                        <span style="color:#F03;">En caso de que este cliente sea el mismo contacto</span>
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
            
            
            <!--
             <div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
                                
                                <div class="control-group">
									<label class="control-label">Alcance</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="alcance"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Primera auditoría</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="pa"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Segunda auditoría</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="sa"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Renovación</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="renovacion"></textarea>
									</div>
								</div>

								<div class="form-actions">
									<a href="clientes.php" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
                              
                            </form>    

						</div>
					</div>
				</div>
			</div>
            -->
            

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>