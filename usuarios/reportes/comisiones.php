<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"));?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - COMISIONES</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">COMISIONES</h2>
                            <div align="center" style="margin-bottom:5px;"><img src="../files/<?=$configuracion['conf_logo'];?>" height="100" alt="<?=$configuracion['conf_empresa'];?>"></div>

							<p><a href="../excel-exportar.php?exp=6&responsable=<?=$_POST["responsable"];?>&vendedor=<?=$_POST["vendedor"];?>&cliente=<?=$_POST["cliente"];?>&desdeF=<?=$_POST["desdeF"];?>&hastaF=<?=$_POST["hastaF"];?>">EXPORTAR A EXCEL</a></p>

                            <table width="100%" border="1" rules="all" align="center">
							<thead>
							<tr style="height:30px;">
								<th>No</th>
								<th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
								<th>Productos</th>
								<th>Responsable</th>
								<th>Vendedor</th>
								<th>Valor</th>
								<th>Comisión</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro="";
							if($_POST["responsable"]!=""){$filtro .= " AND (factura_creador='".$_POST["responsable"]."')";}
							if($_POST["vendedor"]!=""){$filtro .= " AND (factura_vendedor='".$_POST["vendedor"]."')";}
							if($_POST["cliente"]!=""){$filtro .= " AND (factura_cliente='".$_POST["cliente"]."')";}

							if(isset($_POST["desdeF"]) and $_POST["desdeF"]!=""){$filtro .= " AND (factura_fecha_propuesta>='".$_POST["desdeF"]."')";}
							if(isset($_POST["hastaF"]) and $_POST["hastaF"]!=""){$filtro .= " AND (factura_fecha_propuesta<='".$_POST["hastaF"]."')";}


							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas
							INNER JOIN clientes ON cli_id=factura_cliente
							INNER JOIN usuarios ON usr_id=factura_creador
							WHERE factura_id=factura_id $filtro
							ORDER BY factura_vendedor");
							
							$no = 1;
							$totalVendidas = 0;
							$totalNoVendidas = 0;
							while($res = mysqli_fetch_array($consulta)){
								
								$vendedor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['factura_vendedor']."'"));

								$valorFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(czpp_cantidad * czpp_valor) FROM cotizacion_productos"));

								$pCom = $configuracion['conf_comision_vendedores']/100;

								$comision = ($valorFactura[0] * $pCom);

								$totalComision += $comision;
							?>
							<tr>
								<td align="center"><?=$no;?></td>
                                <td align="center"><a href="#../cotizaciones-editar.php?id=<?=$res['cotiz_id'];?>" target="_blank"><?=$res['factura_id'];?></a></td>
                                <td><?=$res['factura_fecha_propuesta'];?></td>
                                <td><?=strtoupper($res['cli_nombre']);?></td>
								<td>
									<?php
										$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
										INNER JOIN productos ON prod_id=czpp_producto
										WHERE czpp_cotizacion='".$res['factura_id']."' AND czpp_tipo=4");
										$i = 1;
										while($prod = mysqli_fetch_array($productos)){
											echo "<b>".$i.".</b> ".$prod['prod_nombre'].", ";
											$i++;
										}
									?>
								</td>
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=strtoupper($vendedor['usr_nombre']);?></td>
								<td align="center">$<?=number_format($valorFactura[0],0,".",".");?></td>
								<td align="center">$<?=number_format($comision,0,".",".");?></td>
                                </tr>
                            <?php $no++;}?>
							</tbody>

							<tfoot>
										<tr style="height: 30px; font-weight: bold; font-size: large;">
											<td align="right" colspan="8">Total</td>
											<td align="center">$<?=number_format($totalComision,0,".",".");?></td>
										</tr>
							</tfoot>
							
							</table>

</body>
</html>