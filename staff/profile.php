<?php
include "../middleware/role_staff.php";
include "../config/db.php";
include "../includes/header.php";

$staff_id = (int) $_SESSION['user_id'];

$staff = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE user_id=$staff_id"
));

/* UPDATE PROFILE */
if(isset($_POST['save'])){
    $first = $_POST['first_name'];
    $last  = $_POST['last_name'];
    $phone = $_POST['phone'];

    // PHOTO UPLOAD
    if(!empty($_FILES['photo']['name'])){
        $photo = time()."_".$_FILES['photo']['name'];
        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            "../uploads/staff/".$photo
        );

        mysqli_query($conn,"
            UPDATE users SET
            first_name='$first',
            last_name='$last',
            phone='$phone',
            photo='$photo'
            WHERE user_id=$staff_id
        ");
    }else{
        mysqli_query($conn,"
            UPDATE users SET
            first_name='$first',
            last_name='$last',
            phone='$phone'
            WHERE user_id=$staff_id
        ");
    }

    header("Location: profile.php");
}
?>

<div class="container px-4">
<h3 class="fw-bold mb-4">My Profile</h3>

<div class="row">
    <div class="col-md-4 text-center">
        <img src="../uploads/staff/<?= $staff['photo'] ?? 'default.png' ?>"
             class="rounded-circle mb-3"
             width="150" height="150"
             style="object-fit:cover">

        <h5><?= $staff['first_name']." ".$staff['last_name'] ?></h5>
        <small class="text-muted">Staff</small>
    </div>

    <div class="col-md-8">
        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>First Name</label>
                <input class="form-control" name="first_name"
                       value="<?= $staff['first_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Last Name</label>
                <input class="form-control" name="last_name"
                       value="<?= $staff['last_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Phone</label>
                <input class="form-control" name="phone"
                       value="<?= $staff['phone'] ?>">
            </div>

            <div class="mb-3">
                <label>Profile Photo</label>
                <input type="file" class="form-control" name="photo">
            </div>

            <button class="btn btn-success" name="save">
                <i class="fa fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>
</div>

<?php include "../includes/footer.php"; ?>
