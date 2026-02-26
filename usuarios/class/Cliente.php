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

}