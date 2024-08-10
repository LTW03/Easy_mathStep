<?php
include('Teacher_loginValidate.php');

//lesson_id
$lesson_id = $lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';

$sql = "SELECT question_id, question_text, question_audio FROM question WHERE lesson_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="Css_folder/Edit-Mcq.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <header class="header">
        <div class="back_btn">
            <a href="#" id="back_btn">
                <button class="backBtn_icon">
                    <i class="fas fa-chevron-left"></i>
                </button>
                back
            </a>
        </div>
        <a href="">
            <button class="Publish_Btn" onclick="saveQuiz(event)">
                Publish
                <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path d="M568.5 177.4L424.5 313.4C409.3 327.8 384 317.1 384 296v-72c-144.6 1-205.6 35.1-164.8 171.4 4.5 15-12.8 26.6-25 17.3C155.3 383.1 120 326.5 120 269.3c0-143.9 117.6-172.5 264-173.3V24c0-21.2 25.3-31.8 40.5-17.4l144 136c10 9.5 10 25.4 0 34.9zM384 379.1V448H64V128h50.9a12 12 0 0 0 8.6-3.7c15-15.6 32.2-27.9 51-37.7C185.7 80.8 181.6 64 169 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-88.8c0-8.3-8.2-14.1-16-11.3a71.8 71.8 0 0 1-34.2 3.4c-7.3-1-13.8 4.5-13.8 11.9z"/>
                </svg>
            </button>
        </a>
    </header>
    <div class="centralize_form">
        <div class="form_container">
            <h1>Edit Quiz</h1>
            <form id="quizForm" action="editquiz.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">

                <div id="questionsContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        $question_number = 1;
                        while($row = $result->fetch_assoc()) {
                            $question_id = $row['question_id'];
                            $question_text = $row['question_text'];
                            $question_audio = $row['question_audio'];

                            // Fetch corresponding words for the current question_id
                            $sql_words = "SELECT word_text, img_path, is_encouragement FROM words WHERE question_id = ?";
                            $stmt_words = $conn->prepare($sql_words);
                            $stmt_words->bind_param("i", $question_id);
                            $stmt_words->execute();
                            $result_words = $stmt_words->get_result();
                            $encouragement_text = '';
                            $encouragement_image = '';
                            $is_encouragement = '';
                            if ($result_words->num_rows > 0) {
                                $word_row = $result_words->fetch_assoc();
                                $encouragement_text = $word_row['word_text'];
                                $encouragement_image = $word_row['img_path'];
                                $is_encouragement = $word_row['is_encouragement'] == 1 ? 'checked' : '';
                            }
                    ?>
                    <div class="question-block" id="questionBlock<?php echo $question_number; ?>">
                        <input type="hidden" name="question_id[]" value="<?php echo $question_id; ?>">
                        <h3>Question <?php echo $question_number; ?></h3>
                        <label for="question<?php echo $question_number; ?>">Question:</label>
                        <input type="text" id="question<?php echo $question_number; ?>" name="question_text[]" value="<?php echo $question_text; ?>" required><br><br>

                        <label for="questionAudio<?php echo $question_number; ?>">Upload Question Audio:</label>
                        <?php if ($question_audio): ?>
                            <audio controls>
                                <source src="<?php echo $question_audio; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        <?php endif; ?>
                        <input type="file" id="questionAudio<?php echo $question_number; ?>" name="question_audio[]" accept="audio/*"><br><br>

                        <div class="options-container">
                            <?php
                            // Fetch answers for the current question
                            $sql_answers = "SELECT answer_text, mcq_audio, is_correct FROM mcq_answer WHERE question_id = ?";
                            $stmt_answers = $conn->prepare($sql_answers);
                            $stmt_answers->bind_param("i", $question_id);
                            $stmt_answers->execute();
                            $result_answers = $stmt_answers->get_result();

                            $option_number = 1;
                            while($answer_row = $result_answers->fetch_assoc()) {
                                $answer_text = $answer_row['answer_text'];
                                $mcq_audio = $answer_row['mcq_audio'];
                                $is_correct = $answer_row['is_correct'] == 1 ? "checked" : "";
                            ?>
                            <div class="option">
                                <input type="radio" name="correctAnswer<?php echo $question_number; ?>" value="option<?php echo $question_number . '_' . $option_number; ?>" <?php echo $is_correct; ?>>
                                <input type="text" id="option<?php echo $question_number . '_' . $option_number; ?>" name="answer_text[]" value="<?php echo $answer_text; ?>" required>
                                <?php if ($mcq_audio): ?>
                                    <audio controls>
                                        <source src="<?php echo $mcq_audio; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php endif; ?>
                                <input type="file" id="optionAudio<?php echo $question_number . '_' . $option_number; ?>" name="answer_audio[]" accept="audio/*">
                            </div>
                            <?php
                                $option_number++;
                            }
                            ?>
                        </div><br><br>

                        <label for="questionImage<?php echo $question_number; ?>">Upload Encouragement Image:</label>
                        <!-- Image preview and loader -->

                        <?php if ($encouragement_image): ?>
                            <img id="imagePreview<?php echo $question_number; ?>" src="<?php echo $encouragement_image; ?>" alt="Encouragement Image Preview" style="display: block;">
                        <?php else: ?>
                            <img id="imagePreview<?php echo $question_number; ?>" src="" alt="Encouragement Image Preview" style="display: none;">
                        <?php endif; ?><br><br>
                        <input type="file" id="questionImage<?php echo $question_number; ?>" name="question_image[]" accept="image/*" onchange="previewImage(event, 'imagePreview<?php echo $question_number; ?>', 'loader<?php echo $question_number; ?>')"><br><br>
                        <div id="loader<?php echo $question_number; ?>" class="loader" style="display: none;"></div><br><br>

                        <label for="encouragement<?php echo $question_number; ?>">Encouragement Text:</label>
                        <input type="text" id="encouragement<?php echo $question_number; ?>" name="encouragement_text[]" value="<?php echo $encouragement_text; ?>" placeholder="Enter encouragement text"><br><br>

                        <label for="isEncouragement<?php echo $question_number; ?>">
                            <input type="checkbox" id="isEncouragement<?php echo $question_number; ?>" name="is_encouragement[]" <?php echo $is_encouragement ? 'checked' : ''; ?>>
                            Mark as encouragement
                        </label><br><br>

                        <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock<?php echo $question_number; ?>')">X</button>
                    </div>
                    <?php
                        $question_number++;
                        }
                    } else {
                        echo "No questions found for the specified lesson_id.";
                    }
                    ?>
                </div>
                
                <div class="exercise_buttons">
                    <button type="button" onclick="addQuestion()">Add Question</button>
                </div>
            </form>
        </div>
    </div>

<script>
    let questionCount = <?php echo $question_number - 1; ?>;

    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');
        const newQuestionBlock = document.createElement('div');
        newQuestionBlock.className = 'question-block';
        newQuestionBlock.id = `questionBlock${questionCount}`;

        newQuestionBlock.innerHTML = `
            <h3>Question ${questionCount}</h3>
            <label for="question${questionCount}">Question:</label>
            <input type="text" id="question${questionCount}" name="question_text[]" required><br><br>

            <label for="questionAudio${questionCount}">Upload Question Audio:</label>

            <input type="file" id="questionAudio${questionCount}" name="question_audio[]" accept="audio/*"><br><br>

            <div class="options-container">
                <div class="option">
                    <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_1" required>
                    <input type="text" id="option${questionCount}_1" name="answer_text[]" placeholder="Option 1" required>
                    <input type="file" id="optionAudio${questionCount}_1" name="answer_audio[]" accept="audio/*">
                </div>
                <div class="option">
                    <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_2">
                    <input type="text" id="option${questionCount}_2" name="answer_text[]" placeholder="Option 2" required>
                    <input type="file" id="optionAudio${questionCount}_2" name="answer_audio[]" accept="audio/*">
                </div>
                <div class="option">
                    <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_3">
                    <input type="text" id="option${questionCount}_3" name="answer_text[]" placeholder="Option 3" required>
                    <input type="file" id="optionAudio${questionCount}_3" name="answer_audio[]" accept="audio/*">
                </div>
                <div class="option">
                    <input type="radio" name="correctAnswer${questionCount}" value="option${questionCount}_3">
                    <input type="text" id="option${questionCount}_4" name="answer_text[]" placeholder="Option " required>
                    <input type="file" id="optionAudio${questionCount}_4" name="answer_audio[]" accept="audio/*">
                </div>
            </div><br><br>

            <label for="questionImage${questionCount}">Upload Encouragement Image:</label>
            <input type="file" id="questionImage${questionCount}" name="question_image[]" accept="image/*" onchange="previewImage(event, 'imagePreview${questionCount}', 'loader${questionCount}')"><br><br>
            <img id="imagePreview${questionCount}" src="" alt="Encouragement Image Preview" style="display: none;"><br><br>
            <div id="loader${questionCount}" class="loader" style="display: none;"></div><br><br>

            <label for="encouragement${questionCount}">Encouragement Text:</label>
            <input type="text" id="encouragement${questionCount}" name="encouragement_text[]" placeholder="Enter encouragement text"><br><br>

            <label for="isEncouragement${questionCount}">
                <input type="checkbox" id="isEncouragement${questionCount}" name="is_encouragement[]">
                Mark as encouragement
            </label><br><br>

            <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock${questionCount}')"><i class="fas fa-trash-alt"></i></button>
        `;

        questionsContainer.appendChild(newQuestionBlock);
    }

function deleteQuestion(questionBlockId) {
    const questionBlock = document.getElementById(questionBlockId);
    questionBlock.remove();x

    // Renumber remaining questions
    const questionBlocks = document.querySelectorAll('.question-block');
    questionBlocks.forEach((block, index) => {
        const questionNumber = index + 1;
        block.querySelector('h3').textContent = `Question ${questionNumber}`;
        block.id = `questionBlock${questionNumber}`;
        block.querySelectorAll('input[id^="question"], input[id^="questionAudio"], input[id^="option"], input[id^="optionAudio"], input[id^="questionImage"], img[id^="imagePreview"], input[id^="encouragement"], input[id^="isEncouragement"]').forEach((input) => {
            const id = input.id;
            input.id = id.replace(/\d+$/, questionNumber);
        });
    });

    // Update question count
    questionCount = questionBlocks.length;
}


    function previewImage(event, previewId, loaderId) {
        const reader = new FileReader();
        const preview = document.getElementById(previewId);
        const loader = document.getElementById(loaderId);

        reader.onloadstart = () => {
            preview.style.display = "none";
            loader.style.display = "block";
        };

        reader.onload = () => {
            preview.src = reader.result;
            loader.style.display = "none";
            preview.style.display = "block";
        };

        reader.readAsDataURL(event.target.files[0]);
    }

    function saveQuiz(event) {
        event.preventDefault();
        document.getElementById('quizForm').submit();
    }
</script>
</body>
</html>

<?php
$conn->close();
?>