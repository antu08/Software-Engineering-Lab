<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body></body>
</html>

<?php
include("../config/db.php");

$result = mysqli_query($conn,"SELECT * FROM users");
?>
<h2>System Users</h2>
<table border="1">
<tr><th>ID</th><th>Role</th><th>Username</th></tr>
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['role'] ?></td>
<td><?= $row['username'] ?></td>
</tr>
<?php } ?>
</table>


