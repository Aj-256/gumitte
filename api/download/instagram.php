<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    echo json_encode(['success' => false, 'error' => 'URL is required']);
    exit;
}

$apiUrl = "https://api.nexray.eu.cc/downloader/v2/instagram?url=" . urlencode($url);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && isset($data['url'])) {
    $result = [
        'success' => true,
        'download_url' => $data['url'],
        'title' => isset($data['title']) ? $data['title'] : 'Instagram Media',
        'type' => 'video',
        'thumbnail' => isset($data['thumbnail']) ? $data['thumbnail'] : '',
        'author' => isset($data['author']) ? $data['author'] : 'Instagram User',
        'description' => isset($data['description']) ? $data['description'] : ''
    ];
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch Instagram media']);
}
?>