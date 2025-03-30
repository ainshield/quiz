<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "quiz_db");

    // Check for database connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Assuming the username is stored in the session
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 

    $stmt = $conn->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $options = [
            'A' => $row['option1'],
            'B' => $row['option2'],
            'C' => $row['option3'],
            'D' => $row['option4'],
        ];
        $optionKeys = array_keys($options);
        shuffle($optionKeys);
        $shuffledOptions = [];
        foreach ($optionKeys as $key) {
            $shuffledOptions[$key] = $options[$key];
        }
        $row['options'] = $shuffledOptions;
        $questions[] = $row;
    }

    $_SESSION['quiz_questions'] = $questions;
    $_SESSION['score'] = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <?php include_once '../vendor/bootstrap.php'; ?>
    <link rel="stylesheet" href="css/quiz.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
    <?php include_once 'sidebar.php'; ?>
    <div class="header" style="background-color: #004d40;">Quiz</div>
    <div class="main-content justify-content-center">
        <div class="card" style="width: auto; height: 85vh;">
            <div class="card-body">
                <div class="content">
                    <div id="quizContainer">
                        <h1>Quiz</h1>
                        <div id="questionContainer"></div>
                        <div id="questionCounter"></div>
                        <div class="next-btn-container">
                            <button id="submitAnswerBtn" onclick="checkAnswer()">Submit Answer</button>
                            <button id="nextBtn" onclick="loadNextQuestion()" style="display: none;">Next Question</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQuestionIndex = 0;
        let questions = <?php echo json_encode($questions); ?>;
        let userAnswers = {};

        function updateQuestionCounter() {
            const questionCounter = document.getElementById('questionCounter');
            questionCounter.innerHTML = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
        }

        function loadNextQuestion() {
            if (currentQuestionIndex >= questions.length) {
                submitQuiz();
                return;
            }

            const question = questions[currentQuestionIndex];
            const container = document.getElementById('questionContainer');

            container.innerHTML = `
                <div class="question-container">
                    <p>${question.question}</p>
                    <div class="question-options">
                        ${['A', 'B', 'C', 'D'].map(option => `
                            <label>
                                <input type="radio" name="answer" value="${option}"> ${question['option' + (option === 'A' ? 1 : option === 'B' ? 2 : option === 'C' ? 3 : 4)]}
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;
            
            updateQuestionCounter();

            document.getElementById('nextBtn').style.display = 'none';
            document.getElementById('submitAnswerBtn').style.display = 'block';
        }

        function checkAnswer() {
            const selectedOption = document.querySelector('input[name="answer"]:checked');
            if (!selectedOption) {
                alert('Please select an answer before proceeding.');
                return;
            }

            const userAnswer = selectedOption.value;
            const question = questions[currentQuestionIndex];
            userAnswers[question.id] = userAnswer;

            const correctOption = question.correct_option;
            const labels = document.querySelectorAll('label');
            labels.forEach(label => {
                const input = label.querySelector('input');
                if (input.value === correctOption) {
                    label.classList.add('correct');
                } else if (input.value === userAnswer) {
                    label.classList.add('wrong');
                }
            });

            document.getElementById('nextBtn').style.display = 'block';
            document.getElementById('submitAnswerBtn').style.display = 'none';
            currentQuestionIndex++;
        }

        function submitQuiz() {
            const formData = new FormData();
            for (const [questionId, userAnswer] of Object.entries(userAnswers)) {
                formData.append(`answers[${questionId}]`, userAnswer);
            }

            fetch('quiz/check_answers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('quizContainer').innerHTML = data;
                storeUserScore();
            })
            .catch(error => console.error('Error:', error));
        }

        function storeUserScore() {
            let score = 0;
            for (const question of questions) {
                const userAnswer = userAnswers[question.id];
                if (userAnswer === question.correct_option) {
                    score++;
                }
            }

            const username = '<?php echo $username; ?>'; // Ensure this is correctly embedded in the JS
            fetch('store_score.php', {
                method: 'POST',
                body: JSON.stringify({ username, score }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Score stored successfully', data);
            })
            .catch(error => {
                console.error('Error storing score:', error);
            });
        }

        loadNextQuestion();
    </script>
</body>
</html>
