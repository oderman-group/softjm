<?php
include("sesion.php"); //exit();
include("../compartido/head.php");
$idPagina = 1;
$tituloPagina = "Remisiones";
include("verificar-paginas.php");

if(is_numeric($_GET["idRem"])){
?>
	<script type="text/javascript">window.open('lab-certificado-imprimir.php?id=<?=$_GET["idRem"];?>', '_blank');</script>
<?php
}
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
                        <h4 class="page-title"><?=$tituloPagina;?></h4>
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
				
				<div style="margin: 10px; padding: 10px; background-color: darkgray;">
					<h4 style="color: white;">Buscar por</h4>
					
					<form class="m-t-25" action="<?=$_SERVER["PHP_SELF"];?>" method="get">
						<div class="form-group">
						<select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="campo">
							<option value="rem_id" <?php if($_GET["campo"]=="rem_id") echo "selected"; ?> >ID</option>
							<option value="rem_serial" <?php if($_GET["campo"]=="rem_serial") echo "selected"; ?>>Serial</option>
						</select>
						</div>	
						
                       <div class="form-group">
                           <input type="text" class="form-control" name="busqueda" value="<?=$_GET["busqueda"];?>" placeholder="Buscar...">
                        </div>
						
						<div class="action-form">
							<div class="form-group m-b-0 text-left">
								<button type="submit" class="btn btn-info waves-effect waves-light">Aplicar filtros</button>
								<a href="<?=$_SERVER["PHP_SELF"];?>" class="btn btn-dark waves-effect waves-light">Quitar filtros</a>
							</div>
                        </div>
						
                    </form>
					
				</div>	
				
                <!-- scroll-vertical -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
								
								<div align="center" style="margin:10px; font-size:20px;">
								<?php
								$a = $config['conf_agno_inicio'];
								while($a<=date("Y")){
									if($a==$_GET["a"])
										echo '<a style="font-weight:bold;">'.$a.'</a>&nbsp;&nbsp;&nbsp;';
									else
										echo '<a href="lab-remisiones.php?a='.$a.'&m='.$_GET["m"].'&u='.$_GET["u"].'">'.$a.'</a>&nbsp;&nbsp;&nbsp;';	
									$a++;
								}
								?>
								</div>
								
								<div align="center" style="margin:10px; font-size:18px;">
								<?php
								$m = 1;
								$meses = array("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
								while($m<=12){
									$numRem = mysql_num_rows(mysql_query("SELECT * FROM remisiones 
									INNER JOIN clientes ON cli_id=rem_cliente
									WHERE rem_estado=1 AND MONTH(rem_fecha_registro)='".$m."'",$conexion));
									if($m==$_GET["m"])
										echo '<a style="font-weight:bold;">'.$meses[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';
									else
										echo '<a href="lab-remisiones.php?m='.$m.'&a='.$_GET["a"].'&u='.$_GET["u"].'">'.$meses[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';	
									$m++;
								}
								?>
								</div>
								
								<?php
								if($_GET["msg"]==1){
								?>
									<p style="color: darkblue; font-size: 20px;">La remisión actual ha sido enviada al cliente y al contacto por email.</p>
								<?php
								}
								?>
                                
								<p>
									<a href="lab-remisiones-agregar.php" class="btn btn-danger"><i class="fa fa-plus-square"></i> Agregar nuevo</a>
									
									<a href="lab-remisiones-escoger.php" class="btn btn-warning"><i class="fa fa-print"></i> Imprimir varias remisiones</a>
								</p>
								
                                <div class="table-responsive">
                                    <table id="zero_config" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
												<th></th>
												<th>ID</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
												<th>Equipo</th>
												<th>REF</th>
												<th>Serial</th>
												<th>Sucursal</th>
												<th>Estado</th>
												<th>Certificado</th>
												<th>Foto</th>
												<th>Vencimiento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php
											$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
											
											$filtro = '';
											if(is_numeric($_GET["idRem"])){$filtro = " AND rem_id='".$_GET["idRem"]."'";}
											if(is_numeric($_GET["cliente"])){$filtro = " AND rem_cliente='".$_GET["cliente"]."'";}
											
											if($_GET["busqueda"]!=""){$filtro = " AND ".$_GET["campo"]."='".$_GET["busqueda"]."'";}
											
											if(is_numeric($_GET["a"])){$filtro .= " AND YEAR(rem_fecha_registro)='".$_GET["a"]."'";}
											if(is_numeric($_GET["m"])){$filtro .= " AND MONTH(rem_fecha_registro)='".$_GET["m"]."'";}
											
											if($datosUsuarioActual[3] == 14){

												$consulta = mysql_query("SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id=rem_id $filtro
												ORDER BY rem_id DESC
												",$conexion);
												
											}else{

												$consulta = mysql_query("SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id=rem_id $filtro
												ORDER BY rem_id DESC
												LIMIT 0, 100
												",$conexion);
											}

											$conRegistros = 1;
											
											while($resultado = mysql_fetch_array($consulta)){

												//Solo Vendedores externos
												if($datosUsuarioActual[3] == 14){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resultado['cli_zona']."'",$conexion));
													if($numZ==0) continue;
												}
												
												$remisionesEnt = mysql_fetch_array(mysql_query("SELECT DATEDIFF(now(), rem_fecha_registro), rem_id FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."'",$conexion));
												
												$remisionesSeg = mysql_num_rows(mysql_query("SELECT * FROM remisiones_seguimiento 
												WHERE remseg_id_remisiones='".$resultado['rem_id']."'",$conexion));
												
												$colorEntrada = 'info';
												
												if($resultado['rem_estado']==1){
													if($remisionesEnt[0]>=3 and $remisionesSeg>0){
														$colorEntrada = 'warning';
													}elseif($remisionesEnt[0]>=3 and $remisionesSeg==0){
														$colorEntrada = 'danger';
													}
												}
												
												$html = '<span class="label label-'.$colorEntrada.'">Entrada</a>';
												
												if($resultado['rem_estado']==2){
													$html = '<span class="label label-success">Salida</span>';
												}
												
												$certificado = '<span class="label label-success">Vigente</a>';
												
												if($resultado['rem_estado_certificado']==2){
													$certificado = '<span class="label label-danger">Vencido</span>';
												}
												//Para obtener la fecha
												$camposRemision = mysql_fetch_array(mysql_query("SELECT 
												DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
												DAY(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH))
												FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."'",$conexion));
												
												
														$msjFoto = '-';
														if($resultado['rem_foto_certificado']!=""){
															$msjFoto = '<a href="../../../usuarios/files/adjuntos/'.$resultado['rem_foto_certificado'].'" target="_blank">OK</a>';
														}
														
											?>
                                            <tr style="font-size: 12px;">
                                                <td><?=$conRegistros;?></td>
												
												<td>
													<div class="btn-group">
														<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Opciones
														</button>
														<div class="dropdown-menu animated slideInUp">
															<a class="dropdown-item" href="lab-remisiones-editar.php?id=<?=$resultado['rem_id'];?>">Ver detalles</a>
															
															<a class="dropdown-item" href="#" onClick="if(!confirm('Desea eliminar este registro?')){return false;}">Eliminar</a>
															
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" href="sql.php?get=32&id=<?=$resultado['rem_id'];?>&cte=<?=$resultado['rem_cliente'];?>&contacto=<?=$resultado['rem_contacto'];?>">Enviar remisión actual</a>
															
															<div class="dropdown-divider"></div>
															<?php if($resultado['rem_generar_certificado']==1 and $resultado['rem_estado']==1){?>
																<a class="dropdown-item" href="sql.php?get=30&id=<?=$resultado['rem_id'];?>" onClick="if(!confirm('Desea generar salida a este equipo?')){return false;}" target="_blank">Generar salida</a>
															<?php } if($resultado['rem_generar_certificado']!=1){?>
																<a class="dropdown-item" href="sql.php?get=31&id=<?=$resultado['rem_id'];?>&cte=<?=$resultado['rem_cliente'];?>" onClick="if(!confirm('Desea generar certificado a este equipo?')){return false;}">Generar certificado</a>
															<?php }?>
															
															<div class="dropdown-divider"></div>
															<a class="dropdown-item" href="lab-remisiones-imprimir.php?id=<?=$resultado['rem_id'];?>&estado=1" target="_blank">Remisión Entrada</a>
															
															<?php if($resultado['rem_estado']==2){?>
															<a class="dropdown-item" href="lab-remisiones-imprimir.php?id=<?=$resultado['rem_id'];?>&estado=2" target="_blank">Remisión Salida</a>
															<?php }?>
															
															<?php if($resultado['rem_generar_certificado']==1){?>
															<a class="dropdown-item" href="lab-certificado-imprimir.php?id=<?=$resultado['rem_id'];?>" target="_blank">Certificado</a>
															<?php }?>
															
															<div class="dropdown-divider"></div>
															<?php if($resultado['rem_archivo']!=""){?>
															<a class="dropdown-item" href="../../../usuarios/files/adjuntos/<?=$resultado['rem_archivo'];?>" target="_blank">Ver imagen</a>
															<?php }?>
															
														</div>
													</div>
												</td>
												
												<td><a href="lab-remisiones-editar.php?id=<?=$resultado['rem_id'];?>"><?=$resultado['rem_id'];?></a></td>
												
												<td><?=$resultado['rem_fecha'];?></td>
                                                
												<td><a href="../../../usuarios/clientes-editar.php?id=<?php echo $resultado['cli_id'];?>" target="_blank"><?php echo $resultado['cli_nombre'];?></a></td>
												
												<td>
													<?php if($resultado['rem_archivo']!=""){echo '<img src="../../../usuarios/files/adjuntos/'.$resultado["rem_archivo"].'" width="20">';}?>
													<?=$resultado['rem_equipo'];?>
												</td>
												<td><?=$resultado['rem_referencia'];?></td>
												<td><?=$resultado['rem_serial'];?></td>
												<td><?=$resultado['sucp_nombre'];?></td>
												<td><?=$html;?></td>
												<td><?=$certificado;?></td>
												<td><?=$msjFoto;?></td>
												<td><?=$camposRemision[3]." DE ".$meses[$camposRemision[4]]." DE ".$camposRemision[5];?></td>
                                                
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