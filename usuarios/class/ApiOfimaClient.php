<?php
require_once RUTA_PROYECTO.'/conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/OfimaEndpointService.php';

/**
 * Cliente HTTP Orion → Ofima.
 * Rutas: OfimaEndpointService (código).
 * Base/credenciales: api_ofima_conexion.
 * Habilitación: registro en api_configuracion (apic_activo).
 */
class ApiOfimaClient {
    
    private $conexionBdPrincipal;
    private $idEmpresa;
    
    public function __construct($conexionBdPrincipal, $idEmpresa) {
        $this->conexionBdPrincipal = $conexionBdPrincipal;
        $this->idEmpresa = $idEmpresa;
    }
    
    /**
     * Resuelve URL, método y autenticación Bearer vía servicio + conexión Ofima.
     */
    private function resolverEnvio($modulo, $tipoOperacion) {
        if (!ofimaIntegracionActiva($this->conexionBdPrincipal, (int) $this->idEmpresa)) {
            return ['success' => false, 'error' => 'La integración Ofima está desactivada'];
        }

        $clave = OfimaEndpointService::claveOperacion($modulo, $tipoOperacion);
        if (!OfimaEndpointService::obtener($clave)) {
            return ['success' => false, 'error' => 'Endpoint Ofima no definido en el servicio: ' . $clave];
        }

        if (!OfimaEndpointService::estaHabilitado($this->conexionBdPrincipal, (int) $this->idEmpresa, $clave)) {
            return ['success' => false, 'error' => 'Endpoint Ofima deshabilitado en el registro: ' . $clave];
        }

        $conexion = obtenerApiOfimaConexion($this->conexionBdPrincipal, (int) $this->idEmpresa);
        if (!$conexion) {
            return ['success' => false, 'error' => 'No hay configuración de conexión Ofima'];
        }

        $credenciales = resolverCredencialesOfima($conexion);
        if ($credenciales['url_base'] === '') {
            return ['success' => false, 'error' => 'URL base Ofima no configurada para el ambiente ' . $credenciales['ambiente']];
        }

        $url = OfimaEndpointService::urlAbsoluta($credenciales['url_base'], $clave);
        if (!$url) {
            return ['success' => false, 'error' => 'No se pudo construir la URL del endpoint ' . $clave];
        }

        $tokenResult = $this->obtenerTokenValido($conexion, $credenciales);
        if (!$tokenResult['success']) {
            return $tokenResult;
        }

        $meta = OfimaEndpointService::obtener($clave);

        return [
            'success' => true,
            'url' => $url,
            'metodo' => $meta['metodo'] ?? 'POST',
            'token' => $tokenResult['token'],
            'usuario' => null,
            'password' => null,
            'envolver_lista' => ($clave !== 'autenticacion'),
            'clave' => $clave,
        ];
    }

    /**
     * Obtiene un token vigente o solicita uno nuevo (válido 1 hora según documentación Ofima).
     */
    private function obtenerTokenValido(array $conexion, array $credenciales) {
        $ahora = time();
        $expira = !empty($conexion['aoc_token_expira']) ? strtotime($conexion['aoc_token_expira']) : 0;

        if (!empty($conexion['aoc_token']) && $expira > ($ahora + 60)) {
            return ['success' => true, 'token' => $conexion['aoc_token']];
        }

        $resultado = solicitarTokenOfima(
            $credenciales['url_base'],
            $credenciales['usuario'],
            $credenciales['clave']
        );

        if (!$resultado['success']) {
            return [
                'success' => false,
                'error' => $resultado['error'] ?? 'No se pudo autenticar con Ofima',
            ];
        }

        $token = $resultado['token'];
        $expiraStr = date('Y-m-d H:i:s', $ahora + 3600);
        $stmt = $this->conexionBdPrincipal->prepare(
            "UPDATE api_ofima_conexion SET aoc_token = ?, aoc_token_expira = ? WHERE aoc_id_empresa = ?"
        );
        $stmt->bind_param('ssi', $token, $expiraStr, $this->idEmpresa);
        $stmt->execute();

        return ['success' => true, 'token' => $token];
    }
    
    /**
     * Realiza una petición HTTP a la API de Ofima
     */
    private function realizarPeticion($url, $metodo = 'POST', $datos = null, $usuario = null, $password = null, $token = null) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        if (!empty($token)) {
            $headers[] = 'Authorization: Bearer ' . $token;
        } elseif ($usuario && $password) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $usuario . ":" . $password);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($datos !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            }
        } elseif ($metodo === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($datos !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            }
        }
        
        $respuesta = curl_exec($ch);
        $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => $error,
                'codigo_http' => 0
            ];
        }
        
        $datosRespuesta = json_decode($respuesta, true);
        
        return [
            'success' => ($codigoHttp >= 200 && $codigoHttp < 300),
            'codigo_http' => $codigoHttp,
            'datos' => $datosRespuesta ? $datosRespuesta : $respuesta,
            'respuesta_raw' => $respuesta
        ];
    }

    private function enviarAOfima($modulo, $tipoOperacion, $datosOfima) {
        $envio = $this->resolverEnvio($modulo, $tipoOperacion);
        if (!$envio['success']) {
            return $envio;
        }

        $payload = $datosOfima;
        if (!empty($envio['envolver_lista'])) {
            $esLista = is_array($datosOfima) && array_keys($datosOfima) === range(0, count($datosOfima) - 1);
            $payload = $esLista ? $datosOfima : [$datosOfima];
        }

        return $this->realizarPeticion(
            $envio['url'],
            $envio['metodo'],
            $payload,
            $envio['usuario'],
            $envio['password'],
            $envio['token']
        );
    }
    
    /**
     * Registra una sincronización en api_sincronizaciones (éxito o error).
     */
    public function registrarSincronizacion($modulo, $direccion, $tipoOperacion, $idRegistro, $referencia, $datosEnviados, $respuesta, $estado, $codigoRespuesta = null, $mensajeError = null) {
        $query = "INSERT INTO api_sincronizaciones (
            apis_modulo, apis_direccion, apis_tipo_operacion, apis_id_registro, 
            apis_referencia, apis_datos_enviados, apis_respuesta, apis_estado, 
            apis_codigo_respuesta, apis_mensaje_error, apis_intentos, apis_id_empresa
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        if (!$stmt) {
            error_log('No se pudo preparar insert api_sincronizaciones: ' . $this->conexionBdPrincipal->error);
            return 0;
        }
        $datosEnviadosJson = json_encode($datosEnviados, JSON_UNESCAPED_UNICODE);
        $respuestaJson = json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        $codigoRespuesta = $codigoRespuesta === null ? 0 : (int) $codigoRespuesta;
        $mensajeError = $mensajeError === null ? '' : (string) $mensajeError;
        
        $stmt->bind_param("sssissssisi", 
            $modulo, $direccion, $tipoOperacion, $idRegistro, $referencia,
            $datosEnviadosJson, $respuestaJson, $estado, $codigoRespuesta, 
            $mensajeError, $this->idEmpresa
        );
        
        $stmt->execute();
        return (int) $stmt->insert_id;
    }
    
    /**
     * Sincroniza un producto a Ofima
     */
    public function sincronizarProducto($producto, $tipoOperacion = 'CREATE') {
        $datosOfima = $this->mapearCamposProducto($producto);
        $referencia = isset($producto['prod_referencia']) ? $producto['prod_referencia'] : ($datosOfima['referencia'] ?? '');
        $idRegistro = isset($producto['prod_id']) ? (int) $producto['prod_id'] : 0;

        $costo = isset($producto['prod_costo']) ? floatval($producto['prod_costo']) : null;
        $utilidad = isset($producto['prod_utilidad']) ? floatval($producto['prod_utilidad']) : null;
        if ($costo !== null && $costo < 0) {
            $resultado = [
                'success' => false,
                'error' => 'El costo del producto no puede ser negativo',
                'codigo_http' => 400,
            ];
            $this->registrarSincronizacion(
                'productos', 'orion_ofima', $tipoOperacion, $idRegistro, $referencia,
                $datosOfima, $resultado, 'error', 400, $resultado['error']
            );
            return $resultado;
        }
        if ($utilidad !== null && ($utilidad < 0 || $utilidad > 100)) {
            $resultado = [
                'success' => false,
                'error' => 'La utilidad debe estar entre 0 y 100',
                'codigo_http' => 400,
            ];
            $this->registrarSincronizacion(
                'productos', 'orion_ofima', $tipoOperacion, $idRegistro, $referencia,
                $datosOfima, $resultado, 'error', 400, $resultado['error']
            );
            return $resultado;
        }

        $faltantes = [];
        foreach (['referencia', 'nombre'] as $campo) {
            if (!isset($datosOfima[$campo]) || trim((string) $datosOfima[$campo]) === '') {
                $faltantes[] = $campo;
            }
        }
        if (!empty($faltantes)) {
            $resultado = [
                'success' => false,
                'error' => 'Faltan datos requeridos para Ofima: ' . implode(', ', $faltantes),
                'codigo_http' => 400,
            ];
            $this->registrarSincronizacion(
                'productos', 'orion_ofima', $tipoOperacion, $idRegistro, $referencia,
                $datosOfima, $resultado, 'error', 400, $resultado['error']
            );
            return $resultado;
        }

        $resultado = $this->enviarAOfima('productos', $tipoOperacion, $datosOfima);

        $this->registrarSincronizacion(
            'productos',
            'orion_ofima',
            $tipoOperacion,
            $idRegistro,
            $referencia,
            $datosOfima,
            $resultado,
            $resultado['success'] ? 'exitoso' : 'error',
            $resultado['codigo_http'] ?? null,
            $resultado['success'] ? null : ($resultado['error'] ?? 'Error desconocido')
        );

        return $resultado;
    }
    
    /**
     * Sincroniza un cliente a Ofima
     */
    public function sincronizarCliente($cliente, $tipoOperacion = 'CREATE') {
        $datosOfima = $this->mapearCamposCliente($cliente);

        $camposRequeridos = ['identificacion', 'nombre', 'email', 'telefono', 'direccion', 'ciudad', 'celular', 'tipodcto'];
        $faltantes = [];
        foreach ($camposRequeridos as $campo) {
            if (!isset($datosOfima[$campo]) || trim((string) $datosOfima[$campo]) === '') {
                $faltantes[] = $campo;
            }
        }
        if (!empty($faltantes)) {
            $resultado = [
                'success' => false,
                'error' => 'Faltan datos requeridos para Ofima: ' . implode(', ', $faltantes),
                'codigo_http' => 400,
            ];
            $referencia = $datosOfima['identificacion'] ?? '';
            $idRegistro = isset($cliente['cli_id']) ? (int) $cliente['cli_id'] : 0;
            $this->registrarSincronizacion(
                'clientes',
                'orion_ofima',
                $tipoOperacion,
                $idRegistro,
                $referencia,
                $datosOfima,
                $resultado,
                'error',
                400,
                $resultado['error']
            );
            return $resultado;
        }

        $resultado = $this->enviarAOfima('clientes', $tipoOperacion, $datosOfima);
        
        $referencia = isset($cliente['cli_usuario']) ? $cliente['cli_usuario'] : (isset($cliente['cli_identificacion']) ? $cliente['cli_identificacion'] : '');
        $idRegistro = isset($cliente['cli_id']) ? $cliente['cli_id'] : 0;
        
        $this->registrarSincronizacion(
            'clientes',
            'orion_ofima',
            $tipoOperacion,
            $idRegistro,
            $referencia,
            $datosOfima,
            $resultado,
            $resultado['success'] ? 'exitoso' : 'error',
            $resultado['codigo_http'] ?? null,
            $resultado['success'] ? null : ($resultado['error'] ?? 'Error desconocido')
        );
        
        return $resultado;
    }
    
    /**
     * Sincroniza un pedido a Ofima (Orion → Ofima).
     * Si el pedido está facturado, no se permite UPDATE y se retorna error.
     *
     * @param array|int $pedido Array del pedido (con o sin 'productos') o pedid_id para cargar desde BD
     * @param string $tipoOperacion 'CREATE' o 'UPDATE'
     * @return array { success, error?, codigo_http?, datos? }
     */
    public function sincronizarPedido($pedido, $tipoOperacion = 'CREATE') {
        $pedidId = is_array($pedido) ? (isset($pedido['pedid_id']) ? (int) $pedido['pedid_id'] : 0) : (int) $pedido;
        if ($pedidId <= 0) {
            return ['success' => false, 'error' => 'ID de pedido inválido'];
        }
        
        if (!is_array($pedido) || empty($pedido['pedid_cliente']) && empty($pedido['productos'])) {
            $pedido = $this->cargarPedidoCompleto($pedidId);
            if (!$pedido) {
                return ['success' => false, 'error' => 'Pedido no encontrado'];
            }
        }
        
        if ($tipoOperacion === 'UPDATE') {
            if (Pedido::estaFacturado($pedidId, $this->conexionBdPrincipal, $this->idEmpresa)) {
                $this->registrarSincronizacion(
                    'pedidos', 'orion_ofima', 'UPDATE', $pedidId, (string) $pedidId,
                    ['pedid_id' => $pedidId], ['success' => false],
                    'error', 400, 'El pedido ya está facturado y no puede modificarse en Ofima', 1
                );
                return [
                    'success' => false,
                    'error' => 'El pedido ya está facturado. No puede modificarse en Ofima.'
                ];
            }
        }
        
        $datosOfima = $this->mapearCamposPedido($pedido);
        if (isset($datosOfima['error'])) {
            $this->registrarSincronizacion(
                'pedidos', 'orion_ofima', $tipoOperacion, $pedidId, (string) $pedidId,
                $pedido, ['success' => false], 'error', 400, $datosOfima['error'], 1
            );
            return ['success' => false, 'error' => $datosOfima['error']];
        }
        
        $resultado = $this->enviarAOfima('pedidos', $tipoOperacion, $datosOfima);
        
        $this->registrarSincronizacion(
            'pedidos',
            'orion_ofima',
            $tipoOperacion,
            $pedidId,
            (string) $pedidId,
            $datosOfima,
            $resultado,
            $resultado['success'] ? 'exitoso' : 'error',
            $resultado['codigo_http'] ?? null,
            $resultado['success'] ? null : ($resultado['error'] ?? ($resultado['datos']['error'] ?? 'Error desconocido'))
        );
        
        return $resultado;
    }
    
    /**
     * Carga pedido con cabecera, cliente (identificación) e ítems desde BD.
     */
    private function cargarPedidoCompleto($pedidId) {
        $stmt = $this->conexionBdPrincipal->prepare(
            "SELECT p.*, c.cli_usuario AS cli_identificacion, c.cli_nombre AS cli_nombre 
             FROM pedidos p 
             INNER JOIN clientes c ON c.cli_id = p.pedid_cliente AND c.cli_id_empresa = ? 
             WHERE p.pedid_id = ? AND p.pedid_id_empresa = ? LIMIT 1"
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("iii", $this->idEmpresa, $pedidId, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return null;
        }
        $pedido = $result->fetch_assoc();
        $pedido['productos'] = $this->cargarItemsPedido($pedidId);
        return $pedido;
    }
    
    /**
     * Carga ítems del pedido (cotizacion_productos tipo PED).
     * Productos sueltos se añaden tal cual; los combos se desglosan en líneas de producto para Ofima.
     */
    private function cargarItemsPedido($pedidId) {
        if (!defined('CZPP_TIPO_PED')) {
            return [];
        }
        $tipoPed = CZPP_TIPO_PED;
        $items = [];

        // 1) Líneas que son productos (no combo)
        $stmt = $this->conexionBdPrincipal->prepare(
            "SELECT cp.czpp_id, cp.czpp_producto, cp.czpp_cantidad, cp.czpp_valor, cp.czpp_descuento, cp.czpp_impuesto, prod.prod_referencia 
             FROM cotizacion_productos cp 
             LEFT JOIN productos prod ON prod.prod_id = cp.czpp_producto 
             WHERE cp.czpp_cotizacion = ? AND cp.czpp_tipo = ? AND (cp.czpp_producto IS NOT NULL AND cp.czpp_producto != '') AND (cp.czpp_combo IS NULL OR cp.czpp_combo = '')
             ORDER BY cp.czpp_orden, cp.czpp_id"
        );
        if ($stmt) {
            $stmt->bind_param("ii", $pedidId, $tipoPed);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $items[] = [
                    'producto_id' => (int) $row['czpp_producto'],
                    'referencia' => isset($row['prod_referencia']) ? $row['prod_referencia'] : '',
                    'cantidad' => (float) $row['czpp_cantidad'],
                    'valor' => (float) $row['czpp_valor'],
                    'descuento' => isset($row['czpp_descuento']) ? (float) $row['czpp_descuento'] : 0,
                    'impuesto' => isset($row['czpp_impuesto']) ? (float) $row['czpp_impuesto'] : 0
                ];
            }
        }

        // 2) Líneas que son combos: desglosar en productos
        $stmtCombo = $this->conexionBdPrincipal->prepare(
            "SELECT cp.czpp_cantidad, cp.czpp_valor, cp.czpp_descuento, cp.czpp_impuesto, cp.czpp_productos_en_combo_generar_pedido, cp.czpp_combo 
             FROM cotizacion_productos cp 
             WHERE cp.czpp_cotizacion = ? AND cp.czpp_tipo = ? AND cp.czpp_combo IS NOT NULL AND cp.czpp_combo != ''
             ORDER BY cp.czpp_orden, cp.czpp_id"
        );
        if (!$stmtCombo) {
            return $items;
        }
        $stmtCombo->bind_param("ii", $pedidId, $tipoPed);
        $stmtCombo->execute();
        $resultCombo = $stmtCombo->get_result();
        $productIdsParaRef = [];

        while ($comboRow = $resultCombo->fetch_assoc()) {
            $cantidadCombos = !empty($comboRow['czpp_cantidad']) ? (float) $comboRow['czpp_cantidad'] : 1;
            $impuestoCombo = isset($comboRow['czpp_impuesto']) ? (float) $comboRow['czpp_impuesto'] : 0;
            $descuentoCombo = isset($comboRow['czpp_descuento']) ? (float) $comboRow['czpp_descuento'] : 0;
            $jsonProductos = isset($comboRow['czpp_productos_en_combo_generar_pedido']) ? $comboRow['czpp_productos_en_combo_generar_pedido'] : '';

            $productosCombo = [];
            if ($jsonProductos !== '' && $jsonProductos !== null) {
                $decoded = json_decode($jsonProductos, true);
                if (is_array($decoded)) {
                    $productosCombo = $decoded;
                }
            }
            if (empty($productosCombo)) {
                $comboId = (int) $comboRow['czpp_combo'];
                $productosCombo = $this->obtenerProductosComboDesdeTabla($comboId);
            }
            foreach ($productosCombo as $comProd) {
                $idProd = isset($comProd['id_producto']) ? (int) $comProd['id_producto'] : (isset($comProd['prod_id']) ? (int) $comProd['prod_id'] : 0);
                if ($idProd <= 0) {
                    continue;
                }
                $cantidadEnCombo = isset($comProd['cantidad_en_combo']) ? (float) $comProd['cantidad_en_combo'] : (isset($comProd['copp_cantidad']) ? (float) $comProd['copp_cantidad'] : 1);
                $cantidadTotal = $cantidadEnCombo * $cantidadCombos;
                if ($cantidadTotal <= 0) {
                    continue;
                }
                $precioUnit = isset($comProd['precio_unitario_cotizado']) ? (float) $comProd['precio_unitario_cotizado'] : (isset($comProd['copp_precio']) ? (float) $comProd['copp_precio'] : 0);
                $descCombo = isset($comProd['descuento_del_combo']) ? (float) $comProd['descuento_del_combo'] : 0;
                $valorUnit = $precioUnit - ($precioUnit * ($descCombo / 100));
                $items[] = [
                    'producto_id' => $idProd,
                    'referencia' => '',
                    'cantidad' => $cantidadTotal,
                    'valor' => round($valorUnit, 2),
                    'descuento' => $descuentoCombo,
                    'impuesto' => $impuestoCombo
                ];
                $productIdsParaRef[$idProd] = true;
            }
        }

        if (!empty($productIdsParaRef)) {
            $ids = array_keys($productIdsParaRef);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmtRef = $this->conexionBdPrincipal->prepare("SELECT prod_id, prod_referencia FROM productos WHERE prod_id IN ($placeholders) AND prod_id_empresa = ?");
            if ($stmtRef) {
                $tipos = str_repeat('i', count($ids)) . 'i';
                $params = array_merge($ids, [$this->idEmpresa]);
                $refs = [];
                foreach ($params as $key => $value) {
                    $refs[$key] = &$params[$key];
                }
                array_unshift($refs, $tipos);
                call_user_func_array([$stmtRef, 'bind_param'], $refs);
                $stmtRef->execute();
                $resRef = $stmtRef->get_result();
                $mapRef = [];
                while ($r = $resRef->fetch_assoc()) {
                    $mapRef[(int) $r['prod_id']] = isset($r['prod_referencia']) ? $r['prod_referencia'] : '';
                }
                foreach ($items as $idx => $it) {
                    if (($it['referencia'] ?? '') === '' && isset($mapRef[$it['producto_id']])) {
                        $items[$idx]['referencia'] = $mapRef[$it['producto_id']];
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Obtiene los productos de un combo desde combos_productos (fallback si no hay JSON en la línea del pedido).
     */
    private function obtenerProductosComboDesdeTabla($comboId) {
        $stmt = $this->conexionBdPrincipal->prepare(
            "SELECT copp_producto AS id_producto, copp_cantidad AS cantidad_en_combo, copp_precio AS precio_unitario_cotizado 
             FROM combos_productos 
             WHERE copp_combo = ?"
        );
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("i", $comboId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = [
                'id_producto' => (int) $row['id_producto'],
                'cantidad_en_combo' => (float) $row['cantidad_en_combo'],
                'precio_unitario_cotizado' => (float) $row['precio_unitario_cotizado'],
                'descuento_del_combo' => 0
            ];
        }
        return $list;
    }
    
    /**
     * Mapea campos de producto de Orion a Ofima.
     * Default: referencia, nombre, costo, utilidad, precio, ids y id_empresa.
     */
    private function mapearCamposProducto($producto) {
        $mapeo = $this->obtenerMapeoCampos('productos');
        $datosOfima = [];

        if (!empty($mapeo)) {
            foreach ($mapeo as $campo) {
                $campoOrion = $campo['apim_campo_orion'];
                $campoOfima = $campo['apim_campo_ofima'];
                if (isset($producto[$campoOrion]) && $producto[$campoOrion] !== '' && $producto[$campoOrion] !== null) {
                    $datosOfima[$campoOfima] = $this->aplicarTransformacion($producto[$campoOrion], $campo['apim_tipo_transformacion']);
                } elseif (!empty($campo['apim_valor_default'])) {
                    $datosOfima[$campoOfima] = $campo['apim_valor_default'];
                }
            }
        }

        if (empty($datosOfima)) {
            $datosOfima = [
                'referencia' => (string) ($producto['prod_referencia'] ?? ''),
                'nombre' => (string) ($producto['prod_nombre'] ?? ''),
                'costo' => isset($producto['prod_costo']) ? (float) $producto['prod_costo'] : 0,
                'utilidad' => isset($producto['prod_utilidad']) ? (float) $producto['prod_utilidad'] : 0,
                'categoria_id' => (int) ($producto['prod_categoria'] ?? 0),
                'grupo_id' => (int) ($producto['prod_grupo1'] ?? 0),
                'marca_id' => (int) ($producto['prod_marca'] ?? 0),
                'proveedor_id' => (int) ($producto['prod_proveedor'] ?? 0),
            ];
            if (isset($producto['prod_precio']) && $producto['prod_precio'] !== '' && $producto['prod_precio'] !== null) {
                $datosOfima['precio'] = (float) $producto['prod_precio'];
            }
        }

        $datosOfima['id_empresa'] = (int) $this->idEmpresa;
        if (!isset($datosOfima['referencia']) || $datosOfima['referencia'] === '') {
            $datosOfima['referencia'] = (string) ($producto['prod_referencia'] ?? '');
        }
        if (!isset($datosOfima['nombre']) || $datosOfima['nombre'] === '') {
            $datosOfima['nombre'] = (string) ($producto['prod_nombre'] ?? '');
        }

        unset($datosOfima['clasificacion_id']);
        $codigoLinea = $this->codigoGrupoOfima($producto['prod_grupo1'] ?? 0);
        $codigoSublinea = $this->codigoGrupoOfima($producto['prod_categoria'] ?? 0);
        $datosOfima['grupo_1'] = $codigoLinea;
        $datosOfima['grupo_id'] = $codigoLinea;
        $datosOfima['grupo_2'] = $codigoSublinea;
        $datosOfima['categoria_id'] = $codigoSublinea;
        $datosOfima['grupo_3'] = $this->codigoGrupoOfima($producto['prod_grupo3'] ?? 0);
        $datosOfima['marca_id'] = $this->codigoMarcaOfima($producto['prod_marca'] ?? 0);

        $nombre = trim((string) ($datosOfima['nombre'] ?? $producto['prod_nombre'] ?? ''));
        $corta = trim(strip_tags((string) ($producto['prod_descripcion_corta'] ?? '')));
        $larga = trim(strip_tags((string) ($producto['prod_descripcion_larga'] ?? '')));
        $datosOfima['descripcion_corta'] = $corta !== '' ? $corta : $nombre;
        $datosOfima['descripcion_larga'] = $larga !== '' ? $larga : ($corta !== '' ? $corta : $nombre);

        return $datosOfima;
    }

    /**
     * Código Ofima (catp_cod_grupo) de una categoría. Se envía como texto para conservar ceros.
     */
    private function codigoGrupoOfima($categoriaId) {
        $categoriaId = (int) $categoriaId;
        if ($categoriaId <= 0) {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT catp_cod_grupo FROM productos_categorias WHERE catp_id = ? AND catp_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('ii', $categoriaId, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $codigo = trim((string) ($row['catp_cod_grupo'] ?? ''));
        return $codigo === '' ? null : $codigo;
    }

    /**
     * Código Ofima de la marca (mar_cod_ofima).
     */
    private function codigoMarcaOfima($marcaId) {
        $marcaId = (int) $marcaId;
        if ($marcaId <= 0) {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT mar_cod_ofima FROM marcas WHERE mar_id = ? AND mar_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('ii', $marcaId, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $codigo = trim((string) ($row['mar_cod_ofima'] ?? ''));
        return $codigo === '' ? null : $codigo;
    }
    
    /**
     * Mapea campos de cliente de Orion a Ofima.
     * Payload: identificacion, nombre, email, telefono, direccion, ciudad (DIAN),
     * celular, referencia, id_empresa, tipodcto.
     */
    private function mapearCamposCliente($cliente) {
        $ciudadId = isset($cliente['cli_ciudad']) ? (int) $cliente['cli_ciudad'] : 0;
        $identificacion = (string) ($cliente['cli_usuario'] ?? $cliente['cli_identificacion'] ?? '');
        $referencia = trim((string) ($cliente['cli_referencia'] ?? ''));
        if ($referencia === '') {
            $referencia = $identificacion;
        }

        return [
            'identificacion' => $identificacion,
            'nombre' => (string) ($cliente['cli_nombre'] ?? ''),
            'email' => (string) ($cliente['cli_email'] ?? ''),
            'telefono' => (string) ($cliente['cli_telefono'] ?? ''),
            'direccion' => (string) ($cliente['cli_direccion'] ?? ''),
            'ciudad' => $this->obtenerCodigoDianCiudad($ciudadId),
            'celular' => (string) ($cliente['cli_celular'] ?? ''),
            'referencia' => $referencia,
            'id_empresa' => (int) $this->idEmpresa,
            'tipodcto' => $this->mapearTipoDocumentoOfima($cliente['cli_tipo_documento'] ?? null),
        ];
    }

    /**
     * Obtiene ciu_cod_dian homologado para Ofima/DIAN.
     */
    private function obtenerCodigoDianCiudad($ciudadId) {
        $ciudadId = (int) $ciudadId;
        if ($ciudadId <= 0 || !defined('BDADMIN')) {
            return '';
        }

        $sql = 'SELECT ciu_cod_dian FROM ' . BDADMIN . '.localidad_ciudades WHERE ciu_id = ? LIMIT 1';
        $stmt = $this->conexionBdPrincipal->prepare($sql);
        if (!$stmt) {
            return '';
        }
        $stmt->bind_param('i', $ciudadId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row || empty($row['ciu_cod_dian'])) {
            return '';
        }
        return (string) $row['ciu_cod_dian'];
    }

    /**
     * Homologa tipo documento Orion → tipodcto Ofima.
     * 2=NIT, 3=Cédula (resto por defecto C).
     */
    private function mapearTipoDocumentoOfima($tipoDocumento) {
        $tipo = (int) $tipoDocumento;
        if ($tipo === 2) {
            return 'N';
        }
        if ($tipo === 3) {
            return 'C';
        }
        return 'C';
    }
    
    /**
     * Mapea campos de pedido de Orion a Ofima (cabecera + ítems).
     * Ofima espera: numero, cliente_identificacion (NIT), fecha, observaciones, items[], id_empresa.
     */
    private function mapearCamposPedido($pedido) {
        $productos = isset($pedido['productos']) ? $pedido['productos'] : [];
        $items = [];
        foreach ($productos as $item) {
            $items[] = [
                'referencia' => isset($item['referencia']) ? $item['referencia'] : '',
                'producto_id' => isset($item['producto_id']) ? (int) $item['producto_id'] : 0,
                'cantidad' => isset($item['cantidad']) ? (float) $item['cantidad'] : 0,
                'valor' => isset($item['valor']) ? (float) $item['valor'] : 0,
                'descuento' => isset($item['descuento']) ? (float) $item['descuento'] : 0,
                'impuesto' => isset($item['impuesto']) ? (float) $item['impuesto'] : 0
            ];
        }
        $identificacion = isset($pedido['cli_identificacion']) ? $pedido['cli_identificacion'] : '';
        if ($identificacion === '' && !empty($pedido['pedid_cliente'])) {
            $stmt = $this->conexionBdPrincipal->prepare("SELECT cli_usuario FROM clientes WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("ii", $pedido['pedid_cliente'], $this->idEmpresa);
                $stmt->execute();
                $r = $stmt->get_result()->fetch_assoc();
                $identificacion = $r ? $r['cli_usuario'] : '';
            }
        }
        $fecha = isset($pedido['pedid_fecha_propuesta']) ? $pedido['pedid_fecha_propuesta'] : (isset($pedido['pedid_fecha_creacion']) ? $pedido['pedid_fecha_creacion'] : date('Y-m-d'));
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $fecha) === 0) {
            $fecha = date('Y-m-d', strtotime($fecha));
        }
        if (empty($items)) {
            return ['error' => 'El pedido no tiene ítems de producto para enviar a Ofima'];
        }
        return [
            'numero' => isset($pedido['pedid_id']) ? (string) $pedido['pedid_id'] : '',
            'cliente_identificacion' => $identificacion,
            'cliente_id' => isset($pedido['pedid_cliente']) ? (int) $pedido['pedid_cliente'] : 0,
            'fecha' => $fecha,
            'observaciones' => isset($pedido['pedid_observaciones']) ? $pedido['pedid_observaciones'] : '',
            'items' => $items,
            'id_empresa' => $this->idEmpresa
        ];
    }
    
    /**
     * Obtiene el mapeo de campos desde la base de datos
     */
    private function obtenerMapeoCampos($modulo) {
        $query = "SELECT * FROM api_mapeo_campos 
                  WHERE apim_modulo = ? 
                  AND apim_id_empresa = ? 
                  AND apim_activo = 1";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("si", $modulo, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $mapeo = [];
        while ($row = $result->fetch_assoc()) {
            $mapeo[] = $row;
        }
        
        return $mapeo;
    }
    
    /**
     * Aplica transformación al valor según el tipo
     */
    private function aplicarTransformacion($valor, $tipo) {
        switch ($tipo) {
            case 'decimal':
                return floatval($valor);
            case 'entero':
                return intval($valor);
            case 'fecha':
                return date('Y-m-d', strtotime($valor));
            case 'fecha_hora':
                return date('Y-m-d H:i:s', strtotime($valor));
            default:
                return $valor;
        }
    }
    
    /**
     * Desencripta la contraseña (implementación básica - mejorar con encriptación real)
     */
    private function desencriptarPassword($passwordEncriptado) {
        // TODO: Implementar desencriptación real
        // Por ahora retornamos el valor tal cual (debe cambiarse)
        return base64_decode($passwordEncriptado);
    }
}

