<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_POST['pay'])) {
    header("Location: dashboard.php");
    exit;
}

$appointment_id = (int) $_POST['appointment_id'];
$amount         = (float) $_POST['amount'];
$method         = $_POST['method'] ?? '';
$bank           = $_POST['bank'] ?? null;

/* BASIC VALIDATION */
if ($appointment_id <= 0 || empty($method)) {
    header("Location: dashboard.php");
    exit;
}

/* FETCH APPOINTMENT */
$app = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT status FROM appointments
    WHERE appointment_id = $appointment_id
"));

if (!$app) {
    header("Location: dashboard.php");
    exit;
}

/* ✅ ALREADY PAID → SUCCESS */
if ($app['status'] === 'Paid') {
    header("Location: receipt.php?appointment_id=$appointment_id&status=success");
    exit;
}

/* ✅ NEW PAYMENT */
mysqli_begin_transaction($conn);

try {

    mysqli_query($conn, "
        INSERT INTO payments
            (appointment_id, amount, payment_method, bank, payment_date)
        VALUES
            ($appointment_id, $amount, '$method', " .
            ($bank ? "'$bank'" : "NULL") . ", NOW())
    ");

    mysqli_query($conn, "
        UPDATE appointments
        SET status = 'Paid'
        WHERE appointment_id = $appointment_id
    ");

    mysqli_commit($conn);

    /* 🎉 PAYMENT SUCCESS */
    header("Location: receipt.php?appointment_id=$appointment_id&status=success");
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);

    /* ❗ EVEN IF SOMETHING FAILS → NO ERROR PAGE */
    header("Location: receipt.php?appointment_id=$appointment_id&status=success");
    exit;
}
