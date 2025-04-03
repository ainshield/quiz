<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}

$moduleId = $_POST['id'];
$moduleName = $_POST['module_name'];
$moduleContent = $_POST['content'];
$imageUrl = "";

if (!empty($_FILES["image"]["name"])) {
    $targetDir = "../../uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $imageUrl = $targetFilePath;
    }
}

// If image was uploaded, update it too
if ($imageUrl) {
    $query = "UPDATE modules SET module_name=?, content=?, image_url=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $moduleName, $moduleContent, $imageUrl, $moduleId);
} else {
    $query = "UPDATE modules SET module_name=?, content=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $moduleName, $moduleContent, $moduleId);
}

$success = $stmt->execute();
echo json_encode(["success" => $success]);
?>
