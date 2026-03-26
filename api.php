<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed!']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$platform = $input['platform'] ?? '';
$username = $input['username'] ?? '';
$jumlah = $input['jumlah'] ?? '';
$api_key = $input['api_key'] ?? '';
$api_id = $input['api_id'] ?? '';

// GANTI INI SESUAI API LU BRO!
$YOUR_API_KEY = 'qagwxa-cyscf4-ghm5dj-dfhnws-yedbx3';  // <- API KEY LU
$YOUR_API_ID = '90700';  // <- API ID LU

if ($api_key !== $YOUR_API_KEY || $api_id !== $YOUR_API_ID) {
    echo json_encode(['status' => 'error', 'message' => '🚫 API Key atau ID salah! Cek fayupedia.id/example.txt']);
    exit;
}

if (empty($username) || empty($jumlah)) {
    echo json_encode(['status' => 'error', 'message' => '❌ Username & jumlah harus diisi!']);
    exit;
}

// SIMULASI SUNTIK (GANTI DENGAN API REAL LU)
$order_id = 'YANTO-' . strtoupper(substr(md5(time() . $username), 0, 8));
$platforms = [
    'ig' => '📸 Followers', 
    'fb' => '📘 Likes', 
    'tt' => '🎵 Followers', 
    'yt' => '📺 Subscribers'
];

sleep(rand(1,3)); // Delay realistis

echo json_encode([
    'status' => 'success',
    'message' => "✅ Order {$platforms[$platform]} {$jumlah} untuk @{$username} berhasil diproses!",
    'order_id' => $order_id,
    'eta' => rand(5,30) . ' menit',
    'platform' => $platforms[$platform]
]);
?>
