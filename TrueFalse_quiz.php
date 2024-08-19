<script>
        window.history.forward();
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>True False Game | E.M.S</title>
    <link rel="stylesheet" href="./Css_folder/games.css">
</head>
<body>

<?php
    include('database/connection.php');
    $lesson_id = $_GET['lesson_id']; 
    $question_text = "No questions available";
    $is_true = null;
    $question_id = 0;
    $question_audio = ""; 

    $sql = "SELECT q.question_id, q.question_text, tfo.is_true, q.question_audio
            FROM question q 
            JOIN true_false_options tfo ON q.question_id = tfo.question_id 
            WHERE q.lesson_id = $lesson_id 
            LIMIT 1"; 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $question_text = $row["question_text"];
        $is_true = $row["is_true"];
        $question_id = $row["question_id"];
        $question_audio = $row["question_audio"];
    }


$conn->close();
?>

<div class="background">
        <img src="src/games_images/shapes-top.png" alt="Top Shape" class="shape shape-top">
        <img src="src/games_images/blue-shape-b - Copy (2).png" alt="Blue Shape" class="shape shape-bottom">
        <img src="src/games_images/adaptive_01 - Copy.png" alt="Left Shape" class="shape shape-left">
        <img src="src/games_images/right-image - Copy (2).webp" alt="Right Shape" class="shape shape-right">
</div>

<div class="container_truefalse">
    <div class="game">
        <div class="question">
            <h1 id="question-text"><?php echo htmlspecialchars($question_text); ?></h1>
            <img src="src/games_images/enable-sound_10352269.png" alt="sound icon" class="sound_icon" onclick="playSound()">
        </div>
    </div>
</div> 

<div class="buttons_true_false">
    <button class="true-button" onclick="checkAnswer(true)">True</button>
    <button class="false-button" onclick="checkAnswer(false)">False</button>
</div>

<div id="popup" class="popup_game">
    <div class="encouragemenTcontainer">
        <p id="popup-text"></p>
        <img id="popup-img" src="" alt="Feedback Image">
        <button onclick="closePopup()">Close</button>
    </div>
</div>

<audio id="question-audio" src="<?php echo htmlspecialchars($question_audio); ?>"></audio>

<script>
    var currentQuestionId = <?php echo $question_id; ?>;
    var correctAnswer = <?php echo $is_true ? 'true' : 'false'; ?>;
    var lessonId = <?php echo json_encode($lesson_id); ?>;
    var score = 0;
    var incorrectQuestions = [];

    function checkAnswer(answer) {
        var trueButton = document.querySelector('.true-button');
        var falseButton = document.querySelector('.false-button');

        if (answer === correctAnswer) {
            score++;
            if (correctAnswer === true) {
                trueButton.style.backgroundColor = 'green'; 
                falseButton.style.backgroundColor = 'red';  
            } else {
                falseButton.style.backgroundColor = 'green'; 
                trueButton.style.backgroundColor = 'red';    
            }
            setTimeout(function() {
                fetchFeedback(1); 
            }, 1000); 
        } else {
            incorrectQuestions.push({ question_id: currentQuestionId, correct_answer: correctAnswer });
            if (correctAnswer === true) {
                trueButton.style.backgroundColor = 'green'; 
                falseButton.style.backgroundColor = 'red';  
            } else {
                falseButton.style.backgroundColor = 'green'; 
                trueButton.style.backgroundColor = 'red';    
            }
            setTimeout(function() {
                fetchFeedback(0); 
            }, 1000); 
        }
    }
</script>

<script src="./JS_Folder/fetchquestiontf.js"></script>

</body>
</html>
