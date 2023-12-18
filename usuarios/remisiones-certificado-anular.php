<?php 
include("sesion.php");
$idPagina = 239;

include("includes/verificar-paginas.php");
include("includes/head.php");

$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."'"));
?>

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
						<li><a href="remisiones.php">Remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_update/remisiones-certificado-generar-anulacion.php">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">

								<?php if($resultadoD['rem_archivo']!=""){ echo '<p><a href="bd_update/quitar-imagen-remision.php?id='.$resultadoD["rem_id"].'" title="Eliminar">X</a> <img src="files/adjuntos/'.$resultadoD["rem_archivo"].'" width="50"></p>';}?>

								<h4 class="card-title">Datos básicos</h4>
								<div class="control-group">
									<label class="control-label">ID</label>
									<div class="controls">
										<input type="text" class="span8" name="equipo" value="<?=$resultadoD['rem_id'];?>" readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="estado" disabled>
											<option value="1" <?php if($resultadoD['rem_estado']==1){echo "selected";} ?> >Entrada</option>
											<option value="2" <?php if($resultadoD['rem_estado']==2){echo "selected";} ?>>Salida</option>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span8" name="fecha" value="<?=$resultadoD['rem_fecha'];?>" disabled>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Asesor</label>
									<div class="controls">
										<input type="text" class="span8" name="referencia" value="<?=$resultadoD['usr_nombre'];?>" readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Equipo</label>
									<div class="controls">
										<input type="text" class="span8" name="equipo" value="<?=$resultadoD['rem_equipo'];?>" readonly>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Referencia</label>
									<div class="controls">
										<input type="text" class="span8" name="referencia" value="<?=$resultadoD['rem_referencia'];?>" disabled>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Marca</label>
									<div class="controls">
										<input type="text" class="span8" name="marca" value="<?=$resultadoD['rem_marca'];?>" disabled>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Tipo de equipo</label>
									<div class="controls">
										<select  data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tipoEquipo" disabled>
											<option value="">--</option>
											<option value="1" <?php if($resultadoD['rem_tipo_equipo']==1){echo "selected";} ?> >Estación total</option>
											<option value="2" <?php if($resultadoD['rem_tipo_equipo']==2){echo "selected";} ?>>Teodolito</option>
											<option value="3" <?php if($resultadoD['rem_tipo_equipo']==3){echo "selected";} ?>>Nivel</option>
											<option value="4" <?php if($resultadoD['rem_tipo_equipo']==4){echo "selected";} ?>>GPS</option>
											<option value="5" <?php if($resultadoD['rem_tipo_equipo']==5){echo "selected";} ?>>Nivel digital</option>
											<option value="6" <?php if($resultadoD['rem_tipo_equipo']==6){echo "selected";} ?>>Distanciómetro</option>
											<option value="7" <?php if($resultadoD['rem_tipo_equipo']==7){echo "selected";} ?>>Nivel laser</option>
											<option value="8" <?php if($resultadoD['rem_tipo_equipo']==8){echo "selected";} ?>>Semi-estación</option>
											<option value="9" <?php if($resultadoD['rem_tipo_equipo']==9){echo "selected";} ?>>Colector</option>
											<option value="10" <?php if($resultadoD['rem_tipo_equipo']==10){echo "selected";} ?>>Brújula</option>
											<option value="11" <?php if($resultadoD['rem_tipo_equipo']==11){echo "selected";} ?>>Bastón</option>
											<option value="12" <?php if($resultadoD['rem_tipo_equipo']==12){echo "selected";} ?>>Trípode</option>
											<option value="13" <?php if($resultadoD['rem_tipo_equipo']==13){echo "selected";} ?>>Prisma</option>
											<option value="14" <?php if($resultadoD['rem_tipo_equipo']==14){echo "selected";} ?>>Batería</option>
											<option value="15" <?php if($resultadoD['rem_tipo_equipo']==15){echo "selected";} ?>>Radio</option>
											<option value="16" <?php if($resultadoD['rem_tipo_equipo']==16){echo "selected";} ?>>Estuche</option>
											<option value="17" <?php if($resultadoD['rem_tipo_equipo']==17){echo "selected";} ?>>Drone</option>
											<option value="18" <?php if($resultadoD['rem_tipo_equipo']==17){echo "selected";} ?>>MATENIMIENTO GENERAL DRON</option>
										</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Serial</label>
									<div class="controls">
										<input type="text" class="span8" name="serial" value="<?=$resultadoD['rem_serial'];?>" disabled>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Nuevo o usado?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tiposEquipos" disabled>
											<option value="2">--</option>
											<option value="<?= REM_TIPOS_EQUIPOS_NUEVO?>" <?php if($resultadoD['rem_tipos_equipos']==REM_TIPOS_EQUIPOS_NUEVO){echo "selected";} ?>>Nuevo</option>
											<option value="<?= REM_TIPOS_EQUIPOS_USADO?>" <?php if($resultadoD['rem_tipos_equipos']==REM_TIPOS_EQUIPOS_USADO){echo "selected";} ?>>Usado</option>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="control-group">
										<label class="control-label">Motivo de anulación</label>
										<div class="controls">
											<textarea rows="7" style="width: 100%;" class="tinymce-simple" name="motivo" placeholder="Escriba los Motivo de anulación..."></textarea>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i>Generar Anulación</button>
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