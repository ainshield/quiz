<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM student_credentials WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user['username'];
        header('Location: member-dashboard.php');
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr;
            border-radius: 8px;
            height: 50vh;
            overflow: hidden;
            width: 30vw;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: white;
            padding: 30px;
        }

        .login-form-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .input-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 12px 0;
        }

        .input-field {
            padding: 15px 20px 15px 40px;
            width: 100%;
            font-weight: 600;
            border: 2px solid #007bff;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .input-field:focus {
            border-color: #0056b3;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            align-items: center;
        }

        .login-button, .register-button {
            padding: 12px 25px;
            border: none;
            margin-top: 15px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
            font-size: 1.1em;
            transition: background 0.3s;
            text-align: center;
        }

        .login-button {
            background: #007bff;
            color: white;
        }

        .login-button:hover {
            background: #0056b3;
        }

        .register-button {
            background: #28a745;
            color: white;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-button:hover {
            background: #218838;
        }

        @media (max-width: 600px) {
            .container {
                width: 90vw;
                padding: 20px;
            }

            .login-button, .register-button {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="login-form-box">
            <h2>Login</h2>
            <form method="POST">
                <div class="input-container">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="input-field" name="username" placeholder="Username" required>
                </div>
                <div class="input-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="input-field" name="password" placeholder="Password" required>
                </div>
                <div class="button-container">
                    <button class="login-button" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
                    <a href="register.php" class="register-button"><i class="fas fa-user-plus"></i> Register</a>
                </div>
            </form>
            <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
        </div>
    </div>

</body>
</html>
