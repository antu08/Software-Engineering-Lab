<?php
include("../config/db.php");
include("../auth/check_login.php");

if ($_SESSION['role'] != 'employee') {
    die("Access Denied");
}

// Filter for current month
$currentMonth = date('Y-m');
$result = mysqli_query($conn, "SELECT * FROM bills WHERE generated_at LIKE '$currentMonth%' ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Current Month Bills</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }
    </style>
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container1">
        <div class="card" style="max-width: 900px;">
            <h2>Bills Generated This Month (
                <?= date('M Y') ?>)
            </h2>

            <?php if (mysqli_num_rows($result) > 0) { ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Service No</th>
                        <th>Units</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>
                                <?= $row['id'] ?>
                            </td>
                            <td>
                                <?= $row['service_number'] ?>
                            </td>
                            <td>
                                <?= $row['units'] ?>
                            </td>
                            <td>₹
                                <?= $row['total'] ?>
                            </td>
                            <td>
                                <?= date('d M Y, h:i A', strtotime($row['generated_at'])) ?>
                            </td>
                            <td class="<?= ($row['status'] == 'PAID') ? 'status-paid' : 'status-unpaid' ?>">
                                <?= $row['status'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No bills generated this month yet.</p>
            <?php } ?>

            <br>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>