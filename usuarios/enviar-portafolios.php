<?php 
include("sesion.php");

$idPagina = 110;
$paginaActual['pag_nombre'] = "Enviar portafolios";
include("includes/verificar-paginas.php");
include("includes/head.php");

if($_GET["em"]==3){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo=3, cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='".$_SESSION["id"]."' WHERE cli_id='".$_GET["cte"]."'  AND cli_id_empresa='".$idEmpresa."'");	
}	
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
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_create/enviar-portafolio-guardar.php" enctype="multipart/form-data">
                            <input type="hidden" name="cte" value="<?=$_GET["cte"];?>">
							<fieldset class="default">
								<legend>Opciones</legend>
								
									<div class="control-group">
                                        <label class="control-label">Clientes</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span8" multiple tabindex="2" name="clientes[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes  WHERE cli_id_empresa='".$idEmpresa."'");
                                                while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>" <?php if($resOp[0]==$_GET["cte"]){echo "selected";}?>><?=$resOp[1]." (".$resOp['cli_email'].")";?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
								
									<div class="control-group">
                                        <label class="control-label">Portafolios</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span8" multiple tabindex="2" name="portafolios[]">
                                                <option value=""></option>
                                                
                                                <option value="1">Portafolio Topografía</option>
                                                <option value="2">Portafolio Construcción y Arquitectura</option>
												<option value="3">Portafolio Accesorios</option>
												<option value="4">Portafolio Agricultura</option>
												<option value="5">Portafolio Cartografía</option>
                                               
                                               <?php if($_SESSION["bd"]=='odermancom_orioncrm_exacta'){?>
                                                    <option value="6">Portafolio Exacta Ing.</option>
                                                <?php }?>

                                                <option value="7">Brochure Laboratorio</option>

                                                <option value="8">Portafolio Drones.</option>
                                                <option value="9">Portafolio Estaciones totales</option>
                                                
                                            </select>
                                        </div>
                                   </div>
                                   
                               </fieldset>
                               
                                
                               
								<div class="form-actions">
                                <?php if(Modulos::validarRol([365], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
                                    ?>
									<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar Portafolios</button>
								<?php } ?>
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