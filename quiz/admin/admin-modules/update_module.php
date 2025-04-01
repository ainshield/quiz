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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['module_name'], $_POST['content'])) {
    $id = (int) $_POST['id'];
    $module_name = trim($_POST['module_name']);
    $content = trim($_POST['content']);

    // Prepare & execute update query
    $stmt = $conn->prepare("UPDATE modules SET module_name = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $module_name, $content, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }

    $stmt->close();
}
?>
