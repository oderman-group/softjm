<?php
include("../sesion.php");

$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"));
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - COTIZACIONES CON SERVICIOS</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">COTIZACIONES CON SERVICIOS</h2>
	<div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>
	<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px;">
				<th>No</th>
				<th>ID</th>
				<th>Fecha Propuesta</th>
				<th>Ciudad, Departamento</th>
				<th>Cliente</th>
				<th>Servicios</th>
				<th>Responsable</th>
				<th>Vendedor</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$filtro = "";
			if ($_POST["responsable"] != "") {
				$filtro .= " AND (cotiz_creador='" . $_POST["responsable"] . "')";
			}
			if ($_POST["vendedor"] != "") {
				$filtro .= " AND (cotiz_vendedor='" . $_POST["vendedor"] . "')";
			}
			if ($_POST["cliente"] != "") {
				$filtro .= " AND (cotiz_cliente='" . $_POST["cliente"] . "')";
			}
			if ($_POST["estado"] == 1) {
				$filtro .= " AND (cotiz_vendida=1)";
			}
			if ($_POST["estado"] == 2) {
				$filtro .= " AND (cotiz_vendida='0' OR cotiz_vendida IS NULL)";
			}

			if (isset($_POST["desdeF"]) and $_POST["desdeF"] != "") {
				$filtro .= " AND (cotiz_fecha_propuesta>='" . $_POST["desdeF"] . "')";
			}
			if (isset($_POST["hastaF"]) and $_POST["hastaF"] != "") {
				$filtro .= " AND (cotiz_fecha_propuesta<='" . $_POST["hastaF"] . "')";
			}

			$filtroCli = '';
			if ($_POST["ciudad"] != "") {
				$filtroCli .= " AND cli_ciudad='" . $_POST["ciudad"] . "'";
			}

			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion 
			INNER JOIN clientes ON cli_id=cotiz_cliente 
			LEFT JOIN localidad_ciudades ON ciu_id=cli_ciudad
			LEFT JOIN localidad_departamentos ON dep_id=ciu_departamento
			INNER JOIN usuarios ON usr_id=cotiz_creador
			WHERE cotiz_id=cotiz_id $filtro ");

			$no = 1;
			$totalVendidas = 0;
			$totalNoVendidas = 0;
			while ($res = mysqli_fetch_array($consulta)) {
				
				$consultaServicios = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
				INNER JOIN servicios ON serv_id=czpp_servicio
				WHERE czpp_tipo=1 AND czpp_cotizacion='" . $res['cotiz_id'] . "'");
				$numServicios=mysqli_num_rows($consultaServicios);
				if($numServicios>0){

					$vendedor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $res['cotiz_vendedor'] . "'"));

					switch ($res['cotiz_vendida']) {
						case 0:
							$estadoF = 'No vendida';
							break;
						case 1:
							$estadoF = 'Vendida';
							break;

						default:
							$estadoF = 'No Vendida';
							break;
					}

					if ($res['cotiz_vendida'] == 1) {
						$totalVendidas++;
					} else {
						$totalNoVendidas++;
					}
			?>
				<tr>
					<td align="center"><?= $no; ?></td>
					<td align="center"><a href="../cotizaciones-editar.php?id=<?= $res['cotiz_id']; ?>" target="_blank"><?= $res['cotiz_id']; ?></a></td>
					<td><?= $res['cotiz_fecha_propuesta']; ?></td>
					<td><?= $res['ciu_nombre'] . ", " . $res['dep_nombre']; ?></td>
					<td><?= strtoupper($res['cli_nombre']); ?></td>
					<td>
						<?php
						$i = 1;
						while ($servicios = mysqli_fetch_array($consultaServicios)) {
							echo "<b>" . $i . ".</b> " . $servicios['serv_nombre'] . ", ";
							$i++;
						}
						?>
					</td>
					<td><?= strtoupper($res['usr_nombre']); ?></td>
					<td><?= strtoupper($vendedor['usr_nombre']); ?></td>
					<td><?= $estadoF; ?></td>
				</tr>
			<?php
					$no++;
					}
				}
			?>
		</tbody>
	</table>

	<p>
		Total Facturadas: <?= $totalVendidas; ?><br>
		Total No Facturadas: <?= $totalNoVendidas; ?><br>
	</p>

</body>

</html>