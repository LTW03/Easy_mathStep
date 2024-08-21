<script>
        window.history.forward();
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="short icon" type= "x-icon" href="src/logo.png">
    <title>MCQs Game | E.M.S</title>
    <link rel="stylesheet" href="Css_folder/games.css">
</head>
<body>

<!-- PHP = database connection and fetch the options -->
    <?php
    include('database/connection.php');
    $lesson_id = $_GET['lesson_id']; 

    $question_sql = "SELECT q.question_text, q.question_id, q.question_audio
                        FROM question q 
                        WHERE q.lesson_id = $lesson_id
                        LIMIT 1"; 

    $question_result = $conn->query($question_sql);

    $question_text = "Question not found"; 
    $question_id = null;
    $question_audio = ""; 

    if ($question_result->num_rows > 0) {
        $question_row = $question_result->fetch_assoc();
        $question_text = $question_row["question_text"];
        $question_id = $question_row["question_id"];
        $question_audio = $question_row["question_audio"]; 
    }

    $answers_sql = "SELECT answer_text, is_correct 
                    FROM mcq_answer 
                    WHERE question_id = ?"; 

    $stmt = $conn->prepare($answers_sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $answers_result = $stmt->get_result();

    $options = [];
    $correct_option = null;

    while ($answer_row = $answers_result->fetch_assoc()) {
        $options[] = $answer_row["answer_text"];
        if ($answer_row["is_correct"] == 1) {
            $correct_option = $answer_row["answer_text"];
        }
    }

    $stmt->close();
    $conn->close();
    ?>

<!-- MCQs design -->
    <div class="background">
        <img src="src/games_images/shapes-top.png" alt="Top Shape" class="shape shape-top">
        <img src="src/games_images/blue-shape-b - Copy (2).png" alt="Blue Shape" class="shape shape-bottom">
        <img src="src/games_images/adaptive_01 - Copy.png" alt="Left Shape" class="shape shape-left">
        <img src="src/games_images/right-image - Copy (2).webp" alt="Right Shape" class="shape shape-right">
    </div>

    <div class="container_MCQs">
        <div class="game">
            <div class="question">
                <h1><?php echo htmlspecialchars($question_text); ?></h1>
                <img src="src/games_images/enable-sound_10352269.png" alt="sound icon" class="sound_icon" onclick="playSound()">
            </div>
        </div>
    </div> 

    <div class="buttons_MCQs">
        <?php
        $colors = ["red", "yellow", "blue", "purple"];
        foreach ($options as $index => $option) {
            if ($index < count($colors)) {
                echo '<button id="button-' . $index . '" class="' . $colors[$index] . '-button" draggable="true" onclick="checkAnswer(\'' . addslashes($option) . '\', this)">' . htmlspecialchars($option) . '</button>';
            }
        }
        ?>
    </div>

    <div id="popup" class="popup_game">
        <div class="encouragemenTcontainer">
            <p id="popup-text"></p>
            <img id="popup-img" src="" alt="Feedback Image">
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <?php if (!empty($question_audio)): ?>
        <audio id="question-audio" src="<?php echo htmlspecialchars($question_audio); ?>"></audio>
    <?php endif; ?>

<!-- JavaScript function -->
    <script>
        var currentQuestionId = <?php echo $question_id; ?>;
        var lesson_id = <?php echo json_encode($lesson_id); ?>;
        var correctAnswer = "<?php echo htmlspecialchars($correct_option); ?>";
        var score = 0;
        var incorrectQuestions = [];

        function checkAnswer(answer, button) {
            var buttons = document.querySelectorAll('.buttons_MCQs button');
            buttons.forEach(btn => btn.disabled = true); 
            if (answer === correctAnswer) {
                score++;
                button.style.backgroundColor = 'green'; 
                buttons.forEach(btn => {
                    if (btn !== button) btn.style.backgroundColor = 'red'; 
                });
                setTimeout(function() {
                    fetchFeedback(1); 
                }, 1000); 
            } else {
                incorrectQuestions.push({ question_id: currentQuestionId, correct_answer: correctAnswer });
                button.style.backgroundColor = 'red';
                buttons.forEach(btn => {
                    if (btn.textContent === correctAnswer) btn.style.backgroundColor = 'green'; 
                });
                setTimeout(function() {
                    fetchFeedback(0); 
                }, 1000); 
            }
        }
    </script>
    <script src="./JS_Folder/fetchquestionMcq.js"></script>

</body>
</html>
