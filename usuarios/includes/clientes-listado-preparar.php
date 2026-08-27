<?php

/**
 * Prepara KPIs, permisos y analytics del listado de clientes.
 * Requiere: $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion, $idEmpresa
 */

$clienteConMasVenta = Cliente::obtenerDatosClienteConMasComprasAgnoActual($idEmpresa, $conexionBdPrincipal) ?: [
    'cantidad'         => 0,
    'factura_cliente'  => 0,
    'nombreCliente'    => 'Sin datos',
];
$clientesNuevosEsteMes = Cliente::clientesNuevosEstesMes($idEmpresa, $conexionBdPrincipal);
$conteoPorDepartamento = Cliente::conteoClientesPorDepartamento($idEmpresa, $conexionBdPrincipal, $conexionBdAdmin);
$conteoPorGrupoDealer  = Cliente::conteoClientesPorGrupoDealer($idEmpresa, $conexionBdPrincipal);

$listadoPermisos = [
    'restringirZona'           => !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'excluirCiudadDesconocida' => Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'verTodosDepartamentos'    => Modulos::validarRol([387], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'agregarCliente'           => Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'importarClientes'         => Modulos::validarRol([252], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'imprimirInforme'          => Modulos::validarRol([103], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'exportarExcel'            => Modulos::validarRol([264], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'cambiarClaves'            => Modulos::validarRol([57], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'verPapelera'              => Modulos::validarRol([2], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
];

$clientesAnioActual = intval(date('Y'));
$clientesAnalytics = Cliente::obtenerEstadisticasAnuales(
    $conexionBdPrincipal,
    $clientesAnioActual,
    intval($idEmpresa),
    intval($_SESSION['id']),
    $listadoPermisos['restringirZona'],
    $listadoPermisos['excluirCiudadDesconocida']
);

$clientesResumenAnual = $clientesAnalytics['resumen'] ?? [];
$clientesCartera      = $clientesAnalytics['cartera'] ?? [];

$gruposDealerListado = [];
$consultaGrupos = $conexionBdPrincipal->query("
    SELECT deal_id, deal_nombre
    FROM dealer
    WHERE deal_id_empresa = '" . intval($idEmpresa) . "'
    ORDER BY deal_nombre
");
while ($consultaGrupos && ($grupo = mysqli_fetch_array($consultaGrupos, MYSQLI_ASSOC))) {
    $gruposDealerListado[] = $grupo;
}

$departamentosListado = [];
if ($listadoPermisos['verTodosDepartamentos']) {
    $consultaDeptos = $conexionBdAdmin->query("
        SELECT dep_id, dep_nombre
        FROM localidad_departamentos
        ORDER BY dep_nombre
    ");
} else {
    $consultaDeptos = $conexionBdAdmin->query("
        SELECT dep.dep_id, dep.dep_nombre
        FROM " . BDADMIN . ".localidad_departamentos dep
        INNER JOIN " . MAINBD . ".zonas_usuarios zu
            ON zu.zpu_usuario = '" . intval($_SESSION['id']) . "'
           AND zu.zpu_zona = dep.dep_id
        ORDER BY dep.dep_nombre
    ");
}
while ($consultaDeptos && ($depto = mysqli_fetch_array($consultaDeptos, MYSQLI_ASSOC))) {
    $departamentosListado[] = $depto;
}

$filtrosGetPreservados = array_intersect_key($_GET, array_flip([
    'dpto', 'tipoDoc', 'categoria', 'grupo', 'pap', 'clientesNuevos', 'buscar',
    'fecha_registro_inicio', 'fecha_registro_fin', 'fecha_ingreso_inicio', 'fecha_ingreso_fin',
]));
