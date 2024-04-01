<?php 
include("sesion.php");

$idPagina = 124;

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

<script type="text/javascript">
  function clientesVerificar(enviada){
  var usuario = enviada.value;
  var opcion = 2;	  
	  $('#resp').empty().hide().html("esperando...").show(1);
		datos = "usuario="+(usuario)+
				"&opcion="+(opcion);
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-clientes-verificar.php",
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
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="proveedores.php">Proveedores</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/proveedores-guardar.php">
                                
                                <fieldset class="default">
                                	<legend>Datos básicos</legend>
                                <div class="control-group">
									<label class="control-label">DNI
									    <button class="tooltipp">Documento Nacional de Identidad.</button>
										<i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span4" name="dni" autocomplete="off" onChange="clientesVerificar(this)">
                                        <span style="color:#F03;">Este valor sin puntos ni espacios.</span>
									</div>
									<span id="resp"></span>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Nombre (*)</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" style="text-transform:uppercase;" required>
									</div>
								</div>
									
								 <div class="control-group">
									<label class="control-label">Régimen
									    <button class="tooltipp">Tipo ya sea fiscal, laboral, contable, legal o regulatorio.</button>
										<i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span6" name="regimen" style="text-transform:uppercase;">
									</div>
								</div>	
 
                                
                                <div class="control-group">
									<label class="control-label">Email
									    <button class="tooltipp">Comunicacion con los proveedores.</button>
										<i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="email" class="span6" name="email" style="text-transform:lowercase;">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Teléfono
									    <button class="tooltipp">Telefono del proveedor para una mejor comunicacion.</button>
										<i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span4" name="telefono">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Pais</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="pais">
											<option value="1"></option>
                                            <?php
											$conOp = mysqli_query($conexionBdAdmin,"SELECT * FROM localidad_paises ORDER BY pais_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['pais_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                               
                               <div class="control-group">
									<label class="control-label">Ciudad(COP)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
											<option value="1"></option>
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
									<label class="control-label">Ciudad internacional</label>
									<div class="controls">
										<input type="text" class="span6" name="otraCiudad">
									</div>
								</div>
									
								 <div class="control-group">
									<label class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" class="span6" name="direccion" style="text-transform:uppercase;">
									</div>
								</div>
									
                               </fieldset>
                                
                              
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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