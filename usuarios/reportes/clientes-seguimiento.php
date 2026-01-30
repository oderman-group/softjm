<?php include("../sesion.php");?>
<?php include("../../conexion.php");?>
<?php
if(isset($_POST["formato"]) && $_POST["formato"]=="2"){
	$params = array(
		'exp' => 4,
		'usuarioR' => isset($_POST["usuarioR"]) ? $_POST["usuarioR"] : '',
		'cliente' => isset($_POST["cliente"]) ? $_POST["cliente"] : '',
		'tipoS' => isset($_POST["tipoS"]) ? $_POST["tipoS"] : '',
		'cotizacion' => isset($_POST["cotizacion"]) ? $_POST["cotizacion"] : '',
		'venta' => isset($_POST["venta"]) ? $_POST["venta"] : '',
		'datos' => isset($_POST["datos"]) ? $_POST["datos"] : '',
		'demostracion' => isset($_POST["demostracion"]) ? $_POST["demostracion"] : '',
		'visita' => isset($_POST["visita"]) ? $_POST["visita"] : '',
		'desde' => isset($_POST["desde"]) ? $_POST["desde"] : '',
		'hasta' => isset($_POST["hasta"]) ? $_POST["hasta"] : '',
		'orden' => isset($_POST["orden"]) ? $_POST["orden"] : 'cseg_id',
		'formaOrden' => isset($_POST["formaOrden"]) ? $_POST["formaOrden"] : 'ASC',
		'departamento' => isset($_POST["departamento"]) ? $_POST["departamento"] : '',
		'tipoDocumento' => isset($_POST["tipoDocumento"]) ? $_POST["tipoDocumento"] : '',
		'ciudad' => isset($_POST["ciudad"]) ? $_POST["ciudad"] : ''
	);
	header("Location:../excel-exportar.php?".http_build_query($params));
	exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Informe - Seguimiento de clientes</title>
	<style>
		* { box-sizing: border-box; }
		body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; padding: 20px; background: #fff; }
		.reporte-header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #2563eb; }
		.reporte-header h1 { margin: 0; font-size: 22px; font-weight: 600; color: #1e40af; }
		.reporte-header h2 { margin: 8px 0 0; font-size: 16px; font-weight: 500; color: #64748b; }
		.reporte-header .fecha-gen { margin-top: 8px; font-size: 11px; color: #94a3b8; }
		.leyenda { font-size: 11px; color: #64748b; margin-bottom: 12px; }
		.tabla-wrap { overflow-x: auto; margin-bottom: 28px; }
		table { width: 100%; border-collapse: collapse; font-size: 11px; }
		thead th { background: #1e40af; color: #fff; font-weight: 600; text-align: left; padding: 10px 8px; white-space: nowrap; border: 1px solid #1e3a8a; }
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
			.leyenda { font-size: 9px; color: #333; }
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
if(isset($_POST["usuarioR"]) && $_POST["usuarioR"]!="") { $filtro .= " AND (cseg_usuario_responsable='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["usuarioR"])."')"; }
if(isset($_POST["cliente"]) && $_POST["cliente"]!="") { $filtro .= " AND (cseg_cliente='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["cliente"])."')"; }
if(isset($_POST["tipoS"]) && $_POST["tipoS"]!="") { $filtro .= " AND (cseg_tipo='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["tipoS"])."')"; }
if(isset($_POST["cotizacion"]) && $_POST["cotizacion"]!="") { $filtro .= " AND (cseg_cotizo='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["cotizacion"])."')"; }
if(isset($_POST["venta"]) && $_POST["venta"]!="") { $filtro .= " AND (cseg_vendio='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["venta"])."')"; }
if(isset($_POST["datos"]) && $_POST["datos"]!="") { $filtro .= " AND (cseg_consiguio_datos='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["datos"])."')"; }
if(isset($_POST["demostracion"]) && $_POST["demostracion"]!="") { $filtro .= " AND (cseg_demostracion='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["demostracion"])."')"; }
if(isset($_POST["visita"]) && $_POST["visita"]!="") { $filtro .= " AND (cseg_visita='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["visita"])."')"; }
if(isset($_POST["desde"]) && $_POST["desde"]!="") { $filtro .= " AND (cseg_fecha_contacto>='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["desde"])."')"; }
if(isset($_POST["hasta"]) && $_POST["hasta"]!="") { $filtro .= " AND (cseg_fecha_contacto<='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["hasta"])."')"; }

$filtro2 = '';
if(isset($_POST["departamento"]) && $_POST["departamento"]!="") { $filtro2 .= " AND dep_id='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["departamento"])."'"; }
$filtroCli = '';
if(isset($_POST["ciudad"]) && $_POST["ciudad"]!="") { $filtroCli .= " AND cli_ciudad='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["ciudad"])."'"; }
if(isset($_POST["tipoDocumento"]) && $_POST["tipoDocumento"]!="") { $filtroCli .= " AND cli_tipo_documento='".mysqli_real_escape_string($conexionBdPrincipal, $_POST["tipoDocumento"])."'"; }

$orden = (isset($_POST["orden"]) && $_POST["orden"]!="") ? preg_replace('/[^a-z_]/', '', $_POST["orden"]) : 'cseg_id';
$formaOrden = (isset($_POST["formaOrden"]) && strtoupper($_POST["formaOrden"])==='DESC') ? 'DESC' : 'ASC';

$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM cliente_seguimiento
	INNER JOIN clientes_tikets ON tik_id=cseg_tiket
	INNER JOIN clientes ON cli_id=cseg_cliente ".$filtroCli."
	INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
	INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento ".$filtro2."
	INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
	WHERE cseg_id=cseg_id ".$filtro."
	ORDER BY ".$orden." ".$formaOrden);

$opcionesSino = array("NO", "SI");
$filas = array();
while($res = mysqli_fetch_array($consulta)) { $filas[] = $res; }

$resumenConCotiz = 0;
$resumenConVenta = 0;
$resumenConsiguioDatos = 0;
$resumenDemostracion = 0;
$resumenVisita = 0;
$resumenCompletado = 0;
$resumenPendiente = 0;
foreach($filas as $res) {
	if(!empty($res['cseg_cotizo'])) $resumenConCotiz++;
	if(!empty($res['cseg_vendio'])) $resumenConVenta++;
	if(!empty($res['cseg_consiguio_datos'])) $resumenConsiguioDatos++;
	if(!empty($res['cseg_demostracion'])) $resumenDemostracion++;
	if(!empty($res['cseg_visita'])) $resumenVisita++;
	if(isset($res['cseg_realizado']) && $res['cseg_realizado']==1) $resumenCompletado++; else $resumenPendiente++;
}
?>

	<div class="reporte-header">
		<h1>Informe de seguimiento de clientes</h1>
		<h2>Seguimiento de clientes</h2>
		<div class="fecha-gen">Generado el <?= date('d/m/Y H:i'); ?></div>
	</div>

	<p class="leyenda">DT = Consiguió datos | CZ = Hubo cotización | VT = Hubo venta</p>

	<div class="tabla-wrap">
		<table>
			<thead>
				<tr>
					<th class="num">No</th>
					<th>Ticket</th>
					<th>Fecha contacto</th>
					<th>Ciudad, Depto</th>
					<th>Cliente</th>
					<th>Responsable</th>
					<th class="num">CZ</th>
					<th class="num">VT</th>
					<th class="num">DT</th>
					<th class="num">Demostración</th>
					<th class="num">Visita</th>
					<th>Observación</th>
					<th>Próx. contacto</th>
					<th>Encargado</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			foreach($filas as $res) {
				$encargado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT usr_nombre FROM usuarios WHERE usr_id='".mysqli_real_escape_string($conexionBdPrincipal, (string)($res['cseg_usuario_encargado'] ?? ''))."'"));
				$estado = (isset($res['cseg_realizado']) && $res['cseg_realizado']==1) ? 'Completado' : 'Pendiente';
				$demostracion = !empty($res['cseg_demostracion']) ? 'SI' : 'NO';
				$visita = !empty($res['cseg_visita']) ? 'SI' : 'NO';
			?>
				<tr>
					<td class="num"><?= $no; ?></td>
					<td><a href="../clientes-tikets-editar.php?id=<?= (int)$res['tik_id']; ?>" target="_blank"><?= (int)$res['tik_id']; ?></a><br><small><?= htmlspecialchars($res['tik_fecha_creacion']); ?></small></td>
					<td><a href="../clientes-seguimiento-editar.php?id=<?= (int)$res['cseg_id']; ?>&idTK=<?= (int)$res['tik_id']; ?>" target="_blank"><?= htmlspecialchars($res['cseg_fecha_contacto']); ?></a></td>
					<td><?= htmlspecialchars($res['ciu_nombre'].", ".$res['dep_nombre']); ?></td>
					<td><?= htmlspecialchars($res['cli_nombre']); ?></td>
					<td><?= htmlspecialchars($res['usr_nombre']); ?></td>
					<td class="num"><?= isset($opcionesSino[$res['cseg_cotizo']]) ? $opcionesSino[$res['cseg_cotizo']] : '-'; ?></td>
					<td class="num"><?= isset($opcionesSino[$res['cseg_vendio']]) ? $opcionesSino[$res['cseg_vendio']] : '-'; ?></td>
					<td class="num"><?= isset($opcionesSino[$res['cseg_consiguio_datos']]) ? $opcionesSino[$res['cseg_consiguio_datos']] : '-'; ?></td>
					<td class="num"><?= $demostracion; ?></td>
					<td class="num"><?= $visita; ?></td>
					<td><?= htmlspecialchars($res['cseg_observacion'] ?? ''); ?></td>
					<td><?= htmlspecialchars($res['cseg_fecha_proximo_contacto'] ?? ''); ?></td>
					<td><?= htmlspecialchars($encargado['usr_nombre'] ?? '-'); ?></td>
					<td><?= $estado; ?></td>
				</tr>
			<?php $no++; } ?>
			</tbody>
		</table>
	</div>

	<p class="total-reg">Total registros: <?= $no - 1; ?></p>

	<div class="resumenes">
		<h3>Resumen para gerencia comercial</h3>
		<pre><?php
$total = $no - 1;
echo "Total de seguimientos en el reporte: " . $total . "\n";
echo "Seguimientos con cotización (CZ): " . $resumenConCotiz . ($total ? " (" . round($resumenConCotiz / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos con venta (VT): " . $resumenConVenta . ($total ? " (" . round($resumenConVenta / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos que consiguieron datos (DT): " . $resumenConsiguioDatos . ($total ? " (" . round($resumenConsiguioDatos / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos con demostración: " . $resumenDemostracion . ($total ? " (" . round($resumenDemostracion / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos con visita: " . $resumenVisita . ($total ? " (" . round($resumenVisita / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos completados: " . $resumenCompletado . ($total ? " (" . round($resumenCompletado / $total * 100, 1) . "%)" : "") . "\n";
echo "Seguimientos pendientes: " . $resumenPendiente . ($total ? " (" . round($resumenPendiente / $total * 100, 1) . "%)" : "") . "\n";
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
