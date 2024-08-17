<?php
include('../../database/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Directories for file uploads
    $QuestionTarget_dir = "../../src/question_audio/";
    $EncouragementTarget_dir = "../../src/encouragement_source/";

    // Fetch all questions for the lesson
    $sql = "SELECT question_id FROM question WHERE lesson_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $existingQuestions = $stmt->get_result();
    $existingQuestionIds = [];

    while ($row = $existingQuestions->fetch_assoc()) {
        $existingQuestionIds[] = $row['question_id'];
    }

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question') === 0) {
            $question_id = str_replace('question', '', $key);
            $question_text = $value;
            $question_audio = null;
            $encouragement_text = $_POST["encouragement$question_id"] ?? '';
            $is_encouragement = isset($_POST["isEncouragement$question_id"]) ? 1 : 0;

            // Handle question audio upload
            if (isset($_FILES["questionAudio$question_id"]) && $_FILES["questionAudio$question_id"]['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["questionAudio$question_id"]['tmp_name'];
                $audio_name = basename($_FILES["questionAudio$question_id"]['name']);
                $question_audio = $QuestionTarget_dir . $audio_name;

                // Move uploaded file
                move_uploaded_file($tmp_name, $question_audio);
                $question_audio = str_replace("../../", "", $question_audio); // Remove relative path
            } else {
                // Keep the existing audio path if no new file is uploaded
                $sql = "SELECT question_audio FROM question WHERE question_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $question_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $question_audio = $row['question_audio'];
                }
            }

            // Handle encouragement image upload
            $img_path = null;
            if (isset($_FILES["questionImage$question_id"]) && $_FILES["questionImage$question_id"]['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["questionImage$question_id"]['tmp_name'];
                $img_name = basename($_FILES["questionImage$question_id"]['name']);
                $img_path = $EncouragementTarget_dir . $img_name;

                // Move uploaded file
                move_uploaded_file($tmp_name, $img_path);
                $img_path = str_replace("../../", "", $img_path); // Remove relative path
            }

            // Update or insert question
            if (in_array($question_id, $existingQuestionIds)) {
                $sql = "UPDATE question SET question_text = ?, question_audio = ? WHERE question_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $question_text, $question_audio, $question_id);
                $stmt->execute();
                
                // Update encouragement text and image
                $sql = "UPDATE words SET word_text = ?, img_path = ?, is_encouragement = ? WHERE question_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $encouragement_text, $img_path, $is_encouragement, $question_id);
                $stmt->execute();

                // Update True/False option
                $is_true = $_POST["correctAnswer$question_id"] === 'true' ? 1 : 0;
                $sql = "UPDATE true_false_options SET is_true = ? WHERE question_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $is_true, $question_id);
                $stmt->execute();
            } else {
                // Insert new question
                $sql = "INSERT INTO question (question_text, question_audio, lesson_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $question_text, $question_audio, $lesson_id);
                $stmt->execute();
                $question_id = $conn->insert_id;

                // Insert new encouragement text and image
                $sql = "INSERT INTO words (word_text, img_path, is_encouragement, question_id) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $encouragement_text, $img_path, $is_encouragement, $question_id);
                $stmt->execute();

                // Insert True/False option
                $is_true = $_POST["correctAnswer$question_id"] === 'true' ? 1 : 0;
                $sql = "INSERT INTO true_false_options (question_id, is_true) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $question_id, $is_true);
                $stmt->execute();
            }
        }
    }

    echo "Update successful!";
}
?>
