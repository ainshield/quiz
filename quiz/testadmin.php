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

// Handle the update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $question_id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];
    $explanation = $_POST['explanation'];

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE questions SET question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, correct_option = ?, explanation = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssssssi", $question, $option1, $option2, $option3, $option4, $correct_option, $explanation, $question_id);
        if ($stmt->execute()) {
            safeRedirect("Question updated successfully!", "testadmin.php");
        } else {
            error_log("Update Error: " . $stmt->error); // Log the error
            safeRedirect("Error updating question: " . $stmt->error, "testadmin.php");
        }
        $stmt->close();
    } else {
        error_log("Update Prepare Error: " . $conn->error); // Log the error
        safeRedirect("Error preparing update statement: " . $conn->error, "testadmin.php");
    }
}

// Handle add question
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correctOption = $_POST['correctOption'];
    $explanation = $_POST['explanation'];

    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("Add Prepare failed: " . $conn->error);
        safeRedirect("Prepare failed: " . $conn->error, "testadmin.php");
    }
    $stmt->bind_param("sssssss", $question, $option1, $option2, $option3, $option4, $correctOption, $explanation);

    if ($stmt->execute()) {
        safeRedirect("Question added successfully!", "testadmin.php");
    } else {
        error_log("Add Execute Error: " . $stmt->error);
        safeRedirect("Error adding question: " . $stmt->error, "testadmin.php");
    }

    $stmt->close();
}

// Handle delete
if (isset($_GET['delete'])) {
    $question_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    if (!$stmt) {
        error_log("Delete Prepare failed: " . $conn->error);
        safeRedirect("Prepare failed: " . $conn->error, "testadmin.php");
    }
    $stmt->bind_param("i", $question_id);
    if ($stmt->execute()) {
        safeRedirect("Question deleted successfully!", "testadmin.php");
    } else {
        error_log("Delete Execute Error: " . $stmt->error);
        safeRedirect("Error deleting question: " . $stmt->error, "testadmin.php");
    }
    $stmt->close();
}

// Check if the edit parameter is set
if (isset($_GET['edit'])) {
    $question_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
    if (!$stmt) {
        error_log("Edit Prepare failed: " . $conn->error);
        safeRedirect("Prepare failed: " . $conn->error, "admin_edit.php");
    }
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        error_log("Edit Result failed: " . $stmt->error);
        safeRedirect("Result failed: " . $stmt->error, "testadmin.php");
    }
    $questionData = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    :root {
        --primary: #5469D4;
        --primary-hover: #3a4ca0;
        --danger: #fc5a5a;
        --danger-hover: #e03c3c;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0056b3;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header .logo {
            font-size: 1.5em;
            font-weight: bold;
        }

        .header .user-actions {
            display: flex;
            align-items: center;
        }

        .header .user-actions .profile-photo {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            position:relative;
            background-color: #fff;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #0056b3;
            border: 2px solid #fff;
        }
        .profile-dropdown {
            display: none;
            position: absolute;
            right: 20px;
            top: 70px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            z-index: 99;
        }

        .profile-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .profile-dropdown ul li {
            padding: 10px;
            font-size: 1em;
            cursor: pointer;
        }

        .profile-dropdown ul li:hover {
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
        }

        .profile-photo {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            background-color: #fff;
            margin-right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #0056b3;
            border: 2px solid #fff;
            cursor: pointer;
        }
        .profile-dropdown ul li a {
            text-decoration:none;
            color:white;
        }


        .header .user-actions .bell {
            position: relative;
            margin-right: 20px;
            cursor: pointer;
        }

        .header .user-actions .bell i {
            font-size: 1.5em;
            color: white;
        }

        .header .user-actions .bell .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px;
            font-size: 0.8em;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 4fr;
            height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .sidebar {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease-in-out;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            padding: 10px;
            font-size: 1.1em;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .sidebar .menu li a {
            text-decoration:none;
            color:white;
        }

        .sidebar .menu li:hover {
            background-color: #007BFF;
            border-radius: 5px;
        }

        .subcategories {
            display: block;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-left: 20px;
        }

        .subcategories li {
            padding: 8px;
            font-size: 1em;
            cursor: pointer;
        }

        .subcategories li:hover {
            background-color: #007BFF;
            border-radius: 5px;
        }

        .main-content {
            padding: 20px;
            display: grid;
            grid-template-columns:1fr;
            gap: 20px;
            transition: margin-left 0.3s ease;
        }

        .card {
            background-color: white;
            padding: 20px;
            display:block;
            margin: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1em;
            color: #555;
        }

        .card .tutorial {
            margin-top: 10px;
            font-size: 1em;
            color: #007BFF;
            text-decoration: none;
        }

        .card .tutorial:hover {
            text-decoration: underline;
        }

        .active {
            background: #007BFF;
            border-radius: 5px;
        }
        .card img {
        width: 100%;
        max-width: 300px;
        height: auto;
        object-fit: cover;
        margin-bottom: 10px;
    }
    #content-5 img {
        max-width:900px;
    }
        .quiz-button-container {
            display: flex;
            justify-content: flex-start;
            margin-top: 20px;
        }

        .quiz-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .quiz-button:hover {
            background-color: #0056b3;
        }


        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .card-body {
            padding: 24px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-family: inherit;
            font-size: 16px;
            background-color: white;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(84, 105, 212, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .option-group {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .option-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            font-weight: 600;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .option-input {
            flex: 1;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: var(--gray-100);
            color: var(--gray-800);
            font-weight: 600;
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid var(--gray-200);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--gray-200);
            vertical-align: top;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: var(--gray-50);
        }

        .table-option {
            display: flex;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .table-option-label {
            font-weight: 600;
            color: var(--primary);
            margin-right: 8px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background-color: white;
            margin: 10vh auto;
            padding: 32px;
            border-radius: 12px;
            max-width: 600px;
            box-shadow: var(--shadow-md);
            position: relative;
            animation: modalFade 0.25s;
        }

        @keyframes modalFade {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: var(--gray-700);
        }

        .close:hover {
            color: var(--gray-900);
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: var(--gray-700);
        }


    
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">OraQuiz</div>
        <div class="user-actions">
        <div class="profile-photo" id="profile-photo">
            <span>J</span>
        </div>


        </div>
    </div>

    <div class="profile-dropdown" id="profile-dropdown">
    <ul>
    <a href="logout-student.php"> <li><i class="fas fa-sign-out-alt"></i> Logout</li></a>
    </ul>
</div>

    <div class="dashboard">
    <div class="sidebar">
    <ul class="menu">
    <li id="lesson1"><a href="admin-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li  class="active"><a href="testadmin.php" ><i class="fas fa-plus-circle"></i> Add Question</a></li>
<li><a href="admin_leaderboard.php"><i class="fas fa-trophy"></i> Leaderboard</a></li>


    </ul>
</div>

        <div class="main-content" >

        <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Add New Question</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <textarea id="question" name="question" required></textarea>
                    </div>

                    <div class="form-group">
                        <div class="option-group">
                            <div class="option-label">A</div>
                            <input type="text" id="option1" name="option1" class="option-input" required>
                        </div>

                        <div class="option-group">
                            <div class="option-label">B</div>
                            <input type="text" id="option2" name="option2" class="option-input" required>
                        </div>

                        <div class="option-group">
                            <div class="option-label">C</div>
                            <input type="text" id="option3" name="option3" class="option-input" required>
                        </div>

                        <div class="option-group">
                            <div class="option-label">D</div>
                            <input type="text" id="option4" name="option4" class="option-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correctOption">Correct Option</label>
                        <select id="correctOption" name="correctOption" required>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="explanation">Explanation</label>
                        <textarea id="explanation" name="explanation" required></textarea>
                    </div>

                    <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">View All Questions</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Options</th>
                                <th>Correct Answer</th>
                                 <th>Explanation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$stmt = $conn->prepare("SELECT * FROM questions");
if(!$stmt){
    die("Prepare Error: ". $conn->error);
}
$stmt->execute();
$result = $stmt->get_result();
if(!$result){
    die("Get Result Error: ". $stmt->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['question']); ?></td>

            <td>
                <div class="table-option">
                    <span class="table-option-label">A:</span> <?php echo htmlspecialchars($row['option1']); ?>
                </div>
                <div class="table-option">
                    <span class="table-option-label">B:</span> <?php echo htmlspecialchars($row['option2']); ?>
                </div>
                <div class="table-option">
                    <span class="table-option-label">C:</span> <?php echo htmlspecialchars($row['option3']); ?>
                </div>
                <div class="table-option">
                    <span class="table-option-label">D:</span> <?php echo htmlspecialchars($row['option4']); ?>
                </div>
            </td>
            <td><?php echo htmlspecialchars($row['correct_option']); ?></td>
             <td><?php echo htmlspecialchars($row['explanation']); ?></td>
            <td class="action-buttons">
                <a href="admin_edit.php?edit=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                <a href="testadmin.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='6' class='empty-state'>No questions found.</td></tr>";
}
$stmt->close();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        const profilePhoto = document.getElementById("profile-photo");
        const profileDropdown = document.getElementById("profile-dropdown");

        profilePhoto.addEventListener("click", function() {
            profileDropdown.style.display =
                profileDropdown.style.display === "none" ? "block" : "none";
        });

        document.addEventListener("click", function(event) {
            if (!profileDropdown.contains(event.target) && event.target !== profilePhoto) {
                profileDropdown.style.display = "none";
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
