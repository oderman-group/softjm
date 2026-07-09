<?php
class Api_JmEquipos {
    //URL DE CONSUMO
    public const JM_URL_PORTAFOLIOS = 'https://jmequipos.com/api/portafolios.php';

    //URL GENERALES
    public const JM_URL_ARCHIVOS_PORTAFOLIOS = 'https://jmequipos.com/archivos/catalogos/';

    public static function getData(string $url): array {
        $response = @file_get_contents($url);
        if ($response === false) {
            return ['data' => []];
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            return ['data' => []];
        }

        if (!isset($data['data']) || !is_array($data['data'])) {
            $data['data'] = [];
        }

        return $data;
    }
}