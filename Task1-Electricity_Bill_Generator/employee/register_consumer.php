<?php
include("../config/db.php");

if(isset($_POST['register'])){
    $type = $_POST['type'];
    $name = ucwords($_POST['name']);
    $mobile = $_POST['mobile'];
    $address = ucwords($_POST['address']);
    $pin = $_POST['pincode'];
    $license = $_POST['license'];

    $prefix = ($type=='household')?'M':(($type=='commercial')?'C':'I');
    $meter = $prefix . rand(100,999);

    mysqli_query($conn,"INSERT INTO consumers
    (meter_id,type,name,mobile,address,pincode,license_id)
    VALUES('$meter','$type','$name','$mobile','$address','$pin','$license')");

    echo "<p style='color:green'>
          Registered Successfully. Meter ID: <b>$meter</b>
          </p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Consumer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- ✅ JS VALIDATION FILE -->
    <script src="../assets/js/validate.js"></script>
</head>
<body>

<div class="container1">
        <div class="card" style="text-align:center; flex-wrap:wrap;">

<h2>Register New Consumer</h2>

<form method="post" onsubmit="return confirmGenerate();">

    <select name="type" required>
        <option value="household">Household</option>
        <option value="commercial">Commercial</option>
        <option value="industry">Industry</option>
    </select><br><br>

    <input name="name" placeholder="Name" required><br><br>

    <!-- ✅ MOBILE VALIDATION -->
    <input name="mobile"
           placeholder="Mobile Number"
           onblur="validateMobile(this)"
           required><br><br>

    <textarea name="address" placeholder="Address" required></textarea><br><br>

    <!-- ✅ PINCODE VALIDATION -->
    <input name="pincode"
           placeholder="Pincode"
           onblur="validatePincode(this)"
           required><br><br>

    <input name="license" placeholder="License (if any)"><br><br>

    <!-- ✅ CONFIRM BEFORE REGISTER -->
    <button type="submit" name="register">
        Register
    </button>

</form>

</div>
</div>
</body>
</html>

