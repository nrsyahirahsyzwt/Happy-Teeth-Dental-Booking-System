<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "happy_teeth_dental");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
