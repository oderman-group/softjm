<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para crear clientes.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$nombreOriginal  = trim($_POST['nombre'] ?? '');
$documento       = trim($_POST['documento'] ?? '');
$tipoDocumento   = !empty($_POST['tipoDocumento']) && is_numeric($_POST['tipoDocumento']) ? intval($_POST['tipoDocumento']) : 1;
$ciudad          = !empty($_POST['ciudad']) && is_numeric($_POST['ciudad']) ? intval($_POST['ciudad']) : 0;
$email           = trim($_POST['email'] ?? '');
$telefono        = trim($_POST['telefono'] ?? '');
$celular         = trim($_POST['celular'] ?? '');
$referencia      = trim($_POST['referencia'] ?? '');
$nombreEvento    = trim($_POST['nombreEvento'] ?? '');
$asesor          = !empty($_POST['asesor']) && is_numeric($_POST['asesor']) ? intval($_POST['asesor']) : intval($_SESSION['id']);
$notaInterna     = trim($_POST['notaInterna'] ?? '');

if ($documento === '') {
    echo json_encode(['success' => false, 'message' => 'El número de documento es obligatorio.']);
    exit;
}

if ($ciudad <= 0 || $ciudad === CIUDAD_DESCONOCIDA) {
    echo json_encode(['success' => false, 'message' => 'Debe seleccionar una ciudad válida.']);
    exit;
}

$documentoEsc = mysqli_real_escape_string($conexionBdPrincipal, $documento);
$consultaDuplicado = mysqli_query(
    $conexionBdPrincipal,
    "SELECT cli_id, cli_nombre FROM clientes
     WHERE cli_usuario='" . $documentoEsc . "' AND cli_id_empresa='" . $idEmpresa . "'"
);
if ($consultaDuplicado && mysqli_num_rows($consultaDuplicado) > 0) {
    $clienteDuplicado = mysqli_fetch_array($consultaDuplicado, MYSQLI_BOTH);
    echo json_encode([
        'success' => false,
        'message' => 'Ya existe un cliente con este número de documento: ' . $clienteDuplicado['cli_nombre'] . '.',
    ]);
    exit;
}

if ($nombreOriginal === '') {
    echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
    exit;
}

if ($celular !== '' && !preg_match('/^\d{10}$/', $celular)) {
    echo json_encode(['success' => false, 'message' => 'El celular debe tener 10 dígitos sin espacios ni puntos.']);
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El email no es válido.']);
    exit;
}

$nombre       = mysqli_real_escape_string($conexionBdPrincipal, strtoupper($nombreOriginal));
$email        = mysqli_real_escape_string($conexionBdPrincipal, strtolower($email));
$telefono     = mysqli_real_escape_string($conexionBdPrincipal, $telefono);
$celular      = mysqli_real_escape_string($conexionBdPrincipal, $celular);
$referencia   = mysqli_real_escape_string($conexionBdPrincipal, $referencia);
$nombreEvento = mysqli_real_escape_string($conexionBdPrincipal, $nombreEvento);

$pais = 'Colombia';

$consultaZona = mysqli_query($conexionBdAdmin, "SELECT * FROM localidad_ciudades WHERE ciu_id='" . $ciudad . "'");
$zona         = mysqli_fetch_array($consultaZona, MYSQLI_BOTH);
$zonaId       = $zona ? $zona[2] : '';

if (!$zona) {
    echo json_encode(['success' => false, 'message' => 'La ciudad seleccionada no es válida.']);
    exit;
}

$clave1          = generarClaves();
$clave2          = generarClaves();
$eventoNombre    = ($referencia === '4' && $nombreEvento !== '') ? $nombreEvento : '';

mysqli_query($conexionBdPrincipal, "INSERT INTO clientes(
    cli_nombre, cli_referencia, cli_categoria, cli_email, cli_telefono, cli_ciudad,
    cli_usuario, cli_clave, cli_direccion, cli_zona, cli_fecha_registro, cli_fecha_ingreso,
    cli_nivel, cli_celular, cli_telefonos, cli_sigla, cli_responsable, cli_clave_documentos,
    cli_tipo_documento, cli_pais, cli_ciudad_extranjera, cli_id_empresa, cli_usuario_acceso,
    cli_institucional, cli_nombre_evento, cli_forma_creacion
) VALUES (
    '" . $nombre . "',
    '" . $referencia . "',
    '" . CLI_CATEGORIA_PROSPECTO . "',
    '" . $email . "',
    '" . $telefono . "',
    '" . $ciudad . "',
    '" . $documentoEsc . "',
    '" . $clave1 . "',
    '',
    '" . $zonaId . "',
    now(),
    NULL,
    3,
    '" . $celular . "',
    '',
    '',
    '" . $asesor . "',
    '" . $clave2 . "',
    '" . $tipoDocumento . "',
    '" . $pais . "',
    '',
    '" . $idEmpresa . "',
    '',
    '0',
    '" . $eventoNombre . "',
    'ACCESO_RAPIDO'
)");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);

if (!$idInsertU) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el cliente. Intente nuevamente.']);
    exit;
}

mysqli_query($conexionBdPrincipal, "INSERT INTO sucursales(
    sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_telefonos, sucu_nombre
) VALUES (
    '" . $idInsertU . "',
    '" . $ciudad . "',
    '',
    '" . $telefono . "',
    '" . $celular . "',
    '',
    'Sede principal'
)");

mysqli_query($conexionBdPrincipal, "INSERT INTO contactos(
    cont_nombre, cont_telefono, cont_email, cont_cliente_principal, cont_celular, cont_telefonos
) VALUES (
    '" . $nombre . "',
    '" . $telefono . "',
    '" . $email . "',
    '" . $idInsertU . "',
    '" . $celular . "',
    ''
)");

mysqli_query($conexionBdPrincipal, "INSERT INTO clientes_usuarios(cliu_usuario, cliu_cliente, cliu_fecha) VALUES ('" . $asesor . "', '" . $idInsertU . "', now())");

require_once RUTA_PROYECTO . '/usuarios/class/Notificacion.php';
Notificacion::crearAsignacionCliente(
    $conexionBdPrincipal,
    (int) $idInsertU,
    (int) $asesor,
    (int) $idEmpresa,
    'Nuevo cliente asignado (' . strtoupper($nombreOriginal) . ')'
);

require_once RUTA_PROYECTO . '/usuarios/class/Etiqueta.php';
$etiquetasPost = isset($_POST['etiquetas']) && is_array($_POST['etiquetas']) ? $_POST['etiquetas'] : [];
Etiqueta::sincronizarAsignaciones(
    Etiqueta::MODULO_CLIENTE,
    $idInsertU,
    $etiquetasPost,
    intval($_SESSION['id']),
    intval($idEmpresa),
    $conexionBdPrincipal
);

$advertenciaNota = '';
$notaGuardada = null;
if ($notaInterna !== '') {
    require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';
    $notaId = ClienteNotaInterna::crear($idInsertU, $_SESSION['id'], $idEmpresa, $notaInterna, $conexionBdPrincipal);
    $notaGuardada = (bool) $notaId;
    if (!$notaId) {
        $advertenciaNota = ' El cliente se creó, pero la nota interna no pudo guardarse.';
    }
}

try {
    require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';
    Cliente::sincronizarConOfima($idInsertU, $conexionBdPrincipal, $idEmpresa, 'CREATE');
} catch (Exception $e) {
    error_log('Error al sincronizar cliente con Ofima: ' . $e->getMessage());
}

echo json_encode([
    'success'   => true,
    'message'   => 'Cliente creado correctamente.' . $advertenciaNota,
    'clienteId' => $idInsertU,
    'editUrl'   => 'clientes-editar.php?id=' . $idInsertU . '&msg=1',
    'notaGuardada' => $notaGuardada,
]);
