<?php
session_start();

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
    include("connection.php");
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['name'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['loggedIn'] = True;
        $_SESSION['role'] = $row['role'];
        $_SESSION['success'] = "You are now logged in";

        header('location: ../src/pages/dashboard/index.php');
        exit();
    } else {
        echo "<script>alert('Email atau password Anda salah. Silakan coba lagi!')</script>";
    }
}
