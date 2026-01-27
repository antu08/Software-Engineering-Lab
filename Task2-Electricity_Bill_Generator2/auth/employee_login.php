<?php include('../includes/header.php'); ?>

<div class="container1">
    <div class="card">
        <form method="post" style="margin: 0 auto;">
            <h2>Employee Login</h2>
            
            <label>Username</label>
            <input name="username" required placeholder="Enter username">
            
            <label>Password</label>
            <input name="password" type="password" required placeholder="Enter password">
            
            <button name="login" class="btn-primary">Login</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>


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

