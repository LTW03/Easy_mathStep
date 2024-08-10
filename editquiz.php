<?php
include('database/connection.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// lesson_id
$lesson_id = isset($_POST['lesson_id']) ? $_POST['lesson_id'] : '';

// Get posted data
$question_ids = $_POST['question_id'] ?? [];
$question_texts = $_POST['question_text'] ?? [];
$question_audios = $_FILES['question_audio']['tmp_name'] ?? [];
$answer_texts = $_POST['answer_text'] ?? [];
$answer_audios = $_FILES['answer_audio']['tmp_name'] ?? [];
$is_encouragements = $_POST['is_encouragement'] ?? [];
$encouragement_texts = $_POST['encouragement_text'] ?? [];
$question_images = $_FILES['question_image']['tmp_name'] ?? [];

// Handle file uploads
$upload_dirs = [
    'option_audio' => './src/option_audio/',
    'question_audio' => './src/question_audio/',
    'encouragement_source' => './src/encouragement_source/'
];

// Create directories if they don't exist
foreach ($upload_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

function moveUploadedFile($fileArray, $index, $dir) {
    if (isset($fileArray['name'][$index]) && $fileArray['error'][$index] === UPLOAD_ERR_OK) {
        $tmp_name = $fileArray['tmp_name'][$index];
        $name = basename($fileArray['name'][$index]);
        $target_file = $dir . $name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            return $target_file;
        } else {
            echo "Failed to move uploaded file: $target_file<br>";
            return null;
        }
    } else {
        echo "Upload error or no file: " . $fileArray['error'][$index] . "<br>";
        return null;
    }
}

// Process audio and image files
$processed_question_audios = [];
foreach ($question_audios as $i => $tmp_name) {
    $processed_question_audios[$i] = moveUploadedFile($_FILES['question_audio'], $i, $upload_dirs['question_audio']);
}

$processed_answer_audios = [];
foreach ($answer_audios as $i => $tmp_name) {
    $processed_answer_audios[$i] = moveUploadedFile($_FILES['answer_audio'], $i, $upload_dirs['option_audio']);
}

$processed_question_images = [];
foreach ($question_images as $i => $tmp_name) {
    $processed_question_images[$i] = moveUploadedFile($_FILES['question_image'], $i, $upload_dirs['encouragement_source']);
}

// Update or insert questions
foreach ($question_ids as $i => $question_id) {
    $question_text = $question_texts[$i];
    $question_audio = $processed_question_audios[$i] ?? null;
    $encouragement_text = $encouragement_texts[$i] ?? '';
    $encouragement_image = $processed_question_images[$i] ?? null;
    $is_encouragement = isset($is_encouragements[$i]) && $is_encouragements[$i] == 'on' ? 1 : 0;

    if ($question_id) {
        // Update existing question
        $sql = "UPDATE question SET question_text = ?, question_audio = ? WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssi", $question_text, $question_audio, $question_id);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }

        // Update encouragement
        $sql = "UPDATE words SET word_text = ?, img_path = ?, is_encouragement = ? WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssii", $encouragement_text, $encouragement_image, $is_encouragement, $question_id);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }

        // Delete existing answers for the question
        $sql = "DELETE FROM mcq_answer WHERE question_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $question_id);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }
    } else {
        // Insert new question
        $sql = "INSERT INTO question (lesson_id, question_text, question_audio) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("iss", $lesson_id, $question_text, $question_audio);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }
        $question_id = $stmt->insert_id;

        // Insert encouragement
        $sql = "INSERT INTO words (question_id, word_text, img_path, is_encouragement) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("issi", $question_id, $encouragement_text, $encouragement_image, $is_encouragement);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }
    }

    // Insert answers
    for ($j = 0; $j < 4; $j++) {
        $answer_text = $answer_texts[$i * 4 + $j] ?? '';
        $answer_audio = $processed_answer_audios[$i * 4 + $j] ?? null;
        $correct_answer_key = $_POST['correctAnswer'][$i] ?? '';
        $expected_option = sprintf("option%d_%d", $i, $j + 1); 
        $is_correct = ($correct_answer_key === $expected_option) ? 1 : 0;

        $sql = "INSERT INTO mcq_answer (question_id, answer_text, mcq_audio, is_correct) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("issi", $question_id, $answer_text, $answer_audio, $is_correct);
        if (!$stmt->execute()) {
            die("Error executing query: " . $stmt->error);
        }
    }
}

// Remove questions
$deleted_question_ids = $_POST['deleted_question_ids'] ?? [];
foreach ($deleted_question_ids as $deleted_question_id) {
    // Delete question
    $sql = "DELETE FROM question WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $deleted_question_id);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    // Delete related answers
    $sql = "DELETE FROM mcq_answer WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $deleted_question_id);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    // Delete related encouragement words
    $sql = "DELETE FROM words WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $deleted_question_id);
    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
}

header("Location: library_page.php");
$conn->close();
?>
