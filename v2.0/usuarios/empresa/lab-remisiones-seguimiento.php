<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 249;
$tituloPagina = "Seguimiento a remisiones";
include("verificar-paginas.php");

$consultaRemision=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN clientes ON cli_id=rem_cliente
INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."'");
$remision = mysqli_fetch_array($consultaRemision, MYSQLI_BOTH);

$consultaContacto=mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$remision["rem_contacto"]."'");
$contacto = mysqli_fetch_array($consultaContacto, MYSQLI_BOTH);

$estadosRemision = array("","Entrada","Salida");
?>
    <!-- This page css -->
    <link href="../../assets/extra-libs/c3/c3.min.css" rel="stylesheet">
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
                        <h4 class="page-title">Seguimiento remisiones</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Escritorio</a></li>
									<li class="breadcrumb-item"><a href="lab-remisiones.php">Remisiones</a></li>
									<li class="breadcrumb-item"><a href="lab-remisiones-editar.php?id=<?=$_GET["id"];?>">Editar remisiones</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Seguimiento</li>
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
                <!-- basic table -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Seguimiento</h4>
                                <ul class="list-unstyled m-t-40">
                                    <?php	
									$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento
									INNER JOIN usuarios ON usr_id=remseg_usuario
									WHERE remseg_id_remisiones='".$_GET["id"]."'
									ORDER BY remseg_id DESC
									");
									$conRegistros = 1;
									while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
										$html = '<a href="sql.php?id='.$resultado['cseg_id'].'&get=28&idTK='.$_GET["idTK"].'" class="label label-warning">No notificado</a>';
										if($resultado['remseg_notificar_cliente']==1){
											$html = '<span class="label label-success">Notificado</span>';
										}
										switch($resultado['remseg_visto_cliente']){
											case 0: $estado = 'No revisado'; $color='red'; break;
											case 1: $estado = 'Visto ('.$resultado['remseg_fecha_visto'].')'; $color='blue'; break;
										}
									?>
									<li class="media">
                                        <img class="m-r-15" src="../../assets/images/users/2.jpg" width="60" alt="<?=$resultado['usr_nombre'];?>">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1" style="font-weight: bold;"><?=$resultado['usr_nombre'];?></h5>
											<span style="color: darkgray; font-size: 10px;"><?=$resultado['remseg_fecha'];?></span>
											<div class="dropdown-divider"></div>
											<?=$resultado['remseg_comentario'];?>
											
											<p><?=$html;?></p>
											
											<?php if($resultado['remseg_notificar_cliente']==1){?>
												<p style="font-size: 9px; color:<?=$color;?>; font-weight:bold;"><?=$estado;?></p>
											<?php }?>
											
											<?php if($resultado['remseg_archivo']!=""){?>
												<p><a href="../../../usuarios/files/adjuntos/<?=$resultado['remseg_archivo'];?>" style="text-decoration: underline;" target="_blank">Descargar Archivo</a></p>
											<?php }?>
											
                                        </div>
                                    </li>
                                    <hr>
									<?php }?>
                                    
                                </ul>
                            </div>
                        </div>
						
						<?php if($remision['rem_estado']==1){?>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="m-b-20">Seguimiento</h4>
                                <form method="post" action="sql.php" enctype="multipart/form-data">
									<input type="hidden" name="idSql" value="49">
									<input type="hidden" name="id" value="<?=$_GET["id"];?>">
									<input type="hidden" name="cliente" value="<?=$remision["rem_cliente"];?>">
									<input type="hidden" name="contacto" value="<?=$remision["rem_contacto"];?>">
									
									<h5>Observaciones</h5>
									<p>
										<div class="col-md-12">
                                           <div class="form-group">
												<select name="obsLista">
													<option value="">Escoja una ya existente</option>
													<?php
													$observaciones = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento 
													GROUP BY remseg_comentario
													ORDER BY remseg_comentario
													");		 
													while($obs = mysqli_fetch_array($observaciones, MYSQLI_BOTH)){
													?>
														<option value="<?=$obs['remseg_comentario'];?>"><?=$obs['remseg_comentario'];?></option>
													<?php
													}
													?>		
											   	</select>
											</div>
										</div>	
									</p>
								
									<p>
										<div class="col-md-12">
                                           <div class="form-group">
                                               
												<textarea name="observaciones" rows="5" cols="80"></textarea>
											</div>
										</div>	
									</p>
								
									<p>
										<div class="col-md-12">
                                           <div class="form-group">
                                               <label class="control-label">Archivo</label>
												<input type="file" name="archivo">
											</div>
										</div>	
									</p>

									<div class="dropdown-divider"></div>
						
									<p>
										<div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck3" value="1" name="notfCliente">
                                            <label class="custom-control-label" for="customCheck3">Notificar al cliente</label>
                                        </div>
									</p>
									
                                    <button type="submit" class="m-t-20 btn waves-effect waves-light btn-success">Guardar seguimiento</button>
                                </form>
                            </div>
                        </div>
						<?php }?>

                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><?=$remision['rem_equipo'];?></h4>
                            </div>
							<div class="card-body bg-light">
                                <div class="row text-center">
                                    <div class="col-6 m-t-10 m-b-10">
                                        <?=$remision['rem_referencia'];?>
                                    </div>
									
                                    <div class="col-6 m-t-10 m-b-10">
                                        <?=$remision['rem_serial'];?>
                                    </div>
                                </div>
                            </div>
							
                            <div class="card-body bg-light">
                                <div class="row text-center">
                                    <div class="col-6 m-t-10 m-b-10">
                                        <span class="label label-warning"><?=$estadosRemision[$remision['rem_estado']];?></span>
                                    </div>
									
                                    <div class="col-6 m-t-10 m-b-10">
                                        <?=$remision['rem_fecha'];?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="p-t-20">Asesor</h5>
                                <span><?=$remision['usr_nombre'];?></span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body text-center">
                                <h4 class="card-title"><?=$remision['cli_nombre'];?></h4>
                                <div class="profile-pic m-b-20 m-t-20">
                                    <img src="../../assets/images/users/5.jpg" width="150" class="rounded-circle" alt="user">
                                    <h4 class="m-t-20 m-b-0"><?=$remision['ciu_nombre'].", ".$remision['dep_nombre'];?></h4>
                                    <a href="mailto:<?=$remision['cli_email'];?>"><?=$remision['cli_email'];?></a><br>
									<?=$remision['cli_telefono'];?>
                                </div>
								<hr>
								<h5><?=strtoupper($contacto['cont_nombre']);?></h5>
								<p>
									<?=$contacto['cont_celular'];?><br>
									<?=$contacto['cont_email'];?><br>
								</p>
                            </div>
							
                        </div>
                    </div>
                </div>
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
    <script src="../../assets/extra-libs/taskboard/js/jquery.ui.touch-punch-improved.js"></script>
    <script src="../../assets/extra-libs/taskboard/js/jquery-ui.min.js"></script>
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
    <!--This page JavaScript -->
    <!-- This Page JS -->
    <script src="../../assets/libs/tinymce/tinymce.min.js"></script>
    <!--c3 charts -->
    <script src="../../assets/extra-libs/c3/d3.min.js"></script>
    <script src="../../assets/extra-libs/c3/c3.min.js"></script>
	
	<script src="../../assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="../../assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="../../dist/js/pages/forms/select2/select2.init.js"></script>
	
    <script>
    $(function() {

        if ($("#mymce").length > 0) {
            tinymce.init({
                selector: "textarea#mymce",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

            });
        }
        // ============================================================== 
        // Our Visitor
        // ============================================================== 

        var chart = c3.generate({
            bindto: '#visitor',
            data: {
                columns: [
                    ['Pendientes', <?=$numeroTickets[3];?>],
                    ['Completados', <?=$numeroTickets[4];?>],
                ],

                type: 'donut',
                tooltip: {
                    show: true
                }
            },
            donut: {
                label: {
                    show: false
                },
                title: "Seguimientos",
                width: 35,

            },

            legend: {
                hide: true
                //or hide: 'data1'
                //or hide: ['data1', 'data2']

            },
            color: {
                pattern: ['#217300', '#D8C300']
            }
        });
    });
    </script>
</body>

</html>