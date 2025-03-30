<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "quiz_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$questionData = null;

// Function to safely redirect with JavaScript alert
function safeRedirect($message, $url) {
    echo "<script>alert('$message'); window.location.href='$url';</script>";
    exit(); // Stop further execution after redirect
}

// Check if the edit parameter is set
if (isset($_GET['edit'])) {
    // Prepare the data for fetching
    $question_id = $_GET['edit'];

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
        } else {
            safeRedirect('No question found with the given ID.', 'testadmin.php');
        }

        $stmt->close();
    } else {
        safeRedirect('Error preparing statement.', 'testadmin.php');
    }
}

// Handle the update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $question_id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];
    $explanation = $_POST['explanation']; // Get explanation

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE questions SET question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, correct_option = ?, explanation = ? WHERE id = ?");
    
    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param("sssssssi", $question, $option1, $option2, $option3, $option4, $correct_option, $explanation, $question_id); // Added explanation

        if ($stmt->execute()) {
            safeRedirect('Question updated successfully!', 'testadmin.php');
        } else {
            safeRedirect('Error updating question: ' . $stmt->error, 'testadmin.php');
        }

        $stmt->close();
    } else {
        safeRedirect('Error preparing update statement.', 'testadmin.php');
    }
}

// Close the database connection.  This should remain outside the conditional blocks.
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px; /* Set a fixed width for the container */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(40, 44, 167);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color:rgb(36, 33, 136);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Question</h2>
    <?php if ($questionData): ?>
        <form method="POST">  <input type="hidden" name="id" value="<?php echo $questionData['id']; ?>">
            <div class="form-group">
                <label for="question">Question</label>
                <textarea id="question" name="question" required><?php echo htmlspecialchars($questionData['question']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="option1">Option A</label>
                <input type="text" id="option1" name="option1" value ="<?php echo htmlspecialchars($questionData['option1']); ?>" required>
            </div>

            <div class="form-group">
                <label for="option2">Option B</label>
                <input type="text" id="option2" name="option2" value="<?php echo htmlspecialchars($questionData['option2']); ?>" required>
            </div>

            <div class="form-group">
                <label for="option3">Option C</label>
                <input type="text" id="option3" name="option3" value="<?php echo htmlspecialchars($questionData['option3']); ?>" required>
            </div>

            <div class="form-group">
                <label for="option4">Option D</label>
                <input type="text" id="option4" name="option4" value="<?php echo htmlspecialchars($questionData['option4']); ?>" required>
            </div>

            <div class="form-group">
                <label for="correct_option">Correct Option</label>
                <select id="correct_option" name="correct_option" required>
                    <option value="A" <?php echo ($questionData['correct_option'] == 'A') ? 'selected' : ''; ?>>Option A</option>
                    <option value="B" <?php echo ($questionData['correct_option'] == 'B') ? 'selected' : ''; ?>>Option B</option>
                    <option value="C" <?php echo ($questionData['correct_option'] == 'C') ? 'selected' : ''; ?>>Option C</option>
                    <option value="D" <?php echo ($questionData['correct_option'] == 'D') ? 'selected' : ''; ?>>Option D</option>
                </select>
            </div>
            <div class="form-group">
                <label for="explanation">Explanation</label>
                <textarea id="explanation" name="explanation" required><?php echo htmlspecialchars($questionData['explanation']); ?></textarea>
            </div>

            <button type="submit" name="update">Update Question</button>
        </form>
    <?php else: ?>
        <p>Question not found.</p>
    <?php endif; ?>
</div>

</body>
</html>
