<?php

include('database/connection.php');

$quizName = $_POST['quizName'];
$teacher_email = $_COOKIE['user_email'];

function uploadFile($file, $target_dir) {
    if ($file["error"] === UPLOAD_ERR_NO_FILE) {
        return ''; 
    } elseif ($file["error"] !== UPLOAD_ERR_OK) {
        echo "File upload error: " . $file["error"];
        return null; 
    }

    // Ensure target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); 
    }

    $target_file = $target_dir . basename($file["name"]);

    // Move uploaded file to target directory
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file; 
    } else {
        echo "Error moving file.";

    }
}

$sql = "INSERT INTO lesson (lesson_name, question_type, teacher_email) VALUES (?, 'MCQ', ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $quizName, $teacher_email);

if ($stmt->execute()) {
    $lesson_id = $stmt->insert_id; 
} else {
    echo "Error inserting lesson: " . $stmt->error;
    exit();
}

$sql_question = "INSERT INTO question (question_text, question_audio, lesson_id) VALUES (?, ?, ?)";
$stmt_question = $conn->prepare($sql_question);
$stmt_question->bind_param("ssi", $questionText, $questionAudio, $lesson_id);

$sql_option = "INSERT INTO mcq_answer (question_id, answer_text, mcq_audio, is_correct) VALUES (?, ?, ?, ?)";
$stmt_option = $conn->prepare($sql_option);
$stmt_option->bind_param("issi", $question_id, $optionText, $optionAudio, $isCorrect);

$sql_encouragement = "INSERT INTO words (word_text, img_path, question_id, is_encouragement) VALUES (?, ?, ?, ?)";
$stmt_encouragement = $conn->prepare($sql_encouragement);
$stmt_encouragement->bind_param("ssii", $encouragementText, $encouragementImage, $question_id, $isEncouragement);


foreach ($_POST as $key => $value) {
    if (strpos($key, 'question') !== false && !strpos($key, 'option')) {
        $questionText = $value;
        $questionNumber = str_replace('question', '', $key);

        // Upload question audio if provided
        $questionAudioKey = 'questionAudio' . $questionNumber;
        $questionAudio = isset($_FILES[$questionAudioKey]) ? uploadFile($_FILES[$questionAudioKey], "src/question_audio/") : '';
        if ($questionAudio === null) {
            die("Failed to upload question audio for question $questionNumber");
        }

        // Insert question into question table 
        $stmt_question->execute();
        $question_id = $stmt_question->insert_id; // Get last inserted ID

        // Get encouragement data
        $encouragementTextKey = 'encouragement' . $questionNumber;
        $encouragementText = isset($_POST[$encouragementTextKey]) ? $_POST[$encouragementTextKey] : '';

        $encouragementImageKey = 'questionImage' . $questionNumber;
        $encouragementImage = isset($_FILES[$encouragementImageKey]) ? uploadFile($_FILES[$encouragementImageKey], "src/encouragement_source/") : '';
        if ($encouragementImage === null) {
            die("Failed to upload encouragement image for question $questionNumber");
        }

        
        $isEncouragementKey = 'isEncouragement' . $questionNumber;
        $isEncouragement = isset($_POST[$isEncouragementKey]) ? 1 : 0; 

        $stmt_encouragement->execute();

        // Insert options into mcq_answer table
        for ($i = 1; $i <= 4; $i++) {
            $optionTextKey = "option${questionNumber}_$i";
            $optionAudioKey = "optionAudio${questionNumber}_$i";

            $optionText = isset($_POST[$optionTextKey]) ? $_POST[$optionTextKey] : '';
            $optionAudio = isset($_FILES[$optionAudioKey]) ? uploadFile($_FILES[$optionAudioKey], "src/option_audio/") : '';

            // Check option audio is uploaded
            if ($optionAudio !== null) {
                $isCorrect = (isset($_POST["correctAnswer${questionNumber}"]) && $_POST["correctAnswer${questionNumber}"] == "option${questionNumber}_$i") ? 1 : 0;

                $stmt_option->execute();
            } else {
                echo "Option audio $optionAudioKey not found or empty.<br>";
            }
        }
    }
}

$stmt_question->close();
$stmt_option->close();
$stmt_encouragement->close();
$conn->close();
header("Location: library_page.php");
exit();
?>
