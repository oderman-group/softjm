<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Cliente extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'clientes';
    public static $primaryKey = 'cli_id';
    public static $tableAs    = 'cli';

    /**
     * Consulta los detalles de un cliente en la base de datos por su ID de cliente y el ID de la empresa.
     *
     * Esta función recibe dos parámetros: el ID del cliente (`$idRegistro`) y el ID de la empresa (`$idEmpresa`). 
     * Realiza una consulta a la base de datos para obtener la información del cliente que coincida con esos valores.
     * Los datos devueltos incluyen el ID del cliente, su categoría y la fecha de ingreso.
     *
     * @param int $idRegistro El ID único del cliente a consultar.
     * @param int $idEmpresa El ID de la empresa a la que pertenece el cliente.
     *
     * @return array|false Devuelve un array asociativo con la información del cliente (ID, categoría, fecha de ingreso) si se encuentra, 
     *                    o `false` si no se encuentra el cliente o hay un error en la consulta.
     */
    public static function consultarClientePorId(int $idRegistro, int $idEmpresa) {
        $predicado = [
            'cli_id'         => $idRegistro,
            'cli_id_empresa' => $idEmpresa
        ];

        $campos = 'cli_id, cli_categoria, cli_fecha_ingreso';

        $consulta = self::Select($predicado, $campos);

        return mysqli_fetch_array($consulta, MYSQLI_BOTH);
    }

    /**
     * Determina si un registro de cliente es válido según su categoría y fecha de ingreso.
     *
     * Esta función verifica si un cliente cumple con dos condiciones:
     * 1. La categoría del cliente no es igual a 1 (`cli_categoria`).
     * 2. La fecha de ingreso del cliente no está vacía (`cli_fecha_ingreso`).
     *
     * Si ambas condiciones se cumplen, la función retorna `true`, indicando que el registro es válido como cliente.
     * Si alguna de las condiciones no se cumple, retorna `false`.
     *
     * @param array $datosRegistro Un arreglo asociativo que contiene los datos del cliente, 
     *                              debe incluir al menos las claves `cli_categoria` y `cli_fecha_ingreso`.
     *
     * @return bool Devuelve `true` si el cliente cumple con las condiciones, `false` en caso contrario.
     */
    public static function esCliente(array $datosRegistro) {
        return $datosRegistro['cli_categoria'] != 1 && !empty($datosRegistro['cli_fecha_ingreso']);
    }

    /**
     * Convierte un registro existente en la base de datos en un cliente si aún no lo es.
     *
     * Esta función primero consulta los datos del cliente usando su ID y el ID de la empresa. 
     * Luego, verifica si el registro ya califica como cliente mediante `self::esCliente()`.
     * 
     * Si el registro **no** es considerado un cliente (categoría distinta de 2 o sin fecha de ingreso), 
     * actualiza los siguientes campos del registro:
     * - `cli_categoria`: Se asigna el valor `2`, indicando que es un cliente.
     * - `cli_nivel`: Se asigna el valor `4` (posiblemente un nivel predeterminado para nuevos clientes).
     * - `cli_fecha_ingreso`: Se actualiza con la fecha actual.
     * 
     * La actualización se realiza en la tabla definida por la propiedad estática `self::$tableName`, 
     * utilizando la clave primaria definida en `self::$primaryKey`.
     *
     * @param int $idRegistro El ID del registro que se desea convertir en cliente.
     * @param int $idEmpresa El ID de la empresa a la que pertenece el registro.
     *
     * @return mixed El resultado del método `actualizarRegistro()` si se realiza la conversión,
     *               o `null` si el registro ya es considerado un cliente y no se realiza ninguna acción.
     */
    public static function convertirACliente(int $idRegistro, int $idEmpresa) {
        $datosRegistro = self::consultarClientePorId($idRegistro, $idEmpresa);
        $esCliente     = self::esCliente($datosRegistro);

        if (!$esCliente) {
            $infoActualizar = [
                'tabla'           => self::$tableName,
                'clave_primaria'  => self::$primaryKey,
                'id_registro'     => $idRegistro,
                'sucp_id_empresa' => $idEmpresa
            ];

            $campos = [
                'cli_categoria'     => 2,
                'cli_nivel'         => 4,
                'cli_fecha_ingreso' => date('Y-m-d')
            ];

            return self::actualizarRegistro($infoActualizar, $campos);
        }
    }

    /**
     * Consulta la fecha de la primera cotización realizada por un cliente en la base de datos.
     *
     * Esta función consulta la base de datos para obtener la fecha de la **primera cotización** realizada 
     * por un cliente en particular. La fecha de la cotización se obtiene utilizando la función `MIN()` 
     * para encontrar la cotización con la fecha más antigua.
     * 
     * La consulta filtra las cotizaciones donde:
     * - El ID del cliente (`cotiz_cliente`) coincide con el ID de cliente proporcionado.
     * - La cotización no es una precotización (`cotiz_es_precotizacion = 0`).
     * - La cotización corresponde a la empresa indicada mediante el ID de empresa.
     * 
     * La función devuelve la fecha de la primera cotización si existe, o `null` si no se encuentran cotizaciones 
     * que cumplan con los criterios.
     *
     * @param int $idRegistro El ID del cliente cuya primera cotización se quiere consultar.
     * @param int $idEmpresa El ID de la empresa para la que se desea obtener las cotizaciones del cliente.
     * @param mysqli $conexionBdPrincipal La conexión activa a la base de datos que se usará para ejecutar la consulta.
     *
     * @return string|null La fecha de la primera cotización en formato `Y-m-d` si se encuentra, o `null` si no hay cotizaciones.
     */
    public static function consultarPrimeraCotizacionCliente(int $idRegistro, int $idEmpresa, $conexionBdPrincipal) {
        $consulta = $conexionBdPrincipal->query("SELECT MIN(cotiz_fecha_propuesta) as fecha FROM cotizacion 
        INNER JOIN clientes ON cli_id=cotiz_cliente AND cotiz_cliente = ".$idRegistro."
        WHERE cotiz_es_precotizacion = 0 AND cotiz_id_empresa='".$idEmpresa."'");

        return mysqli_fetch_array($consulta, MYSQLI_ASSOC)['fecha'];
    }

    /**
     * Obtiene la fecha de la primera compra (factura más antigua) realizada por un cliente.
     *
     * Esta función consulta la base de datos para encontrar la fecha de creación de la primera
     * factura de tipo venta y con estado activo asociada a un cliente específico.
     *
     * @param int $idRegistro         ID del cliente en la base de datos.
     * @param int $idEmpresa          ID de la empresa (actualmente no se utiliza en la consulta).
     * @param mysqli $conexionBdPrincipal Conexión activa a la base de datos principal.
     *
     * @return string|null Fecha de la primera compra en formato 'YYYY-MM-DD HH:MM:SS',
     *                     o null si el cliente no tiene compras registradas.
     */
    public static function consultarPrimeraCompraCliente(int $idRegistro, int $idEmpresa, $conexionBdPrincipal) {
        $consulta = $conexionBdPrincipal->query("SELECT MIN(factura_fecha_creacion) as fecha FROM facturas 
        INNER JOIN clientes ON cli_id=factura_cliente AND factura_cliente = ".$idRegistro."
        WHERE factura_tipo = ".FACTURA_TIPO_VENTA." AND factura_estado = 1");

        return mysqli_fetch_array($consulta, MYSQLI_ASSOC)['fecha'];
    }

    /**
     * Obtiene la fecha de la última compra (factura más reciente) realizada por un cliente.
     *
     * Esta función consulta la base de datos para encontrar la fecha de creación de la factura
     * más reciente de tipo venta y con estado activo asociada a un cliente específico.
     *
     * @param int $idRegistro         ID del cliente en la base de datos.
     * @param int $idEmpresa          ID de la empresa (actualmente no se utiliza en la consulta).
     * @param mysqli $conexionBdPrincipal Conexión activa a la base de datos principal.
     *
     * @return string|null Fecha de la última compra en formato 'YYYY-MM-DD HH:MM:SS',
     *                     o null si el cliente no tiene compras registradas.
     */
    public static function consultarUltimaCompraCliente(int $idRegistro, int $idEmpresa, $conexionBdPrincipal) {
        $consulta = $conexionBdPrincipal->query("SELECT MAX(factura_fecha_creacion) as fecha FROM facturas 
        INNER JOIN clientes ON cli_id=factura_cliente AND factura_cliente = ".$idRegistro."
        WHERE factura_tipo = ".FACTURA_TIPO_VENTA." AND factura_estado = 1");

        return mysqli_fetch_array($consulta, MYSQLI_ASSOC)['fecha'];
    }

    /**
     * Obtiene los datos del cliente con mayor cantidad de compras durante el año actual.
     *
     * Esta función realiza una consulta a la base de datos para identificar al cliente
     * que ha realizado el mayor número de facturas de tipo venta en el año en curso,
     * dentro de una empresa específica.
     *
     * @param int $idEmpresa              ID de la empresa para filtrar los clientes y facturas.
     * @param mysqli $conexionBdPrincipal Conexión activa a la base de datos principal.
     *
     * @return array|null Arreglo asociativo con los siguientes campos:
     *                    - 'cantidad' (int): número total de compras realizadas.
     *                    - 'factura_cliente' (int): ID del cliente.
     *                    - 'nombreCliente' (string): nombre del cliente.
     *                    Retorna null si no existen registros.
     */
    public static function obtenerDatosClienteConMasComprasAgnoActual(int $idEmpresa, $conexionBdPrincipal) {
        $sql = "SELECT COUNT(*) AS cantidad, factura_cliente, cli_nombre AS nombreCliente 
        FROM facturas f
            JOIN clientes c ON cli_id=factura_cliente 
            AND cli_id_empresa=".$idEmpresa."
        WHERE factura_tipo = ".FACT_TIPO_VENTA." 
        AND year(f.factura_fecha_creacion)=".date("Y")."
        GROUP BY factura_cliente
        ORDER BY cantidad DESC
        LIMIT 1
        ";
        $consulta = $conexionBdPrincipal->query($sql);

        return mysqli_fetch_array($consulta, MYSQLI_ASSOC);
    }

    /**
     * Obtiene la cantidad de clientes nuevos registrados en el mes actual.
     *
     * Esta función consulta la base de datos para contar cuántos clientes fueron
     * registrados durante el mes y año en curso dentro de una empresa específica.
     * Solo se consideran los clientes cuya categoría corresponde a "cliente" (CLI_CATEGORIA_CLIENTE).
     *
     * @param int $idEmpresa              ID de la empresa para filtrar los clientes.
     * @param mysqli $conexionBdPrincipal Conexión activa a la base de datos principal.
     *
     * @return int Cantidad de clientes nuevos registrados en el mes actual.
     */
    public static function clientesNuevosEstesMes(int $idEmpresa, $conexionBdPrincipal) {
        $sql = "SELECT COUNT(*) AS cantidad
        FROM clientes c
        WHERE cli_categoria = ".CLI_CATEGORIA_CLIENTE." 
        AND YEAR(cli_fecha_ingreso)=".date("Y")."
        AND MONTH(cli_fecha_ingreso)=".date("m")." 
        AND cli_id_empresa=".$idEmpresa."
        ";
        $consulta = $conexionBdPrincipal->query($sql);

        return mysqli_fetch_array($consulta, MYSQLI_ASSOC)['cantidad'];
    }

    /**
     * Sincroniza un cliente con Ofima (Orion → Ofima).
     * Se llama al crear o actualizar un cliente.
     *
     * @param int     $clienteId          ID del cliente.
     * @param mysqli  $conexionBdPrincipal Conexión a la base de datos.
     * @param int     $idEmpresa          ID de la empresa.
     * @param string  $tipoOperacion      'CREATE' o 'UPDATE'
     * @return array Resultado de la sincronización
     */
    public static function sincronizarConOfima($clienteId, $conexionBdPrincipal, $idEmpresa, $tipoOperacion = 'UPDATE') {
        $query = "SELECT apic_activo FROM api_configuracion 
                  WHERE apic_modulo = 'clientes' 
                  AND apic_direccion = 'orion_ofima' 
                  AND apic_id_empresa = ? 
                  LIMIT 1";
        $stmt = $conexionBdPrincipal->prepare($query);
        $stmt->bind_param("i", $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0 || $result->fetch_assoc()['apic_activo'] != 1) {
            return ['success' => false, 'message' => 'Sincronización desactivada'];
        }

        $query = "SELECT * FROM clientes WHERE cli_id = ? AND cli_id_empresa = ?";
        $stmt = $conexionBdPrincipal->prepare($query);
        $stmt->bind_param("ii", $clienteId, $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'error' => 'Cliente no encontrado'];
        }
        $cliente = $result->fetch_assoc();

        if (empty(trim($cliente['cli_usuario'] ?? '')) || empty(trim($cliente['cli_nombre'] ?? ''))) {
            return [
                'success' => false,
                'error'   => 'El cliente debe tener documento (NIT) y nombre para sincronizar con Ofima'
            ];
        }

        require_once RUTA_PROYECTO . '/usuarios/class/ApiOfimaClient.php';
        $apiClient = new ApiOfimaClient($conexionBdPrincipal, $idEmpresa);
        return $apiClient->sincronizarCliente($cliente, $tipoOperacion);
    }

    /**
     * Obtiene en una sola consulta las fechas clave de evolución comercial del cliente.
     */
    public static function obtenerEvolucionComercial(int $idCliente, int $idEmpresa, $conexionBdPrincipal) {
        $idCliente = intval($idCliente);
        $idEmpresa = intval($idEmpresa);

        $sql = "
            SELECT
                (SELECT MIN(c.cotiz_fecha_propuesta)
                 FROM cotizacion c
                 WHERE c.cotiz_cliente = '" . $idCliente . "'
                   AND c.cotiz_es_precotizacion = 0
                   AND c.cotiz_id_empresa = '" . $idEmpresa . "') AS primera_cotizacion,
                (SELECT MIN(f.factura_fecha_creacion)
                 FROM facturas f
                 WHERE f.factura_cliente = '" . $idCliente . "'
                   AND f.factura_tipo = " . FACTURA_TIPO_VENTA . "
                   AND f.factura_estado = 1) AS primera_compra,
                (SELECT MAX(f.factura_fecha_creacion)
                 FROM facturas f
                 WHERE f.factura_cliente = '" . $idCliente . "'
                   AND f.factura_tipo = " . FACTURA_TIPO_VENTA . "
                   AND f.factura_estado = 1) AS ultima_compra
        ";

        $consulta = $conexionBdPrincipal->query($sql);
        return mysqli_fetch_array($consulta, MYSQLI_ASSOC) ?: [];
    }

    /**
     * Contadores operativos del cliente para la vista comercial.
     */
    public static function obtenerContadoresRelacionados(int $idCliente, int $idEmpresa, $conexionBdPrincipal) {
        $idCliente = intval($idCliente);
        $idEmpresa = intval($idEmpresa);

        $sql = "
            SELECT
                (SELECT COUNT(*) FROM sucursales WHERE sucu_cliente_principal = '" . $idCliente . "') AS sucursales,
                (SELECT COUNT(*) FROM contactos WHERE cont_cliente_principal = '" . $idCliente . "') AS contactos,
                (SELECT COUNT(*) FROM clientes_tikets WHERE tik_cliente = '" . $idCliente . "') AS tickets,
                (SELECT COUNT(*) FROM clientes_tikets WHERE tik_cliente = '" . $idCliente . "' AND tik_estado = 1) AS tickets_abiertos,
                (SELECT COUNT(*) FROM cliente_seguimiento WHERE cseg_cliente = '" . $idCliente . "') AS seguimientos,
                (SELECT COUNT(*) FROM cotizacion WHERE cotiz_cliente = '" . $idCliente . "' AND cotiz_id_empresa = '" . $idEmpresa . "') AS cotizaciones,
                (SELECT COUNT(*) FROM facturacion WHERE fact_cliente = '" . $idCliente . "' AND fact_id_empresa = '" . $idEmpresa . "') AS facturas,
                (SELECT COUNT(*) FROM clientes_notas_internas WHERE clin_cliente = '" . $idCliente . "' AND clin_id_empresa = '" . $idEmpresa . "') AS notas_internas
        ";

        $consulta = $conexionBdPrincipal->query($sql);
        return mysqli_fetch_array($consulta, MYSQLI_ASSOC) ?: [];
    }

    /**
     * IDs de grupos (dealer) asociados al cliente.
     */
    public static function obtenerGruposCliente(int $idCliente, $conexionBdPrincipal) {
        $grupos = [];
        $consulta = $conexionBdPrincipal->query("
            SELECT cpcat_categoria
            FROM clientes_categorias
            WHERE cpcat_cliente = '" . intval($idCliente) . "'
        ");

        while ($fila = mysqli_fetch_array($consulta, MYSQLI_ASSOC)) {
            $grupos[] = intval($fila['cpcat_categoria']);
        }

        return $grupos;
    }

    /**
     * Usuario asesor asociado al cliente.
     */
    public static function obtenerAsesorCliente(int $idCliente, $conexionBdPrincipal) {
        $consulta = $conexionBdPrincipal->query("
            SELECT cliu_usuario
            FROM clientes_usuarios
            WHERE cliu_cliente = '" . intval($idCliente) . "'
            LIMIT 1
        ");

        $fila = mysqli_fetch_array($consulta, MYSQLI_ASSOC);
        return $fila ? intval($fila['cliu_usuario']) : null;
    }

    /**
     * Tipos de vía y puntos cardinales usados en la nomenclatura de dirección.
     */
    public static function obtenerTiposViaDireccion(): array {
        return [
            'Calle', 'Carrera', 'Avenida', 'Avenida Carrera', 'Avenida Calle',
            'Circular', 'Circunvalar', 'Diagonal', 'Manzana', 'Transversal', 'Vía',
        ];
    }

    public static function obtenerPuntosCardinalesDireccion(): array {
        return ['Este', 'Norte', 'Occidente', 'Oeste', 'Oriente', 'Sur'];
    }

    /**
     * Descompone una dirección guardada con la nomenclatura estándar del CRM.
     */
    public static function parsearDireccionNomenclatura(string $direccion): array {
        $partes = [
            'op1' => '', 'op2' => '', 'op3' => '', 'op4' => '',
            'op5' => '', 'op6' => '', 'op7' => '',
        ];

        $direccion = trim($direccion);
        if ($direccion === '') {
            return $partes;
        }

        $tiposVia = self::obtenerTiposViaDireccion();
        usort($tiposVia, static function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $resto = $direccion;
        foreach ($tiposVia as $tipo) {
            if (stripos($resto, $tipo) === 0) {
                $partes['op1'] = $tipo;
                $resto = trim(substr($resto, strlen($tipo)));
                break;
            }
        }

        if (preg_match('/^(\S+)\s+(.+?)\s+#\s+(\S+)\s+(.+?)\s+-\s+(\S+)\s+-\s+(.+)$/u', $resto, $coincidencias)) {
            $partes['op2'] = trim($coincidencias[1]);
            $partes['op3'] = self::normalizarPuntoCardinal(trim($coincidencias[2]));
            $partes['op4'] = trim($coincidencias[3]);
            $partes['op5'] = self::normalizarPuntoCardinal(trim($coincidencias[4]));
            $partes['op6'] = trim($coincidencias[5]);
            $partes['op7'] = trim($coincidencias[6]);
            return $partes;
        }

        if ($partes['op1'] === '') {
            $partes['op7'] = $direccion;
        } else {
            $partes['op7'] = $resto;
        }

        return $partes;
    }

    /**
     * Construye la dirección con la nomenclatura estándar del CRM.
     */
    public static function construirDireccionNomenclatura(array $partes): string {
        return trim(
            ($partes['op1'] ?? '') . ' ' .
            ($partes['op2'] ?? '') . ' ' .
            ($partes['op3'] ?? '') . ' # ' .
            ($partes['op4'] ?? '') . ' ' .
            ($partes['op5'] ?? '') . ' - ' .
            ($partes['op6'] ?? '') . ' - ' .
            ($partes['op7'] ?? '')
        );
    }

    private static function normalizarPuntoCardinal(string $valor): string {
        foreach (self::obtenerPuntosCardinalesDireccion() as $punto) {
            if (strcasecmp($valor, $punto) === 0) {
                return $punto;
            }
        }

        return $valor;
    }

    /**
     * Filtros compartidos del listado de clientes (clientes.php y fetch-buscar-clientes.php).
     */
    public static function prepararFiltrosListado(
        array $get,
        int $idEmpresa,
        $conexionBdPrincipal,
        bool $excluirCiudadDesconocida = false
    ): array {
        $idEmpresa = intval($idEmpresa);
        $where     = " AND cli.cli_id_empresa = '" . $idEmpresa . "'";
        $joinExtra = '';

        if (isset($get['pap']) && (int) $get['pap'] === 1) {
            $where .= ' AND cli.cli_papelera = 1';
        }

        if (isset($get['grupo']) && is_numeric($get['grupo'])) {
            $joinExtra = " INNER JOIN clientes_categorias cpcat ON cpcat.cpcat_cliente = cli.cli_id AND cpcat.cpcat_categoria = '" . intval($get['grupo']) . "'";
        }

        if (isset($get['tipoDoc']) && is_numeric($get['tipoDoc'])) {
            $where .= " AND cli.cli_tipo_documento = '" . intval($get['tipoDoc']) . "'";
        }

        if ($excluirCiudadDesconocida) {
            $where .= " AND cli.cli_ciudad != '1122'";
        }

        if (isset($get['clientesNuevos'])) {
            $where .= ' AND YEAR(cli.cli_fecha_ingreso) = ' . date('Y') . ' AND MONTH(cli.cli_fecha_ingreso) = ' . date('m');
        }

        if (isset($get['categoria']) && is_numeric($get['categoria'])) {
            $where .= ' AND cli.cli_categoria = ' . intval($get['categoria']);
        }

        if (!empty($get['fecha_registro_inicio'])) {
            $fecha = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_registro_inicio']);
            $where .= " AND cli.cli_fecha_registro >= '" . $fecha . " 00:00:00'";
        }

        if (!empty($get['fecha_registro_fin'])) {
            $fecha = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_registro_fin']);
            $where .= " AND cli.cli_fecha_registro <= '" . $fecha . " 23:59:59'";
        }

        if (!empty($get['fecha_ingreso_inicio'])) {
            $fecha = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_ingreso_inicio']);
            $where .= " AND cli.cli_fecha_ingreso >= '" . $fecha . " 00:00:00' AND cli.cli_categoria = 2";
        }

        if (!empty($get['fecha_ingreso_fin'])) {
            $fecha = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_ingreso_fin']);
            $where .= " AND cli.cli_fecha_ingreso <= '" . $fecha . " 23:59:59' AND cli.cli_categoria = 2";
        }

        if (!empty($get['buscar'])) {
            $termino = mysqli_real_escape_string($conexionBdPrincipal, $get['buscar']);
            $where  .= " AND (
                cli.cli_usuario LIKE '%" . $termino . "%'
                OR cli.cli_nombre LIKE '%" . $termino . "%'
                OR cli.cli_email LIKE '%" . $termino . "%'
                OR cli.cli_telefono LIKE '%" . $termino . "%'
                OR cli.cli_celular LIKE '%" . $termino . "%'
                OR cli.cli_sigla LIKE '%" . $termino . "%'
                OR ciu.ciu_nombre LIKE '%" . $termino . "%'
                OR dep.dep_nombre LIKE '%" . $termino . "%'
            )";
        }

        $dpto = '';
        if (isset($get['dpto']) && $get['dpto'] !== '') {
            $dpto  = intval($get['dpto']);
            $where .= " AND dep.dep_id = '" . $dpto . "'";
        }

        return [
            'where'      => $where,
            'join_extra' => $joinExtra,
            'dpto'       => $dpto,
            'tipo_doc'   => isset($get['tipoDoc']) && is_numeric($get['tipoDoc']) ? intval($get['tipoDoc']) : '',
        ];
    }

    public static function sqlJoinsUbicacionListado(): string {
        return '
            LEFT JOIN ' . BDADMIN . '.localidad_ciudades ciu ON ciu.ciu_id = cli.cli_ciudad
            INNER JOIN ' . BDADMIN . '.localidad_departamentos dep ON dep.dep_id = ciu.ciu_departamento
        ';
    }

    public static function sqlConteoListado(string $joinExtra, string $where): string {
        return 'SELECT COUNT(DISTINCT cli.cli_id)
            FROM ' . MAINBD . '.clientes cli
            ' . self::sqlJoinsUbicacionListado() . '
            ' . $joinExtra . '
            WHERE 1=1 ' . $where;
    }

    public static function sqlFilasListado(string $joinExtra, string $where, int $inicio, int $limite): string {
        $inicio = max(0, intval($inicio));
        $limite = max(1, intval($limite));

        return 'SELECT
                cli.cli_id,
                cli.cli_sesion,
                cli.cli_papelera,
                cli.cli_estado_mercadeo,
                cli.cli_estado_mercadeo_fecha,
                cli.cli_zona,
                cli.cli_categoria,
                cli.cli_retirado,
                cli.cli_fecha_registro,
                cli.cli_tipo_documento,
                cli.cli_usuario,
                cli.cli_nombre,
                cli.cli_telefono,
                cli.cli_celular,
                cli.cli_email,
                ciu.ciu_nombre,
                dep.dep_nombre,
                dep.dep_indicativo
            FROM ' . MAINBD . '.clientes cli
            ' . self::sqlJoinsUbicacionListado() . '
            ' . $joinExtra . '
            WHERE 1=1 ' . $where . '
            ORDER BY cli.cli_id DESC
            LIMIT ' . $inicio . ', ' . $limite;
    }

    /**
     * Contadores TK/SG/SC/CT/FC/RM para un lote de clientes (una sola consulta).
     */
    public static function contadoresRelacionadosBatch(array $clienteIds, $conexionBdPrincipal): array {
        $clienteIds = array_values(array_filter(array_map('intval', $clienteIds)));
        if (empty($clienteIds)) {
            return [];
        }

        $idsSql = implode(',', $clienteIds);
        $mapa   = [];
        foreach ($clienteIds as $idCliente) {
            $mapa[$idCliente] = [0, 0, 0, 0, 0, 0];
        }

        $consulta = $conexionBdPrincipal->query("
            SELECT
                cli.cli_id,
                (SELECT COUNT(*) FROM clientes_tikets WHERE tik_cliente = cli.cli_id) AS cnt_tickets,
                (SELECT COUNT(*)
                 FROM cliente_seguimiento cs
                 INNER JOIN clientes_tikets ct ON ct.tik_id = cs.cseg_tiket
                 WHERE cs.cseg_cliente = cli.cli_id) AS cnt_seguimientos,
                (SELECT COUNT(*) FROM sucursales WHERE sucu_cliente_principal = cli.cli_id) AS cnt_sucursales,
                (SELECT COUNT(*) FROM contactos WHERE cont_cliente_principal = cli.cli_id) AS cnt_contactos,
                (SELECT COUNT(*) FROM facturacion WHERE fact_cliente = cli.cli_id) AS cnt_facturas,
                (SELECT COUNT(*) FROM remisiones WHERE rem_cliente = cli.cli_id) AS cnt_remisiones
            FROM clientes cli
            WHERE cli.cli_id IN (" . $idsSql . ")
        ");

        if (!$consulta) {
            return $mapa;
        }

        while ($fila = mysqli_fetch_assoc($consulta)) {
            $id = intval($fila['cli_id']);
            $mapa[$id] = [
                intval($fila['cnt_tickets']),
                intval($fila['cnt_seguimientos']),
                intval($fila['cnt_sucursales']),
                intval($fila['cnt_contactos']),
                intval($fila['cnt_facturas']),
                intval($fila['cnt_remisiones']),
            ];
        }

        return $mapa;
    }

    /**
     * Zonas y clientes asignados al usuario para filtrar filas del listado.
     */
    public static function permisosVisibilidadListado(int $usuarioId, $conexionBdPrincipal): array {
        $usuarioId = intval($usuarioId);
        $zonas     = [];
        $clientes  = [];

        $consultaZonas = $conexionBdPrincipal->query("
            SELECT zpu_zona FROM zonas_usuarios WHERE zpu_usuario = '" . $usuarioId . "'
        ");
        while ($fila = mysqli_fetch_assoc($consultaZonas)) {
            $zonas[] = intval($fila['zpu_zona']);
        }

        $consultaClientes = $conexionBdPrincipal->query("
            SELECT cliu_cliente FROM clientes_usuarios WHERE cliu_usuario = '" . $usuarioId . "'
        ");
        while ($fila = mysqli_fetch_assoc($consultaClientes)) {
            $clientes[] = intval($fila['cliu_cliente']);
        }

        return [
            'zonas'    => $zonas,
            'clientes' => $clientes,
        ];
    }

    public static function usuarioPuedeVerClienteEnListado(array $permisos, int $zonaId, int $clienteId): bool {
        return in_array($zonaId, $permisos['zonas'], true)
            || in_array($clienteId, $permisos['clientes'], true);
    }

    /**
     * Conteo de clientes activos por departamento (una consulta).
     */
    public static function conteoClientesPorDepartamento(int $idEmpresa, $conexionBdPrincipal, $conexionBdAdmin): array {
        $idEmpresa = intval($idEmpresa);
        $mapa      = [];

        $consulta = $conexionBdPrincipal->query("
            SELECT dep.dep_id, COUNT(DISTINCT cli.cli_id) AS total
            FROM " . MAINBD . ".clientes cli
            INNER JOIN " . BDADMIN . ".localidad_ciudades ciu ON ciu.ciu_id = cli.cli_ciudad
            INNER JOIN " . BDADMIN . ".localidad_departamentos dep ON dep.dep_id = ciu.ciu_departamento
            WHERE cli.cli_id_empresa = '" . $idEmpresa . "'
              AND (cli.cli_papelera IS NULL OR cli.cli_papelera = 0)
            GROUP BY dep.dep_id
        ");

        if ($consulta) {
            while ($fila = mysqli_fetch_assoc($consulta)) {
                $mapa[intval($fila['dep_id'])] = intval($fila['total']);
            }
        }

        return $mapa;
    }

    /**
     * Conteo de clientes activos por grupo dealer (una consulta).
     */
    public static function conteoClientesPorGrupoDealer(int $idEmpresa, $conexionBdPrincipal): array {
        $idEmpresa = intval($idEmpresa);
        $mapa      = [];

        $consulta = $conexionBdPrincipal->query("
            SELECT cc.cpcat_categoria, COUNT(*) AS total
            FROM clientes_categorias cc
            INNER JOIN clientes cli ON cli.cli_id = cc.cpcat_cliente
                AND cli.cli_id_empresa = '" . $idEmpresa . "'
                AND (cli.cli_papelera = 0 OR cli.cli_papelera IS NULL)
            GROUP BY cc.cpcat_categoria
        ");

        if ($consulta) {
            while ($fila = mysqli_fetch_assoc($consulta)) {
                $mapa[intval($fila['cpcat_categoria'])] = intval($fila['total']);
            }
        }

        return $mapa;
    }

    private static function construirWhereAnualListado(int $anio, int $idEmpresa, bool $excluirCiudadDesconocida): string {
        $anio      = intval($anio);
        $idEmpresa = intval($idEmpresa);
        $where     = " AND cli.cli_id_empresa = '" . $idEmpresa . "'";
        $where    .= " AND (cli.cli_papelera IS NULL OR cli.cli_papelera = 0)";
        $where    .= " AND cli.cli_fecha_registro >= '" . $anio . "-01-01 00:00:00'";
        $where    .= " AND cli.cli_fecha_registro <= '" . $anio . "-12-31 23:59:59'";

        if ($excluirCiudadDesconocida) {
            $where .= " AND cli.cli_ciudad != '1122'";
        }

        return $where;
    }

    private static function construirWhereCarteraActiva(int $idEmpresa, bool $excluirCiudadDesconocida): string {
        $idEmpresa = intval($idEmpresa);
        $where     = " AND cli.cli_id_empresa = '" . $idEmpresa . "'";
        $where    .= " AND (cli.cli_papelera IS NULL OR cli.cli_papelera = 0)";

        if ($excluirCiudadDesconocida) {
            $where .= " AND cli.cli_ciudad != '1122'";
        }

        return $where;
    }

    private static function appendFiltrosVisibilidadAnalyticsWhere(
        string $where,
        int $usuarioId,
        bool $restringirPorZona
    ): string {
        if (!$restringirPorZona) {
            return $where;
        }

        $usuarioId = intval($usuarioId);
        $where    .= " AND (
            cli.cli_zona IN (
                SELECT zpu_zona
                FROM " . MAINBD . ".zonas_usuarios
                WHERE zpu_usuario = '" . $usuarioId . "'
            )
            OR cli.cli_id IN (
                SELECT cliu_cliente
                FROM " . MAINBD . ".clientes_usuarios
                WHERE cliu_usuario = '" . $usuarioId . "'
            )
        )";

        return $where;
    }

    /**
     * Estadísticas agregadas del año en curso para gráficos del listado de clientes.
     */
    public static function obtenerEstadisticasAnuales(
        $conexionBdPrincipal,
        int $anio,
        int $idEmpresa,
        int $usuarioId,
        bool $restringirPorZona,
        bool $excluirCiudadDesconocida
    ): array {
        $whereAnual = self::construirWhereAnualListado($anio, $idEmpresa, $excluirCiudadDesconocida);
        $whereAnual = self::appendFiltrosVisibilidadAnalyticsWhere($whereAnual, $usuarioId, $restringirPorZona);

        $whereCartera = self::construirWhereCarteraActiva($idEmpresa, $excluirCiudadDesconocida);
        $whereCartera = self::appendFiltrosVisibilidadAnalyticsWhere($whereCartera, $usuarioId, $restringirPorZona);

        $fromAnual = '
            FROM ' . MAINBD . '.clientes cli
            ' . self::sqlJoinsUbicacionListado() . '
            WHERE 1=1 ' . $whereAnual;

        $fromCartera = '
            FROM ' . MAINBD . '.clientes cli
            ' . self::sqlJoinsUbicacionListado() . '
            WHERE 1=1 ' . $whereCartera;

        $resumen = mysqli_fetch_assoc(mysqli_query($conexionBdPrincipal, "
            SELECT
                COUNT(DISTINCT cli.cli_id) AS total,
                SUM(CASE WHEN cli.cli_categoria = 1 THEN 1 ELSE 0 END) AS prospectos,
                SUM(CASE WHEN cli.cli_categoria = 2 THEN 1 ELSE 0 END) AS clientes,
                SUM(CASE WHEN cli.cli_categoria = 3 THEN 1 ELSE 0 END) AS dealers,
                SUM(CASE WHEN cli.cli_tipo_documento = 2 THEN 1 ELSE 0 END) AS nit,
                SUM(CASE WHEN cli.cli_tipo_documento = 3 THEN 1 ELSE 0 END) AS cedula
            " . $fromAnual)) ?: [];

        $cartera = mysqli_fetch_assoc(mysqli_query($conexionBdPrincipal, "
            SELECT
                COUNT(DISTINCT cli.cli_id) AS total,
                SUM(CASE WHEN cli.cli_categoria = 1 THEN 1 ELSE 0 END) AS prospectos,
                SUM(CASE WHEN cli.cli_categoria = 2 THEN 1 ELSE 0 END) AS clientes,
                SUM(CASE WHEN cli.cli_categoria = 3 THEN 1 ELSE 0 END) AS dealers
            " . $fromCartera)) ?: [];

        $porMesRegistro = array_fill(1, 12, 0);
        $consultaMes = mysqli_query($conexionBdPrincipal, "
            SELECT MONTH(cli.cli_fecha_registro) AS mes, COUNT(DISTINCT cli.cli_id) AS total
            " . $fromAnual . "
            GROUP BY MONTH(cli.cli_fecha_registro)
        ");
        while ($consultaMes && ($fila = mysqli_fetch_assoc($consultaMes))) {
            $mes = intval($fila['mes']);
            if ($mes >= 1 && $mes <= 12) {
                $porMesRegistro[$mes] = intval($fila['total']);
            }
        }

        $whereIngreso = $whereAnual . "
            AND cli.cli_categoria = " . CLI_CATEGORIA_CLIENTE . "
            AND cli.cli_fecha_ingreso >= '" . intval($anio) . "-01-01 00:00:00'
            AND cli.cli_fecha_ingreso <= '" . intval($anio) . "-12-31 23:59:59'";
        $fromIngreso = '
            FROM ' . MAINBD . '.clientes cli
            ' . self::sqlJoinsUbicacionListado() . '
            WHERE 1=1 ' . $whereIngreso;

        $porMesIngreso = array_fill(1, 12, 0);
        $consultaIngreso = mysqli_query($conexionBdPrincipal, "
            SELECT MONTH(cli.cli_fecha_ingreso) AS mes, COUNT(DISTINCT cli.cli_id) AS total
            " . $fromIngreso . "
            GROUP BY MONTH(cli.cli_fecha_ingreso)
        ");
        while ($consultaIngreso && ($fila = mysqli_fetch_assoc($consultaIngreso))) {
            $mes = intval($fila['mes']);
            if ($mes >= 1 && $mes <= 12) {
                $porMesIngreso[$mes] = intval($fila['total']);
            }
        }

        $nuevosClientesAnio = array_sum($porMesIngreso);

        $porCategoria = [
            ['label' => 'Prospecto', 'total' => intval($resumen['prospectos'] ?? 0)],
            ['label' => 'Cliente', 'total' => intval($resumen['clientes'] ?? 0)],
            ['label' => 'Dealer', 'total' => intval($resumen['dealers'] ?? 0)],
        ];

        $porTipoDocumento = [
            ['label' => 'NIT', 'total' => intval($resumen['nit'] ?? 0)],
            ['label' => 'Cédula', 'total' => intval($resumen['cedula'] ?? 0)],
        ];

        $porDepartamento = [];
        $consultaDepto = mysqli_query($conexionBdPrincipal, "
            SELECT dep.dep_nombre AS label, COUNT(DISTINCT cli.cli_id) AS total
            " . $fromAnual . "
            GROUP BY dep.dep_id, dep.dep_nombre
            ORDER BY total DESC
            LIMIT 10
        ");
        while ($consultaDepto && ($fila = mysqli_fetch_assoc($consultaDepto))) {
            $porDepartamento[] = [
                'label' => $fila['label'] ?? 'Sin departamento',
                'total' => intval($fila['total']),
            ];
        }

        $porEstadoMercadeo = [];
        $labelsEstado = [
            0 => 'Sin estado',
            2 => 'Número equivocado',
            5 => 'Actualizado',
            6 => 'Papelera mercadeo',
        ];
        $consultaEstado = mysqli_query($conexionBdPrincipal, "
            SELECT cli.cli_estado_mercadeo AS id, COUNT(DISTINCT cli.cli_id) AS total
            " . $fromAnual . "
            GROUP BY cli.cli_estado_mercadeo
            ORDER BY total DESC
        ");
        while ($consultaEstado && ($fila = mysqli_fetch_assoc($consultaEstado))) {
            $id = intval($fila['id']);
            $porEstadoMercadeo[] = [
                'label' => $labelsEstado[$id] ?? ('Estado ' . $id),
                'total' => intval($fila['total']),
            ];
        }

        $totalRegistrados = intval($resumen['total'] ?? 0);

        return [
            'anio'               => intval($anio),
            'resumen'            => [
                'registrados_anio'  => $totalRegistrados,
                'nuevos_clientes'   => $nuevosClientesAnio,
                'prospectos_anio'   => intval($resumen['prospectos'] ?? 0),
                'clientes_anio'     => intval($resumen['clientes'] ?? 0),
                'dealers_anio'      => intval($resumen['dealers'] ?? 0),
            ],
            'cartera'            => [
                'total'      => intval($cartera['total'] ?? 0),
                'prospectos' => intval($cartera['prospectos'] ?? 0),
                'clientes'   => intval($cartera['clientes'] ?? 0),
                'dealers'    => intval($cartera['dealers'] ?? 0),
            ],
            'por_mes_registro'   => array_values($porMesRegistro),
            'por_mes_ingreso'    => array_values($porMesIngreso),
            'por_categoria'      => $porCategoria,
            'por_tipo_documento' => $porTipoDocumento,
            'por_departamento'   => $porDepartamento,
            'por_estado_mercadeo'=> $porEstadoMercadeo,
        ];
    }

}