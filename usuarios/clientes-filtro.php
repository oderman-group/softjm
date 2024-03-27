<?php 
include("sesion.php");

$idPagina = 103;

include("includes/verificar-paginas.php");
include("includes/head.php");
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
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li><a href="clientes-seguimiento.php?idTK=<?=$_GET["idTK"];?>&cte=<?=$_GET["cte"];?>">Seguimiento de clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="get" action="reportes/clientes.php" target="_blank">  
                               
								<div class="control-group">
									<label class="control-label">Departamento</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="departamento">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdAdmin,"SELECT * FROM localidad_departamentos ORDER BY dep_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['dep_id'];?>"><?=$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Ciudad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdAdmin,"SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>"><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Grupos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="grupos">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM dealer WHERE deal_id_empresa='".$idEmpresa."' ORDER BY deal_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['deal_id'];?>"><?=$resOp['deal_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Categoría</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="categoria">
											<option value="">Todos</option>
                                            <option value="1">Prospecto</option>
                                            <option value="2">Cliente</option>
                                    	</select>
                                    </div>
                               </div>
                               
								
								<div class="control-group">
									<label class="control-label">Proceso Mercadeo</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="proceso">
											<option value="">Todos</option>
                                            <option value="1">No Contestó</option>
                                            <option value="2">Número equivocado</option>
											<option value="3">Envío portafolio</option>
											<option value="4">Inicio negocio</option>
											<option value="5">Actualizados</option>
											<option value="6">Papelera</option>
                                    	</select>
                                    </div>
                               </div>
								
							
								<div class="control-group">
									<label class="control-label">Mostrar los de la papelera?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="papelera">
                                            <option value="1">SI</option>
                                            <option value="2">NO</option>
                                    	</select>
                                    </div>
                               </div>
								
								<hr>
								<div class="control-group">
									<label class="control-label">Usuario (último proceso de mercadeo)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="usuarioMercadeo">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Rango Desde
									     <button class="tooltipp">Fecha de inicio.</button>
							             <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="date" class="span3" name="desdeMercadeo">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Rango Hasta
									    <button class="tooltipp">Fecha de caducidad.</button>
							             <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="date" class="span3" name="hastaMercadeo">
									</div>
								</div>
                               
								<hr>
                               <div class="control-group">
									<label class="control-label">Registrados Desde
									<button class="tooltipp">Fecha de inicio.</button>
							             <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="date" class="span3" name="desde">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Registrados Hasta
									<button class="tooltipp">Fecha de caducidad.</button>
							             <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="date" class="span3" name="hasta">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Ordenar por</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="orden">
											<option value="cli_id"></option>
											<option value="cli_usuario">NIT</option>
                                            <option value="cli_nombre">Nombre</option>
                                            <option value="cli_ciudad">Ciudad</option>
                                            <option value="cli_email">Email</option>
                                            <option value="cli_categoria">Categoría</option>
											<option value="cli_estado_mercadeo">Proceso de mercadeo</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Forma de orden</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaOrden">
											<option value="ASC"></option>
                                            <option value="ASC">Ascendente</option>
                                            <option value="DESC">Descendente</option>
                                    	</select>
                                    </div>
                               </div>
								
                               <hr>
								<div class="control-group">
									<label class="control-label">Mostrar sucursales</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="sucursales">
											<option value=""></option>
                                            <option value="1">SI</option>
                                            <option value="2">NO</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Mostrar contactos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="contacto">
											<option value=""></option>
                                            <option value="1">SI</option>
                                            <option value="2">NO</option>
                                    	</select>
                                    </div>
                               </div>
								<hr>
								<div class="control-group">
									<label class="control-label">¿Qué listado necesitas?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="listado">
											<option value="3">Todos</option>
                                            <option value="1">Clientes Antiguos</option>
                                            <option value="2">Clientes Nuevos</option>
                                    	</select>
                                    </div>
                               </div>
                              
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
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