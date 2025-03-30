<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quiz_db");

$userAnswers = $_POST['answers'] ?? [];
$quizQuestions = $_SESSION['quiz_questions'] ?? [];

$score = 0;
$feedback = [];

foreach ($quizQuestions as $question) {
    $questionId = $question['id'];
    $correctOption = $question['correct_option'];

    if (isset($userAnswers[$questionId])) {
        $userAnswer = $userAnswers[$questionId];
        if ($userAnswer === $correctOption) {
            $score++;
            $feedback[$questionId] = [
                'result' => 'correct',
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctOption,
                'question' => $question['question']
            ];
        } else {
            $feedback[$questionId] = [
                'result' => 'incorrect',
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctOption,
                'question' => $question['question']
            ];
        }
    } else {
        $feedback[$questionId] = [
            'result' => 'unanswered',
            'correctAnswer' => $correctOption,
            'question' => $question['question']
        ];
    }
}

$totalQuestions = count($quizQuestions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <style>
      body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fc;
}

.result-container {
    width: 80%;
    margin: auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    text-align: center;
}

.result {
    margin-bottom: 20px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.correct {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.incorrect {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

.unanswered {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffeeba;
}

.result p {
    margin: 5px 0;
}

.result a {
    display: block;
    text-align: center;
    margin-top: 30px;
    padding: 10px 20px;
    background-color: #52c755;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}

.result a:hover {
    background-color: #3aa442;
}

    </style>
</head>
<body>

<div class="result-container">
    <h1>Your Score: <?php echo $score; ?> / <?php echo $totalQuestions; ?></h1>
    
    <?php foreach ($feedback as $questionId => $result): ?>
        <div class="result <?php echo $result['result']; ?>">
            <p><strong>Question:</strong> <?php echo htmlspecialchars($result['question']); ?></p>
            <p><strong>Your Answer:</strong> <?php echo $result['userAnswer'] ?? 'No Answer'; ?></p>
            <p><strong>Correct Answer:</strong> <?php echo $result['correctAnswer']; ?></p>
        </div>
    <?php endforeach; ?>

    <a href="member-dashboard.php">Play Again</a>
</div>

</body>
</html>
