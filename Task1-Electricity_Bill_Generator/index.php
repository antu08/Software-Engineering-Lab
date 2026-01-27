<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TGSPDCL Electricity Bill System</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <div class="card" style="text-align:center;">

        <img src="assets/images/tgspdcl_logo.png" alt="TGSPDCL Logo" height="120" width="530">

        <h1>TGSPDCL Electricity Bill System</h1>
        <p>
            Telangana State Power Distribution Company Limited
        </p>

    </div>

    <div class="card">
        <style>
        h2 {
            text-align: center;
            }
        </style>

        <h2>Select Your Role</h2>

        <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">

            <a href="auth/admin_login.php" class="btn btn-primary">
                Admin Login
            </a>

            <a href="auth/employee_login.php" class="btn btn-success">
                Employee Login
            </a>

            <a href="consumer/view_bill.php" class="btn btn-warning">
                Consumer Access
            </a>

        </div>

    </div>

    <div class="footer">
        © 2026 TGSPDCL | Electricity Bill Management System
    </div>

</div>

</body>
</html>
