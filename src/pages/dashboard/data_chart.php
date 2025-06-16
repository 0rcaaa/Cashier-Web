<?php
include '../../../service/connection.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["from"], $data["to"])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Parameter 'from' dan 'to' wajib diisi."
    ]);
    exit;
}

$from = $conn->real_escape_string($data["from"]);
$to = $conn->real_escape_string($data["to"]);

if (!$from || !$to) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Tanggal tidak valid."
    ]);
    exit;
}

$query = "SELECT 
    DATE(o.created_at) AS tanggal,
    SUM((p.price - p.base_price) * od.qty) AS keuntungan,
    SUM(od.qty) AS penjualan
  FROM orders o
  JOIN order_details od ON o.id = od.order_fid
  JOIN products p ON od.product_fid = p.id
  WHERE o.status = 'paid' AND DATE(o.created_at) BETWEEN '$from' AND '$to'
  GROUP BY DATE(o.created_at)
  ORDER BY DATE(o.created_at)";

$result = $conn->query($query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengambil data: " . $conn->error
    ]);
    exit;
}

$tanggal = [];
$keuntungan = [];
$penjualan = [];

while ($row = $result->fetch_assoc()) {
    $tanggal[] = $row['tanggal'];
    $keuntungan[] = (float)$row['keuntungan'];
    $penjualan[] = (float)$row['penjualan'];
}

echo json_encode([
    "success" => true,
    "data" => [
        "tanggal" => $tanggal,
        "keuntungan" => $keuntungan,
        "penjualan" => $penjualan
    ]
]);