<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    echo json_encode(['success' => false, 'error' => 'URL is required']);
    exit;
}

$apiUrl = "https://api.nexray.eu.cc/downloader/likee?url=" . urlencode($url);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && (isset($data['url']) || isset($data['download_url']))) {
    $downloadUrl = isset($data['url']) ? $data['url'] : (isset($data['download_url']) ? $data['download_url'] : '');
    $result = [
        'success' => true,
        'download_url' => $downloadUrl,
        'title' => isset($data['title']) ? $data['title'] : 'Likee Video',
        'type' => 'video',
        'thumbnail' => isset($data['thumbnail']) ? $data['thumbnail'] : '',
        'author' => isset($data['author']) ? $data['author'] : 'Likee Creator',
        'description' => isset($data['description']) ? $data['description'] : ''
    ];
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch Likee video']);
}
?>