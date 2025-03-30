<?php
$conn = new mysqli("localhost", "root", "", "quiz_db");

if (isset($_GET['id'])) {
    $questionId = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Question not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "No question ID provided."]);
}

$conn->close();
?>
