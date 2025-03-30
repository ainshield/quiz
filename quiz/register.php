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

$error = "";
$success = "";
$first_name = $last_name = $lrn = $strand = $grade_level = $section = $username = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $lrn = trim($_POST['lrn']);
    $strand = trim($_POST['strand']);
    $grade_level = trim($_POST['grade_level']);
    $section = trim($_POST['section']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if fields are empty
    if (empty($first_name) || empty($last_name) || empty($lrn) || empty($strand) || empty($grade_level) || empty($section) || empty($username) || empty($password)) {
        $error = "All fields are required!";
    }
    // Validate LRN (ensure it's numeric)
    elseif (!is_numeric($lrn)) {
        $error = "LRN must be a numeric value.";
    }
    // Validate username (alphanumeric)
    elseif (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $error = "Username can only contain letters and numbers.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT * FROM student_registration WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already taken. Please choose a different one.";
        } else {
            // Hash the password for storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user into database
            $sql = "INSERT INTO student_registration (first_name, last_name, lrn, strand, grade_level, section, username, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $first_name, $last_name, $lrn, $strand, $grade_level, $section, $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['registered'] = true;
                $success = "Registration successful! Redirecting...";
                header("refresh:3; url=member-login.php"); // Redirect after 3 seconds
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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
            width: 400px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .input-container {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }
        .input-field {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #0056b3;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .register-button {
            background-color: #0056b3;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .register-button:hover {
            background-color: #007BFF;
        }
        .message {
            margin-top: 10px;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST">
            <div class="input-container">
                <input type="text" class="input-field" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($first_name) ?>" required>
            </div>
            <div class="input-container">
                <input type="text" class="input-field" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($last_name) ?>" required>
            </div>
            <div class="input-container">
                <input type="text" class="input-field" name="lrn" placeholder="LRN" value="<?= htmlspecialchars($lrn) ?>" required>
            </div>
            <div class="input-container">
                <select name="strand" class="input-field" required>
                    <option value="" disabled <?= empty($strand) ? "selected" : "" ?>>Select Strand</option>
                    <option value="ABM" <?= $strand == "ABM" ? "selected" : "" ?>>ABM</option>
                    <option value="HUMSS" <?= $strand == "HUMSS" ? "selected" : "" ?>>HUMSS</option>
                    <option value="STEM" <?= $strand == "STEM" ? "selected" : "" ?>>STEM</option>
                    <option value="GAS" <?= $strand == "GAS" ? "selected" : "" ?>>GAS</option>
                    <option value="ICT" <?= $strand == "ICT" ? "selected" : "" ?>>ICT</option> 
                </select>
            </div>
            <div class="input-container">
                <select name="grade_level" class="input-field" required>
                    <option value="" disabled <?= empty($grade_level) ? "selected" : "" ?>>Select Grade Level</option>
                    <option value="11" <?= $grade_level == "11" ? "selected" : "" ?>>Grade 11</option>
                    <option value="12" <?= $grade_level == "12" ? "selected" : "" ?>>Grade 12</option>
                </select>
            </div>
            <div class="input-container">
                <input type="text" class="input-field" name="section" placeholder="Section" value="<?= htmlspecialchars($section) ?>" required>
            </div>
            <div class="input-container">
                <input type="text" class="input-field" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="input-container">
                <input type="password" class="input-field" name="password" placeholder="Password" required>
            </div>
            <button class="register-button" type="submit">Register</button>
        </form>
        <div class="message">
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        </div>
    </div>
</body>
</html>
