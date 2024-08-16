<?php
include('../../database/connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$lesson_id = $_POST['lesson_id'];
echo $lesson_id;

// Function to handle file upload
function handleFileUpload($fileInputName, $targetDir) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
        $targetFile = $targetDir . basename($_FILES[$fileInputName]['name']);
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFile)) {
            // Clean up the path to remove ../ or ./ before storing
            return str_replace(['../', './'], '', $targetFile);
        } else {
            return "";
        }
    }
    return "";
}

// Iterate through questions
foreach ($_POST as $key => $value) {
    if (strpos($key, 'question') === 0) {
        $questionNumber = str_replace('question', '', $key);
        $questionText = $_POST["question$questionNumber"];
        
        // Process the question text to replace spaces with placeholders
        $placeholder_count = 1;
        $questionText = preg_replace_callback('/\s{2,}/', function($matches) use (&$placeholder_count) {
            return "[BLANK" . $placeholder_count++ . "]";
        }, $questionText);
        
        // Handle question audio file upload
        $questionAudioPath = handleFileUpload("questionAudio$questionNumber", '../../src/question_audio/');

        // Insert or update question
        $questionSql = "INSERT INTO question (lesson_id, question_text, question_audio) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($questionSql);
        $stmt->bind_param("iss", $lesson_id, $questionText, $questionAudioPath);
        $stmt->execute();
        $questionId = $stmt->insert_id;

        // Check if options are set for this question
        if (isset($_POST["options$questionNumber"])) {
            $options = $_POST["options$questionNumber"];
            $correctOptions = isset($_POST["is_correct$questionNumber"]) ? $_POST["is_correct$questionNumber"] : [];

            foreach ($options as $index => $optionText) {
                // Determine if the option is correct (1 for true, 0 for false)
                $isCorrect = in_array($index + 1, $correctOptions) ? 1 : 0;
                $blankPosition = isset($_POST["blank_position$questionNumber"][$index]) ? $_POST["blank_position$questionNumber"][$index] : null;

                // Insert option
                $optionSql = "INSERT INTO draggable_options (question_id, drag_option_text, is_correct, blank_position) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($optionSql);
                $stmt->bind_param("issi", $questionId, $optionText, $isCorrect, $blankPosition);
                $stmt->execute();
            }
        }

        // Handle encouragement image and text
        $encouragementText = $_POST["encouragement$questionNumber"];
        $isEncouragement = isset($_POST["isEncouragement$questionNumber"]) ? 1 : 0;
        $encouragementImagePath = handleFileUpload("questionImage$questionNumber", '../../src/encouragement_source/');

        // Insert or update encouragement
        $encouragementSql = "INSERT INTO words (question_id, word_text, img_path, is_encouragement) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($encouragementSql);
        $stmt->bind_param("issi", $questionId, $encouragementText, $encouragementImagePath, $isEncouragement);
        $stmt->execute();
    }
}

echo "Quiz created successfully!";
header("Location: ../../library_page.php");
$conn->close();
?>
