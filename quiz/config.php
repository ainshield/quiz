<?php
// Database configuration
$host = 'localhost'; // Database host
$db = 'question'; // Database name
$id = ' '; // Database username
$question_id = 'question_id'; // Database password

// Create a connection
$conn = new mysqli($host, $id, $question_id, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the edit form was submitted
if (isset($_POST['edit'])) {
    // Prepare the data for fetching
    $question_id = $_POST['id'];

    // Prepare and execute the query to fetch the question data
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
    
    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the question data
        if ($result->num_rows > 0) {
            $questionData = $result->fetch_assoc();
            // You can now use $questionData to populate your form or for further processing
        } else {
            echo "<script>alert('No question found with the given ID.'); window.location.href='testadmin.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement.'); window.location.href='testadmin.php';</script>";
    }
}

// Close the database connection
$conn->close();
?>