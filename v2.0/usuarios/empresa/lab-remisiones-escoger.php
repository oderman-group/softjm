<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 246;

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
    <?php include("../compartido/preloader.php");?>
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
                        <h4 class="page-title"><?=$paginaActual['pag_nombre'];?></h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="lab-remisiones.php">Remisiones</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?=$paginaActual['pag_nombre'];?></li>
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
                            <form class="form-horizontal" method="post" action="lab-remisiones-imprimir-varias.php" target="_blank">
								<input type="hidden" name="idSql" value="47">
								
                                <div class="card-body">
										<h4 class="card-title">Datos básicos</h4>										
										
								
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Remisiones</label>
													<div class="col-sm-9">	
														<select class="select2 form-control" multiple="multiple" style="width: 100%; height:36px;" name="remisiones[]">
																	<?php
																	$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
																	INNER JOIN clientes ON cli_id=rem_cliente
                                                                    WHERE rem_id_empresa='".$idEmpresa."'
																	");
																	while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
																	?>
																	<option value="<?=$datosSelect[0];?>"><?=strtoupper($datosSelect['rem_id']." - ".$datosSelect['rem_equipo']." - ".$datosSelect['cli_nombre']);?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Estado</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="estado">
															<option value="1">Entrada</option>
															<option value="2">Salida</option>
														</select>
													</div>
												</div>
											</div>
												
										</div>
									
									
									
									
                                </div>
                            
                                
                                <div class="card-body">
                                    <div class="form-group m-b-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Imprimir</button>
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