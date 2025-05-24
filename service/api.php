<?php
session_start();
include 'connection.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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
    case 'getProduct':
        getProducts($conn);
        break;
    case 'getAccountDetails':
        getAccD($conn);
        break;
    case 'getDiscountDetails':
        getDD($conn);
        break;
    case 'getMemberDetails':
        getMD($conn);
        break;
    case 'getProductDetails':
        getProductDetails($conn);
        break;
    case 'get_inv':
        getInv($conn);
        break;
    case 'get_orders':
        getOrders($conn);
        break;
    case 'getDiscounts':
        getDiscounts($conn);
        break;
    case 'getMembers':
        getMembers($conn);
        break;
    case 'getAdmins':
        getAdmins($conn);
        break;
    default:
        echo json_encode(['error' => 'Invalid request']);
        http_response_code(400);
}

// ====================== FUNCTION FETCH DATA ======================
function getMD($conn){
    $stmt = $conn->prepare('SELECT * FROM members WHERE id = ? ');
    $stmt->bind_param('i', $_GET['memId']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'gagal mendapatkan data']);
    }
}
function getAccD($conn){
    $stmt = $conn->prepare('SELECT * FROM admin WHERE id = ? ');
    $stmt->bind_param('i', $_GET['accId']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'gagal mendapatkan data']);
    }
}
function getDD($conn){
    $stmt = $conn->prepare('SELECT * FROM discounts WHERE id = ? ');
    $stmt->bind_param('i', $_GET['disId']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'gagal mendapatkan data']);
    }
}
function getMembers($conn)
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // Ambil filter dari request
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Bangun WHERE condition
    $where = " WHERE m.id > 0 ";
    $params = [];
    $types = '';

    // Filter Search Name
    if (!empty($search)) {
        $where .= " AND m.name OR m.phone LIKE ? ";
        $params[] = "%$search%";
        $types .= 's';
    }

    // Filter Status
    if ($status === '1') {
        $where .= " AND m.exp_at > NOW() ";
    } elseif ($status === '0') {
        $where .= " AND m.exp_at < NOW() ";
    }

    // Hitung total rows
    $countQuery = "SELECT COUNT(*) AS total FROM members m $where";
    $countStmt = $conn->prepare($countQuery);
    if (!empty($types)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    // Query data dengan filter dan pagination
    $query = "SELECT 
        m.id AS id,
        m.name AS Name,
        m.phone AS Phone,
        m.points AS Points,
        CASE 
            WHEN m.exp_at > NOW() THEN 'Active'
            ELSE 'Expired'
        END AS Status,
        (SELECT COUNT(*) FROM orders o WHERE o.member_id = m.id) AS `Transaction`,
        m.created_at AS `Created_at`
    FROM members m
    $where
    ORDER BY m.created_at DESC
    LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $members = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'data' => $members,
            'current_page' => $page,
            'total_pages' => $totalPages
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'No members found']);
        http_response_code(404);
    }
}

function getAdmins($conn)
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // Ambil filter dari request
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $role = isset($_GET['role']) ? $_GET['role'] : '';

    // Bangun WHERE condition
    $where = " WHERE a.id > 0 ";
    $params = [];
    $types = '';

    // Filter Search Name
    if (!empty($search)) {
        $where .= " AND a.username OR a.email LIKE ? ";
        $params[] = "%$search%";
        $types .= 's';
    }

    // Filter Role
    if (!empty($role)) {
        $where .= " AND a.role = ? ";
        $params[] = $role;
        $types .= 's';
    }

    // Hitung total rows
    $countQuery = "SELECT COUNT(*) AS total FROM admin a $where";
    $countStmt = $conn->prepare($countQuery);
    if (!empty($types)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    // Query data dengan filter dan pagination
    $query = "SELECT 
        a.id AS id,
        a.username AS Name,
        a.email AS Email,
        a.role AS Role,
        a.image AS Img,
        a.created_at AS Created_at,
        a.updated_at AS Updated_at
    FROM admin a
    $where
    ORDER BY a.created_at DESC
    LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admins = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'data' => $admins,
            'current_page' => $page,
            'total_pages' => $totalPages
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'No admins found']);
        http_response_code(404);
    }
}


function getDiscounts($conn)
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // Ambil filter dari request
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $minPoints = isset($_GET['min']) ? (int)$_GET['min'] : null;
    $maxPoints = isset($_GET['max']) ? (int)$_GET['max'] : null;

    // Mulai query
    $where = " WHERE d.id > 0 ";
    $params = [];
    $types = '';

    // Filter Search Title
    if (!empty($search)) {
        $where .= " AND d.title LIKE ? ";
        $params[] = "%$search%";
        $types .= 's';
    }

    // Filter Status
    if ($status === '1') { // Available
        $where .= " AND d.exp_at > NOW() ";
    } elseif ($status === '0') { // Expired
        $where .= " AND d.exp_at <= NOW() ";
    }

    // Filter Min Points
    if (!is_null($minPoints)) {
        $where .= " AND d.points_required >= ? ";
        $params[] = $minPoints;
        $types .= 'i';
    }

    // Filter Max Points
    if (!is_null($maxPoints)) {
        $where .= " AND d.points_required <= ? ";
        $params[] = $maxPoints;
        $types .= 'i';
    }

    // Hitung total rows
    $countQuery = "SELECT COUNT(*) AS total FROM discounts d $where";
    $countStmt = $conn->prepare($countQuery);
    if (!empty($types)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    // Query data dengan filter dan pagination
    $query = "SELECT 
    d.id AS id,
    d.title AS Title,
    d.percentage AS Percentage,
    d.points_required AS PR,
    d.created_at AS CAT,
    d.exp_at AS Exp,
    (SELECT COUNT(*) FROM transactions t WHERE t.discount_id = d.id) AS Used
FROM discounts d
$where
LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $discounts = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'data' => $discounts,
            'current_page' => $page,
            'total_pages' => $totalPages
        ]);
        exit();
    } else {
        echo json_encode(['error' => 'No discounts found']);
        http_response_code(404);
    }
}
function getOrders($conn)
{
    $where = [];
    $params = [];
    $types = '';

    // Filter member
    if (!empty($_GET['member'])) {
        if ($_GET['member'] == 'registered') {
            $where[] = 'o.member_id > 0';
        } elseif ($_GET['member'] == 'unregistered') {
            $where[] = 'o.member_id = 0';
        }
    }

    // Filter status
    if (!empty($_GET['status'])) {
        $where[] = 'o.status = ?';
        $params[] = $_GET['status'];
        $types .= 's';
    }

    if (!empty($_GET['date_from']) && !empty($_GET['date_to'])) {
        $where[] = "DATE(o.created_at) BETWEEN ? AND ?";
        $params[] = $_GET['date_from'];
        $params[] = $_GET['date_to'];
        $types .= 'ss';
    } elseif (!empty($_GET['date_from'])) {
        $where[] = "DATE(o.created_at) >= ?";
        $params[] = $_GET['date_from'];
        $types .= 's';
    } elseif (!empty($_GET['date_to'])) {
        $where[] = "DATE(o.created_at) <= ?";
        $params[] = $_GET['date_to'];
        $types .= 's';
    }


    // Search by order number
    if (!empty($_GET['search'])) {
        $where[] = 'o.id LIKE ?';
        $params[] = '%' . $_GET['search'] . '%';
        $types .= 's';
    }

    $sql = "SELECT o.id AS order_number, o.total_items AS qty, o.total_price, m.name AS member_name, o.created_at AS date, o.status
            FROM orders o
            LEFT JOIN members m ON o.member_id = m.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    $sql .= " ORDER BY o.created_at DESC";

    // Pagination
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = 7;
    $offset = ($page - 1) * $limit;
    $sql .= " LIMIT $limit OFFSET $offset";

    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($orders);
}

function getInv($conn)
{
    $order_id = $_GET['order'];
    $stmt = $conn->prepare("SELECT 
    t.transaction_code AS noInv,
    t.cash,
    t.exchange,
    t.date,
    t.payment_method,
    o.total_items,
    o.total_price,
    COALESCE(m.name, 'default') AS nama_pelanggan,
    d.title,
    d.percentage AS discount_value,
    p.name AS product_name,
    p.price AS price,
    od.qty,
    od.total_price AS subPrice
        FROM transactions t
        JOIN orders o ON t.order_fid = o.id
        JOIN order_details od ON o.id = od.order_fid
        JOIN products p ON od.product_fid = p.id
        LEFT JOIN members m ON o.member_id = m.id
        LEFT JOIN discounts d ON t.discount_id = d.id
        WHERE o.id = ?
");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    if ($result = $stmt->get_result()) {
        $items = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($items);
    } else {
        echo json_encode(['error' => 'Data not found']);
        http_response_code(404);
    }
}

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

    // Format data untuk dikirim
    echo json_encode([
        'total_users' => $total_users,
        'total_transactions' => $total_transactions,
        'total_products' => $total_products,
        'total_profit' => $formatted_profit,
        'percentage_profit' => $percentage_profit_change_formatted,
        'percentage_transaction' => $percentage_change_formatted,
        'profit_class' => $profit_class,
        'percentage_class' => $percentage_class
    ]);

    exit();
}

function getProducts($conn)
{
    $search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;
    $brand = isset($_GET['brand']) ? $conn->real_escape_string($_GET['brand']) : null;
    $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : null;
    $min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : null;
    $max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : null;

    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;

    // ====== Hitung total produk ======
    $countQuery = "SELECT COUNT(DISTINCT p.id) AS total
                   FROM products p
                   JOIN categories c ON p.category_id = c.id
                   JOIN brands b ON p.brand_id = b.id
                   WHERE 1";
    $countParams = [];
    $countTypes = '';

    if ($search) {
        $countQuery .= " AND (p.name LIKE ? OR c.name LIKE ? OR b.name LIKE ?) ";
        $countTypes .= 'sss';
        array_push($countParams, $search, $search, $search);
    }
    if ($brand) {
        $countQuery .= " AND b.name = ? ";
        $countTypes .= 's';
        $countParams[] = $brand;
    }
    if ($category) {
        $countQuery .= " AND c.name = ? ";
        $countTypes .= 's';
        $countParams[] = $category;
    }
    if ($min_price !== null) {
        $countQuery .= " AND p.price >= ? ";
        $countTypes .= 'i';
        $countParams[] = $min_price;
    }
    if ($max_price !== null) {
        $countQuery .= " AND p.price <= ? ";
        $countTypes .= 'i';
        $countParams[] = $max_price;
    }

    $stmtCount = $conn->prepare($countQuery);
    if (!empty($countParams)) {
        $stmtCount->bind_param($countTypes, ...$countParams);
    }
    $stmtCount->execute();
    $totalResult = $stmtCount->get_result()->fetch_assoc();
    $total = $totalResult['total'];
    $total_pages = ceil($total / $limit);

    // ====== Query data produk ======
    $query = "SELECT 
                p.id AS product_id,
                p.image AS img,
                p.name AS product_name,
                c.name AS category_name,
                p.price AS product_price,
                b.name AS brand,
                COALESCE(SUM(od.qty), 0) AS total_sold,
                (COALESCE(SUM(od.qty), 0) * p.price) AS profit
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN brands b ON p.brand_id = b.id
            LEFT JOIN order_details od ON p.id = od.product_fid
            WHERE 1 ";

    $types = '';
    $params = [];

    if ($search) {
        $query .= " AND (p.name LIKE ? OR c.name LIKE ? OR b.name LIKE ?) ";
        $types .= 'sss';
        array_push($params, $search, $search, $search);
    }
    if ($brand) {
        $query .= " AND b.name = ? ";
        $types .= 's';
        $params[] = $brand;
    }
    if ($category) {
        $query .= " AND c.name = ? ";
        $types .= 's';
        $params[] = $category;
    }
    if ($min_price !== null) {
        $query .= " AND p.price >= ? ";
        $types .= 'i';
        $params[] = $min_price;
    }
    if ($max_price !== null) {
        $query .= " AND p.price <= ? ";
        $types .= 'i';
        $params[] = $max_price;
    }

    $query .= " GROUP BY p.id, p.name, c.name, p.price, b.name
                ORDER BY profit DESC
                LIMIT ? OFFSET ?";

    $types .= 'ii';
    array_push($params, $limit, $offset);

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'data' => $products,
        'current_page' => $page,
        'total_pages' => $total_pages
    ]);
    exit();
}

function getProductDetails($conn)
{
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ? ');
    $stmt->bind_param('i', $_GET['productId']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'gagal mendapatkan data']);
    }
}

function getCategory($conn)
{
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;

    // Count total
    $countSql = "SELECT COUNT(*) AS total FROM brands";
    if ($search) {
        $countSql .= " WHERE name LIKE ?";
        $stmt = $conn->prepare($countSql);
        $stmt->bind_param('s', $search);
    } else {
        $stmt = $conn->prepare($countSql);
    }
    $stmt->execute();
    $totalResult = $stmt->get_result()->fetch_assoc();
    $total = $totalResult['total'];
    $total_pages = ceil($total / $limit);

    // Select brands with product count
    $dataSql = "SELECT c.id AS id, c.name AS name, COUNT(p.id) AS relate_c
                FROM categories c
                LEFT JOIN products p ON c.id = p.category_id";
    if ($search) {
        $dataSql .= " WHERE b.name LIKE ?";
    }
    $dataSql .= " GROUP BY c.id, c.name
                  ORDER BY c.id ASC
                  LIMIT ? OFFSET ?";

    if ($search) {
        $stmt = $conn->prepare($dataSql);
        $stmt->bind_param('sii', $search, $limit, $offset);
    } else {
        $stmt = $conn->prepare($dataSql);
        $stmt->bind_param('ii', $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $brands = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'data' => $brands,
        'current_page' => $page,
        'total_pages' => $total_pages
    ]);
    exit();
}

function getBrands($conn)
{
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : null;

    // Count total
    $countSql = "SELECT COUNT(*) AS total FROM brands";
    if ($search) {
        $countSql .= " WHERE name LIKE ?";
        $stmt = $conn->prepare($countSql);
        $stmt->bind_param('s', $search);
    } else {
        $stmt = $conn->prepare($countSql);
    }
    $stmt->execute();
    $totalResult = $stmt->get_result()->fetch_assoc();
    $total = $totalResult['total'];
    $total_pages = ceil($total / $limit);

    // Select brands with product count
    $dataSql = "SELECT b.id AS id, b.name AS name, COUNT(p.id) AS relate_b
                FROM brands b
                LEFT JOIN products p ON b.id = p.brand_id";
    if ($search) {
        $dataSql .= " WHERE b.name LIKE ?";
    }
    $dataSql .= " GROUP BY b.id, b.name
                  ORDER BY b.id ASC
                  LIMIT ? OFFSET ?";

    if ($search) {
        $stmt = $conn->prepare($dataSql);
        $stmt->bind_param('sii', $search, $limit, $offset);
    } else {
        $stmt = $conn->prepare($dataSql);
        $stmt->bind_param('ii', $limit, $offset);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $brands = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'data' => $brands,
        'current_page' => $page,
        'total_pages' => $total_pages
    ]);
    exit();
}
