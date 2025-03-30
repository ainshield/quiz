<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM modules";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <?php include_once '../vendor/bootstrap.php'; ?>
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
    <?php include_once 'sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Modules</div>
    <div class="main-content justify-content-center">
        <div class="card" style="width: auto; height: auto;">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Module Name</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($module = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($module['id']) ?></td>
                            <td><?= htmlspecialchars($module['module_name']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($module['url']) ?>" target="_blank" class="btn btn-success">Download</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
