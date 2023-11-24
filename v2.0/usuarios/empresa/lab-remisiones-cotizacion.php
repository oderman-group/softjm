<?php
include("sesion.php"); //exit();
include("../compartido/head.php");
$idPagina = 244;

include("verificar-paginas.php");
?>
    <!-- This page plugin CSS -->
    <link href="../../assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../../assets/libs/select2/dist/css/select2.min.css">
    <!-- Custom CSS -->
    <link href="../../dist/css/style.min.css" rel="stylesheet">
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
                <!-- basic table -->	
				
                <!-- scroll-vertical -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                
								<p><a href="lab-remisiones-cotizacion-generar.php?id=<?=$_GET["id"];?>" class="btn btn-danger"><i class="fa fa-plus-square"></i> Generar cotización</a></p>
								
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
												<th></th>
                                                <th>Descripción</th>
                                                <th>Cant.</th>
												<th>Valor</th>
												<th>IVA</th>
												<th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php											
											$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_servicios
											INNER JOIN servicios ON serv_id=remxs_id_servicio
											WHERE remxs_id_remision='".$_GET["id"]."'");
											$conRegistros = 1;
											while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
												$html = '<span class="label label-warning">Entrada</a>';
												
												if($resultado['rem_estado']==2){
													$html = '<span class="label label-success">Salida</span>';
												}
											?>
                                            <tr>
                                                <td><?=$conRegistros;?></td>
												<td><a href="#">X</a></td>
												
												<td><?=$resultado['serv_nombre'];?><br>
													<textarea name="observaciones" rows="3" cols="30"></textarea>
												</td>
                                                
												<td><input type="number" value="1"></td>
												
												<td>$<?=number_format($resultado['serv_precio'],0,",",".");?></td>
												<td>19%</td>
												<td>$<?=number_format($resultado['serv_precio'],0,",",".");?></td>
                                                
                                            </tr>
											<?php $conRegistros++;}?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
    <!--This page plugins -->
    <script src="../../assets/extra-libs/DataTables/datatables.min.js"></script>
    <script src="../../dist/js/pages/datatable/datatable-basic.init.js"></script>
	
	<script src="../../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../../dist/js/pages/forms/select2/select2.init.js"></script>
</body>


</html>