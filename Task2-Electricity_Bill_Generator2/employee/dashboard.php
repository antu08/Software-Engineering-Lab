<?php include('../includes/header.php'); ?>

<div class="container2">
    <div class="card" style="text-align:center;">
        <h2>Employee Dashboard</h2>

        <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
            <a href="generate_bill.php" class="btn btn-primary">⚡ Generate New Bill</a>
            <a href="current_bills.php" class="btn btn-secondary">📜 Current Month Bills</a>
            <a href="history.php" class="btn btn-info">🔍 Search History</a>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>


<?php
include("../auth/check_login.php");
if ($_SESSION['role'] != 'employee') {
    die("Access Denied");
}
?>