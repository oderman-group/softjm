<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<?php
if($_POST["formato"]==2){
	header("Location:../excel-exportar.php?exp=4&usuarioR=".$_POST["usuarioR"]."&cliente=".$_POST["cliente"]."&tipoS=".$_POST["tipoS"]."&cotizacion=".$_POST["cotizacion"]."&desde=".$_POST["desde"]."&hasta=".$_POST["hasta"]."&orden=".$_POST["orden"]."&formaOrden=".$_POST["formaOrden"]."&departamento=".$_POST["departamento"]."&tipoDocumento=".$_POST["tipoDocumento"]."&ciudad=".$_POST["ciudad"]);
	exit();
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - SEGUIMIENTO DE CLIENTES</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">SEGUIMIENTO DE CLIENTES</h2>
	
							<p style="font-size: 11px;">
								DT = Consiguió datos | CZ = Hubo cotización | VT = Hubo venta
							</p>
	
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="background:#090B2E; color: yellow;">
								<th>No</th>
                                <th>TICKET</th>
                                <th>Fecha de contacto</th>
                                <th>Ciudad, Departamento</th>
								<th>Cliente</th>
                                <th>Resposable</th>
                                <th>CZ</th>
								<th>VT</th>
								<th>DT</th>
                                <th>Observación</th>
                                <th>Próximo contacto</th>
                                <th>Encargado</th>
								<th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_POST["usuarioR"]) and $_POST["usuarioR"]!=""){$filtro .= " AND (cseg_usuario_responsable='".$_POST["usuarioR"]."')";}
								
							if(isset($_POST["cliente"]) and $_POST["cliente"]!=""){$filtro .= " AND (cseg_cliente='".$_POST["cliente"]."')";}
							if(isset($_POST["tipoS"]) and $_POST["tipoS"]!=""){$filtro .= " AND (cseg_tipo='".$_POST["tipoS"]."')";}
							if(isset($_POST["cotizacion"]) and $_POST["cotizacion"]!=""){$filtro .= " AND (cseg_cotizo='".$_POST["cotizacion"]."')";}
							if(isset($_POST["venta"]) and $_POST["venta"]!=""){$filtro .= " AND (cseg_vendio='".$_POST["venta"]."')";}
							if(isset($_POST["datos"]) and $_POST["datos"]!=""){$filtro .= " AND (cseg_consiguio_datos='".$_POST["datos"]."')";}	
							
								/*	
							if(isset($_POST["cotizacion"]) and $_POST["cotizacion"]!=""){
								if($_POST["cotizacion"]==1)$filtro .= " AND (cseg_cotizacion!='')";
								elseif($_POST["cotizacion"]==2)$filtro .= " AND (cseg_cotizacion='')";
								else $filtro.="";
							}
							*/
							$filtro2 = '';
							if($_POST["departamento"]!=""){$filtro2 .= " AND dep_id='".$_POST["departamento"]."'";}
								
							if(isset($_POST["desde"]) and $_POST["desde"]!=""){$filtro .= " AND (cseg_fecha_contacto>='".$_POST["desde"]."')";}
							if(isset($_POST["hasta"]) and $_POST["hasta"]!=""){$filtro .= " AND (cseg_fecha_contacto<='".$_POST["hasta"]."')";}
							
							$filtroCli = '';	
							if($_POST["ciudad"]!=""){$filtroCli .= " AND cli_ciudad='".$_POST["ciudad"]."'";}
							if($_POST["tipoDocumento"]!=""){$filtroCli .= " AND cli_tipo_documento='".$_POST["tipoDocumento"]."'";}
								
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM cliente_seguimiento
							INNER JOIN clientes_tikets ON tik_id=cseg_tiket
							INNER JOIN clientes ON cli_id=cseg_cliente $filtroCli
							INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
							INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento $filtro2
							INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
							WHERE cseg_id=cseg_id ".$filtro."
							ORDER BY ".$_POST["orden"]." ".$_POST["formaOrden"]);
							$opcionesSino = array("NO","SI");	
							$no = 1;
							while($res = mysqli_fetch_array($consulta)){
								$encargado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['cseg_usuario_encargado']."'"));
								switch($res['cseg_realizado']){
									case 1: $html = 'Completado'; break;
									default: $html = '<span class="label label-important">Pendiente'; break;
								}
							?>
							<tr style="font-size: 9px;">
								<td align="center"><?=$no;?></td>
                                <td><a href="../clientes-tikets-editar.php?id=<?=$res['tik_id'];?>" target="_blank" title="Detalles del ticket"><?="<b>".$res['tik_id']."</b><br><br>".$res['tik_fecha_creacion'];?></a></td>
                                <td><a href="../clientes-seguimiento-editar.php?id=<?=$res['cseg_id'];?>&idTK=<?=$res['tik_id'];?>" target="_blank" title="Detalles del seguimiento"><?=$res['cseg_fecha_contacto'];?></a></td>
								<td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><?=$res['usr_nombre'];?></td>
								
                                <td><?=$opcionesSINO[$res['cseg_cotizo']];?></td>
								<td><?=$opcionesSINO[$res['cseg_vendio']];?></td>
								<td><?=$opcionesSINO[$res['cseg_consiguio_datos']];?></td>
								
                                <td><?=$res['cseg_observacion'];?></td>
                                <td><?=$res['cseg_fecha_proximo_contacto'];?></td>
                                <td><?=$encargado['usr_nombre'];?></td>
								<td><?=$html;?></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
                            
                            <p>
                            	TOTAL REGISTROS : <?=$no-1;?>
                            </p>

</body>
</html>