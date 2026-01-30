<?php
session_start();
include "../config/db.php";
include "../includes/header.php";
include "../includes/admin_sidebar.php";

/* ================= SECURITY ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "Admin") {
    header("Location: ../auth/login.php");
    exit;
}

$adminId = $_SESSION['user_id'];

/* ================= STAFF & ADMIN ================= */
$staffs = mysqli_query($conn, "
    SELECT user_id, first_name, last_name, email, role
    FROM users
    WHERE role IN ('Admin','Staff')
    ORDER BY role, first_name
");

/* ================= PATIENT ================= */
$patients = mysqli_query($conn, "
    SELECT user_id, first_name, last_name, email, gender
    FROM users
    WHERE role='Patient'
    ORDER BY first_name
");

/* ================= DENTISTS ================= */
$dentists = mysqli_query($conn, "
    SELECT dentist_id, fullname, email
    FROM dentists
    ORDER BY fullname
");
?>

<style>
.section-card{
    background:#fff;
    border-radius:22px;
    padding:24px;
    box-shadow:0 8px 22px rgba(0,0,0,.08);
    margin-bottom:30px;
}
.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:16px;
}
.section-header h5{
    margin:0;
    font-weight:700;
}
.action-btns a,
.action-btns button{
    margin-right:6px;
}
</style>

<div class="main-content p-4">

<h4 class="fw-bold mb-4">
    <i class="bi bi-people-fill me-2"></i> User Management
</h4>

<!-- ================= STAFF & ADMIN ================= -->
<div class="section-card">
    <div class="section-header">
        <h5><i class="bi bi-person-badge-fill me-2"></i>Staff & Admin</h5>
        <a href="add_staff.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Staff
        </a>
    </div>

    <table class="table align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th width="160">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while($s = mysqli_fetch_assoc($staffs)): ?>
        <tr>
            <td><?= htmlspecialchars($s['first_name']." ".$s['last_name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td>
                <span class="badge <?= $s['role']=='Admin'?'bg-danger':'bg-primary' ?>">
                    <?= $s['role'] ?>
                </span>
            </td>
            <td class="action-btns">

                <a href="edit_user.php?id=<?= $s['user_id'] ?>"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>

                <?php if($s['user_id'] != $adminId): ?>
                <form action="delete_user.php" method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Delete this user?')">
                    <input type="hidden" name="user_id"
                           value="<?= $s['user_id'] ?>">
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                <?php else: ?>
                <button class="btn btn-secondary btn-sm" disabled>
                    <i class="bi bi-lock-fill"></i>
                </button>
                <?php endif; ?>

            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- ================= PATIENT ================= -->
<div class="section-card">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-person-lines-fill me-2"></i> Patients
    </h5>

    <table class="table align-middle">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th width="160">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while($p = mysqli_fetch_assoc($patients)): ?>
        <tr>
            <td><?= htmlspecialchars($p['first_name']." ".$p['last_name']) ?></td>
            <td><?= htmlspecialchars($p['email']) ?></td>
            <td><?= htmlspecialchars($p['gender']) ?></td>
            <td class="action-btns">

                <a href="edit_user.php?id=<?= $p['user_id'] ?>"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>

                <form action="delete_user.php" method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Delete this patient?')">
                    <input type="hidden" name="user_id"
                           value="<?= $p['user_id'] ?>">
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- ================= DENTISTS ================= -->
<div class="section-card">
    <div class="section-header">
        <h5><i class="bi bi-heart-pulse-fill me-2"></i>Dentists</h5>
        <a href="add_dentist.php" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Add Dentist
        </a>
    </div>

    <small class="text-muted d-block mb-2">
        Dentists do not log into the system
    </small>

    <table class="table align-middle">
        <thead class="table-light">
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th width="160">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while($d = mysqli_fetch_assoc($dentists)): ?>
        <tr>
            <td><?= htmlspecialchars($d['fullname']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td class="action-btns">

                <a href="edit_dentist.php?id=<?= $d['dentist_id'] ?>"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                </a>

                <form action="delete_dentist.php" method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Delete this dentist?')">
                    <input type="hidden" name="dentist_id"
                           value="<?= $d['dentist_id'] ?>">
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div>

<?php include "../includes/footer.php"; ?>
