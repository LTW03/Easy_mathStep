<?php
include('../../Teacher_loginValidate.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get lesson_id from query parameter
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';

// Fetch existing True/False questions
$sql = "SELECT q.question_id, q.question_text, q.question_audio, tf.is_true, w.word_text, w.img_path, w.is_encouragement
        FROM question q
        LEFT JOIN true_false_options tf ON q.question_id = tf.question_id
        LEFT JOIN words w ON q.question_id = w.question_id
        WHERE q.lesson_id = ?";
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
    <title>Update True/False Quiz | E.M.S</title>
    <link rel="stylesheet" href="./CSS/addTFquestion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
    <div class="header">
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
                Save
                <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path d="M568.5 177.4L424.5 313.4C409.3 327.8 384 317.1 384 296v-72c-144.6 1-205.6 35.1-164.8 171.4 4.5 15-12.8 26.6-25 17.3C155.3 383.1 120 326.5 120 269.3c0-143.9 117.6-172.5 264-173.3V24c0-21.2 25.3-31.8 40.5-17.4l144 136c10 9.5 10 25.4 0 34.9zM384 379.1V448H64V128h50.9a12 12 0 0 0 8.6-3.7c15-15.6 32.2-27.9 51-37.7C185.7 80.8 181.6 64 169 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-88.8c0-8.3-8.2-14.1-16-11.3a71.8 71.8 0 0 1-34.2 3.4c-7.3-1-13.8 4.5-13.8 11.9z"/>
                </svg>
            </button>
        </a>
    </div>

    <div class="centralize_form">
        <div class="form_container">
            <h1>Edit True/False Quiz</h1>
            <form id="quizForm" action="./update_TFprocess.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">

                <div id="questionsContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        $questionCount = 0;
                        while ($row = $result->fetch_assoc()) {
                            $questionCount++;
                            $isTrue = $row['is_true'] ? 'true' : 'false';
                            ?>
                            <div class="question-block" id="questionBlock<?php echo $questionCount; ?>">
                                <h3>Question <?php echo $questionCount; ?></h3>
                                <label for="question<?php echo $questionCount; ?>">Question:</label>
                                <input type="text" id="question<?php echo $questionCount; ?>" name="question<?php echo $row['question_id']; ?>" value="<?php echo $row['question_text']; ?>" required>

                                <label for="questionAudio<?php echo $questionCount; ?>">Upload Question Audio:</label>
                                <input type="file" id="questionAudio<?php echo $questionCount; ?>" name="questionAudio<?php echo $row['question_id']; ?>" accept="audio/*">
                                <?php if ($row['question_audio']) { ?>
                                    <audio controls>
                                        <source src="<?php echo '../../'.$row['question_audio']; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php } ?>

                                <div class="options-container">
                                    <div class="option">
                                        <input type="radio" id="true<?php echo $questionCount; ?>" name="correctAnswer<?php echo $row['question_id']; ?>" value="true" <?php echo $isTrue == 'true' ? 'checked' : ''; ?> required>
                                        <label for="true<?php echo $questionCount; ?>">True</label>
                                    </div>
                                    <div class="option">
                                        <input type="radio" id="false<?php echo $questionCount; ?>" name="correctAnswer<?php echo $row['question_id']; ?>" value="false" <?php echo $isTrue == 'false' ? 'checked' : ''; ?> required>
                                        <label for="false<?php echo $questionCount; ?>">False</label>
                                    </div>
                                </div>

                                <label for="questionImage<?php echo $questionCount; ?>">Upload Encouragement Image:</label>
                                <input type="file" id="questionImage<?php echo $questionCount; ?>" name="questionImage<?php echo $row['question_id']; ?>" accept="image/*" onchange="previewImage(event, 'imagePreview<?php echo $questionCount; ?>', 'loader<?php echo $questionCount; ?>')">
                                <?php if ($row['img_path']) { ?>
                                    <img id="imagePreview<?php echo $questionCount; ?>" src="<?php echo '../../'.$row['img_path']; ?>" alt="Encouragement Image Preview" style="display: block;"><br><br>
                                <?php } else { ?>
                                    <img id="imagePreview<?php echo $questionCount; ?>" src="" alt="Encouragement Image Preview" style="display: none;"><br><br>
                                <?php } ?>
                                <div id="loader<?php echo $questionCount; ?>" class="loader" style="display: none;"></div>

                                <label for="encouragement<?php echo $questionCount; ?>">Encouragement Text:</label>
                                <input type="text" id="encouragement<?php echo $questionCount; ?>" name="encouragement<?php echo $row['question_id']; ?>" placeholder="Enter encouragement text" value="<?php echo $row['word_text']; ?>"><br><br>

                                <label for="isEncouragement<?php echo $questionCount; ?>">
                                    <input type="checkbox" id="isEncouragement<?php echo $questionCount; ?>" name="isEncouragement<?php echo $row['question_id']; ?>" <?php echo $row['is_encouragement'] ? 'checked' : ''; ?>>
                                    Mark as encouragement
                                </label>

                                <button type="button" class="delete-question" onclick="deleteQuestion('questionBlock<?php echo $questionCount; ?>')"><i class="fas fa-trash-alt"></i></button>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No True/False questions found for this lesson.</p>";
                    }
                    ?>
                </div>

                <div class="exercise_buttons">
                    <button type="button" onclick="addQuestion()">Add Question</button>
                </div>
            </form>
        </div>
    </div>

    <script src = "./JS/addTFquestion.js"></script>
</body>
</html>
