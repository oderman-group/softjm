<?php include("sesion.php");

$idPagina = 176;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consuluta=$conexionBdPrincipal->query("SELECT * FROM usuarios 
INNER JOIN usuarios_tipos ON utipo_id=usr_tipo
WHERE usr_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consuluta, MYSQLI_BOTH);
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
						<li><a href="usuarios.php">Usuarios</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <?php include("includes/notificaciones.php");?>
            
			<div class="row-fluid">
				<div class="span12">

					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							
							

							<form class="form-horizontal" method="post" action="bd_update/zonas-usuarios-actualizar.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="92">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">

                            	
                                <div class="control-group">
									<label class="control-label">Usuario</label>
									<div class="controls">
										<?=$resultadoD['usr_login'];?>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<?=$resultadoD['usr_nombre'];?>
									</div>
								</div>

                                <div class="control-group">
                                    <label class="control-label">Asignar todas las zonas</label>
                                    <div class="controls">
                                        <select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="zonasTodas">
                                            <option value="1">SI</option>
                                            <option value="2" selected>NO</option>
                                        </select>
                                    </div>
                                </div>
                                
                               

                                
                               <div class="control-group">
									<label class="control-label">Zona</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="zona[]" multiple>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM zonas");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
                                                $consultaZonasUser=$conexionBdPrincipal->query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$resultadoD['usr_id']."' AND zpu_zona='".$resOp['zon_id']."'");
												$numZ = $consultaZonasUser->num_rows;
											?>
                                            	<option value="<?=$resOp['zon_id'];?>" <?php if($numZ>0){echo "selected";}?>><?=$resOp['zon_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								
								<div class="control-group">
									<label class="control-label">Clientes</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente[]" multiple>
											<option value=""></option>
                                            <?php
                                            
											$conOp = $conexionBdPrincipal->query("SELECT cli_id, cli_nombre FROM clientes  LIMIT 20");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
                                                $consultaCliente=$conexionBdPrincipal->query("SELECT cliu_cliente, cliu_usuario FROM clientes_usuarios WHERE cliu_usuario='".$resultadoD['usr_id']."' AND cliu_cliente='".$resOp[0]."'");
												$numZ = $consultaCliente->num_rows;
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($numZ>0){echo "selected";}?>><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
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