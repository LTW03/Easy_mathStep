<?php
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
$stmt_question = $conn->prepare($sql_question);
$stmt_question->bind_param("ssi", $questionText, $questionAudio, $lesson_id);

$sql_tf_option = "INSERT INTO true_false_options (question_id, is_true) VALUES (?, ?)";
$stmt_tf_option = $conn->prepare($sql_tf_option);
$stmt_tf_option->bind_param("ii", $question_id, $isTrue);

$sql_encouragement = "INSERT INTO words (word_text, img_path, question_id, is_encouragement) VALUES (?, ?, ?, ?)";
$stmt_encouragement = $conn->prepare($sql_encouragement);
$stmt_encouragement->bind_param("ssii", $encouragementText, $encouragementImage, $question_id, $isEncouragement);

foreach ($_POST as $key => $value) {
    if (preg_match('/^question(\d+)$/', $key, $matches)) {
        $questionNumber = $matches[1];
        $questionText = $value;

        $questionAudioKey = 'questionAudio' . $questionNumber;
        $questionAudio = isset($_FILES[$questionAudioKey]) ? uploadFile($_FILES[$questionAudioKey], "../../src/question_audio/") : '';
        if ($questionAudio === null) {
            die("Failed to upload question audio for question $questionNumber");
        }
        $questionAudio = removeRelativePath($questionAudio);

        $stmt_question->execute();
        $question_id = $stmt_question->insert_id;

        $encouragementTextKey = 'encouragement' . $questionNumber;
        $encouragementText = isset($_POST[$encouragementTextKey]) ? $_POST[$encouragementTextKey] : '';

        $encouragementImageKey = 'questionImage' . $questionNumber;
        $encouragementImage = isset($_FILES[$encouragementImageKey]) ? uploadFile($_FILES[$encouragementImageKey], "../../src/encouragement_source/") : '';
        if ($encouragementImage === null) {
            die("Failed to upload encouragement image for question $questionNumber");
        }
        $encouragementImage = removeRelativePath($encouragementImage);

        $isEncouragementKey = 'isEncouragement' . $questionNumber;
        $isEncouragement = isset($_POST[$isEncouragementKey]) ? 1 : 0;

        $stmt_encouragement->execute();

        $correctAnswerKey = "correctAnswer${questionNumber}";
        if (isset($_POST[$correctAnswerKey])) {
            $isTrue = $_POST[$correctAnswerKey] === 'true' ? 1 : 0;
            $stmt_tf_option->execute();
        } else {
            echo "No true/false option found for question $questionNumber<br>";
        }
    }
}

$stmt_question->close();
$stmt_tf_option->close();
$stmt_encouragement->close();
$conn->close();
// header("Location: ../../library_page.php");
exit();
?>
