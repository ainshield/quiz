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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
          body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
            transition: background 0.3s;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #34495E;
            color: white;
            padding-top: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            padding: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar .menu li:hover {
            background: #1A252F;
        }

        .sidebar .menu li a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
            font-size: 1.1em;
        }

        .logout-container {
            padding: 15px;
        }

        .logout-button {
            background: #E74C3C;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: 0.3s;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-button:hover {
            background: #C0392B;
        }
        .header {
            margin-left: 250px;
            padding: 20px;
            background-color: #3498db;
            color: white;
            font-size: 24px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dark-mode-toggle {
            background-color: white;
            color: #333;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        .dark-mode-toggle:hover {
            background-color: #ddd;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        #leaderboardContainer {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        /* Dark Mode Styles */
        .dark-mode {
            background-color: #2c3e50;
            color: white;
        }
        .dark-mode .header {
            background-color: #1a252f;
        }
        .dark-mode .sidebar {
            background-color: #1a252f;
        }
        .dark-mode #leaderboardContainer {
            background-color: #34495e;
            color: white;
        }
        .dark-mode table th {
            background-color: #1abc9c;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <ul class="menu">
            <li><i class="fas fa-home"></i><a href="admin-dashboard.php"> Dashboard</a></li>
            <li><i class="fas fa-plus-circle"></i><a href="admin-new.php"> Add Question</a></li>
            <li><i class="fas fa-trophy"></i><a href="admin-new-leaderboard.php"> Leaderboard</a></li>
        </ul>
        <div class="logout-container">
            <form action="logout.php" method="post">
                <button class="logout-button" type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>

    

    <div class="main-content">
    <div class="main-content" >
        <div id="leaderboardContainer">
        <h1>Admin Leaderboard</h1>
        
        <table>
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

    </div>

</body>
</html>
