
<!DOCTYPE html>
<html>
<head>
    <title>Generate Bill</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/validate.js"></script>
</head>
<body>
<div class="container1">
        <div class="card" style="text-align:center; flex-wrap:wrap;">

<h2>Generate Electricity Bill</h2>

<form method="post">
    <input type="text" name="meter" placeholder="Meter ID (M101 / C101 / I101)" required><br><br>
    <input type="number" name="current" placeholder="Current Meter Reading" required><br><br>
    <button type="submit" name="generate">Generate Bill</button>
</form>

</div>
</div>
</body>
</html>

<?php
include("../config/db.php");
include("../auth/check_login.php");
include("../utils/bill_calculator.php");

if ($_SESSION['role'] != 'employee') {
    die("Access Denied");
}

if (isset($_POST['generate'])) {

    $meter = trim($_POST['meter']);
    $current = (int)$_POST['current'];

    // 1️⃣ Get consumer details
    $consumerQuery = mysqli_query($conn,
        "SELECT type FROM consumers WHERE meter_id='$meter'"
    );

    if (mysqli_num_rows($consumerQuery) == 0) {
        die("Invalid Meter ID");
    }

    $consumer = mysqli_fetch_assoc($consumerQuery);
    $type = $consumer['type'];

    // 2️⃣ Get previous meter reading
    $prevQuery = mysqli_query($conn,
        "SELECT curr_unit FROM meter_readings 
         WHERE meter_id='$meter' 
         ORDER BY id DESC LIMIT 1"
    );

    $prev_unit = 0;
    if ($prev = mysqli_fetch_assoc($prevQuery)) {
        $prev_unit = (int)$prev['curr_unit'];
    }

    // 3️⃣ Calculate units used
    $units = $current - $prev_unit;

    if ($units < 0) {
        die("Current reading cannot be less than previous reading");
    }

    // 4️⃣ Check previous unpaid bills
    $dueQuery = mysqli_query($conn,
        "SELECT id FROM bills 
         WHERE meter_id='$meter' AND status='UNPAID'"
    );

    $hasPreviousDue = mysqli_num_rows($dueQuery) > 0;

    // 5️⃣ Calculate total bill
    $total = calculateBill($type, $units, $hasPreviousDue);

    // 6️⃣ Due date (7 days)
    $due_date = date('Y-m-d', strtotime('+7 days'));

    // 7️⃣ Insert bill
    mysqli_query($conn,
        "INSERT INTO bills
        (meter_id, units, total, due_date, status)
        VALUES
        ('$meter', '$units', '$total', '$due_date', 'UNPAID')"
    );

    // 8️⃣ Insert meter reading
    mysqli_query($conn,
        "INSERT INTO meter_readings
        (meter_id, prev_unit, curr_unit, units_used, month_year)
        VALUES
        ('$meter', '$prev_unit', '$current', '$units', DATE_FORMAT(NOW(), '%M %Y'))"
    );

    // 9️⃣ Redirect to PDF
    // header("Location: ../pdf/bill_pdf.php?meter=$meter");
    header("Location: ../consumer/view_bill.php?meter=$meter");

    exit;
}
?>

