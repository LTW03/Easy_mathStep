<?php
session_start();
$score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
$incorrectQuestions = isset($_SESSION['incorrectQuestions']) ? $_SESSION['incorrectQuestions'] : [];

session_unset(); 
session_destroy(); 
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
        <p>You got <?php echo htmlspecialchars($score); ?> correct out of <?php echo htmlspecialchars($score + count($incorrectQuestions)); ?> questions.</p>
        <ul>
            <?php foreach ($incorrectQuestions as $question): ?>
                <li>
                    Question <?php echo htmlspecialchars($question['question_id']); ?> - Correct Answer: <?php echo htmlspecialchars($question['correct_answer']); ?>
                </li>
            <?php endforeach; ?>
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
