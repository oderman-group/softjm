<?php 
include("sesion.php");

$idPagina = 134;
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
						<li><a href="importacion.php">Importaciones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/importacion-guardar.php">

							<div class="control-group">
									<label class="control-label">Concepto 
									<button class="tooltipp">registrar o documentar la entrada de bienes o productos desde otro país a través de la aduana.</button>
							             <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span8" name="concepto" required>
									</div>
								</div>

								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
											 <option value=""></option>
											 <?php
											 $conOp = mysqli_query($conexionBdPrincipal, "SELECT * FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
											 while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											 ?>
												 <option value="<?=$resOp[0];?>"><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
								</div>
 
								
									
                               
                               <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span4" name="fecha" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
									
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-arrow-right"></i> Continuar</button>
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