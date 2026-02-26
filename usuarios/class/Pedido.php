<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Pedido extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'pedidos';
    public static $primaryKey = 'pedid_id';
    public static $tableAs    = 'pedid';

    const ESTADO_EN_PREPRACION = 'En prepración';
    const ESTADO_EN_CAMINO     = 'En camino';
    const ESTADO_ENTREGADO     = 'Entregado';
    const ESTADO_PEDIDO = ['DESCONOCIDO', self::ESTADO_EN_PREPRACION, self::ESTADO_EN_CAMINO, self::ESTADO_ENTREGADO];

    /**
     * Indica si el pedido ya fue facturado (tiene remisión y esa remisión tiene factura).
     * No se debe modificar ni reenviar a Ofima si está facturado.
     *
     * @param int $pedidId
     * @param mysqli $conexionBdPrincipal
     * @param int|null $idEmpresa opcional para filtrar
     * @return bool
     */
    public static function estaFacturado($pedidId, $conexionBdPrincipal, $idEmpresa) {
        $pedidId = (int) $pedidId;
        $idEmpresa = (int) $idEmpresa;
        if ($pedidId <= 0 || $idEmpresa <= 0) {
            return false;
        }
        $sql = "SELECT 1 FROM remisionbdg r 
                INNER JOIN facturas f ON f.factura_remision = r.remi_id AND f.factura_id_empresa = ? 
                WHERE r.remi_pedido = ? AND r.remi_id_empresa = ? LIMIT 1";
        $stmt = $conexionBdPrincipal->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("iii", $idEmpresa, $pedidId, $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}