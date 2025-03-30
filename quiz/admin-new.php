<?php
session_start();

?>
<?php
$conn = new mysqli("localhost", "root", "", "quiz_db");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correctOption = $_POST['correctOption'];

    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question, $option1, $option2, $option3, $option4, $correctOption);

    if ($stmt->execute()) {
        echo "<script>alert('Question added successfully!'); window.location.href='admin-new.php';</script>";
    } else {
        echo "<script>alert('Error adding question.'); window.location.href='admin-new.php';</script>";
    }

    $stmt->close();
}

if (isset($_GET['delete'])) {
    $question_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Question deleted successfully!'); window.location.href='admin-new.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oral Communication Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
}
.sidebar {
            width: 250px;
            height: 100vh;
            background: #34495E;
            color: white;
            padding-top: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            padding: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar .menu li:hover {
            background: #1A252F;
        }

        .sidebar .menu li a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
            font-size: 1.1em;
        }

        .logout-container {
            padding: 15px;
        }

        .logout-button {
            background: #E74C3C;
            color: white;
            padding: 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 1em;
            transition: 0.3s;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-button:hover {
            background: #C0392B;
        }

.main-content {
    margin-left: 260px;
    padding: 20px;
    width: calc(100% - 260px);
}

.card {
    background: white;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.card-header h2 {
    margin: 0;
    font-size: 22px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
}

textarea, input, select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
}

.btn-primary:hover {
    background: #2980b9;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background: #2c3e50;
    color: white;
}

.btn-danger {
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.btn-danger:hover {
    background: #c0392b;
}

    </style>
</head>
<body>

    <div class="sidebar">
        <ul class="menu">
        <li><i class="fas fa-home"></i><a href="admin-dashboard.php"> Dashboard</a></li>
            <li><i class="fas fa-plus-circle"></i><a href="admin-new.php"> Add Question</a></li>
            <li><i class="fas fa-trophy"></i><a href="admin-new-leaderboard.php"> Leaderboard</a></li>
        </ul>
        <div class="logout-container">
        <form action="logout.php" method="post">
        <button class="logout-button" type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
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

                   <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
               </form>
           </div>
       </div>

       <!-- View Questions Section -->
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
                               <th>Actions</th>
                           </tr>
                       </thead>
                       <tbody>
                           <?php
$stmt = $conn->prepare("SELECT * FROM questions");
$stmt->execute();
$result = $stmt->get_result();

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
           
           <td>
           <div class="table-option">
                   <span class="table-option-label"></span> <?php echo htmlspecialchars($row['correct_option']); ?>
               </div> 
           </td>
           <td>
           <div class="action-buttons">
                   <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
               </div>

           </td>
       </tr>
       <?php
   }
} else {
   echo "<tr><td colspan='3'>No questions found.</td></tr>";
}
?>
                       </tbody>
                   </table>
               </div>
           </div>
       </div>
   </div>
   </div>
   </div>

</body>
</html>