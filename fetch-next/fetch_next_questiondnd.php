<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sdp_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current question ID from the request
$data = json_decode(file_get_contents("php://input"), true);
$currentQuestionId = $data['currentQuestionId'];

// Fetch the next question for lesson_id 4 (Drag and Drop)
$question_sql = "SELECT q.question_id, q.question_text, q.question_audio  
                 FROM question q 
                 WHERE q.lesson_id = 4 
                 AND q.question_id > ? 
                 ORDER BY q.question_id 
                 LIMIT 1";

$stmt = $conn->prepare($question_sql);
$stmt->bind_param("i", $currentQuestionId);
$stmt->execute();
$question_result = $stmt->get_result();

$response = [
    'question_text' => 'No more questions',
    'question_id' => null,
    'options' => [],
    'question_audio' => null
];

if ($question_result->num_rows > 0) {
    $question_row = $question_result->fetch_assoc();
    $nextQuestionId = $question_row['question_id'];
    $questionText = $question_row['question_text'];
    $questionAudio = $question_row['question_audio'];

    // Fetch options for the next question
    $options_sql = "SELECT drag_option_text, is_correct, blank_position 
                    FROM draggable_options 
                    WHERE question_id = ?";
                    
    $stmt = $conn->prepare($options_sql);
    $stmt->bind_param("i", $nextQuestionId);
    $stmt->execute();
    $options_result = $stmt->get_result();

    while ($option_row = $options_result->fetch_assoc()) {
        $response['options'][] = [
            'drag_option_text' => $option_row['drag_option_text'],
            'is_correct' => $option_row['is_correct'],
            'blank_position' => $option_row['blank_position']
        ];
    }

    $response['question_text'] = $questionText;
    $response['question_id'] = $nextQuestionId;
    $response['question_audio'] = $questionAudio ?: null; 
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
