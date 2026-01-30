<?php
include "../config/db.php";
include "../includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Patient") {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    mysqli_query($conn, "
        INSERT INTO support_tickets (user_id, subject, message)
        VALUES ($user_id, '$subject', '$message')
    ");

    $success = true;
}
?>

<div class="container mt-4">
    <h4 class="mb-3">🆘 Help Desk</h4>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            Your request has been sent. We will contact you soon.
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>

        <button class="btn btn-success w-100">
            Submit
        </button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>
