<?php
/**
 * Homologa marcas JM (jmequipo_jm_dev.marcas) con Orion (mar_id → mar_id_crm).
 * - Asegura columna mar_id_crm.
 * - Empareja por nombre normalizado + aliases.
 * - No renombra marcas JM; solo setea mar_id_crm donde esté NULL.
 *
 * Uso: php sql/homologar_jm_marcas_crm.php
 */
$_SERVER['HTTP_HOST'] = 'localhost';
require __DIR__ . '/../sensitive.php';
require __DIR__ . '/../usuarios/includes/jm-web-conexion.php';
require __DIR__ . '/../usuarios/class/JmWebProductosSync.php';

echo 'ENV=' . CURRENT_ENVIROMENT . PHP_EOL;
echo 'MAINBD=' . MAINBD . ' @ ' . SERVER . PHP_EOL;
echo 'JM_WEB_DB=' . JM_WEB_DB . ' @ ' . JM_WEB_SERVER . PHP_EOL;

$orion = new mysqli(SERVER, USER, PASS, MAINBD);
if ($orion->connect_error) {
    fwrite(STDERR, 'Error Orion: ' . $orion->connect_error . PHP_EOL);
    exit(1);
}
$orion->set_charset('utf8mb4');

$jm = obtenerConexionJmWeb();
if (!$jm) {
    fwrite(STDERR, 'Error JM: ' . jmWebUltimoErrorConexion() . PHP_EOL);
    exit(1);
}

// DDL idempotente
$col = $jm->query(
    "SELECT COUNT(*) AS n FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'marcas' AND COLUMN_NAME = 'mar_id_crm'"
);
if ($col && (int) ($col->fetch_assoc()['n'] ?? 0) === 0) {
    if (!$jm->query(
        "ALTER TABLE marcas
         ADD COLUMN mar_id_crm INT UNSIGNED NULL DEFAULT NULL
         COMMENT 'ID marca en Orion CRM' AFTER mar_icono"
    )) {
        fwrite(STDERR, 'FAIL ALTER mar_id_crm: ' . $jm->error . PHP_EOL);
        exit(1);
    }
    echo "OK columna mar_id_crm creada\n";
} else {
    echo "OK mar_id_crm ya existe\n";
}

$idx = $jm->query(
    "SELECT COUNT(*) AS n FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'marcas' AND INDEX_NAME = 'uk_marcas_mar_id_crm'"
);
if ($idx && (int) ($idx->fetch_assoc()['n'] ?? 0) === 0) {
    if (!$jm->query('CREATE UNIQUE INDEX uk_marcas_mar_id_crm ON marcas (mar_id_crm)')) {
        fwrite(STDERR, 'FAIL INDEX uk_marcas_mar_id_crm: ' . $jm->error . PHP_EOL);
        exit(1);
    }
    echo "OK índice uk_marcas_mar_id_crm creado\n";
} else {
    echo "OK índice uk_marcas_mar_id_crm ya existe\n";
}

$idEmpresa = 1;
$orionMarcas = [];
$resO = $orion->query(
    "SELECT mar_id, mar_nombre
     FROM marcas
     WHERE mar_id_empresa = " . (int) $idEmpresa . "
     ORDER BY mar_id"
);
while ($resO && ($row = $resO->fetch_assoc())) {
    $norm = JmWebProductosSync::normalizarNombreMarca((string) $row['mar_nombre']);
    if ($norm === '') {
        continue;
    }
    // Primer Orion gana si hay colisión de nombre normalizado.
    if (!isset($orionMarcas[$norm])) {
        $orionMarcas[$norm] = [
            'mar_id' => (int) $row['mar_id'],
            'mar_nombre' => (string) $row['mar_nombre'],
        ];
    }
}

$jmMarcas = [];
$resJ = $jm->query('SELECT mar_id, mar_nombre, mar_id_crm FROM marcas ORDER BY mar_id');
while ($resJ && ($row = $resJ->fetch_assoc())) {
    $jmMarcas[] = [
        'mar_id' => (int) $row['mar_id'],
        'mar_nombre' => (string) ($row['mar_nombre'] ?? ''),
        'mar_id_crm' => $row['mar_id_crm'] !== null ? (int) $row['mar_id_crm'] : null,
        'norm' => JmWebProductosSync::normalizarNombreMarca((string) ($row['mar_nombre'] ?? '')),
    ];
}

$relacionadas = 0;
$yaTienen = 0;
$sinMatch = [];

$upd = $jm->prepare('UPDATE marcas SET mar_id_crm = ? WHERE mar_id = ? AND mar_id_crm IS NULL');
if (!$upd) {
    fwrite(STDERR, 'FAIL prepare UPDATE: ' . $jm->error . PHP_EOL);
    exit(1);
}

foreach ($jmMarcas as $jmMarca) {
    if ($jmMarca['mar_id_crm'] !== null && $jmMarca['mar_id_crm'] > 0) {
        $yaTienen++;
        continue;
    }
    $norm = $jmMarca['norm'];
    if ($norm === '' || !isset($orionMarcas[$norm])) {
        $sinMatch[] = $jmMarca['mar_id'] . ':' . $jmMarca['mar_nombre'];
        continue;
    }
    $crmId = $orionMarcas[$norm]['mar_id'];
    $jmId = $jmMarca['mar_id'];
    $upd->bind_param('ii', $crmId, $jmId);
    if ($upd->execute() && $upd->affected_rows > 0) {
        $relacionadas++;
        echo 'REL JM#' . $jmId . ' "' . $jmMarca['mar_nombre'] . '" ← Orion#' . $crmId
            . ' "' . $orionMarcas[$norm]['mar_nombre'] . '"' . PHP_EOL;
    } else {
        // Unique conflict u otro: reportar
        echo 'SKIP JM#' . $jmId . ' "' . $jmMarca['mar_nombre'] . '" → Orion#' . $crmId
            . ' (' . ($jm->error ?: 'sin cambios') . ')' . PHP_EOL;
    }
}
$upd->close();

echo "=== RESUMEN ===\n";
echo 'Orion marcas (empresa ' . $idEmpresa . ') normalizadas: ' . count($orionMarcas) . PHP_EOL;
echo 'JM marcas: ' . count($jmMarcas) . PHP_EOL;
echo 'Ya tenían mar_id_crm: ' . $yaTienen . PHP_EOL;
echo 'Relacionadas ahora: ' . $relacionadas . PHP_EOL;
echo 'JM sin match Orion: ' . count($sinMatch) . PHP_EOL;
if ($sinMatch) {
    echo '  - ' . implode("\n  - ", $sinMatch) . PHP_EOL;
}
echo "Listo.\n";
