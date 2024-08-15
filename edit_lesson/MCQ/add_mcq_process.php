<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../database/connection.php');

$lesson_id = $_POST['lesson_id'];

function uploadFile($file, $target_dir) {
    if ($file["error"] === UPLOAD_ERR_NO_FILE) {
        return ''; 
    } elseif ($file["error"] !== UPLOAD_ERR_OK) {
        echo "File upload error: " . $file["error"];
        return null; 
    }

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); 
    }

    $target_file = $target_dir . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file; 
    } else {
        echo "Error moving file.";
        return null; 
    }
}

function removeRelativePath($path) {
    return str_replace('../../', '', $path);
}

$sql_question = "INSERT INTO question (question_text, question_audio, lesson_id) VALUES (?, ?, ?)";
$stmt_question = $conn->prepare($sql_question) or die("Prepare failed: " . $conn->error);
$stmt_question->bind_param("ssi", $questionText, $questionAudio, $lesson_id);

$sql_option = "INSERT INTO mcq_answer (question_id, answer_text, mcq_audio, is_correct) VALUES (?, ?, ?, ?)";
$stmt_option = $conn->prepare($sql_option) or die("Prepare failed: " . $conn->error);
$stmt_option->bind_param("issi", $question_id, $optionText, $optionAudio, $isCorrect);

$sql_encouragement = "INSERT INTO words (word_text, img_path, question_id, is_encouragement) VALUES (?, ?, ?, ?)";
$stmt_encouragement = $conn->prepare($sql_encouragement) or die("Prepare failed: " . $conn->error);
$stmt_encouragement->bind_param("ssii", $encouragementText, $encouragementImage, $question_id, $isEncouragement);

foreach ($_POST as $key => $value) {
    if (strpos($key, 'question') !== false && !strpos($key, 'option')) {
        $questionText = $value;
        $questionNumber = str_replace('question', '', $key);

        $questionAudioKey = 'questionAudio' . $questionNumber;
        $questionAudio = isset($_FILES[$questionAudioKey]) ? uploadFile($_FILES[$questionAudioKey], "../../src/question_audio/") : '';
        if ($questionAudio === null) {
            die("Failed to upload question audio for question $questionNumber");
        }
        $questionAudio = removeRelativePath($questionAudio); // Remove relative path

        $stmt_question->execute() || die("Execute failed: " . $stmt_question->error);
        $question_id = $stmt_question->insert_id; 

        $encouragementTextKey = 'encouragement' . $questionNumber;
        $encouragementText = isset($_POST[$encouragementTextKey]) ? $_POST[$encouragementTextKey] : '';

        $encouragementImageKey = 'questionImage' . $questionNumber;
        $encouragementImage = isset($_FILES[$encouragementImageKey]) ? uploadFile($_FILES[$encouragementImageKey], "../../src/encouragement_source/") : '';
        if ($encouragementImage === null) {
            die("Failed to upload encouragement image for question $questionNumber");
        }
        $encouragementImage = removeRelativePath($encouragementImage); // Remove relative path

        $isEncouragementKey = 'isEncouragement' . $questionNumber;
        $isEncouragement = isset($_POST[$isEncouragementKey]) ? 1 : 0; 

        $stmt_encouragement->execute() || die("Execute failed: " . $stmt_encouragement->error);

        for ($i = 1; $i <= 4; $i++) {
            $optionTextKey = "option${questionNumber}_$i";
            $optionAudioKey = "optionAudio${questionNumber}_$i";

            $optionText = isset($_POST[$optionTextKey]) ? $_POST[$optionTextKey] : '';
            $optionAudio = isset($_FILES[$optionAudioKey]) ? uploadFile($_FILES[$optionAudioKey], "../../src/option_audio/") : '';

            if ($optionAudio !== null) {
                $optionAudio = removeRelativePath($optionAudio); // Remove relative path

                $isCorrect = (isset($_POST["correctAnswer${questionNumber}"]) && $_POST["correctAnswer${questionNumber}"] == "option${questionNumber}_$i") ? 1 : 0;

                $stmt_option->execute() || die("Execute failed: " . $stmt_option->error);
            } else {
                echo "Option audio $optionAudioKey not found or empty.<br>";
            }
        }
    }
}
header("Location: ../../library_page.php");
$stmt_question->close();
$stmt_option->close();
$stmt_encouragement->close();
$conn->close();

exit();
?>
