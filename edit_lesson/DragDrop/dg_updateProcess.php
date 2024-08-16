<?php
include('../../database/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// lesson_id
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Update questions
        $update_question_sql = "UPDATE question SET question_text = ?, question_audio = ? WHERE question_id = ?";
        $update_question_stmt = $conn->prepare($update_question_sql);

        // Update draggable_options
        $update_option_sql = "UPDATE draggable_options SET drag_option_text = ?, drag_option_audio = ?, is_correct = ?, blank_position = ? WHERE drag_option_id = ?";
        $update_option_stmt = $conn->prepare($update_option_sql);

        // Update words (encouragement)
        $update_word_sql = "UPDATE words SET word_text = ?, is_encouragement = ?, img_path = ? WHERE word_id = ?";
        $update_word_stmt = $conn->prepare($update_word_sql);

        foreach ($_POST['question_id'] as $question_id) {
            // Update question
            $question_text = $_POST['question_text'][$question_id];
            if ($_FILES['question_audio']['name'][$question_id]) {
                // Full path for file storage
                $file_storage_path = '../../src/question_audio/' . $_FILES['question_audio']['name'][$question_id];
                // Relative path for database storage
                $question_audio = 'src/question_audio/' . $_FILES['question_audio']['name'][$question_id];
                // Move the uploaded file
                move_uploaded_file($_FILES['question_audio']['tmp_name'][$question_id], $file_storage_path);
            } else {
                $question_audio = null;
            }

            $update_question_stmt->bind_param("ssi", $question_text, $question_audio, $question_id);
            $update_question_stmt->execute();

            // Update draggable options
            foreach ($_POST['drag_option_id'][$question_id] as $index => $option_id) {
                $option_text = $_POST['drag_option_text'][$question_id][$index];
                if ($_FILES['drag_option_audio']['name'][$question_id][$index]) {
                    // Full path for file storage
                    $file_storage_path = '../../src/option_audio/' . $_FILES['drag_option_audio']['name'][$question_id][$index];
                    // Relative path for database storage
                    $option_audio = 'src/option_audio/' . $_FILES['drag_option_audio']['name'][$question_id][$index];
                    // Move the uploaded file
                    move_uploaded_file($_FILES['drag_option_audio']['tmp_name'][$question_id][$index], $file_storage_path);
                } else {
                    $option_audio = null;
                }
                $is_correct = isset($_POST['is_correct'][$question_id][$option_id]) ? 1 : 0;  // Update this line to handle multiple checkboxes
                $blank_position = $_POST['blank_position'][$question_id][$index];

                $update_option_stmt->bind_param("ssiii", $option_text, $option_audio, $is_correct, $blank_position, $option_id);
                $update_option_stmt->execute();
            }

            // Update words (encouragement)
            if (isset($_POST['word_id'][$question_id])) {
                $word_id = $_POST['word_id'][$question_id];
                $word_text = $_POST['encouragement_text'][$question_id];
                $is_encouragement = isset($_POST['is_encouragement'][$question_id]) ? 1 : 0;
                if ($_FILES['encouragement_image']['name'][$question_id]) {
                    // Full path for file storage
                    $file_storage_path = '../../src/encouragement_source/' . $_FILES['encouragement_image']['name'][$question_id];
                    // Relative path for database storage
                    $img_path = 'src/encouragement_source/' . $_FILES['encouragement_image']['name'][$question_id];
                    // Move the uploaded file
                    move_uploaded_file($_FILES['encouragement_image']['tmp_name'][$question_id], $file_storage_path);
                } else {
                    $img_path = null;
                }

                $update_word_stmt->bind_param("sisi", $word_text, $is_encouragement, $img_path, $word_id);
                $update_word_stmt->execute();
            }
        }

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Drag and drop questions updated successfully!');   window.location.href = '../../library_page.php'</script>";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "<script>alert('Error updating drag and drop questions: " . $e->getMessage() . "');</script>";
    }
}


?>