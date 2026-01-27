<?php
include("../config/db.php");
include("../auth/check_login.php");
include("../utils/bill_calculator.php");

if ($_SESSION['role'] != 'employee') {
    die("Access Denied");
}

$step = 1;
$error = "";
$details = [];
$prev_unit = 0;

if (isset($_POST['search'])) {
    $service_number = trim($_POST['service_number']);

    // Fetch Consumer Details
    $query = mysqli_query($conn, "SELECT * FROM consumers WHERE service_number='$service_number'");
    if (mysqli_num_rows($query) > 0) {
        $details = mysqli_fetch_assoc($query);
        $step = 2;

        // Fetch Previous Reading
        $prevQuery = mysqli_query($conn, "SELECT curr_unit FROM meter_readings WHERE service_number='$service_number' ORDER BY id DESC LIMIT 1");
        if ($prev = mysqli_fetch_assoc($prevQuery)) {
            $prev_unit = (int) $prev['curr_unit'];
        }
    } else {
        $error = "❌ Invalid Service Number";
    }
}

if (isset($_POST['generate'])) {
    $service_number = $_POST['service_number'];
    $current = (int) $_POST['current'];
    $prev_unit = (int) $_POST['prev'];
    $type = $_POST['type'];

    if ($current < $prev_unit) {
        $error = "❌ Current reading cannot be less than previous reading ($prev_unit)";
        $step = 2; // Stay on step 2
        // Re-fetch details needed for Step 2 display
        $query = mysqli_query($conn, "SELECT * FROM consumers WHERE service_number='$service_number'");
        $details = mysqli_fetch_assoc($query);
    } else {
        $units = $current - $prev_unit;

        // Check Previous Due
        $dueQuery = mysqli_query($conn, "SELECT id FROM bills WHERE service_number='$service_number' AND status='UNPAID'");
        $hasPreviousDue = mysqli_num_rows($dueQuery) > 0;

        // Calculate Bill
        $billData = calculateBill($type, $units, $hasPreviousDue);

        $total = $billData['total'];
        $energy_charge = $billData['energy_charge'];
        $fixed_charge = $billData['fixed_charge'];
        $customer_charge = $billData['customer_charge'];
        $ed = $billData['ed'];
        $surcharge = $billData['surcharge'];
        $fine = $billData['fine'];

        // Due date (2 Weeks)
        $due_date = date('Y-m-d', strtotime('+14 days'));

        // Insert Bill
        // Using service_number column instead of meter_id
        $sql = "INSERT INTO bills 
                (service_number, units, energy_charge, fixed_charge, customer_charge, ed, surcharge, fine, total, due_date, status)
                VALUES 
                ('$service_number', '$units', '$energy_charge', '$fixed_charge', '$customer_charge', '$ed', '$surcharge', '$fine', '$total', '$due_date', 'UNPAID')";

        if (mysqli_query($conn, $sql)) {
            // Insert Meter Reading
            mysqli_query($conn, "INSERT INTO meter_readings 
                                 (service_number, prev_unit, curr_unit, units_used, month_year)
                                 VALUES 
                                 ('$service_number', '$prev_unit', '$current', '$units', DATE_FORMAT(NOW(), '%M %Y'))");

            // Redirect to Success/Summary
            // Could redirect to receipt preview or back to dashboard
            echo "<script>alert('✅ Bill Generated Successfully!'); window.location='current_bills.php';</script>";
            exit;
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Generate Bill</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container1">
        <div class="card" style="max-width: 600px;">
            <h2>Generate Electricity Bill</h2>
            <?php if ($error)
                echo "<div class='alert' style='color:red;'>$error</div>"; ?>

            <?php if ($step == 1) { ?>
                <!-- STEP 1: Search Service Number -->
                <form method="post">
                    <label>Enter Service Number</label>
                    <input type="text" name="service_number" placeholder="e.g. 001234" required>
                    <button type="submit" name="search" class="btn btn-primary">Next</button>
                    <br><br>
                    <a href="dashboard.php">Cancel</a>
                </form>
            <?php } elseif ($step == 2) { ?>
                <!-- STEP 2: Enter Reading -->
                <div style="background:#f9f9f9; padding:15px; border-radius:5px; margin-bottom:15px;">
                    <h3>Consumer Details</h3>
                    <p><strong>Name:</strong> <?= $details['name'] ?></p>
                    <p><strong>Service No:</strong> <?= $details['service_number'] ?></p>
                    <p><strong>Category:</strong> <?= ucfirst($details['type']) ?></p>
                    <p><strong>Address:</strong> <?= $details['address'] ?></p>
                    <hr>
                    <p><strong>Previous Reading:</strong> <span
                            style="font-size:1.2em; color:blue;"><?= $prev_unit ?></span> Units</p>
                </div>

                <form method="post">
                    <input type="hidden" name="service_number" value="<?= $details['service_number'] ?>">
                    <input type="hidden" name="prev" value="<?= $prev_unit ?>">
                    <input type="hidden" name="type" value="<?= $details['type'] ?>">

                    <label>Current Meter Reading (Units)</label>
                    <input type="number" name="current" placeholder="Enter Current Units" required min="<?= $prev_unit ?>">

                    <button type="submit" name="generate" class="btn btn-success">Generate Bill</button>
                    <br><br>
                    <a href="generate_bill.php">Back</a>
                </form>
            <?php } ?>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>