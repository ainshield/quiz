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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['module_name'], $_POST['url'])) {
    $module_name = trim($_POST['module_name']);
    $url = trim($_POST['url']);

    // Prepare & execute insert query
    $stmt = $conn->prepare("INSERT INTO modules (module_name, url) VALUES (?, ?)");
    $stmt->bind_param("ss", $module_name, $url);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $stmt->close();
}
?>
