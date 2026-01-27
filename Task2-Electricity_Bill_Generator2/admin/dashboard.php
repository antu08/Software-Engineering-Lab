<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <div class="container1">
        <div class="card" style="text-align:center; flex-wrap:wrap;">
            <h2>Admin Control Panel</h2>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; text-align:left;">
                <a href="add_employee.php" class="btn btn-primary">➕ New Employee</a>
                <a href="register_consumer.php" class="btn btn-primary">➕ New Consumer</a>

                <a href="users.php" class="btn btn-secondary">👥 View Employees</a>
                <a href="consumers.php" class="btn btn-secondary">🏠 View Consumers</a>

                <a href="bills.php" class="btn btn-info">📜 All Bills</a>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

</body>

</html>

<?php
include("../config/db.php");
include("../auth/check_login.php");

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}
?>