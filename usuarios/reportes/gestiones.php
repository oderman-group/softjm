<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - LISTADO DE CLIENTES</title>
<!-- styles -->
<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="../css/font-awesome.css">

<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="../css/styles.css" rel="stylesheet">
<link href="../css/theme-blue.css" rel="stylesheet">	
	
	<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="../js/bootstrap.js"></script>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">GESTION DE CLIENTES</h2>
	
							<p style="font-size: 11px;">
								TK = Tickets | SG = Seguimientos | SC = Sucursales | CT = Contactos | FC = Facturas | RM = Remisiones
							</p>
	
							<strong>PROCESOS</strong>
							<p style="font-size: 11px;">
								NC = No Contestó | NE = Número equivocado | EP = Enviar portafolio | IN = Incio negocio| AC = Actualizado | PR = Papelera reciclaje
							</p>
	
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 40px; background-color: darkblue; color:white;">
								<th>Acciones</th>
								<th>No</th>
								<th>Proceso</th>
                                <th>Ciudad, Departamento</th>
                                <th>NIT</th>
								<th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Email</th>
								<th>Categoría</th>
								<th>U. Modificación</th>
								<th>TK</th>
								<th>SG</th>
								<th>SC</th>
								<th>CT</th>
								<th>FC</th>
								<th>RM</th>
								
							</tr>
							</thead>
							<tbody>
                            <?php
							$em = array("","NC","NE","EP","IN","AC","PR");
								
							$consulta = mysql_query("SELECT * FROM gestiones_clientes
							INNER JOIN clientes ON cli_id=gestxc_cliente
							INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
							INNER JOIN localidad_departamentos ON dep_id=ciu_departamento 
							WHERE gestxc_gestion='".$_GET["gestion"]."'
							",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM gestiones_usuarios WHERE gestxu_usuario='".$_SESSION["id"]."' AND gestxu_gestion='".$_GET["gestion"]."'",$conexion));
									if($numZ==0) continue;
								}
								
								//if($res['cli_estado_mercadeo']==7){continue;}
								
								//if($res['cli_papelera']==1){continue;}
								
								switch($res['cli_categoria']){
									case 1: $categ = 'Prospecto'; break;
									case 2: $categ = 'Cliente'; break;
								}
								
								$numContac = mysql_num_rows(mysql_query("SELECT * FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'",$conexion));
								
								$usuarioMod = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['cli_usuario_modificacion']."'",$conexion));
								
								$numeros = mysql_fetch_array(mysql_query("
								SELECT
								(SELECT count(tik_id) FROM clientes_tikets WHERE tik_cliente='".$res['cli_id']."'),
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket 
								WHERE cseg_cliente='".$res['cli_id']."'),
								(SELECT count(sucu_id) FROM sucursales WHERE sucu_cliente_principal='".$res['cli_id']."'),
								(SELECT count(cont_id) FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'),
								(SELECT count(fact_id) FROM facturacion WHERE fact_cliente='".$res['cli_id']."'),
								(SELECT count(rem_id) FROM remisiones WHERE rem_cliente='".$res['cli_id']."')
								",$conexion));
								
								$color1='#FFF';	$color2='#FFF';	$color3='#FFF';	$color4='#FFF';	$color5='#FFF';	$color6='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								if($numeros[1]==0){$color2='#FFF090';}
								if($numeros[2]==0){$color3='#FFF090';}
								if($numeros[3]==0){$color4='#FFF090';}
								if($numeros[4]==0){$color5='#FFF090';}
								if($numeros[5]==0){$color6='#FFF090';}
							?>
							<tr style="height: 20px;">
								<td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											
											<li><a href="../bd_update/cliente-terminado-actualizar.php?get=34&idR=<?=$res['cli_id'];?>&em=5" onClick="if(!confirm('El cliente pasará al listado de clientes que ya están actualizados. Desea continuar?')){return false;}">Actualizado</a></li>
											<li><a href="../bd_update/cliente-estado-mercadeo-update.php?get=42&idR=<?=$res['cli_id'];?>&em=1">No contestó. Mantener en la lista.</a></li>
											<li><a href="../bd_update/cliente-estado-mercadeo-update.php?get=42&idR=<?=$res['cli_id'];?>&em=7">No contestó. Quitar de la lista.</a></li>
											<li><a href="../bd_update/cliente-estado-mercadeo-update.php?get=42&idR=<?=$res['cli_id'];?>&em=2">Número equivocado</a></li>
											<li><a href="../enviar-portafolios.php?cte=<?=$res['cli_id'];?>&em=3" target="_blank">Enviar portafolio</a></li>
											<li><a href="../clientes-tikets-agregar.php?cte=<?=$res['cli_id'];?>&origenNegocio=1&em=4" target="_blank">Iniciar negociación</a></li>
											<li><a href="../bd_update/cliente-papelera-actualizar.php?get=33&idR=<?=$res['cli_id'];?>&em=6" onClick="if(!confirm('Este cliente pasará a una papelera de reciclaje y podrá ser recuperado después. Desea continuar?')){return false;}">Enviar a papelera</a></li>
											
										</ul>
									</div>
								</td>
								
								<td align="center"><?=$no;?></td>
								<td align="center"><?=$em[$res['cli_estado_mercadeo']];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
                                <td><?=$res['cli_usuario'];?></td>
								<td><a href="../clientes-editar.php?id=<?=$res['cli_id'];?>" target="_blank"><?=$res['cli_nombre'];?></a></td>
                                <td><?=$res['cli_telefono'];?></td>
                                <td><?=$res['cli_email'];?></td>
                                <td><?=$categ;?></td>
								<td>
									<?=$res['cli_ultima_modificacion'];?><br>
									<?=$usuarioMod['usr_nombre'];?>
								</td>
								
								<td align="center" style="background:<?=$color1;?>;"><a href="../clientes-tikets.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Tickets"><?=$numeros[0];?></a></td>
								<td align="center" style="background:<?=$color2;?>;"><a href="../clientes-seguimiento.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Seguimientos"><?=$numeros[1];?></a></td>
								<td align="center" style="background:<?=$color3;?>;"><a href="../clientes-sucursales.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Sucursales"><?=$numeros[2];?></td>
								<td align="center" style="background:<?=$color4;?>;"><a href="../clientes-contactos.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Contactos"><?=$numeros[3];?></a></td>
								<td align="center" style="background:<?=$color5;?>;"><a href="../facturacion.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Facturas"><?=$numeros[4];?></a></td>
								<td align="center" style="background:<?=$color6;?>;"><a href="../../v2.0/usuarios/empresa/lab-remisiones.php?cte=<?=$res['cli_id'];?>" target="_blank" title="Remisiones"><?=$numeros[5];?></a></td>
								
								
								
							</tr>
								
							<?php if($_GET['sucursales']==1){
								$i = 1;
								$sucursales = mysql_query("SELECT * FROM sucursales 
								INNER JOIN localidad_ciudades ON ciu_id=sucu_ciudad
								INNER JOIN localidad_departamentos ON dep_id=ciu_departamento
								WHERE sucu_cliente_principal='".$res['cli_id']."'",$conexion);
								$sucNum = mysql_num_rows($sucursales);
								if($sucNum>0){
							?>
								<tr style="background-color: dimgray; height: 20px; font-weight: bold; color:white;">
									<td align="center" colspan="17">SUCURSALES</td>
								</tr>
								<tr style="background-color: gainsboro; font-weight: bold;">
									<td align="center">No</td>
									<td colspan="3">Ubicación</td>
									<td colspan="10">Nombre</td>
									<td colspan="3">Teléfono</td>
								</tr>
							<?php
								}
								while($sucu = mysql_fetch_array($sucursales)){
							?>
							<tr style="background-color: gainsboro;">
								<td align="center"><?=$i;?></td>
                                <td colspan="3"><?=$sucu['ciu_nombre'].", ".$sucu['dep_nombre'];?></td>
                                <td colspan="10"><?=$sucu['sucu_nombre'];?></td>
                                <td colspan="3"><?=$res['sucu_telefono'];?></td>
							</tr>	
							<?php
									$i++;
								}
							}
							?>
								
							<?php if($_GET['contacto']==1){	
								$i = 1;
								$contacto = mysql_query("SELECT * FROM contactos
								WHERE cont_cliente_principal='".$res['cli_id']."'",$conexion);
								$contNum = mysql_num_rows($contacto);
								if($contNum>0){
							?>
								<tr style="background-color: dimgray; height: 20px; font-weight: bold; color:white;">
									<td align="center" colspan="17">CONTACTOS</td>
								</tr>
								<tr style="background-color: gainsboro; font-weight: bold;">
									<td align="center">No</td>
									<td colspan="3">Nombre</td>
									<td colspan="3">Teléfono</td>
									<td colspan="3">Celular</td>
									<td colspan="3">Email</td>
									<td colspan="4">Área</td>
								</tr>
							<?php
								}
								while($cont = mysql_fetch_array($contacto)){
							?>
							<tr style="background-color: gainsboro;">
								<td align="center"><?=$i;?></td>
                                <td colspan="3"><?=$cont['cont_nombre'];?></td>
								<td colspan="3"><?=$cont['cont_telefono'];?></td>
								<td colspan="3"><?=$cont['cont_celular'];?></td>
                                <td colspan="3"><?=$cont['cont_email'];?></td>
								<td colspan="4"><?=$cont['cont_area'];?></td>
							</tr>	
							<?php
									$i++;
								}
							}
							?>
								
                            <?php $no++;}?>
							</tbody>
							</table>

</body>
</html>