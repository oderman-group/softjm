<?php
include("../sesion.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([37], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para crear productos.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$referencia = trim($_POST['referencia'] ?? '');
$nombre     = trim($_POST['nombre'] ?? '');
$grupo1     = !empty($_POST['grupo1']) && is_numeric($_POST['grupo1']) ? (int) $_POST['grupo1'] : 0;
$categoria  = !empty($_POST['categoria']) && is_numeric($_POST['categoria']) ? (int) $_POST['categoria'] : 0;
$grupo3     = !empty($_POST['grupo3']) && is_numeric($_POST['grupo3']) ? (int) $_POST['grupo3'] : 0;
$marca      = !empty($_POST['marca']) && is_numeric($_POST['marca']) ? (int) $_POST['marca'] : 0;
$proveedor  = isset($_POST['proveedor']) && is_numeric($_POST['proveedor']) ? (int) $_POST['proveedor'] : 0;
$replicar   = isset($_POST['replicar']) && (int) $_POST['replicar'] === 1 ? 1 : 0;

$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
if ($ofimaActiva && $referencia === '') {
    echo json_encode(['success' => false, 'message' => 'El código (referencia) es obligatorio.']);
    exit;
}
if (!$ofimaActiva && $referencia === '') {
    $referencia = 'L' . date('ymdHis') . substr(uniqid(), -4);
}
if ($nombre === '') {
    echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
    exit;
}
if ($grupo1 <= 0) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar el Grupo 1.']);
    exit;
}
if ($categoria <= 0) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar el Grupo 2.']);
    exit;
}
if ($grupo3 <= 0) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar la clasificación.']);
    exit;
}
if ($marca <= 0) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar el grupo.']);
    exit;
}

if (!empty($configuracion['conf_proveedor_cotizacion']) && (int) $configuracion['conf_proveedor_cotizacion'] === 1 && $proveedor <= 0) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar el proveedor.']);
    exit;
}

$referenciaEsc = mysqli_real_escape_string($conexionBdPrincipal, $referencia);
$consultaDup = $conexionBdPrincipal->query(
    "SELECT prod_id, prod_nombre FROM productos
     WHERE prod_referencia='" . $referenciaEsc . "' AND prod_id_empresa='" . (int) $idEmpresa . "' LIMIT 1"
);
if ($consultaDup && mysqli_num_rows($consultaDup) > 0) {
    $dup = mysqli_fetch_array($consultaDup, MYSQLI_ASSOC);
    echo json_encode([
        'success' => false,
        'message' => 'Ya existe un producto con este código: ' . $dup['prod_nombre'] . '.',
    ]);
    exit;
}

$nombreEsc = mysqli_real_escape_string($conexionBdPrincipal, $nombre);

$conexionBdPrincipal->query(
    "INSERT INTO productos(
        prod_nombre, prod_categoria, prod_grupo1, prod_grupo3, prod_marca, prod_referencia, prod_proveedor, prod_id_empresa
    ) VALUES (
        '" . $nombreEsc . "',
        '" . $categoria . "',
        '" . $grupo1 . "',
        '" . $grupo3 . "',
        '" . $marca . "',
        '" . $referenciaEsc . "',
        '" . $proveedor . "',
        '" . (int) $idEmpresa . "'
    )"
);

$idInsertU = (int) mysqli_insert_id($conexionBdPrincipal);
if ($idInsertU <= 0) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el producto. Intente nuevamente.']);
    exit;
}

$conexionBdPrincipal->query(
    "INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_fecha_actualizacion, prodb_usuario_actualizacion)
     VALUES ('" . $idInsertU . "', 1, now(), '" . (int) $_SESSION['id'] . "')"
);

if ($replicar === 1) {
    $conexionBdPrincipal->query(
        "INSERT INTO productos_soptec(prod_nombre, prod_categoria, prod_grupo1, prod_marca)
         VALUES ('" . $nombreEsc . "', '" . $categoria . "', '" . $grupo1 . "', '" . $marca . "')"
    );
}

$syncOfima = ['success' => false, 'message' => 'Sin sincronización'];
try {
    require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';
    $syncOfima = Producto::sincronizarConOfima($idInsertU, $conexionBdPrincipal, $idEmpresa, 'CREATE');
} catch (Exception $e) {
    error_log('Error al sincronizar producto con Ofima: ' . $e->getMessage());
    $syncOfima = ['success' => false, 'error' => $e->getMessage()];
}

echo json_encode([
    'success' => true,
    'message' => 'Producto creado correctamente.',
    'productoId' => $idInsertU,
    'editUrl' => 'productos-editar.php?id=' . $idInsertU . '&msg=1',
    'ofima' => [
        'sincronizado' => !empty($syncOfima['success']),
        'mensaje' => $syncOfima['notificacion']['mensaje']
            ?? $syncOfima['message']
            ?? $syncOfima['error']
            ?? null,
        'tipo' => $syncOfima['notificacion']['tipo']
            ?? (!empty($syncOfima['success']) ? 'success' : 'error'),
        'codigo_http' => $syncOfima['codigo_http'] ?? null,
    ],
]);
