<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 239;

include("verificar-paginas.php");

$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."'"));
?>
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../assets/libs/select2/dist/css/select2.min.css">
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
                            <form class="form-horizontal" method="post" action="lab-certificado-generar-anulacion.php" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">

                                <div class="card-body">
                                    <h4 class="card-title">Datos básicos</h4>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['rem_id'];?>" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Estado</label>
                                                <div class="col-sm-9">
                                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" disabled>
                                                        <option value="1" <?php if($resultadoD['rem_estado']==1){echo "selected";} ?> >Entrada</option>
                                                        <option value="2" <?php if($resultadoD['rem_estado']==2){echo "selected";} ?>>Salida</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Fecha</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" value="<?=$resultadoD['rem_fecha'];?>" disabled>
                                                </div>
                                            </div>
                                        </div>	

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Asesor</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['usr_nombre'];?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Equipo</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['rem_equipo'];?>" readonly>
                                                </div>
                                            </div>
                                        </div>	

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Referencia</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['rem_referencia'];?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Marca</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['rem_marca'];?>" disabled>
                                                </div>
                                            </div>
                                        </div>	

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Tipo de equipo</label>
                                                <div class="col-sm-9">
                                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" disabled>
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
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Serial</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?=$resultadoD['rem_serial'];?>" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-lg-6">
                                            <div class="form-group row">
                                                <label for="fname" class="col-sm-3 text-right control-label col-form-label">Nuevo o usado?</label>
                                                <div class="col-sm-9">
                                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" disabled>
                                                        <option value="2">--</option>
                                                        <option value="<?= REM_TIPOS_EQUIPOS_NUEVO?>" <?php if($resultadoD['rem_tipos_equipos']==REM_TIPOS_EQUIPOS_NUEVO){echo "selected";} ?>>Nuevo</option>
											            <option value="<?= REM_TIPOS_EQUIPOS_USADO?>" <?php if($resultadoD['rem_tipos_equipos']==REM_TIPOS_EQUIPOS_USADO){echo "selected";} ?>>Usado</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h6>Motivo de anulación</h6>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12">
                                            <div class="form-group row">
                                                <div class="col-sm-12">	
                                                    <textarea rows="5" style="width: 100%;" name="motivo" placeholder="Escriba el motivo de anulación..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

								<div class="card-body">
                                    <div class="form-group m-b-0 text-right">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Generar Anulación</button>
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