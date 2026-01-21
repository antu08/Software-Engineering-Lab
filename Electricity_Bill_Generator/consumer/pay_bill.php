<?php
include("../config/db.php");
$bill_id = $_GET['id'];

mysqli_query($conn,"UPDATE bills SET status='PAID' WHERE id=$bill_id");

header("Location: receipt.php?id=$bill_id");
