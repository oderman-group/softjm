<?php include("../sesion.php");?>
<?php
if(isset($_POST["formato"]) && $_POST["formato"]==2){
	$params = array(
		'exp' => 3,
		'usuarioR' => isset($_POST["usuarioR"]) ? $_POST["usuarioR"] : '',
		'cliente' => isset($_POST["cliente"]) ? $_POST["cliente"] : '',
		'tipoTK' => isset($_POST["tipoTK"]) ? $_POST["tipoTK"] : '',
		'canal' => isset($_POST["canal"]) ? $_POST["canal"] : '',
		'etapa' => isset($_POST["etapa"]) ? $_POST["etapa"] : '',
		'cotizAsociada' => isset($_POST["cotizAsociada"]) ? $_POST["cotizAsociada"] : '',
		'desde' => isset($_POST["desde"]) ? $_POST["desde"] : '',
		'hasta' => isset($_POST["hasta"]) ? $_POST["hasta"] : '',
		'orden' => isset($_POST["orden"]) ? $_POST["orden"] : 'tik_id',
		'formaOrden' => isset($_POST["formaOrden"]) ? $_POST["formaOrden"] : 'ASC'
	);
	header("Location:../excel-exportar.php?" . http_build_query($params));
	exit();
}
?>
<?php include("../../conexion.php");?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Informe - Tickets de clientes</title>
	<style>
		* { box-sizing: border-box; }
		body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; padding: 20px; background: #fff; }
		.reporte-header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #2563eb; }
		.reporte-header h1 { margin: 0; font-size: 22px; font-weight: 600; color: #1e40af; }
		.reporte-header h2 { margin: 8px 0 0; font-size: 16px; font-weight: 500; color: #64748b; }
		.reporte-header .fecha-gen { margin-top: 8px; font-size: 11px; color: #94a3b8; }
		.tabla-wrap { overflow-x: auto; margin-bottom: 28px; }
		table { width: 100%; border-collapse: collapse; font-size: 11px; }
		thead th { background: #1e40af; color: #fff; font-weight: 600; text-align: left; padding: 10px 8px; white-space: nowrap; border: 1px solid #1e3a8a; }
		thead th { font-size: 11px; }
		tbody td { padding: 8px; border: 1px solid #e2e8f0; }
		tbody tr:nth-child(even) { background: #f8fafc; }
		tbody tr:hover { background: #f1f5f9; }
		a { color: #2563eb; text-decoration: none; }
		a:hover { text-decoration: underline; }
		.num { text-align: center; }
		.resumenes { margin-top: 32px; padding: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; }
		.resumenes h3 { margin: 0 0 14px; font-size: 14px; color: #1e40af; }
		.resumenes pre { margin: 0; font-family: inherit; font-size: 12px; white-space: pre-wrap; word-wrap: break-word; color: #334155; }
		.total-reg { margin-top: 16px; font-weight: 600; color: #1e40af; }
		.no-print { display: block; }
		@media print {
			@page { size: A4 landscape; margin: 12mm; }
			body { padding: 0; margin: 0; font-size: 10px; background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
			.no-print { display: none !important; }
			.reporte-header { margin-bottom: 12px; padding-bottom: 8px; border-bottom-color: #000; }
			.reporte-header h1 { font-size: 16px; color: #000; }
			.reporte-header h2 { font-size: 13px; color: #333; }
			.reporte-header .fecha-gen { font-size: 10px; color: #333; }
			.tabla-wrap { overflow: visible !important; margin-bottom: 14px; page-break-inside: avoid; }
			table { font-size: 8px; table-layout: fixed; width: 100%; border: 1px solid #333; }
			thead { display: table-header-group; }
			thead th { padding: 4px 3px; font-size: 8px; white-space: normal; word-wrap: break-word; overflow-wrap: break-word; background: #1e40af !important; color: #fff !important; border: 1px solid #333; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
			tbody td { padding: 3px; border: 1px solid #333; font-size: 8px; word-wrap: break-word; overflow-wrap: break-word; }
			tbody tr:nth-child(even) { background: #f0f4f8 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
			tbody tr:hover { background: inherit; }
			.total-reg { font-size: 10px; margin-top: 8px; color: #000; }
			.resumenes { margin-top: 14px; padding: 10px; border: 1px solid #333; border-radius: 0; background: #f0f4f8 !important; page-break-inside: avoid; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
			.resumenes h3 { font-size: 11px; color: #000; margin-bottom: 8px; }
			.resumenes pre { font-size: 9px; color: #000; line-height: 1.35; }
			a { color: #000; }
		}
	</style>
</head>
<body>

<?php
$filtro = "";
if(isset($_POST["usuarioR"]) && $_POST["usuarioR"] !== "") { $filtro .= " AND (tik_usuario_responsable='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["usuarioR"])."')"; }
if(isset($_POST["cliente"]) && $_POST["cliente"] !== "") { $filtro .= " AND (tik_cliente='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["cliente"])."')"; }
if(isset($_POST["tipoTK"]) && $_POST["tipoTK"] !== "") { $filtro .= " AND (tik_tipo_tiket='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["tipoTK"])."')"; }
if(isset($_POST["canal"]) && $_POST["canal"] !== "") { $filtro .= " AND (tik_canal='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["canal"])."')"; }
if(isset($_POST["etapa"]) && $_POST["etapa"] !== "") { $filtro .= " AND (tik_etapa='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["etapa"])."')"; }
if(isset($_POST["cotizAsociada"]) && $_POST["cotizAsociada"] !== "") {
	if($_POST["cotizAsociada"] === "1") { $filtro .= " AND (tik_id_cotizacion IS NOT NULL AND tik_id_cotizacion != '')"; }
	else { $filtro .= " AND (tik_id_cotizacion IS NULL OR tik_id_cotizacion = '')"; }
}
if(isset($_POST["desde"]) && $_POST["desde"] !== "") { $filtro .= " AND (tik_fecha_creacion>='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["desde"])."')"; }
if(isset($_POST["hasta"]) && $_POST["hasta"] !== "") { $filtro .= " AND (tik_fecha_creacion<='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["hasta"])."')"; }

$orden = (isset($_POST["orden"]) && $_POST["orden"] !== "") ? preg_replace('/[^a-z_]/', '', $_POST["orden"]) : 'tik_id';
$formaOrden = (isset($_POST["formaOrden"]) && strtoupper($_POST["formaOrden"]) === 'DESC') ? 'DESC' : 'ASC';

$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM clientes_tikets
	INNER JOIN clientes ON cli_id=tik_cliente
	INNER JOIN usuarios ON usr_id=tik_usuario_responsable
	WHERE tik_id=tik_id " . $filtro . "
	ORDER BY " . $orden . " " . $formaOrden);

$canales = array("", "Facebook", "WhatsApp", "Fijo", "Celular", "Personal", "Skype", "Otro");
$opcionesEtapa = array("N/A", "En progreso", "En espera", "Propuesta/Cotización", "Negociación/Revisión", "Cerrado y ganado", "Cerrado y perdido");

$resumenGanados = 0;
$resumenPerdidos = 0;
$resumenSinSeguimiento = 0;
$resumenConCotiz = 0;
$resumenSinCotiz = 0;
$resumenPorEtapa = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0);
$filas = array();
while($res = mysqli_fetch_array($consulta)) {
	$filas[] = $res;
}
foreach($filas as $res) {
	$e = (int)$res['tik_etapa'];
	if($e >= 1 && $e <= 6) $resumenPorEtapa[$e]++;
	if($e == 5) $resumenGanados++;
	if($e == 6) $resumenPerdidos++;
	if(!empty($res['tik_id_cotizacion'])) $resumenConCotiz++; else $resumenSinCotiz++;
}
?>

	<div class="reporte-header">
		<h1>Informe de tickets de clientes</h1>
		<h2>Tickets de clientes</h2>
		<div class="fecha-gen">Generado el <?= date('d/m/Y H:i'); ?></div>
	</div>

	<div class="tabla-wrap">
		<table>
			<thead>
				<tr>
					<th class="num">No</th>
					<th>Tipo</th>
					<th>Fecha contacto</th>
					<th>Cliente</th>
					<th>Responsable</th>
					<th>Canal</th>
					<th>Etapa</th>
					<th>Nº Cotización</th>
					<th>Asunto</th>
					<th class="num">Complet.</th>
					<th class="num">Pend.</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			foreach($filas as $res) {
				switch($res['tik_tipo_tiket']) {
					case 1: $tipoS = 'Comercial'; break;
					case 2: $tipoS = 'Soporte técnico'; break;
					case 3: $tipoS = 'Soporte operativo'; break;
					default: $tipoS = '-'; break;
				}
				switch($res['tik_estado']) {
					case 1: $estado = 'Abierto'; break;
					case 2: $estado = 'Cerrado'; break;
					default: $estado = '-'; break;
				}
				$etapaTexto = isset($opcionesEtapa[(int)$res['tik_etapa']]) ? $opcionesEtapa[(int)$res['tik_etapa']] : '-';

				$seguimientos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "
					SELECT
					(SELECT COUNT(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='".mysqli_real_escape_string($conexionBdPrincipal, $res['tik_id'])."' AND cseg_realizado=1),
					(SELECT COUNT(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='".mysqli_real_escape_string($conexionBdPrincipal, $res['tik_id'])."' AND cseg_realizado IS NULL)"));
				$compl = (int)($seguimientos[0] ?? 0);
				$pend = (int)($seguimientos[1] ?? 0);
				if($compl === 0 && $pend === 0) $resumenSinSeguimiento++;
			?>
				<tr>
					<td class="num"><?= $no; ?></td>
					<td><?= htmlspecialchars($tipoS); ?></td>
					<td><?= htmlspecialchars($res['tik_fecha_creacion']); ?></td>
					<td><?= htmlspecialchars($res['cli_nombre']); ?></td>
					<td><?= htmlspecialchars(strtoupper($res['usr_nombre'])); ?></td>
					<td><?= htmlspecialchars($canales[(int)$res['tik_canal']] ?? '-'); ?></td>
					<td><?= htmlspecialchars($etapaTexto); ?></td>
					<td class="num">
						<?php if(!empty($res['tik_id_cotizacion'])) { ?>
							<a href="../cotizaciones-editar.php?id=<?= (int)$res['tik_id_cotizacion']; ?>" target="_blank">#<?= (int)$res['tik_id_cotizacion']; ?></a>
						<?php } else { ?>—<?php } ?>
					</td>
					<td><a href="../clientes-tikets-editar.php?id=<?= (int)$res['tik_id']; ?>" target="_blank"><?= htmlspecialchars($res['tik_id'] . ' - ' . $res['tik_asunto_principal']); ?></a></td>
					<td class="num"><?= $compl; ?></td>
					<td class="num"><?= $pend; ?></td>
					<td><?= htmlspecialchars($estado); ?></td>
				</tr>
			<?php $no++; } ?>
			</tbody>
		</table>
	</div>

	<p class="total-reg">Total registros: <?= $no - 1; ?></p>

	<div class="resumenes">
		<h3>Resumen para gerencia</h3>
		<pre><?php
$total = $no - 1;
echo "Total de tickets en el reporte: " . $total . "\n";
echo "Tickets cerrados y ganados: " . $resumenGanados . ($total ? " (" . round($resumenGanados / $total * 100, 1) . "%)" : "") . "\n";
echo "Tickets cerrados y perdidos: " . $resumenPerdidos . ($total ? " (" . round($resumenPerdidos / $total * 100, 1) . "%)" : "") . "\n";
echo "Tickets sin seguimiento registrado: " . $resumenSinSeguimiento . ($total ? " (" . round($resumenSinSeguimiento / $total * 100, 1) . "%)" : "") . "\n";
echo "Tickets con cotización asociada: " . $resumenConCotiz . ($total ? " (" . round($resumenConCotiz / $total * 100, 1) . "%)" : "") . "\n";
echo "Tickets sin cotización asociada: " . $resumenSinCotiz . ($total ? " (" . round($resumenSinCotiz / $total * 100, 1) . "%)" : "") . "\n\n";
echo "Por etapa:\n";
foreach($opcionesEtapa as $idx => $nombre) {
	if($idx === 0) continue;
	echo "  - " . $nombre . ": " . ($resumenPorEtapa[$idx] ?? 0) . "\n";
}
		?></pre>
	</div>

	<p class="no-print" style="margin-top:20px; font-size:11px; color:#64748b;">Para imprimir: use el botón o Ctrl+P. En el cuadro de impresión elija <strong>orientación horizontal</strong> para que entren todas las columnas.</p>
	<script>
		if (typeof window.print === 'function') {
			document.body.insertAdjacentHTML('beforeend', '<p class="no-print" style="margin-top:8px;"><button onclick="window.print();" style="padding:8px 16px; cursor:pointer;">Imprimir</button></p>');
		}
	</script>
</body>
</html>
