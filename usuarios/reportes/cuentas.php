<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM configuracion WHERE conf_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'"));?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - FACTURACIÓN</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">FACTURACIÓN</h2>
                            <div align="center" style="margin-bottom:5px;"><img src="../files/<?=$configuracion['conf_logo'];?>" height="100" alt="<?=$configuracion['conf_empresa'];?>"></div>
                            <table width="90%" border="1" rules="all" align="center">
							<thead>
							<tr style="height:30px;">
								<th>No</th>
                                <th>Responsable</th>
                                <th>Infuyente</th>
                                <th>#Factura</th>
                                <th>Fecha</th>
                                <th>Vence</th>
                                <th>Cliente</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                                <th>Abonos</th>
                                <th>Saldo</th>
                                <th>Tipo</th>
                                <th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if(isset($_POST["usuarioR"]) and $_POST["usuarioR"]!=""){$filtro .= " AND (fact_usuario_responsable='".$_POST["usuarioR"]."')";}
							if(isset($_POST["usuarioI"]) and $_POST["usuarioI"]!=""){$filtro .= " AND (fact_usuario_influyente='".$_POST["usuarioI"]."')";}
							if(isset($_POST["cliente"]) and $_POST["cliente"]!=""){$filtro .= " AND (fact_cliente='".$_POST["cliente"]."')";}
							if(isset($_POST["tipoOp"]) and $_POST["tipoOp"]!=""){$filtro .= " AND (fact_tipo='".$_POST["tipoOp"]."')";}
							if(isset($_POST["estado"]) and $_POST["estado"]!=""){$filtro .= " AND (fact_estado='".$_POST["estado"]."')";}
							if(isset($_POST["descuento"]) and $_POST["descuento"]!=""){
								if($_POST["descuento"]==1)$filtro .= " AND (fact_descuento>0)";
								elseif($_POST["descuento"]==2)$filtro .= " AND (fact_descuento=0)";
								else $filtro.="";
							}
							if(isset($_POST["desdeF"]) and $_POST["desdeF"]!=""){$filtro .= " AND (fact_fecha_real>='".$_POST["desdeF"]."')";}
							if(isset($_POST["hastaF"]) and $_POST["hastaF"]!=""){$filtro .= " AND (fact_fecha_real<='".$_POST["hastaF"]."')";}
							if(isset($_POST["desdeV"]) and $_POST["desdeV"]!=""){$filtro .= " AND (fact_valor>='".$_POST["desdeV"]."')";}
							if(isset($_POST["hastaV"]) and $_POST["hastaV"]!=""){$filtro .= " AND (fact_valor<='".$_POST["hastaV"]."')";}
							$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM facturacion
							INNER JOIN clientes ON cli_id=fact_cliente
							INNER JOIN usuarios ON usr_id=fact_usuario_responsable
							WHERE fact_id=fact_id ".$filtro." AND fact_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
							ORDER BY ".$_POST["orden"]." ".$_POST["formaOrden"]);
							$no = 1;
							while($res = mysqli_fetch_array($consulta)){	
								$inf = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM usuarios WHERE usr_id='".$res['fact_usuario_influyente']."'"));
								
								$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT sum(fpab_valor) FROM facturacion_abonos WHERE fpab_factura='".$res['fact_id']."'"));
								
								$totalValor += $res['fact_valor'];
								$totalAbonos += $abonos[0];
								
								$saldoFinal = $res['fact_valor'] - $abonos[0];
								$sumaTotal += $saldoFinal;
								
								switch($res['fact_estado']){
									case 1: $estadoF = 'Pagada'; break;
									case 2: $estadoF = 'Por pagar'; break;
									case 3: $estadoF = 'Anulada'; break;
								}
								switch($res['fact_tipo']){
									case 1: $tipoF = 'Ingreso'; break;
									case 2: $tipoF = 'Egreso'; break;
								}
							?>
							<tr>
								<td align="center"><?=$no;?></td>
                                <td><?=$res['usr_nombre'];?></td>
                                <td><?=$inf['usr_nombre'];?></td>
                                
                                <td><?=$res['fact_numero_fisica'];?></td>
                                <td><?=$res['fact_fecha_real'];?></td>
                                <td><?=$res['fact_fecha_vencimiento'];?></td>
                                <td><?=$res['cli_nombre']." (".$res['cli_telefono'].")";?></td>
                                <td><?=$res['fact_descripcion'];?></td>
                                <td style="font-weight:bold;">$<?=number_format($res['fact_valor'],0,",",".");?></td>
                                <td style="font-weight:bold;">$<?=number_format($abonos[0],0,",",".");?></td>
                                <td style="font-weight:bold;">$<?=number_format($saldoFinal,0,",",".");?></td>
                                <td><?=$tipoF;?></td>
                                <td><?=$estadoF;?></td>
                                </tr>
                            <?php $no++;}?>
							</tbody>
                            <tfoot>
                                <tr style="font-weight:bold; height:20px;">
                                	<td colspan="8" align="right">TOTAL: </td>
                                    <td>$<?=number_format($totalValor,0,",",".");?> </td>
                                    <td>$<?=number_format($totalAbonos,0,",",".");?> </td>
                                    <td>$<?=number_format($sumaTotal,0,",",".");?> </td>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            </tfoot>
							</table>

</body>
</html>