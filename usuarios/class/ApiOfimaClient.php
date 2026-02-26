<?php
require_once RUTA_PROYECTO.'/conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';

/**
 * Clase para consumir APIs de Ofima desde Orion
 * Maneja la comunicación HTTP con autenticación básica
 */
class ApiOfimaClient {
    
    private $conexionBdPrincipal;
    private $idEmpresa;
    
    public function __construct($conexionBdPrincipal, $idEmpresa) {
        $this->conexionBdPrincipal = $conexionBdPrincipal;
        $this->idEmpresa = $idEmpresa;
    }
    
    /**
     * Obtiene la configuración de API para un módulo y dirección específicos
     */
    private function obtenerConfiguracion($modulo, $direccion = 'orion_ofima') {
        $query = "SELECT * FROM api_configuracion 
                  WHERE apic_modulo = ? 
                  AND apic_direccion = ? 
                  AND apic_id_empresa = ? 
                  AND apic_activo = 1
                  LIMIT 1";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("ssi", $modulo, $direccion, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Realiza una petición HTTP a la API de Ofima
     */
    private function realizarPeticion($url, $metodo = 'POST', $datos = null, $usuario = null, $password = null) {
        $ch = curl_init();
        
        // Configurar opciones básicas
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        // Autenticación básica
        if ($usuario && $password) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $usuario . ":" . $password);
        }
        
        // Headers
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Método y datos
        if ($metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($datos) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            }
        } elseif ($metodo === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($datos) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
            }
        }
        
        // Ejecutar petición
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
    
    /**
     * Registra una sincronización en la base de datos
     */
    private function registrarSincronizacion($modulo, $direccion, $tipoOperacion, $idRegistro, $referencia, $datosEnviados, $respuesta, $estado, $codigoRespuesta = null, $mensajeError = null) {
        $query = "INSERT INTO api_sincronizaciones (
            apis_modulo, apis_direccion, apis_tipo_operacion, apis_id_registro, 
            apis_referencia, apis_datos_enviados, apis_respuesta, apis_estado, 
            apis_codigo_respuesta, apis_mensaje_error, apis_intentos, apis_id_empresa
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $datosEnviadosJson = json_encode($datosEnviados);
        $respuestaJson = json_encode($respuesta);
        
        $stmt->bind_param("sssissssisi", 
            $modulo, $direccion, $tipoOperacion, $idRegistro, $referencia,
            $datosEnviadosJson, $respuestaJson, $estado, $codigoRespuesta, 
            $mensajeError, $this->idEmpresa
        );
        
        $stmt->execute();
        return $stmt->insert_id;
    }
    
    /**
     * Sincroniza un producto a Ofima
     */
    public function sincronizarProducto($producto, $tipoOperacion = 'CREATE') {
        $config = $this->obtenerConfiguracion('productos', 'orion_ofima');
        
        if (!$config) {
            return [
                'success' => false,
                'error' => 'No hay configuración de API para productos'
            ];
        }
        
        // Validar datos antes de enviar (referencia y nombre ya validados en Producto; validar numéricos)
        $costo = isset($producto['prod_costo']) ? floatval($producto['prod_costo']) : null;
        $utilidad = isset($producto['prod_utilidad']) ? floatval($producto['prod_utilidad']) : null;
        if ($costo !== null && $costo < 0) {
            return [
                'success' => false,
                'error' => 'El costo del producto no puede ser negativo'
            ];
        }
        if ($utilidad !== null && ($utilidad < 0 || $utilidad > 100)) {
            return [
                'success' => false,
                'error' => 'La utilidad debe estar entre 0 y 100'
            ];
        }
        
        // Mapear campos de Orion a Ofima
        $datosOfima = $this->mapearCamposProducto($producto);
        
        // Realizar petición
        $resultado = $this->realizarPeticion(
            $config['apic_url_endpoint'],
            $tipoOperacion === 'CREATE' ? 'POST' : 'PUT',
            $datosOfima,
            $config['apic_usuario'],
            $this->desencriptarPassword($config['apic_password'])
        );
        
        // Registrar sincronización
        $referencia = isset($producto['prod_referencia']) ? $producto['prod_referencia'] : '';
        $idRegistro = isset($producto['prod_id']) ? $producto['prod_id'] : 0;
        
        $this->registrarSincronizacion(
            'productos',
            'orion_ofima',
            $tipoOperacion,
            $idRegistro,
            $referencia,
            $datosOfima,
            $resultado,
            $resultado['success'] ? 'exitoso' : 'error',
            $resultado['codigo_http'],
            $resultado['success'] ? null : ($resultado['error'] ?? 'Error desconocido')
        );
        
        return $resultado;
    }
    
    /**
     * Sincroniza un cliente a Ofima
     */
    public function sincronizarCliente($cliente, $tipoOperacion = 'CREATE') {
        $config = $this->obtenerConfiguracion('clientes', 'orion_ofima');
        
        if (!$config) {
            return [
                'success' => false,
                'error' => 'No hay configuración de API para clientes'
            ];
        }
        
        // Mapear campos de Orion a Ofima
        $datosOfima = $this->mapearCamposCliente($cliente);
        
        // Realizar petición
        $resultado = $this->realizarPeticion(
            $config['apic_url_endpoint'],
            $tipoOperacion === 'CREATE' ? 'POST' : 'PUT',
            $datosOfima,
            $config['apic_usuario'],
            $this->desencriptarPassword($config['apic_password'])
        );
        
        // Registrar sincronización (identificador único: NIT/documento = cli_usuario)
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
            $resultado['codigo_http'],
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
        $config = $this->obtenerConfiguracion('pedidos', 'orion_ofima');
        
        if (!$config) {
            return [
                'success' => false,
                'error' => 'No hay configuración de API para pedidos'
            ];
        }
        
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
        
        $resultado = $this->realizarPeticion(
            $config['apic_url_endpoint'],
            $tipoOperacion === 'CREATE' ? 'POST' : 'PUT',
            $datosOfima,
            $config['apic_usuario'],
            $this->desencriptarPassword($config['apic_password'])
        );
        
        $this->registrarSincronizacion(
            'pedidos',
            'orion_ofima',
            $tipoOperacion,
            $pedidId,
            (string) $pedidId,
            $datosOfima,
            $resultado,
            $resultado['success'] ? 'exitoso' : 'error',
            $resultado['codigo_http'],
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
     * Carga ítems del pedido (cotizacion_productos tipo PED), con referencia del producto.
     */
    private function cargarItemsPedido($pedidId) {
        if (!defined('CZPP_TIPO_PED')) {
            return [];
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            "SELECT cp.czpp_id, cp.czpp_producto, cp.czpp_cantidad, cp.czpp_valor, cp.czpp_descuento, cp.czpp_impuesto, cp.czpp_combo, prod.prod_referencia 
             FROM cotizacion_productos cp 
             LEFT JOIN productos prod ON prod.prod_id = cp.czpp_producto 
             WHERE cp.czpp_cotizacion = ? AND cp.czpp_tipo = ? AND (cp.czpp_producto IS NOT NULL AND cp.czpp_producto != '')
             ORDER BY cp.czpp_orden, cp.czpp_id"
        );
        if (!$stmt) {
            return [];
        }
        $tipoPed = CZPP_TIPO_PED;
        $stmt->bind_param("ii", $pedidId, $tipoPed);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = [
                'producto_id' => (int) $row['czpp_producto'],
                'referencia' => $row['prod_referencia'] ?? '',
                'cantidad' => (float) $row['czpp_cantidad'],
                'valor' => (float) $row['czpp_valor'],
                'descuento' => isset($row['czpp_descuento']) ? (float) $row['czpp_descuento'] : 0,
                'impuesto' => isset($row['czpp_impuesto']) ? (float) $row['czpp_impuesto'] : 0
            ];
        }
        return $items;
    }
    
    /**
     * Mapea campos de producto de Orion a Ofima
     */
    private function mapearCamposProducto($producto) {
        // Obtener mapeo de campos desde la base de datos
        $mapeo = $this->obtenerMapeoCampos('productos');
        
        $datosOfima = [];
        
        foreach ($mapeo as $campo) {
            $campoOrion = $campo['apim_campo_orion'];
            $campoOfima = $campo['apim_campo_ofima'];
            
            if (isset($producto[$campoOrion])) {
                $valor = $producto[$campoOrion];
                
                // Aplicar transformación si es necesario
                $valor = $this->aplicarTransformacion($valor, $campo['apim_tipo_transformacion']);
                
                $datosOfima[$campoOfima] = $valor;
            } elseif ($campo['apim_valor_default']) {
                $datosOfima[$campoOfima] = $campo['apim_valor_default'];
            }
        }
        
        return $datosOfima;
    }
    
    /**
     * Mapea campos de cliente de Orion a Ofima
     * En Orion el NIT/documento es cli_usuario; se envía como identificacion.
     */
    private function mapearCamposCliente($cliente) {
        $mapeo = $this->obtenerMapeoCampos('clientes');
        if (!empty($mapeo)) {
            $datosOfima = [];
            foreach ($mapeo as $campo) {
                $campoOrion = $campo['apim_campo_orion'];
                $campoOfima = $campo['apim_campo_ofima'];
                if (isset($cliente[$campoOrion])) {
                    $datosOfima[$campoOfima] = $this->aplicarTransformacion($cliente[$campoOrion], $campo['apim_tipo_transformacion']);
                } elseif ($campo['apim_valor_default']) {
                    $datosOfima[$campoOfima] = $campo['apim_valor_default'];
                }
            }
            $datosOfima['id_empresa'] = $this->idEmpresa;
            return $datosOfima;
        }
        return [
            'identificacion' => $cliente['cli_usuario'] ?? $cliente['cli_identificacion'] ?? '',
            'nombre' => $cliente['cli_nombre'] ?? '',
            'email' => $cliente['cli_email'] ?? '',
            'telefono' => $cliente['cli_telefono'] ?? '',
            'direccion' => $cliente['cli_direccion'] ?? '',
            'ciudad' => $cliente['cli_ciudad'] ?? '',
            'celular' => $cliente['cli_celular'] ?? '',
            'referencia' => $cliente['cli_referencia'] ?? '',
            'id_empresa' => $this->idEmpresa
        ];
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

