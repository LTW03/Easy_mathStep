<?php
include('../database/connection.php');

$data = json_decode(file_get_contents('php://input'), true);
$currentQuestionId = $data['currentQuestionId'];
$allCorrect = $data['allCorrect'];

$response = [
    'showPopup' => false,
    'popupText' => '',
    'popupImg' => ''
];

if ($allCorrect) {
    $popup_sql = "SELECT word_text, img_path 
                  FROM words 
                  WHERE question_id = ? AND is_encouragement = 1";

    $stmt = $conn->prepare($popup_sql);
    $stmt->bind_param("i", $currentQuestionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['showPopup'] = true;
        $response['popupText'] = $row['word_text'] ?? 'Great job!';
        $response['popupImg'] = $row['img_path'] ?? '';
    }
} else {
    $popup_sql = "SELECT word_text, img_path, is_encouragement 
                  FROM words 
                  WHERE question_id = ?";

    $stmt = $conn->prepare($popup_sql);
    $stmt->bind_param("i", $currentQuestionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['is_encouragement'] == 0 && (!empty($row['word_text']) || !empty($row['img_path']))) {
            $response['showPopup'] = true;
            $response['popupText'] = $row['word_text'] ?? 'Try again!';
            $response['popupImg'] = $row['img_path'] ?? '';
        }
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
