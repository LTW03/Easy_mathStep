<?php
include('../../Teacher_loginValidate.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// lesson_id
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';

$sql = "SELECT q.question_id, q.question_text, q.question_audio FROM question q 
        JOIN lesson l ON q.lesson_id = l.lesson_id 
        WHERE l.lesson_id = ? AND l.question_type = 'DragDrop'";
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
    <title>Edit Drag and Drop Quiz</title>
    <link rel="stylesheet" href="./css/update_drag_drop.css">
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
                Save 
                <svg class="svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path d="M568.5 177.4L424.5 313.4C409.3 327.8 384 317.1 384 296v-72c-144.6 1-205.6 35.1-164.8 171.4 4.5 15-12.8 26.6-25 17.3C155.3 383.1 120 326.5 120 269.3c0-143.9 117.6-172.5 264-173.3V24c0-21.2 25.3-31.8 40.5-17.4l144 136c10 9.5 10 25.4 0 34.9zM384 379.1V448H64V128h50.9a12 12 0 0 0 8.6-3.7c15-15.6 32.2-27.9 51-37.7C185.7 80.8 181.6 64 169 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-88.8c0-8.3-8.2-14.1-16-11.3a71.8 71.8 0 0 1-34.2 3.4c-7.3-1-13.8 4.5-13.8 11.9z"/>
                </svg>
            </button>
        </a>
    </header>
    <div class="centralize_form">
        <div class="form_container">
            <h1>Edit Drag and Drop Quiz</h1>
            <form id="quizForm" action="<?php echo'./dg_updateProcess.php?lesson_id='.$lesson_id; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">

                <div id="questionsContainer">
                    <?php
                    if ($result->num_rows > 0) {
                        $question_number = 1;
                        while ($row = $result->fetch_assoc()) {
                            $question_id = $row['question_id'];
                            $question_text = $row['question_text'];
                            $question_audio = $row['question_audio'];

                            // Fetch corresponding options for the current question_id
                            $sql_options = "SELECT drag_option_id, drag_option_text, drag_option_audio, is_correct, blank_position FROM draggable_options WHERE question_id = ?";
                            $stmt_options = $conn->prepare($sql_options);
                            $stmt_options->bind_param("i", $question_id);
                            $stmt_options->execute();
                            $result_options = $stmt_options->get_result();

                            // Fetch word (encouragement) for the current question_id
                            $sql_word = "SELECT word_id, word_text, is_encouragement, img_path FROM words WHERE question_id = ?";
                            $stmt_word = $conn->prepare($sql_word);
                            $stmt_word->bind_param("i", $question_id);
                            $stmt_word->execute();
                            $result_word = $stmt_word->get_result();
                            $word = $result_word->fetch_assoc();
                    ?>
                    <div class="question-block" id="questionBlock<?php echo $question_number; ?>">
                        <input type="hidden" name="question_id[]" value="<?php echo $question_id; ?>">
                        <h3>Question <?php echo $question_number; ?></h3>
                        <label for="question<?php echo $question_number; ?>">Question:</label>
                        <textarea id="question<?php echo $question_number; ?>" name="question_text[<?php echo $question_id; ?>]" required><?php echo $question_text; ?></textarea><br><br>

                        <label for="questionAudio<?php echo $question_number; ?>">Upload Question Audio:</label>
                        <?php if ($question_audio): ?>
                            <audio controls>
                                <source src="<?php echo "../../".$question_audio; ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        <?php endif; ?>
                        
                        <input type="file" id="questionAudio<?php echo $question_number; ?>" name="question_audio[<?php echo $question_id; ?>]" accept="audio/*"><br><br>

                        <div class="options-container">
                            <?php
                            $option_number = 1;
                            while ($option_row = $result_options->fetch_assoc()) {
                                $option_id = $option_row['drag_option_id'];
                                $option_text = $option_row['drag_option_text'];
                                $option_audio = $option_row['drag_option_audio'];
                                $is_correct = $option_row['is_correct'];
                                $blank_position = $option_row['blank_position'];
                            ?>
                            <div class="option">
                                <input type="hidden" name="drag_option_id[<?php echo $question_id; ?>][]" value="<?php echo $option_id; ?>">
                                <label for="option<?php echo $question_number . '_' . $option_number; ?>">Option <?php echo $option_number; ?>:</label>
                                <input type="text" id="option<?php echo $question_number . '_' . $option_number; ?>" name="drag_option_text[<?php echo $question_id; ?>][]" value="<?php echo $option_text; ?>" required>
                                <?php if ($option_audio): ?>
                                    <audio controls>
                                        <source src="<?php echo $option_audio; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php endif; ?>
                                <input type="file" id="optionAudio<?php echo $question_number . '_' . $option_number; ?>" name="drag_option_audio[<?php echo $question_id; ?>][]" accept="audio/*">
                                
                                <!-- Checkbox allowing multiple selections -->
                                <label>
                                    <input type="checkbox" name="is_correct[<?php echo $question_id; ?>][<?php echo $option_id; ?>]" value="1" <?php echo $is_correct ? 'checked' : ''; ?>> Correct
                                </label>
                                <input type="number" name="blank_position[<?php echo $question_id; ?>][]" value="<?php echo $blank_position; ?>" placeholder="Blank position">
                            </div>
                            <?php
                                $option_number++;
                            }
                            ?>
                        </div><br><br>

                        <?php if ($word): ?>
                            <input type="hidden" name="word_id[<?php echo $question_id; ?>]" value="<?php echo $word['word_id']; ?>">
                            <label for="encouragement<?php echo $question_number; ?>">Encouragement Text:</label>
                            <input type="text" id="encouragement<?php echo $question_number; ?>" name="encouragement_text[<?php echo $question_id; ?>]" value="<?php echo $word['word_text']; ?>"><br><br>

                            <label for="isEncouragement<?php echo $question_number; ?>">
                                <input type="checkbox" id="isEncouragement<?php echo $question_number; ?>" name="is_encouragement[<?php echo $question_id; ?>]" <?php echo $word['is_encouragement'] ? 'checked' : ''; ?>>
                                Mark as Encouragement
                            </label><br><br>

                            <label for="encouragementImage<?php echo $question_number; ?>">Upload Encouragement Image:</label>
                            <?php if ($word['img_path']): ?>
                                <img src="<?php echo $word['img_path']; ?>" alt="Encouragement Image" style="max-width: 200px;">
                            <?php endif; ?>
                            <input type="file" id="encouragementImage<?php echo $question_number; ?>" name="encouragement_image[<?php echo $question_id; ?>]" accept="image/*">
                        <?php endif; ?>
                    </div>
                    <?php
                            $question_number++;
                        }
                    } else {
                        echo "No drag and drop questions found.";
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
    <script src="./JS/update_dg.js"></script>
</body>
</html>