<?php
include("../config/db.php");
$id=$_GET['id'];
$bill=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM bills WHERE id=$id"));
?>
<h2>Payment Receipt</h2>
Meter: <?= $bill['meter_id'] ?><br>
Amount Paid: <?= $bill['total'] ?><br>
Status: PAID
