<?php
include("../config/db.php");

if (isset($_POST['register'])) {
    $type = $_POST['type'];
    $name = ucwords($_POST['name']);
    $mobile = $_POST['mobile'];
    $address = ucwords($_POST['address']);
    $pin = $_POST['pincode'];
    $license = $_POST['license'];

    $prefix = ($type == 'household') ? 'M' : (($type == 'commercial') ? 'C' : 'I');
    $meter = $prefix . rand(100, 999);

    // --- VALIDATION (Task 1) ---
    $errors = [];
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name must contain only letters and whitespace.";
    }
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile number must be exactly 10 digits.";
    }

    if (empty($errors)) {
        $q = "INSERT INTO consumers (meter_id,type,name,mobile,address,pincode,license_id) 
              VALUES('$meter','$type','$name','$mobile','$address','$pin','$license')";

        if (mysqli_query($conn, $q)) {
            echo "<div class='alert alert-success'>Registered Successfully. Meter ID: <b>$meter</b></div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        foreach ($errors as $err) {
            echo "<div class='alert alert-danger'>$err</div>";
        }
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="container1">
    <div class="card">
        <h2>Register New Consumer</h2>

        <form method="post" onsubmit="return confirmGenerate();" style="margin: 0 auto;">

            <label>Consumer Type</label>
            <select name="type" required>
                <option value="household">Household</option>
                <option value="commercial">Commercial</option>
                <option value="industry">Industry</option>
            </select>

            <label>Name</label>
            <input name="name" placeholder="Full Name" required>

            <label>Mobile Number</label>
            <input name="mobile" placeholder="10-digit Mobile" onblur="validateMobile(this)" required>

            <label>Address</label>
            <textarea name="address" placeholder="Full Address" required></textarea>

            <label>Pincode</label>
            <input name="pincode" placeholder="6-digit Pincode" onblur="validatePincode(this)" required>

            <label>License ID (Optional)</label>
            <input name="license" placeholder="License ID">

            <button type="submit" name="register" class="btn-primary">Register</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>