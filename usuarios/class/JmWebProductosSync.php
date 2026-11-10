<?php
/**
 * Sincronización Orion → tienda web JM (gw_productos).
 * Supercategoría TIENDA WEB (sup_slug=tienda-web): create-only, una sola (LIMIT 1).
 * Subcategoría CRM (catep_especial=0 bajo esa supercat): create-only si no hay relación.
 * Las marcas JM se relacionan con Orion vía marcas.mar_id_crm.
 */
class JmWebProductosSync
{
    public const NOMBRE_TIENDA_WEB = 'TIENDA WEB';
    public const SLUG_TIENDA_WEB = 'tienda-web';
    public const NOMBRE_SUBCAT_CRM = 'PRODUCTOS AGREGADOS DEL CRM';
    public const MAR_NOMBRE_MAX = 45;

    /**
     * Aliases de nombre normalizado JM → Orion (y viceversa vía misma clave canónica).
     * Clave y valores ya normalizados.
     *
     * @return array<string, string> aliasNorm => canonNorm
     */
    public static function aliasesMarcaNormalizados(): array
    {
        return [
            'IDS GEORADAR' => 'IDS',
            'IDS' => 'IDS',
            'DJI AGRICULTURE' => 'DJI AGRICULTURA',
            'DJI AGRICULTURA' => 'DJI AGRICULTURA',
            'DJI ENTERPRISE' => 'DJI ENTERPRISE',
            'E SURVEY' => 'ESURVEY',
            'ESURVEY' => 'ESURVEY',
            'GEOCUE' => 'GEOCUE',
            'GEOMAX' => 'GEOMAX',
            'HEXAGON' => 'HEXAGON',
            'TRIMBLE' => 'TRIMBLE',
        ];
    }

    /**
     * Normaliza nombre de marca para comparación (sin acentos, mayúsculas).
     */
    public static function normalizarNombreMarca(string $texto): string
    {
        $texto = mb_strtoupper(trim($texto), 'UTF-8');
        $map = [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
        ];
        $texto = strtr($texto, $map);
        if (function_exists('iconv')) {
            $conv = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
            if ($conv !== false) {
                $texto = strtoupper($conv);
            }
        }
        $texto = preg_replace('/[^A-Z0-9]+/', ' ', $texto);
        $texto = preg_replace('/\s+/', ' ', (string) $texto);
        $texto = trim((string) $texto);

        $aliases = self::aliasesMarcaNormalizados();
        if (isset($aliases[$texto])) {
            return $aliases[$texto];
        }

        return $texto;
    }

    /**
     * Resuelve o crea la marca JM a partir de la marca Orion del producto.
     *
     * @return int mar_id en JM
     * @throws RuntimeException
     */
    public static function asegurarMarcaDesdeOrion(mysqli $jm, int $marIdOrion, string $marNombreOrion): int
    {
        $marIdOrion = (int) $marIdOrion;
        $marNombreOrion = trim($marNombreOrion);
        if ($marIdOrion <= 0) {
            throw new RuntimeException('JM Web: mar_id Orion inválido para sincronizar marca');
        }
        if ($marNombreOrion === '') {
            throw new RuntimeException('JM Web: nombre de marca Orion vacío');
        }

        $porCrm = self::obtenerMarcaJmPorCrmId($jm, $marIdOrion);
        if ($porCrm > 0) {
            return $porCrm;
        }

        $porNombre = self::obtenerMarcaJmPorNombreNormalizado($jm, $marNombreOrion);
        if ($porNombre > 0) {
            self::vincularMarcaJmConCrmSiLibre($jm, $porNombre, $marIdOrion);
            return $porNombre;
        }

        return self::crearMarcaJm($jm, $marIdOrion, $marNombreOrion);
    }

    private static function obtenerMarcaJmPorCrmId(mysqli $jm, int $marIdOrion): int
    {
        $stmt = $jm->prepare(
            'SELECT mar_id FROM marcas WHERE mar_id_crm = ? LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare marca por CRM falló: ' . $jm->error);
        }
        $stmt->bind_param('i', $marIdOrion);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['mar_id'] ?? 0);
    }

    private static function obtenerMarcaJmPorNombreNormalizado(mysqli $jm, string $nombreOrion): int
    {
        $canon = self::normalizarNombreMarca($nombreOrion);
        if ($canon === '') {
            return 0;
        }

        $res = $jm->query('SELECT mar_id, mar_nombre FROM marcas');
        if (!$res) {
            throw new RuntimeException('JM Web: listar marcas falló: ' . $jm->error);
        }
        while ($row = $res->fetch_assoc()) {
            if (self::normalizarNombreMarca((string) ($row['mar_nombre'] ?? '')) === $canon) {
                return (int) $row['mar_id'];
            }
        }
        return 0;
    }

    private static function vincularMarcaJmConCrmSiLibre(mysqli $jm, int $marIdJm, int $marIdOrion): void
    {
        $stmt = $jm->prepare(
            'UPDATE marcas SET mar_id_crm = ? WHERE mar_id = ? AND mar_id_crm IS NULL'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare vincular marca falló: ' . $jm->error);
        }
        $stmt->bind_param('ii', $marIdOrion, $marIdJm);
        if (!$stmt->execute()) {
            // Conflicto de unique: otra fila ya tiene ese mar_id_crm; no bloquear.
            $stmt->close();
            return;
        }
        $stmt->close();
    }

    private static function crearMarcaJm(mysqli $jm, int $marIdOrion, string $marNombreOrion): int
    {
        $existente = self::obtenerMarcaJmPorCrmId($jm, $marIdOrion);
        if ($existente > 0) {
            return $existente;
        }

        $nombre = mb_substr(trim($marNombreOrion), 0, self::MAR_NOMBRE_MAX, 'UTF-8');
        $stmt = $jm->prepare(
            'INSERT INTO marcas (mar_nombre, mar_icono, mar_id_crm) VALUES (?, NULL, ?)'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare INSERT marca falló: ' . $jm->error);
        }
        $stmt->bind_param('si', $nombre, $marIdOrion);
        if (!$stmt->execute()) {
            $stmt->close();
            $reintento = self::obtenerMarcaJmPorCrmId($jm, $marIdOrion);
            if ($reintento > 0) {
                return $reintento;
            }
            throw new RuntimeException('JM Web: INSERT marca falló: ' . $jm->error);
        }
        $nuevoId = (int) $stmt->insert_id;
        $stmt->close();
        if ($nuevoId <= 0) {
            $nuevoId = self::obtenerMarcaJmPorCrmId($jm, $marIdOrion);
        }
        if ($nuevoId <= 0) {
            throw new RuntimeException('JM Web: no se obtuvo mar_id tras crear marca');
        }
        return $nuevoId;
    }

    /**
     * Asegura supercategoría TIENDA WEB (por sup_slug) + subcategoría CRM (catep_especial=0).
     * Solo INSERT si faltan; nunca UPDATE de esas filas.
     *
     * @return int catep_id de la subcategoría PRODUCTOS AGREGADOS DEL CRM
     * @throws RuntimeException si no se puede obtener/crear
     */
    public static function asegurarCategoriaTiendaWeb(mysqli $jm): int
    {
        $supId = self::obtenerSupercategoriaTiendaWebId($jm);
        if ($supId <= 0) {
            $supId = self::crearSupercategoriaTiendaWeb($jm);
        }

        $catepId = self::obtenerSubcategoriaCrmId($jm, $supId);
        if ($catepId <= 0) {
            $catepId = self::crearSubcategoriaCrm($jm, $supId);
        }

        if ($catepId <= 0) {
            throw new RuntimeException('No se pudo asegurar la categoría TIENDA WEB / subcategoría CRM en JM Web');
        }

        return $catepId;
    }

    /**
     * Busca la única supercategoría con slug tienda-web (LIMIT 1).
     */
    private static function obtenerSupercategoriaTiendaWebId(mysqli $jm): int
    {
        $stmt = $jm->prepare(
            'SELECT sup_id
             FROM gw_supcategorias
             WHERE sup_slug = ?
             ORDER BY sup_id ASC
             LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare supercategoría falló: ' . $jm->error);
        }

        $slug = self::SLUG_TIENDA_WEB;
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['sup_id'] ?? 0);
    }

    private static function crearSupercategoriaTiendaWeb(mysqli $jm): int
    {
        // Releer por carrera: otro proceso pudo crearla entre SELECT e INSERT.
        $existente = self::obtenerSupercategoriaTiendaWebId($jm);
        if ($existente > 0) {
            return $existente;
        }

        $stmt = $jm->prepare(
            "INSERT INTO gw_supcategorias
                (sup_nombre, sup_slug, sup_descripcion, sup_mostrar_texto_hero, sup_mostrar_botones_hero,
                 sup_hero_media, sup_banner, sup_video_externo, sup_visible_web, sup_especial)
             VALUES (?, ?, '', 1, 1, 'video', NULL, NULL, 1, 1)"
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare INSERT supercategoría falló: ' . $jm->error);
        }

        $nombre = self::NOMBRE_TIENDA_WEB;
        $slug = self::SLUG_TIENDA_WEB;
        $stmt->bind_param('ss', $nombre, $slug);
        if (!$stmt->execute()) {
            $stmt->close();
            // Posible duplicado por slug único: reconsultar
            $reintento = self::obtenerSupercategoriaTiendaWebId($jm);
            if ($reintento > 0) {
                return $reintento;
            }
            throw new RuntimeException('JM Web: INSERT supercategoría falló: ' . $jm->error);
        }

        $nuevoId = (int) $stmt->insert_id;
        $stmt->close();

        if ($nuevoId <= 0) {
            $nuevoId = self::obtenerSupercategoriaTiendaWebId($jm);
        }

        return $nuevoId;
    }

    /**
     * Relación CRM bajo la supercategoría: catep_id_supercat + catep_especial = 0 (LIMIT 1).
     */
    private static function obtenerSubcategoriaCrmId(mysqli $jm, int $supId): int
    {
        $supId = (int) $supId;
        if ($supId <= 0) {
            return 0;
        }

        $stmt = $jm->prepare(
            'SELECT catep_id
             FROM gw_prod_categorias
             WHERE catep_id_supercat = ?
               AND catep_especial = 0
             ORDER BY catep_id ASC
             LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare subcategoría CRM falló: ' . $jm->error);
        }

        $stmt->bind_param('i', $supId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['catep_id'] ?? 0);
    }

    private static function crearSubcategoriaCrm(mysqli $jm, int $supId): int
    {
        $existente = self::obtenerSubcategoriaCrmId($jm, $supId);
        if ($existente > 0) {
            return $existente;
        }

        $stmt = $jm->prepare(
            "INSERT INTO gw_prod_categorias
                (catep_nombre, catep_descripcion, catep_imagen, catep_posicion,
                 catep_id_supercat, catep_especial, catep_banner, catep_video_externo)
             VALUES (?, '', NULL, 0, ?, 0, NULL, NULL)"
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare INSERT subcategoría CRM falló: ' . $jm->error);
        }

        $nombre = self::NOMBRE_SUBCAT_CRM;
        $stmt->bind_param('si', $nombre, $supId);
        if (!$stmt->execute()) {
            $stmt->close();
            $reintento = self::obtenerSubcategoriaCrmId($jm, $supId);
            if ($reintento > 0) {
                return $reintento;
            }
            throw new RuntimeException('JM Web: INSERT subcategoría CRM falló: ' . $jm->error);
        }

        $nuevoId = (int) $stmt->insert_id;
        $stmt->close();

        if ($nuevoId <= 0) {
            $nuevoId = self::obtenerSubcategoriaCrmId($jm, $supId);
        }

        return $nuevoId;
    }

    /**
     * Upsert / oculta producto en gw_productos desde un registro Orion.
     *
     * Espera en $productoOrion al menos: prod_id, prod_nombre, prod_referencia,
     * prod_precio, prod_descuento_web (opc), prod_descripcion_corta/larga (opc),
     * prod_marca (id Orion), mar_nombre (nombre Orion).
     *
     * @return array{success:bool, action:string, prop_id:?int, message:?string}
     */
    public static function sincronizarDesdeOrion(mysqli $jm, array $productoOrion, bool $visibleWeb): array
    {
        $prodIdCrm = (int) ($productoOrion['prod_id'] ?? 0);
        if ($prodIdCrm <= 0) {
            return [
                'success' => false,
                'action' => 'error',
                'prop_id' => null,
                'message' => 'prod_id Orion inválido',
            ];
        }

        // En todo cambio SI/NO: asegurar supercategoría (sup_slug=tienda-web) y subcategoría CRM.
        $catepId = self::asegurarCategoriaTiendaWeb($jm);

        $existenteId = self::obtenerProductoJmPorCrmId($jm, $prodIdCrm);
        $nombreCrm = mb_substr(trim((string) ($productoOrion['prod_nombre'] ?? '')), 0, 255, 'UTF-8');
        $precio = self::calcularPrecioWebOrion($productoOrion);

        // SI→NO: solo precio web, prod_nombre_crm y flags; no toca prod_nombre (CMS).
        if (!$visibleWeb) {
            if ($existenteId <= 0) {
                return [
                    'success' => true,
                    'action' => 'noop',
                    'prop_id' => null,
                    'message' => 'Producto no existe en JM; nada que ocultar',
                ];
            }
            self::ocultarProductoJm($jm, $existenteId, $precio, $nombreCrm);
            return [
                'success' => true,
                'action' => 'hide',
                'prop_id' => $existenteId,
                'message' => null,
            ];
        }

        // Ya existe + CRM SI: solo precio web, prod_nombre_crm y flag CRM; no toca prod_nombre.
        if ($existenteId > 0) {
            self::actualizarProductoJm($jm, $existenteId, $precio, $nombreCrm);
            return [
                'success' => true,
                'action' => 'update',
                'prop_id' => $existenteId,
                'message' => null,
            ];
        }

        // Alta desde cero
        $marIdOrion = (int) ($productoOrion['prod_marca'] ?? 0);
        $marNombre = trim((string) ($productoOrion['mar_nombre'] ?? ''));
        if ($marIdOrion <= 0 || $marNombre === '') {
            return [
                'success' => false,
                'action' => 'error',
                'prop_id' => null,
                'message' => 'El producto Orion no tiene marca válida para sincronizar',
            ];
        }
        $marIdJm = self::asegurarMarcaDesdeOrion($jm, $marIdOrion, $marNombre);

        $nombre = mb_substr(trim((string) ($productoOrion['prod_nombre'] ?? '')), 0, 150, 'UTF-8');
        if ($nombre === '') {
            return [
                'success' => false,
                'action' => 'error',
                'prop_id' => null,
                'message' => 'El producto Orion no tiene nombre',
            ];
        }

        $referencia = mb_substr(trim((string) ($productoOrion['prod_referencia'] ?? '')), 0, 45, 'UTF-8');
        $descripcion = (string) (
            $productoOrion['prod_descripcion_larga']
            ?? $productoOrion['prod_descripcion_corta']
            ?? $productoOrion['prod_descripcion']
            ?? ''
        );

        $slug = self::generarSlugUnico($jm, $nombre, $referencia, $prodIdCrm);
        $propId = self::insertarProductoJm(
            $jm,
            $prodIdCrm,
            $nombre,
            $slug,
            $referencia,
            $precio,
            $descripcion,
            $catepId,
            $marIdJm
        );

        return [
            'success' => true,
            'action' => 'insert',
            'prop_id' => $propId,
            'message' => null,
        ];
    }

    /**
     * Precio lista menos descuento web (si aplica).
     */
    public static function calcularPrecioWebOrion(array $productoOrion): string
    {
        $precioLista = (float) ($productoOrion['prod_precio'] ?? 0);
        $dctoWeb = (float) ($productoOrion['prod_descuento_web'] ?? 0);
        if ($dctoWeb > 0 && $precioLista > 0) {
            $precioLista = $precioLista - ($precioLista * ($dctoWeb / 100));
        }
        if ($precioLista < 0) {
            $precioLista = 0;
        }
        // Sin decimales forzados: como varchar en JM
        if (abs($precioLista - round($precioLista)) < 0.00001) {
            return (string) (int) round($precioLista);
        }
        return rtrim(rtrim(number_format($precioLista, 2, '.', ''), '0'), '.');
    }

    private static function obtenerProductoJmPorCrmId(mysqli $jm, int $prodIdCrm): int
    {
        $stmt = $jm->prepare(
            'SELECT prop_id FROM gw_productos WHERE prod_id_crm = ? LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare producto por CRM falló: ' . $jm->error);
        }
        $stmt->bind_param('i', $prodIdCrm);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int) ($row['prop_id'] ?? 0);
    }

    private static function ocultarProductoJm(
        mysqli $jm,
        int $propId,
        string $precio,
        string $nombreCrm
    ): void {
        // CRM marca NO: precio web + nombre CRM; fuerza oculto CMS/CRM; no toca prod_nombre.
        $stmt = $jm->prepare(
            'UPDATE gw_productos
             SET prod_precio = ?,
                 prod_nombre_crm = ?,
                 prod_visible_web = 0,
                 prod_visible_web_crm = 0
             WHERE prop_id = ?
             LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare ocultar producto falló: ' . $jm->error);
        }
        $stmt->bind_param('ssi', $precio, $nombreCrm, $propId);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('JM Web: ocultar producto falló: ' . $jm->error);
        }
        $stmt->close();
    }

    private static function actualizarProductoJm(
        mysqli $jm,
        int $propId,
        string $precio,
        string $nombreCrm
    ): void {
        // CRM marca SI: solo precio web + prod_nombre_crm + flag CRM; no toca prod_nombre ni prod_visible_web.
        $stmt = $jm->prepare(
            'UPDATE gw_productos
             SET prod_precio = ?,
                 prod_nombre_crm = ?,
                 prod_visible_web_crm = 1
             WHERE prop_id = ?
             LIMIT 1'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare UPDATE producto falló: ' . $jm->error);
        }
        $stmt->bind_param('ssi', $precio, $nombreCrm, $propId);
        if (!$stmt->execute()) {
            $stmt->close();
            throw new RuntimeException('JM Web: UPDATE producto falló: ' . $jm->error);
        }
        $stmt->close();
    }

    private static function insertarProductoJm(
        mysqli $jm,
        int $prodIdCrm,
        string $nombre,
        string $slug,
        string $referencia,
        string $precio,
        string $descripcion,
        int $catepId,
        int $marIdJm
    ): int {
        // Alta desde CRM: CMS oculto (0), intención CRM visible (1), hero apagado.
        $stmt = $jm->prepare(
            'INSERT INTO gw_productos
                (prod_nombre, prod_slug, prod_categoria, prod_marca, prod_img_ppal,
                 prod_descripcion, prod_descripcion_comercial, prod_video, prod_hero_video,
                 prod_hero_imagen, prod_hero_titulo, prod_hero_subtitulo, prod_hero_cta_texto, prod_hero_cta_url,
                 prod_palabras_claves, prod_ficha, prod_posicion, prod_destacado, prod_venta,
                 prod_precio, prod_es_alto_precio, prod_id_crm, prod_nombre_crm,
                 prod_especificaciones, prod_caracteristicas, prod_referencia,
                 prod_mostrar_ficha, prod_mostrar_hero, prod_visible_web, prod_visible_web_crm)
             VALUES
                (?, ?, ?, ?, NULL,
                 ?, NULL, NULL, NULL,
                 NULL, NULL, NULL, NULL, NULL,
                 NULL, NULL, 0, 0, 0,
                 ?, 0, ?, ?,
                 NULL, NULL, ?,
                 1, 0, 0, 1)'
        );
        if (!$stmt) {
            throw new RuntimeException('JM Web: prepare INSERT producto falló: ' . $jm->error);
        }
        $stmt->bind_param(
            'ssiississ',
            $nombre,
            $slug,
            $catepId,
            $marIdJm,
            $descripcion,
            $precio,
            $prodIdCrm,
            $nombre,
            $referencia
        );
        if (!$stmt->execute()) {
            $stmt->close();
            // Carrera: otro insertó el mismo prod_id_crm
            $existente = self::obtenerProductoJmPorCrmId($jm, $prodIdCrm);
            if ($existente > 0) {
                self::actualizarProductoJm($jm, $existente, $precio, $nombre);
                return $existente;
            }
            throw new RuntimeException('JM Web: INSERT producto falló: ' . $jm->error);
        }
        $propId = (int) $stmt->insert_id;
        $stmt->close();
        if ($propId <= 0) {
            $propId = self::obtenerProductoJmPorCrmId($jm, $prodIdCrm);
        }
        if ($propId <= 0) {
            throw new RuntimeException('JM Web: no se obtuvo prop_id tras INSERT');
        }
        return $propId;
    }

    private static function generarSlugUnico(
        mysqli $jm,
        string $nombre,
        string $referencia,
        int $prodIdCrm
    ): string {
        $base = self::slugificar($nombre);
        if ($base === '') {
            $base = self::slugificar($referencia);
        }
        if ($base === '') {
            $base = 'producto-' . $prodIdCrm;
        }

        $slug = $base;
        $i = 0;
        while (self::slugExiste($jm, $slug)) {
            $i++;
            $slug = $base . '-' . $prodIdCrm . ($i > 1 ? '-' . $i : '');
            if ($i > 50) {
                $slug = $base . '-' . $prodIdCrm . '-' . uniqid();
                break;
            }
        }
        return mb_substr($slug, 0, 255, 'UTF-8');
    }

    private static function slugificar(string $texto): string
    {
        $texto = mb_strtolower(trim($texto), 'UTF-8');
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ü' => 'u', 'Ñ' => 'n',
        ];
        $texto = strtr($texto, $map);
        if (function_exists('iconv')) {
            $conv = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
            if ($conv !== false) {
                $texto = strtolower($conv);
            }
        }
        $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
        $texto = trim((string) $texto, '-');
        return $texto;
    }

    private static function slugExiste(mysqli $jm, string $slug): bool
    {
        $stmt = $jm->prepare('SELECT prop_id FROM gw_productos WHERE prod_slug = ? LIMIT 1');
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return !empty($row['prop_id']);
    }
}
