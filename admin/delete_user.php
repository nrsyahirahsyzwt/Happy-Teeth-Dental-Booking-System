<?php
session_start();
include "../config/db.php";

/* ========== ADMIN ONLY ========== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../auth/login.php");
    exit;
}

/* ========== POST ONLY ========== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_management.php");
    exit;
}

$user_id  = (int) ($_POST['user_id'] ?? 0);
$admin_id = (int) $_SESSION['user_id'];

if ($user_id <= 0) {
    header("Location: user_management.php?error=invalid_id");
    exit;
}

/* ========== PREVENT SELF DELETE ========== */
if ($user_id === $admin_id) {
    header("Location: user_management.php?error=self_delete");
    exit;
}

mysqli_begin_transaction($conn);

try {
    /* DELETE APPOINTMENTS FIRST */
    $stmt1 = mysqli_prepare($conn, "DELETE FROM appointments WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt1, "i", $user_id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    /* DELETE USER */
    $stmt2 = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $user_id);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    mysqli_commit($conn);

    header("Location: user_management.php?deleted=1");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    header("Location: user_management.php?error=delete_failed");
    exit;
}
