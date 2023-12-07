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
							<h3 style="text-align:center;">DESDE <u><?=$_GET["desde"];?></u> HASTA <u><?=$_GET["hasta"];?></u></h3>
	
							<p style="font-size: 11px;">
								LL = llamadas | EM = Email | VS = Visitas
							</p>
	
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 40px; background-color: darkblue; color:white;">
								<th rowspan="2">No</th>
                                <th rowspan="2">Nombre</th>
								<th colspan="3" style="background-color: forestgreen;">REALIZADO</th>
								<th colspan="3" style="background-color: orangered;">PENDIENTE</th>
							</tr>
							<tr style="height: 20px;">
								<th style="background-color: forestgreen;">LL</th>
                                <th style="background-color: forestgreen;">EM</th>
                                <th style="background-color: forestgreen;">VS</th>
								<th style="background-color: orangered;">LL</th>
                                <th style="background-color: orangered;">EM</th>
                                <th style="background-color: orangered;">VS</th>
							</tr>	
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							$filtroSeg = '';
							$filtroSeg2 = '';
								
							if($_GET["roles"]!=""){$filtro .= " AND (usr_tipo='".$_GET["roles"]."')";}
							if($_GET["usuarioR"]!=""){$filtro .= " AND (usr_id='".$_GET["usuarioR"]."')";}

							if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtroSeg .= " AND (cseg_fecha_reporte>='".$_GET["desde"]."')";}
							if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtroSeg .= " AND (cseg_fecha_reporte<='".$_GET["hasta"]."')";}
								
							if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtroSeg2 .= " AND (cseg_fecha_proximo_contacto>='".$_GET["desde"]."')";}
							if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtroSeg2 .= " AND (cseg_fecha_proximo_contacto<='".$_GET["hasta"]."')";}	
								
							$consulta = mysql_query("SELECT * FROM usuarios 
							WHERE usr_bloqueado=0 $filtro
							",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){

								$numeros = mysql_fetch_array(mysql_query("
								SELECT
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_usuario_responsable='".$res['usr_id']."' AND (cseg_canal=3 OR cseg_canal=4) AND (cseg_realizado=1) $filtroSeg),
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket 
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_usuario_responsable='".$res['usr_id']."' AND (cseg_canal=8) AND (cseg_realizado=1) $filtroSeg),
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_usuario_responsable='".$res['usr_id']."' AND (cseg_canal=5) AND (cseg_realizado=1) $filtroSeg),
								
								
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket
								INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
								WHERE cseg_usuario_encargado='".$res['usr_id']."' AND (cseg_canal=3 OR cseg_canal=4) AND (cseg_realizado IS NULL OR cseg_realizado=0) $filtroSeg2),
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket
								INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
								WHERE cseg_usuario_encargado='".$res['usr_id']."' AND (cseg_canal=8) AND (cseg_realizado IS NULL OR cseg_realizado=0) $filtroSeg2),
								
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket
								INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
								WHERE cseg_usuario_encargado='".$res['usr_id']."' AND (cseg_canal=5) AND (cseg_realizado IS NULL OR cseg_realizado=0) $filtroSeg2)
								",$conexion));
								
								$color1='#FFF';	$color2='#FFF';	$color3='#FFF'; $color4='#FFF'; $color5='#FFF'; $color6='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								if($numeros[1]==0){$color2='#FFF090';}
								if($numeros[2]==0){$color3='#FFF090';}
								if($numeros[3]>0){$color4='#FFF090';}
								if($numeros[4]>0){$color5='#FFF090';}
								if($numeros[5]>0){$color6='#FFF090';}
							?>
							<tr style="height: 20px;">
								<td align="center"><?=$no;?></td>
                                <td><?=strtoupper($res['usr_nombre']);?></td>
								
								<td align="center" style="background:<?=$color1;?>;"><a href="usuarios-seguimiento.php?inf=1&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=3&canalDos=4"><?=$numeros[0];?></a></td>
								
								<td align="center" style="background:<?=$color2;?>;"><a href="usuarios-seguimiento.php?inf=1&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=8&canalDos=44"><?=$numeros[1];?></a></td>
								
								<td align="center" style="background:<?=$color3;?>;"><a href="usuarios-seguimiento.php?inf=1&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=5&canalDos=44"><?=$numeros[2];?></td>
								
									
								<td align="center" style="background:<?=$color4;?>;"><a href="usuarios-seguimiento.php?inf=2&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=3&canalDos=4"><?=$numeros[3];?></a></td>
								
								<td align="center" style="background:<?=$color5;?>;"><a href="usuarios-seguimiento.php?inf=2&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=8&canalDos=44"><?=$numeros[4];?></a></td>
								
								<td align="center" style="background:<?=$color6;?>;"><a href="usuarios-seguimiento.php?inf=2&usuario=<?=$res['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>&canal=5&canalDos=44"><?=$numeros[5];?></td>
								
							</tr>	
								
                            <?php $no++;}?>
							</tbody>
							</table>
	
	
	<?php if($_GET["inf"]==1 or $_GET["inf"]==2){?>
	
	<h1>SEGUIMIENTOS</h1>
	<table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="background-color: darkblue; color:white; height: 30px;">
								<th>No</th>
                                <th>Cliente</th>
                                <th>Seguimiento</th>
                                <th>DT</th>
								<th>CZ</th>
								<th>VT</th>
								<th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$orden = 'ORDER BY cseg_id DESC';
							if(isset($_GET["seg"]) and $_GET["seg"]!="" and is_numeric($_GET["seg"])){$orden = 'ORDER BY cseg_id='.$_GET["seg"].' DESC';}
							
							$filtroUsuario = " AND (cseg_usuario_responsable='".$_SESSION["id"]."' OR cseg_usuario_encargado='".$_SESSION["id"]."')";
							$filtro = '';	
							if($_GET["estado"]==1){$filtro .= ' AND cseg_realizado=1'; $filtroUsuario='';}
							if($_GET["estado"]==2){$filtro .= ' AND cseg_realizado IS NULL'; $filtroUsuario='';}
							if(is_numeric($_GET["idTK"])){$filtro .= " AND cseg_tiket='".$_GET["idTK"]."'"; $filtroUsuario='';}
							if(is_numeric($_GET["cte"])){$filtro .= " AND cseg_cliente='".$_GET["cte"]."'"; $filtroUsuario='';}	
							if(is_numeric($_GET["a"])){$filtro .= " AND YEAR(cseg_fecha_reporte)='".$_GET["a"]."'"; $filtroUsuario='';}
							if(is_numeric($_GET["m"])){$filtro .= " AND MONTH(cseg_fecha_reporte)='".$_GET["m"]."'"; $filtroUsuario='';}	
							
							if($_GET["inf"]==1){
								$consulta = mysql_query("SELECT * FROM cliente_seguimiento
								INNER JOIN clientes ON cli_id=cseg_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_usuario_responsable='".$_GET["usuario"]."' AND cseg_fecha_reporte>='".$_GET["desde"]."' AND cseg_fecha_reporte<='".$_GET["hasta"]."' AND (cseg_canal='".$_GET["canal"]."' OR cseg_canal='".$_GET["canalDos"]."') AND (cseg_realizado=1)
								",$conexion);
							}
							elseif($_GET["inf"]==2){
								$consulta = mysql_query("SELECT * FROM cliente_seguimiento
								INNER JOIN clientes ON cli_id=cseg_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
								WHERE cseg_usuario_encargado='".$_GET["usuario"]."' AND cseg_fecha_proximo_contacto>='".$_GET["desde"]."' AND cseg_fecha_proximo_contacto<='".$_GET["hasta"]."' AND (cseg_canal='".$_GET["canal"]."' OR cseg_canal='".$_GET["canalDos"]."') AND (cseg_realizado IS NULL OR cseg_realizado=0)
								",$conexion);
							}
							
							
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								$encargado = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['cseg_usuario_encargado']."'",$conexion));
								
								$contacto = mysql_fetch_array(mysql_query("SELECT * FROM contactos 
								INNER JOIN sucursales ON sucu_id=cont_sucursal
								WHERE cont_id='".$res['cseg_contacto']."'",$conexion));
								
								if($res['cseg_id']==$_GET['seg']){$fondoColor = 'style="background:#99DFC6; font-weight:bold;"'; } else {$fondoColor = '';}
								switch($res['cseg_realizado']){
									case 1: $html = 'Completado'; break;
									default: $html = 'Pendiente'; break;
								}
								
								$ticketR = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='".$res['cseg_tiket']."'",$conexion));
							?>
							<tr>
								<td <?=$fondoColor;?>><?=$no;?></td>
                                <td <?=$fondoColor;?>>
                                	<?php echo "<b>Nombre</b>:". $res['cli_nombre'];?>
                                    <?php if($res['cli_telefono']!="") echo "<br><b>Tel:</b> ". $res['cli_telefono'];?>
                                    <?php if($res['cli_celular']!="") echo "<br><b>Cel:</b> ". $res['cli_celular'];?>
                                    <?php if($res['cli_email']!="") echo "<br><b>Email:</b> ". $res['cli_email'];?>
                                    <?php echo "<br><b>Ciudad:</b> ". $res['ciu_nombre'];?>
                                    
                                    <h4 style="margin-top:10px;">
                                		<a href="clientes-seguimiento-agregar.php?idTK=<?=$res['cseg_tiket'];?>" data-toggle="tooltip" title="Nuevo Seguimiento"><i class="icon-plus"></i></a>
										<a href="clientes-seguimiento-editar.php?id=<?=$res[0];?>&idTK=<?=$_GET["idTK"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
                                    	<a href="sql.php?id=<?=$res[0];?>&get=4&idTK=<?=$_GET["idTK"];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
										
                                </h4>
                                </td>
                                <td <?=$fondoColor;?>>
									<b>Ticket:</b> <?="<b>".$ticketR['tik_id']."</b> - ".$ticketR['tik_asunto_principal'];?>
									<hr>
									<h5 style="font-weight:bold;">GESTIÓN</h5>
									<b>Fecha:</b> <?=$res['cseg_fecha_contacto'];?>&nbsp;|&nbsp;
                                	<b>Responsable:</b> <?=$res['usr_nombre'];?><br>
									<span style="color:#009; font-size: 10px;"><?=$res['cseg_observacion'];?></span>
                                    
									<?php if($res['cseg_fecha_proximo_contacto']!='0000-00-00'){?>
									<p>
                                    	<h5 style="font-weight:bold;">PRÓXIMO CONTACTO</h5>
                                        <b>Fecha:</b> <?=$res['cseg_fecha_proximo_contacto'];?>&nbsp;|&nbsp;
                                    	<b>Encargado:</b> <?=$encargado['usr_nombre'];?><br>
										<span style="color:#009; font-size: 10px;"><?=$res['cseg_asunto'];?></span>
                                    </p>
									<?php }?>
                                </td>
                                <td <?=$fondoColor;?>><?=$opcionesSINO[$res['cseg_consiguio_datos']];?></td>
								<td <?=$fondoColor;?>><?=$opcionesSINO[$res['cseg_cotizo']];?></td>
								<td <?=$fondoColor;?>><?=$opcionesSINO[$res['cseg_vendio']];?></td>
								<td <?=$fondoColor;?>><?=$html;?></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>

	<?php }?>
	
	

</body>
</html>