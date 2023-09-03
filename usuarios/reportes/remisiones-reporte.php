<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - REMISIONES LABORATORIO</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">REMISIONES LABORATORIO</h2>
                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height: 50px; background: #D1D1D1;">
								<th>No</th>
								<th>ID</th>
                                <th>Equipo</th>
								<th>Descripción</th>
								<th>Entrega</th>
                                <th>Entrada</th>
								<th>Salida</th>
								<th>Días</th>
                                <th>Cliente</th>
                                <th>Resposable</th>
								<th>Estado</th>
								<th>Tipo</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_POST["usuarioR"]) and $_POST["usuarioR"]!=""){$filtro .= " AND (rem_asesor='".$_POST["usuarioR"]."')";}
							if(isset($_POST["cliente"]) and $_POST["cliente"]!=""){$filtro .= " AND (rem_cliente='".$_POST["cliente"]."')";}
							if(isset($_POST["tipoEQ"]) and $_POST["tipoEQ"]!=""){$filtro .= " AND (rem_tipo_equipo='".$_POST["tipoEQ"]."')";}
							if(isset($_POST["estado"]) and $_POST["estado"]!=""){$filtro .= " AND (rem_estado='".$_POST["estado"]."')";}	

							if(isset($_POST["desde"]) and $_POST["desde"]!=""){$filtro .= " AND (rem_fecha_registro>='".$_POST["desde"]."')";}
							if(isset($_POST["hasta"]) and $_POST["hasta"]!=""){$filtro .= " AND (rem_fecha_registro<='".$_POST["hasta"]."')";}
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
							INNER JOIN clientes ON cli_id=rem_cliente
							INNER JOIN usuarios ON usr_id=rem_asesor
							WHERE rem_id=rem_id ".$filtro."
							ORDER BY ".$_POST["orden"]." ".$_POST["formaOrden"]);
							$no = 1;
							$eq_nuevos = 0;
							$eq_usados = 0;
							$eq_nr = 0;	
							while($res = mysqli_fetch_array($consulta)){
								$dias = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT DATEDIFF(rem_fecha_salida, rem_fecha_registro) FROM remisiones WHERE rem_id='".$res['rem_id']."'"));
								
								switch($res['rem_tipo_equipo']){
									case 1: $tipoE = 'Estación total'; break;
									case 2: $tipoE = 'Teodolito'; break;
									case 3: $tipoE = 'Nivel'; break;
								}
								switch($res['rem_estado']){
									case 1: $estado = 'Entrada'; break;
									case 2: $estado = 'Salida'; break;
								}
								
								switch($res['rem_tipos_equipos']){
									case 1: $tiposEquipos = 'Nuevo'; $eq_nuevos++; break;
									case 2: $tiposEquipos = 'Usado'; $eq_usados++; break;
										
									default: $tiposEquipos = '-'; $eq_nr++; break;	
								}
							?>
							<tr style="height: 30px;">
								<td align="center"><?=$no;?></td>
								<td align="center"><?=$res['rem_id'];?></td>
                                <td><?=$tipoE;?></td>
								<td>
									<?php
									$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios");
									while($datosSelect = mysqli_fetch_array($consultaSelect)){

										$numOpciones = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_servicios 
										WHERE remxs_id_remision='".$res['rem_id']."' AND remxs_id_servicio='".$datosSelect[0]."'"));

										if($numOpciones>0){
											echo strtoupper($datosSelect['serv_nombre'])."<br>";	
										}

									}
									echo $res['rem_descripcion'];
									?>
								</td>
								<td><?=$res['rem_dias_entrega'];?></td>
                                <td><?=$res['rem_fecha_registro'];?></td>
								<td><?=$res['rem_fecha_salida'];?></td>
								<td align="center"><?=$dias[0];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=$estado;?></td>
								<td><?=$tiposEquipos;?></td>
								<td align="center"><a href="../../v2.0/usuarios/empresa/lab-remisiones-seguimiento.php?id=<?=$res['rem_id'];?>" target="_blank">Ver seguimiento</a></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
                            
                            <p>
                            	TOTAL REGISTROS : <?=$no-1;?><br><br>
								
								EQUIPOS NUEVOS : <?=$eq_nuevos;?><br>
								EQUIPOS USADOS : <?=$eq_usados;?><br>
								SIN CLASIFICAR : <?=$eq_nr;?>
                            </p>

</body>
</html>