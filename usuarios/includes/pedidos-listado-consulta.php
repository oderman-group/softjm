<?php
/**
 * Consulta paginada del listado de pedidos.
 * Deja: $pedidosFilas, $numTotalPedidos, $porPaginaPedido, $paginaListaPedidos,
 * $paginasPedidos, $offsetPedidos, $tamanosPaginaPedido, $urlPaginaPedido.
 */

$filtroPedido = '';
$busquedaPedido = isset($_GET['busqueda']) ? trim((string) $_GET['busqueda']) : '';
if ($busquedaPedido !== '') {
	$busquedaSql = $conexionBdPrincipal->real_escape_string($busquedaPedido);
	$filtroPedido .= " AND (pedid_id='" . $busquedaSql . "' OR cli_nombre='" . $busquedaSql . "' OR creador.usr_nombre='" . $busquedaSql . "')";
}

$ctePedido = (isset($_GET['cte']) && $_GET['cte'] !== '') ? intval($_GET['cte']) : 0;
if ($ctePedido > 0) {
	$filtroPedido .= " AND cli_id='" . $ctePedido . "'";
}

if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
	$filtroPedido .= " AND cli_zona IN (SELECT zpu_zona FROM zonas_usuarios WHERE zpu_usuario='" . intval($_SESSION['id'] ?? 0) . "')";
}

$fromPedido = "FROM pedidos
	INNER JOIN clientes ON cli_id=pedid_cliente
	LEFT JOIN usuarios creador ON creador.usr_id=pedid_creador
	LEFT JOIN usuarios vendedor ON vendedor.usr_id=pedid_vendedor AND vendedor.usr_id_empresa='" . intval($idEmpresa) . "'";
$wherePedido = "WHERE pedid_id_empresa='" . intval($idEmpresa) . "'" . $filtroPedido;

$conteo = $conexionBdPrincipal->query("SELECT COUNT(*) " . $fromPedido . " " . $wherePedido);
$filaConteo = $conteo ? mysqli_fetch_array($conteo, MYSQLI_NUM) : array(0);
$numTotalPedidos = intval($filaConteo[0] ?? 0);

$tamanosPaginaPedido = array(10, 25, 50, 100, 200);
$porPaginaPedido = (isset($_GET['por']) && in_array((int) $_GET['por'], $tamanosPaginaPedido, true))
	? (int) $_GET['por']
	: 10;
$paginasPedidos = max(1, (int) ceil($numTotalPedidos / $porPaginaPedido));
$paginaListaPedidos = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, (int) $_GET['inicio']) : 1;
if ($paginaListaPedidos > $paginasPedidos) {
	$paginaListaPedidos = $paginasPedidos;
}
$offsetPedidos = ($paginaListaPedidos - 1) * $porPaginaPedido;

$urlPaginaPedido = function ($pagina, $por) {
	$params = $_GET;
	unset($params['ofima'], $params['ofima_msg']);
	if ((int) $pagina > 1) {
		$params['inicio'] = (int) $pagina;
	} else {
		unset($params['inicio']);
	}
	if ((int) $por > 0 && (int) $por !== 10) {
		$params['por'] = (int) $por;
	} else {
		unset($params['por']);
	}
	$query = http_build_query($params);
	return 'pedidos.php' . ($query !== '' ? '?' . $query : '');
};

$consultaLista = $conexionBdPrincipal->query("SELECT pedid_id, pedid_fecha_propuesta, pedid_cliente, pedid_creador, pedid_vendedor, pedid_estado, pedid_cotizacion,
	pedid_ofima_estado, pedid_ofima_respuesta, pedid_ofima_integrado,
	cli_id, cli_nombre, cli_zona, creador.usr_nombre AS responsable_nombre, vendedor.usr_nombre AS vendedor_nombre
	" . $fromPedido . " " . $wherePedido . "
	ORDER BY pedid_id DESC
	LIMIT " . intval($offsetPedidos) . ", " . intval($porPaginaPedido));

if (!$consultaLista) {
	$consultaLista = $conexionBdPrincipal->query("SELECT pedid_id, pedid_fecha_propuesta, pedid_cliente, pedid_creador, pedid_vendedor, pedid_estado, pedid_cotizacion,
		cli_id, cli_nombre, cli_zona, creador.usr_nombre AS responsable_nombre, vendedor.usr_nombre AS vendedor_nombre
		" . $fromPedido . " " . $wherePedido . "
		ORDER BY pedid_id DESC
		LIMIT " . intval($offsetPedidos) . ", " . intval($porPaginaPedido));
}

$pedidosFilas = array();
$idsPedido = array();
if ($consultaLista) {
	while ($fila = mysqli_fetch_assoc($consultaLista)) {
		$idFila = (int) $fila['pedid_id'];
		$fila['remision'] = null;
		$pedidosFilas[$idFila] = $fila;
		$idsPedido[] = $idFila;
	}
}

if ($idsPedido) {
	$listaIds = implode(',', $idsPedido);
	$consultaRemisiones = $conexionBdPrincipal->query("SELECT remi_id, remi_pedido, remi_fecha_creacion
		FROM remisionbdg
		WHERE remi_pedido IN (" . $listaIds . ") AND remi_id_empresa='" . intval($idEmpresa) . "'
		ORDER BY remi_id DESC");
	if ($consultaRemisiones) {
		while ($remision = mysqli_fetch_assoc($consultaRemisiones)) {
			$idPed = (int) $remision['remi_pedido'];
			if (isset($pedidosFilas[$idPed]) && $pedidosFilas[$idPed]['remision'] === null) {
				$pedidosFilas[$idPed]['remision'] = $remision;
			}
		}
	}
}
