<?php
session_start();
session_destroy();

// Hapus cookie auth_token
setcookie("auth_token", "", time() - 3600, "/");

header("location: index.php");
exit();