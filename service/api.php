<?php
session_start();
include 'connection.php';

// Pastikan pengguna telah login
if (!isset($_SESSION['loggedIn'])) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(403);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('location: index.php');
}

// Tangkap parameter action untuk menentukan data yang diminta
$action = isset($_GET['action']) ? $_GET['action'] : header('location: index.php');

switch ($action) {
    case 'dashboard':
        dashboardData($conn);
        break;
        case 'getCategory':
        getCategory($conn);
        break;
        case 'getBrands':
        getBrands($conn);
        break;
    default:
        echo json_encode(['error' => 'Invalid request']);
        http_response_code(400);
}

// ====================== FUNCTION FETCH DATA ======================

// Fetch Data Dashboard
function dashboardData($conn)
{

// Ambil data total transaksi hari ini
$stmt = $conn->prepare("SELECT
DATE(NOW()) AS tanggal,
COUNT(DISTINCT t.id) AS total_transaksi_today,
COALESCE(SUM(td.quantity * (p.price + (p.price * p.margin / 100))), 0) AS total_penjualan,
COALESCE(SUM(td.quantity * p.price), 0) AS total_modal,
COALESCE(SUM(td.quantity * ((p.price * p.margin) / 100)), 0) AS total_keuntungan
FROM kasir.transactions t
LEFT JOIN kasir.transaction_details td ON t.id = td.transaction_id
LEFT JOIN kasir.products p ON td.product_id = p.id
WHERE DATE(t.created_at) = CURDATE()
");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_profit = $row['total_keuntungan'];
$total_transactions = $row['total_transaksi_today'];
$stmt->close();

$stmt = $conn->prepare("SELECT 
SUM(td.quantity * ((p.price * p.margin) / 100)) AS total_profit_yesterday
FROM kasir.transactions t
JOIN kasir.transaction_details td ON t.id = td.transaction_id
JOIN kasir.products p ON td.product_id = p.id
WHERE DATE(t.created_at) = CURDATE() - INTERVAL 1 DAY
");

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$total_profit_yesterday = $row['total_profit_yesterday'];

$percentage_profit_change = 0;
if ($total_profit_yesterday > 0) {
$percentage_profit_change = (($total_profit - $total_profit_yesterday) / $total_profit_yesterday) * 100;
}

// Format keuntungan ke dalam persentase
$percentage_profit_change_formatted = number_format($percentage_profit_change, 2);

// Tentukan ikon panah dan warna
$profit_class = $percentage_profit_change >= 0 ? 'text-meta-3' : 'text-meta-5';
$profit_arrow_icon = $percentage_profit_change >= 0 ? 'up' : 'down';
// Format ke dalam Rupiah (IDR)
$formatted_profit = "Rp " . number_format($total_profit, 0, ',', '.');


// card data transaksi, user, dan produk
$stmt = $conn->prepare('SELECT
(SELECT COUNT(*) FROM members) AS total_users,
(SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY) AS total_transaksi_kemarin,
(SELECT COUNT(*) FROM products) AS total_products,
(SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY) AS total_keuntungan_kemarin');
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$total_users = $row['total_users'];
$total_products = $row['total_products'];


// Hitung persentase perubahan untuk transaksi
$percentage_change = 0;
if ($row['total_transaksi_kemarin'] > 0) {
$percentage_change = (($total_transactions - $row['total_transaksi_kemarin']) / $row['total_transaksi_kemarin']) * 100;
}
$percentage_change_formatted = number_format($percentage_change, 2);

// Tentukan ikon panah dan warna
$percentage_class = $percentage_change >= 0 ? 'text-meta-3' : 'text-meta-5';
$arrow_icon = $percentage_change >= 0 ? 'up' : 'down';

    // Format data untuk dikirim
    echo json_encode([
        'total_users' => $total_users,
        'total_transactions' => $total_transactions,
        'total_products' => $total_products,
        'total_profit' => $formatted_profit,
        'percentage_profit' => $percentage_profit_change_formatted,
        'percentage_transaction' => $percentage_change_formatted,
        'profit_class' => $profit_class,
        'percentage_class' => $percentage_class,
        'profit_arrow' => $profit_arrow_icon,
        'transaction_arrow' => $arrow_icon
    ]);
    
    exit();
}

function getCategory($conn)
{
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories);
    exit();
}

function getBrands($conn)
{
    $stmt = $conn->prepare("SELECT * FROM brands");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $brands = [];
    while ($row = $result->fetch_assoc()) {
        $brands[] = $row;
    }

    echo json_encode($brands);
    exit();
}