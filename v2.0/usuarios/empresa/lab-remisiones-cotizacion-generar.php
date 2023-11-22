<?php
include("sesion.php"); //exit();
include("../compartido/head.php");
$idPagina = 243;

include("verificar-paginas.php");

$consultaRemision=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN clientes ON cli_id=rem_cliente
INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."' AND rem_id_empresa='".$idEmpresa."'");
$remision = mysqli_fetch_array($consultaRemision, MYSQLI_BOTH);
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
	
	<table style="width:95%" border="0" align="center">
		<tr>
			<td><img src="../../assets/images/logojm.png" width="300"></td>
			<td align="right" style="border: solid;"><h3>COTIZACIÓN</h3>No. 4564</td>
		</tr>
    	  
	</table>
	<p>&nbsp;</p>
	
	<table style="width:95%" border="1" rules="all" align="center">
		<tr>
			<td><strong>CLIENTE:</strong> <?=$remision['cli_nombre'];?></td>
			<td><strong>TELÉFONO:</strong> <?=$remision['cli_telefono'];?> </td>
			<td><strong>CELULAR:</strong> <?=$remision['cli_celular'];?></td>
		</tr>
		
		<tr>
			<td><strong>NIT:</strong> <?=$remision['cli_usuario'];?></td>
			<td><strong>CIUDAD:</strong> <?=$remision['ciu_nombre'].", ".$remision['dep_nombre'];?> </td>
			<td><strong>DIRECCIÓN:</strong> <?=$remision['cli_dirección'];?></td>
		</tr>
    	  
	</table>
	<p>&nbsp;</p>
								<table style="width:95%" border="1" rules="all" align="center">
                                        <thead>
                                            <tr align="center">
                                                <th>#</th>
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
                                                <td align="center"><?=$conRegistros;?></td>
												
												<td><?=$resultado['serv_nombre'];?>
												</td>
                                                
												<td align="center">1</td>
												
												<td align="right">$<?=number_format($resultado['serv_precio'],0,",",".");?></td>
												<td align="center">19%</td>
												<td align="right">$<?=number_format($resultado['serv_precio'],0,",",".");?></td>
                                                
                                            </tr>
											<?php $conRegistros++;}?>
                                        </tbody>
                                    </table>
	
	<p>&nbsp;</p>
	
	<table style="width:30%" border="1" rules="all" align="right">
		<tr align="right">
			<td>SUBTOTAL: </td>
			<td>$484.000 </td>
		</tr>
    	  
	</table>


               
</body>


</html>