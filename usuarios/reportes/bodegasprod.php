<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<?php $configuracion = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>INFORMES - BODEGAS Y PRODUCTOS</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

							<h1 style="text-align:center;">INFORMES</h1>
                            <h2 style="text-align:center;">BODEGAS Y PRODUCTOS</h2>
                            <div align="center" style="margin-bottom:5px;"><img src="../files/<?=$configuracion['conf_logo'];?>" height="100" alt="<?=$configuracion['conf_empresa'];?>"></div>
                            <table width="90%" border="1" rules="all" align="center">
							<thead>
							<tr style="height:30px;">
								<th>No</th>
											<th>Bodega</th>
											<th>Producto</th>
											<th>Existencias</th>
											<th>Actualización</th>
											<th>Responsable</th>
							</tr>
							</thead>
							<tbody>
                            <?php
										$filtro = '';
										if ($_GET["bod"] != "") {
											$filtro .= " AND prodb_bodega='" . $_GET["bod"] . "'";
										}
										if ($_GET["prod"] != "") {
											$filtro .= " AND prodb_producto='" . $_GET["prod"] . "'";
										}


										$consulta = mysql_query("SELECT * FROM productos_bodegas
										INNER JOIN productos ON prod_id=prodb_producto
										INNER JOIN bodegas ON bod_id=prodb_bodega
										LEFT JOIN usuarios ON usr_id=prodb_usuario_actualizacion
										WHERE prodb_id=prodb_id $filtro
										", $conexion);
										$no = 1;
										while ($res = mysql_fetch_array($consulta)) {
										?>
							<tr>
								<td align="center"><?=$no;?></td>
                                <td><?= $res['bod_nombre']; ?></td>
												<td><?= $res['prod_nombre']; ?></td>
												<td><?= $res['prodb_existencias']; ?></td>
												<td><?= $res['prodb_fecha_actualizacion']; ?></td>
												<td><?= $res['usr_nombre']; ?></td>
                                </tr>
                            <?php $no++;}?>
							</tbody>
							</table>

</body>
</html>