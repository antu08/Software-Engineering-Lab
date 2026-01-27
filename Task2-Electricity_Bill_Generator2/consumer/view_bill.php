<?php
include("../config/db.php");

$message = "";
$currentBill = null;
$historyBills = null;
$service_number = "";

if (isset($_POST['service_number'])) {
    $service_number = trim($_POST['service_number']);
} elseif (isset($_GET['service_number'])) { // Support GET too for redirects
    $service_number = trim($_GET['service_number']);
}

/* Handle payment */
if (isset($_GET['pay_id'])) {
    $id = $_GET['pay_id'];
    mysqli_query($conn, "UPDATE bills SET status='PAID' WHERE id='$id'");
    $message = "✅ Bill paid successfully.";
}

/* Handle Payment Success from Gateway */
if (isset($_GET['payment_success'])) {
    $message = "✅ Payment Successful! Receipt generated.";
}

/* Fetch bills if service_number provided */
if ($service_number) {

    // Current month bill (latest)
    $currentBill = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM bills 
             WHERE service_number='$service_number' 
             ORDER BY id DESC LIMIT 1"
        )
    );

    // Fetch Consumer Details
    $consumerDetails = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM consumers WHERE service_number='$service_number'"
        )
    );

    // Last 6 months history (excluding latest)
    $historyBills = mysqli_query(
        $conn,
        "SELECT * FROM bills 
         WHERE service_number='$service_number' 
         ORDER BY id DESC 
         LIMIT 6 OFFSET 1"
    );
}
?>

<!DOCTYPE html>
<?php include('../includes/header.php'); ?>

<div class="container">

    <div class="card">
        <h2>Consumer Bill Portal</h2>

        <form method="post" style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;">
            <div style="flex:1; min-width:200px;">
                <label>Service Number</label>
                <input name="service_number" placeholder="Enter Service Number"
                    value="<?= htmlspecialchars($service_number) ?>" required style="margin-bottom:0;">
            </div>
            <button class="btn btn-primary" style="width: auto; height: 48px;">View Bill</button>
        </form>

        <?php if ($message) { ?>
            <div class="alert alert-success" style="margin-top:20px;"><?= $message ?></div>
        <?php } ?>
    </div>

    <?php if ($currentBill) { ?>

        <!-- ================= CURRENT MONTH BILL ================= -->
        <div class="card">
            <h3>Current Month Bill</h3>

            <div class="bill-box">
                <div class="bill-header">
                    <h4>Bill Details (Service No: <?= $service_number ?>)</h4>
                    <span>Due Date: <strong><?= $currentBill['due_date'] ?></strong></span>
                </div>

                <div class="bill-row"><span>Units Consumed</span> <span><?= $currentBill['units'] ?> kWh</span></div>
                <div class="bill-row"><span>Fixed Charges</span> <span>₹ <?= $currentBill['fixed_charge'] ?></span></div>
                <div class="bill-row"><span>Customer Charges</span> <span>₹ <?= $currentBill['customer_charge'] ?></span>
                </div>
                <div class="bill-row"><span>Energy Charges</span> <span>₹ <?= $currentBill['energy_charge'] ?></span></div>
                <div class="bill-row"><span>Electricity Duty</span> <span>₹ <?= $currentBill['ed'] ?></span></div>
                <div class="bill-row"><span>Surcharge</span> <span>₹ <?= $currentBill['surcharge'] ?></span></div>
                <?php if ($currentBill['fine'] > 0): ?>
                    <div class="bill-row text-danger"><span>Fine (Overdue)</span> <span>₹ <?= $currentBill['fine'] ?></span>
                    </div>
                <?php endif; ?>

                <div class="bill-total">
                    Total Payable: ₹ <?= ($currentBill['status'] == 'PAID') ? '0 (PAID)' : $currentBill['total'] ?>
                </div>

                <div style="text-align:right; margin-top:10px;"
                    class="<?= ($currentBill['status'] == 'PAID') ? 'text-success' : 'text-danger' ?>">
                    Status: <?= $currentBill['status'] ?>
                </div>
            </div>

            <div style="margin-top:20px; display:flex; gap:15px; flex-wrap:wrap;">
                <?php if ($currentBill['status'] == 'UNPAID') { ?>
                    <!-- Link to Payment Page -->
                    <a href="payment_gateway.php?pay_id=<?= $currentBill['id'] ?>&service_number=<?= $service_number ?>"
                        class="btn btn-success" style="width:auto;">
                        Pay Now
                    </a>
                <?php } ?>

                <!-- Link to Receipt Preview Page -->
                <a href="receipt_preview.php?service_number=<?= $service_number ?>" target="_blank" class="btn btn-primary"
                    style="width:auto;">
                    Download Receipt
                </a>
            </div>
        </div>

        <!-- ================= BILL HISTORY ================= -->
        <div class="card">
            <h3>Past Bills (Last 6 Months)</h3>

            <div class="table-container">
                <table>
                    <tr>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th>Units</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>

                    <?php
                    if ($historyBills) {
                        while ($b = mysqli_fetch_assoc($historyBills)) {
                            ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($b['generated_at'])) ?></td>
                                <td><?= $b['due_date'] ?></td>
                                <td><?= $b['units'] ?></td>
                                <td>₹ <?= $b['total'] ?></td>
                                <td class="<?= ($b['status'] == 'PAID') ? 'status-paid' : 'status-unpaid' ?>">
                                    <?= $b['status'] ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

    <?php } elseif ($service_number) { ?>
        <div class="card">
            <p>No bills found for Service Number: <strong><?= htmlspecialchars($service_number) ?></strong> or Invalid
                Number.</p>
        </div>
    <?php } ?>

</div>

<?php include('../includes/footer.php'); ?>