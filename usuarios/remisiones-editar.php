<?php 
include("sesion.php");
$idPagina = 245;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."' AND rem_id_empresa='".$idEmpresa."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);

$consultaAnulado = mysqli_query($conexionBdPrincipal,"SELECT * FROM certificados_anulados WHERE certanu_id_certificado='".$_GET["id"]."'");
$numAnulado=mysqli_num_rows($consultaAnulado);
$version="";
if($numAnulado>0 && $resultadoD['rem_generar_certificado']==1){
	$version='
		<div class="control-group">
			<label class="control-label">Versión del certificado</label>
			<div class="controls">
				<input type="text" class="span8" value="v'.($numAnulado+1).'" disabled>
			</div>
		</div>
	';
}
?>

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
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="remisiones.php">Remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid" style="margin-bottom: 10px;">
				<div class="span12">
					<div class="span6" style="text-align: left;">
						<a href="remisiones-cotizacion.php?id=<?=$_GET["id"];?>" class="btn btn-success">Cotización</a>
						<a href="remisiones-seguimiento.php?id=<?=$_GET["id"];?>" class="btn btn-warning">Seguimiento</a>
					</div>
					<div class="span6" style="text-align: right;">
						<a href="reportes/remisiones-imprimir.php?id=<?=$resultadoD['rem_id'];?>&estado=1" target="_blank" class="btn btn-success">Remisión entrada</a>
						
						<?php
							if($resultadoD['rem_estado']==2){
						?>
						<a href="reportes/remisiones-imprimir.php?id=<?=$resultadoD['rem_id'];?>&estado=2" target="_blank" class="btn btn-success">Remisión salida</a>
						<?php 
							}
							if($resultadoD['rem_generar_certificado']==1 and $resultadoD['rem_estado']==1){
						?>
						<a href="bd_update/salida-remision-actualizar.php?id=<?=$resultadoD['rem_id'];?>" onClick="if(!confirm('Desea generar salida a este equipo?')){return false;}" target="_blank" class="btn btn-success">Remisión salida</a>
						<?php 
							}
							$disabled="";
							if($resultadoD['rem_generar_certificado']!=1){
						?>
						<a href="bd_update/generar-certificado.php?id=<?=$resultadoD['rem_id'];?>&cte=<?=$resultadoD['rem_cliente'];?>" onClick="if(!confirm('Desea generar certificado a este equipo?')){return false;}" target="_blank" class="btn btn-success">Generar Certificado</a>
						<?php 
							}else{
								$disabled="disabled";
						?>
						<a href="reportes/certificado-imprimir.php?id=<?=$resultadoD['rem_id'];?>" target="_blank" class="btn btn-success">Certificado</a>
						<?php 
							}
							if($numAnulado==0 && $resultadoD['rem_certificado_anulado']!=1 && $resultadoD['rem_generar_certificado']==1){
						?>
						<a href="remisiones-certificado-anular.php?id=<?=$resultadoD['rem_id'];?>" class="btn btn-danger">Anular Certificado</a>
						<?php }?>
						
						<?php if(!empty($resultadoD['rem_archivo'])){?>
							<a href="files/adjuntos/<?=$resultadoD['rem_archivo'];?>" target="_blank" class="btn btn-info">Ver imagen</a>
						<?php }?>
					</div>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/remisiones-actualizar.php" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?=$_GET["id"];?>">

								<?php if($resultadoD['rem_archivo']!=""){ echo '<p><a href="bd_update/quitar-imagen-remision.php?id='.$resultadoD["rem_id"].'" title="Eliminar">X</a> <img src="files/adjuntos/'.$resultadoD["rem_archivo"].'" width="50"></p>';}?>

								<h4 class="card-title">Datos básicos</h4>
								<div class="control-group">
									<label class="control-label">ID</label>
									<div class="controls">
										<input type="text" class="span8" name="equipo" value="<?=$resultadoD['rem_id'];?>" readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="estado" disabled>
											<option value="1" <?php if($resultadoD['rem_estado']==1){echo "selected";} ?> >Entrada</option>
											<option value="2" <?php if($resultadoD['rem_estado']==2){echo "selected";} ?>>Salida</option>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span8" name="fecha" value="<?=$resultadoD['rem_fecha'];?>" <?=$disabled;?>>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Asesor</label>
									<div class="controls">
										<input type="text" class="span8" name="referencia" value="<?=$resultadoD['usr_nombre'];?>" readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Equipo</label>
									<div class="controls">
										<input type="text" class="span8" name="equipo" value="<?=$resultadoD['rem_equipo'];?>" readonly>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Referencia</label>
									<div class="controls">
										<input type="text" class="span8" name="referencia" value="<?=$resultadoD['rem_referencia'];?>" <?=$disabled;?>>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Marca</label>
									<div class="controls">
										<input type="text" class="span8" name="marca" value="<?=$resultadoD['rem_marca'];?>" <?=$disabled;?>>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Tipo de equipo</label>
									<div class="controls">
										<select  data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tipoEquipo" <?=$disabled;?>>
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

								<div class="control-group">
									<label class="control-label">Serial</label>
									<div class="controls">
										<input type="text" class="span8" name="serial" value="<?=$resultadoD['rem_serial'];?>" <?=$disabled;?>>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Nuevo o usado?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tiposEquipos" <?=$disabled;?>>
											<option value="2">--</option>
											<option value="1" <?php if($resultadoD['rem_tipos_equipos']==1){echo "selected";} ?>>Nuevo</option>
											<option value="2" <?php if($resultadoD['rem_tipos_equipos']==2){echo "selected";} ?>>Usado</option>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Precisión angular "</label>
									<div class="controls">
										<input type="text" class="span8" name="pAngular" value="<?=$resultadoD['rem_precision_angular'];?>" maxlength="1" <?=$disabled;?>>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Precisión a distancia</label>
									<div class="controls">
										<input type="text" class="span8" name="pDistancia" value="<?=$resultadoD['rem_precision_distancia'];?>" <?=$disabled;?>>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Tiempo de entrega</label>
									<div class="controls">
										<input type="text" class="span8" name="tiempoEntrega" value="<?=$resultadoD['rem_dias_entrega'];?>" <?=$disabled;?>>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Días para reclamar el equipo</label>
									<div class="controls">
										<input type="text" class="span8" name="tiempoReclamar" value="<?=$resultadoD['rem_dias_reclamar'];?>" <?=$disabled;?>>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Vigencia cerificado (meses)</label>
									<div class="controls">
										<input type="text" class="span8" name="vigenciaCerificado" value="<?=$resultadoD['rem_tiempo_certificado'];?>" <?=$disabled;?>>
									</div>
								</div>	
								
								<div class="control-group">
									<label class="control-label">Imagen Certificado</label>
									<div class="controls">
										<input type="file" class="span8" name="imgCertificado" <?=$disabled;?>>
										<?php
										$msjFoto = 'Sin foto.';
										if($resultadoD['rem_foto_certificado']!=""){
											$msjFoto = 'La foto está OK.';
										}
										echo $msjFoto;
										?>
									</div>
								</div>

								<?=$version;?>

								<div class="control-group">
									<label class="control-label">Supervisor de Laboratorio</label>
									<div class="controls">
										<input type="text" class="span8" name="supervisor" value="<?=$resultadoD['rem_supervisor'];?>" <?=$disabled;?>>
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
													<td><input value="<?=$resultadoD['rem_p1vd_grados'];?>" name="p1vd_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1vd_minutos'];?>" name="p1vd_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1vd_segundos'];?>" name="p1vd_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p1hd_grados'];?>" name="p1hd_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1hd_minutos'];?>" name="p1hd_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1hd_segundos'];?>" name="p1hd_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p1vi_grados'];?>" name="p1vi_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1vi_minutos'];?>" name="p1vi_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1vi_segundos'];?>" name="p1vi_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p1hi_grados'];?>" name="p1hi_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1hi_minutos'];?>" name="p1hi_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p1hi_segundos'];?>" name="p1hi_segundos" style="text-align: center;" <?=$disabled;?>></td>
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
													<td><input value="<?=$resultadoD['rem_p2vd_grados'];?>" name="p2vd_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2vd_minutos'];?>" name="p2vd_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2vd_segundos'];?>" name="p2vd_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p2hd_grados'];?>" name="p2hd_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2hd_minutos'];?>" name="p2hd_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2hd_segundos'];?>" name="p2hd_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p2vi_grados'];?>" name="p2vi_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2vi_minutos'];?>" name="p2vi_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2vi_segundos'];?>" name="p2vi_segundos" style="text-align: center;" <?=$disabled;?>></td>
												</tr>
												<tr align="center">
													<td><input value="<?=$resultadoD['rem_p2hi_grados'];?>" name="p2hi_grados" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2hi_minutos'];?>" name="p2hi_minutos" style="text-align: center;" <?=$disabled;?>></td>
													<td><input value="<?=$resultadoD['rem_p2hi_segundos'];?>" name="p2hi_segundos" style="text-align: center;" <?=$disabled;?>></td>
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
								<p>&nbsp;</p>

								<h4 style="color: darkblue;">MENSURADO<br>N°1 PRISMA; N°2 LASERA; N°3 DIANA</h4>
								<table style="width:100%; padding: 10px; margin-top: 30px;" border="0">
									<tr>
										<td>
											<table style="width:100%; padding: 10px;" border="1" rules="all">
												<tr style="color: darkblue;" align="center">
													<td>MENSURADO</td>
													<td>PATRON</td>
													<td>EQUIPO AJUSTADO</td>
													<td>DIFERENCIA</td>
												</tr>
												<?php
													$valor="";
													$valorN1P="11.591";
													$valorN2P="11.620";
													$valorN3P="11.605";
													$disabledM="";
													$mensaje="";
													if ($resultadoD['rem_tipo_equipo'] == 2) {
														$valor="N/A";
														$valorN1P="N/A";
														$valorN2P="N/A";
														$valorN3P="N/A";
														$disabledM="disabled";
														$mensaje="<span>Nota: NO Aplica en este equipo (N/A)</span>";
													}
												?>
												<tr align="center">
													<td>Nº1</td>
													<td><input name="n1_patron" style="text-align: center;" disabled value="<?=$valorN1P;?>"></td>
													<td><input value="<?=$resultadoD['rem_n1_equipo'];?>" name="n1_equipo" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
													<td><input value="<?=$resultadoD['rem_n1_diferencia'];?>" name="n1_diferencia" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
												</tr>
												<tr align="center">
													<td>Nº2</td>
													<td><input name="n2_patron" style="text-align: center;" disabled value="<?=$valorN2P;?>"></td>
													<td><input value="<?=$resultadoD['rem_n2_equipo'];?>" name="n2_equipo" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
													<td><input value="<?=$resultadoD['rem_n2_diferencia'];?>" name="n2_diferencia" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
												</tr>
												<tr align="center">
													<td>Nº3</td>
													<td><input name="n3_patron" style="text-align: center;" disabled value="<?=$valorN3P;?>"></td>
													<td><input value="<?=$resultadoD['rem_n3_equipo'];?>" name="n3_equipo" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
													<td><input value="<?=$resultadoD['rem_n3_diferencia'];?>" name="n3_diferencia" style="text-align: center;" <?=$disabled;?> <?=$disabledM;?> value="<?=$valor;?>"></td>
												</tr>
											</table>
											<?=$mensaje;?>
										</td>
									</tr>
								</table>
								<p>&nbsp;</p>
								<?php }
								
								//PARA NIVEL
								if($resultadoD['rem_tipo_equipo']==3 or $resultadoD['rem_tipo_equipo']==5){?>
								<h4 style="color: darkblue; margin-top: 10px;">LECTURAS</h4>
								<table style="width:100%; padding: 10px;" border="0">
									<tr>
										<td width="30%">
											<table style="width:100%;" border="0">
												<tr><td><strong>L1:</strong></td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1a'];?>" name="l1a" style="text-align: center;" <?=$disabled;?>></td><td> m</td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1b'];?>" name="l1b" style="text-align: center;" <?=$disabled;?>></td><td> m</td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l1c'];?>" name="l1c" style="text-align: center;" <?=$disabled;?>></td><td> mm</td></tr>
											</table>
										</td>

										<td width="30%">
											<table style="width:100%;" border="0">
												<tr><td><strong>L2:</strong></td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2a'];?>" name="l2a" style="text-align: center;" <?=$disabled;?>></td><td> m</td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2b'];?>" name="l2b" style="text-align: center;" <?=$disabled;?>></td><td> m</td></tr>
												<tr><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_l2c'];?>" name="l2c" style="text-align: center;" <?=$disabled;?>></td><td> mm</td></tr>
											</table>
										</td>

										<td width="40%">
											<table style="width:100%;" border="0">
												<tr><td>ERROR DETECTADO</td><td style="border: thin; border-style: solid;" align="center"><input value="<?=$resultadoD['rem_error_detectado'];?>" name="errorDetectado" style="text-align: center;" <?=$disabled;?>></td><td> mm</td></tr>
											</table>
										</td>
									</tr>
								</table>
								<p>&nbsp;</p>
								<?php }?>

								<div class="row">
									<div class="control-group">
										<label class="control-label">DETALLES</label>
										<div class="controls">
											<textarea rows="7" style="width: 100%;" class="tinymce-simple" name="detalles" placeholder="Escriba los detalles..." <?=$disabled;?>><?=$resultadoD['rem_detalles'];?></textarea>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">OBSERVACIONES DE ENTRADA</label>
										<div class="controls">
											<textarea rows="7" style="width: 100%;" class="tinymce-simple" name="descripcion" placeholder="Observaciones de entrada" <?=$disabled;?>><?=$resultadoD['rem_descripcion'];?></textarea>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">OBSERVACIONES DE SALIDA</label>
										<div class="controls">
											<textarea rows="7" style="width: 100%;" class="tinymce-simple" name="obsSalida" placeholder="Observaciones de salida" <?=$disabled;?>><?=$resultadoD['rem_observacion_salida'];?></textarea>
										</div>
									</div>
								
									<div class="control-group">
										<label class="control-label">Servicios</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" multiple="multiple" name="servicios[]" <?=$disabled;?>>
														<?php
														$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id_empresa='".$idEmpresa."'");
														while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
															
															$consultaOpciones=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_servicios WHERE remxs_id_remision='".$_GET["id"]."' AND remxs_id_servicio='".$datosSelect[0]."'");
															$numOpciones = mysqli_num_rows($consultaOpciones);	
														?>
														<option value="<?=$datosSelect[0];?>" <?php if($numOpciones > 0){echo "selected";} ?> ><?=strtoupper($datosSelect['serv_nombre']);?></option>
														<?php }?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Cliente</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" <?=$disabled;?>>
												<option>Cliente</option>
													<?php
													$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
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

								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php if($resultadoD['rem_generar_certificado']!=1){?>
										<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
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