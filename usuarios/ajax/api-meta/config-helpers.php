<?php
header('Content-Type: application/json');

$appId = '1908014503405045';
$appSecret = '36626f2fe08a9bebae691f0f4d58f805';
$API_VERSION = 'v24.0';

function sendError($msg, $data = null) {
    $response = ['success' => false, 'error' => $msg];
    if ($data !== null) {
        $response['details'] = $data;
    }
    echo json_encode($response);
    exit;
}

function curlGetApiCall($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        sendError("Error de red cURL (GET): " . $error);
    }
    curl_close($ch);
    return $response;
}


function curlPostApiCall($url, $postData) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); 

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        sendError("Error de red cURL (POST): " . $error);
    }
    curl_close($ch);
    return $response;
}
?>