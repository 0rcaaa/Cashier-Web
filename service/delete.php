<?php
include './connection.php';
session_start();
if(isset($_GET['type']) && isset($_GET['id'])){
    $type = $_GET['type'];
    $id = $_GET['id'];
    $sql = "DELETE FROM $type WHERE id= $id";
    $conn->query($sql);

    if($conn->affected_rows > 0){
        echo "<script>alert('Data berhasil dihapus'); window.history.back();</script>";
    } else {
        echo "<script>alert('Data gagal dihapus'); window.history.back();</script>";
    }
} else{
    echo "<script>alert('invalid request'); window.history.back();</script>";
}