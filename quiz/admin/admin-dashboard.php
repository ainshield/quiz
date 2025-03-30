<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oral Communication Admin Panel</title>
    <?php include_once '../vendor/bootstrap.php';?>
    <link rel="stylesheet" href="css/admin-dash.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">
</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">
        Dashboard
    </div>

    <div class="main-content">
        <div class="card">
            <h3>Welcome, <?php echo !empty($username) ? ucfirst($username) : "Admin"; ?>!</h3>
            <p>Manage quizzes, and monitor the leaderboard from this dashboard.</p>
        </div>
    </div>
</body>
</html>
