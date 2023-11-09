<?php
include("sesion.php");

$idPagina = 37;
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
  function repetidosVerificar(enviada){
  var idUnico = enviada.value;
  var opcion = 3;	  
	  $('#resp').empty().hide().html("esperando...").show(1);
		datos = "idUnico="+(idUnico)+
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
						<li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/productos-guardar.php" name="formProductos">
								
								<div class="control-group">
									<label class="control-label">CÓDIGO (*)</label>
									<div class="controls">
										<input type="text" class="span4" name="referencia" required onChange="repetidosVerificar(this)" id="referencia">
									</div>
									<span id="resp"></span>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Nombre (*)</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" required id="nombre">
									</div>
                                </div>  
                                
                                <?php if ($configuracion['conf_proveedor_cotizacion'] == 1) { ?>

								<div class="control-group">
									<label class="control-label">Proveedor</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
											<option value=""></option>
											<?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
											while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
											?>
												<option value="<?= $resOp[0]; ?>"><?= $resOp['prov_nombre']; ?></option>
											<?php
											}
											?>
										</select>
										</div>

								</div>

								<?php } else { ?>
								<input type="hidden" name="proveedor" value="0">
								<?php } ?>
                                   
                               <div class="control-group">
									<label class="control-label">Grupo 1 (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo1" id="grupo1" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1 AND catp_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                               <div class="control-group">
									<label class="control-label">Grupo 2 (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="categoria" id="categoria" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2  AND catp_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Marca (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="marca" id="marca" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Replicar en soporte operativo? (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="replicar">
											<option value="0"></option>
											<option value="1">SI</option>
											<option value="0" selected>NO</option>
                                    	</select>
                                    </div>
                               </div>
 
								<div class="form-actions">
									<button type="submit" class="btn btn-info" onclick="validarCampos()"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>

                                <script type="text/javascript">
                                function validarCampos(){

                                    var referencia = document.getElementById("referencia").value;
                                    var nombre = document.getElementById("nombre").value;
                                    var grupo1 = document.getElementById("grupo1").value;
                                    var categoria = document.getElementById("categoria").value;
                                    var marca = document.getElementById("marca").value;

                                        if(referencia == ""){
                                            alert("Debe llenar el campo código");
                                            return false;
                                        }

                                        if(nombre == ""){
                                            alert("Debe llenar el campo nombre");
                                            return false;
                                        }

                                        if(grupo1 == ""){
                                            alert("Escoja una opción para el campo Grupo 1");
                                            return false;
                                        }

                                        if(categoria == ""){
                                            alert("Escoja una opción para el campo Grupo 2");
                                            return false;
                                        }

                                        if(marca == ""){
                                            alert("Escoja una opción para el campo Marca");
                                            return false;
                                        }

                                        document.formProductos.submit();
                                }
                                </script>
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