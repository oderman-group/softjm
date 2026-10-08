<?php
require_once RUTA_PROYECTO.'/conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';
require_once RUTA_PROYECTO.'/usuarios/class/JwtHelper.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';

/**
 * Clase para exponer servicios API desde Orion
 * Procesa datos recibidos desde Ofima y los sincroniza en Orion
 */
class ApiOrionService {
    
    private $conexionBdPrincipal;
    private $idEmpresa;
    
    public function __construct($conexionBdPrincipal, $idEmpresa) {
        $this->conexionBdPrincipal = $conexionBdPrincipal;
        $this->idEmpresa = $idEmpresa;
    }
    
    /**
     * Valida un JWT y devuelve el payload (id_empresa, sub, etc.) o null si es inválido/expirado.
     *
     * @param string $token Token JWT (con o sin prefijo "Bearer ")
     * @return array|null Payload con al menos id_empresa y sub, o null
     */
    public static function validarTokenJwt($token) {
        if (empty($token)) {
            return null;
        }
        $payload = JwtHelper::decode($token);
        if ($payload === null || !isset($payload['id_empresa'])) {
            return null;
        }
        return $payload;
    }
    
    /**
     * Verifica las credenciales de autenticación (Basic Auth) para el módulo productos
     */
    public function verificarAutenticacion($usuario, $password) {
        return $this->verificarAutenticacionModulo('productos', $usuario, $password);
    }

    /**
     * Verifica las credenciales de autenticación (Basic Auth) para el módulo clientes
     */
    public function verificarAutenticacionCliente($usuario, $password) {
        return $this->verificarAutenticacionModulo('clientes', $usuario, $password);
    }

    /**
     * Verifica las credenciales de autenticación (Basic Auth) para el módulo inventario
     */
    public function verificarAutenticacionInventario($usuario, $password) {
        return $this->verificarAutenticacionModulo('inventario', $usuario, $password);
    }

    /**
     * Verifica las credenciales de autenticación (Basic Auth) para el módulo bodegas
     */
    public function verificarAutenticacionBodegas($usuario, $password) {
        return $this->verificarAutenticacionModulo('bodegas', $usuario, $password);
    }

    /**
     * Verifica credenciales contra api_configuracion para un módulo (productos, clientes, etc.)
     */
    private function verificarAutenticacionModulo($modulo, $usuario, $password) {
        $query = "SELECT apic_usuario, apic_password FROM api_configuracion 
                  WHERE apic_modulo = ? 
                  AND apic_direccion = 'ofima_orion' 
                  AND apic_id_empresa = ? 
                  AND apic_activo = 1
                  LIMIT 1";
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("si", $modulo, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return false;
        }
        $config = $result->fetch_assoc();
        if ($config['apic_usuario'] !== $usuario) {
            return false;
        }
        $passwordDesencriptado = $this->desencriptarPassword($config['apic_password']);
        return $passwordDesencriptado === $password;
    }
    
    /**
     * Recibe y procesa un producto desde Ofima
     */
    public function recibirProductoDeOfima($datosOfima) {
        try {
            // Mapear campos de Ofima a Orion
            $datosOrion = $this->mapearCamposProductoDeOfima($datosOfima);
            // Llegó por productos-recibir → queda marcado como integrado con Ofima
            $datosOrion['prod_integrado_ofima'] = 1;

            // Verificar si el producto ya existe por referencia
            $productoExistente = $this->buscarProductoPorReferencia($datosOrion['prod_referencia']);
            
            if ($productoExistente) {
                // Actualizar producto existente
                return $this->actualizarProducto($productoExistente['prod_id'], $datosOrion);
            } else {
                // Crear nuevo producto
                return $this->crearProducto($datosOrion);
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Mapea campos de Ofima a Orion
     */
    private function mapearCamposProductoDeOfima($datosOfima) {
        // Obtener mapeo de campos desde la base de datos
        $mapeo = $this->obtenerMapeoCampos('productos');
        
        $datosOrion = [
            'prod_id_empresa' => $this->idEmpresa
        ];
        
        // Crear mapa inverso (Ofima -> Orion)
        $mapaInverso = [];
        foreach ($mapeo as $campo) {
            $mapaInverso[$campo['apim_campo_ofima']] = [
                'campo_orion' => $campo['apim_campo_orion'],
                'transformacion' => $campo['apim_tipo_transformacion'],
                'default' => $campo['apim_valor_default']
            ];
        }
        
        // Mapear cada campo
        foreach ($datosOfima as $campoOfima => $valor) {
            if (isset($mapaInverso[$campoOfima])) {
                $campoOrion = $mapaInverso[$campoOfima]['campo_orion'];
                $transformacion = $mapaInverso[$campoOfima]['transformacion'];
                
                // Aplicar transformación
                $valor = $this->aplicarTransformacion($valor, $transformacion);
                
                $datosOrion[$campoOrion] = $valor;
            }
        }
        
        // Si no hay mapeo configurado, usar mapeo por defecto
        if (empty($mapeo)) {
            $datosOrion = [
                'prod_referencia' => $datosOfima['referencia'] ?? '',
                'prod_nombre' => $datosOfima['nombre'] ?? '',
                'prod_costo' => $datosOfima['costo'] ?? 0,
                'prod_utilidad' => $datosOfima['utilidad'] ?? 0,
                'prod_precio' => $datosOfima['precio'] ?? 0,
                // Clasificación: se resuelve en aplicarClasificacionOfima (códigos Ofima → IDs Orion)
                'prod_proveedor' => $datosOfima['proveedor_id'] ?? null,
                'prod_descripcion_corta' => $datosOfima['descripcion_corta'] ?? '',
                'prod_descripcion_larga' => $datosOfima['descripcion_larga'] ?? '',
                'prod_descuento1' => $datosOfima['descuento1'] ?? null,
                'prod_comision' => $datosOfima['comision'] ?? null,
                'prod_costo_dolar' => $datosOfima['costo_dolar'] ?? null,
                'prod_id_empresa' => $this->idEmpresa
            ];
        }
        
        $this->aplicarClasificacionOfima($datosOfima, $datosOrion);

        // Convención utilidad: Orion almacena siempre 0-100. Ofima puede enviar 0-100 (porcentaje) o 0-1 (factor).
        if (isset($datosOrion['prod_utilidad'])) {
            $u = floatval($datosOrion['prod_utilidad']);
            if ($u >= 0 && $u <= 1) {
                $datosOrion['prod_utilidad'] = $u * 100;
            }
        }
        
        // Calcular precio si no viene pero hay costo y utilidad (prod_utilidad ya en 0-100)
        if ((!isset($datosOrion['prod_precio']) || $datosOrion['prod_precio'] === '' || $datosOrion['prod_precio'] === null)
            && isset($datosOrion['prod_costo']) && isset($datosOrion['prod_utilidad'])
            && (floatval($datosOrion['prod_costo']) > 0 || floatval($datosOrion['prod_utilidad']) > 0)) {
            $utilidadFactor = floatval($datosOrion['prod_utilidad']) / 100;
            $datosOrion['prod_precio'] = Producto::CalcularPrecioLista((float) $datosOrion['prod_costo'], $utilidadFactor);
        }
        
        return $datosOrion;
    }

    /**
     * Clasificación Ofima → Orion.
     * Ofima envía códigos (linea/sublinea/grupo o grupo_id/categoria_id/marca_id como catp_cod_grupo / mar_cod_ofima).
     * Orion resuelve y guarda IDs internos: prod_grupo1, prod_categoria, prod_marca, prod_grupo3.
     * Nunca se persiste el código Ofima en esas columnas.
     */
    private function aplicarClasificacionOfima($datosOfima, &$datosOrion) {
        unset($datosOrion['prod_grupo1'], $datosOrion['prod_marca'], $datosOrion['prod_grupo3'], $datosOrion['prod_categoria']);

        // Línea → prod_grupo1 (catp_grupo = 1)
        $codigoLinea = $this->primerTexto($datosOfima, ['linea', 'grupo_1', 'grupo_id']);
        $idLinea = $this->resolverCategoriaDesdeOfima($codigoLinea, 1);
        if ($idLinea !== null) {
            $datosOrion['prod_grupo1'] = $idLinea;
        }

        // Sublínea → prod_categoria (catp_grupo = 2). Default 1 si no viene o no se resuelve.
        $codigoSublinea = $this->primerTexto($datosOfima, ['sublinea', 'grupo_2', 'categoria_id']);
        $idSublinea = $this->resolverCategoriaDesdeOfima($codigoSublinea, 2);
        $datosOrion['prod_categoria'] = $idSublinea !== null ? $idSublinea : 1;

        // Clasificación 1 → prod_grupo3 (catp_grupo = 3)
        $codigoClasif = $this->primerTexto($datosOfima, ['clasificacion_1', 'grupo_3']);
        $idClasif = $this->resolverCategoriaDesdeOfima($codigoClasif, 3);
        if ($idClasif !== null) {
            $datosOrion['prod_grupo3'] = $idClasif;
        }

        // Grupo / marca → prod_marca (mar_cod_ofima)
        $codigoMarca = $this->primerTexto($datosOfima, ['grupo', 'marca_id']);
        $idMarca = $this->resolverMarcaDesdeOfima($codigoMarca);
        if ($idMarca !== null) {
            $datosOrion['prod_marca'] = $idMarca;
        }

        if (array_key_exists('habilitado', $datosOfima) && $datosOfima['habilitado'] !== null && $datosOfima['habilitado'] !== '') {
            $datosOrion['prod_habilitado'] = ((int) $datosOfima['habilitado']) === 1 ? 1 : 0;
        }
    }

    private function primerTexto($datos, $claves) {
        foreach ($claves as $clave) {
            if (!isset($datos[$clave]) || $datos[$clave] === null) {
                continue;
            }
            $texto = trim((string) $datos[$clave]);
            if ($texto !== '') {
                return $texto;
            }
        }
        return '';
    }

    /**
     * Resuelve código Ofima (catp_cod_grupo) → catp_id.
     * Si no hay código y el valor parece ID Orion puro (sin ceros a la izquierda), valida por catp_id.
     */
    private function resolverCategoriaDesdeOfima($codigo, $grupo) {
        $codigo = trim((string) $codigo);
        if ($codigo === '' || $codigo === '0') {
            return null;
        }

        $porCodigo = $this->buscarCategoriaIdPorCodigo($codigo, $grupo);
        if ($porCodigo !== null) {
            return $porCodigo;
        }

        // "001" no debe caer a catp_id=1; solo IDs sin padding (ej. "12")
        if (ctype_digit($codigo) && (string) (int) $codigo === $codigo) {
            return $this->buscarCategoriaIdPorId((int) $codigo, $grupo);
        }

        return null;
    }

    /**
     * Resuelve mar_cod_ofima → mar_id (o mar_id si viene ID Orion puro).
     */
    private function resolverMarcaDesdeOfima($codigo) {
        $codigo = trim((string) $codigo);
        if ($codigo === '' || $codigo === '0') {
            return null;
        }

        $porCodigo = $this->buscarMarcaIdPorCodigo($codigo);
        if ($porCodigo !== null) {
            return $porCodigo;
        }

        if (ctype_digit($codigo) && (string) (int) $codigo === $codigo) {
            return $this->buscarMarcaIdPorId((int) $codigo);
        }

        return null;
    }

    private function buscarCategoriaIdPorCodigo($codigo, $grupo) {
        $codigo = trim((string) $codigo);
        if ($codigo === '') {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT catp_id FROM productos_categorias WHERE catp_cod_grupo = ? AND catp_grupo = ? AND catp_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $grupo = (int) $grupo;
        $stmt->bind_param('sii', $codigo, $grupo, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['catp_id'] : null;
    }

    private function buscarCategoriaIdPorId($catpId, $grupo) {
        $catpId = (int) $catpId;
        $grupo = (int) $grupo;
        if ($catpId <= 0) {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT catp_id FROM productos_categorias WHERE catp_id = ? AND catp_grupo = ? AND catp_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('iii', $catpId, $grupo, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['catp_id'] : null;
    }

    private function buscarMarcaIdPorCodigo($codigo) {
        $codigo = trim((string) $codigo);
        if ($codigo === '') {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT mar_id FROM marcas WHERE mar_cod_ofima = ? AND mar_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('si', $codigo, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['mar_id'] : null;
    }

    private function buscarMarcaIdPorId($marId) {
        $marId = (int) $marId;
        if ($marId <= 0) {
            return null;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'SELECT mar_id FROM marcas WHERE mar_id = ? AND mar_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('ii', $marId, $this->idEmpresa);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['mar_id'] : null;
    }
    
    /**
     * Busca un producto por referencia
     */
    private function buscarProductoPorReferencia($referencia) {
        $query = "SELECT * FROM productos 
                  WHERE prod_referencia = ? 
                  AND prod_id_empresa = ? 
                  LIMIT 1";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("si", $referencia, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Valida datos numéricos del producto (costo, utilidad, precio)
     * @return array|null null si es válido, array con error si no
     */
    private function validarDatosProducto($datos) {
        if (isset($datos['prod_costo']) && floatval($datos['prod_costo']) < 0) {
            return ['success' => false, 'error' => 'El costo no puede ser negativo'];
        }
        $utilidad = isset($datos['prod_utilidad']) ? floatval($datos['prod_utilidad']) : null;
        if ($utilidad !== null && ($utilidad < 0 || $utilidad > 100)) {
            return ['success' => false, 'error' => 'La utilidad debe estar entre 0 y 100'];
        }
        if (isset($datos['prod_precio']) && floatval($datos['prod_precio']) < 0) {
            return ['success' => false, 'error' => 'El precio no puede ser negativo'];
        }
        return null;
    }
    
    /**
     * Crea un nuevo producto
     */
    private function crearProducto($datos) {
        // Validar campos requeridos
        if (empty(trim($datos['prod_referencia'] ?? '')) || empty(trim($datos['prod_nombre'] ?? ''))) {
            return [
                'success' => false,
                'error' => 'Los campos prod_referencia y prod_nombre son obligatorios'
            ];
        }
        
        $validacion = $this->validarDatosProducto($datos);
        if ($validacion !== null) {
            return $validacion;
        }
        
        // Verificar que no exista otra referencia igual
        $existente = $this->buscarProductoPorReferencia($datos['prod_referencia']);
        if ($existente) {
            return [
                'success' => false,
                'error' => 'Ya existe un producto con esa referencia'
            ];
        }
        
        // Construir query de inserción
        $campos = [];
        $valores = [];
        $tipos = '';
        $params = [];
        
        foreach ($datos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = '?';
            $tipos .= $this->obtenerTipoParametro($valor);
            $params[] = $valor;
        }
        
        $query = "INSERT INTO productos (" . implode(', ', $campos) . ") 
                  VALUES (" . implode(', ', $valores) . ")";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Error al preparar la consulta: ' . $this->conexionBdPrincipal->error
            ];
        }
        
        $stmt->bind_param($tipos, ...$params);
        
        if ($stmt->execute()) {
            $productoId = (int) $stmt->insert_id;
            $this->marcarProductoIntegradoOfima($productoId);

            // Crear registro en productos_bodegas por defecto
            $this->crearRegistroBodegaDefault($productoId);
            
            // Registrar sincronización
            $this->registrarSincronizacion(
                'productos',
                'ofima_orion',
                'CREATE',
                $productoId,
                $datos['prod_referencia'],
                $datos,
                ['success' => true],
                'exitoso',
                200,
                null,
                1
            );
            
            return [
                'success' => true,
                'producto_id' => $productoId,
                'operacion' => 'CREATE'
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Error al insertar producto: ' . $stmt->error
            ];
        }
    }
    
    /**
     * Actualiza un producto existente
     */
    private function actualizarProducto($productoId, $datos) {
        $validacion = $this->validarDatosProducto($datos);
        if ($validacion !== null) {
            return $validacion;
        }
        
        // Obtener datos actuales para comparar
        $queryActual = "SELECT * FROM productos WHERE prod_id = ? AND prod_id_empresa = ?";
        $stmtActual = $this->conexionBdPrincipal->prepare($queryActual);
        $stmtActual->bind_param("ii", $productoId, $this->idEmpresa);
        $stmtActual->execute();
        $productoActual = $stmtActual->get_result()->fetch_assoc();
        
        // Construir query de actualización
        $campos = [];
        $tipos = '';
        $params = [];
        
        foreach ($datos as $campo => $valor) {
            // No actualizar el ID ni la empresa
            if ($campo === 'prod_id' || $campo === 'prod_id_empresa') {
                continue;
            }
            
            $campos[] = "$campo = ?";
            $tipos .= $this->obtenerTipoParametro($valor);
            $params[] = $valor;
        }
        
        // Al actualizar desde Ofima → Orion siempre queda marcado como integrado
        if (!isset($datos['prod_integrado_ofima'])) {
            $campos[] = 'prod_integrado_ofima = 1';
        }

        // Agregar fecha de actualización
        $campos[] = "prod_ultima_actualizacion = NOW()";
        
        // Agregar parámetros finales (producto_id y empresa)
        $tipos .= 'ii';
        $params[] = $productoId;
        $params[] = $this->idEmpresa;
        
        $query = "UPDATE productos SET " . implode(', ', $campos) . " 
                  WHERE prod_id = ? AND prod_id_empresa = ?";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        if (!$stmt) {
            return [
                'success' => false,
                'error' => 'Error al preparar la consulta: ' . $this->conexionBdPrincipal->error
            ];
        }
        
        $stmt->bind_param($tipos, ...$params);
        
        if ($stmt->execute()) {
            $this->marcarProductoIntegradoOfima((int) $productoId);

            // Registrar cambio de precio si hubo
            if (isset($datos['prod_precio']) && $productoActual['prod_precio'] != $datos['prod_precio']) {
                $this->registrarCambioPrecio($productoId, $productoActual['prod_precio'], $datos['prod_precio']);
            }
            
            // Registrar sincronización
            $this->registrarSincronizacion(
                'productos',
                'ofima_orion',
                'UPDATE',
                $productoId,
                $datos['prod_referencia'] ?? '',
                $datos,
                ['success' => true],
                'exitoso',
                200,
                null,
                1
            );
            
            return [
                'success' => true,
                'producto_id' => $productoId,
                'operacion' => 'UPDATE'
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Error al actualizar producto: ' . $stmt->error
            ];
        }
    }

    /**
     * Marca prod_integrado_ofima = 1 para el producto de la empresa actual.
     */
    private function marcarProductoIntegradoOfima($productoId) {
        $productoId = (int) $productoId;
        if ($productoId <= 0) {
            return;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'UPDATE productos SET prod_integrado_ofima = 1 WHERE prod_id = ? AND prod_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return;
        }
        $stmt->bind_param('ii', $productoId, $this->idEmpresa);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Recibe y procesa un cliente desde Ofima (Ofima → Orion).
     * ciudad = código DIAN (ciu_cod_dian); se homologa a ciu_id y cli_zona = ciu_departamento.
     */
    public function recibirClienteDeOfima($datosOfima) {
        try {
            $datosOrion = $this->mapearCamposClienteDeOfima($datosOfima);
            $homologacion = $this->homologarCiudadCliente($datosOfima, $datosOrion);
            if ($homologacion !== null) {
                return $homologacion;
            }
            $clienteExistente = $this->buscarClientePorIdentificacion($datosOrion['cli_usuario']);
            if ($clienteExistente) {
                return $this->actualizarCliente($clienteExistente['cli_id'], $datosOrion, $datosOfima);
            }
            return $this->crearCliente($datosOrion, $datosOfima);
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Resuelve ciudad DIAN → cli_ciudad (ciu_id) y cli_zona (ciu_departamento).
     * Solo actúa si el payload trae "ciudad"; si no, no toca esos campos (evita borrar en UPDATE).
     * Mutates $datosOrion. Returns error array on failure, null on success.
     */
    private function homologarCiudadCliente(array $datosOfima, array &$datosOrion) {
        if (!array_key_exists('ciudad', $datosOfima)) {
            unset($datosOrion['cli_ciudad'], $datosOrion['cli_zona']);
            return null;
        }

        $codigoCiudad = trim((string) $datosOfima['ciudad']);
        if ($codigoCiudad === '') {
            $datosOrion['cli_ciudad'] = null;
            $datosOrion['cli_zona'] = '';
            return null;
        }

        $ciudad = $this->obtenerCiudadPorCodigoDian($codigoCiudad);
        if ($ciudad === null && ctype_digit($codigoCiudad)) {
            $ciudad = $this->obtenerCiudadPorId((int) $codigoCiudad);
        }
        if ($ciudad === null) {
            return [
                'success' => false,
                'error'   => 'No se encontró una ciudad con ciu_cod_dian ' . $codigoCiudad
            ];
        }

        $datosOrion['cli_ciudad'] = (int) $ciudad['ciu_id'];
        $datosOrion['cli_zona'] = (string) $ciudad['ciu_departamento'];
        return null;
    }

    /**
     * Mapea campos de Ofima a Orion para clientes
     */
    private function mapearCamposClienteDeOfima($datosOfima) {
        $mapeo = $this->obtenerMapeoCampos('clientes');
        $datosOrion = ['cli_id_empresa' => $this->idEmpresa];
        if (!empty($mapeo)) {
            $mapaInverso = [];
            foreach ($mapeo as $campo) {
                $mapaInverso[$campo['apim_campo_ofima']] = [
                    'campo_orion' => $campo['apim_campo_orion'],
                    'transformacion' => $campo['apim_tipo_transformacion']
                ];
            }
            foreach ($datosOfima as $campoOfima => $valor) {
                if (isset($mapaInverso[$campoOfima])) {
                    $campoOrion = $mapaInverso[$campoOfima]['campo_orion'];
                    $datosOrion[$campoOrion] = $this->aplicarTransformacion($valor, $mapaInverso[$campoOfima]['transformacion']);
                }
            }
        } else {
            $datosOrion = [
                'cli_usuario'    => $datosOfima['identificacion'] ?? '',
                'cli_nombre'     => $datosOfima['nombre'] ?? '',
                'cli_email'      => $datosOfima['email'] ?? '',
                'cli_telefono'   => $datosOfima['telefono'] ?? '',
                'cli_direccion'  => $datosOfima['direccion'] ?? '',
                'cli_ciudad'     => $datosOfima['ciudad'] ?? null,
                'cli_celular'    => $datosOfima['celular'] ?? '',
                'cli_referencia' => $datosOfima['referencia'] ?? null,
                'cli_id_empresa' => $this->idEmpresa
            ];
        }
        return $datosOrion;
    }

    private function buscarClientePorIdentificacion($identificacion) {
        $query = "SELECT * FROM clientes WHERE cli_usuario = ? AND cli_id_empresa = ? LIMIT 1";
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("si", $identificacion, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    /**
     * @param array $datos Datos ya mapeados a columnas Orion
     * @param array|null $datosRecibidos Payload original recibido de Ofima (para apis_datos_enviados)
     */
    private function crearCliente($datos, $datosRecibidos = null) {
        if (empty(trim($datos['cli_usuario'] ?? '')) || empty(trim($datos['cli_nombre'] ?? ''))) {
            return ['success' => false, 'error' => 'Los campos identificacion (cli_usuario) y nombre son obligatorios'];
        }
        if ($this->buscarClientePorIdentificacion($datos['cli_usuario'])) {
            return ['success' => false, 'error' => 'Ya existe un cliente con esa identificación (NIT/documento)'];
        }
        $cliClave = bin2hex(random_bytes(8));
        $campos = [
            'cli_nombre', 'cli_usuario', 'cli_email', 'cli_telefono', 'cli_direccion', 'cli_ciudad',
            'cli_celular', 'cli_referencia', 'cli_id_empresa', 'cli_categoria', 'cli_fecha_registro',
            'cli_nivel', 'cli_clave', 'cli_forma_creacion', 'cli_integrado_ofima', 'cli_zona', 'cli_pais'
        ];
        // cli_ciudad = ciu_id homologado (string para permitir NULL sin coercer a 0)
        $cliCiudad = isset($datos['cli_ciudad']) && $datos['cli_ciudad'] !== null && $datos['cli_ciudad'] !== ''
            ? (string) (int) $datos['cli_ciudad']
            : null;
        $cliZona = isset($datos['cli_zona']) ? (string) $datos['cli_zona'] : '';
        $valores = [
            $datos['cli_nombre'] ?? '',
            $datos['cli_usuario'] ?? '',
            $datos['cli_email'] ?? '',
            $datos['cli_telefono'] ?? '',
            $datos['cli_direccion'] ?? '',
            $cliCiudad,
            $datos['cli_celular'] ?? '',
            $datos['cli_referencia'] ?? null,
            $this->idEmpresa,
            1, // CLI_CATEGORIA_PROSPECTO
            date('Y-m-d H:i:s'),
            3, // nivel
            $cliClave,
            'API',
            1, // cli_integrado_ofima: llegó desde Ofima
            $cliZona,
            'Colombia'  // cli_pais
        ];
        $placeholders = implode(', ', array_fill(0, count($campos), '?'));
        $query = "INSERT INTO clientes (" . implode(', ', $campos) . ") VALUES ($placeholders)";
        $stmt = $this->conexionBdPrincipal->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'error' => 'Error al preparar la consulta: ' . $this->conexionBdPrincipal->error];
        }
        $tipos = 'ssssssssiisississ';
        $stmt->bind_param($tipos, ...$valores);
        if ($stmt->execute()) {
            $clienteId = (int) $stmt->insert_id;
            $this->marcarClienteIntegradoOfima($clienteId);
            $payloadLog = is_array($datosRecibidos) ? $datosRecibidos : $datos;
            $this->registrarSincronizacion('clientes', 'ofima_orion', 'CREATE', $clienteId, $datos['cli_usuario'], $payloadLog, ['success' => true], 'exitoso', 200, null, 1);
            return ['success' => true, 'cliente_id' => $clienteId, 'operacion' => 'CREATE'];
        }
        return ['success' => false, 'error' => 'Error al insertar cliente: ' . $stmt->error];
    }

    /**
     * @param array $datos Datos ya mapeados a columnas Orion
     * @param array|null $datosRecibidos Payload original recibido de Ofima (para apis_datos_enviados)
     */
    private function actualizarCliente($clienteId, $datos, $datosRecibidos = null) {
        $camposPermitidos = ['cli_nombre', 'cli_email', 'cli_telefono', 'cli_direccion', 'cli_ciudad', 'cli_zona', 'cli_celular', 'cli_referencia'];
        $sets = [];
        $tipos = '';
        $params = [];
        foreach ($camposPermitidos as $campo) {
            if (array_key_exists($campo, $datos)) {
                $sets[] = "$campo = ?";
                $tipos .= ($datos[$campo] === null || is_int($datos[$campo])) ? 'i' : 's';
                $params[] = $datos[$campo];
            }
        }
        // Al actualizar desde Ofima → Orion siempre queda marcado como integrado
        $sets[] = 'cli_integrado_ofima = 1';
        $sets[] = 'cli_ultima_modificacion = NOW()';
        $tipos .= 'ii';
        $params[] = $clienteId;
        $params[] = $this->idEmpresa;
        $query = 'UPDATE clientes SET ' . implode(', ', $sets) . ' WHERE cli_id = ? AND cli_id_empresa = ?';
        $stmt = $this->conexionBdPrincipal->prepare($query);
        if (!$stmt || !$stmt->bind_param($tipos, ...$params) || !$stmt->execute()) {
            return ['success' => false, 'error' => 'Error al actualizar cliente: ' . ($stmt ? $stmt->error : $this->conexionBdPrincipal->error)];
        }
        // Refuerzo: asegurar flag aunque el SET anterior se omita por esquema antiguo
        $this->marcarClienteIntegradoOfima((int) $clienteId);
        $payloadLog = is_array($datosRecibidos) ? $datosRecibidos : $datos;
        $this->registrarSincronizacion('clientes', 'ofima_orion', 'UPDATE', $clienteId, $datos['cli_usuario'] ?? '', $payloadLog, ['success' => true], 'exitoso', 200, null, 1);
        return ['success' => true, 'cliente_id' => $clienteId, 'operacion' => 'UPDATE'];
    }

    /**
     * Marca cli_integrado_ofima = 1 para el cliente de la empresa actual.
     */
    private function marcarClienteIntegradoOfima($clienteId) {
        $clienteId = (int) $clienteId;
        if ($clienteId <= 0) {
            return;
        }
        $stmt = $this->conexionBdPrincipal->prepare(
            'UPDATE clientes SET cli_integrado_ofima = 1 WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1'
        );
        if (!$stmt) {
            return;
        }
        $stmt->bind_param('ii', $clienteId, $this->idEmpresa);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Recibe y procesa inventario (existencias por producto y bodega) desde Ofima (Ofima → Orion).
     * Inserta o actualiza productos_bodegas y recalcula prod_existencias en productos.
     *
     * @param array $datos Un objeto con referencia o producto_id, bodega_referencia (bod_referencia), existencias; o { items: [ {...}, ... ] } para lote
     * @return array { success, procesados?, resultados?, error? }
     */
    public function recibirInventarioDeOfima($datos) {
        $items = isset($datos['items']) && is_array($datos['items']) ? $datos['items'] : [$datos];
        $resultados = [];

        foreach ($items as $item) {
            $referencia = isset($item['referencia']) ? trim((string) $item['referencia']) : '';
            $productoId = isset($item['producto_id']) ? (int) $item['producto_id'] : 0;
            $bodegaReferencia = isset($item['bodega_referencia']) ? trim((string) $item['bodega_referencia']) : '';
            $existencias = isset($item['existencias']) ? (float) $item['existencias'] : null;

            if ($existencias === null || $existencias < 0) {
                $this->registrarSincronizacion('inventario', 'ofima_orion', 'UPDATE', 0, $referencia ?: (string) $productoId, $item, ['success' => false], 'error', 400, 'Campo existencias obligatorio y debe ser >= 0', 1);
                return ['success' => false, 'error' => 'Cada ítem debe incluir existencias (número >= 0)'];
            }
            if ($bodegaReferencia === '') {
                $this->registrarSincronizacion('inventario', 'ofima_orion', 'UPDATE', 0, $referencia ?: (string) $productoId, $item, ['success' => false], 'error', 400, 'bodega_referencia obligatorio', 1);
                return ['success' => false, 'error' => 'Cada ítem debe incluir bodega_referencia (código bod_referencia)'];
            }
            if ($referencia === '' && $productoId <= 0) {
                $this->registrarSincronizacion('inventario', 'ofima_orion', 'UPDATE', 0, '', $item, ['success' => false], 'error', 400, 'Se requiere referencia o producto_id', 1);
                return ['success' => false, 'error' => 'Cada ítem debe incluir referencia (código del producto) o producto_id'];
            }

            $producto = null;
            if ($productoId > 0) {
                $producto = $this->obtenerProductoPorId($productoId);
            }
            if (!$producto && $referencia !== '') {
                $producto = $this->buscarProductoPorReferencia($referencia);
            }
            if (!$producto) {
                $ref = $referencia ?: (string) $productoId;
                $this->registrarSincronizacion('inventario', 'ofima_orion', 'UPDATE', 0, $ref, $item, ['success' => false], 'error', 404, 'Producto no encontrado', 1);
                return ['success' => false, 'error' => 'Producto no encontrado (referencia o producto_id: ' . $ref . ')'];
            }

            $prodId = (int) $producto['prod_id'];
            $bodega = $this->buscarBodegaPorReferencia($bodegaReferencia);
            if (!$bodega) {
                $this->registrarSincronizacion('inventario', 'ofima_orion', 'UPDATE', $prodId, $producto['prod_referencia'] ?? '', $item, ['success' => false], 'error', 400, 'Bodega no válida para la empresa', 1);
                return ['success' => false, 'error' => 'Bodega no encontrada para la referencia ' . $bodegaReferencia];
            }
            $bodegaId = (int) $bodega['bod_id'];

            $existenciasInt = (int) round($existencias);
            $operacion = $this->upsertProductoBodega($prodId, $bodegaId, $existenciasInt);
            Producto::sincronizarExistenciasConBodegas($prodId, $this->conexionBdPrincipal);

            $this->registrarSincronizacion('inventario', 'ofima_orion', $operacion === 'CREATE' ? 'CREATE' : 'UPDATE', $prodId, $producto['prod_referencia'] ?? '', $item, ['success' => true, 'existencias' => $existenciasInt], 'exitoso', 200, null, 1);
            $resultados[] = ['producto_id' => $prodId, 'bodega_referencia' => $bodegaReferencia, 'bodega_id' => $bodegaId, 'existencias' => $existenciasInt, 'operacion' => $operacion];
        }

        return ['success' => true, 'procesados' => count($resultados), 'resultados' => $resultados];
    }

    /**
     * Obtiene un producto por ID si pertenece a la empresa
     */
    private function obtenerProductoPorId($productoId) {
        $query = "SELECT * FROM productos WHERE prod_id = ? AND prod_id_empresa = ? LIMIT 1";
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("ii", $productoId, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    /**
     * Recibe y procesa una bodega desde Ofima (Ofima → Orion).
     * Payload: referencia (código en Ofima), nombre, ciudad (código DIAN ciu_cod_dian, opcional).
     * Requiere tabla bodegas con columna bod_referencia (ver sql/api_integracion_tablas.sql).
     *
     * @param array $datos { referencia, nombre, ciudad?, id_empresa? }
     * @return array { success, bodega_id?, operacion? }
     */
    public function recibirBodegaDeOfima($datos) {
        $referencia = isset($datos['referencia']) ? trim((string) $datos['referencia']) : '';
        $nombre = isset($datos['nombre']) ? trim((string) $datos['nombre']) : '';
        $codigoCiudad = isset($datos['ciudad']) ? trim((string) $datos['ciudad']) : '';
        $ciudad = null;
        if ($codigoCiudad !== '') {
            $ciudad = $this->buscarCiudadIdPorCodigoDian($codigoCiudad);
            if ($ciudad === null) {
                $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', 0, $referencia, $datos, ['success' => false], 'error', 400, 'No se encontró la ciudad con el código DIAN indicado', 1);
                return ['success' => false, 'error' => 'No se encontró una ciudad con ciu_cod_dian ' . $codigoCiudad];
            }
        }

        if ($nombre === '') {
            $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', 0, $referencia, $datos, ['success' => false], 'error', 400, 'El campo nombre es obligatorio', 1);
            return ['success' => false, 'error' => 'El campo nombre es obligatorio'];
        }
        if ($referencia === '') {
            $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', 0, '', $datos, ['success' => false], 'error', 400, 'El campo referencia es obligatorio para sincronizar bodegas', 1);
            return ['success' => false, 'error' => 'El campo referencia (código de bodega en Ofima) es obligatorio'];
        }

        $existe = $this->buscarBodegaPorReferencia($referencia);
        if ($existe) {
            $bodId = (int) $existe['bod_id'];
            $stmt = $this->conexionBdPrincipal->prepare("UPDATE bodegas SET bod_nombre = ?, bod_ciudad = ?, bod_referencia = ?, bod_habilitada = 1 WHERE bod_id = ? AND bod_id_empresa = ?");
            if (!$stmt) {
                $stmt = $this->conexionBdPrincipal->prepare("UPDATE bodegas SET bod_nombre = ?, bod_ciudad = ?, bod_referencia = ? WHERE bod_id = ? AND bod_id_empresa = ?");
            }
            if (!$stmt) {
                $stmt = $this->conexionBdPrincipal->prepare("UPDATE bodegas SET bod_nombre = ?, bod_ciudad = ? WHERE bod_id = ? AND bod_id_empresa = ?");
                if (!$stmt) {
                    $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', $bodId, $referencia, $datos, ['success' => false], 'error', 500, 'Error al actualizar bodega', 1);
                    return ['success' => false, 'error' => 'Error al preparar actualización de bodega'];
                }
                $stmt->bind_param("siii", $nombre, $ciudad, $bodId, $this->idEmpresa);
            } else {
                $stmt->bind_param("sisii", $nombre, $ciudad, $referencia, $bodId, $this->idEmpresa);
            }
            if (!$stmt->execute()) {
                $errorSql = $stmt->error;
                $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', $bodId, $referencia, $datos, ['success' => false], 'error', 500, $errorSql, 1);
                return ['success' => false, 'error' => 'No se pudo actualizar la bodega: ' . $errorSql];
            }
            $this->registrarSincronizacion('bodegas', 'ofima_orion', 'UPDATE', $bodId, $referencia, $datos, ['success' => true], 'exitoso', 200, null, 1);
            return ['success' => true, 'bodega_id' => $bodId, 'operacion' => 'UPDATE'];
        }

        $stmt = $this->conexionBdPrincipal->prepare("INSERT INTO bodegas (bod_referencia, bod_nombre, bod_ciudad, bod_id_empresa, bod_habilitada) VALUES (?, ?, ?, ?, 1)");
        if (!$stmt) {
            $stmt = $this->conexionBdPrincipal->prepare("INSERT INTO bodegas (bod_referencia, bod_nombre, bod_ciudad, bod_id_empresa) VALUES (?, ?, ?, ?)");
        }
        if (!$stmt) {
            $stmt = $this->conexionBdPrincipal->prepare("INSERT INTO bodegas (bod_nombre, bod_ciudad, bod_id_empresa) VALUES (?, ?, ?)");
            if (!$stmt) {
                $this->registrarSincronizacion('bodegas', 'ofima_orion', 'CREATE', 0, $referencia, $datos, ['success' => false], 'error', 500, 'Error al insertar bodega', 1);
                return ['success' => false, 'error' => 'Error al preparar inserción de bodega'];
            }
            $stmt->bind_param("sii", $nombre, $ciudad, $this->idEmpresa);
        } else {
            $stmt->bind_param("ssii", $referencia, $nombre, $ciudad, $this->idEmpresa);
        }
        if (!$stmt->execute()) {
            $errorSql = $stmt->error;
            $this->registrarSincronizacion('bodegas', 'ofima_orion', 'CREATE', 0, $referencia, $datos, ['success' => false], 'error', 500, $errorSql, 1);
            return ['success' => false, 'error' => 'No se pudo crear la bodega: ' . $errorSql];
        }
        $bodId = $this->conexionBdPrincipal->insert_id;
        $this->registrarSincronizacion('bodegas', 'ofima_orion', 'CREATE', $bodId, $referencia, $datos, ['success' => true], 'exitoso', 200, null, 1);
        return ['success' => true, 'bodega_id' => $bodId, 'operacion' => 'CREATE'];
    }

    /**
     * Resuelve ciu_id a partir del código DIAN (ciu_cod_dian) enviado por Ofima.
     *
     * @param string $codigoDian
     * @return int|null
     */
    private function buscarCiudadIdPorCodigoDian($codigoDian) {
        $ciudad = $this->obtenerCiudadPorCodigoDian($codigoDian);
        return $ciudad ? (int) $ciudad['ciu_id'] : null;
    }

    /**
     * @return array{ciu_id:int,ciu_departamento:int|string}|null
     */
    private function obtenerCiudadPorCodigoDian($codigoDian) {
        $codigoDian = trim((string) $codigoDian);
        if ($codigoDian === '' || !defined('BDADMIN')) {
            return null;
        }

        $codigoNormalizado = ctype_digit($codigoDian) ? str_pad($codigoDian, 5, '0', STR_PAD_LEFT) : $codigoDian;
        $sql = 'SELECT ciu_id, ciu_departamento FROM ' . BDADMIN . '.localidad_ciudades WHERE ciu_cod_dian = ? OR ciu_cod_dian = ? LIMIT 1';
        $stmt = $this->conexionBdPrincipal->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('ss', $codigoDian, $codigoNormalizado);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row || empty($row['ciu_id'])) {
            return null;
        }
        return [
            'ciu_id' => (int) $row['ciu_id'],
            'ciu_departamento' => $row['ciu_departamento'],
        ];
    }

    /**
     * Fallback si Ofima envía el ciu_id interno de Orion en lugar del código DIAN.
     *
     * @return array{ciu_id:int,ciu_departamento:int|string}|null
     */
    private function obtenerCiudadPorId($ciuId) {
        $ciuId = (int) $ciuId;
        if ($ciuId <= 0 || !defined('BDADMIN')) {
            return null;
        }
        $sql = 'SELECT ciu_id, ciu_departamento FROM ' . BDADMIN . '.localidad_ciudades WHERE ciu_id = ? LIMIT 1';
        $stmt = $this->conexionBdPrincipal->prepare($sql);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $ciuId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row || empty($row['ciu_id'])) {
            return null;
        }
        return [
            'ciu_id' => (int) $row['ciu_id'],
            'ciu_departamento' => $row['ciu_departamento'],
        ];
    }

    private function buscarBodegaPorReferencia($referencia) {
        $stmt = $this->conexionBdPrincipal->prepare("SELECT * FROM bodegas WHERE bod_referencia = ? AND bod_id_empresa = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("si", $referencia, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    /**
     * Inserta o actualiza existencias en productos_bodegas. Retorna 'CREATE' o 'UPDATE'.
     * Comprueba por (prodb_producto, prodb_bodega) para no depender de UNIQUE en la tabla.
     */
    private function upsertProductoBodega($productoId, $bodegaId, $existencias) {
        $querySelect = "SELECT prodb_id FROM productos_bodegas 
                        WHERE prodb_producto = ? AND prodb_bodega = ? LIMIT 1";
        $stmt = $this->conexionBdPrincipal->prepare($querySelect);
        $stmt->bind_param("ii", $productoId, $bodegaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existe = $result->fetch_assoc();

        if ($existe) {
            $query = "UPDATE productos_bodegas 
                      SET prodb_existencias = ?, prodb_fecha_actualizacion = NOW(), prodb_usuario_actualizacion = 0 
                      WHERE prodb_id = ?";
            $stmt = $this->conexionBdPrincipal->prepare($query);
            $stmt->bind_param("ii", $existencias, $existe['prodb_id']);
            $stmt->execute();
            return 'UPDATE';
        }

        $query = "INSERT INTO productos_bodegas 
                  (prodb_producto, prodb_bodega, prodb_existencias, prodb_fecha_actualizacion, prodb_usuario_actualizacion) 
                  VALUES (?, ?, ?, NOW(), 0)";
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("iii", $productoId, $bodegaId, $existencias);
        $stmt->execute();
        return 'CREATE';
    }
    
    /**
     * Crea registro en productos_bodegas por defecto
     */
    private function crearRegistroBodegaDefault($productoId) {
        $query = "INSERT INTO productos_bodegas 
                  (prodb_producto, prodb_bodega, prodb_existencias, prodb_fecha_actualizacion) 
                  VALUES (?, 1, 0, NOW())
                  ON DUPLICATE KEY UPDATE prodb_fecha_actualizacion = NOW()";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("i", $productoId);
        $stmt->execute();
    }
    
    /**
     * Registra cambio de precio en el historial
     */
    private function registrarCambioPrecio($productoId, $precioAnterior, $precioNuevo) {
        $query = "INSERT INTO productos_historial_precios 
                  (php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa) 
                  VALUES (?, ?, ?, 0, 4)";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $stmt->bind_param("idd", $productoId, $precioAnterior, $precioNuevo);
        $stmt->execute();
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
     * Obtiene el tipo de parámetro para bind_param
     */
    private function obtenerTipoParametro($valor) {
        if (is_int($valor)) {
            return 'i';
        } elseif (is_float($valor) || is_double($valor)) {
            return 'd';
        } else {
            return 's';
        }
    }
    
    /**
     * Registra una sincronización en la base de datos (Ofima → Orion)
     * Incluye apis_mensaje_error y apis_intentos para homogeneidad con ApiOfimaClient.
     */
    private function registrarSincronizacion($modulo, $direccion, $tipoOperacion, $idRegistro, $referencia, $datosEnviados, $respuesta, $estado, $codigoRespuesta = null, $mensajeError = null, $intentos = 1) {
        $query = "INSERT INTO api_sincronizaciones (
            apis_modulo, apis_direccion, apis_tipo_operacion, apis_id_registro, 
            apis_referencia, apis_datos_enviados, apis_respuesta, apis_estado, 
            apis_codigo_respuesta, apis_mensaje_error, apis_intentos, apis_id_empresa, apis_fecha_procesado
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conexionBdPrincipal->prepare($query);
        $datosEnviadosJson = json_encode($datosEnviados);
        $respuestaJson = json_encode($respuesta);
        
        $stmt->bind_param("sssissssisii", 
            $modulo, $direccion, $tipoOperacion, $idRegistro, $referencia,
            $datosEnviadosJson, $respuestaJson, $estado, $codigoRespuesta, $mensajeError, $intentos, $this->idEmpresa
        );
        
        $stmt->execute();
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

