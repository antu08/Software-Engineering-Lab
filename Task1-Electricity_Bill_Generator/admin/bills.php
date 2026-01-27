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

$result = mysqli_query($conn,"SELECT * FROM bills ORDER BY id DESC");
?>
<h2>All Bills</h2>
<table border="1">
<tr>
<th>Meter</th><th>Units</th><th>Total</th><th>Status</th><th>Due</th>
</tr>
<?php while($b=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $b['meter_id'] ?></td>
<td><?= $b['units'] ?></td>
<td><?= $b['total'] ?></td>
<td><?= $b['status'] ?></td>
<td><?= $b['due_date'] ?></td>
</tr>
<?php } ?>
</table>

