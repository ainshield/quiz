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
    $explanation = $question['explanation']; // Get explanation

    if (isset($userAnswers[$questionId])) {
        $userAnswer = $userAnswers[$questionId];
        if ($userAnswer === $correctOption) {
            $score++;
            $feedback[$questionId] = [
                'result' => 'correct',
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctOption,
                'question' => $question['question'], 
                'explanation' => $explanation
            ];
        } else {
            $feedback[$questionId] = [
                'result' => 'incorrect',
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctOption,
                'question' => $question['question'], 
                'explanation' => $explanation
            ];
        }
    } else {
        $feedback[$questionId] = [
            'result' => 'unanswered',
            'correctAnswer' => $correctOption,
            'question' => $question['question'],
            'explanation' => $explanation
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
    <link rel="stylesheet" href="../vendor/bootstrap.css">
    <style>
      body {
            /* font-family: Arial, sans-serif; */
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

        .result-container {
            width: auto;
            background-color: white;
        }

        .result {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-weight: normal;  /* Ensures all result divs have normal font weight */
        }

        .correct {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            font-weight: normal !important;  /* Overrides Bootstrap bold styles */
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
        <h1 style="text-align: center;">Your Score: <?php echo $score; ?> / <?php echo $totalQuestions; ?></h1>
        <div style="overflow-y: scroll; height: 65vh; padding: 20px;">
            <?php foreach ($feedback as $questionId => $result): ?>
                <div class="result <?php echo $result['result']; ?>">
                    <p><strong>Question:</strong> <?php echo htmlspecialchars($result['question']); ?></p>
                    <p><strong>Your Answer:</strong> 
                        <span class="<?php echo ($result['result'] === 'correct') ? 'correct-answer' : 'incorrect-answer'; ?>">
                            <?php echo $result['userAnswer'] ?? 'No Answer'; ?>
                        </span>
                    </p>
                    <p><strong>Correct Answer:</strong> <span class="correct-answer"><?php echo $result['correctAnswer']; ?></span></p>
                    <p><strong>Explanation:</strong> <?php echo htmlspecialchars($result['explanation']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <a href="quiz.php" class="btn btn-primary btn-lg">Play Again</a>
    </div>
</body>
</html>
