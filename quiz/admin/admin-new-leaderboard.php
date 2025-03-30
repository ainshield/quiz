<?php
session_start();

$conn = new mysqli("localhost", "root", "", "quiz_db");

$sql = "SELECT username, score FROM student_credentials ORDER BY score DESC LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oral Communication Admin Panel</title>
    <?php include_once '../vendor/bootstrap.php';?>
    <link rel="stylesheet" href="css/admin-leaderboard.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">
</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>
    <div class="main-content" >
        <div id="leaderboardContainer">
            <h1>Admin Leaderboard</h1>
            <table id="leaderboardTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $rank = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$rank}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['score']}</td>
                                </tr>";
                            $rank++;
                        }
                    } else {
                        echo "<tr><td colspan='3'>No students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
