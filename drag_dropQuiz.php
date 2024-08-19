<script>
        window.history.forward();
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Game</title>
    <link rel="stylesheet" href="./Css_folder/gamednd.css">
</head>
<body>

<!-- PHP: Database connection and fetch the question and options -->
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
        $question_audio = $question_row["question_audio"] ? $question_row["question_audio"] : ""; // Default to empty string
    }

    $options_sql = "SELECT d.drag_option_text, d.is_correct, d.blank_position 
                    FROM draggable_options d 
                    WHERE d.question_id = ?"; 

    $stmt = $conn->prepare($options_sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $options_result = $stmt->get_result();

    $options = [];

    while ($option_row = $options_result->fetch_assoc()) {
        $options[] = $option_row;
    }

    $stmt->close();
$conn->close();
?>


<!-- Drag and Drop Design -->
<div class="background">
        <img src="src/games_images/shapes-top.png" alt="Top Shape" class="shape shape-topdnd">
        <img src="src/games_images/blue-shape-b - Copy (2).png" alt="Blue Shape" class="shape shape-bottomdnd">
        <img src="src/games_images/adaptive_01 - Copy.png" alt="Left Shape" class="shape shape-left">
        <img src="src/games_images/right-image - Copy (2).webp" alt="Right Shape" class="shape shape-rightdnd">
</div>

<div class="container_MCQs">
    <div class="game-dnd">
        <div class="question" id="question-container" data-question-id="<?php echo $question_id; ?>">
            <h1 class="drop" id="question-text">
                <?php
                echo preg_replace('/\[BLANK(\d+)\]/', '<span class="dropzone" data-correct="$1">______</span>', htmlspecialchars($question_text));
                ?>
            </h1>
                <img src="src/games_images/enable-sound_10352269.png" alt="sound icon" class="sound_icon" id="sound-icon" data-audio-url="<?php echo htmlspecialchars($question_audio); ?>" onclick="playSound()">
            </div>
    </div>
</div>

<div class="buttons_dnd" id="options-container">
    <?php
    $colors = ["red", "yellow", "blue", "purple", "green", "orange"];
    foreach ($options as $index => $option) {
        echo '<button class="' . $colors[$index % count($colors)] . '-button draggable-option" draggable="true" data-correct="' . $option['is_correct'] . '" data-position="' . $option['blank_position'] . '">' . htmlspecialchars($option['drag_option_text']) . '</button>';
    } 
    ?>
</div>

<button class="button" onclick="checkAnswers()">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
    </svg>
    <div class="text">Next</div>
</button>

<div id="popup" class="popup_game">
    <div class="encouragemenTcontainer">
        <p id="popup-text"></p>
        <img id="popup-img" src="" alt="Feedback Image">
        <button onclick="closePopup()">Close</button>
    </div>
</div>

<!-- function javasript -->
<script>
    var lesson_id = <?php echo json_encode($lesson_id); ?>;
    let incorrectQuestions = [];
    let score = 0;

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.buttons_dnd button');
        const dropzones = document.querySelectorAll('.dropzone');

        buttons.forEach(button => {
            button.addEventListener('dragstart', dragStart);
        });

        dropzones.forEach(zone => {
            zone.addEventListener('dragover', dragOver);
            zone.addEventListener('drop', drop);
        });
    });

    function dragStart(e) {
        e.dataTransfer.setData('text/plain', e.target.textContent);
        e.dataTransfer.setData('data-position', e.target.getAttribute('data-position'));
    }

    function dragOver(e) {
        e.preventDefault(); 
    }

    function drop(e) {
        e.preventDefault();
        const data = e.dataTransfer.getData('text/plain');
        e.target.textContent = data;
        e.target.setAttribute('data-user-input', e.dataTransfer.getData('data-position'));
        e.target.style.backgroundColor = 'lightyellow'; 
    }

    function checkAnswers() {
        const dropzones = document.querySelectorAll('.dropzone');
        let allCorrect = true;
        let currentQuestionId = document.getElementById('question-container').getAttribute('data-question-id');

        dropzones.forEach(zone => {
            const correctPosition = zone.getAttribute('data-correct');
            const userPosition = zone.getAttribute('data-user-input');

            if (userPosition === correctPosition) {
                zone.style.backgroundColor = 'lightgreen';
                score += parseInt(correctPosition); 
            } else {
                zone.style.backgroundColor = 'lightcoral';
                allCorrect = false;
                const correctAnswerText = document.querySelector(`.draggable-option[data-position="${correctPosition}"]`).textContent;
                incorrectQuestions.push({
                    question_id: currentQuestionId,
                    blank_position: correctPosition,
                    correct_answer: correctAnswerText
                });
            }
        });

        fetchPopupData(allCorrect);
    }

    function submitResults() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_results.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = 'resultdnd.php?score=' + score + '&incorrectQuestions=' + encodeURIComponent(JSON.stringify(incorrectQuestions));
            }
        };
        xhr.send(JSON.stringify({
            score: score,
            incorrectQuestions: incorrectQuestions
        }));
    }

    function fetchPopupData(allCorrect) {
        const currentQuestionId = document.getElementById('question-container').getAttribute('data-question-id');

        fetch('fetch-incorrect/fetch_incorrect_feebackdnd.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ currentQuestionId: currentQuestionId, allCorrect: allCorrect })
        })
        .then(response => response.json())
        .then(data => {
            if (data.showPopup) {
                setTimeout(() => {
                    displayPopup(data.popupText, data.popupImg);
                }, 500);
            } else if (allCorrect) {
                setTimeout(() => {
                    alert('All answers are correct!');
                    fetchNextQuestion();
                }, 500);
            } else {
                setTimeout(() => {
                    alert('Some answers are incorrect, try again!');
                    fetchNextQuestion();
                }, 500);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function displayPopup(text, imgSrc) {
        document.getElementById('popup-text').textContent = text;
        document.getElementById('popup-img').src = imgSrc;
        document.getElementById('popup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        fetchNextQuestion();
    }

    function fetchNextQuestion() {
        const currentQuestionId = document.getElementById('question-container').getAttribute('data-question-id');
        fetch('fetch-next/fetch_next_questiondnd.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ currentQuestionId: currentQuestionId, lessonId: lesson_id})
        })
        .then(response => response.json())
        .then(data => {
            if (data.question_id) {
                updateQuestionAndOptions(data);
            } else {
                submitResults(); 
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function finishGame() {
        const score = calculateScore();
        const incorrectQuestions = getIncorrectQuestions();

        submitResults(score, incorrectQuestions);
    }

    function submitResults() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_results.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                window.location.href = 'resultdnd.php?score=' + score + '&incorrectQuestions=' + encodeURIComponent(JSON.stringify(incorrectQuestions));
            }
        };
        xhr.send(JSON.stringify({
            score: score,
            incorrectQuestions: incorrectQuestions
        }));
    }

    function calculateScore() {
        let score = 0;
        document.querySelectorAll('.dropzone').forEach(zone => {
            if (zone.style.backgroundColor === 'lightgreen') {
                score += parseInt(zone.getAttribute('data-correct'), 10); 
            }
        });
        return score;
    }

    function getIncorrectQuestions() {
        const incorrectQuestions = [];
        document.querySelectorAll('.dropzone').forEach(zone => {
            if (zone.style.backgroundColor === 'lightcoral') {
                incorrectQuestions.push({
                    question_id: document.getElementById('question-container').getAttribute('data-question-id'),
                    correct_answer: zone.getAttribute('data-correct')
                });
            }
        });
        return incorrectQuestions;
    }

    function updateQuestionAndOptions(data) {
        const questionContainer = document.getElementById('question-container');
        const questionTextElem = document.getElementById('question-text');
        const optionsContainer = document.getElementById('options-container');
        const soundIcon = document.getElementById('sound-icon');

        questionTextElem.innerHTML = data.question_text.replace(/\[BLANK(\d+)\]/g, '<span class="dropzone" data-correct="$1">______</span>');
        questionContainer.setAttribute('data-question-id', data.question_id);

        soundIcon.setAttribute('data-audio-url', data.question_audio || '');

        optionsContainer.innerHTML = '';

        const colors = ["red", "yellow", "blue", "purple"];
        data.options.forEach((option, index) => {
            const button = document.createElement('button');
            button.className = colors[index % colors.length] + '-button draggable-option';
            button.draggable = true;
            button.textContent = option.drag_option_text;
            button.setAttribute('data-correct', option.is_correct);
            button.setAttribute('data-position', option.blank_position);
            button.addEventListener('dragstart', dragStart);
            optionsContainer.appendChild(button);
        });

        const dropzones = document.querySelectorAll('.dropzone');
        dropzones.forEach(zone => {
            zone.addEventListener('dragover', dragOver);
            zone.addEventListener('drop', drop);
        });
    }

    function playSound() {
        const soundIcon = document.getElementById('sound-icon');
        const audioUrl = soundIcon.getAttribute('data-audio-url');

        if (audioUrl && audioUrl.trim() !== "") {
            const audio = new Audio(audioUrl);
            audio.play().catch(error => console.error('Error playing audio:', error));
        } else {
            console.log('No audio URL available.');
        }
    }
</script>

</body>
</html>
