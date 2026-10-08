<?php
/**
 * Agrega dep_cod_dian / ciu_cod_dian y homologa con códigos DIAN-DIVIPOLA.
 * Ejecutar contra BDADMIN del ambiente actual (sensitive.php).
 */
$_SERVER['HTTP_HOST'] = 'localhost';
require __DIR__ . '/sensitive.php';

echo 'ENV=' . CURRENT_ENVIROMENT . PHP_EOL;
echo 'BDADMIN=' . BDADMIN . ' @ ' . SERVER . PHP_EOL;

$admin = new mysqli(SERVER, USER, PASS, BDADMIN);
if ($admin->connect_error) {
    die('Error conexión BDADMIN: ' . $admin->connect_error . PHP_EOL);
}
$admin->set_charset('utf8mb4');

function columnaExiste(mysqli $db, string $tabla, string $columna): bool
{
    $stmt = $db->prepare(
        "SELECT COUNT(*) AS n
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = ?
           AND COLUMN_NAME = ?"
    );
    $stmt->bind_param('ss', $tabla, $columna);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int) ($row['n'] ?? 0) > 0;
}

function normalizarNombre(string $texto): string
{
    $texto = mb_strtoupper(trim($texto), 'UTF-8');
    $map = [
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
        'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
    ];
    $texto = strtr($texto, $map);
    // transliterator fallback
    if (function_exists('iconv')) {
        $conv = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        if ($conv !== false) {
            $texto = strtoupper($conv);
        }
    }
    $texto = preg_replace('/[^A-Z0-9 ]+/', ' ', $texto);
    $texto = preg_replace('/\s+/', ' ', $texto);
    return trim($texto);
}

function aliasesDepartamento(string $norm): array
{
    $aliases = [$norm];
    $mapa = [
        'GUAJIRA' => ['LA GUAJIRA', 'GUAJIRA'],
        'LA GUAJIRA' => ['LA GUAJIRA', 'GUAJIRA'],
        'NARINO' => ['NARINO', 'NARINO'],
        'BOGOTA' => ['BOGOTA', 'BOGOTA D C', 'BOGOTA DC', 'DISTRITO CAPITAL'],
        'BOGOTA D C' => ['BOGOTA', 'BOGOTA D C', 'BOGOTA DC'],
        'ARCHIPIELAGO DE SAN ANDRES PROVIDENCIA Y SANTA CATALINA' => [
            'SAN ANDRES',
            'SAN ANDRES PROVIDENCIA Y SANTA CATALINA',
            'ARCHIPIELAGO DE SAN ANDRES PROVIDENCIA Y SANTA CATALINA',
        ],
        'SAN ANDRES' => [
            'SAN ANDRES',
            'ARCHIPIELAGO DE SAN ANDRES PROVIDENCIA Y SANTA CATALINA',
        ],
        'VALLE DEL CAUCA' => ['VALLE DEL CAUCA', 'VALLE'],
    ];
    foreach ($mapa as $k => $vals) {
        if ($norm === $k || in_array($norm, $vals, true)) {
            $aliases = array_unique(array_merge($aliases, $vals, [$k]));
            break;
        }
    }
    return $aliases;
}

// 1) Columnas
if (!columnaExiste($admin, 'localidad_departamentos', 'dep_cod_dian')) {
    $ok = $admin->query(
        "ALTER TABLE localidad_departamentos
         ADD COLUMN dep_cod_dian VARCHAR(5) NULL DEFAULT NULL
         COMMENT 'Codigo DIAN/DIVIPOLA departamento'
         AFTER dep_indicativo"
    );
    echo $ok ? "OK columna dep_cod_dian\n" : ('FAIL dep_cod_dian: ' . $admin->error . PHP_EOL);
} else {
    echo "OK dep_cod_dian ya existe\n";
}

if (!columnaExiste($admin, 'localidad_ciudades', 'ciu_cod_dian')) {
    $ok = $admin->query(
        "ALTER TABLE localidad_ciudades
         ADD COLUMN ciu_cod_dian VARCHAR(10) NULL DEFAULT NULL
         COMMENT 'Codigo DIAN/DIVIPOLA municipio'
         AFTER ciu_departamento"
    );
    echo $ok ? "OK columna ciu_cod_dian\n" : ('FAIL ciu_cod_dian: ' . $admin->error . PHP_EOL);
} else {
    echo "OK ciu_cod_dian ya existe\n";
}

// Índices (ignorar si ya existen)
@$admin->query('CREATE INDEX idx_dep_cod_dian ON localidad_departamentos (dep_cod_dian)');
@$admin->query('CREATE INDEX idx_ciu_cod_dian ON localidad_ciudades (ciu_cod_dian)');

// 2) Departamentos DIAN (código 2 dígitos)
$departamentosDian = [
    ['05', 'Antioquia'],
    ['08', 'Atlantico'],
    ['11', 'Bogota'],
    ['13', 'Bolivar'],
    ['15', 'Boyaca'],
    ['17', 'Caldas'],
    ['18', 'Caqueta'],
    ['19', 'Cauca'],
    ['20', 'Cesar'],
    ['23', 'Cordoba'],
    ['25', 'Cundinamarca'],
    ['27', 'Choco'],
    ['41', 'Huila'],
    ['44', 'La Guajira'],
    ['47', 'Magdalena'],
    ['50', 'Meta'],
    ['52', 'Narino'],
    ['54', 'Norte de Santander'],
    ['63', 'Quindio'],
    ['66', 'Risaralda'],
    ['68', 'Santander'],
    ['70', 'Sucre'],
    ['73', 'Tolima'],
    ['76', 'Valle del Cauca'],
    ['81', 'Arauca'],
    ['85', 'Casanare'],
    ['86', 'Putumayo'],
    ['88', 'Archipielago de San Andres, Providencia y Santa Catalina'],
    ['91', 'Amazonas'],
    ['94', 'Guainia'],
    ['95', 'Guaviare'],
    ['97', 'Vaupes'],
    ['99', 'Vichada'],
];

// Alias especiales nombre DB -> código
$aliasDepDb = [
    'GUAJIRA' => '44',
    'BOGOTA' => '11',
];

$depsDb = [];
$res = $admin->query('SELECT dep_id, dep_nombre, dep_pais, dep_cod_dian FROM localidad_departamentos');
while ($row = $res->fetch_assoc()) {
    $depsDb[] = $row;
}

$depIdPorCodigo = [];
$depIdPorNombreNorm = [];
foreach ($depsDb as $d) {
    $n = normalizarNombre($d['dep_nombre']);
    $depIdPorNombreNorm[$n] = (int) $d['dep_id'];
    if (!empty($d['dep_cod_dian'])) {
        $depIdPorCodigo[str_pad($d['dep_cod_dian'], 2, '0', STR_PAD_LEFT)] = (int) $d['dep_id'];
    }
}

$depsActualizados = 0;
$depsCreados = 0;

foreach ($departamentosDian as $item) {
    [$cod, $nombre] = $item;
    $norm = normalizarNombre($nombre);
    $aliases = aliasesDepartamento($norm);

    $depId = null;
    foreach ($aliases as $a) {
        if (isset($depIdPorNombreNorm[$a])) {
            $depId = $depIdPorNombreNorm[$a];
            break;
        }
    }
    // match por alias fijo (Guajira)
    if ($depId === null && isset($aliasDepDb[$norm])) {
        // buscar por código si ya existe otro
    }
    // buscar "Guajira" en DB cuando DIAN es La Guajira
    if ($depId === null && $cod === '44' && isset($depIdPorNombreNorm['GUAJIRA'])) {
        $depId = $depIdPorNombreNorm['GUAJIRA'];
    }

    if ($depId !== null) {
        $stmt = $admin->prepare('UPDATE localidad_departamentos SET dep_cod_dian = ? WHERE dep_id = ?');
        $stmt->bind_param('si', $cod, $depId);
        $stmt->execute();
        $depsActualizados++;
        $depIdPorCodigo[$cod] = $depId;
        echo "UPD dep {$nombre} ({$cod}) id={$depId}\n";
    } else {
        $pais = 1;
        $indicativo = '';
        $stmt = $admin->prepare(
            'INSERT INTO localidad_departamentos (dep_nombre, dep_pais, dep_indicativo, dep_cod_dian)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('siss', $nombre, $pais, $indicativo, $cod);
        $stmt->execute();
        $depId = (int) $admin->insert_id;
        $depsCreados++;
        $depIdPorCodigo[$cod] = $depId;
        $depIdPorNombreNorm[$norm] = $depId;
        echo "INS dep {$nombre} ({$cod}) id={$depId}\n";
    }
}

// Asegurar alias Guajira/Bogota codes if still empty
foreach ($depsDb as $d) {
    $n = normalizarNombre($d['dep_nombre']);
    if (empty($d['dep_cod_dian']) && isset($aliasDepDb[$n])) {
        $cod = $aliasDepDb[$n];
        $depId = (int) $d['dep_id'];
        $stmt = $admin->prepare('UPDATE localidad_departamentos SET dep_cod_dian = ? WHERE dep_id = ?');
        $stmt->bind_param('si', $cod, $depId);
        $stmt->execute();
        $depIdPorCodigo[$cod] = $depId;
        $depsActualizados++;
        echo "UPD alias dep {$d['dep_nombre']} ({$cod}) id={$depId}\n";
    }
}

echo "Departamentos actualizados={$depsActualizados} creados={$depsCreados}\n";

// Recargar mapa dep_id -> cod
$depCodPorId = [];
$res = $admin->query('SELECT dep_id, dep_cod_dian FROM localidad_departamentos WHERE dep_cod_dian IS NOT NULL AND dep_cod_dian <> ""');
while ($row = $res->fetch_assoc()) {
    $depCodPorId[(int) $row['dep_id']] = str_pad($row['dep_cod_dian'], 2, '0', STR_PAD_LEFT);
}

// 3) Ciudades desde DIVIPOLA (datos.gov.co)
echo "Descargando municipios DIVIPOLA...\n";
$url = 'https://www.datos.gov.co/resource/gdxc-w37w.json?$limit=5000';
$json = @file_get_contents($url);
if ($json === false) {
    // fallback curl
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $json = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($json === false || $json === '') {
        die("No se pudo descargar DIVIPOLA: {$err}\n");
    }
}

$municipios = json_decode($json, true);
if (!is_array($municipios)) {
    die("JSON DIVIPOLA inválido\n");
}
echo 'Municipios DIVIPOLA: ' . count($municipios) . PHP_EOL;

// Indexar ciudades DB: por dep_id + nombre normalizado, y por código
$ciudadesDb = [];
$ciuPorDepNombre = []; // dep_id => [norm => row]
$ciuPorCodigo = [];
$res = $admin->query('SELECT ciu_id, ciu_nombre, ciu_departamento, ciu_cod_dian FROM localidad_ciudades');
while ($row = $res->fetch_assoc()) {
    $ciudadesDb[] = $row;
    $depId = (int) $row['ciu_departamento'];
    $n = normalizarNombre($row['ciu_nombre']);
    if (!isset($ciuPorDepNombre[$depId])) {
        $ciuPorDepNombre[$depId] = [];
    }
    $ciuPorDepNombre[$depId][$n] = $row;
    if (!empty($row['ciu_cod_dian'])) {
        $ciuPorCodigo[$row['ciu_cod_dian']] = (int) $row['ciu_id'];
    }
}

$ciuActualizadas = 0;
$ciuCreadas = 0;
$ciuSinDep = 0;

foreach ($municipios as $mpio) {
    $codMpio = str_pad((string) ($mpio['cod_mpio'] ?? ''), 5, '0', STR_PAD_LEFT);
    $codDpto = str_pad((string) ($mpio['cod_dpto'] ?? ''), 2, '0', STR_PAD_LEFT);
    $nomMpio = (string) ($mpio['nom_mpio'] ?? '');
    if ($codMpio === '00000' || $nomMpio === '') {
        continue;
    }

    $depId = $depIdPorCodigo[$codDpto] ?? null;
    if ($depId === null) {
        $ciuSinDep++;
        continue;
    }

    $normCiudad = normalizarNombre($nomMpio);
    $existente = $ciuPorDepNombre[$depId][$normCiudad] ?? null;

    // También buscar por código ya asignado
    if ($existente === null && isset($ciuPorCodigo[$codMpio])) {
        $existente = ['ciu_id' => $ciuPorCodigo[$codMpio]];
    }

    // Alias comunes
    if ($existente === null) {
        $aliasCiudad = [
            'SANTA FE DE ANTIOQUIA' => 'SANTAFE DE ANTIOQUIA',
            'SANTAFE DE ANTIOQUIA' => 'SANTA FE DE ANTIOQUIA',
            'BOGOTA D C' => 'BOGOTA',
            'BOGOTA' => 'BOGOTA D C',
        ];
        foreach ($aliasCiudad as $from => $to) {
            if ($normCiudad === $from && isset($ciuPorDepNombre[$depId][$to])) {
                $existente = $ciuPorDepNombre[$depId][$to];
                break;
            }
            if ($normCiudad === $from) {
                // buscar en todos los deps si es Bogotá
                foreach ($ciuPorDepNombre as $did => $lista) {
                    if (isset($lista[$to]) || isset($lista[$from])) {
                        $existente = $lista[$to] ?? $lista[$from];
                        $depId = $did;
                        break 2;
                    }
                }
            }
        }
    }

    if ($existente !== null) {
        $ciuId = (int) $existente['ciu_id'];
        $stmt = $admin->prepare('UPDATE localidad_ciudades SET ciu_cod_dian = ?, ciu_departamento = ? WHERE ciu_id = ?');
        $stmt->bind_param('sii', $codMpio, $depId, $ciuId);
        $stmt->execute();
        $ciuActualizadas++;
        $ciuPorCodigo[$codMpio] = $ciuId;
        $ciuPorDepNombre[$depId][$normCiudad] = ['ciu_id' => $ciuId, 'ciu_nombre' => $nomMpio, 'ciu_departamento' => $depId, 'ciu_cod_dian' => $codMpio];
    } else {
        $stmt = $admin->prepare(
            'INSERT INTO localidad_ciudades (ciu_nombre, ciu_departamento, ciu_cod_dian) VALUES (?, ?, ?)'
        );
        // Guardar nombre en mayúsculas sin tilde para consistencia con DB actual
        $nombreInsert = mb_strtoupper($nomMpio, 'UTF-8');
        $stmt->bind_param('sis', $nombreInsert, $depId, $codMpio);
        $stmt->execute();
        $ciuId = (int) $admin->insert_id;
        $ciuCreadas++;
        $ciuPorCodigo[$codMpio] = $ciuId;
        $ciuPorDepNombre[$depId][$normCiudad] = ['ciu_id' => $ciuId, 'ciu_nombre' => $nombreInsert, 'ciu_departamento' => $depId, 'ciu_cod_dian' => $codMpio];
    }
}

echo "Ciudades actualizadas={$ciuActualizadas} creadas={$ciuCreadas} sin_departamento={$ciuSinDep}\n";

// Resumen final
$r1 = $admin->query("SELECT COUNT(*) n FROM localidad_departamentos WHERE dep_cod_dian IS NOT NULL AND dep_cod_dian <> ''")->fetch_assoc();
$r2 = $admin->query("SELECT COUNT(*) n FROM localidad_ciudades WHERE ciu_cod_dian IS NOT NULL AND ciu_cod_dian <> ''")->fetch_assoc();
$r3 = $admin->query('SELECT COUNT(*) n FROM localidad_departamentos')->fetch_assoc();
$r4 = $admin->query('SELECT COUNT(*) n FROM localidad_ciudades')->fetch_assoc();

echo "=== RESUMEN ===\n";
echo "Departamentos con código: {$r1['n']} / {$r3['n']}\n";
echo "Ciudades con código: {$r2['n']} / {$r4['n']}\n";
echo "Listo.\n";
