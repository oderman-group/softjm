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
	
	
	
	<script>
	</script>
	
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;" onkeydown="javascript:Disable_Control_C()">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">LISTADO DE CLIENTES</h2>
	
							<p style="font-size: 11px;">
								TK = Tickets | SG = Seguimientos | SC = Sucursales | CT = Contactos | FC = Facturas | RM = Remisiones
							</p>
	
							<strong>PROCESOS</strong>
							<p style="font-size: 11px;">
								NC = No Contestó | NE = Número equivocado | EP = Enviar portafolio | IN = Incio negocio| AC = Actualizado | PR = Papelera reciclaje
							</p>
							<?php if($_SESSION["id"]==7 or $_SESSION["id"]==17){?>
							<p>
								<a href="clientes-imp.php?<?=$_SERVER['QUERY_STRING'];?>" target="_blank" style="text-decoration: underline; font-size: 18px;">IMPRIMIR INFORME</a>	
							</p>
							<?php }?>
	
							<span id="resp"></span>
	
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
								<th>U.P. Mercadeo</th>
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
							$emColor = array("white","goldenrod","gold","green","limegreen","aqua","tomato");	
							$filtro = '';
							$filtro2 = '';
							$filtro3 = '';	
							
							if($_GET["grupos"]!=""){$filtro3 .= "INNER JOIN clientes_categorias ON cpcat_cliente=cli_id AND cpcat_categoria='".$_GET["grupos"]."'";}	
							
							if($_GET["departamento"]!=""){$filtro2 .= " AND dep_id='".$_GET["departamento"]."'";}
								
							if($_GET["ciudad"]!=""){$filtro .= " AND (cli_ciudad='".$_GET["ciudad"]."')";}
							if($_GET["categoria"]!=""){$filtro .= " AND (cli_categoria='".$_GET["categoria"]."')";}
								
							if($_GET["listado"]==1){$filtro .= " AND (cli_montados IS NULL OR cli_montados=2)";}
							if($_GET["listado"]==2){$filtro .= " AND (cli_montados=1)";}
							if($_GET["listado"]==3){$filtro .= "";}	
								
							if($_GET["proceso"]!=""){$filtro .= " AND (cli_estado_mercadeo='".$_GET["proceso"]."')";}
							if($_GET["usuarioMercadeo"]!=""){$filtro .= " AND (cli_estado_mercadeo_usuario='".$_GET["usuarioMercadeo"]."')";}
							
							if(isset($_GET["desdeMercadeo"]) and $_GET["desdeMercadeo"]!=""){$filtro .= " AND (cli_estado_mercadeo_fecha>='".$_GET["desdeMercadeo"]."')";}
							if(isset($_GET["hastaMercadeo"]) and $_GET["hastaMercadeo"]!=""){$filtro .= " AND (cli_estado_mercadeo_fecha<='".$_GET["hastaMercadeo"]."')";}	

							if(isset($_GET["desde"]) and $_GET["desde"]!=""){$filtro .= " AND (cli_fecha_registro>='".$_GET["desde"]."')";}
							if(isset($_GET["hasta"]) and $_GET["hasta"]!=""){$filtro .= " AND (cli_fecha_registro<='".$_GET["hasta"]."')";}
								
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
							INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
							INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento 
							$filtro2
							$filtro3
							WHERE cli_id=cli_id $filtro
							ORDER BY ".$_GET['orden']." ".$_GET['formaOrden']."
							");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
								if($datosUsuarioActual[3]!=1){
									$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'");
									$numZ = mysqli_num_rows($consultaZonas);
									if($numZ==0) continue;
								}
								
								if($res['cli_estado_mercadeo']==7){continue;}
								
								if($res['cli_papelera']==1 and $_GET["papelera"]==2){continue;}
								
								switch($res['cli_categoria']){
									case 1: $categ = 'Prospecto'; break;
									case 2: $categ = 'Cliente'; break;
								}
								
								$consultaContactos=mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'");
								$numContac = mysqli_num_rows($consultaContactos);
								
								$consultaUsuarios=mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['cli_usuario_modificacion']."'");
								$usuarioMod = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH);
								
								$consultaUsuarios2=mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['cli_estado_mercadeo_usuario']."'");
								$usuarioMercadeo = mysqli_fetch_array($consultaUsuarios2, MYSQLI_BOTH);
								
								$consultaNumeros=mysqli_query($conexionBdPrincipal,"
								SELECT
								(SELECT count(tik_id) FROM clientes_tikets WHERE tik_cliente='".$res['cli_id']."'),
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket 
								WHERE cseg_cliente='".$res['cli_id']."'),
								(SELECT count(sucu_id) FROM sucursales WHERE sucu_cliente_principal='".$res['cli_id']."'),
								(SELECT count(cont_id) FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'),
								(SELECT count(fact_id) FROM facturacion WHERE fact_cliente='".$res['cli_id']."'),
								(SELECT count(rem_id) FROM remisiones WHERE rem_cliente='".$res['cli_id']."')
								");
								$numeros = mysqli_fetch_array($consultaNumeros, MYSQLI_BOTH);
								
								$color1='#FFF';	$color2='#FFF';	$color3='#FFF';	$color4='#FFF';	$color5='#FFF';	$color6='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								if($numeros[1]==0){$color2='#FFF090';}
								if($numeros[2]==0){$color3='#FFF090';}
								if($numeros[3]==0){$color4='#FFF090';}
								if($numeros[4]==0){$color5='#FFF090';}
								if($numeros[5]==0){$color6='#FFF090';}
							?>
							<tr style="height: 20px;" id="filaClientes<?=$res['cli_id'];?>">
								<td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											
											<li><a href="usuarios/bd_update/cliente-terminado-actualizar.php?get=34&idR=<?=$res['cli_id'];?>&em=5" name="<?=$res['cli_id'];?>" title="5" onClick="mercadeo(this)">Actualizado</a></li>
											
											<li><a href="#../bd_update/cliente-estado-mercadeo-update.php?get=42&idR=<?=$res['cli_id'];?>&em=1" name="<?=$res['cli_id'];?>"  title="1" onClick="mercadeo(this)">No contestó. Mantener en la lista.</a></li>
											<li><a href="#../bd_update/cliente-estado-mercadeo-update?get=42&idR=<?=$res['cli_id'];?>&em=7" name="<?=$res['cli_id'];?>" title="7" onClick="mercadeo(this)">No contestó. Quitar de la lista.</a></li>
											<li><a href="#../bd_update/cliente-estado-mercadeo-update?get=42&idR=<?=$res['cli_id'];?>&em=2" name="<?=$res['cli_id'];?>" title="2" onClick="mercadeo(this)">Número equivocado</a></li>
											<li><a href="#" name="<?=$res['cli_id'];?>" title="3" onClick="mercadeo(this)">Enviar portafolio</a></li>
											<li><a href="#" name="<?=$res['cli_id'];?>" title="4" onClick="mercadeo(this)">Iniciar negociación</a></li>
											
											<li><a href="#../cliente-papelera-actualizar.php?get=33&idR=<?=$res['cli_id'];?>&em=6" name="<?=$res['cli_id'];?>" title="6" onClick="mercadeo(this)">Enviar a papelera</a></li>
											
										</ul>
									</div>
								</td>
								
								<td align="center"><?=$no;?></td>
								<td align="center" id="proceso<?=$res['cli_id'];?>" style="background-color: <?=$emColor[$res['cli_estado_mercadeo']];?>"><?=$em[$res['cli_estado_mercadeo']];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
                                <td><?=$res['cli_usuario'];?></td>
								<td><a href="../clientes-editar.php?id=<?=$res['cli_id'];?>" target="_blank"><?=$res['cli_nombre'];?></a></td>
                                <td>
									<b>Tel:</b> <?=$res['cli_telefono'];?><br>
									<b>Cel:</b> <?=$res['cli_celular'];?>
								</td>
                                <td><?=$res['cli_email'];?></td>
                                <td><?=$categ;?></td>
								<td>
									<?=$res['cli_ultima_modificacion'];?><br>
									<?=$usuarioMod['usr_nombre'];?>
								</td>
								
								<td>
									<span id="upm<?=$res['cli_id'];?>">
										
										<?=$res['cli_estado_mercadeo_fecha'];?><br>
										<?=$usuarioMercadeo['usr_nombre'];?>
									</span>
									
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
								$sucursales = mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales 
								INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=sucu_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
								WHERE sucu_cliente_principal='".$res['cli_id']."'");
								$sucNum = mysqli_num_rows($sucursales);
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
								while($sucu = mysqli_fetch_array($sucursales, MYSQLI_BOTH)){
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
								$contacto = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos
								WHERE cont_cliente_principal='".$res['cli_id']."'");
								$contNum = mysqli_num_rows($contacto);
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
								while($cont = mysqli_fetch_array($contacto, MYSQLI_BOTH)){
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
	
	
	<script src="app.js"></script>
	
	<script type='text/javascript'>
	document.oncontextmenu = function(){
		alert('Esta función no es permitida');
		return false;
	}
	
	function Disable_Control_C() {
		var keystroke = String.fromCharCode(event.keyCode).toLowerCase();

		if (event.ctrlKey && (keystroke == 'c' || keystroke == 'v' || keystroke == 'x')) {
			alert("Esta función no es permitida.");
			event.returnValue = false; // disable Ctrl+C
		}
	}
	
	if (document.layers)
		document.captureEvents(Event.KEYPRESS)
		function backhome(e){
		window.clipboardData.clearData();
	}
</script>

</body>
</html>