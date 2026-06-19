<?php
/**
 * Mock local de la API de Ofima para pruebas Orion → Ofima.
 * Recibe POST (crear) y PUT (actualizar) producto, registra el payload en log y responde 200.
 *
 * Uso: En api_configuracion, para productos / orion_ofima, poner como apic_url_endpoint:
 *      http://localhost/softjm/usuarios/api/mock/ofima-productos-recibir.php
 */

header('Content-Type: application/json; charset=utf-8');

$metodo = $_SERVER['REQUEST_METHOD'] ?? '';

if (!in_array($metodo, ['POST', 'PUT'], true)) {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error'   => 'Método no permitido. Use POST o PUT.'
    ]);
    exit;
}

$raw = file_get_contents('php://input');
$datos = $raw ? json_decode($raw, true) : null;

$logDir = __DIR__;
$logFile = $logDir . '/log-ofima-productos.txt';

$entrada = [
    'fecha'   => date('Y-m-d H:i:s'),
    'metodo'  => $metodo,
    'payload' => $datos !== null ? $datos : ['_raw' => $raw]
];

$linea = str_repeat('-', 80) . "\n"
    . json_encode($entrada, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

if (is_writable($logDir)) {
    file_put_contents($logFile, $linea, FILE_APPEND | LOCK_EX);
}

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Mock Ofima: producto recibido (prueba local)',
    'metodo'  => $metodo
]);
