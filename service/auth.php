<?php
session_start();
include("utility.php");
include("connection.php");
include('./sendToken.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $_POST['action'] ?? $data['action'] ?? '';
    switch ($action) {
        case 'login':
            login($conn);
            break;
        case 'addProduct':
            add_product($conn);
            break;
        case 'scanProduct';
            scanProduct($conn);
            break;
        case 'verify':
            verify($conn);
            break;
        case 'token_verify':
            verify_token($conn);
            break;
        case 'NPW':
            new_password($conn);
            break;
        case 'order':
            order($conn);
            break;
        case 'transaction':
            transaction($conn);
            break;
        case 'addCategory':
            add_category($conn);
            break;
        case 'addBrand':
            add_brand($conn);
            break;
        case 'new_acc':
            new_account($conn);
            break;
        case 'addDiscount':
            add_discount($conn);
            break;
        case 'newMember':
            new_member($conn);
            break;
        case 'verifyMember':
            verifyMember($conn);
            break;
        default:
            header('location: ../src/pages/auth/index.php');
            exit;
    }
}

function verifyMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    $phone = $data['memberPhone'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($phone) || empty($password)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Phone number and password are required']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password FROM members WHERE phone = ? LIMIT 1");
    $stmt->bind_param('s', $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            // Password cocok
            echo json_encode(['status' => 'success', 'message' => 'Verification successful']);
        } else {
            // Password salah
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
        }
    } else {
        // Nomor telepon tidak ditemukan
        echo json_encode(['status' => 'error', 'message' => 'Phone number not found']);
    }

    $stmt->close();
}


function new_member($conn)
{
    //check apakah password dan konfirmasi password sama
    if ($_POST['password'] == $_POST['cpass']) {
        //check apakah member sudah ada dari nomor telepon
        $stmt = $conn->prepare("SELECT * FROM members WHERE phone = ? LIMIT 1");
        $stmt->bind_param('s', $_POST['phone']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            echo json_encode(['message' => 'Member sudah ada']);
        } else {
            //hash password
            $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            //insert member ke database
            $stmt = $conn->prepare("INSERT INTO members (name, phone, password) VALUES (?,?,?)");
            $stmt->bind_param('sss', $_POST['username'], $_POST['phone'], $hashed);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Member berhasil ditambahkan']);
            } else {
                echo json_encode(['message' => 'kesalahan saat menambahkan member']);
            }
        }
    } else {
        echo json_encode(['message' => 'passowrd tidak sama']);
    }
}

function add_discount($conn)
{
    $stmt = $conn->prepare("SELECT * FROM discounts WHERE title = ? LIMIT 1");
    $stmt->bind_param('s', $_POST['title']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['err'] = 'Diskon dengan judul tersebut sudah ada';
        header('location: ../src/pages/dashboard/add_discount.php');
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO discounts (title, percentage, points_required ,exp_at) VALUES (?,?,?,?)");
    $stmt->bind_param("siis", $_POST['title'], $_POST['percentage'], $_POST['PR'], $_POST['exp']);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Diskon berhasil ditambahkan';
        header('location: ../src/pages/dashboard/add_discount.php');
    } else {
        $_SESSION['err'] = 'Diskon gagal ditambahkan';
        header('location: ../src/pages/dashboard/add_discount.php');
    }
}


function add_category($conn)
{
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $_POST['categoryName']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['err'] = 'Kategori sudah ada';
        header('location: ../src/pages/dashboard/add_category.php');
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $_POST['categoryName']);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan';
            header('location: ../src/pages/dashboard/add_category.php');
        } else {
            $_SESSION['err'] = 'Kategori gagal ditambahkan';
            header('location: ../src/pages/dashboard/add_category.php');
        }
    }
}

function add_brand($conn)
{
    $stmt = $conn->prepare("SELECT * FROM brands WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $_POST['brandName']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['err'] = 'Brand sudah ada';
        header('location: ../src/pages/dashboard/add_brand.php');
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO brands (name) VALUES (?)");
        $stmt->bind_param("s", $_POST['brandName']);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'brand berhasil ditambahkan';
            header('location: ../src/pages/dashboard/add_brand.php');
        } else {
            $_SESSION['err'] = 'brand gagal ditambahkan';
            header('location: ../src/pages/dashboard/add_brand.php');
        }
    }
}

function new_account($conn)
{
    // Pastikan semua data POST ada
    if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['cpassword'], $_POST['role'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Data tidak lengkap']);
        exit;
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_input = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role = $_POST['role'];


    // Cek email sudah ada atau belum
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'Email sudah terdaftar']);
        exit;
    }

    // Direktori target upload
    $targetDIR = __DIR__ . '/../src/assets/images/profiles/';
    if (!file_exists($targetDIR)) {
        if (!mkdir($targetDIR, 0755, true)) {
            echo json_encode(['message' => 'Failed to create target directory']);
            exit;
        }
    }

    // Validasi file upload
    $allowed = ['png', 'jpg', 'jpeg'];
    $maxsize = 4194304; // 4 MB

    if (!isset($_FILES['image'])) {
        echo json_encode(['message' => 'No image uploaded']);
        exit;
    }

    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed)) {
        echo json_encode(['message' => 'File type is not allowed. Please upload a png, jpg, or jpeg file instead.' . $file_ext]);
        exit;
    }

    if ($file_size > $maxsize) {
        echo json_encode(['message' => 'File is too large. File size should not exceed 4MB.']);
        exit;
    }

    // Generate nama file unik
    $new_name = time() . '_' . uniqid() . '.' . $file_ext;
    $uploadDIR = $targetDIR . $new_name;

    if (!is_writable($targetDIR)) {
        echo json_encode(['message' => 'Target directory is not writable.']);
        exit;
    }

    if (!move_uploaded_file($file_tmp, $uploadDIR)) {
        echo json_encode(['message' => 'Error while uploading the image']);
        exit;
    }

    $img = 'src/assets/images/profiles/' . $new_name;

    // Validasi password sama
    if ($password_input !== $cpassword) {
        echo json_encode(['message' => 'Password tidak sama']);
        exit;
    }

    // Simpan akun baru
    $password_hashed = password_hash($password_input, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admin (username, email, password, image, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $username, $email, $password_hashed, $img, $role);
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Akun berhasil dibuat']);
    } else {
        echo json_encode(['mesasge' => 'Gagal membuat akun']);
    }
}
function transaction($conn)
{
    $data = json_decode(file_get_contents("php://input"));

    if (!$data || !isset($data->order_id) || !isset($data->cash)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }

    $order_id = (int)$data->order_id;
    $cash = (float)$data->cash;
    $discount_id = (isset($data->discount) && $data->discount !== null) ? (int)$data->discount : 0;
    $code =  date('Ymd') . '-' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    // echo json_encode([
    //     'status' => 'error',
    //     'message' => [
    //         'order_id' => $order_id,
    //         'cash' => $cash,
    //         'discount_id' => $discount_id,
    //         'code' => $code
    //     ]
    // ]);
    // exit;

    // Ambil detail order dan member_id
    $stmt = $conn->prepare("SELECT member_id FROM orders WHERE id = ?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Order tidak ditemukan']);
        exit;
    }

    $member_id = (int)$order['member_id'];

    // Ambil subtotal
    $stmtSubtotal = $conn->prepare("
        SELECT SUM(p.price * od.qty) AS subtotal
        FROM order_details od
        JOIN products p ON od.product_fid = p.id
        WHERE od.order_fid = ?
    ");
    $stmtSubtotal->bind_param('i', $order_id);
    $stmtSubtotal->execute();
    $resultSubtotal = $stmtSubtotal->get_result()->fetch_assoc();
    $subtotal = (float)$resultSubtotal['subtotal'];

    $exchange = 0;
    $discount_value = 0;

    // Cek stok produk
    $stmtCheckStock = $conn->prepare("
        SELECT p.id, p.name, p.stock, od.qty
        FROM order_details od
        JOIN products p ON od.product_fid = p.id
        WHERE od.order_fid = ?
    ");
    $stmtCheckStock->bind_param('i', $order_id);
    $stmtCheckStock->execute();
    $resultStock = $stmtCheckStock->get_result();
    while ($row = $resultStock->fetch_assoc()) {
        if ($row['stock'] < $row['qty']) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => "Stok produk '{$row['name']}' tidak mencukupi."]);
            exit;
        }
    }

    if ($discount_id > 0) {
        $stmtDisc = $conn->prepare("SELECT percentage, points_required FROM discounts WHERE id = ? AND exp_at > NOW()");
        $stmtDisc->bind_param('i', $discount_id);
        $stmtDisc->execute();
        $discount = $stmtDisc->get_result()->fetch_assoc();

        if (!$discount) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Diskon tidak valid atau sudah kadaluarsa']);
            exit;
        }

        $discount_value = ($discount['percentage'] / 100) * $subtotal;
        $final_total = $subtotal - $discount_value;
        $exchange = $cash - $final_total;
        if ($exchange < 0) $exchange = 0;

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("
                INSERT INTO transactions (order_fid, transaction_code, cash, exchange, discount_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param('issdi', $order_id, $code, $cash, $exchange, $discount_id);
            $stmt->execute();
            $transaction_id = $stmt->insert_id;

            if ($member_id > 0) {
                $stmt = $conn->prepare("UPDATE members SET points = points - ? WHERE id = ?");
                $stmt->bind_param('ii', $discount['points_required'], $member_id);
                $stmt->execute();
            }

            updateOrderAndStock($conn, $order_id);
            $conn->commit();

            echo json_encode(['status' => 'success', 'message' => 'Transaksi berhasil', 'transaction_id' => $transaction_id]);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memproses transaksi', 'error' => $e->getMessage()]);
        }
    } elseif ($discount_id == 0) {
        $exchange = $cash - $subtotal;
        if ($exchange < 0) $exchange = 0;

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("
                INSERT INTO transactions (order_fid, transaction_code, cash, exchange)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param('issd', $order_id, $code, $cash, $exchange);
            $stmt->execute();
            $transaction_id = $stmt->insert_id;

            if ($member_id > 0) {
                $points_earned = floor($subtotal / 10000);
                $stmt = $conn->prepare("UPDATE members SET points = points + ? WHERE id = ?");
                $stmt->bind_param('ii', $points_earned, $member_id);
                $stmt->execute();
            }

            updateOrderAndStock($conn, $order_id);
            $conn->commit();

            echo json_encode(['status' => 'success', 'message' => 'Transaksi berhasil', 'transaction_id' => $transaction_id]);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal memproses transaksi', 'error' => $e->getMessage()]);
        }
    }
}


// Fungsi bantu update order dan stock
function updateOrderAndStock($conn, $order_id)
{
    $stmtOrder = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
    $stmtOrder->bind_param('i', $order_id);
    $stmtOrder->execute();

    $stmtStock = $conn->prepare("
        UPDATE products p
        JOIN order_details od ON p.id = od.product_fid
        SET p.stock = p.stock - od.qty
        WHERE od.order_fid = ?
    ");
    $stmtStock->bind_param('i', $order_id);
    $stmtStock->execute();
}


function order($conn)
{
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data || !isset($data['cartData']) || count($data['cartData']) == 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak ditemukan']);
        exit;
    }

    $total_price = 0;
    $total_items = 0;
    foreach ($data['cartData'] as $item) {
        $total_price += $item['total'];
        $total_items += $item['qty'];
    }

    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_id = $result->fetch_assoc()['id'];
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'User tidak ditemukan']);
        exit;
    }

    if ($data['memberPhone'] != '') {
        $stmt = $conn->prepare("SELECT id FROM members WHERE phone = ? LIMIT 1");
        $stmt->bind_param('s', $data['memberPhone']);
        $stmt->execute();
        $result = $stmt->get_result();
        $member_id = $result->fetch_assoc()['id'];
    } else {
        $member_id = 0;
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, member_id, total_items, total_price, status) VALUES (?,?,?,?,'pending')");
    $stmt->bind_param('iiid', $user_id, $member_id, $total_items, $total_price);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmtDetail = $conn->prepare("INSERT INTO order_details (order_fid, product_fid, qty, total_price) VALUES (?,?,?,?)");
        foreach ($data['cartData'] as $item) {
            $stmtDetail->bind_param('iiid', $order_id, $item['id'], $item['qty'], $item['price']);
            if (!$stmtDetail->execute()) {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal menyimpan item pesanan']);
                exit;
            }
        }
        $stmtDetail->close();
        echo json_encode(['success' => 'Pesanan berhasil dibuat', 'order_id' => $order_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal membuat pesanan']);
    }
}

function verify($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email =? LIMIT 1");
    $stmt->bind_param('s', $email);
    $result = $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $idAcc = $result->fetch_assoc()['id'];
        $characters = '0123456789';
        $length = 4; // Panjang kode yang diinginkan
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }

        $stmt = $conn->prepare("INSERT INTO verify_tokens (fid_acc, token, exp_at) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)");
        $stmt->bind_param('is', $idAcc, $token);
        if ($stmt->execute()) {
            if (sendTokenEmail($email, $token)) {
                echo json_encode(['status' => 'success', 'message' => 'Kode verifikasi telah dikirim ke email Anda']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email']);
            }
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'gagal melakukan verifikasi']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'email tidak terdaftar']);
    }
}

function verify_token($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $token = $data['token'];

    // Ambil ID akun berdasarkan email
    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak ditemukan']);
        exit();
    }

    $idAcc = $result->fetch_assoc()['id'];

    // Cek token masih berlaku
    $stmt = $conn->prepare("SELECT * FROM verify_tokens WHERE fid_acc = ? AND token = ? AND exp_at > NOW() LIMIT 1");
    $stmt->bind_param('is', $idAcc, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
        exit();
    }

    // (Opsional) Tandai akun terverifikasi atau hapus token
    $stmt = $conn->prepare("DELETE FROM verify_tokens WHERE fid_acc = ?");
    $stmt->bind_param('i', $idAcc);
    $stmt->execute();

    $_SESSION['verified'] = true; // Tandai sesi terverifikasi
    echo json_encode(['status' => 'success', 'message' => 'Verifikasi berhasil']);
}

function new_password($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $newPassword = $data['password'];

    // Validasi input dasar
    if (empty($email) || empty($newPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Email dan password baru wajib diisi']);
        exit();
    }

    // Pastikan pengguna sudah terverifikasi sebelumnya
    if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Akses tidak diizinkan. Verifikasi token terlebih dahulu.']);
        exit();
    }

    // Hash password baru
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password di tabel admin
    $stmt = $conn->prepare("UPDATE admin SET password = ?, updated_at = NOW() WHERE email = ?");
    $stmt->bind_param('ss', $hashedPassword, $email);

    if ($stmt->execute()) {
        // Bersihkan sesi verifikasi                                                                                                                                            mmmmmmmmmmmmmj
        unset($_SESSION['verified']);
        echo json_encode(['status' => 'success', 'message' => 'Password berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui password']);
    }
}

function scanProduct($conn)
{

    // Ambil data JSON dari request
    $data = json_decode(file_get_contents("php://input"), true);

    // Validasi input
    if (!$data || !isset($data['qrcode'])) {
        echo json_encode(['error' => 'Data QR/Barcode Tidak Ditemukan']);
        exit;
    }

    $code = trim($data['qrcode']); // Pastikan tidak ada spasi berlebih

    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("SELECT name, price, id FROM products WHERE uniqcode = ? AND stock > 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Produk tidak ditemukan', 'coba lgi']);
    }

    $stmt->close();
    $conn->close();
}

function login($conn)
{
    session_start();
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $stmt = $conn->prepare("SELECT * FROM admin WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['name'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['profile'] = base_url() . "/" . $row['image'];
            $_SESSION['loggedIn'] = true;
            $_SESSION['role'] = $row['role'];
            header('location: ../src/pages/dashboard/index.php');
            exit();
        }
    }

    if (!isset($_COOKIE['auth_token'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        // Gunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verifikasi password dengan password_verify()
            if (password_verify($password, $row['password'])) {
                $_SESSION['name'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['profile'] = base_url() . "/" . $row['image'];
                $_SESSION['loggedIn'] = true;
                $_SESSION['role'] = $row['role'];

                // Jika "Remember Me" dicentang, buat token dan simpan dalam cookie serta database
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32)); // Token unik
                    setcookie("auth_token", $token, time() + (86400 * 30), "/", "", true, true); // HTTP-Only & Secure

                    // Simpan token ke database
                    $stmt = $conn->prepare("UPDATE admin SET token = ? WHERE email = ?");
                    $stmt->bind_param("ss", $token, $email);
                    $stmt->execute();
                }

                header('location: ../src/pages/dashboard/index.php');
                exit();
            } else {
                $_SESSION['err'] = "password salah";
            }
        } else {
            $_SESSION['err'] = "email tidak ditemukan";
        }
        header('location: ../src/pages/auth/index.php');
        exit();
    }
}

function add_product($conn)
{
    // Debugging: Echo all submitted data
    //turn on to debug
    // echo '<pre>';
    // print_r($_POST);
    // print_r($_FILES);
    // echo '</pre>';

    $name = $_POST['productName'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE name = ? LIMIT 1");
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product with this name already exists']);
        exit();
    }

    $targetDIR = __DIR__ . '/../src/assets/images/product/';
    if (!file_exists($targetDIR)) {
        mkdir($targetDIR, 0777, true);
    }


    // Check if the file is an image
    $allowed = ['png', 'jpg', 'jpeg']; // Allowed file extensions
    $maxsize = 4194304; // 4 MB in bytes

    $file_name = $_FILES['image']['name']; // Get the name of the file (including file extension)
    $file_size = $_FILES['image']['size']; // Get the size of the file
    $file_tmp = $_FILES['image']['tmp_name']; // Get the temporary file path
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Get the file extension

    if (!in_array($file_ext, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'File type isnt allowed. Please upload as png, jpg, or jpeg']);
        exit();
    }

    if ($file_size > $maxsize) {
        echo json_encode(['status' => 'error', 'message' => 'File is too large. File size should not exceed 4MB']);
        exit();
    }

    // Generate a unique name for the image
    $new_name = time() . '_' . uniqid() . '.' . $file_ext;
    $uploadDIR = $targetDIR . $new_name;

    if (!is_writable($targetDIR)) {
        die("Error: Target directory is not writable.");
    }

    if (!move_uploaded_file($file_tmp, $uploadDIR)) {
        die("Error while uploading the image");
    }

    // Insert the product into the database
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $base_price = $_POST['base_price'];
    if ($_POST['production'] == '') {
        $production = NULL;
    } elseif ($_POST['production'] !== '') {
        $production = $_POST['production'];
    }
    if ($_POST['exp'] == '') {
        $exp = NULL;
    } elseif ($_POST['exp'] !== '') {
        $exp = $_POST['exp'];
    }
    $brand = $_POST['brand'];
    $fid_category = $_POST['kategori'];
    $description = $_POST['Detail'];
    $img = 'src/assets/images/product/' . $new_name;
    if ($_POST['uniqcode'] == '') {
        $uniqcode = generate_varchar();
    } else {
        $uniqcode = $_POST['uniqcode'];
    }
    $uniqcode = generate_varchar();

    if ($price === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Selling price cant be empty']);
        exit();
    }

    if ($base_price === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Base price cant be empty']);
        exit();
    }

    if ($brand === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Brand cant be empty']);
        exit();
    }

    if ($fid_category === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Category cant be empty']);
        exit();
    }

    if ($stock === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Stock cant be empty']);
        exit();
    }

    // Check if all required fields are set
    $stmt = $conn->prepare("INSERT INTO products
            (name, price, base_price, stock, category_id, description, image, brand_id, production_date, expiration_date, created_at, uniqcode) 
            VALUES (?,?,?,?,?,?,?,?,?,?,NOW(),?)");
    $stmt->bind_param("sddiississs", $name, $price, $base_price, $stock, $fid_category, $description, $img, $brand, $production, $exp, $uniqcode);
    // $query = "INSERT INTO products (name, price, margin, stock, category_id, description, image, brand_id, production_date, expiration_date, created_at) VALUES ($name, $price, $margin, $stock, $fid_category, $description, $img, $brand, $production, $exp, NOW())";
    // echo $query;

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Upload successful']);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload add new product']);
        exit();
    }
}
