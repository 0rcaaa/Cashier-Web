<?php
session_start();
include("utility.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    switch ($action) {
        case 'login':
            login();
            break;
        case 'addProduct':
            add_product($conn);
            break;

        default:
            header('location: ../src/pages/auth/index.php');
            exit;
    }
}

function login()
{
    include("connection.php");
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
                $_SESSION['error'] = "password salah";
            }
        } else {
            $_SESSION['error'] = "email tidak ditemukan";
        }
        header('location: ../src/pages/auth/index.php');
        exit();
    }
}

function add_product($conn)
{

    // Debugging: Echo all submitted data
    echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    echo '</pre>';

    $targetDIR = __DIR__ . '/../src/assets/images/product/';
    if (!file_exists($targetDIR)) {
        mkdir($targetDIR, 0777, true);
    }
    echo $targetDIR;
    // Check if the file is an image
    $allowed = ['png', 'jpg', 'jpeg']; // Allowed file extensions
    $maxsize = 4194304; // 4 MB in bytes

    $file_name = $_FILES['image']['name']; // Get the name of the file (including file extension)
    $file_size = $_FILES['image']['size']; // Get the size of the file
    $file_tmp = $_FILES['image']['tmp_name']; // Get the temporary file path
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Get the file extension

    if (!in_array($file_ext, $allowed)) {
        $_SESSION['error'] = "File type is not allowed. Please upload a png, jpg, or jpeg file instead.";
        header('location: ../src/pages/dashboard/add_product.php');
        exit();
    }

    if ($file_size > $maxsize) {
        $_SESSION['error'] = "File is too large. File size should not exceed 4MB.";
        header('location: ../src/pages/dashboard/add_product.php');
        exit();
    }

    // Generate a unique name for the image
    $new_name = time() . '_' . uniqid() . '.' . $file_ext;
    $uploadDIR = $targetDIR . $new_name;

    if (!move_uploaded_file($file_tmp, $uploadDIR)) {
        die("Error while uploading the image");
    }

    // Insert the product into the database
    $name = $_POST['productName'];
    $price = $_POST['price'];
    $margin = $_POST['margin'];
    $stock = $_POST['stock'];
    $fid_category = $_POST['kategori'];
    $description = $_POST['Detail'];


    $stmt = $conn->prepare("INSERT INTO products
        (name, price, margin, stock, category_id, description, image, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sddiiss", $name, $price, $margin, $stock, $fid_category, $description, $uploadDIR);
    $stmt->execute();

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully";
    } else {
        $_SESSION['error'] = 'error' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header('location: ../src/pages/dashboard/add_product.php');


}
