<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}

$moduleName = $_POST['module_name'];
$moduleContent = $_POST['content'];
$imageUrl = "";

if (!empty($_FILES["image"]["name"])) {
    $targetDir = "../../uploads/";  // Make sure this directory exists
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $imageUrl = $targetFilePath;
    }
}

$query = "INSERT INTO modules (module_name, content, image_url) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $moduleName, $moduleContent, $imageUrl);
$success = $stmt->execute();

echo json_encode(["success" => $success]);
?>
