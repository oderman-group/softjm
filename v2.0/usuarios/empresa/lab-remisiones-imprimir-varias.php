<?php
include("sesion.php"); //exit();
include("../compartido/head.php");
$idPagina = 247;
$tituloPagina = "Cotización";
//include("verificar-paginas.php");

$consultaRemision=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN clientes ON cli_id=rem_cliente
INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_POST["remisiones"][0]."'");
$remision = mysqli_fetch_array($consultaRemision, MYSQLI_BOTH);

$consultaContacto=mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$remision['rem_contacto']."'");
$contacto = mysqli_fetch_array($consultaContacto, MYSQLI_BOTH);

switch($_POST['estado']){
	case 1: 
		$tipo = "ENTRADA";
		
		$letra = "E";
		
		$descripcion = "ENTRADA AL DEPARTAMENTO TÉCNICO";
		
		$estado = "RECEPCIÓN";
		
		$mensaje1 = "TIEMPO DE ENTREGA: <strong>".$remision['rem_dias_entrega']."</strong>";
		
		$mensaje2 = "El cliente tendrá <strong>".$remision['rem_dias_reclamar']." días calendario</strong>  para reclamar su equipo, después de que el técnico le informe que el equipo está listo, pasado este tiempo <strong>JMENDOZA EQUIPOS</strong> no se hará responsable por daño o pérdida del instrumento. A demás de cobrar un bodegaje de 5.000 pesos semanales por estación o 3.000 pesos por teodolito y nivel.";
		
		$fecha = $remision['rem_fecha_registro'];
	break;
	
	case 2:
		$tipo = "SALIDA";
		
		$letra = "S";
		
		$descripcion = "SALIDA DEL DEPARTAMENTO TÉCNICO";
		
		$estado = "ENTREGA";
		
		$mensaje1 = "A partir de la fecha el cliente cuenta con un plazo de <strong>".$remision['rem_dias_reclamar']." días</strong> para presentar cualquier inconformidad con respecto al ajuste del equipo, si pasado este tiempo no se recibe ningún reporte se asumirá que trabaja en óptimas condiciones.";
		
		$mensaje2 = "El ciente tendrá <strong>".$remision['rem_dias_reclamar']." días calendario</strong>  para reclamar su equipo, después de que el técnico le informe que el equipo está listo, pasado este tiempo <strong>JMENDOZA EQUIPOS</strong> no se hará responsable por daño o pérdida del instrumento. A demás de cobrar un bodegaje de 5.000 pesos semanales por estación o 3.000 pesos por teodolito y nivel.";
		
		$fecha = $remision['rem_fecha_salida'];
	break;	
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

<body style="color: black;">
	
	<table style="width:90%; height: 120px;" border="0" align="center">
		<tr>
			<td><img src="../../assets/images/logojm.png" width="300"></td>
			<td>
				<table style="width:100%" border="1" rules="all">
					<tr align="center" style="font-weight: bold;">
						<td colspan="2">Edición</td>
						<td>Versión</td>
						<td>Código</td>
					</tr>
					<tr align="center">
						<td colspan="2">15-Sep-2018</td>
						<td>4</td>
						<td>O-F-10</td>
					</tr>
					<tr align="center">
						<td colspan="4"><strong>F-REMISIÓN</strong></td>
					</tr>
					<tr>
						<td colspan="2"><strong>Revisó:</strong> Alejandra Correal</td>
						<td colspan="2"><strong>Aprobó:</strong> Alejandra Correal</td>
					</tr>
				</table>
			</td>
		</tr>  
	</table>
	<p>&nbsp;</p>
	
	<table style="width:90%; height: 120px;" border="0" align="center">
		<tr>
			<td style="width: 70%;" align="left">
				<table style="width:100%" border="0">
					<tr>
						<td><strong>FECHA:</strong></td>
						<td><?=date("Y-m-d");?></td>
					</tr>
					
					<tr>
						<td><strong>CONCEPTO:</strong></td>
						<td><?=$estado;?></td>
					</tr>
					
					<tr>
						<td><strong>CLIENTE:</strong></td>
						<td><?=$remision['cli_nombre'];?></td>
					</tr>
					
					<tr>
						<td><strong>DESCRIPCIÓN:</strong></td>
						<td><?=$descripcion;?></td>
					</tr>

				</table>	
			</td>
			
			<td style="width: 30%; border-radius: 10px; background-color: lightgray;" align="center">
				<h3>REMISIÓN DE <?=$tipo;?><br>
				<?php
				$numero =(count($_POST["remisiones"]));
				$contador=0;
				while($contador<$numero){
					$consultaRemi=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["remisiones"][$contador]."'");
					$remi = mysqli_fetch_array($consultaRemi, MYSQLI_BOTH);
					
					if($_POST['estado']==2){
						mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_estado=2, rem_fecha_salida=now() 
						WHERE rem_id='".$_POST["remisiones"][$contador]."'");
					}
					
					
					if(($numero-$contador)>1)
						echo $letra."".$remi[0]." - ";
					else
						echo $letra."".$remi[0];
					$contador++;
				}?>
				</h3>	
			</td>
		</tr>

	</table>
	<p>&nbsp;</p>
	
	<table style="width:90%;" border="1" rules="all" align="center">
		<tr style="background-color: lightgray">
			<td align="center"><strong>DETALLE</strong></td>
		</tr> 
		<tr style="height: 100%;">
			<td>
				<div style="padding-left:10px;">
				<?php
				$numero =(count($_POST["remisiones"]));
				$contador=0;
				while($contador<$numero){
					$consultaRemi=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["remisiones"][$contador]."'");
					$remi = mysqli_fetch_array($consultaRemi, MYSQLI_BOTH);
				?>
				
					<p>
						<span style="font-weight: bold;">
						<?="<span style='color:darkblue;'>".$letra." ".$remi['rem_id'].".</span> ".strtoupper($remi['rem_equipo']." ".$remi['rem_referencia']." SERIAL ".$remi['rem_serial']);?><br>
						INCLUYE:<br>
						</span>
						<?=$remi['rem_detalles'];?>
					</p>	
				
				<?php 
					$contador++;
				}?>
				</div>
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	
	
	<table style="width:90%" border="1" rules="all" align="center">
		<tr style="background-color: lightgray">
			<td><strong>OBSERVACIONES:</strong></td>
			<td><strong>DATOS DEL CONTACTO:</strong></td>
		</tr>
		
		<tr style="height: 100%;">
			<td width="65%">
				<div style="padding-left:10px; ">
				<?php
				$numero =(count($_POST["remisiones"]));
				$contador=0;
				while($contador<$numero){
					$consultaRemi=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["remisiones"][$contador]."'");
					$remis = mysqli_fetch_array($consultaRemi, MYSQLI_BOTH);
					
					$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios");
					while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
						
						$consultaOpciones=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_servicios 
						WHERE remxs_id_remision='".$_POST["remisiones"][$contador]."' AND remxs_id_servicio='".$datosSelect[0]."'");
						$numOpciones = mysqli_num_rows($consultaOpciones);
						
						if($numOpciones>0){
							echo strtoupper($datosSelect['serv_nombre'])."<br>";	
						}
						
					}
						
					if($_POST["estado"]==1 and $remis['rem_descripcion']!=""){echo "<b>".$remis['rem_serial']."</b> - ".$remis['rem_descripcion']."<hr>";}
					
					if($_POST["estado"]==2 and $remis['rem_observacion_salida']!=""){echo "<b>".$remis['rem_serial']."</b> - ".$remis['rem_observacion_salida']."<hr>";}
			
					$contador++;
				}?>
				</div>	
				
			</td>
			<td width="35%">
				<div style="padding-left:10px;">
					<strong>NOMBRE:</strong> <?=$contacto['cont_nombre'];?><br>
					<strong>CELULAR:</strong> <?=$contacto['cont_celular'];?><br>
					<strong>CORREO:</strong> <?=$contacto['cont_email'];?>
				</div>	
			</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	
	<div style="margin-left: 70px; margin-right: 70px;">
	<p>&#9679; <?=$mensaje1;?></p>
	<p>&#9679; <?=$mensaje2;?></p>
	</div>	
	
	<p>&nbsp;</p><p>&nbsp;</p>
	
	<div style="margin-left: 130px;">
	<?php if($_POST['estado']==1){?>
	<table style="width:100%" border="0" align="center">
		<tr>
			<td><strong>RECIBE:</strong></td>
			<td><strong>ENTREGA:</strong></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	
	<table style="width:100%" border="0" align="center">
		<tr>
			<td>
				<!--<img src="../../assets/images/firmaAlex2.png">--><br>
				JMENDOZA EQUIPOS S.A.S<br>
				NIT: 900.374.255-1
			</td>
			<td><div style="border: thin; border-top-style: solid; width: 250px;"></div></td>
		</tr>
	</table>
	<?php }?>
	
	<?php if($_POST['estado']==2){?>
	<table style="width:100%" border="0" align="center">
		<tr>
			<td><strong>ENTREGA:</strong></td>
			<td><strong>RECIBE:</strong></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	
	<table style="width:100%" border="0" align="center">
		<tr>

			<td>
				<!--<img src="../../assets/images/firmaAlex2.png">--><br>
				JMENDOZA EQUIPOS S.A.S<br>
				NIT: 900.374.255-1
			</td>
			
			<td><div style="border: thin; border-top-style: solid; width: 250px;"></div></td>
		</tr>
	</table>
	<?php }?>
	</div>	
	
	<div align="center" style="border:thin; border-top-style: double; margin-top: 60px; font-size: 18px; padding: 5px;">
	CALLE 30B NO. 71 - 42 Teléfonos: (574) 3220619 EXT. 102 Celular y Whatsapp: 3107983526<br>
	www.jmequipos.com<br>
	laboratorio@jmequipos.com - auxlaboratorio@jmequipos.com<br>
	Medellín - Colombia	
	</div>
	

</body>

<script type="application/javascript">
	print();
</script>

</html>