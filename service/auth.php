<?php
session_start();
include ("utility.php");

if($_SERVER['REQUEST_METHOD']=='GET'){
    header('index.php');
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $type = $_POST['type'];
    switch($type){
        case 'login':
            login();
            break;
            
            default:
            header('location: ../src/pages/auth/index.php');
            exit;
        }
    }
    
    function login(){
        include ("connection.php");
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
    
