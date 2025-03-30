<?php
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

    $sql = "SELECT * FROM admin_credentials WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $_SESSION['logged_in'] = true;
        header('Location: admin-dashboard.php');
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}

$conn->close();
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
        font-family: "Arial", sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #eaeaea;
    }

    .container {
        display: grid;
        grid-template-columns: 1fr;
        border-radius: 12px;
        height: auto;
        overflow: hidden;
        width: 80vw;
        max-width: 450px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .design-box {
        background-image: url("background2.jpg");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 50px 20px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .logo {
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 2em;
        font-weight: bold;
        color: #ffffff;
    }

    .hello-welcome {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    .hello-welcome h1 {
        font-size: 2.5em;
        margin: 0;
        color: #ffffff;
    }

    .description {
        margin-top: 15px;
        font-size: 1.2em;
        line-height: 1.6;
        color: #f0f0f0;
    }

    .login-form-box {
        background-color: #ffffff;
        padding: 30px 20px;
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 2em;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
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

    .login-button {
        background: #007bff;
        color: white;
        padding: 12px 25px;
        border: none;
        margin-top: 15px;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
        max-width: 180px;
        font-size: 1.1em;
        transition: background 0.3s;
    }

    .login-button:hover {
        background: #0056b3;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 600px) {
        .container {
            width: 90vw;
            padding: 20px;
        }

        .design-box {
            padding: 30px;
        }

        .login-button {
            max-width: 100%;
        }
    }
</style>

</head>
<body>

    <div class="container">
       
        <div class="login-form-box">
    <h2>Admin Login</h2>
    <form method="POST">
        <div class="input-container">
            <i class="fas fa-user input-icon"></i>
            <input type="text" class="input-field" name="username" placeholder="Username" required>
        </div>
        <div class="input-container">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" class="input-field" name="password" placeholder="Password" required>
        </div>
        <button class="login-button" type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
    </form>
</div>
    </div>

</body>
</html>