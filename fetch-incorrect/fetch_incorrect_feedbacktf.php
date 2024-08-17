<?php
include('../database/connection.php');

$question_id = $_GET['question_id'];
$is_encouragement = $_GET['is_encouragement'];

$sql = "SELECT word_text, img_path 
        FROM words 
        WHERE question_id = ? AND is_encouragement = ? 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $question_id, $is_encouragement);
$stmt->execute();
$stmt->bind_result($word_text, $img_path);

$response = ["word_text" => "No feedback available", "img_path" => ""];

if ($stmt->fetch()) {
    if ($is_encouragement == 0 && (empty($word_text) && empty($img_path))) {
        
    } else {
        $response = ["word_text" => $word_text, "img_path" => $img_path];
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
