<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/validate.js"></script>
</head>
<body>
    <div class="container1">
        <div class="card" style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">
            <form method="post">
                <h2>Employee Login</h2>
                <input name="username" required><br>
                <input name="password" type="password" required><br>
                <button name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include("../config/db.php");

if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=md5($_POST['password']);

    $q=mysqli_query($conn,"SELECT * FROM users 
        WHERE username='$u' AND password='$p' AND role='employee'");
    
    if(mysqli_num_rows($q)==1){
        $_SESSION['role']='employee';
        header("Location: ../employee/dashboard.php");
    } else {
        echo "Invalid Employee Login";
    }
}
?>

