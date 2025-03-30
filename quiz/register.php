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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $repass = $_POST['repassword'];
    $role = "user"; // Default role set to user
    
    if ($pass !== $repass) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $checkUser = $conn->query("SELECT * FROM users WHERE username = '$user'");
        if ($checkUser->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user, $pass, $role);
            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='index.php'>login</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register - Oral Communication Quiz App</title> 
</head>
<body>
<section class="vh-100" style="background-color:rgb(47, 95, 41);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <h3 class="mb-5">Register</h3>

            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

            <form method="POST" >
              <div class="form-outline mb-4 d-flex align-items-center">
                <label class="form-label me-3" for="username" style="min-width: 100px;">Username</label>
                <input type="text" id="username" name="username" class="form-control form-control-lg" required />
              </div>

              <div class="form-outline mb-4 d-flex align-items-center">
                <label class="form-label me-3" for="password" style="min-width: 100px;">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-lg" required />
              </div>
              
              <div class="form-outline mb-4 d-flex align-items-center">
                <label class="form-label me-3" for="repassword" style="min-width: 100px;">Retype Password</label>
                <input type="password" id="repassword" name="repassword" class="form-control form-control-lg" required />
              </div>

              <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
            </form>
            <div style="margin-top: 10px;">
              <a class="btn btn-primary btn-lg btn-block" href="index.php" role="button">Back to Login</a>
            </div>
            <hr class="my-4">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
