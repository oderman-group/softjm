<?php
$clienteId = intval($_GET['id'] ?? 0);

if ($clienteId <= 0) {
    header('Location: clientes.php');
    exit;
}

$consulta = $conexionBdPrincipal->query("
    SELECT *
    FROM clientes
    WHERE cli_id = '" . $clienteId . "'
      AND cli_id_empresa = '" . $idEmpresa . "'
    LIMIT 1
");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);

if (!$resultadoD) {
    header('Location: clientes.php');
    exit;
}

foreach ($resultadoD as $campo => $valor) {
    if ($valor === null) {
        $resultadoD[$campo] = '';
    }
}

$diplayNombreEvento = ($resultadoD['cli_referencia'] == 4) ? 'block' : 'none';

$evolucionComercial = Cliente::obtenerEvolucionComercial($clienteId, $idEmpresa, $conexionBdPrincipal);

require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';
ClienteNotaInterna::asegurarTabla($conexionBdPrincipal);

$contadoresCliente  = Cliente::obtenerContadoresRelacionados($clienteId, $idEmpresa, $conexionBdPrincipal);
$gruposSeleccionados = Cliente::obtenerGruposCliente($clienteId, $conexionBdPrincipal);
$asesorSeleccionado  = Cliente::obtenerAsesorCliente($clienteId, $conexionBdPrincipal);

$fechaPrimeraCotizacion = $evolucionComercial['primera_cotizacion'] ?? null;
$fechaPrimeraCompra   = $evolucionComercial['primera_compra'] ?? null;
$fechaUltimaCompra    = $evolucionComercial['ultima_compra'] ?? null;

$categoriasCliente = [
    CLI_CATEGORIA_PROSPECTO => 'Prospecto',
    CLI_CATEGORIA_CLIENTE   => 'Cliente',
    CLI_CATEGORIA_DEALER    => 'Dealer',
];

$nivelesCliente = [
    1 => 'Leads',
    2 => 'Interesado',
    3 => 'Prospecto en proceso',
    4 => 'Cliente A',
    5 => 'Cliente B',
    6 => 'Cliente C',
];

$categoriaActualLabel = $categoriasCliente[$resultadoD['cli_categoria']] ?? 'Sin categoría';
$nivelActualLabel     = $nivelesCliente[$resultadoD['cli_nivel']] ?? 'Sin nivel';
$referenciaActualLabel = !empty($resultadoD['cli_referencia']) && !empty($referenciaLlegada[$resultadoD['cli_referencia']])
    ? $referenciaLlegada[$resultadoD['cli_referencia']]
    : 'Sin referencia';

$listaPaises = [];
$conPais = $conexionBdAdmin->query("SELECT pais_nombre FROM localidad_paises ORDER BY pais_nombre");
while ($resPais = mysqli_fetch_array($conPais, MYSQLI_BOTH)) {
    $listaPaises[] = $resPais['pais_nombre'];
}

$listaCiudades = [];
$conCiudades = $conexionBdAdmin->query("
    SELECT c.ciu_id, c.ciu_nombre, d.dep_nombre
    FROM localidad_ciudades c
    INNER JOIN localidad_departamentos d ON d.dep_id = c.ciu_departamento
    ORDER BY c.ciu_nombre
");
while ($resCiudad = mysqli_fetch_array($conCiudades, MYSQLI_BOTH)) {
    $listaCiudades[] = $resCiudad;
}

$listaZonas = [];
$conZonas = $conexionBdPrincipal->query("SELECT zon_id, zon_nombre FROM zonas WHERE zon_id_empresa = '" . $idEmpresa . "' ORDER BY zon_nombre");
while ($resZona = mysqli_fetch_array($conZonas, MYSQLI_BOTH)) {
    $listaZonas[] = $resZona;
}

$listaDealers = [];
$conDealers = $conexionBdPrincipal->query("SELECT deal_id, deal_nombre FROM dealer WHERE deal_id_empresa = '" . $idEmpresa . "' ORDER BY deal_nombre");
while ($resDealer = mysqli_fetch_array($conDealers, MYSQLI_BOTH)) {
    $listaDealers[] = $resDealer;
}

$listaAsesores = [];
$conAsesores = $conexionBdPrincipal->query("
    SELECT usr_id, usr_nombre
    FROM usuarios
    WHERE usr_bloqueado != 1
      AND usr_id_empresa = '" . $idEmpresa . "'
    ORDER BY usr_nombre
");
while ($resAsesor = mysqli_fetch_array($conAsesores, MYSQLI_BOTH)) {
    $listaAsesores[] = $resAsesor;
}

$nombreAsesorActual = 'Sin asignar';
foreach ($listaAsesores as $asesorItem) {
    if ($asesorSeleccionado && intval($asesorItem['usr_id']) === $asesorSeleccionado) {
        $nombreAsesorActual = strtoupper($asesorItem['usr_nombre']);
        break;
    }
}

if (($resultadoD['cli_pais'] == 'Colombia') || ($resultadoD['cli_pais'] == '1122')) {
    $displayCol  = 'display: block;';
    $displayExtr = 'display: none;';
} else {
    $displayCol  = 'display: none;';
    $displayExtr = 'display: block;';
}

if (Modulos::validarRol([386], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $campoC = 'text';
} else {
    $campoC = 'password';
}

$soloLecturaUsuarioAcceso = empty($resultadoD['cli_usuario_acceso']) ? '' : 'readonly';

$direccionPartes = Cliente::parsearDireccionNomenclatura((string) $resultadoD['cli_direccion']);
$tiposViaDireccion = Cliente::obtenerTiposViaDireccion();
$puntosCardinalesDireccion = Cliente::obtenerPuntosCardinalesDireccion();

require_once RUTA_PROYECTO . '/usuarios/class/Etiqueta.php';

$listaEtiquetasCliente = Etiqueta::listarPorModulo(Etiqueta::MODULO_CLIENTE, $idEmpresa, $conexionBdPrincipal);
$etiquetasCliente = Etiqueta::listarPorEntidad(Etiqueta::MODULO_CLIENTE, $clienteId, $idEmpresa, $conexionBdPrincipal);
$etiquetasClienteSeleccionadas = array_map(static function ($etiqueta) {
    return intval($etiqueta['etiq_id']);
}, $etiquetasCliente);

$zonasUsuarioPermitidas = null;
if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $zonasUsuarioPermitidas = [];
    $conZonasUsuario = $conexionBdPrincipal->query("
        SELECT zpu_zona
        FROM zonas_usuarios
        WHERE zpu_usuario = '" . intval($_SESSION['id']) . "'
    ");
    while ($zonaUsuario = mysqli_fetch_array($conZonasUsuario, MYSQLI_BOTH)) {
        $zonasUsuarioPermitidas[] = intval($zonaUsuario['zpu_zona']);
    }
}
