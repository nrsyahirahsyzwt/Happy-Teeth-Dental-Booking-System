<?php
include "../config/db.php";
include "../includes/header.php";

$id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id=$id"
));

if(isset($_POST['save'])){
    $fname=$_POST['first_name'];
    $lname=$_POST['last_name'];
    $phone=$_POST['phone_number'];
    $email=$_POST['email'];

    mysqli_query($conn,"
        UPDATE users SET
        first_name='$fname',
        last_name='$lname',
        phone_number='$phone',
        email='$email'
        WHERE user_id=$id
    ");

    echo "<script>alert('Profile updated');</script>";
}
?>

<h3 class="page-title mb-4">My Profile</h3>

<div class="card shadow p-4 col-md-6">
<form method="POST">

    <div class="mb-3">
        <label>First Name</label>
        <input class="form-control" name="first_name"
        value="<?= $user['first_name'] ?>">
    </div>

    <div class="mb-3">
        <label>Last Name</label>
        <input class="form-control" name="last_name"
        value="<?= $user['last_name'] ?>">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input class="form-control" name="phone_number"
        value="<?= $user['phone_number'] ?>">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input class="form-control" name="email"
        value="<?= $user['email'] ?>">
    </div>

    <button class="btn btn-success w-100" name="save">
        Save Profile
    </button>

</form>
</div>

<?php include "../includes/footer.php"; ?>
