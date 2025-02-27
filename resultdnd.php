<?php
$score = isset($_GET['score']) ? intval($_GET['score']) : 0;
$incorrectQuestions = isset($_GET['incorrectQuestions']) ? json_decode($_GET['incorrectQuestions'], true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results | E.M.S</title>
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <link rel="stylesheet" href="Css_folder/games.css">
</head>
<body>
    <div class="results-container">
        <h1>Your Quiz Results</h1>
        <p>You got <?php echo htmlspecialchars($score); marks ?></p>
        <ul>
            <?php if (count($incorrectQuestions) > 0): ?>
                <?php foreach ($incorrectQuestions as $question): ?>
                    <li>
                        Question <?php echo htmlspecialchars($question['question_id']); ?> - Blank <?php echo htmlspecialchars($question['blank_position']); ?> - Correct Answer: <?php echo htmlspecialchars($question['correct_answer']); ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </ul>
        <button onclick="clearCookiesAndRedirect()">Go to Home</button>
    </div>
    <script>
        function clearCookiesAndRedirect() {
            window.location.href = 'update_student_stat.php';
        }
    </script>

</body>
</html>
