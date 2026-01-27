<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

</body>

</html>

<?php
include("../config/db.php");

$result = mysqli_query($conn, "SELECT * FROM bills ORDER BY id DESC");
?>
<h2>All Generated Bills</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Service Number</th>
        <th>Units</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Date</th>
    </tr>
    <?php while ($b = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $b['id'] ?></td>
            <td><?= $b['service_number'] ?></td>
            <td><?= $b['units'] ?></td>
            <td><?= $b['total'] ?></td>
            <td><?= $b['status'] ?></td>
            <td><?= $b['generated_at'] ?></td>
        </tr>
    <?php } ?>
</table>