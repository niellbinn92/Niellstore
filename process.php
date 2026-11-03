<?php
// Pastikan request datang dari POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $api = $_POST['api'] ?? '';
    $username = $_POST['username'] ?? '';

    if (empty($api) || empty($username)) {
        echo "Error: API dan Key/Username tidak boleh kosong!";
        exit;
    }

    $url = 'https://dripclientofficial.shop/api/v1/reset_key.php';
    $apiToken = 'c307544495ec38c2663af63a1b6ab3a1c18195ea0af0a723b45048eef3e7b393'; // Token aman di backend

    $postData = http_build_query([
        'api' => $api,
        'username' => $username
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Token: ' . $apiToken,
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "cURL Error: " . $error;
    } else {
        echo $response;
    }
} else {
    echo "Akses ditolak.";
}
?>
