<?php 
include("../../conexion.php");

$equipo = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM remisiones WHERE rem_id='" . $_GET["id"] . "'"), MYSQLI_BOTH);
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - SEGUIMIENTO A EQUIPOS</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">SEGUIMIENTO A EQUIPO </h2>

	<p align="center">
		<b>EQUIPO:</b> <?= $equipo['rem_equipo']; ?><br>
		<b>SERIAL:</b> <?= $equipo['rem_serial']; ?><br>
	</p>

	<table width="90%" border="1" rules="all" align="center">
		<thead>
			<tr>
				<th>No</th>
				<th>Fecha</th>
				<th>Observación</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM remisiones_seguimiento WHERE remseg_id_remisiones='" . $_GET["id"] . "'");
			$no = 1;
			while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
			?>
				<tr>
					<td align="center"><?= $no; ?></td>
					<td><?= $res['remseg_fecha']; ?></td>
					<td><?= $res['remseg_comentario']; ?></td>
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>

</body>

</html>