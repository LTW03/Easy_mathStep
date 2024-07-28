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

?>