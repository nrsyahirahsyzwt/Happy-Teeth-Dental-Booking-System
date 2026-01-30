<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logged Out</title>
    <meta http-equiv="refresh" content="3;url=login.php">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body{
            background:#FFF1C1;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }
        .logout-card{
            background:white;
            padding:40px;
            border-radius:22px;
            text-align:center;
            box-shadow:0 20px 40px rgba(0,0,0,.15);
        }
    </style>
</head>
<body>

<div class="logout-card">
    <h3 class="text-success fw-bold mb-3">
        👋 Logged Out Successfully
    </h3>

    <p class="mb-2">
        Thank you for using<br>
        <b>Happy Teeth Dental Clinic Booking System</b> 🦷
    </p>

    <small class="text-muted">
        Redirecting to login page...
    </small>
</div>

</body>
</html>
