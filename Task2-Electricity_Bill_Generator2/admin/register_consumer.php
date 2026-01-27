<?php
include("../config/db.php");
include("../auth/check_login.php");

if ($_SESSION['role'] != 'admin') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['register'])) {

    // Auto-generate service number? Or manual input?
    // Prompt says: "example 000123... put one unique service number".
    // I will let Admin input it to ensure it matches their system, or auto-generate if requested?
    // "put service number example 000123" -> Implies input.
    // "New consumers can only be created by Admin."

    $service_number = $_POST['service_number'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];

    // Check if exists
    $check = mysqli_query($conn, "SELECT id FROM consumers WHERE service_number='$service_number'");
    if (mysqli_num_rows($check) > 0) {
        $message = "❌ Service Number already exists!";
    } else {
        $query = "INSERT INTO consumers (service_number, name, type, mobile, address, pincode)
                  VALUES ('$service_number', '$name', '$type', '$mobile', '$address', '$pincode')";

        if (mysqli_query($conn, $query)) {
            $message = "✅ Consumer Registered Successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Consumer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container1">
        <div class="card">
            <h2>Register New Consumer</h2>

            <?php if ($message) {
                echo "<div class='alert'>$message</div>";
            } ?>

            <form method="post">
                <label>Unique Service Number (6 Digit)</label>
                <input type="text" name="service_number" required placeholder="e.g. 001234" maxlength="6"
                    pattern="\d{6}">

                <label>Consumer Name</label>
                <input type="text" name="name" required placeholder="Full Name">

                <label>Category</label>
                <select name="type">
                    <option value="household">Household</option>
                    <option value="commercial">Commercial</option>
                    <option value="industry">Industry</option>
                </select>

                <label>Mobile Number</label>
                <input type="text" name="mobile" required placeholder="10-digit mobile">

                <label>Address</label>
                <textarea name="address" required></textarea>

                <label>Pincode</label>
                <input type="text" name="pincode" required>

                <button type="submit" name="register" class="btn btn-success">Register Consumer</button>
            </form>
            <br>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>