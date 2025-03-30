<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$database = "quiz_db"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correctOption = $_POST['correctOption'];
    $explanation = isset($_POST['explanation']) ? $_POST['explanation'] : ''; // Default empty explanation
    
    $stmt = $conn->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option, explanation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $question, $option1, $option2, $option3, $option4, $correctOption, $explanation);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-addquestion.php");
    exit();
}

// Delete Question
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin-addquestion.php");
    exit();
}

// Update Question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_question'])) {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correctOption = $_POST['correctOption'];
    $explanation = isset($_POST['explanation']) ? $_POST['explanation'] : '';

    $stmt = $conn->prepare("UPDATE questions SET question=?, option1=?, option2=?, option3=?, option4=?, correct_option=?, explanation=? WHERE id=?");
    $stmt->bind_param("sssssssi", $question, $option1, $option2, $option3, $option4, $correctOption, $explanation, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin-addquestion.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oral Communication Admin Panel</title>
    <?php include_once '../vendor/bootstrap.php'; ?>
    <link rel="stylesheet" href="css/admin-add-q.css">
    <link rel="stylesheet" href="css/admin-sidebar.css">
</head>
<body>
    <?php include_once 'admin-sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="header d-flex justify-content-between align-items-center">
                <h2 class="card-title">View All Questions</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addQuestionModal">Add Question</button>
            </div>
            <div class="card">
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
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['question']); ?></td>
                                        <td>
                                            A: <?php echo htmlspecialchars($row['option1']); ?><br>
                                            B: <?php echo htmlspecialchars($row['option2']); ?><br>
                                            C: <?php echo htmlspecialchars($row['option3']); ?><br>
                                            D: <?php echo htmlspecialchars($row['option4']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['correct_option']); ?></td>
                                        <td><?php echo htmlspecialchars($row['explanation']); ?></td>
                                        <td>
                                        <button class="btn btn-warning btn-sm edit-btn"
                                            data-id="<?php echo $row['id']; ?>"
                                            data-question="<?php echo htmlspecialchars($row['question']); ?>"
                                            data-option1="<?php echo htmlspecialchars($row['option1']); ?>"
                                            data-option2="<?php echo htmlspecialchars($row['option2']); ?>"
                                            data-option3="<?php echo htmlspecialchars($row['option3']); ?>"
                                            data-option4="<?php echo htmlspecialchars($row['option4']); ?>"
                                            data-correct_option="<?php echo htmlspecialchars($row['correct_option']); ?>"
                                            data-explanation="<?php echo htmlspecialchars($row['explanation']); ?>"
                                            data-toggle="modal"
                                            data-target="#editQuestionModal">Edit
                                        </button>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php
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

    <!-- Add Question Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuestionModalLabel">Add New Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="question">Question</label>
                            <textarea id="question" name="question" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Options</label>
                            <input type="text" name="option1" class="form-control" required placeholder="Option A">
                            <input type="text" name="option2" class="form-control" required placeholder="Option B">
                            <input type="text" name="option3" class="form-control" required placeholder="Option C">
                            <input type="text" name="option4" class="form-control" required placeholder="Option D">
                        </div>
                        <div class="form-group">
                            <label for="correctOption">Correct Option</label>
                            <select id="correctOption" name="correctOption" class="form-control" required>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="explanation">Explanation (Optional)</label>
                            <textarea id="explanation" name="explanation" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuestionModalLabel">Edit Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editQuestion">Question</label>
                            <textarea id="editQuestion" name="question" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Options</label>
                            <input type="text" id="editOption1" name="option1" class="form-control" required placeholder="Option A">
                            <input type="text" id="editOption2" name="option2" class="form-control" required placeholder="Option B">
                            <input type="text" id="editOption3" name="option3" class="form-control" required placeholder="Option C">
                            <input type="text" id="editOption4" name="option4" class="form-control" required placeholder="Option D">
                        </div>
                        <div class="form-group">
                            <label for="editCorrectOption">Correct Option</label>
                            <select id="editCorrectOption" name="correctOption" class="form-control" required>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editExplanation">Explanation (Optional)</label>
                            <textarea id="editExplanation" name="explanation" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="edit_question" class="btn btn-primary">Update Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".edit-btn").click(function(){
                var id = $(this).data('id');
                var question = $(this).data('question');
                var option1 = $(this).data('option1');
                var option2 = $(this).data('option2');
                var option3 = $(this).data('option3');
                var option4 = $(this).data('option4');
                var correctOption = $(this).data('correct_option');
                var explanation = $(this).data('explanation');

                $("#editId").val(id);
                $("#editQuestion").val(question);
                $("#editOption1").val(option1);
                $("#editOption2").val(option2);
                $("#editOption3").val(option3);
                $("#editOption4").val(option4);
                $("#editCorrectOption").val(correctOption);
                $("#editExplanation").val(explanation);
            });
        });
    </script>

</body>
</html>
