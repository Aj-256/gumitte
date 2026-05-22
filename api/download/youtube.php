<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = isset($_GET['url']) ? $_GET['url'] : '';
$format = isset($_GET['format']) ? $_GET['format'] : '720p';

if (empty($url)) {
    echo json_encode(['success' => false, 'error' => 'URL is required']);
    exit;
}

$apiUrl = "https://meta-api.zone.id/downloader/youtube?url=" . urlencode($url) . "&format=" . $format;
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && isset($data['success']) && $data['success']) {
    $result = [
        'success' => true,
        'download_url' => $data['download_url'],
        'title' => isset($data['title']) ? $data['title'] : 'YouTube Video',
        'type' => $format === 'audio' ? 'audio' : 'video',
        'thumbnail' => isset($data['thumbnail']) ? $data['thumbnail'] : '',
        'author' => isset($data['author']) ? $data['author'] : 'Unknown',
        'description' => isset($data['description']) ? $data['description'] : ''
    ];
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch video']);
}
?>