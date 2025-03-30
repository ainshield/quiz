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
    
    $result = $conn->query("SELECT password, role FROM users WHERE username = '$user'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($pass === $row['password']) {
            $_SESSION['username'] = $user;
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] === 'admin') {
                header("Location: admin/admin-dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Oral Communication Quiz App</title> 
</head>
<body>
<section class="vh-100" style="background-color:rgb(47, 95, 41);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <h3 class="mb-5">Sign in</h3>

            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="POST" >
              <div class="form-outline mb-4 d-flex align-items-center">
                <label class="form-label me-3" for="username" style="min-width: 100px;">Username</label>
                <input type="text" id="username" name="username" class="form-control form-control-lg" required />
              </div>

              <div class="form-outline mb-4 d-flex align-items-center">
                <label class="form-label me-3" for="password" style="min-width: 100px;">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-lg" required />
              </div>

              <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
            </form>
            <div style="margin-top: 10px;">
              <a class="btn btn-primary btn-lg btn-block" href="register.php" role="button">Register</a>
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