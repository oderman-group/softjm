<?php
require_once('config-helpers.php');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$shortLivedToken = $data['short_token'] ?? null;
$fbUserId = $data['fb_user_id'] ?? null;

if (!$shortLivedToken || !$fbUserId) {
    sendError('Datos de autenticación incompletos.');
}

$tokenExchangeUrl = "https://graph.facebook.com/{$API_VERSION}/oauth/access_token?" .
                    "grant_type=fb_exchange_token" .
                    "&client_id={$appId}" .
                    "&client_secret={$appSecret}" .
                    "&fb_exchange_token={$shortLivedToken}";

$responseLong = curlGetApiCall($tokenExchangeUrl);
$dataLong = json_decode($responseLong, true);

if (!isset($dataLong['access_token'])) {
    $detailedError = (isset($dataLong['error'])) ? $dataLong['error']['message'] : 'Respuesta inválida al obtener el token largo.';
    sendError("Fallo en intercambio de token: " . $detailedError, ['meta_response' => $responseLong]);
}
$longLivedToken = $dataLong['access_token']; 

$pagesUrl = "https://graph.facebook.com/{$API_VERSION}/me/accounts?access_token={$longLivedToken}";

$responsePages = curlGetApiCall($pagesUrl);
$dataPages = json_decode($responsePages, true);

if (isset($dataPages['data'])) {
    echo json_encode(['success' => true, 'pages' => $dataPages['data']]);
} else {
    sendError("Fallo al listar páginas. Meta no devolvió la clave 'data'.", ['raw_response' => $responsePages]);
}
?>