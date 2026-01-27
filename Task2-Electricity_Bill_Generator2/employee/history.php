<?php
include("../config/db.php");
include("../auth/check_login.php");

if ($_SESSION['role'] != 'employee') {
    die("Access Denied");
}

$history = null;
$service_number = "";

if (isset($_GET['service_number'])) {
    $service_number = $_GET['service_number'];

    // Fetch last 10 bills
    $history = mysqli_query($conn, "SELECT * FROM bills WHERE service_number='$service_number' ORDER BY id DESC LIMIT 10");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Bill History</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container1">
        <div class="card">
            <h2>Search Bill History</h2>

            <form method="get" style="display:flex; gap:10px;">
                <input type="text" name="service_number" placeholder="Enter Service Number"
                    value="<?= $service_number ?>" required>
                <button class="btn btn-primary">Search</button>
            </form>

            <?php if ($history && mysqli_num_rows($history) > 0) { ?>
                <h3>Results for:
                    <?= $service_number ?>
                </h3>
                <table border="1" style="width:100%; margin-top:10px;">
                    <tr>
                        <th>Bill Date</th>
                        <th>Units</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($history)) { ?>
                        <tr>
                            <td>
                                <?= $row['generated_at'] ?>
                            </td>
                            <td>
                                <?= $row['units'] ?>
                            </td>
                            <td>₹
                                <?= $row['total'] ?>
                            </td>
                            <td class="<?= ($row['status'] == 'PAID') ? 'status-paid' : 'status-unpaid' ?>">
                                <?= $row['status'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } elseif ($service_number) { ?>
                <p>No records found for this Service Number.</p>
            <?php } ?>

            <br>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>