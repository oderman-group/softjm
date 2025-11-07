<?php
require_once('config-helpers.php'); 
global $appSecret, $API_VERSION;


function uploadSinglePhoto($pageId, $pageToken, $appsecret_proof, $photoUrl, $API_VERSION) {
    $uploadUrl = "https://graph.facebook.com/{$API_VERSION}/{$pageId}/photos";
    
    $uploadBody = http_build_query([
        'url' => $photoUrl,
        'access_token' => $pageToken,
        'appsecret_proof' => $appsecret_proof, 
        'published' => 'false'
    ]);

    $response = curlPostApiCall($uploadUrl, $uploadBody);
    $data = json_decode($response, true);

    if (isset($data['id'])) {
        return $data['id'];
    } else {
        $errorMsg = $data['error']['message'] ?? 'Fallo al subir foto.';
        sendError("Fallo al subir foto: " . $errorMsg, ['photo_url' => $photoUrl, 'meta_response' => $response]);
    }
}


$input = file_get_contents('php://input');
$data = json_decode($input, true);

$postPageId = $data['page_id'] ?? null;
$postPageToken = $data['page_token'] ?? null;

$postMessage = $data['message'] ?? '';
$imageUrls = $data['image_urls'] ?? []; 
$linkUrl = $data['link_url'] ?? null;


if (!$postPageId || !$postPageToken) {
    sendError('Token o ID de Página faltante para la publicación.');
}

if (empty(trim($postMessage))) {
    $postMessage = "¡Producto no valido!";
}

$appsecret_proof = hash_hmac('sha256', $postPageToken, $appSecret);

if (empty($imageUrls) && $linkUrl) {
    $imageUrls[] = $linkUrl; 
}

if (!empty($imageUrls)) {
    $attachedMediaIds = [];
    
    foreach ($imageUrls as $url) {
        $mediaId = uploadSinglePhoto($postPageId, $postPageToken, $appsecret_proof, $url, $API_VERSION);
        if ($mediaId) {
            $attachedMediaIds[] = [
                'media_fbid' => $mediaId
            ];
        }
    }
    
    $postUrl = "https://graph.facebook.com/{$API_VERSION}/{$postPageId}/feed";

    $postBody = http_build_query([
        'message' => $postMessage,
        'access_token' => $postPageToken,
        'appsecret_proof' => $appsecret_proof, 
        'published' => true,
        'attached_media' => json_encode($attachedMediaIds)
    ]);

} else {
    sendError("Fallo en la publicación: No se encontró contenido multimedia para publicar y no hay URL de fallback.");
}


$responsePost = curlPostApiCall($postUrl, $postBody);
$dataPost = json_decode($responsePost, true);

if (isset($dataPost['id'])) {
    $numFotos = count($imageUrls);
    $msg = $numFotos > 1 ? "¡Publicación exitosa con {$numFotos} imágenes!" : "¡Publicación de foto exitosa!";
    echo json_encode(['success' => true, 'message' => $msg, 'post_id' => $dataPost['id']]);
} else {
    $errorMsg = $dataPost['error']['message'] ?? 'Fallo desconocido al publicar el post.';
    sendError("Fallo en la publicación: " . $errorMsg, ['meta_response' => $responsePost]);
}