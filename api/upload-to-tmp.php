<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$input = json_decode(file_get_contents('php://input'), true);
$fileUrl = isset($input['fileUrl']) ? $input['fileUrl'] : '';
$filename = isset($input['filename']) ? $input['filename'] : 'media.mp4';

if (empty($fileUrl)) {
    echo json_encode(['success' => false, 'error' => 'File URL is required']);
    exit;
}

$fileContent = file_get_contents($fileUrl);
if ($fileContent === false) {
    echo json_encode(['success' => false, 'error' => 'Failed to download file']);
    exit;
}

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$postData = $delimiter . "\r\n";
$postData .= 'Content-Disposition: form-data; name="file"; filename="' . $filename . '"' . "\r\n";
$postData .= 'Content-Type: application/octet-stream' . "\r\n\r\n";
$postData .= $fileContent . "\r\n";
$postData .= $delimiter . "--\r\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://tmpfiles.org/api/v1/upload');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: multipart/form-data; boundary=' . $delimiter
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    if ($result && isset($result['status']) && $result['status'] === 'success') {
        echo json_encode([
            'success' => true,
            'url' => $result['data']['url']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Upload failed with status: ' . $httpCode]);
}
?>