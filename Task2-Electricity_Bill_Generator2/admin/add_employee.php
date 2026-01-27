<?php
include("../config/db.php");
include("../auth/check_login.php"); // Ensure Admin

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$message = "";

if (isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    // Auto-generate username: employee + next ID
    // Count existing employees to determine specific ID? 
    // Or just use 'employee' . (count + 1)
    $res = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='employee'");
    $row = mysqli_fetch_assoc($res);
    $nextId = $row['count'] + 1;
    $username = "employee" . $nextId;
    $password = MD5("1234"); // Default password

    // Check availability just in case
    while (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE username='$username'")) > 0) {
        $nextId++;
        $username = "employee" . $nextId;
    }

    $query = "INSERT INTO users (role, username, password, name, mobile, address) 
              VALUES ('employee', '$username', '$password', '$name', '$mobile', '$address')";

    if (mysqli_query($conn, $query)) {
        $message = "✅ Employee Added Successfully! <br> Username: <strong>$username</strong> <br> Password: <strong>1234</strong>";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Employee</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container1">
        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <h2>Add New Employee</h2>

            <?php if ($message) {
                echo "<div class='alert'>$message</div>";
            } ?>

            <form method="post">
                <label>Employee Name</label>
                <input type="text" name="name" required placeholder="Full Name">

                <label>Phone Number</label>
                <input type="text" name="mobile" required placeholder="10-digit Mobile">

                <label>Address</label>
                <textarea name="address" required placeholder="Residential Address"></textarea>

                <button type="submit" name="add_employee" class="btn btn-primary">Create Employee</button>
            </form>
            <br>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>