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

            <a href="users.php">View All Users</a><br><br>
            <a href="consumers.php">View Consumers</a><br><br>
            <a href="bills.php">All Bills</a><br><br>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

</body>
</html>

<?php
include("../config/db.php");
include("../auth/check_login.php");

if($_SESSION['role']!='admin'){ die("Access Denied"); }
?>
