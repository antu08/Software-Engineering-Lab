<?php include('includes/header.php'); ?>

<div class="container">

    <div class="card" style="text-align:center;">
        <!-- Logo might need path adjustment in header vs here, but header logic handles it if passed correct context -->
        <!-- Actually header has logic. Here we just put content -->
        <img src="assets/images/tgspdcl_logo.png" alt="TGSPDCL Logo"
            style="max-height: 120px; width: auto; margin-bottom: 20px;">

        <h1>Electricity Bill System</h1>
        <p class="text-secondary">
            Telangana State Power Distribution Company Limited
        </p>
    </div>

    <div class="card">
        <h2 class="text-center">Select Your Role</h2>

        <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap; margin-top: 30px;">

            <a href="auth/admin_login.php" class="btn btn-primary" style="width: auto;">
                Admin Login
            </a>

            <a href="auth/employee_login.php" class="btn btn-secondary" style="width: auto;">
                Employee Login
            </a>

            <a href="consumer/view_bill.php" class="btn btn-warning"
                style="width: auto; background-color: var(--accent); color: white;">
                Consumer Access
            </a>

        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>