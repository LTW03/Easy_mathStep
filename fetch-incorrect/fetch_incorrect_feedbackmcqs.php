<?php
include('../database/connection.php');

$question_id = $_GET['question_id'];
$is_encouragement = $_GET['is_encouragement'];

// Fetch feedback for incorrect answers or encouragement
$feedback_sql = "SELECT word_text, img_path 
                 FROM words 
                 WHERE question_id = ? 
                 AND is_encouragement = ? 
                 LIMIT 1";

$stmt = $conn->prepare($feedback_sql);
$stmt->bind_param("ii", $question_id, $is_encouragement);
$stmt->execute();
$result = $stmt->get_result();

$response = ['word_text' => 'No feedback available', 'img_path' => ''];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['word_text'] = $row['word_text'];
    $response['img_path'] = $row['img_path'];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>

