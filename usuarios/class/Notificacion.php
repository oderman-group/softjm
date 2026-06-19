<?php

class Notificacion {

    public static function crearAsignacionCliente(
        $conexionBdPrincipal,
        int $clienteId,
        int $usuarioId,
        int $empresaId,
        string $asunto
    ): ?int {
        if ($clienteId <= 0 || $usuarioId <= 0 || $empresaId <= 0) {
            return null;
        }

        $asunto = trim($asunto);
        if ($asunto === '') {
            return null;
        }

        $asuntoEscapado = mysqli_real_escape_string($conexionBdPrincipal, $asunto);
        $resultado = mysqli_query($conexionBdPrincipal, "
            INSERT INTO notificaciones (
                not_asunto, not_cliente, not_usuario, not_visto, not_estado,
                not_seguimiento, not_fecha, not_id_empresa
            ) VALUES (
                '" . $asuntoEscapado . "',
                '" . $clienteId . "',
                '" . $usuarioId . "',
                0,
                " . NOT_ESTADO_PENDIENTE . ",
                NULL,
                NOW(),
                '" . $empresaId . "'
            )
        ");

        if (!$resultado) {
            error_log('Error al crear notificación: ' . mysqli_error($conexionBdPrincipal));
            return null;
        }

        return mysqli_insert_id($conexionBdPrincipal);
    }

    public static function marcarVista($conexionBdPrincipal, int $notificacionId, int $usuarioId): void {
        if ($notificacionId <= 0 || $usuarioId <= 0) {
            return;
        }

        mysqli_query($conexionBdPrincipal, "
            UPDATE notificaciones
            SET not_visto = 1
            WHERE not_id = '" . $notificacionId . "'
              AND not_usuario = '" . $usuarioId . "'
        ");
    }

    public static function obtenerUrlDestino(array $notificacion): string {
        if (!empty($notificacion['not_seguimiento']) && (int) $notificacion['not_seguimiento'] > 0) {
            return 'notificaciones-lista.php?idNot=' . (int) $notificacion['not_id']
                . '&idSeg=' . (int) $notificacion['not_seguimiento'];
        }

        $clienteId = (int) ($notificacion['not_cliente'] ?? 0);
        $notId     = (int) ($notificacion['not_id'] ?? 0);
        $url       = 'clientes-editar.php?id=' . $clienteId;

        if ($notId > 0) {
            $url .= '&not=' . $notId;
        }

        return $url;
    }

    public static function tieneSeguimiento(array $notificacion): bool {
        return !empty($notificacion['not_seguimiento']) && (int) $notificacion['not_seguimiento'] > 0;
    }
}
