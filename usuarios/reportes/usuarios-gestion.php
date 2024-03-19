<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - USUARIOS - GESTIÓN DE CLIENTES</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">USUARIOS - GESTIÓN DE CLIENTES</h2>
							<h3 style="text-align:center;">MES ACTUAL (<?=date("m");?>)</h3>
	
							<p style="font-size: 11px;">
								OK = Clientes completados | PR = Papelera de reciclaje 
							</p>
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 40px; background-color: darkblue; color:white;">
								<th rowspan="2">No</th>
                                <th rowspan="2">Nombre</th>
								<?php for($d=1; $d<=31; $d++) {?>
									<th colspan="2"><?=$d;?></th>
								<?php }?> 
							</tr>
								
							<tr style="height: 40px; background-color: darkblue; color:white;">
								<?php for($d=1; $d<=31; $d++) {?>
									<th style="background-color: green;">OK</th>
									<th style="background-color: darkred;">PR</th>
								<?php }?> 
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							if($datosUsuarioActual[3]!=1){
								$filtro = " AND usr_id='".$_SESSION["id"]."'";	
							}
							
								
							$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM usuarios 
							WHERE usr_bloqueado=0 AND usr_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' $filtro
							");
							$no = 1;
							while($res = mysqli_fetch_array($consulta)){

								
								
							?>
							<tr style="height: 20px;">
								<td align="center"><?=$no;?></td>
                                <td><?=strtoupper($res['usr_nombre']);?></td>
								<?php 
								for($d=1; $d<=31; $d++) {
									$gestion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "
									SELECT
									(SELECT count(cli_id) FROM clientes
									WHERE cli_terminado_por='".$res['usr_id']."' AND cli_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND cli_terminado=1 AND MONTH(cli_terminado_fecha)='".date("m")."' AND DAY(cli_terminado_fecha)='".$d."'),
									
									(SELECT count(cli_id) FROM clientes
									WHERE cli_papelera_por='".$res['usr_id']."' AND cli_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND cli_papelera=1 AND MONTH(cli_papelera_fecha)='".date("m")."' AND DAY(cli_papelera_fecha)='".$d."')
									"));
									
									$color1 = 'white';
									if($gestion[0]==0){$color1 = 'khaki';}
									
									$color2 = 'white';
									if($gestion[0]==0){$color2 = 'khaki';}
								?>
									<td align="center" style="background-color:<?=$color1;?>;" title="<?=strtoupper($res['usr_nombre']);?> completó <?=$gestion[0];?> el día <?=$d;?>"><?=$gestion[0];?></td>
								
									<td align="center" style="background-color:<?=$color2;?>; color:red;" title="<?=strtoupper($res['usr_nombre']);?> envió a la papelera <?=$gestion[1];?> el día <?=$d;?>"><?=$gestion[1];?></td>
								<?php }?> 
								
								
									
								
							</tr>	
								
                            <?php $no++;}?>
							</tbody>
							</table>

</body>
</html>