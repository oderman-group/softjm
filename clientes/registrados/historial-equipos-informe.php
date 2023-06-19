<?php include("../../conexion.php"); ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - HISTORIAL DE EQUIPOS</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">HISTORIAL DE EQUIPOS</h2>
	<table width="90%" border="1" rules="all" align="center">
		<thead>
			<tr>
				<th>No</th>
				<th>Entrada</th>
				<th>Salida</th>
				<th>Equipo</th>
				<th>Referencia</th>
				<th>Serial</th>
				<th>Asesor</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM remisiones
							INNER JOIN usuarios ON usr_id=rem_asesor
							WHERE rem_cliente='" . $_GET["cte"] . "'
							");
			$no = 1;
			while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
			?>
				<tr style="text-align: center;">
					<td><?= $no; ?></td>
					<td><?= $res['rem_fecha']; ?></td>
					<td><?= $res['rem_fecha_salida']; ?></td>
					<td><?= $res['rem_equipo']; ?></td>
					<td><?= $res['rem_referencia']; ?></td>
					<td><?= $res['rem_serial']; ?></td>
					<td><?= $res['usr_nombre']; ?></td>
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>

</body>

</html>