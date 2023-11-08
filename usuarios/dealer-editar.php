<?php 
include("sesion.php");

$idPagina = 234;

include("includes/verificar-paginas.php");
include("includes/head.php");
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM dealer WHERE deal_id='".$_GET["id"]."' AND deal_id_empresa='".$idEmpresa."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
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
						<li><a href="dealer.php">Grupos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <p><a href="dealer-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
            
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/dealer-actualizar.php">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            	   
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre" value="<?=$resultadoD['deal_nombre'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Clientes</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" multiple tabindex="2" name="clientes[]">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

												$consultaCategorias=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_categorias WHERE cpcat_categoria='".$_GET["id"]."' AND cpcat_cliente='".$resOp[0]."'");
												$numD = mysqli_num_rows($consultaCategorias);
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($numD > 0){echo "selected";} ?>><?=$resOp[1];?></option>
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