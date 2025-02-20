<?php
$conn = mysqli_connect("localhost","root","","kasir");
if (mysqli_connect_errno()) {
    printf("", mysqli_connect_error());
    exit(1);
}