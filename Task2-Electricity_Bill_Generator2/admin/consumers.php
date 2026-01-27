<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body></body>

</html>

<?php
include("../config/db.php");

$result = mysqli_query($conn, "SELECT * FROM consumers");
?>
<h2>Registered Consumers</h2>
<table border="1">
    <tr>
        <th>Service Number</th>
        <th>Name</th>
        <th>Type</th>
        <th>Mobile</th>
        <th>Address</th>
        <th>Pincode</th>
    </tr>
    <?php while ($c = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $c['service_number'] ?></td>
            <td><?= $c['name'] ?></td>
            <td><?= $c['type'] ?></td>
            <td><?= $c['mobile'] ?></td>
            <td><?= $c['address'] ?></td>
            <td><?= $c['pincode'] ?></td>
        </tr>
    <?php } ?>
</table>