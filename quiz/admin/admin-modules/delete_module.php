<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "quiz_db";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM modules WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        echo json_encode(["success" => $success]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid module ID."]);
    }
}
?>
