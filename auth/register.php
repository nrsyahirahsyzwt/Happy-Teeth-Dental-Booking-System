<?php
include "../config/db.php";

if(isset($_POST['register'])){
    $first = $_POST['firstname'];
    $last  = $_POST['lastname'];
    $dob   = $_POST['dateofbirth'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user  = $_POST['username'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = "Patient";

    mysqli_query($conn,"
        INSERT INTO users
        (first_name, last_name, date_of_birth, email, phone_number, username, password, role)
        VALUES
        ('$first','$last','$dob','$email','$phone','$user','$pass','$role')
    ");

    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - Happy Teeth Dental Clinic</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root{
    --pink:#FFB7B2;
    --brown:#A9746E;
    --cream:#FFF1C1;
    --green-accent:#4D995A;
    --white:#FFFFFF;
}

body{
    min-height:100vh;
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,var(--pink),var(--cream));
    display:flex;
    flex-direction:column;
}

.main-content{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.card{
    width:100%;
    max-width:420px;
    padding:30px;
    border-radius:20px;
    background:var(--white);
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

h3{
    color:var(--brown);
    font-weight:800;
}

.btn-register{
    background:var(--green-accent);
    color:white;
    font-weight:600;
}
.btn-register:hover{
    background:#3d7f4b;
}

.form-control{
    border-radius:10px;
}
.form-control:focus{
    border-color:var(--green-accent);
    box-shadow:none;
}
</style>
</head>

<body>

<div class="main-content">
    <div class="card text-center">

        <h3 class="mb-4">
            <i class="fa-solid fa-user-plus"></i><br>
            User Registration
        </h3>

        <form method="POST">

            <div class="row">
                <div class="col-md-6 mb-3 text-start">
                    <label>First Name</label>
                    <input class="form-control" name="firstname" required>
                </div>
                <div class="col-md-6 mb-3 text-start">
                    <label>Last Name</label>
                    <input class="form-control" name="lastname" required>
                </div>
            </div>

            <div class="mb-3 text-start">
                <label>Date of Birth</label>
                <input type="date" class="form-control" name="dateofbirth" required>
            </div>

            <div class="mb-3 text-start">
                <label>Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3 text-start">
                <label>Phone Number</label>
                <input class="form-control" name="phone" required>
            </div>

            <div class="mb-3 text-start">
                <label>Username</label>
                <input class="form-control" name="username" required>
            </div>

            <div class="mb-3 text-start">
                <label>Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button class="btn btn-register w-100" name="register">
                Register
            </button>
        </form>

        <p class="mt-3">
            Already registered?
            <a href="login.php" style="color:var(--green-accent)">Login</a>
        </p>

    </div>
</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>
