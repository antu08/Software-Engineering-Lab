
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/validate.js"></script>
</head>
<body>
    <div class="container1">
        <div class="card" style="text-align:center; flex-wrap:wrap;">
            <h2>Employee Dashboard</h2>

            <a href="register_consumer.php">Register New Consumer</a><br><br>
            <a href="generate_bill.php">Generate Bill</a><br><br>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

</body>
</html>

<?php
include("../auth/check_login.php");
if($_SESSION['role']!='employee'){ die("Access Denied"); }
?>
