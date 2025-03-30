<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <?php include_once '../vendor/bootstrap.php';?>
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
    <?php include_once 'sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">
        Modules
    </div>

    <div class="main-content">
        <div class="card">
            <h3>Welcome, <?php echo !empty($username) ? ucfirst($username) : "User"; ?>!</h3>
            <p>Access your quizzes and track your progress from this dashboard.</p>
        </div>

    </div>
</body>
</html>
