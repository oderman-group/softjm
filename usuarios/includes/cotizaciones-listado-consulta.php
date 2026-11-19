<?php
/**
 * Consulta paginada del listado de cotizaciones.
 * Deja: $cotizacionesFilas, $numTotalCotizaciones, $porPaginaCotizacion,
 * $paginaListaCotizaciones, $paginasCotizaciones, $offsetCotizaciones,
 * $tamanosPaginaCotizacion, $urlPaginaCotizacion.
 */

$filtro = '';
if (isset($_GET['q']) && $_GET['q'] !== '') {
	$filtro .= " AND cotiz_id='" . $conexionBdPrincipal->real_escape_string($_GET['q']) . "'";
}
if (isset($_GET['dctoEspecial']) && $_GET['dctoEspecial'] !== '') {
	$filtro .= " AND cotiz_descuentos_especiales=1";
}
if ($datosUsuarioActual['usr_tipo'] != ADMIN) {
	$filtro .= ' AND cli_ciudad!="1122"';
}
if (isset($_GET['usuario_resp']) && $_GET['usuario_resp'] !== '') {
	$filtro .= " AND cotiz_creador='" . intval($_GET['usuario_resp']) . "'";
}
if (isset($_GET['tipo_cot']) && $_GET['tipo_cot'] !== '') {
	$filtro .= " AND cotiz_es_precotizacion='" . intval($_GET['tipo_cot']) . "'";
}
if (isset($_GET['genero_pedido']) && $_GET['genero_pedido'] !== '') {
	if ($_GET['genero_pedido'] == '1') {
		$filtro .= " AND EXISTS (SELECT 1 FROM pedidos WHERE pedid_cotizacion=cotiz_id AND pedid_id_empresa='" . intval($idEmpresa) . "')";
	} else {
		$filtro .= " AND NOT EXISTS (SELECT 1 FROM pedidos WHERE pedid_cotizacion=cotiz_id AND pedid_id_empresa='" . intval($idEmpresa) . "')";
	}
}
if (isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] !== '') {
	$filtro .= " AND cotiz_fecha_propuesta >= '" . $conexionBdPrincipal->real_escape_string($_GET['fecha_inicio']) . " 00:00:00'";
}
if (isset($_GET['fecha_fin']) && $_GET['fecha_fin'] !== '') {
	$filtro .= " AND cotiz_fecha_propuesta <= '" . $conexionBdPrincipal->real_escape_string($_GET['fecha_fin']) . " 23:59:59'";
}

$usuarioIdCotiz = intval($_SESSION['id'] ?? 0);
if ($datosUsuarioActual['usr_tipo'] != ADMIN) {
	$filtro .= " AND cli_zona IN (
		SELECT zpu_zona FROM zonas_usuarios WHERE zpu_usuario='" . $usuarioIdCotiz . "'
	)";
}
if (!Modulos::validarRol([395], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
	$filtro .= " AND (cotiz_creador='" . $usuarioIdCotiz . "' OR cotiz_vendedor='" . $usuarioIdCotiz . "')";
}

$filtroEmpresa = " AND cotiz_id_empresa='" . intval($idEmpresa) . "'";
$cteCotiz = (isset($_GET['cte']) && $_GET['cte'] !== '') ? intval($_GET['cte']) : 0;

if ($cteCotiz > 0) {
	$fromConteo = "FROM cotizacion
		INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='" . $cteCotiz . "'";
} else {
	$fromConteo = "FROM cotizacion
		INNER JOIN clientes ON cli_id=cotiz_cliente
		INNER JOIN usuarios ON usr_id=cotiz_creador";
}

$conteo = $conexionBdPrincipal->query("SELECT COUNT(*) " . $fromConteo . " WHERE 1=1" . $filtroEmpresa . $filtro);
$filaConteo = $conteo ? mysqli_fetch_array($conteo, MYSQLI_NUM) : array(0);
$numTotalCotizaciones = intval($filaConteo[0] ?? 0);

$tamanosPaginaCotizacion = array(10, 25, 50, 100, 200);
$porPaginaCotizacion = (isset($_GET['por']) && in_array((int) $_GET['por'], $tamanosPaginaCotizacion, true))
	? (int) $_GET['por']
	: 10;
$paginasCotizaciones = max(1, (int) ceil($numTotalCotizaciones / $porPaginaCotizacion));
$paginaListaCotizaciones = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, (int) $_GET['inicio']) : 1;
if ($paginaListaCotizaciones > $paginasCotizaciones) {
	$paginaListaCotizaciones = $paginasCotizaciones;
}
$offsetCotizaciones = ($paginaListaCotizaciones - 1) * $porPaginaCotizacion;

$urlPaginaCotizacion = function ($pagina, $por) {
	$params = $_GET;
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
	return 'cotizaciones.php' . ($query !== '' ? '?' . $query : '');
};

if ($cteCotiz > 0) {
	$fromLista = "FROM cotizacion
		INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='" . $cteCotiz . "'
		LEFT JOIN usuarios creador ON creador.usr_id=cotiz_creador";
} else {
	$fromLista = "FROM cotizacion
		INNER JOIN clientes ON cli_id=cotiz_cliente
		INNER JOIN usuarios creador ON creador.usr_id=cotiz_creador";
}
$fromLista .= " LEFT JOIN usuarios vendedor ON vendedor.usr_id=cotiz_vendedor AND vendedor.usr_id_empresa='" . intval($idEmpresa) . "'";

$consultaLista = $conexionBdPrincipal->query("SELECT cotiz_id, cotiz_fecha_propuesta, cotiz_creador, cotiz_vendedor, cotiz_vendida, cotiz_ticket, cotiz_es_precotizacion,
	cli_id, cli_nombre, creador.usr_nombre AS responsable_nombre, vendedor.usr_nombre AS vendedor_nombre
	" . $fromLista . "
	WHERE 1=1" . $filtroEmpresa . $filtro . "
	ORDER BY cotiz_id DESC
	LIMIT " . intval($offsetCotizaciones) . ", " . intval($porPaginaCotizacion));

$cotizacionesFilas = array();
$idsCotizacion = array();
if ($consultaLista) {
	while ($fila = mysqli_fetch_assoc($consultaLista)) {
		$idFila = (int) $fila['cotiz_id'];
		$fila['pedido'] = null;
		$fila['productos'] = array();
		$fila['combos'] = array();
		$fila['servicios'] = array();
		$cotizacionesFilas[$idFila] = $fila;
		$idsCotizacion[] = $idFila;
	}
}

if ($idsCotizacion) {
	$listaIds = implode(',', $idsCotizacion);
	$sqlPedidos = "SELECT pedid_cotizacion, pedid_id, pedid_fecha_creacion, pedid_ofima_estado, pedid_ofima_respuesta, pedid_ofima_integrado
		FROM pedidos
		WHERE pedid_cotizacion IN (" . $listaIds . ") AND pedid_id_empresa='" . intval($idEmpresa) . "'
		ORDER BY pedid_id DESC";
	$consultaPedidos = $conexionBdPrincipal->query($sqlPedidos);
	if (!$consultaPedidos) {
		$consultaPedidos = $conexionBdPrincipal->query("SELECT pedid_cotizacion, pedid_id, pedid_fecha_creacion
			FROM pedidos
			WHERE pedid_cotizacion IN (" . $listaIds . ") AND pedid_id_empresa='" . intval($idEmpresa) . "'
			ORDER BY pedid_id DESC");
	}
	if ($consultaPedidos) {
		while ($pedido = mysqli_fetch_assoc($consultaPedidos)) {
			$idCot = (int) $pedido['pedid_cotizacion'];
			if (isset($cotizacionesFilas[$idCot]) && $cotizacionesFilas[$idCot]['pedido'] === null) {
				$cotizacionesFilas[$idCot]['pedido'] = $pedido;
			}
		}
	}

	$tipoCotz = (int) CZPP_TIPO_COTZ;
	$consultaItems = $conexionBdPrincipal->query("SELECT czpp_cotizacion, prod_nombre, combo_nombre, serv_nombre
		FROM cotizacion_productos
		LEFT JOIN productos ON prod_id=czpp_producto
		LEFT JOIN combos ON combo_id=czpp_combo
		LEFT JOIN servicios ON serv_id=czpp_servicio
		WHERE czpp_tipo=" . $tipoCotz . " AND czpp_cotizacion IN (" . $listaIds . ")
		ORDER BY czpp_cotizacion, czpp_orden, czpp_id");
	if ($consultaItems) {
		while ($item = mysqli_fetch_assoc($consultaItems)) {
			$idCot = (int) $item['czpp_cotizacion'];
			if (!isset($cotizacionesFilas[$idCot])) {
				continue;
			}
			if (!empty($item['prod_nombre'])) {
				$cotizacionesFilas[$idCot]['productos'][] = $item['prod_nombre'];
			}
			if (!empty($item['combo_nombre'])) {
				$cotizacionesFilas[$idCot]['combos'][] = $item['combo_nombre'];
			}
			if (!empty($item['serv_nombre'])) {
				$cotizacionesFilas[$idCot]['servicios'][] = $item['serv_nombre'];
			}
		}
	}
}
