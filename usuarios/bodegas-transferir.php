<?php
include("sesion.php");

$idPagina = 147;

include("includes/verificar-paginas.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");
if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"])) {
    echo '<script type="text/javascript">alert("Con la integración Ofima activa, las transferencias de productos solo se gestionan desde Ofima."); window.location.href="bodegas.php";</script>';
    exit();
}
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
                        <li><a href="bodegas.php">Bodegas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_update/bodegas-transferir-actualizar.php" enctype="multipart/form-data">
                                <div class="control-group">
									<label class="control-label">Trasnferir Desde</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="desde">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM bodegas WHERE bod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'",$conexion);
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?><?php if (isset($resOp['bod_habilitada']) && (int) $resOp['bod_habilitada'] === 0) { echo " (deshabilitada)"; } ?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               <div class="control-group">
									<label class="control-label">Trasnferir Hasta</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="hasta">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM bodegas WHERE bod_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND bod_habilitada=1",$conexion);
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-money"></i> Guardas cambios</button>
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