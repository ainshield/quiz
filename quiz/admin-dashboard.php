<?php
session_start();

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
            background-color: #f8f9fa;
            display: flex;
            width:100vw;
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
            width: calc(100% - 250px); /* Adjust to avoid overlapping */
            background: #34495E;
            color: white;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            font-size: 1.3em;
            font-weight: bold;
            position: fixed;
            top: 0;
            left: 250px; /* Matches sidebar width */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .main-content {
            margin-top: 80px;
            width:80%;
            padding: 20px;
            flex: 1;
        }

        .card {
            background: white;
            width:75vw;
            margin-left: 250px;

            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            font-size: 1.1em;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .header {
                width: calc(100% - 200px);
                left: 200px;
            }
            .main-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .header {
                width: 100%;
                left: 0;
                font-size: 1em;
                padding: 10px 20px;
            }
            .main-content {
                margin-left: 0;
            }
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
              <a href="logout.php"><button class="logout-button" type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
        </div>
    </div>

    <div class="header">
        Oral Communication Admin Panel
    </div>

    <div class="main-content">
        <div class="card">
            <h3>Welcome, <?php echo !empty($username) ? ucfirst($username) : "Admin"; ?>!</h3>
            <p>Manage quizzes, users, and monitor the leaderboard from this dashboard.</p>
        </div>
    </div>


    <script>
    
        </script>
</body>
</html>
