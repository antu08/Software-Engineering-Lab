<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TGSPDCL Electricity Bill System</title>
    <!-- Use absolute path logic or relative based on depth -->
    <?php
    // Simple logic to determine path to assets
    $path = file_exists('assets/css/style.css') ? '' : '../';
    ?>
    <link rel="stylesheet" href="<?php echo $path; ?>assets/css/style.css">
    <script src="<?php echo $path; ?>assets/js/validate.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="nav-brand">
            <img src="<?php echo $path; ?>assets/images/tgspdcl_logo.png" alt="Logo" class="nav-logo"
                onerror="this.style.display='none'">
            <span>TGSPDCL Power</span>
        </div>
        <div class="nav-links">
            <a href="<?php echo $path; ?>index.php">Home</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'employee'): ?>
                <a href="<?php echo $path; ?>employee/dashboard.php">Dashboard</a>
                <a href="<?php echo $path; ?>employee/register_consumer.php">Register</a>
                <a href="<?php echo $path; ?>employee/generate_bill.php">Billing</a>
                <a href="<?php echo $path; ?>auth/logout.php" class="btn-logout">Logout</a>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo $path; ?>admin/dashboard.php">Admin</a>
                <a href="<?php echo $path; ?>auth/logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="<?php echo $path; ?>auth/employee_login.php">Employee</a>
                <a href="<?php echo $path; ?>consumer/view_bill.php">Consumer</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="main-content">