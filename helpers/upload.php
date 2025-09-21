<?php
// helpers/upload.php - file validation and imgbb upload
if (session_status() === PHP_SESSION_NONE) session_start();

function validate_image_upload(array $file, array $config) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return 'No file uploaded or upload error.';
    }

    if ($file['size'] > $config['app']['upload']['max_size']) {
        return 'File too large. Max size is ' . ($config['app']['upload']['max_size'] / 1024 / 1024) . ' MB.';
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $config['app']['upload']['allowed_types'], true)) {
        return 'Invalid file type. Allowed: jpg, png, webp.';
    }

    return true;
}

function upload_to_imgbb(array $file, array $config) {
    $apiKey = $config['imgbb']['api_key'];
    if (empty($apiKey) || $apiKey === 'YOUR_IMGBB_API_KEY') {
        throw new RuntimeException('imgbb API key not configured.');
    }

    $imageData = base64_encode(file_get_contents($file['tmp_name']));

    $payload = [
        'key' => $apiKey,
        'image' => $imageData,
    ];

    $ch = curl_init($config['imgbb']['upload_url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) throw new RuntimeException('imgbb upload failed: ' . $err);

    $json = json_decode($resp, true);
    if (!isset($json['success']) || !$json['success']) {
        throw new RuntimeException('imgbb error: ' . ($json['error']['message'] ?? 'unknown'));
    }

    return $json['data']['url'];
}
