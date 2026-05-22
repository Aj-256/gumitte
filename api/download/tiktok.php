<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = isset($_GET['url']) ? $_GET['url'] : '';

if (empty($url)) {
    echo json_encode(['success' => false, 'error' => 'URL is required']);
    exit;
}

$apiUrl = "https://meta-api.zone.id/downloader/tikdl?url=" . urlencode($url);
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if ($data && isset($data['downloadLink'])) {
    $result = [
        'success' => true,
        'download_url' => $data['downloadLink'],
        'title' => isset($data['description']) ? $data['description'] : 'TikTok Video',
        'type' => 'video',
        'thumbnail' => isset($data['profilePic']) ? $data['profilePic'] : '',
        'author' => isset($data['author']) ? $data['author'] : 'TikTok User',
        'description' => isset($data['description']) ? $data['description'] : '',
        'likes' => isset($data['likes']) ? $data['likes'] : 0,
        'comments' => isset($data['comments']) ? $data['comments'] : 0
    ];
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch TikTok video']);
}
?>