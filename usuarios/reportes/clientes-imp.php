<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - LISTADO DE CLIENTES</title>
	
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">LISTADO DE CLIENTES</h2>
	
							<strong>PROCESOS</strong>
							<p style="font-size: 11px;">
								NC = No Contestó | NE = Número equivocado | EP = Enviar portafolio | IN = Incio negocio| AC = Actualizado | PR = Papelera reciclaje
							</p>

	
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 40px; background-color: darkblue; color:white;">
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

								
							</tr>
							</thead>
							<tbody>
                            <?php
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
								
							$consulta = mysql_query("SELECT * FROM clientes
							INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
							INNER JOIN localidad_departamentos ON dep_id=ciu_departamento 
							$filtro2
							$filtro3
							WHERE cli_id=cli_id $filtro
							ORDER BY ".$_GET['orden']." ".$_GET['formaOrden']."
							",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
								
								if($res['cli_estado_mercadeo']==7){continue;}
								
								//if($res['cli_papelera']==1){continue;}
								
								switch($res['cli_categoria']){
									case 1: $categ = 'Prospecto'; break;
									case 2: $categ = 'Cliente'; break;
								}
								
								$numContac = mysql_num_rows(mysql_query("SELECT * FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'",$conexion));
								
								$usuarioMod = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['cli_usuario_modificacion']."'",$conexion));
								
								$usuarioMercadeo = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['cli_estado_mercadeo_usuario']."'",$conexion));
								
								
							?>
							<tr style="height: 20px;" id="filaClientes<?=$res['cli_id'];?>">
								
								<td align="center"><?=$no;?></td>
								<td align="center" id="proceso<?=$res['cli_id'];?>" style="background-color: <?=$emColor[$res['cli_estado_mercadeo']];?>"><?=$em[$res['cli_estado_mercadeo']];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
                                <td><?=$res['cli_usuario'];?></td>
								<td><?=$res['cli_nombre'];?></td>
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

							</tr>
								
								
                            <?php $no++;}?>
							</tbody>
							</table>
	
	
	<script src="app.js"></script>
	
	<script type='text/javascript'>
	print();
</script>

</body>
</html>