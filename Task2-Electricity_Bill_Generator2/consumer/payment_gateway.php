<?php
include("../config/db.php");
include('../includes/header.php');

$service_number = $_GET['service_number'] ?? '';
$pay_id = $_GET['pay_id'] ?? '';

if (!$service_number || !$pay_id) {
    die("Invalid Request");
}

// Fetch Bill Details
$bill = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bills WHERE id='$pay_id'"));

// Handle Payment Submission
if (isset($_POST['pay_now'])) {
    // Simulate Processing...

    // Update DB
    mysqli_query($conn, "UPDATE bills SET status='PAID' WHERE id='$pay_id'");

    // Redirect with success
    header("Location: view_bill.php?service_number=$service_number&payment_success=1");
    exit;
}
?>

<div class="container" style="max-width: 600px; margin-top: 50px;">
    <div class="card">
        <h2 class="text-center">Secure Payment Gateway</h2>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p><strong>Paying for Service No:</strong>
                <?= $service_number ?>
            </p>
            <p><strong>Bill Amount:</strong> <span style="font-size: 1.2em; color: green;">₹
                    <?= $bill['total'] ?>
                </span></p>
        </div>

        <form method="post">
            <div class="form-group">
                <label>Card Holder Name</label>
                <input type="text" name="card_name" class="form-control" placeholder="Name on Card" required>
            </div>

            <div class="form-group">
                <label>Card Number</label>
                <input type="text" name="card_number" class="form-control" placeholder="1234 5678 1234 5678"
                    maxlength="19" required>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Expiry Date</label>
                    <input type="text" name="expiry" class="form-control" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>CVV</label>
                    <input type="password" name="cvv" class="form-control" placeholder="123" maxlength="3" required>
                </div>
            </div>

            <button type="submit" name="pay_now" class="btn btn-success btn-block"
                style="width: 100%; margin-top: 20px; height: 50px; font-size: 18px;">
                Pay ₹
                <?= $bill['total'] ?>
            </button>

            <a href="view_bill.php?service_number=<?= $service_number ?>" class="btn btn-secondary"
                style="display: block; text-align: center; margin-top: 10px;">Cancel</a>
        </form>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

<?php include('../includes/footer.php'); ?>