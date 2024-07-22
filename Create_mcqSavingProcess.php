<?php

include('database/connection.php');


$quizName = $_POST['quizName'];
$teacher_email = $_COOKIE['user_email'];

// handle file uploads
function uploadFile($file, $target_dir) {
    if ($file["error"] === UPLOAD_ERR_NO_FILE) {
        return ''; 
    } elseif ($file["error"] !== UPLOAD_ERR_OK) {
        echo "File upload error: " . $file["error"];
        return null; // Error uploading file
    }

    // Ensure target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory 
    }

    // Generate unique file name
    $target_file = $target_dir . basename($file["name"]);

    // Move uploaded file to target directory
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file; // Return uploaded file path
    } else {
        echo "Error moving file.";
        return null; // Failed to move file
    }
}



// Insert lesson (quiz) into the lesson table 
$sql = "INSERT INTO lesson (lesson_name, question_type, teacher_email) VALUES (?, 'MCQ' ,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $quizName, $teacher_email);

if ($stmt->execute()) {
    $lesson_id = $stmt->insert_id; // Get the last inserted ID
} else {
    echo "Error inserting lesson: " . $stmt->error;
    exit();
}

$sql_question = "INSERT INTO question (question_text, question_audio, lesson_id) 
                VALUES (?, ?, ?)";
$stmt_question = $conn->prepare($sql_question);
$stmt_question->bind_param("ssi", $questionText, $questionAudio, $lesson_id);

$sql_option = "INSERT INTO mcq_answer (question_id, answer_text, mcq_audio, is_correct) 
               VALUES (?, ?, ?, ?)";
$stmt_option = $conn->prepare($sql_option);
$stmt_option->bind_param("isss", $question_id, $optionText, $optionAudio, $isCorrect);

// loop through each question and insert into question table
foreach ($_POST as $key => $value) {
    if (strpos($key, 'question') !== false && !strpos($key, 'option')) {
        $questionText = $value;
        $questionNumber = str_replace('question', '', $key);

        // upload question audio if provided
        $questionAudioKey = 'questionAudio' . $questionNumber;
        $questionAudio = isset($_FILES[$questionAudioKey]) ? uploadFile($_FILES[$questionAudioKey], "src/question_audio/") : '';
        if ($questionAudio === null) {
            die("Failed to upload question audio for question $questionNumber");
        }

        // Insert question into question table 
        $stmt_question->execute();
        $question_id = $stmt_question->insert_id; // Get last inserted ID

        // Insert options into mcq_answer table
        for ($i = 1; $i <= 4; $i++) {
            $optionTextKey = "option${questionNumber}_$i";
            $optionAudioKey = "option${questionNumber}_{$i}_audio";

            $optionText = isset($_POST[$optionTextKey]) ? $_POST[$optionTextKey] : '';
            $optionAudio = isset($_FILES[$optionAudioKey]) ? uploadFile($_FILES[$optionAudioKey], "src/option_audio/") : '';

            // Check option audio is uploaded
            if ($optionAudio !== null) {
                $isCorrect = (isset($_POST["correctAnswer${questionNumber}"]) && $_POST["correctAnswer${questionNumber}"] == "option${questionNumber}_$i") ? 1 : 0;

                // Insert option into mcq_answer table 
                $stmt_option->execute();
            } else {
                echo "Option audio $optionAudioKey not found or empty.<br>";
            }
        }
    }
}

$stmt_question->close();
$stmt_option->close();
$conn->close();
header("Location: library_page.php");
exit();
?>
