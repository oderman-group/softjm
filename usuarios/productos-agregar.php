<?php include("sesion.php");?>
<?php
$idPagina = 37;
$paginaActual['pag_nombre'] = "Agregar productos";
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
  function repetidosVerificar(enviada){
  var idUnico = enviada.value;
  var opcion = 3;	  
	  $('#resp').empty().hide().html("esperando...").show(1);
		datos = "idUnico="+(idUnico)+
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
							<form class="form-horizontal" method="post" action="sql.php" name="formProductos">
                            <input type="hidden" name="idSql" value="19">
								
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
                                
                                <?php if ($configu['conf_proveedor_cotizacion'] == 1) { ?>

<div class="control-group">
    <label class="control-label">Proveedor</label>
    <div class="controls">
        <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
            <option value=""></option>
            <?php
            $conOp = mysql_query("SELECT * FROM proveedores", $conexion);
            while ($resOp = mysql_fetch_array($conOp)) {
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
											$conOp = mysql_query("SELECT * FROM productos_categorias WHERE catp_grupo=1",$conexion);
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
									<label class="control-label">Grupo 2 (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="categoria" id="categoria" required>
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM productos_categorias WHERE catp_grupo=2",$conexion);
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
									<label class="control-label">Marca (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="marca" id="marca" required>
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM marcas",$conexion);
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