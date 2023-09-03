<?php include("../sesion.php");?>
<?php
if($_POST["formato"]==2){
	header("Location:../excel-exportar.php?exp=3&usuarioR=".$_POST["usuarioR"]."&cliente=".$_POST["cliente"]."&tipoTK=".$_POST["tipoTK"]."&canal=".$_POST["canal"]."&desde=".$_POST["desde"]."&hasta=".$_POST["hasta"]."&orden=".$_POST["orden"]."&formaOrden=".$_POST["formaOrden"]);
	exit();
}
?>
<?php include("../../conexion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - TICKETS DE CLIENTES</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">TICKETS DE CLIENTES</h2>
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 50px; background: #D1D1D1;">
								<th>No</th>
                                <th>Tipo</th>
                                <th>Fecha de contacto</th>
                                <th>Cliente</th>
                                <th>Resposable</th>
                                <th>Canal</th>
                                <th>Asunto</th>
                                <th>Completados</th>
                                <th>Pendientes</th>
								<th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_POST["usuarioR"]) and $_POST["usuarioR"]!=""){$filtro .= " AND (tik_usuario_responsable='".$_POST["usuarioR"]."')";}
							if(isset($_POST["cliente"]) and $_POST["cliente"]!=""){$filtro .= " AND (tik_cliente='".$_POST["cliente"]."')";}
							if(isset($_POST["tipoTK"]) and $_POST["tipoTK"]!=""){$filtro .= " AND (tik_tipo_tiket='".$_POST["tipoTK"]."')";}
							if(isset($_POST["canal"]) and $_POST["canal"]!=""){$filtro .= " AND (tik_canal='".$_POST["canal"]."')";}	

							if(isset($_POST["desde"]) and $_POST["desde"]!=""){$filtro .= " AND (tik_fecha_creacion>='".$_POST["desde"]."')";}
							if(isset($_POST["hasta"]) and $_POST["hasta"]!=""){$filtro .= " AND (tik_fecha_creacion<='".$_POST["hasta"]."')";}
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets
							INNER JOIN clientes ON cli_id=tik_cliente
							INNER JOIN usuarios ON usr_id=tik_usuario_responsable
							WHERE tik_id=tik_id ".$filtro."
							ORDER BY ".$_POST["orden"]." ".$_POST["formaOrden"]);
							$no = 1;
							$canales = array("","Facebook","WhatsApp","Fijo","Celular","Personal","Skype","Otro");
							while($res = mysqli_fetch_array($consulta)){
								$encargado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['cseg_usuario_encargado']."'"));
								switch($res['tik_tipo_tiket']){
									case 1: $tipoS = 'Comercial'; break;
									case 2: $tipoS = 'Soporte técnico'; break;
									case 3: $tipoS = 'Soporte operativo'; break;
								}
								switch($res['tik_estado']){
									case 1: $estado = 'Abierto'; break;
									case 2: $estado = 'Cerrado'; break;
								}
								
								$seguimientos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"
								SELECT
								(SELECT COUNT(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes ON cli_id=cseg_cliente
								WHERE cseg_tiket='".$res['tik_id']."' AND cseg_realizado=1),
								(SELECT COUNT(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes ON cli_id=cseg_cliente
								WHERE cseg_tiket='".$res['tik_id']."' AND cseg_realizado IS NULL)"));
							?>
							<tr style="height: 30px;">
								<td align="center"><?=$no;?></td>
                                <td><?=$tipoS;?></td>
                                <td><?=$res['tik_fecha_creacion'];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><?=strtoupper($res['usr_nombre']);?></td>
                                <td><?=$canales[$res['tik_canal']];?></td>
                                <td><a href="../clientes-tikets-editar.php?id=<?=$res['tik_id'];?>" target="_blank"><?="<b>".$res['tik_id']."</b> - ".$res['tik_asunto_principal'];?></a></td>
                                <td align="center"><a href="../clientes-seguimiento.php?idTK=<?=$res['tik_id'];?>&estado=1" target="_blank"><?=$seguimientos[0];?></a></td>
                                <td align="center"><a href="../clientes-seguimiento.php?idTK=<?=$res['tik_id'];?>&estado=2" target="_blank"><?=$seguimientos[1];?></a></td>
								<td><?=$estado;?></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
                            
                            <p>
                            	TOTAL REGISTROS : <?=$no-1;?>
                            </p>

</body>
</html>