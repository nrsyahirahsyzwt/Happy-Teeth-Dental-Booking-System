<?php
session_start();
include "../config/db.php";

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $dbPassword = $user['password'];

        // ✅ SUPPORT HASHED + OLD PLAIN PASSWORD
        if (
            password_verify($password, $dbPassword) ||
            $password === $dbPassword
        ) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role']    = trim($user['role']);

            // 🔁 NORMALISE ROLE (avoid casing issues)
            $role = strtolower($_SESSION['role']);

            if ($role === 'patient') {
                header("Location: ../patient/dashboard.php");
            } elseif ($role === 'staff') {
                header("Location: ../staff/dashboard.php");
            } elseif ($role === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                $error = "Invalid user role";
            }
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Happy Teeth Dental Clinic Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root{
    --pink: #FFB7B2;
    --brown: #A9746E;
    --cream: #FFF1C1;
    --green-accent: #4D995A;
    --white: #FFFFFF;
}
html, body{ height:100%; }
body{
    font-family:'Segoe UI', sans-serif;
    background: var(--cream);
    display:flex;
    flex-direction:column;
}
.main-wrapper{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
}
.card{
    width:100%;
    max-width:420px;
    padding:30px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
    background: var(--white);
}
h3{
    font-weight:800;
    color: var(--brown);
}
.btn-login{
    background: var(--green-accent);
    color:white;
    border:none;
    font-weight:600;
}
.btn-login:hover{
    background:#3d9950;
}
input.form-control{
    border-radius:10px;
}
input.form-control:focus{
    border-color: var(--green-accent);
    box-shadow:none;
}
footer{
    background: linear-gradient(135deg, #4D995A, #2f6b3a);
    color:white;
    text-align:center;
    padding:15px 0;
    font-size:0.9rem;
}
</style>
</head>

<body>

<div class="main-wrapper">
    <div class="card text-center">
        <h3 class="mb-4">
            <i class="fa-solid fa-tooth fa-lg"></i><br>
            Happy Teeth Dental Clinic Login
        </h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3 text-start">
                <label>Username</label>
                <input class="form-control" name="username" required>
            </div>

            <div class="mb-3 text-start">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button class="btn btn-login w-100 mb-3" name="login">
                Login
            </button>
        </form>

        <p class="mb-0">
            New User?
            <a href="register.php" style="color: var(--green-accent)">
                Register Account Here
            </a>
        </p>
    </div>
</div>

<footer>
    © <?= date("Y") ?> Happy Teeth Dental Clinic • All Rights Reserved
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
