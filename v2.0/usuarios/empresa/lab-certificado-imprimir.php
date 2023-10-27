<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 240;
$tituloPagina = "Cotización";
//include("verificar-paginas.php");

$consultaRemision=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN clientes ON cli_id=rem_cliente
INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='" . $_GET["id"] . "'");
$remision = mysqli_fetch_array($consultaRemision, MYSQLI_BOTH);

$consultaAnulado = mysqli_query($conexionBdPrincipal,"SELECT * FROM certificados_anulados WHERE certanu_id_certificado='".$_GET["id"]."'");
$numAnulado=mysqli_num_rows($consultaAnulado);
$version="";
if($numAnulado>0){
	$version=" v".($numAnulado+1);
}

$ConsultaCampoRemision=mysqli_query($conexionBdPrincipal,"SELECT 
DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
DAY(DATE_ADD(rem_fecha, INTERVAL '" . $remision['rem_tiempo_certificado'] . "' MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL '" . $remision['rem_tiempo_certificado'] . "' MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL '" . $remision['rem_tiempo_certificado'] . "' MONTH))
FROM remisiones 
WHERE rem_id='" . $_GET["id"] . "'");
$camposRemision = mysqli_fetch_array($ConsultaCampoRemision, MYSQLI_BOTH);

$estadosCertificados = array("", "ACEPTABLE", "VENCIDO", "PROVICIONAL");

$meses = array("", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");

switch ($remision['rem_tipo_equipo']) {
	case 1:
		$pixeles1 = 0;
		$pixeles2 = 1;
		$hojas1 = 'Hoja 1 de 2';
		break;
	case 2:
		$pixeles1 = 0;
		$pixeles2 = 10;
		$hojas1 = 'Hoja 1 de 2';
		break;
	case 3:
		$pixeles1 = 20;
		$pixeles2 = 1;
		$hojas1 = 'Hoja 1 de 2';
		break;
}
?>
<link href="../../dist/css/style.min.css" rel="stylesheet">


<style type="text/css">
	#saltoPagina {
		PAGE-BREAK-AFTER: always;
	}
</style>


</head>

	<?php if($_GET['cp'] == 1){?>

		<style type="text/css">
			body {
				 background-image: url("copia.png");
				 background-color: #cccccc;
				}
		</style>

	<?php }?>	
	

	<body style="color: black;">


	<table style="width:100%; height: 120px;" border="1" align="center" rules="all">
		<tr>
			<td width="30%"><img src="logonuevo.png" width="250"></td>
			<td width="50%" style="font-size: 20px; font-weight: bold;" align="center">F- CERTIFICADO DE AJUSTE</td>
			<td width="20%">
				Código: O-F-04<br>
				Versión: 04<br>
				Edición: 12/02/2018<br>
				Copia controlada<br>
				<?= $hojas1; ?>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>

	<table style="width:100%; height: 120px;" border="0">
		<tr>
			<td style="width: 70%;" align="left" ;>
				<h2 style="color: darkblue;">SERVICIO DE LABORATORIO<br>OPTICOMECÁNICO Y ELECTRÓNICO</h2>
			</td>

			<td style="width: 30%; border-radius: 10px; background-color: lightgray;" align="center" ;>
				<h3>CERTIFICADO<br>
					NO. <?= "C" . $remision['rem_id'].$version; ?></h3>
			</td>
		</tr>

	</table>
	<p>&nbsp;</p>

	<table style="width:100%; font-size: 20px;" border="1" rules="all" align="center">
		<tr>
			<td align="center" width="50%">
				<table style="width:100%;" border="0" align="center">
					<tr>
						<td><strong>INSTRUMENTO</strong>:</td>
						<td><?= $remision['rem_equipo']; ?></td>
					</tr>
					<tr>
						<td><strong>MARCA</strong>:</td>
						<td><?= $remision['rem_marca']; ?></td>
					</tr>
					<tr>
						<td><strong>REFERENCIA</strong>:</td>
						<td><?= $remision['rem_referencia']; ?></td>
					</tr>

					<?php
					//PARA ESTACIÓN
					if ($remision['rem_tipo_equipo'] == 1) { ?>
						<tr>
							<td><strong>PRECISIÓN ANGULAR R</strong>:</td>
							<td><?= $remision['rem_precision_angular']; ?>"</td>
						</tr>
						<tr>
							<td><strong>PRECISIÓN A DISTANCIA</strong>:</td>
							<td><?= $remision['rem_precision_distancia']; ?></td>
						</tr>
					<?php } ?>

					<?php
					//PARA TEODOLITO
					if ($remision['rem_tipo_equipo'] == 2) { ?>
						<tr>
							<td><strong>PRECISIÓN ANGULAR</strong>:</td>
							<td><?= $remision['rem_precision_angular']; ?>"</td>
						</tr>
					<?php } ?>


					<tr>
						<td><strong>SERIAL</strong>:</td>
						<td><?= $remision['rem_serial']; ?></td>
					</tr>
				</table>
			</td>

			<td align="center" width="50%">
				<table style="width:100%;" border="0" align="center">
					<tr>
						<td><strong>FECHA DE REVISIÓN</strong>:</td>
						<td><?= $camposRemision[0] . " DE " . $meses[$camposRemision[1]] . " DE " . $camposRemision[2]; ?></td>
					</tr>
					<tr>
						<td><strong>SUGERIMOS NUEVA REVISIÓN</strong>:</td>
						<td><?= $camposRemision[3] . " DE " . $meses[$camposRemision[4]] . " DE " . $camposRemision[5]; ?></td>
					</tr>
					<tr>
						<td><strong>CLIENTE</strong>:</td>
						<td><?= strtoupper($remision['cli_nombre']); ?></td>
					</tr>
					<tr>
						<td><strong>CC</strong>:</td>
						<td><?= $remision['cli_usuario']; ?></td>
					</tr>
					<tr>
						<td><strong>DIRECCIÓN</strong>:</td>
						<td><?= $remision['cli_direccion']; ?></td>
					</tr>
					<tr>
						<td><strong>CIUDAD</strong>:</td>
						<td><?= $remision['ciu_nombre'] . ", " . $remision['dep_nombre']; ?></td>
					</tr>
					<tr>
						<td><strong>CELULAR</strong>:</td>
						<td><?= $remision['cli_celular']; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<?php
	//PARA NIVELES
	if ($remision['rem_tipo_equipo'] == 3 or $remision['rem_tipo_equipo'] == 5) { ?>
		<table style="width:100%; font-size: 20px;" border="1" rules="all" align="center">
			<tr>
				<td align="center" width="50%">
					<table style="width:100%; padding: 10px; font-size: 18px;" border="0" rules="groups" align="center">
						<tr align="center">
							<td align="center" colspan="2">
								<h4 style="color: midnightblue;" align="center">DATOS DE TRAZABILIDAD EQUIPO PATRON UTILAZADO PARA REALIZAR EL AJUSTE</h4>
							</td>
						</tr>
						<tr>
							<td>
								<h4 style="color: midnightblue;">PATRON N°1:</h4>
								<p>LABORATORIO DE CALIBRACION DE EQUIPOS TOPOGRAFICOS- LACET DEL CDHC-SENA EXPIDE CERTIFICADO DE CALIBRACION  N° CAL-2023-0006 PARA NIVEL TOPOGRAFICO MARCA GEOMAX REFERENCIA ZOOM ZAL232 SERIAL:  214232213376.</p>
								<h4 style="color: midnightblue;">PATRON N°2:</h4>
								<p>LABORATORIO DE METROLOGIA ICONTEC EXPIDE CERTIFICADO N°56959 PARA CINTA METRICA MARCA STRAPPING REFERENCIA QK-5022.</p>
							</td>
						</tr>
					</table>
				</td>
	
				<td align="center" width="50%">
					<table style="width:100%; padding: 10px; font-size: 18px;" border="0" rules="groups" align="center">
						<tr align="center">
							<td align="center" colspan="2">
								<h4 style="color: midnightblue;" align="center">METODO DE AJUSTE UTILIZADO</h4>
							</td>
						</tr>
						<tr>
							<td>
								<p>SUSTITUCION O (TRANSFERENCIA) MEDIANTE EL CUAL SE ESTABLECE CON PATRON DE TRABAJO LAS MEDIDAS MATERIALIZADAS DE REFERENCIA, LAS CUALES SERAN COMPARADAS CON LOS VALORES DEL EQUIPO A REALIZAR EL AJUSTE  PARA POSTERIORMENTE APLICAR LA CORRECCION DE ACUERDO CON LOS PARAMENTROS DE  TOLERANCIA ESTABLECIDOS POR EL FABRICANTE.</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">INSPECCIÓN OPTICOMECÁNICA</h4>
				</td>
			</tr>
			<tr>
				<td>

					BASE NIVELANTE<br>
					NIVELES TABULARES Y ESFERICOS<br>
					HORIZONTALIDAD<br>
					OPTICA GENERAL<br>
					MOVIMIENTOS TANGENCIALES
				</td>

				<td align="center">
					AJUSTE Y VERIFICACIÓN<br>
					GENERAL DE FUNCIONES
					<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">INSPECCIÓN DE CONTROL DE COLIMADOR DE<br>CUATRO TUBOS MODELO F420-4TA</h4>
				</td>
			</tr>
			<tr>
				<td>
					COMPENSADOR OPTICOMECÁNICO<br>
					COMPENSADOR ELECTRÓNICO<br>
					HORIZONTALIDAD DE RETICULA
				</td>

				<td align="center">
					AJUSTE Y VERIFICACIÓN<br>
					GENERAL DE FUNCIONES
					<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

				</td>
			</tr>
		</table>
	<?php } ?>

	<?php
	//PARA ESTACIÓN Y TEODOLITO
	if ($remision['rem_tipo_equipo'] == 1 or $remision['rem_tipo_equipo'] == 2) { ?>
		<table style="width:100%; font-size: 20px;" border="1" rules="all" align="center">
			<tr>
				<td align="center" width="50%">
					<table style="width:100%; padding: 10px; font-size: 18px;" border="0" rules="groups" align="center">
						<tr align="center">
							<td align="center" colspan="2">
								<h4 style="color: midnightblue;" align="center">DATOS DE TRAZABILIDAD EQUIPO PATRON UTILAZADO PARA REALIZAR EL AJUSTE</h4>
							</td>
						</tr>
						<tr>
							<td>
								<p>SURVEY RENTAL & SALES SAC EXPIDE CERTIFICADO DE CALIBRACION  N° 25583-23 PARA ESTACION TOTAL MARCA GEOMAX REFERENCIA ZOOM 25 1” SERIAL:  2839480.</p>
							</td>
						</tr>
					</table>
				</td>
	
				<td align="center" width="50%">
					<table style="width:100%; padding: 10px; font-size: 18px;" border="0" rules="groups" align="center">
						<tr align="center">
							<td align="center" colspan="2">
								<h4 style="color: midnightblue;" align="center">METODO DE AJUSTE UTILIZADO</h4>
							</td>
						</tr>
						<tr>
							<td>
								<p>SUSTITUCION O (TRANSFERENCIA) MEDIANTE EL CUAL SE ESTABLECE CON PATRON DE TRABAJO LAS MEDIDAS MATERIALIZADAS DE REFERENCIA, LAS CUALES SERAN COMPARADAS CON LOS VALORES DEL EQUIPO A REALIZAR EL AJUSTE PARA POSTERIORMENTE APLICAR LA CORRECCION DE ACUERDO CON LOS PARAMENTROS DE  TOLERANCIA ESTABLECIDOS POR EL FABRICANTE.</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">INSPECCIÓN OPTICOMECÁNICA</h4>
				</td>
			</tr>
			<tr>
				<td>
					BASE NIVELANTE<br>
					NIVELES TABULARES Y ESFERICOS<br>
					VERTICALIDAD<br>
					OPTICA GENERAL<br>
					EJE VERTICAL Y HORIZONTAL<br>
					FRENOS Y MOVIMIENTOS TANGENCIALES<br>
					PLOMADA OPTICA Y/O LASER
				</td>

				<td align="center">
					AJUSTE Y VERIFICACIÓN<br>
					GENERAL DE FUNCIONES
					<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">INSPECCIÓN Y AJUSTE SISTEMA ANGULAR</h4>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%; padding: 20px; height: 80px;" border="0">
						<tr>
							<td width="50%">
								<table style="width:70%;" align="center" border="0">
									<tr>
										<td colspan="4">
											<h4 style="color: midnightblue;">INSPECCIÓN DE ENTRADA</h4>
										</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (VERTICAL D)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vd_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vd_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vd_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (HORIZONTAL D)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hd_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hd_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hd_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (VERTICAL I)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vi_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vi_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1vi_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (HORIZONTAL I)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hi_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hi_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p1hi_segundos']; ?>"</td>
									</tr>

									<?php
										$sumaGradosV1 = ($remision['rem_p1vd_grados'] + $remision['rem_p1vi_grados']);
										$sumaMinutosV1 = ($remision['rem_p1vd_minutos'] + $remision['rem_p1vi_minutos']);
										$sumaSegundosV1 = ($remision['rem_p1vd_segundos'] + $remision['rem_p1vi_segundos']);

										$gradosV1 = (359 - $sumaGradosV1);
										$minutosV1 = (59 - $sumaMinutosV1);
										$segundosV1 = (60 - $sumaSegundosV1);

										$sumaGradosH1 = ($remision['rem_p1hd_grados'] + $remision['rem_p1hi_grados']);
										$sumaMinutosH1 = ($remision['rem_p1hd_minutos'] + $remision['rem_p1hi_minutos']);
										$sumaSegundosH1 = ($remision['rem_p1hd_segundos'] + $remision['rem_p1hi_segundos']);

										$gradosH1 = (179 - $sumaGradosH1);
										$minutosH1 = (59 - $sumaMinutosH1);
										$segundosH1 = (60 - $sumaSegundosH1);
									?>

									<tr>
										<td style="width:52%;">ERROR OBSERVADO V</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($gradosV1 > 0 and $gradosV1 != 360) echo $gradosV1;
											else echo "00"; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($minutosV1 > 0 and $minutosV1 != 59) echo $minutosV1;
											else echo "00"; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $segundosV1; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">ERROR OBSERVADO H</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($gradosH1 > 0 and $gradosH1 != 180) echo $gradosH1;
											else echo "00"; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($minutosH1 > 0 and $minutosH1 != 59) echo $minutosH1;
											else echo "00"; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $sumaSegundosH1; ?>"</td>
									</tr>
								</table>
							</td>

							<td width="50%">
								<table style="width:70%;" align="center" border="0">
									<tr>
										<td colspan="4">
											<h4 style="color: midnightblue;">INSPECCIÓN DE SALIDA</h4>
										</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (VERTICAL D)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vd_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vd_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vd_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (HORIZONTAL D)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hd_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hd_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hd_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (VERTICAL I)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vi_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vi_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2vi_segundos']; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">POSICIÓN 1 (HORIZONTAL I)</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hi_grados']; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hi_minutos']; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $remision['rem_p2hi_segundos']; ?>"</td>
									</tr>

									<?php
										$sumaGradosV2 = ($remision['rem_p2vd_grados'] + $remision['rem_p2vi_grados']);
										$sumaMinutosV2 = ($remision['rem_p2vd_minutos'] + $remision['rem_p2vi_minutos']);
										$sumaSegundosV2 = ($remision['rem_p2vd_segundos'] + $remision['rem_p2vi_segundos']);

										$gradosV2 = (359 - $sumaGradosV2);
										$minutosV2 = (59 - $sumaMinutosV2);
										$segundosV2 = (60 - $sumaSegundosV2);

										$sumaGradosH2 = ($remision['rem_p2hd_grados'] + $remision['rem_p2hi_grados']);
										$sumaMinutosH2 = ($remision['rem_p2hd_minutos'] + $remision['rem_p2hi_minutos']);
										$sumaSegundosH2 = ($remision['rem_p2hd_segundos'] + $remision['rem_p2hi_segundos']);

										$gradosH2 = (179 - $sumaGradosH2);
										$minutosH2 = (59 - $sumaMinutosH2);
										$segundosH2 = (60 - $sumaSegundosH2);
									?>

									<tr>
										<td style="width:52%;">ERROR OBSERVADO V</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($gradosV2 > 0 and $gradosV2 != 360) echo $gradosV2;
											else echo "00"; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($minutosV2 > 0 and $minutosV2 != 59) echo $minutosV2;
											else echo "00"; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $segundosV2; ?>"</td>
									</tr>
									<tr>
										<td style="width:52%;">ERROR OBSERVADO H</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($gradosH2 > 0 and $gradosH2 != 180) echo $gradosH2;
											else echo "00"; ?>°</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?php if ($minutosH2 > 0 and $minutosH2 != 59) echo $minutosH2;
											else echo "00"; ?>'</td>
										<td style="width:16%; border: thin; border-style: solid;" align="center"><?= $sumaSegundosH2; ?>"</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="0" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">MENSURADO<br>N°1 PRISMA; N°2 LASERA; N°3 DIANA</h4>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%; padding: 20px; height: 80px;" border="0">
						<tr>
							<td width="50%">
								<table style="width:70%;" align="center" border="1">
									<tr>
										<td style="width:25%;" align="center">MENSURANDO</td>
										<td style="width:25%;" align="center">PATRON</td>
										<td style="width:25%;" align="center">EQUIPO AJUSTADO</td>
										<td style="width:25%;" align="center">DIFERENCIA</td>
									</tr>
									<tr>
										<td align="center">N°1</td>
										<td align="center"><?= $remision['rem_n1_patron']; ?></td>
										<td align="center"><?= $remision['rem_n1_equipo']; ?></td>
										<td align="center"><?= $remision['rem_n1_diferencia']; ?></td>
									</tr>
									<tr>
										<td align="center">N°2</td>
										<td align="center"><?= $remision['rem_n2_patron']; ?></td>
										<td align="center"><?= $remision['rem_n2_equipo']; ?></td>
										<td align="center"><?= $remision['rem_n2_diferencia']; ?></td>
									</tr>
									<tr>
										<td align="center">N°3</td>
										<td align="center"><?= $remision['rem_n3_patron']; ?></td>
										<td align="center"><?= $remision['rem_n3_equipo']; ?></td>
										<td align="center"><?= $remision['rem_n3_diferencia']; ?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php } ?>

	<?php
	//PARA NIVELES
	if ($remision['rem_tipo_equipo'] == 3 OR $remision['rem_tipo_equipo'] == 5) { ?>
		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">INSPECCION DEL SISTEMA DIFERENCIA DE ALTURA</h4>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%; padding: 20px; height: 80px;" border="0">
						<tr>
							<td width="50%">
								<table style="width:50%;" align="center" border="0">
									<tr>
										<td colspan="3">
											<h4 style="color: midnightblue;">INSPECCIÓN DE ENTRADA</h4>
										</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">LECTURA DE PUNTO A</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l1a']; ?></td>
										<td style="width:10%;"> m</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">LECTURA DE PUNTO B</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l1b']; ?></td>
										<td style="width:10%;"> m</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">DIFERENCIAL DE ALTURA</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l1c']; ?></td>
										<td style="width:10%;"> mm</td>
									</tr>
								</table>
							</td>

							<td width="50%">
								<table style="width:50%;" align="center" border="0">
									<tr>
										<td colspan="3">
											<h4 style="color: midnightblue;">INSPECCIÓN DE SALIDA</h4>
										</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">LECTURA DE PUNTO A</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l2a']; ?></td>
										<td style="width:10%;"> m</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">LECTURA DE PUNTO B</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l2b']; ?></td>
										<td style="width:10%;"> m</td>
									</tr>
									<tr>
										<td style="width:60%; border: thin; border-style: solid;" align="center">DIFERENCIAL DE ALTURA</td>
										<td style="width:30%; border: thin; border-style: solid;" align="center"><?= $remision['rem_l2c']; ?></td>
										<td style="width:10%;"> mm</td>
									</tr>
								</table>
							</td>

							<!-- <td width="40%">
								<table style="width:100%;" border="0">
									<tr>
										<td>ERROR DETECTADO</td>
										<td style="border: thin; border-style: solid;" align="center"><?= $remision['rem_error_detectado']; ?></td>
										<td> mm</td>
									</tr>
								</table>
							</td> -->
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php } ?>


	<?php
	//PARA GPS
	if ($remision['rem_tipo_equipo'] == 4 ) { ?>
	<table style="width:100%; height:250px; font-size: 18px;" border="1" rules="groups" align="center">
		<tbody>
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">COMPROBACIÓN DE POSICIONAMIENTO HORIZONTAL Y VERTICAL<br>
						PATRÓN DE REFERENCIA PUNTO GEODÉSICO BSIB CON DATOS 4,6624449°N -74,094298°W ALTURA ELIPSOIDAL 2573,717008</h4>
				</td>
			</tr>
			<tr>
				<td>
					ERROR RMS<br>
					ALTÍMETRO<br>
					PROYECCIÓN<br>
					RELOJ DE PRECISIÓN<br>
					TIEMPO DE RECEPCIÓN<br>
					RECEPCIÓN DE SATÉLITES<br>

				</td>

				<td align="center">
					VERIFICACIÓN GENERAL DE FUNCIONES
					<h3 style="color: midnightblue;">ACEPTABLE</h3>

				</td>
			</tr>
		</tbody>
	</table>
	<?php } ?>



	<?php
	//PARA ESTACIÓN Y TEODOLITO
	if ($remision['rem_tipo_equipo'] == 1 or $remision['rem_tipo_equipo'] == 2 or $remision['rem_tipo_equipo'] == 3) { ?>


		<div style="margin-top: <?= $pixeles2; ?>px;">
			<img src="../../assets/images/piePagina.jpg" width="1400">
		</div>

		<div id="saltoPagina"></div>








		<table style="width:100%; height: 130px;" border="1" align="center" rules="all">
			<tr>
				<td width="30%"><img src="logonuevo.png" width="250"></td>
				<td width="50%" style="font-size: 20px; font-weight: bold;" align="center">F- CERTIFICADO DE AJUSTE</td>
				<td width="20%">
					Código: O-F-04<br>
					Versión: 04<br>
					Edición: 12/02/2018<br>
					Copia controlada<br>
					Hoja 2 de 2
				</td>
			</tr>
		</table>
		<p>&nbsp;</p>

		<table style="width:100%; height: 120px;" border="0">
			<tr>
				<td style="width: 70%;" align="left" ;>
					<h2 style="color: darkblue;">SERVICIO DE LABORATORIO<br>OPTICOMECÁNICO Y ELECTRÓNICO</h2>
				</td>

				<td style="width: 30%; border-radius: 10px; background-color: lightgray;" align="center" ;>
					<h3>CERTIFICADO<br>
						NO. <?= "C" . $remision['rem_id'].$version; ?></h3>
				</td>
			</tr>

		</table>
		<p>&nbsp;</p>

		<table style="width:100%; font-size: 15px;" border="1" rules="all" align="center">
			<tr>
				<td align="center" width="50%">
					<table style="width:100%; height: 100px;" border="0" align="center">
						<tr>
							<td><strong>INSTRUMENTO</strong>:</td>
							<td><?= $remision['rem_equipo']; ?></td>
						</tr>
						<tr>
							<td><strong>MARCA</strong>:</td>
							<td><?= $remision['rem_marca']; ?></td>
						</tr>
						<tr>
							<td><strong>REFERENCIA</strong>:</td>
							<td><?= $remision['rem_referencia']; ?></td>
						</tr>

						<tr>
							<td><strong>SERIAL</strong>:</td>
							<td><?= $remision['rem_serial']; ?></td>
						</tr>
					</table>
				</td>

				<td align="center" width="50%">
					<table style="width:100%; height: 60px;" border="0" align="center">
						<tr>
							<td><strong>FECHA DE REVISIÓN</strong>:</td>
							<td><?= $camposRemision[0] . " DE " . $meses[$camposRemision[1]] . " DE " . $camposRemision[2]; ?></td>
						</tr>
						<tr>
							<td><strong>SUGERIMOS NUEVA REVISIÓN</strong>:</td>
							<td><?= $camposRemision[3] . " DE " . $meses[$camposRemision[4]] . " DE " . $camposRemision[5]; ?></td>
						</tr>
						<tr>
							<td><strong>CLIENTE</strong>:</td>
							<td><?= strtoupper($remision['cli_nombre']); ?></td>
						</tr>
						<tr>
							<td><strong>CC</strong>:</td>
							<td><?= $remision['cli_usuario']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table style="width:100%; padding: 10px; font-size: 18px;" border="1" rules="groups" align="center">
			<tr align="center">
				<td align="center" colspan="2">
					<h4 style="color: midnightblue;" align="center">CONDICIONES PRESENTES EN EL LABORATORIO EN EL PROCESO DE AJUSTE</h4>
				</td>
			</tr>
			<tr>
				<td>
					TEMPERATURA AMBIENTE MINIMA:  22°C<br>
					HUMEDAD RELATIVA MINIMA: 63%<br>
					PRESION ATMOSFERICA: 760 mmHg
				</td>

				<td align="center">
					TEMPERATURA MAXIMA: 25°C<br>
					HUMEDAD RELATIVA MAXIMA 69%
				</td>
			</tr>
		</table>

		<?php
		//PARA ESTACIÓN Y TEODOLITO
		if ($remision['rem_tipo_equipo'] == 1 or $remision['rem_tipo_equipo'] == 2) { ?>
			<table style="width:100%; font-size: 18px;" border="1" rules="groups" align="center">
				<tr align="center">
					<td align="center" colspan="2">
						<h4 style="color: midnightblue;" align="center">INSPECCIÓN DEL SISTEMA DE MEDIDA ANGULAR, COLIMADOR DE CUATRO TUBOS<br>
							MODELO S/N:12-0663</h4>
					</td>
				</tr>
				<tr>
					<td>
						COMPENSADOR OPTICOMECÁNICO<br>
						COMPENSADOR ELECTRÓNICO<br>
						<strong>AJUSTE Y VERIFICACIÓN</strong>
					</td>

					<td align="center">
						AJUSTE Y VERIFICACIÓN<br>
						GENERAL DE FUNCIONES
						<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

					</td>
				</tr>
			</table>

			<table style="width:100%; font-size: 18px;" border="1" rules="groups" align="center">
				<tr align="center">
					<td align="center" colspan="2">
						<h4 style="color: midnightblue;" align="center">INSPECCIÓN EDM SOBRE LÍNEA BASE 17.006 m<br>
							(DISTANCIOMETRO) CONDICIONES: TEMPERATURA 25°C, PRESIÓN ATMOSFERICA 760mmhg,<br>
							CONSTANTE DEL PRISMA -30</h4>
					</td>
				</tr>
				<tr>
					<td>

						PRISMAS<br>
						MEDIDA DISTANCIA<br>
						CONSTANTE DEL PRISMA<br>
						CONSTANTE PPM
					</td>

					<td align="center">
						AJUSTE Y VERIFICACIÓN<br>
						GENERAL DE FUNCIONES
						<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

					</td>
				</tr>
			</table>

			<table style="width:100%; font-size: 18px;" border="1" rules="groups" align="center">
				<tr align="center">
					<td align="center" colspan="2">
						<h4 style="color: midnightblue;" align="center">CONTROLES Y VISUALIZACIÓN ELECTRÓNICA</h4>
					</td>
				</tr>

				<tr>
					<td>
						TECLADO<br>
						DISPLAY<br>
						ACCESO MEMORIA<br>
						BATERÍA<br>
						COMUNICACIÓN DISPOSITIVO EXTERNO
					</td>

					<td align="center">
						AJUSTE Y VERIFICACIÓN<br>
						GENERAL DE FUNCIONES
						<h3 style="color: midnightblue;"><?= $estadosCertificados[$remision['rem_estado_certificado']]; ?></h3>

					</td>
				</tr>
			</table>
		<?php } ?>
	<?php } ?>

	
	<p>&nbsp;</p>
	<?php
	//sólo para teodolito. Unos espacios más.
	if ($remision['rem_tipo_equipo'] == 3) { ?>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	<?php } ?>

	<table style="width:90%; padding: 10px; font-size: 18px;" border="0" align="center">
		<tr>
			<td style="font-weight: bold;">
				JMENDOZA EQUIPO SAS. CERTIFICA QUE EL<br>
				INSTRUMENTO SE ENTREGA EN OPTIMAS CONDICIONES<br>
				DE FUNCIONAMIENTO REALIZANDO EL AJUSTE Y/O<br>
				REPARACION (SEGÚN PROTOCOLO DE NORMA ISO<br>
				9001:2015 Y BASADOS EN PROCEDIMIENTOS DE NORMA<br>
				ISO 17123-3) Y QUE LOS ERRORES ENCONTRADOS AL<br>
				INGRESO DEL EQUIPO, HAN SIDO CORREGIDOS DE<br>
				ACUERDO CON LOS PARAMENTROS DE TOLERANCIA<br>
				ESTABLECIDOS POR EL FABRICANTE.
			</td>

			<td align="center" style="font-weight: bold;">
				<img src="ok.png" width="100"><br>
				<!--<p>&nbsp;</p><br><br>
				GEINER CUERVO MENDOZA<br>-->
				TÉCNICO JMEQUIPOS SAS
				<p style="text-align: left;">CERTIFICADO POR:<br>
				<?=strtoupper($remision['rem_supervisor'])?><br>
				SUPERVISOR DE LABORATORIO &emsp;&emsp;&emsp;&emsp;&emsp;FIRMA:<span style="border-top: 1px solid #000; width: 200px; display: inline-block;"></span></p>
				<p style="color: red; text-align: left;">Este certificado no es válido sin el simbolo de aceptación, no puede ser<br>reproducido parcialmente, excepto en su totalidad, con autorización<br>previa por escrito del servicio técnico  de JMEQUIPOS.</p>
				<br><br><span style="color: darkblue;">Verifique la validez de este certificado en<br>
					<a href="https://jmequipos.com/consultar-certificados.php" target="_blank">www.jmequipos.com/consultar-certificados.php</a></span>
			</td>
		</tr>
	</table>


	<div style="text-align: center; margin:4px; font-weight:bold; color:darkblue; font-size:17px; padding: 2px; background-color: #fff;">
		Recuerde nuestro sitio web <span style="font-size: 20px; text-decoration: underline;">www.jmequipos.com</span>, en la tienda virtual tenemos los productos con los mejores precios para usted.
	</div>


	<div style="margin-top: <?= $pixeles1; ?>px;">
		<img src="../../assets/images/piePagina.jpg" width="1400">
	</div>

</body>

<script type="application/javascript">
	print();
</script>

</html>