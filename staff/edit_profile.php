<?php
session_start();
include "../config/db.php";
include "../includes/header.php";
include "../includes/staff_sidebar.php";

/* ================= AUTH ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Staff") {
    header("Location: ../auth/login.php");
    exit;
}

$staff_id = (int) $_SESSION['user_id'];
$uploadDir = "../uploads/staff/";

/* ================= UPDATE PROFILE ================= */
if (isset($_POST['update'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone_number']);

    $photoName = null;

    /* IMAGE UPLOAD */
    if (!empty($_FILES['photo']['name'])) {

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $photoName = "staff_{$staff_id}_" . time() . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);
        }
    }

    /* UPDATE QUERY */
    if ($photoName) {
        $stmt = $conn->prepare("
            UPDATE users SET
                first_name=?,
                last_name=?,
                username=?,
                email=?,
                phone_number=?,
                photo=?
            WHERE user_id=?
        ");
        $stmt->bind_param("ssssssi",
            $first_name, $last_name, $username, $email, $phone, $photoName, $staff_id
        );
    } else {
        $stmt = $conn->prepare("
            UPDATE users SET
                first_name=?,
                last_name=?,
                username=?,
                email=?,
                phone_number=?
            WHERE user_id=?
        ");
        $stmt->bind_param("sssssi",
            $first_name, $last_name, $username, $email, $phone, $staff_id
        );
    }

    $stmt->execute();
    header("Location: edit_profile.php");
    exit;
}

/* ================= STAFF DATA ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=? LIMIT 1");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();

/* ================= PROFILE IMAGE ================= */
if (!empty($staff['photo']) && file_exists($uploadDir . $staff['photo'])) {
    $photo = $uploadDir . $staff['photo'];
} else {
    $photo = "https://ui-avatars.com/api/?name="
        . urlencode($staff['first_name']." ".$staff['last_name'])
        . "&background=4D995A&color=fff&size=200";
}
?>

<style>
body{ background:#FFF1C1; }
.edit-card{ border-radius:22px; }
.profile-pic{
    width:120px;height:120px;
    object-fit:cover;
    border-radius:50%;
    border:4px solid #4D995A;
}
</style>

<div class="container my-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow p-4 edit-card">
<h4 class="fw-bold text-center mb-4">
    <i class="fa fa-user-gear"></i> Edit Staff Profile
</h4>

<div class="text-center mb-4">
    <img src="<?= $photo ?>" class="profile-pic mb-2">
    <div class="small text-muted">Clinic Staff</div>
</div>

<form method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label class="form-label">Profile Photo</label>
        <input type="file" name="photo" class="form-control">
    </div>

    <div class="mb-3">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control"
               value="<?= htmlspecialchars($staff['first_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control"
               value="<?= htmlspecialchars($staff['last_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control"
               value="<?= htmlspecialchars($staff['username']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($staff['email']) ?>" required>
    </div>

    <div class="mb-4">
        <label>Phone</label>
        <input type="text" name="phone_number" class="form-control"
               value="<?= htmlspecialchars($staff['phone_number']) ?>" required>
    </div>

    <div class="d-grid gap-2">
        <button name="update" class="btn btn-success">
            <i class="fa fa-save"></i> Save Changes
        </button>
    </div>

</form>
</div>

</div>
</div>
</div>

<?php include "../includes/footer.php"; ?>
