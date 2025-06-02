<?php
session_start();
include("utility.php");
include("connection.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('index.php');
}   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $action = $_POST['action'] ?? $data['action'] ?? '';
    switch ($action) {
        case 'Account':
            putAccount($conn);
            break;
        case 'Product':
            putProduct($conn);
            break;
        case 'Member':
            putMember($conn);
            break;
        case 'Discount':
            putDiscount($conn);
            break;
        default:
            header('location: ./index.php');
            exit;
    }
}

// if method get give error json with message url not found
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(['success' => false, 'message' => 'URL not found']);
    exit;
}

function putDiscount($conn) {
    // Ambil data dari $_POST karena dikirim via FormData
    $discountId = $_POST['DisId']; // pastikan ada id diskon yang dikirim
    $title = $_POST['title'];
    $pr = $_POST['pr'];
    $percentage = $_POST['percentage'];
    $exp = $_POST['exp'];

    // Validasi awal
    if (empty($discountId)) {
        echo json_encode(['success' => false, 'message' => 'Discount ID is required.']);
        exit;
    }

    $fields = [];
    $params = [];
    $types = '';

    if (!empty($title)) {
        $fields[] = "title = ?";
        $params[] = $title;
        $types .= 's';
    }

    if (!empty($pr)) {
        $fields[] = "points_required = ?";
        $params[] = $pr;
        $types .= 'i';
    }

    if (!empty($percentage)) {
        $fields[] = "percentage = ?";
        $params[] = $percentage;
        $types .= 'd';
    }

    if (!empty($exp)) {
        $fields[] = "exp_at = ?";
        $params[] = $exp;
        $types .= 's';
    }

    // Cek apakah ada field untuk update
    if (empty($fields)) {
        echo json_encode(['success' => false, 'message' => 'No data to update.']);
        exit;
    }

    $params[] = $discountId;
    $types .= 'i';

    $sql = "UPDATE discounts SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Discount updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update discount.']);
    }
}


function putMember($conn) {
    // Ambil data dari $_POST karena dikirim via FormData
    $memId = $_POST['memId'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $points = $_POST['points'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Validasi awal
    if (empty($memId)) {
        echo json_encode(['success' => false, 'message' => 'Member ID is required.']);
        exit;
    }

    // Mulai query dinamis
    $fields = [];
    $params = [];
    $types = '';

    // Tambah fields sesuai data yang dikirim
    if (!empty($name)) {
        $fields[] = "name = ?";
        $params[] = $name;
        $types .= 's';
    }

    if (!empty($phone)) {
        $stmt = $conn->prepare("SELECT id FROM members WHERE phone = ?");
        $stmt->execute([$phone]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'No telephone sudah digunakan oleh akun lain.']);
            exit;
        }
        $fields[] = "phone = ?";
        $params[] = $phone;
        $types .= 's';

    }

    if (isset($points)) {
        $fields[] = "points = ?";
        $params[] = $points;
        $types .= 'i';
    }

    // Jika password diisi, hash dan update
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $fields[] = "password = ?";
        $params[] = $hashedPassword;
        $types .= 's';
    }

    // Cek apakah ada field yang akan diupdate
    if (empty($fields)) {
        echo json_encode(['success' => false, 'message' => 'No data to update.']);
        exit;
    }

    // Tambahkan memId untuk WHERE
    $params[] = $memId;
    $types .= 'i';

    // Siapkan query update
    $sql = "UPDATE members SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Member updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update member.']);
    }
}

function putProduct($conn)
{
    $pId = $_POST['productId'];
    $upName = $_POST['name'];
    $upPrice = $_POST['price'];
    $upStock = $_POST['stok'];
    $upUniqcode = $_POST['uniqcode'];
    $upPro = $_POST['production_date'];
    $upExp = $_POST['expiration_date'];
    $upDesc = $_POST['description'];
    $upCategory = $_POST['kategori_id'];
    $upBrand = $_POST['brand_id'];

    if (empty($pId)) {
        echo json_encode(['message' => 'Product ID is required.']);
        exit;
    }

    // Cek email dan field lain jika diperlukan (contoh di versi sebelumnya)

    $fields = [];
    $params = [];
    $types = '';

    if (!empty($upName)) {
        $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->execute([$upName]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Produk dengan nama yang sama sudah ada.']);
            exit;
        }
        $fields[] = "name=?";
        $params[] = $upName;
        $types .= 's';
    }
    if (!empty($upPrice)) {
        $fields[] = "price=?";
        $params[] = $upPrice;
        $types .= 'd';
    }
    if (!empty($upStock)) {
        $fields[] = "stock=?";
        $params[] = $upStock;
        $types .= 'i';
    }
    if (!empty($upUniqcode)) {
        $fields[] = "uniqcode=?";
        $params[] = $upUniqcode;
        $types .= 's';
    }
    if (!empty($upPro)) {
        $fields[] = "production_date=?";
        $params[] = $upPro;
        $types .= 's';
    }
    if (!empty($upExp)) {
        $fields[] = "expiration_date=?";
        $params[] = $upExp;
        $types .= 's';
    }
    if (!empty($upDesc)) {
        $fields[] = "description=?";
        $params[] = $upDesc;
        $types .= 's';
    }
    if (!empty($upCategory)) {
        $fields[] = "category_id=?";
        $params[] = $upCategory;
        $types .= 'i';
    }
    if (!empty($upBrand)) {
        $fields[] = "brand_id=?";
        $params[] = $upBrand;
        $types .= 'i';
    }

    // Handle image update
    if (isset($_FILES['image'])) {
        // Direktori target
        $targetDIR = __DIR__ . '/../src/assets/images/product/';
        if (!file_exists($targetDIR)) {
            if (!mkdir($targetDIR, 0755, true)) {
                echo json_encode(['message' => 'Failed to create target directory']);
                exit;
            }
        }

        $allowed = ['png', 'jpg', 'jpeg'];
        $maxsize = 4194304; // 4MB

        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            echo json_encode(['message' => 'File type not allowed']);
            exit;
        }
        if ($file_size > $maxsize) {
            echo json_encode(['message' => 'File is too large']);
            exit;
        }

        // Hapus file lama
        $stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
        $stmt->bind_param('i', $pId);
        $stmt->execute();
        $oldImage = null;
        $stmt->bind_result($oldImage);
        $stmt->fetch();
        $stmt->close();

        if ($oldImage && file_exists(__DIR__ . '/../' . $oldImage)) {
            unlink(__DIR__ . '/../' . $oldImage);
        }

        // Upload file baru
        $new_name = time() . '_' . uniqid() . '.' . $file_ext;
        $uploadDIR = $targetDIR . $new_name;
        if (!move_uploaded_file($file_tmp, $uploadDIR)) {
            echo json_encode(['message' => 'Error while uploading the image']);
            exit;
        }
        $imgPath = 'src/assets/images/product/' . $new_name;
        $fields[] = "image=?";
        $params[] = $imgPath;
        $types .= 's';
    }

    if (count($fields) === 0) {
        echo json_encode(['message' => 'No data provided to update']);
        exit;
    }

    $params[] = $pId;
    $types .= 'i';
    $sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
    }
    $stmt->close();
}

function putAccount($conn)
{
    $accId = $_POST['accId'] ?? '';

    if (empty($accId)) {
        echo json_encode(['message' => 'Account ID is required.']);
        exit;
    }

    $accName = $_POST['username'];
    $accEmail = $_POST['email'];
    $accRole = $_POST['role'];
    $accPassword = $_POST['password'] ?? '';

    $fields = [];
    $params = [];

    if (!empty($accName)) {
        // Cek email apakah sudah digunakan oleh akun lain
        $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ? AND id != ?");
        $stmt->execute([$accEmail, $accId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email sudah digunakan oleh akun lain.']);
            exit;
        }
        $fields[] = "username = ?";
        $params[] = $accName;
    }
    if (!empty($accEmail)) {
        $fields[] = "email = ?";
        $params[] = $accEmail;
    }
    if (!empty($accRole)) {
        $fields[] = "role = ?";
        $params[] = $accRole;
    }
    if (!empty($accPassword)) {
        $fields[] = "password = ?";
        $params[] = password_hash($accPassword, PASSWORD_DEFAULT);
    }

    // Cek dan proses upload file jika ada
    $newImagePath = '';
    if (isset($_FILES['image'])) {
        $targetDIR = __DIR__ . '/../src/assets/images/profiles/';
        if (!file_exists($targetDIR)) {
            if (!mkdir($targetDIR, 0755, true)) {
                echo json_encode(['message' => 'Failed to create target directory']);
                exit;
            }
        }

        $allowed = ['png', 'jpg', 'jpeg'];
        $maxsize = 4194304; // 4 MB
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            echo json_encode(['message' => 'File type not allowed.']);
            exit;
        }

        if ($file_size > $maxsize) {
            echo json_encode(['message' => 'File too large. Max 4MB.']);
            exit;
        }

        $new_name = time() . '_' . uniqid() . '.' . $file_ext;
        $uploadDIR = $targetDIR . $new_name;

        if (!is_writable($targetDIR)) {
            echo json_encode(['message' => 'Target directory not writable.']);
            exit;
        }

        if (!move_uploaded_file($file_tmp, $uploadDIR)) {
            echo json_encode(['message' => 'Error uploading file.']);
            exit;
        }

        $newImagePath = 'src/assets/images/profiles/' . $new_name;

        // Ambil path gambar lama dari DB dan hapus
        $stmt = $conn->prepare("SELECT image FROM admin WHERE id = ?");
        $stmt->bind_param("i", $accId);
        $stmt->execute();
        $oldImage = null;
        $stmt->bind_result($oldImage);
        $stmt->fetch();
        $stmt->close();

        //
        if ($oldImage && file_exists(__DIR__ . '/../' . $oldImage)) {
            unlink(__DIR__ . '/../' . $oldImage);
        }

        // Tambahkan field image untuk update
        $fields[] = "image = ?";
        $params[] = $newImagePath;
    }

    if (empty($fields)) {
        echo json_encode(['message' => 'No data to update.']);
        exit;
    }

    $params[] = $accId;
    $sql = "UPDATE admin SET " . implode(", ", $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'message' => 'Account updated successfully.']);
}
