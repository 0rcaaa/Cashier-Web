<?php
$conn = mysqli_connect("localhost","0rca","0_rcarius","kasir");
if (mysqli_connect_errno()) {
    printf("", mysqli_connect_error());
    exit(1);
}