<?php
include("../config/db.php");

$message = "";
$currentBill = null;
$historyBills = null;

if (isset($_POST['meter'])) {
    $meter = $_POST['meter'];
}

/* Handle payment */
if (isset($_GET['pay_id'])) {
    $id = $_GET['pay_id'];
    mysqli_query($conn, "UPDATE bills SET status='PAID' WHERE id='$id'");
    $message = "✅ Bill paid successfully.";
}

/* Handle receipt */
if (isset($_GET['receipt'])) {
    $message = "✅ Receipt downloaded successfully.";
}

/* Fetch bills if meter provided */
if (isset($meter)) {

    // Current month bill (latest)
    $currentBill = mysqli_fetch_assoc(
        mysqli_query($conn,
            "SELECT * FROM bills 
             WHERE meter_id='$meter' 
             ORDER BY id DESC LIMIT 1")
    );

    // Last 6 months history (excluding latest)
    $historyBills = mysqli_query($conn,
        "SELECT * FROM bills 
         WHERE meter_id='$meter' 
         ORDER BY id DESC 
         LIMIT 6 OFFSET 1");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bill</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <div class="card">
        <h2>Consumer Bill Portal</h2>

        <form method="post">
            <input name="meter" placeholder="Enter Meter ID" required>
            <button class="btn btn-primary">View Bill</button>
        </form>

        <?php if ($message) { ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php } ?>
    </div>

    <?php if ($currentBill) { ?>

    <!-- ================= CURRENT MONTH BILL ================= -->
    <div class="card">
        <h3>Current Month Bill</h3>

        <table>
            <tr><td>Units Consumed (kWh)</td><td><?= $currentBill['units'] ?></td></tr>
            
            <tr><td>Fixed Charges</td><td>₹ <?= $currentBill['fixed_charge'] ?></td></tr>
            <tr><td>Customer Charges</td><td>₹ <?= $currentBill['customer_charge'] ?></td></tr>
            
            <tr><td>Surcharge</td><td>₹ <?= $currentBill['surcharge'] ?></td></tr>
            <tr><td>Fine</td><td>₹ <?= $currentBill['fine'] ?></td></tr>
            <tr style="font-weight:bold;">
                <td>Total Amount</td>
                <td>₹ <?= ($currentBill['status']=='PAID') ? '0 (No Due)' : $currentBill['total'] ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="<?= ($currentBill['status']=='PAID')?'status-paid':'status-unpaid' ?>">
                    <?= $currentBill['status'] ?>
                </td>
            </tr>
            <tr>
                <td>Due Date</td>
                <td><?= $currentBill['due_date'] ?></td>
            </tr>
        </table>

        <br>

        <?php if ($currentBill['status'] == 'UNPAID') { ?>
            <a href="?pay_id=<?= $currentBill['id'] ?>&meter=<?= $meter ?>" 
               class="btn btn-success">
               Pay Bill
            </a> <br> <br>
        <?php } ?>

        <a href="?receipt=1&meter=<?= $meter ?>" class="btn btn-primary">
            Download Receipt
        </a>
    </div>

    <!-- ================= BILL HISTORY ================= -->
    <div class="card">
        <h3>Past Bills (Last 6 Months)</h3>

        <table>
            <tr>
                <th>Month</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>

            <?php while ($b = mysqli_fetch_assoc($historyBills)) { ?>
            <tr>
                <td><?= $b['due_date'] ?></td>
                <td>₹ <?= $b['total'] ?></td>
                <td><?= $b['due_date'] ?></td>
                <td class="<?= ($b['status']=='PAID')?'status-paid':'status-unpaid' ?>">
                    <?= $b['status'] ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <?php } ?>

</div>

</body>
</html>
