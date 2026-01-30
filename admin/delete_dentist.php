<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['dentist_id'])) {

    $id = (int) $_POST['dentist_id'];

    // CHECK booking dulu
    $check = $conn->prepare(
        "SELECT COUNT(*) FROM bookings WHERE dentist_id=?"
    );
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($total);
    $check->fetch();
    $check->close();

    if ($total > 0) {
        $_SESSION['error'] =
            "❌ Dentist cannot be deleted. There are existing bookings.";
        header("Location: user_management.php");
        exit;
    }

    // DELETE kalau tiada booking
    $stmt = $conn->prepare(
        "DELETE FROM dentists WHERE dentist_id=?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: user_management.php");
exit;
