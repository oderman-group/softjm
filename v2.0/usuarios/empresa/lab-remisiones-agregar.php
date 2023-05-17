<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 1;
$tituloPagina = "Agregar remisión";
include("verificar-paginas.php");
?>
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../assets/libs/select2/dist/css/select2.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <?php //include("../compartido/preloader.php");?>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include("../compartido/encabezado.php");?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include("../compartido/menu.php");?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><?=$tituloPagina;?></h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="lab-remisiones.php">Remisiones</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?=$tituloPagina;?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
								<input type="hidden" name="idSql" value="47">
								
                                <div class="card-body">
									
									
										<h3>ESCOJA UN CLIENTE EXISTENTE  Ó</h3>
										<script type="application/javascript">
											function clientes(dato){
												var id = dato.value;
												location.href = "lab-remisiones-agregar.php?cte="+id;
											}
										</script>	
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
											<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Cliente</label>
													<div class="col-sm-9">	
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="cliente" onChange="clientes(this)">
															<option value="">Cliente</option>
																	<?php
																	$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");
																	while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){


																		//Solo Vendedores externos
																		if($datosUsuarioActual[3] == 14){
																			$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$datosSelect['cli_zona']."'");
																			$numZ = mysqli_num_rows($consultaZonas);
																			if($numZ==0) continue;
																		}
																	?>
																	<option value="<?=$datosSelect[0];?>" <?php if($_GET['cte']==$datosSelect[0]){echo "selected";} ?>><?=strtoupper($datosSelect['cli_nombre']);?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>
											
											<?php 
											if(is_numeric($_GET["cte"])){
											?>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Contactos</label>
													<div class="col-sm-9">	
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="contacto">
															<option value="">Contactos</option>
																	<?php
																	$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_cliente_principal='".$_GET["cte"]."'");
																	while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
																	?>
																	<option value="<?=$datosSelect[0];?>" <?php if($_GET['contacto']==$datosSelect[0]){echo "selected";} ?>><?=strtoupper($datosSelect['cont_nombre']);?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>
											<?php }?>
										</div>
										
									
										<hr>
									
										<h3>CREE UN NUEVO CLIENTE</h3>
										<div class="row">
											
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">NIT</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="usuarioCliente">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Ciudad</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="ciudadCliente">
															<option value="">--</option>
															<?php
																	$ciudades = mysqli_query($conexionBdPrincipal,"SELECT * FROM ".BDADMIN.".localidad_ciudades 
																	INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
																	ORDER BY ciu_nombre");
																	while($ciudad = mysqli_fetch_array($ciudades, MYSQLI_BOTH)){
																	?>
																	<option value="<?=$ciudad['ciu_id'];?>"><?=$ciudad['ciu_nombre'].", ".$ciudad['dep_nombre'];?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>	
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Nombre</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="nombreCliente">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Email</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="emailCliente">
													</div>
												</div>
											</div>
											
										</div>
									
									
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Celular</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="celularCliente">
													</div>
												</div>
											</div>
											
										</div>
										
										<hr>
									
										<h4>CONTACTO NUEVO (Si no existe)</h4>
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Nombre</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="nombreContacto">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Email</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="emailContacto">
													</div>
												</div>
											</div>
											
										</div>
									
										<div class="row">
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Celular</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="celularContacto">
													</div>
												</div>
											</div>
											
										</div>
										
									<hr>
										<h4 class="card-title">Datos básicos</h4>
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Tipo de equipo</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="tipoEquipo">
															<option value="">--</option>
															<option value="1">Estación total</option>
															<option value="2">Teodolito</option>
															<option value="3">Nivel</option>
															
															<option value="4">GPS</option>
															<option value="5">Nivel digital</option>
															<option value="6">Distanciómetro</option>
															<option value="7">Nivel laser</option>
															<option value="8">Semi-estación</option>
															<option value="9">Colector</option>
															<option value="10">Brújula</option>
															<option value="11">Bastón</option>
															<option value="12">Trípode</option>
															<option value="13">Prisma</option>
															<option value="14">Batería</option>
															<option value="15">Radio</option>
															<option value="16">Estuche</option>
															<option value="17">Drone</option>
															<option value="18">MATENIMIENTO GENERAL DRON</option>
														</select>
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-3">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Referencia</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="referencia">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-3">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Nuevo o usado?</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="tiposEquipos" required>
															<option value="">--</option>
															<option value="1">Nuevo</option>
															<option value="2">Usado</option>
														</select>
													</div>
												</div>
											</div>
											
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Marca</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="marca">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Serial</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="serial">
													</div>
												</div>
											</div>
											
										</div>
									
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Precisión angular "</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="pAngular" maxlength="1">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Precisión a distancia</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="pDistancia" value="2mm + 2ppm">
													</div>
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Tiempo de entrega</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="tiempoEntrega" value="DE 1 A 3 DÍAS">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Días para reclamar el equipo</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="tiempoReclamar" value="20">
													</div>
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Vigencia cerificado (meses)</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="vigenciaCerificado" value="6">
													</div>
												</div>
											</div>	
											
											
										</div>
										
										<h6>DETALLES</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="detalles" placeholder="Escriba los detalles..."></textarea>
													</div>
												</div>
											</div>
											
										</div>
										
										<h6>OBSERVACIONES DE ENTRADA</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="descripcion" placeholder="Observaciones de entrada"></textarea>
													</div>
												</div>
											</div>
											
										</div>
									
									<h6>OBSERVACIONES DE SALIDA</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="obsSalida" placeholder="Observaciones de salida"></textarea>
													</div>
												</div>
											</div>
											
										</div>

									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Servicios</label>
													<div class="col-sm-9">	
														<select class="select2 form-control" multiple="multiple" style="width: 100%; height:36px;" name="servicios[]">
																	<?php
																	$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios");
																	while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
																	?>
																	<option value="<?=$datosSelect[0];?>"><?=strtoupper($datosSelect['serv_nombre']);?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Imagen</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" id="fname" name="imagen" >
													</div>
												</div>
											</div>	
										</div>
									
									
									
									
									
									
                                </div>
                            
                                
                                <div class="card-body">
                                    <div class="form-group m-b-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Guardar cambios</button>
                                    </div>
                                </div>
								
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Row -->

                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php include("../compartido/footer.php");?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
    <?php include("../compartido/panel-configuracion.php");?>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <script src="../../dist/js/app.min.js"></script>
    <script src="../../dist/js/app.init.js"></script>
    <script src="../../dist/js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../../assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="../../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../../dist/js/custom.min.js"></script>
	
	<script src="../../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../../dist/js/pages/forms/select2/select2.init.js"></script>
</body>


<!-- Mirrored from wrappixel.com/demos/admin-templates/nice-admin/html/ltr/form-horizontal.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 01 Aug 2018 16:03:37 GMT -->
</html>