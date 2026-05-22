<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    echo json_encode(['success' => false, 'error' => 'URL is required']);
    exit;
}

$apiUrl = "https://api.nexray.eu.cc/downloader/spotify?url=" . urlencode($url);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && (isset($data['url']) || isset($data['download_url']))) {
    $downloadUrl = isset($data['url']) ? $data['url'] : (isset($data['download_url']) ? $data['download_url'] : '');
    $thumbnail = isset($data['thumbnail']) ? $data['thumbnail'] : (isset($data['cover']) ? $data['cover'] : '');
    $author = isset($data['author']) ? $data['author'] : (isset($data['artist']) ? $data['artist'] : 'Spotify Artist');
    $result = [
        'success' => true,
        'download_url' => $downloadUrl,
        'title' => isset($data['title']) ? $data['title'] : 'Spotify Track',
        'type' => 'audio',
        'thumbnail' => $thumbnail,
        'author' => $author,
        'description' => isset($data['description']) ? $data['description'] : ''
    ];
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch Spotify track']);
}
?>