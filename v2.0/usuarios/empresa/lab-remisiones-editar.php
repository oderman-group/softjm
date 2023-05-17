<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 1;
$tituloPagina = "Editar remisión";
include("verificar-paginas.php");

$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
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
								<input type="hidden" name="idSql" value="48">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">
								
								<div class="card-body">
                                    <div class="form-group m-b-0 text-left">
                                        <a href="lab-remisiones-cotizacion.php?id=<?=$_GET["id"];?>" class="btn btn-success waves-effect waves-light">Cotización</a>
										<a href="lab-remisiones-seguimiento.php?id=<?=$_GET["id"];?>" class="btn btn-danger waves-effect waves-light">Seguimiento</a>
                                    </div>
									
									
									<div class="form-group m-b-0 text-right">
                                        <a href="lab-remisiones-imprimir.php?id=<?=$resultadoD['rem_id'];?>&estado=1" target="_blank" class="btn btn-success waves-effect waves-light">Remisión entrada</a>
										
										<?php if($resultadoD['rem_estado']==2){?>
										<a href="lab-remisiones-imprimir.php?id=<?=$resultadoD['rem_id'];?>&estado=2" target="_blank" class="btn btn-success waves-effect waves-light">Remisión salida</a>
										<?php }?>
										
										<?php if($resultadoD['rem_generar_certificado']==1 and $resultadoD['rem_estado']==1){?>
										<a href="sql.php?get=30&id=<?=$resultadoD['rem_id'];?>" onClick="if(!confirm('Desea generar salida a este equipo?')){return false;}" target="_blank" class="btn btn-success waves-effect waves-light">Remisión salida</a>
										<?php }?>
										
										<?php if($resultadoD['rem_generar_certificado']!=1){?>
										<a href="sql.php?get=31&id=<?=$resultadoD['rem_id'];?>&cte=<?=$resultadoD['rem_cliente'];?>" onClick="if(!confirm('Desea generar certificado a este equipo?')){return false;}" target="_blank" class="btn btn-success waves-effect waves-light">Generar Certificado</a>
										<?php }?>
										
										<?php if($resultadoD['rem_generar_certificado']==1){?>
										<a href="lab-certificado-imprimir.php?id=<?=$resultadoD['rem_id'];?>" target="_blank" class="btn btn-success waves-effect waves-light">Certificado</a>
										<?php }?>
										
										<a href="../../../usuarios/files/adjuntos/<?=$resultadoD['rem_archivo'];?>" target="_blank" class="btn btn-success waves-effect waves-light">Ver imagen</a>
										
                                    </div>
                                </div>
								
                                <div class="card-body">
									
									<?php if($resultadoD['rem_archivo']!=""){echo '<p><a href="sql.php?get=33&id='.$resultadoD["rem_id"].'" title="Eliminar">X</a> <img src="../../../usuarios/files/adjuntos/'.$resultadoD["rem_archivo"].'" width="50"></p>';}?>
									
										<h4 class="card-title">Datos básicos</h4>
									
									<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">ID</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="equipo" value="<?=$resultadoD['rem_id'];?>" readonly>
													</div>
												</div>
											</div>
										
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Estado</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="estado" disabled>
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
														<input type="date" class="form-control" id="fname" name="fecha" value="<?=$resultadoD['rem_fecha'];?>">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Asesor</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="referencia" value="<?=$resultadoD['usr_nombre'];?>" readonly>
													</div>
												</div>
											</div>
										</div>
									
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Equipo</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="equipo" value="<?=$resultadoD['rem_equipo'];?>" readonly>
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Referencia</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="referencia" value="<?=$resultadoD['rem_referencia'];?>">
													</div>
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Marca</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="marca" value="<?=$resultadoD['rem_marca'];?>">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Tipo de equipo</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="tipoEquipo">
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
														<input type="text" class="form-control" id="fname" name="serial" value="<?=$resultadoD['rem_serial'];?>">
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Nuevo o usado?</label>
													<div class="col-sm-9">
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="tiposEquipos" required>
															<option value="2">--</option>
															<option value="1" <?php if($resultadoD['rem_tipos_equipos']==1){echo "selected";} ?>>Nuevo</option>
															<option value="2" <?php if($resultadoD['rem_tipos_equipos']==2){echo "selected";} ?>>Usado</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Precisión angular "</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="pAngular" value="<?=$resultadoD['rem_precision_angular'];?>" maxlength="1">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Precisión a distancia</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="pDistancia" value="<?=$resultadoD['rem_precision_distancia'];?>">
													</div>
												</div>
											</div>
										</div>
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Tiempo de entrega</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="tiempoEntrega" value="<?=$resultadoD['rem_dias_entrega'];?>">
													</div>
												</div>
											</div>	
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Días para reclamar el equipo</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" name="tiempoReclamar" value="<?=$resultadoD['rem_dias_reclamar'];?>">
													</div>
												</div>
											</div>
										</div>
									
									<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Vigencia cerificado (meses)</label>
													<div class="col-sm-9">
														<input type="text" class="form-control" id="fname" name="vigenciaCerificado" value="<?=$resultadoD['rem_tiempo_certificado'];?>">
													</div>
												</div>
											</div>	
										
										<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Imagen Certificado</label>
													<div class="col-sm-9">
														<input type="file" class="form-control" id="fname" name="imgCertificado">
														<?php
														$msjFoto = 'Sin foto.';
														if($resultadoD['rem_foto_certificado']!=""){
															$msjFoto = 'La foto está OK.';
														}
														echo $msjFoto;
														?>
													</div>
												</div>
											</div>
											
											
										</div>
									
									
									
									<?php 
									//PARA ESTACIÓN Y TEODOLITO
									if($resultadoD['rem_tipo_equipo']==1 or $resultadoD['rem_tipo_equipo']==2){?>
									<p>&nbsp;</p>
									<h4 style="color: darkblue;">INSPECCIÓN Y AJUSTE SISTEMA ANGULAR</h4>
									<table style="width:100%; padding: 10px; margin-top: 30px;" border="0">
										<tr>
											<td align="center">

												<table style="width:100%; padding: 20px;" border="0">
													<tr align="center"><td rowspan="6" style="color: midnightblue;">INSPECCIÓN<br>DE<br>ENTRADA</td></tr> 
												</table>
											</td>

											<td>
												<table style="width:100%; padding: 10px;" border="0">
													<tr><td>POSICIÓN 1 (VERTICAL D)</td></tr>
													<tr><td>POSICIÓN 1 (HORIZONTAL D)</td></tr>
													<tr><td>POSICIÓN 1 (VERTICAL I)</td></tr>
													<tr><td>POSICIÓN 1 (HORIZONTAL I)</td></tr>
													<tr><td>ERROR OBSERVADO V</td></tr>
													<tr><td>ERROR OBSERVADO H</td></tr>
												</table>
											</td>

											<td>
												<table style="width:100%; padding: 10px;" border="1" rules="all">
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p1vd_grados'];?>" name="p1vd_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1vd_minutos'];?>" name="p1vd_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1vd_segundos'];?>" name="p1vd_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p1hd_grados'];?>" name="p1hd_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1hd_minutos'];?>" name="p1hd_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1hd_segundos'];?>" name="p1hd_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p1vi_grados'];?>" name="p1vi_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1vi_minutos'];?>" name="p1vi_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1vi_segundos'];?>" name="p1vi_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p1hi_grados'];?>" name="p1hi_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1hi_minutos'];?>" name="p1hi_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p1hi_segundos'];?>" name="p1hi_segundos" style="text-align: center;"></td>
													</tr>
													
													<?php
													$sumaGradosV1 = ($resultadoD['rem_p1vd_grados'] + $resultadoD['rem_p1vi_grados']);
													$sumaMinutosV1 = ($resultadoD['rem_p1vd_minutos'] + $resultadoD['rem_p1vi_minutos']);
													$sumaSegundosV1 = ($resultadoD['rem_p1vd_segundos'] + $resultadoD['rem_p1vi_segundos']);
													
													$gradosV1 = (359 - $sumaGradosV1);
													$minutosV1 = (59 - $sumaMinutosV1);
													$segundosV1 = (60 - $sumaSegundosV1);

													$sumaGradosH1 = ($resultadoD['rem_p1hd_grados'] + $resultadoD['rem_p1hi_grados']);
													$sumaMinutosH1 = ($resultadoD['rem_p1hd_minutos'] + $resultadoD['rem_p1hi_minutos']);
													$sumaSegundosH1 = ($resultadoD['rem_p1hd_segundos'] + $resultadoD['rem_p1hi_segundos']);
													
													$gradosH1 = (179 - $sumaGradosH1);
													$minutosH1 = (59 - $sumaMinutosH1);
													$segundosH1 = (60 - $sumaSegundosH1);
													?>
													
													<tr align="center">
														<td><?php if($gradosV1>0 and $gradosV1!=360) echo $gradosV1; else echo "00";?>°</td>
														<td><?php if($minutosV1>0 and $minutosV1!=59) echo $minutosV1; else echo "00";?>'</td>
														<td><?=$segundosV1;?>"</td>
													</tr>
													<tr align="center">
														<td><?php if($gradosH1>0 and $gradosH1!=180) echo $gradosH1; else echo "00";?>°</td>
														<td><?php if($minutosH1>0 and $minutosH1!=59) echo $minutosH1; else echo "00";?>'</td>
														<td><?=$sumaSegundosH1;?>"</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<p>&nbsp;</p>
									
									
									<table style="width:100%; padding: 10px; margin-top: 30px;" border="0">
										<tr>
											<td align="center">

												<table style="width:100%; padding: 20px;" border="0">
													<tr align="center"><td rowspan="6" style="color: midnightblue;">AJUSTE<br>EN<br>LABORATOR<br>IO</td></tr> 
												</table>
											</td>

											<td>
												<table style="width:100%; padding: 10px;" border="0">
													<tr><td>POSICIÓN 1 (VERTICAL D)</td></tr>
													<tr><td>POSICIÓN 1 (HORIZONTAL D)</td></tr>
													<tr><td>POSICIÓN 1 (VERTICAL I)</td></tr>
													<tr><td>POSICIÓN 1 (HORIZONTAL I)</td></tr>
													<tr><td>ERROR OBSERVADO V</td></tr>
													<tr><td>ERROR OBSERVADO H</td></tr>
												</table>
											</td>

											<td>
												<table style="width:100%; padding: 10px;" border="1" rules="all">
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p2vd_grados'];?>" name="p2vd_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2vd_minutos'];?>" name="p2vd_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2vd_segundos'];?>" name="p2vd_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p2hd_grados'];?>" name="p2hd_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2hd_minutos'];?>" name="p2hd_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2hd_segundos'];?>" name="p2hd_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p2vi_grados'];?>" name="p2vi_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2vi_minutos'];?>" name="p2vi_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2vi_segundos'];?>" name="p2vi_segundos" style="text-align: center;"></td>
													</tr>
													<tr align="center">
														<td><input value="<?=$resultadoD['rem_p2hi_grados'];?>" name="p2hi_grados" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2hi_minutos'];?>" name="p2hi_minutos" style="text-align: center;"></td>
														<td><input value="<?=$resultadoD['rem_p2hi_segundos'];?>" name="p2hi_segundos" style="text-align: center;"></td>
													</tr>
													
													
													<?php
													$sumaGradosV2 = ($resultadoD['rem_p2vd_grados'] + $resultadoD['rem_p2vi_grados']);
													$sumaMinutosV2 = ($resultadoD['rem_p2vd_minutos'] + $resultadoD['rem_p2vi_minutos']);
													$sumaSegundosV2 = ($resultadoD['rem_p2vd_segundos'] + $resultadoD['rem_p2vi_segundos']);
													
													$gradosV2 = (359 - $sumaGradosV2);
													$minutosV2 = (59 - $sumaMinutosV2);
													$segundosV2 = (60 - $sumaSegundosV2);

													$sumaGradosH2 = ($resultadoD['rem_p2hd_grados'] + $resultadoD['rem_p2hi_grados']);
													$sumaMinutosH2 = ($resultadoD['rem_p2hd_minutos'] + $resultadoD['rem_p2hi_minutos']);
													$sumaSegundosH2 = ($resultadoD['rem_p2hd_segundos'] + $resultadoD['rem_p2hi_segundos']);
													
													$gradosH2 = (179 - $sumaGradosH2);
													$minutosH2 = (59 - $sumaMinutosH2);
													$segundosH2 = (60 - $sumaSegundosH2);
													?>
													
													<tr align="center">
														<td><?php if($gradosV2>0 and $gradosV2!=360) echo $gradosV2; else echo "00";?>°</td>
														<td><?php if($minutosV2>0 and $minutosV2!=59) echo $minutosV2; else echo "00";?>'</td>
														<td><?=$segundosV2;?>"</td>
													</tr>
													<tr align="center">
														<td><?php if($gradosH2>0 and $gradosH2!=180) echo $gradosH2; else echo "00";?>°</td>
														<td><?php if($minutosH2>0 and $minutosH2!=59) echo $minutosH2; else echo "00";?>'</td>
														<td><?=$sumaSegundosH2;?>"</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									
									<?php }?>
									
									
									<?php 
									//PARA NIVEL
									if($resultadoD['rem_tipo_equipo']==3 or $resultadoD['rem_tipo_equipo']==5){?>
									<h4 style="color: darkblue; margin-top: 10px;">LECTURAS</h4>
									<table style="width:100%; padding: 10px;" border="0">
										<tr>
											<td width="30%">
												<table style="width:100%;" border="0">
													<tr><td><strong>L1:</strong></td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1a'];?>" name="l1a" style="text-align: center;"></td><td> m</td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1b'];?>" name="l1b" style="text-align: center;"></td><td> m</td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1c'];?>" name="l1c" style="text-align: center;"></td><td> mm</td></tr>
												</table>
											</td>

											<td width="30%">
												<table style="width:100%;" border="0">
													<tr><td><strong>L2:</strong></td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2a'];?>" name="l2a" style="text-align: center;"></td><td> m</td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2b'];?>" name="l2b" style="text-align: center;"></td><td> m</td></tr>
													<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2c'];?>" name="l2c" style="text-align: center;"></td><td> mm</td></tr>
												</table>
											</td>

											<td width="40%">
												<table style="width:100%;" border="0">
													<tr><td>ERROR DETECTADO</td><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_error_detectado'];?>" name="errorDetectado" style="text-align: center;"></td><td> mm</td></tr>
												</table>
											</td>
										</tr>
									</table>
									<p>&nbsp;</p>
									<?php }?>
									

										
										
										<h6>DETALLES</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="detalles" placeholder="Escriba los detalles..."><?=$resultadoD['rem_detalles'];?></textarea>
													</div>
												</div>
											</div>
											
										</div>
									
										<h6>OBSERVACIONES DE ENTRADA</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="descripcion" placeholder="Observaciones de entrada"><?=$resultadoD['rem_descripcion'];?></textarea>
													</div>
												</div>
											</div>
											
										</div>
									
										<h6>OBSERVACIONES DE SALIDA</h6>
										<div class="row">
											<div class="col-sm-12 col-lg-12">
												<div class="form-group row">
													<div class="col-sm-12">	
														<textarea rows="5" style="width: 100%;" name="obsSalida" placeholder="Observaciones de salida"><?=$resultadoD['rem_observacion_salida'];?></textarea>
													</div>
												</div>
											</div>
											
										</div>
									
									
										<div class="row">
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Servicios</label>
													<div class="col-sm-9">	
														<select class="select2 form-control custom-select" multiple="multiple" style="width: 100%; height:36px;" name="servicios[]">
																	<?php
																	$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios");
																	while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
																		
																		$consultaOpciones=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_servicios WHERE remxs_id_remision='".$_GET["id"]."' AND remxs_id_servicio='".$datosSelect[0]."'");
																		$numOpciones = mysqli_num_rows($consultaOpciones);	
																	?>
																	<option value="<?=$datosSelect[0];?>" <?php if($numOpciones > 0){echo "selected";} ?> ><?=strtoupper($datosSelect['serv_nombre']);?></option>
																	<?php }?>
														</select>
													</div>
												</div>
											</div>
											
											<div class="col-sm-12 col-lg-6">
												<div class="form-group row">
													<label for="fname" class="col-sm-3 text-right control-label col-form-label">Cliente</label>
													<div class="col-sm-9">	
														<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="cliente">
															<option>Cliente</option>
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
																	<option value="<?=$datosSelect[0];?>" <?php if($resultadoD['rem_cliente']==$datosSelect[0]){echo "selected";} ?>><?=strtoupper($datosSelect['cli_nombre']);?></option>
																	<?php }?>
														</select>
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