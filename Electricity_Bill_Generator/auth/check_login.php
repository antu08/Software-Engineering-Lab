<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php
        session_start();
        if(!isset($_SESSION['role'])){
        header("Location: ../index.php");
        exit;
        }
    ?>
</body>
</html>

