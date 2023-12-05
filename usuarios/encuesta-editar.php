<?php include("sesion.php");?>
<?php
$idPagina = 231;
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");
$consultaEncuesta=mysqli_query($conexionBdPrincipal,"SELECT * FROM encuesta_satisfaccion WHERE encs_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consultaEncuesta, MYSQLI_BOTH);
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
						<li><a href="encuesta.php">Encuestas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
					<?php
							if (Modulos::validarRol([230], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
								echo '<p><a href="encuesta-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>';
							}
						?>	   
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/encuestas-actualizar.php">
                            <input type="hidden" name="idSql" value="29">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
  
                               <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="text" class="span4" name="fecha" value="<?=$resultadoD['encs_fecha'];?>" readonly>
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($resultadoD['encs_cliente']==$resOp[0]){echo "selected";}?>><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Usuario que atendió</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="usuario">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['usr_id'];?>" <?php if($resultadoD['encs_atendido']==$resOp[0]){echo "selected";}?>><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Producto que compró o cotizó</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="producto">
											<option value="0"></option>
                                            <option value="0">Ninguno</option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['prod_id'];?>" <?php if($resultadoD['encs_producto']==$resOp[0]){echo "selected";}?>><?=$resOp['prod_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
     
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>

                            
                            <h3 style="font-weight:bold;">Resultados de la encuesta (No editable)</h3>
                            <table width="100%" border="1" rules="all" align="center">
							<tr align="center" style="font-weight:bold; height:30px;">
                                <td align="left">Preguntas</td> 
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
							</tr>
                            <tr align="center">
                                <td align="left">¿El tiempo de atencion por parte del funcinario fue el adecuado?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p1" disabled <?php if($i==$resultadoD['encs_p1']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">¿El trato por parte del funcionario fue cordial?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p2" disabled <?php if($i==$resultadoD['encs_p2']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">¿Le fue resuelta su duda?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p3" disabled <?php if($i==$resultadoD['encs_p3']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la asesoria prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p4" disabled <?php if($i==$resultadoD['encs_p4']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la atención prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p5" disabled <?php if($i==$resultadoD['encs_p5']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr>
                                <td>Coloque aquí las Observaciones</td> 
                                <td colspan="5"><?=$resultadoD['encs_observaciones'];?></td>
							</tr>
							</table>
                                

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
