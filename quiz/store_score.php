<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$score = $data['score'];

$conn = new mysqli("localhost", "root", "", "quiz_db");

$stmt = $conn->prepare("UPDATE student_credentials SET score = ? WHERE username = ?");
$stmt->bind_param("is", $score, $username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Score stored successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to store score']);
}

$stmt->close();
$conn->close();
